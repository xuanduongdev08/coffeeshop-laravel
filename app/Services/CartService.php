<?php

namespace App\Services;

use App\Models\Modifier;
use App\Models\Product;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartService
{
    /**
     * Tính đơn giá cuối cho 1 sản phẩm dựa trên size và modifier đã chọn.
     *
     * @param  Product  $product
     * @param  string|null  $size    Null nếu sản phẩm không có size (bánh, hạt cà phê)
     * @param  array  $modifierIds   Danh sách ID modifier đã chọn
     * @return array  [base_price, modifier_extra, unit_price, modifiers]
     */
    public function calculateItemPrice(
        Product $product,
        ?string $size,
        array $modifierIds = []
    ): array {
        // 1. Giá gốc theo size (nếu có)
        $basePrice = ($product->has_size && $size)
            ? $product->priceBySize($size)
            : $product->effective_price;

        // 2. Tổng phụ phí modifier
        $modifiers  = Modifier::whereIn('id', $modifierIds)->where('is_active', true)->get();
        $extraTotal = $modifiers->sum('extra_price');

        return [
            'base_price'     => $basePrice,
            'modifier_extra' => $extraTotal,
            'unit_price'     => $basePrice + $extraTotal,
            'modifiers'      => $modifiers,
        ];
    }

    /**
     * Thêm sản phẩm vào giỏ hàng (session) với đầy đủ thông tin modifier.
     *
     * Dùng Cart facade từ darryldecode/cart.
     * cartId = "product_id_size_mod1-mod2" để phân biệt cùng sản phẩm khác lựa chọn.
     */
    public function addToCart(
        Product $product,
        int $quantity,
        ?string $size,
        array $modifierIds = []
    ): void {
        $priceData = $this->calculateItemPrice($product, $size, $modifierIds);

        // Tạo cartId độc nhất dựa trên sản phẩm + size + modifier
        sort($modifierIds);
        $cartId = $product->id . '_' . ($size ?? 'nosize') . '_' . implode('-', $modifierIds);

        Cart::add([
            'id'         => $cartId,
            'name'       => $product->name,
            'price'      => $priceData['unit_price'],
            'quantity'   => $quantity,
            'attributes' => [
                'product_id'     => $product->id,
                'slug'           => $product->slug,
                'size'           => $size,
                'size_label'     => $size ? (ProductSize::$labels[$size] ?? $size) : null,
                'base_price'     => $priceData['base_price'],
                'modifier_extra' => $priceData['modifier_extra'],
                'modifier_ids'   => $modifierIds,
                'modifier_names' => $priceData['modifiers']->pluck('name')->join(', '),
                'image'          => $product->image,
            ],
        ]);
    }

    /**
     * Lấy tất cả modifier còn lại sau khi lọc theo loại sản phẩm.
     * Dùng để render UI trong trang chi tiết sản phẩm.
     *
     * @param  Product  $product
     * @return array  [sugar => [...], ice => [...], milk => [...], topping => [...]]
     */
    public function getAvailableModifiers(Product $product): array
    {
        $result = [];

        if ($product->allow_sugar) {
            $result['sugar'] = Modifier::ofType('sugar')
                ->when($product->has_topping, fn ($q) => $q->forTeaJuice(), fn ($q) => $q->forDrink())
                ->active()->get();
        }

        if ($product->allow_ice) {
            $result['ice'] = Modifier::ofType('ice')
                ->when($product->has_topping, fn ($q) => $q->forTeaJuice(), fn ($q) => $q->forDrink())
                ->active()->get();
        }

        if ($product->allow_milk) {
            $result['milk'] = Modifier::ofType('milk')->forDrink()->active()->get();
        }

        if ($product->has_topping) {
            $result['topping'] = Modifier::ofType('topping')->forTeaJuice()->active()->get();
        }

        return $result;
    }
}
