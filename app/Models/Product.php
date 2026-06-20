<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, Sluggable, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'image',
        'stock',
        'is_active',
        'is_featured',
        // Modifier flags (Phase 2)
        'has_size',
        'has_topping',
        'allow_sugar',
        'allow_ice',
        'allow_milk',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_active'      => 'boolean',
        'is_featured'    => 'boolean',
        'has_size'       => 'boolean',
        'has_topping'    => 'boolean',
        'allow_sugar'    => 'boolean',
        'allow_ice'      => 'boolean',
        'allow_milk'     => 'boolean',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => ['source' => 'name'],
        ];
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class)->orderByRaw("FIELD(size,'M','L','XL')");
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeHasSize($query)
    {
        return $query->where('has_size', true);
    }

    public function scopeHasTopping($query)
    {
        return $query->where('has_topping', true);
    }

    // Accessors
    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->discount_price ?? $this->price);
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->effective_price, 0, ',', '.') . 'đ';
    }

    /**
     * Lấy giá theo size đã chọn.
     * Nếu sản phẩm không có size hoặc size không tồn tại, trả về effective_price.
     */
    public function priceBySize(string $size): float
    {
        if (! $this->has_size) {
            return $this->effective_price;
        }

        $sizeRecord = $this->sizes->firstWhere('size', $size);
        return $sizeRecord ? (float) $sizeRecord->price : $this->effective_price;
    }

    /**
     * Trả về mảng [size => price] cho dropdown chọn size trong giỏ hàng.
     */
    public function getSizePricesAttribute(): array
    {
        if (! $this->has_size) {
            return [];
        }

        return $this->sizes
            ->where('is_active', true)
            ->mapWithKeys(fn ($s) => [$s->size => (float) $s->price])
            ->toArray();
    }
}
