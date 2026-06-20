<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Sản phẩm nổi bật (is_featured = true)
        $featuredProducts = Product::with('category')
            ->active()
            ->featured()
            ->inStock()
            ->latest()
            ->take(8)
            ->get();

        // Sản phẩm mới nhất
        $newProducts = Product::with('category')
            ->active()
            ->inStock()
            ->latest()
            ->take(4)
            ->get();

        // Banner trang chủ
        $banners = Banner::active()
            ->where('position', 'home')
            ->orderBy('sort_order')
            ->get();

        // Danh mục
        $categories = Category::orderBy('sort_order')->get();

        return view('shop.home', compact(
            'featuredProducts',
            'newProducts',
            'banners',
            'categories'
        ));
    }
}
