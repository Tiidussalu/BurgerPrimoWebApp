<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomBurger extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'total_price',
        'is_favorite',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'is_favorite' => 'boolean',
    ];

    // Status konstandid
    const STATUS_DRAFT    = 'draft';
    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'custom_burger_ingredient')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function calculateTotalPrice(): float
    {
        return $this->ingredients->sum(function ($ingredient) {
            return $ingredient->price * $ingredient->pivot->quantity;
        });
    }

    // Helper meetodid
    public function isPending(): bool  { return $this->status === self::STATUS_PENDING; }
    public function isApproved(): bool { return $this->status === self::STATUS_APPROVED; }
    public function isRejected(): bool { return $this->status === self::STATUS_REJECTED; }
    public function isDraft(): bool    { return $this->status === self::STATUS_DRAFT; }
}