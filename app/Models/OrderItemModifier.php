<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemModifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'modifier_id',
        'extra_price_snapshot',
    ];

    protected $casts = [
        'extra_price_snapshot' => 'decimal:2',
    ];

    // Relationships
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function modifier(): BelongsTo
    {
        return $this->belongsTo(Modifier::class);
    }

    // Accessor
    public function getFormattedPriceAttribute(): string
    {
        if ($this->extra_price_snapshot <= 0) {
            return 'Miễn phí';
        }
        return '+' . number_format($this->extra_price_snapshot, 0, ',', '.') . 'đ';
    }
}
