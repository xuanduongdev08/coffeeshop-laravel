<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Modifier;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Danh sách sản phẩm (có tìm kiếm + lọc danh mục)
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->active();

        // Tìm kiếm — chỉ theo tên sản phẩm và tên danh mục, KHÔNG search description
        // để tránh false-positive (vd: Bánh Tiramisu có "cà phê" trong description)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        // Sắp xếp
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name'       => $query->orderBy('name', 'asc'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('sort_order')->get();

        $currentCategory = $request->filled('category')
            ? Category::where('slug', $request->category)->first()
            : null;

        return view('shop.products.index', compact('products', 'categories', 'currentCategory', 'sort'));
    }

    /**
     * Chi tiết sản phẩm
     */
    public function show(Product $product)
    {
        if (! $product->is_active) {
            abort(404);
        }

        $product->load(['category', 'reviews.user', 'sizes']);

        // Sản phẩm liên quan (cùng danh mục)
        $relatedProducts = Product::with('category')
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inStock()
            ->take(4)
            ->get();

        // Load modifiers phù hợp với loại sản phẩm
        $modifiers = collect();
        if ($product->has_size || $product->has_topping || $product->allow_sugar || $product->allow_ice || $product->allow_milk) {
            $isJuice = $product->has_topping && !$product->allow_milk;

            $modifiers = Modifier::where('is_active', true)
                ->where(function ($q) use ($product, $isJuice) {
                    if ($isJuice) {
                        // Trà/nước trái cây: chỉ lấy modifier applies_to_tea_juice
                        $q->where('applies_to_tea_juice', true);
                    } else {
                        // Đồ uống dùng ly: chỉ lấy modifier applies_to_drink
                        $q->where('applies_to_drink', true);
                        // Thêm topping nếu có
                        if ($product->has_topping) {
                            $q->orWhere('applies_to_tea_juice', true);
                        }
                    }
                })
                ->when(!$product->allow_sugar, fn($q) => $q->where('type', '!=', 'sugar'))
                ->when(!$product->allow_ice,   fn($q) => $q->where('type', '!=', 'ice'))
                ->when(!$product->allow_milk,  fn($q) => $q->where('type', '!=', 'milk'))
                ->when(!$product->has_topping, fn($q) => $q->where('type', '!=', 'topping'))
                ->orderBy('sort_order')
                ->get()
                ->groupBy('type');
        }

        return view('shop.products.show', compact('product', 'relatedProducts', 'modifiers'));
    }

    /**
     * Sản phẩm theo danh mục
     */
    public function byCategory(Category $category)
    {
        $products = Product::with('category')
            ->active()
            ->where('category_id', $category->id)
            ->latest()
            ->paginate(12);

        $categories = Category::orderBy('sort_order')->get();

        return view('shop.products.index', [
            'products'        => $products,
            'categories'      => $categories,
            'currentCategory' => $category,
            'sort'            => 'latest',
        ]);
    }
}
