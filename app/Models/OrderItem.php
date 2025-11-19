<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'custom_burger_id',
        'burger_name',
        'ingredients',
        'price',
        'quantity',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'price' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customBurger(): BelongsTo
    {
        return $this->belongsTo(CustomBurger::class);
    }
}