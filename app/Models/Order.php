<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'customer_notes',
        'admin_notes',
        'confirmed_at',
        'confirmed_by',
        'delivery_method',
        'payment_intent_id',
        'payment_status',
        'payment_method',
        'paid_at',
        'courier_lat',
        'courier_lng',
        'courier_updated_at',
        'courier_token',
        'delivery_lat',
        'delivery_lng',
        'delivery_address',
        'courier_user_id',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'paid_at' => 'datetime',
        'courier_lat' => 'float',
        'courier_lng' => 'float',
        'courier_updated_at' => 'datetime',
        'delivery_lat' => 'float',
        'delivery_lng' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function courierUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'courier_user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Generate unique order number
    public static function generateOrderNumber(): string
    {
        do {
            $number = 'BP-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('order_number', $number)->exists());

        return $number;
    }

    // Confirm order
    public function confirm(int $adminId): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => $adminId,
        ]);
    }
}