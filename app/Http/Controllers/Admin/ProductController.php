<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->withTrashed();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('deleted_at')->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->whereNull('deleted_at')->where('is_active', false);
            } elseif ($request->status === 'deleted') {
                $query->onlyTrashed();
            }
        }

        $products   = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'nullable|exists:categories,id',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'discount_price'=> 'nullable|numeric|min:0|lt:price',
            'stock'         => 'required|integer|min:0',
            'is_active'     => 'boolean',
            'is_featured'   => 'boolean',
            'has_topping'   => 'boolean',
            'allow_sugar'   => 'boolean',
            'allow_ice'     => 'boolean',
            'allow_milk'    => 'boolean',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            // Sizes
            'has_size_m'    => 'boolean',
            'has_size_l'    => 'boolean',
            'has_size_xl'   => 'boolean',
            'price_m'       => 'required_if:has_size_m,1|nullable|numeric|min:0',
            'price_l'       => 'required_if:has_size_l,1|nullable|numeric|min:0',
            'price_xl'      => 'required_if:has_size_xl,1|nullable|numeric|min:0',
        ]);

        // Upload ảnh
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $data['is_active']   = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['has_topping'] = $request->boolean('has_topping');
        $data['allow_sugar'] = $request->boolean('allow_sugar', true);
        $data['allow_ice']   = $request->boolean('allow_ice', true);
        $data['allow_milk']  = $request->boolean('allow_milk');

        $hasSizeM = $request->boolean('has_size_m');
        $hasSizeL = $request->boolean('has_size_l');
        $hasSizeXL = $request->boolean('has_size_xl');
        $data['has_size'] = $hasSizeM || $hasSizeL || $hasSizeXL;

        $product = Product::create($data);

        // Lưu sizes nếu has_size
        if ($data['has_size']) {
            if ($hasSizeM && isset($data['price_m'])) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size'       => 'M',
                    'price'      => $data['price_m'],
                    'is_active'  => true,
                ]);
            }
            if ($hasSizeL && isset($data['price_l'])) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size'       => 'L',
                    'price'      => $data['price_l'],
                    'is_active'  => true,
                ]);
            }
            if ($hasSizeXL && isset($data['price_xl'])) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size'       => 'XL',
                    'price'      => $data['price_xl'],
                    'is_active'  => true,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', "Đã thêm sản phẩm \"{$product->name}\" thành công.");
    }

    public function edit(Product $product)
    {
        $product->load('sizes');
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if (auth()->user()->hasRole('warehouse')) {
            $data = $request->validate([
                'stock' => 'required|integer|min:0',
            ]);
            $product->update(['stock' => $data['stock']]);
            return redirect()->route('admin.products.index')
                ->with('success', "Đã cập nhật tồn kho sản phẩm \"{$product->name}\" thành {$data['stock']} thành công.");
        }

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'nullable|exists:categories,id',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'discount_price'=> 'nullable|numeric|min:0|lt:price',
            'stock'         => 'required|integer|min:0',
            'is_active'     => 'boolean',
            'is_featured'   => 'boolean',
            'has_topping'   => 'boolean',
            'allow_sugar'   => 'boolean',
            'allow_ice'     => 'boolean',
            'allow_milk'    => 'boolean',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            // Sizes
            'has_size_m'    => 'boolean',
            'has_size_l'    => 'boolean',
            'has_size_xl'   => 'boolean',
            'price_m'       => 'required_if:has_size_m,1|nullable|numeric|min:0',
            'price_l'       => 'required_if:has_size_l,1|nullable|numeric|min:0',
            'price_xl'      => 'required_if:has_size_xl,1|nullable|numeric|min:0',
        ]);

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($product->image && str_starts_with($product->image, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $product->image));
            }
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $data['is_active']   = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['has_topping'] = $request->boolean('has_topping');
        $data['allow_sugar'] = $request->boolean('allow_sugar');
        $data['allow_ice']   = $request->boolean('allow_ice');
        $data['allow_milk']  = $request->boolean('allow_milk');

        $hasSizeM = $request->boolean('has_size_m');
        $hasSizeL = $request->boolean('has_size_l');
        $hasSizeXL = $request->boolean('has_size_xl');
        $data['has_size'] = $hasSizeM || $hasSizeL || $hasSizeXL;

        $product->update($data);

        // Cập nhật sizes
        $product->sizes()->delete();
        if ($data['has_size']) {
            if ($hasSizeM && isset($data['price_m'])) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size'       => 'M',
                    'price'      => $data['price_m'],
                    'is_active'  => true,
                ]);
            }
            if ($hasSizeL && isset($data['price_l'])) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size'       => 'L',
                    'price'      => $data['price_l'],
                    'is_active'  => true,
                ]);
            }
            if ($hasSizeXL && isset($data['price_xl'])) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size'       => 'XL',
                    'price'      => $data['price_xl'],
                    'is_active'  => true,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', "Đã cập nhật sản phẩm \"{$product->name}\".");
    }

    public function destroy(Product $product)
    {
        $product->delete(); // SoftDelete
        return back()->with('success', "Đã xóa sản phẩm \"{$product->name}\".");
    }

    public function restore(int $id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        return back()->with('success', "Đã khôi phục sản phẩm \"{$product->name}\".");
    }
}
