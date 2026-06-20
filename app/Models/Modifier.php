<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Modifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'extra_price',
        'applies_to_drink',
        'applies_to_tea_juice',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'extra_price'          => 'decimal:2',
        'applies_to_drink'     => 'boolean',
        'applies_to_tea_juice' => 'boolean',
        'is_active'            => 'boolean',
    ];

    /**
     * Nhãn hiển thị cho từng loại modifier.
     */
    public static array $typeLabels = [
        'sugar'   => 'Đường',
        'ice'     => 'Đá',
        'milk'    => 'Loại sữa',
        'topping' => 'Topping',
    ];

    // Relationships
    public function orderItems(): BelongsToMany
    {
        return $this->belongsToMany(OrderItem::class, 'order_item_modifiers')
                    ->withPivot('extra_price_snapshot')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeForDrink($query)
    {
        return $query->where('applies_to_drink', true)->where('is_active', true);
    }

    public function scopeForTeaJuice($query)
    {
        return $query->where('applies_to_tea_juice', true)->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getTypeLabelAttribute(): string
    {
        return self::$typeLabels[$this->type] ?? $this->type;
    }

    public function getFormattedExtraPriceAttribute(): string
    {
        if ($this->extra_price <= 0) {
            return 'Miễn phí';
        }
        return '+' . number_format($this->extra_price, 0, ',', '.') . 'đ';
    }

    public function getIsFreeAttribute(): bool
    {
        return $this->extra_price <= 0;
    }
}
