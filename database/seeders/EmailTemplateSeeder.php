<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'template_key' => 'register_success',
                'subject' => 'Chào mừng bạn đến với XDTHECOFFEEHOUSE!',
                'content' => '<h3>Chào {customer_name},</h3>
<p>Cảm ơn bạn đã đăng ký tài khoản thành công tại <strong>XDTHECOFFEEHOUSE</strong>.</p>
<p>Tài khoản đăng nhập của bạn là: <strong>{customer_email}</strong></p>
<p>Vui lòng bảo mật mật khẩu của bạn khi sử dụng hệ thống.</p>
<p><a href="{website_link}" style="background-color: #6f4e37; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Ghé thăm cửa hàng</a></p>
<br>
<p>Trân trọng,<br>Đội ngũ XDTHECOFFEEHOUSE</p>',
                'description' => 'Gửi khi khách hàng đăng ký tài khoản thành công.',
            ],
            [
                'template_key' => 'order_status_updated',
                'subject' => 'Cập nhật trạng thái đơn hàng #{order_code}',
                'content' => '<h3>Chào {customer_name},</h3>
<p>Đơn hàng <strong>#{order_code}</strong> của bạn đã thay đổi trạng thái sang: <strong>{order_status}</strong>.</p>
<p>Địa chỉ giao hàng: {shipping_address}</p>
<p>Tổng giá trị đơn hàng: <strong>{total_price}</strong></p>
<p><a href="{order_link}" style="background-color: #6f4e37; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Xem chi tiết đơn hàng</a></p>
<br>
<p>Trân trọng,<br>Đội ngũ XDTHECOFFEEHOUSE</p>',
                'description' => 'Gửi khi trạng thái đơn hàng thay đổi.',
            ],
            [
                'template_key' => 'drink_status_updated',
                'subject' => 'Đơn hàng #{order_code} - Cập nhật trạng thái pha chế',
                'content' => '<h3>Chào {customer_name},</h3>
<p>Đồ uống trong đơn hàng <strong>#{order_code}</strong> của bạn đang ở trạng thái: <strong>{drink_status_label}</strong>.</p>
<p><a href="{order_link}" style="background-color: #6f4e37; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Theo dõi đơn hàng của bạn</a></p>
<br>
<p>Trân trọng,<br>Đội ngũ XDTHECOFFEEHOUSE</p>',
                'description' => 'Gửi khi trạng thái pha chế đồ uống thay đổi.',
            ],
            [
                'template_key' => 'order_placed',
                'subject' => 'Xác nhận đơn hàng #{order_code} đã đặt thành công!',
                'content' => '<h3>Chào {customer_name},</h3>
<p>Cảm ơn bạn đã mua hàng tại <strong>XDTHECOFFEEHOUSE</strong>.</p>
<p>Đơn hàng của bạn <strong>#{order_code}</strong> đã được hệ thống ghi nhận thành công và đang được xử lý.</p>
<p><strong>Thông tin giao nhận:</strong></p>
<ul>
    <li>Người nhận: {recipient_name}</li>
    <li>Số điện thoại: {phone}</li>
    <li>Địa chỉ giao hàng: {shipping_address}</li>
</ul>
<p><strong>Chi tiết đơn hàng:</strong></p>
{items_list}
<p>Tổng tiền thanh toán: <strong>{total_price}</strong></p>
<p>Phương thức thanh toán: <strong>{payment_method}</strong></p>
<p><a href="{order_link}" style="background-color: #6f4e37; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Xem chi tiết đơn hàng</a></p>
<br>
<p>Trân trọng,<br>Đội ngũ XDTHECOFFEEHOUSE</p>',
                'description' => 'Gửi cho khách hàng khi đặt hàng thành công.',
            ],
        ];

        foreach ($templates as $tpl) {
            EmailTemplate::updateOrCreate(
                ['template_key' => $tpl['template_key']],
                $tpl
            );
        }
    }
}
