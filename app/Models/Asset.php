<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'brand',
        'stock_total',
        'stock_available',
        'condition',
        'rent_fee',
        'description',
        'image_url',
    ];

    protected $appends = ['image_source'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function items()
    {
        return $this->hasMany(BorrowingItem::class);
    }

    public function getImageSourceAttribute(): ?string
    {
        $rawImage = $this->getRawOriginal('image_url');

        if (! $rawImage) {
            return null;
        }

        if (! Storage::disk('public')->exists($rawImage)) {
            return null;
        }

        return Storage::url($rawImage);
    }

    public static function synchronizeStockAvailability(): void
    {
        $borrowedMap = BorrowingItem::query()
            ->selectRaw('asset_id, SUM(quantity) as borrowed_qty')
            ->where('status', 'borrowed')
            ->groupBy('asset_id')
            ->pluck('borrowed_qty', 'asset_id');

        self::query()
            ->select(['id', 'stock_total', 'stock_available'])
            ->chunkById(100, function (Collection $assets) use ($borrowedMap): void {
                foreach ($assets as $asset) {
                    $borrowedQty = (int) ($borrowedMap[$asset->id] ?? 0);
                    $available = max(0, (int) $asset->stock_total - $borrowedQty);

                    if ((int) $asset->stock_available !== $available) {
                        self::query()->whereKey($asset->id)->update(['stock_available' => $available]);
                    }
                }
            });
    }
}