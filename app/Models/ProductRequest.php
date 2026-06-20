<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'request_count',
        'sample_query',
        'status',
        'last_requested_at',
    ];

    protected $casts = [
        'last_requested_at' => 'datetime',
    ];

    // Tăng request_count nếu đã tồn tại, tạo mới nếu chưa có
    public static function logRequest(string $productName, string $query): self
    {
        $record = self::where('product_name', $productName)->first();

        if ($record) {
            $record->increment('request_count');
            $record->update(['last_requested_at' => now(), 'sample_query' => $query]);
            return $record;
        }

        return self::create([
            'product_name'      => $productName,
            'sample_query'      => $query,
            'last_requested_at' => now(),
        ]);
    }
}
