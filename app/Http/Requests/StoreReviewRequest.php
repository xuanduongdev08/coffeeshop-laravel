<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request validation cho đánh giá sản phẩm.
 * Thay thế validation inline trong ReviewController::store()
 */
class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required'  => 'Vui lòng chọn số sao đánh giá.',
            'rating.min'       => 'Đánh giá tối thiểu 1 sao.',
            'rating.max'       => 'Đánh giá tối đa 5 sao.',
            'comment.required' => 'Vui lòng nhập nhận xét.',
            'comment.min'      => 'Nhận xét phải có ít nhất 10 ký tự.',
            'comment.max'      => 'Nhận xét không được quá 1000 ký tự.',
        ];
    }
}
