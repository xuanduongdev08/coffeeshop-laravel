<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_image',
        'size',
        'base_price',
        'modifier_extra',
        'unit_price',
        'price',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'base_price'     => 'decimal:2',
        'modifier_extra' => 'decimal:2',
        'unit_price'     => 'decimal:2',
        'subtotal'       => 'decimal:2',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function modifiers(): BelongsToMany
    {
        return $this->belongsToMany(Modifier::class, 'order_item_modifiers')
                    ->withPivot('extra_price_snapshot')
                    ->withTimestamps();
    }

    public function modifierRecords(): HasMany
    {
        return $this->hasMany(OrderItemModifier::class);
    }

    // Helpers
    /**
     * Tính lại đơn giá cuối dựa trên base_price + modifier_extra.
     */
    public function calculateUnitPrice(): float
    {
        return (float) $this->base_price + (float) $this->modifier_extra;
    }

    /**
     * Trả về tên size hiển thị thân thiện.
     */
    public function getSizeLabelAttribute(): string
    {
        return ProductSize::$labels[$this->size] ?? ($this->size ?? '');
    }

    /**
     * Tóm tắt các modifier đã chọn (dùng hiển thị trong hóa đơn).
     */
    public function getModifierSummaryAttribute(): string
    {
        return $this->modifiers->pluck('name')->join(', ');
    }

    public function getFormattedUnitPriceAttribute(): string
    {
        $price = $this->unit_price > 0 ? $this->unit_price : $this->price;
        return number_format($price, 0, ',', '.') . 'đ';
    }
}
