<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'discount_percent',
        'discount_amount',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'discount_percent' => 'decimal:2',
        'discount_amount'  => 'decimal:2',
        'starts_at'        => 'datetime',
        'ends_at'          => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }
}
