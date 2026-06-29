<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Modifier;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemModifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Trang xác nhận đặt hàng (checkout)
     */
    public function checkout()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('warning', 'Giỏ hàng của bạn đang trống.');
        }

        $subtotal = array_sum(array_map(fn($i) => ($i['unit_price'] ?? $i['price']) * $i['quantity'], $cart));
        $user     = auth()->user();

        return view('shop.orders.checkout', compact('cart', 'subtotal', 'user'));
    }

    /**
     * Lưu đơn hàng
     */
    public function store(StoreOrderRequest $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('warning', 'Giỏ hàng trống, không thể đặt hàng.');
        }

        // Ghép địa chỉ đầy đủ từ các trường dropdown
        $shippingAddress = implode(', ', array_filter([
            trim($request->street_address),
            trim($request->ward),
            trim($request->district),
            trim($request->province),
        ]));

        // Tính phí ship: nội thành TP.HCM (province_code = 79) → 15.000đ, còn lại → 25.000đ
        $isHCM       = (int) $request->input('province_code', 0) === 79;
        $shippingFee = $isHCM ? 15000 : 25000;

        // Tính subtotal dùng unit_price (đã bao gồm modifier)
        $subtotal = array_sum(array_map(fn($i) => ($i['unit_price'] ?? $i['price']) * $i['quantity'], $cart));
        $total    = $subtotal + $shippingFee;

        DB::beginTransaction();
        try {
            // Kiểm tra tồn kho trước khi đặt hàng
            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    throw new \Exception("Sản phẩm \"{$item['name']}\" không tồn tại.");
                }
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Sản phẩm \"{$product->name}\" chỉ còn {$product->stock} sản phẩm trong kho, không đủ cho số lượng {$item['quantity']} bạn yêu cầu.");
                }
            }
            $order = Order::create([
                'user_id'          => auth()->id(),
                'recipient_name'   => $request->recipient_name,
                'shipping_address' => $shippingAddress,
                'phone'            => $request->phone,
                'subtotal'         => $subtotal,
                'shipping_fee'     => $shippingFee,
                'total'            => $total,
                'payment_method'   => 'COD',
                'payment_status'   => 'pending',
                'status'           => 'Chờ xử lý',
                'notes'            => $request->notes,
            ]);

            $hasDrink = false;

            foreach ($cart as $item) {
                $orderItem = OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['product_id'],
                    'product_name' => $item['name'],
                    'product_image'=> $item['image'] ?? null,
                    'size'         => $item['size'] ?? null,
                    'base_price'   => $item['base_price'] ?? $item['price'],
                    'modifier_extra' => $item['modifier_extra'] ?? 0,
                    'unit_price'   => $item['unit_price'] ?? $item['price'],
                    'price'        => $item['unit_price'] ?? $item['price'],
                    'quantity'     => $item['quantity'],
                    'subtotal'     => ($item['unit_price'] ?? $item['price']) * $item['quantity'],
                ]);

                // Trừ kho hàng
                Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);

                // Lưu modifiers đã chọn vào order_item_modifiers
                if (! empty($item['modifier_ids'])) {
                    $modifiers = Modifier::whereIn('id', $item['modifier_ids'])->get();
                    foreach ($modifiers as $modifier) {
                        OrderItemModifier::create([
                            'order_item_id'        => $orderItem->id,
                            'modifier_id'          => $modifier->id,
                            'extra_price_snapshot' => $modifier->extra_price,
                        ]);
                    }
                }

                // Đơn có size = đồ uống dùng ly → cần theo dõi pha chế
                if (! empty($item['size'])) {
                    $hasDrink = true;
                }
            }

            // Set drink_status nếu đơn có đồ uống
            if ($hasDrink) {
                $order->update(['drink_status' => 'pending']);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('payment.index', $order);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order store error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại.');
        }
    }

    /**
     * Lịch sử đơn hàng
     */
    public function history()
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('shop.orders.history', compact('orders'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function show(Order $order)
    {
        $order->load(['items.modifiers']);
        return view('shop.orders.show', compact('order'));
    }

    /**
     * Hủy đơn hàng
     */
    public function cancel(Order $order, Request $request)
    {
        if ($order->status !== 'Chờ xử lý') {
            return back()->with('error', 'Chỉ có thể hủy đơn hàng đang ở trạng thái "Chờ xử lý".');
        }

        $order->update([
            'status' => 'Đã hủy',
            'cancel_reason' => $request->input('cancel_reason')
        ]);

        // Hoàn kho hàng khi hủy đơn
        $order->load('items');
        foreach ($order->items as $item) {
            Product::where('id', $item->product_id)->increment('stock', $item->quantity);
        }

        return back()->with('success', 'Đã hủy đơn hàng #' . $order->tracking_code . ' thành công.');
    }

    /**
     * Kiểm tra trạng thái đơn hàng (AJAX Polling)
     */
    public function checkUpdates()
    {
        if (!auth()->check()) {
            return response()->json([]);
        }

        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get(['id', 'tracking_code', 'status', 'drink_status']);

        return response()->json($orders);
    }
}
