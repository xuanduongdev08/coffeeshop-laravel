<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Lưu đánh giá sản phẩm
     */
    public function store(StoreReviewRequest $request, Product $product)
    {

        // Kiểm tra đã đánh giá chưa
        $existing = Review::where('product_id', $product->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->with('warning', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('reviews', 'public');
            $imagePath = 'storage/' . $path;
        }

        Review::create([
            'product_id'  => $product->id,
            'user_id'     => auth()->id(),
            'rating'      => $request->rating,
            'comment'     => $request->comment,
            'image'       => $imagePath,
            'is_approved' => true,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
}
