<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    /**
     * Danh sách các email template.
     */
    public function index()
    {
        $templates = EmailTemplate::all();
        return view('admin.email-templates.index', compact('templates'));
    }

    /**
     * Xem trước email template dưới dạng HTML đã compile.
     */
    public function preview(EmailTemplate $emailTemplate)
    {
        $mockPlaceholders = [
            '{customer_name}'      => 'XDuong Nguyen',
            '{customer_email}'     => 'djxuanduong01@gmail.com',
            '{website_link}'       => route('home'),
            '{order_code}'         => 'XD00022',
            '{order_status}'       => 'Đang giao hàng',
            '{drink_status_label}' => 'Đang pha chế',
            '{shipping_address}'   => '99 Lê Niệm, Tân Phú, Phường Tân Hòa, TP. Buôn Ma Thuột, Đắk Lắk',
            '{total_price}'        => '75.000đ',
            '{order_link}'         => '#',
            '{recipient_name}'     => 'XDuong Nguyen',
            '{phone}'              => '0977888544',
            '{payment_method}'     => 'Thanh toán khi nhận hàng (COD)',
            '{items_list}'         => '<table class="order-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th style="text-align:center; width:60px;">SL</th>
                        <th style="text-align:right; width:100px;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="product-name-cell">
                                <img src="' . asset('images/menu-1.jpg') . '" class="product-img">
                                <span>Cà phê Americano</span>
                            </div>
                        </td>
                        <td style="text-align:center;">1</td>
                        <td style="text-align:right;">50.000đ</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="product-name-cell">
                                <img src="' . asset('images/menu-2.jpg') . '" class="product-img">
                                <span>Bánh Mì Sài Gòn</span>
                            </div>
                        </td>
                        <td style="text-align:center;">1</td>
                        <td style="text-align:right;">25.000đ</td>
                    </tr>
                </tbody>
            </table>',
            '{extra_note}'         => 'Cửa hàng đang ưu tiên chuẩn bị đơn hàng của bạn.',
        ];

        $renderedContent = $emailTemplate->render($mockPlaceholders);

        return view('emails.layout', [
            'content' => $renderedContent,
            'subject' => $emailTemplate->renderSubject($mockPlaceholders)
        ]);
    }

    /**
     * Form chỉnh sửa template.
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        // Phân tích các placeholders khả dụng dựa trên template_key
        $placeholders = [];
        if ($emailTemplate->template_key === 'register_success') {
            $placeholders = ['{customer_name}', '{customer_email}', '{website_link}'];
        } elseif ($emailTemplate->template_key === 'order_status_updated') {
            $placeholders = ['{customer_name}', '{order_code}', '{order_status}', '{shipping_address}', '{total_price}', '{order_link}'];
        } elseif ($emailTemplate->template_key === 'drink_status_updated') {
            $placeholders = ['{customer_name}', '{order_code}', '{drink_status_label}', '{extra_note}', '{order_link}'];
        }

        return view('admin.email-templates.edit', compact('emailTemplate', 'placeholders'));
    }

    /**
     * Cập nhật template.
     */
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $emailTemplate->update($data);

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Đã cập nhật mẫu email thành công.');
    }
}
