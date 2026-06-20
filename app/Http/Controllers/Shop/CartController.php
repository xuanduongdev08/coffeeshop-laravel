<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Models\Modifier;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        $cart  = session('cart', []);
        $total = array_sum(array_map(fn($i) => $i['unit_price'] * $i['quantity'], $cart));

        return view('shop.cart.index', compact('cart', 'total'));
    }

    /**
     * Thêm sản phẩm vào giỏ hàng (hỗ trợ size + modifiers)
     */
    public function add(AddToCartRequest $request)
    {
        $product  = Product::with('sizes')->findOrFail($request->product_id);
        $quantity = (int) $request->get('quantity', 1);

        if (! $product->is_active) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không còn kinh doanh.']);
        }
        if ($product->stock < 1) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm đã hết hàng.']);
        }

        // Tính giá theo size
        $size      = $request->size;
        $basePrice = $product->has_size && $size
            ? $product->priceBySize($size)
            : $product->effective_price;

        // Tính phụ phí modifier
        $modifierIds   = $request->input('modifier_ids', []);
        $modifiers     = Modifier::whereIn('id', $modifierIds)->get();
        $modifierExtra = $modifiers->sum('extra_price');
        $unitPrice     = $basePrice + $modifierExtra;

        // Key duy nhất cho mỗi combination (product + size + modifiers)
        $modifierKey = implode('-', array_map('intval', $modifierIds));
        $key = 'p' . $product->id . '_' . ($size ?? 'ns') . '_' . ($modifierKey ?: '0');

        $cart = session('cart', []);

        if (isset($cart[$key])) {
            $newQty = $cart[$key]['quantity'] + $quantity;
            if ($newQty > $product->stock) {
                return response()->json(['success' => false, 'message' => 'Số lượng vượt quá tồn kho.']);
            }
            $cart[$key]['quantity'] = $newQty;
        } else {
            $cart[$key] = [
                'product_id'     => $product->id,
                'name'           => $product->name,
                'slug'           => $product->slug,
                'image'          => $product->image,
                'size'           => $size,
                'base_price'     => $basePrice,
                'modifier_extra' => $modifierExtra,
                'unit_price'     => $unitPrice,
                'price'          => $unitPrice, // alias cho tương thích
                'modifier_ids'   => $modifierIds,
                'modifier_names' => $modifiers->pluck('name')->join(', '),
                'quantity'       => $quantity,
            ];
        }

        session(['cart' => $cart]);

        $cartCount = array_sum(array_column($cart, 'quantity'));

        $label = $product->name;
        if ($size) $label .= " (Size {$size})";
        if ($modifiers->isNotEmpty()) $label .= ' + ' . $modifiers->pluck('name')->join(', ');

        return response()->json([
            'success'    => true,
            'message'    => "Đã thêm \"{$label}\" vào giỏ hàng.",
            'cart_count' => $cartCount,
        ]);
    }

    /**
     * Cập nhật số lượng
     */
    public function update(Request $request, string $rowId)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:100']);

        $cart = session('cart', []);

        if (! isset($cart[$rowId])) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ.']);
        }

        $product = Product::find($cart[$rowId]['product_id']);
        if ($product && $request->quantity > $product->stock) {
            return response()->json(['success' => false, 'message' => 'Số lượng vượt quá tồn kho.']);
        }

        $cart[$rowId]['quantity'] = $request->quantity;
        session(['cart' => $cart]);

        $unitPrice = $cart[$rowId]['unit_price'] ?? $cart[$rowId]['price'];
        $itemTotal = $unitPrice * $request->quantity;
        $cartTotal = array_sum(array_map(fn($i) => ($i['unit_price'] ?? $i['price']) * $i['quantity'], $cart));
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success'    => true,
            'item_total' => number_format($itemTotal, 0, ',', '.') . 'đ',
            'cart_total' => number_format($cartTotal, 0, ',', '.') . 'đ',
            'cart_count' => $cartCount,
        ]);
    }

    /**
     * Xóa một sản phẩm khỏi giỏ
     */
    public function remove(string $rowId)
    {
        $cart = session('cart', []);
        unset($cart[$rowId]);
        session(['cart' => $cart]);

        $cartTotal = array_sum(array_map(fn($i) => ($i['unit_price'] ?? $i['price']) * $i['quantity'], $cart));
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success'    => true,
            'cart_total' => number_format($cartTotal, 0, ',', '.') . 'đ',
            'cart_count' => $cartCount,
            'is_empty'   => empty($cart),
        ]);
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        session()->forget('cart');

        return redirect()->route('cart.index')
            ->with('success', 'Đã xóa toàn bộ giỏ hàng.');
    }
}
