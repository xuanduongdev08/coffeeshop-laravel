<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request validation cho đặt hàng.
 */
class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'recipient_name'   => ['required', 'string', 'max:100'],
            'province'         => ['required', 'string', 'max:200'],
            'province_code'    => ['nullable', 'numeric'],
            'district'         => ['required', 'string', 'max:200'],
            'ward'             => ['required', 'string', 'max:200'],
            'street_address'   => ['required', 'string', 'max:300'],
            'phone'            => ['required', 'regex:/^[0-9]{10,11}$/'],
            'notes'            => ['nullable', 'string', 'max:500'],
            'coupon_code'      => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'recipient_name.required'   => 'Vui lòng nhập tên người nhận.',
            'recipient_name.max'        => 'Tên người nhận không được quá 100 ký tự.',
            'province.required'         => 'Vui lòng chọn Tỉnh / Thành phố.',
            'district.required'         => 'Vui lòng chọn Quận / Huyện.',
            'ward.required'             => 'Vui lòng chọn Phường / Xã.',
            'street_address.required'   => 'Vui lòng nhập số nhà, tên đường.',
            'street_address.max'        => 'Địa chỉ không được quá 300 ký tự.',
            'phone.required'            => 'Vui lòng nhập số điện thoại.',
            'phone.regex'               => 'Số điện thoại không hợp lệ (10-11 chữ số).',
            'notes.max'                 => 'Ghi chú không được quá 500 ký tự.',
        ];
    }
}
