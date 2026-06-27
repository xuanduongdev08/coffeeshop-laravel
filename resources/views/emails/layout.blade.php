<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Thông báo từ XDTHECOFFEEHOUSE' }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fbf9f6;
            margin: 0;
            padding: 0;
            color: #333333;
            line-height: 1.6;
        }
        .email-wrapper {
            width: 100%;
            background-color: #fbf9f6;
            padding: 40px 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(111, 78, 55, 0.05);
            border: 1px solid #f0e8dd;
        }
        .email-header {
            background-color: #ffffff;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid #f5ede3;
        }
        .email-header img {
            max-height: 80px;
            width: auto;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h3 {
            color: #6f4e37;
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: 700;
            border-bottom: none;
            padding-bottom: 0;
        }
        .email-body p {
            font-size: 15px;
            color: #555555;
            margin-bottom: 20px;
        }
        .email-body ul {
            padding-left: 20px;
            margin-bottom: 20px;
        }
        .email-body li {
            font-size: 14px;
            color: #555555;
            margin-bottom: 8px;
        }
        .btn-coffee {
            background-color: #6f4e37;
            color: #ffffff !important;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            font-size: 15px;
            font-weight: 600;
            border: 1px solid #6f4e37;
            margin: 15px 0;
        }
        .email-footer {
            background-color: #fdfbf9;
            padding: 30px 20px;
            text-align: center;
            border-top: 1px solid #f5ede3;
        }
        .email-footer p {
            font-size: 12px;
            color: #888888;
            margin: 5px 0;
        }
        .email-footer .brand-name {
            color: #6f4e37;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        /* Tables styling for order items */
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .order-table th {
            border-bottom: 2px solid #6f4e37;
            text-align: left;
            padding: 10px 8px;
            font-size: 14px;
            color: #6f4e37;
            font-weight: 700;
        }
        .order-table td {
            border-bottom: 1px solid #f5ede3;
            padding: 12px 8px;
            font-size: 14px;
            color: #555555;
            vertical-align: middle;
        }
        .order-table tr:last-child td {
            border-bottom: none;
        }
        .product-img {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            margin-right: 12px;
            object-fit: cover;
            border: 1px solid #e8dec9;
            vertical-align: middle;
        }
        .product-name-cell {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="email-header">
                <img src="{{ isset($message) && file_exists(public_path('images/logo.png')) ? $message->embed(public_path('images/logo.png')) : asset('images/logo.png') }}" alt="XDTHECOFFEEHOUSE Logo">
            </div>
            <div class="email-body">
                @php
                    if (isset($message)) {
                        $content = preg_replace_callback('/<img[^>]+src=["\']([^"\']+)["\']/i', function($matches) use ($message) {
                            $url = $matches[1];
                            // Clean up url (remove query strings if any)
                            $cleanUrl = preg_replace('/\?.*/', '', $url);
                            $path = '';
                            if (strpos($cleanUrl, 'http') === 0) {
                                $parsedUrl = parse_url($cleanUrl);
                                $path = public_path(ltrim($parsedUrl['path'] ?? '', '/'));
                            } else {
                                $path = public_path(ltrim($cleanUrl, '/'));
                            }
                            
                            if (file_exists($path)) {
                                return str_replace($url, $message->embed($path), $matches[0]);
                            }
                            return $matches[0];
                        }, $content);
                    }
                @endphp
                {!! $content !!}
            </div>
            <div class="email-footer">
                <p class="brand-name">XDTHECOFFEEHOUSE</p>
                <p>Địa chỉ: 93 Lê Cao Lăng, Quận Tân Phú, TP.HCM</p>
                <p>Hotline: +84 978 853 110 | Email: dn250621@coffeeshop.com</p>
                <p>&copy; {{ date('Y') }} XDTHECOFFEEHOUSE. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
