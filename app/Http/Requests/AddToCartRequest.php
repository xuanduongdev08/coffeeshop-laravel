<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request validation cho thêm vào giỏ hàng.
 * Thay thế validation inline trong CartController::add()
 */
class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Không cần đăng nhập để thêm vào giỏ
    }

    public function rules(): array
    {
        return [
            'product_id'     => ['required', 'integer', 'exists:products,id'],
            'quantity'       => ['integer', 'min:1', 'max:100'],
            'size'           => ['nullable', 'in:M,L,XL'],
            'modifier_ids'   => ['nullable', 'array'],
            'modifier_ids.*' => ['integer', 'exists:modifiers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Sản phẩm không hợp lệ.',
            'product_id.exists'   => 'Sản phẩm không tồn tại.',
            'quantity.min'        => 'Số lượng tối thiểu là 1.',
            'quantity.max'        => 'Số lượng tối đa là 100.',
            'size.in'             => 'Size không hợp lệ. Chọn M, L hoặc XL.',
            'modifier_ids.*.exists' => 'Modifier không hợp lệ.',
        ];
    }
}
