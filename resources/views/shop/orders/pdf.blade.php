<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hóa đơn {{ $order->tracking_code }}</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.5;
            background-color: #fff;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #6f4e37;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            color: #6f4e37;
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #8b6f47;
            font-size: 14px;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .card {
            border: 1px solid #e8dec9;
            border-radius: 8px;
            background-color: #faf8f5;
            padding: 15px;
        }
        .card-title {
            font-size: 16px;
            font-weight: bold;
            color: #6f4e37;
            border-bottom: 1px solid #e8dec9;
            padding-bottom: 8px;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .row {
            width: 100%;
        }
        .col {
            float: left;
            width: 50%;
        }
        .clear {
            clear: both;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .info-item strong {
            color: #555;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .table th {
            background-color: #6f4e37;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            font-size: 12px;
            border: 1px solid #6f4e37;
        }
        .table td {
            padding: 10px;
            border-bottom: 1px solid #e8dec9;
            vertical-align: middle;
        }
        .product-name {
            font-weight: bold;
            color: #333;
        }
        .product-meta {
            font-size: 11px;
            color: #777;
            margin-top: 3px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-section {
            margin-top: 20px;
            float: right;
            width: 40%;
        }
        .total-row {
            margin-bottom: 8px;
        }
        .total-row.grand-total {
            border-top: 2px solid #6f4e37;
            padding-top: 8px;
            font-size: 15px;
            font-weight: bold;
            color: #6f4e37;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #999;
            border-top: 1px solid #e8dec9;
            padding-top: 15px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>XDTHECOFFEEHOUSE</h1>
        <p>Hóa Đơn Bán Hàng Online</p>
    </div>

    <div class="info-section">
        <div class="card">
            <div class="card-title">Thông tin đơn hàng {{ $order->tracking_code }}</div>
            <div class="row">
                <div class="col">
                    <div class="info-item"><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</div>
                    <div class="info-item"><strong>Người nhận:</strong> {{ $order->recipient_name }}</div>
                    <div class="info-item"><strong>Số điện thoại:</strong> {{ $order->phone }}</div>
                    <div class="info-item"><strong>Địa chỉ giao:</strong> {{ $order->shipping_address }}</div>
                    @if($order->notes)
                        <div class="info-item"><strong>Ghi chú:</strong> {{ $order->notes }}</div>
                    @endif
                </div>
                <div class="col">
                    <div class="info-item">
                        <strong>Trạng thái đơn:</strong> 
                        {{ $order->status }}
                    </div>
                    <div class="info-item">
                        <strong>Phương thức thanh toán:</strong> 
                        {{ $order->payment_method === 'COD' ? 'Tiền mặt (COD)' : ($order->payment_method === 'VietQR' ? 'Chuyển khoản VietQR' : $order->payment_method) }}
                    </div>
                    <div class="info-item">
                        <strong>Trạng thái thanh toán:</strong> 
                        {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : ($order->payment_status === 'failed' ? 'Thanh toán thất bại' : 'Chờ thanh toán') }}
                    </div>
                    @if($order->has_drink)
                        <div class="info-item">
                            <strong>Trạng thái pha chế:</strong>
                            {{ $order->drink_status === 'pending' ? 'Chờ pha chế' : ($order->drink_status === 'brewing' ? 'Đang pha chế' : 'Đã pha chế xong') }}
                        </div>
                    @endif
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>

    <div>
        <h3 style="color: #6f4e37; border-bottom: 1px solid #e8dec9; padding-bottom: 5px; margin-bottom: 10px;">Chi tiết sản phẩm</h3>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50%;">Sản phẩm</th>
                    <th style="width: 15%; text-align: right;">Đơn giá</th>
                    <th style="width: 15%; text-align: center;">Số lượng</th>
                    <th style="width: 20%; text-align: right;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <div class="product-name">{{ $item->product_name }}</div>
                            @if($item->size || $item->modifiers->count() > 0)
                                <div class="product-meta">
                                    @if($item->size) Size: {{ $item->size }} @endif
                                    @if($item->modifiers->count() > 0)
                                        | Topping/Modifier: {{ $item->modifiers->pluck('name')->join(', ') }}
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($item->unit_price ?? $item->price, 0, ',', '.') }}đ</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="row" style="margin-top: 15px;">
        <div class="col" style="width: 60%;">
            &nbsp;
        </div>
        <div class="col" style="width: 40%;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 5px 0; border: none; font-weight: bold; color: #555;">Tiền hàng:</td>
                    <td class="text-right" style="padding: 5px 0; border: none;">{{ number_format($order->subtotal, 0, ',', '.') }}đ</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0; border: none; font-weight: bold; color: #555;">Phí ship:</td>
                    <td class="text-right" style="padding: 5px 0; border: none;">
                        {{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . 'đ' : 'Miễn phí' }}
                    </td>
                </tr>
                <tr style="border-top: 2px solid #6f4e37;">
                    <td style="padding: 8px 0; border: none; font-weight: bold; font-size: 15px; color: #6f4e37;">Tổng cộng:</td>
                    <td class="text-right" style="padding: 8px 0; border: none; font-weight: bold; font-size: 15px; color: #6f4e37;">
                        {{ number_format($order->total, 0, ',', '.') }}đ
                    </td>
                </tr>
            </table>
        </div>
        <div class="clear"></div>
    </div>

    <div class="footer">
        <p>Hệ thống quản lý XDTHECOFFEEHOUSE - Hotline: +84 978 853 110</p>
    </div>

        <div style="text-align: center; margin-top: 40px; margin-bottom: 20px;">
        <p style="font-style: italic; color: #6f4e37; font-size: 14px; margin: 0;">
            Cảm ơn bạn đã tin tưởng chọn lựa sản phẩm tại XDTHECOFFEEHOUSE!
        </p>
    </div>

</body>
</html>
