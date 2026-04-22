<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        if (Str::startsWith($rawImage, ['http://', 'https://', '/'])) {
            return $rawImage;
        }

        return Storage::url($rawImage);
    }
}