<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrowing_code',
        'user_id',
        'approved_by',
        'returned_by',
        'borrowed_at',
        'due_at',
        'returned_at',
        'status',
        'purpose',
        'total_fine',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'borrowed_at' => 'datetime',
            'due_at' => 'datetime',
            'returned_at' => 'datetime',
            'total_fine' => 'decimal:2',
        ];
    }

    public static function generateCode(): string
    {
        return 'PMJ-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items()
    {
        return $this->hasMany(BorrowingItem::class);
    }

    public function payment()
    {
        return $this->hasOne(FinePayment::class);
    }
}