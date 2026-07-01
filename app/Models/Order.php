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
        'recipient_name',
        'shipping_address',
        'phone',
        'subtotal',
        'shipping_fee',
        'total',
        'payment_method',
        'payment_status',
        'status',
        'tracking_code',
        'notes',
        'cancel_reason',
        // Drink status (Phase 2)
        'drink_status',
        'brewing_at',
        'completed_at',
    ];

    protected $casts = [
        'subtotal'     => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total'        => 'decimal:2',
        'brewing_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Tự động tạo tracking_code khi tạo đơn hàng (VD: XD00001)
    protected static function booted(): void
    {
        static::created(function (Order $order) {
            $order->tracking_code = 'XD' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
            $order->saveQuietly();
        });
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Chờ xử lý');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Lọc các đơn có đồ uống đang trong hàng chờ pha chế.
     */
    public function scopeDrinkInProgress($query)
    {
        return $query->whereIn('drink_status', ['pending', 'brewing']);
    }

    public function scopeByDrinkStatus($query, string $status)
    {
        return $query->where('drink_status', $status);
    }

    // Accessors
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 0, ',', '.') . 'đ';
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'Chờ xử lý' => 'warning',
            'Đang giao'  => 'info',
            'Hoàn thành' => 'success',
            'Đã hủy'     => 'danger',
            default      => 'secondary',
        };
    }

    /**
     * Nhãn hiển thị cho drink_status (dùng trong header thông báo).
     */
    public function getDrinkStatusLabelAttribute(): string
    {
        return match ($this->drink_status) {
            'pending'   => '✅ Đã nhận đơn',
            'brewing'   => '☕ Đang pha chế',
            'completed' => '🎉 Đã pha chế xong',
            default     => '',
        };
    }

    /**
     * Kiểm tra đơn hàng có sản phẩm đồ uống hay không.
     */
    public function getHasDrinkAttribute(): bool
    {
        return $this->drink_status !== null;
    }

    /**
     * Bước tiếp theo của drink_status.
     */
    public function getNextDrinkStatusAttribute(): ?string
    {
        if ($this->status === 'Đã hủy') {
            return null;
        }

        return match ($this->drink_status) {
            'pending'   => 'brewing',
            'brewing'   => 'completed',
            default     => null,
        };
    }
}
