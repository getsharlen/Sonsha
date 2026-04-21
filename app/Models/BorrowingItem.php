<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrowing_id',
        'asset_id',
        'quantity',
        'unit_fee',
        'fine_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'unit_fee' => 'decimal:2',
            'fine_amount' => 'decimal:2',
        ];
    }

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}