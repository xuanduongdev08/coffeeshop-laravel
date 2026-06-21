<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MoMoService;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Trang chọn phương thức thanh toán
     */
    public function index(Order $order)
    {
        // Chỉ chủ đơn mới xem được
        abort_if($order->user_id !== auth()->id(), 403);
        return view('shop.payment.index', compact('order'));
    }

    /**
     * Xử lý COD
     */
    public function processCOD(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->update([
            'payment_method' => 'COD',
            'payment_status' => 'pending',
        ]);

        return redirect()->route('payment.success', $order)
            ->with('success', 'Đặt hàng COD thành công! Chúng tôi sẽ liên hệ xác nhận sớm.');
    }

    /**
     * Hiển thị VietQR (QR động)
     */
    public function showVietQR(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->update(['payment_method' => 'VietQR']);
        $qrData = $this->generateVietQR($order);

        return view('shop.payment.vietqr', compact('order', 'qrData'));
    }

    /**
     * Redirect sang PayPal
     */
    public function redirectPayPal(Order $order, PayPalService $paypal)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->update(['payment_method' => 'PayPal']);
        $payUrl = $paypal->createOrder($order);

        if ($payUrl) {
            return redirect()->away($payUrl);
        }

        return redirect()->route('payment.index', $order)
            ->with('error', 'Không thể tạo liên kết thanh toán PayPal. Vui lòng thử lại sau.');
    }

    /**
     * PayPal return callback (Capture payment)
     */
    public function paypalReturn(Request $request, PayPalService $paypal)
    {
        $paypalOrderId = $request->get('token');
        if (!$paypalOrderId) {
            return redirect()->route('home')->with('error', 'Liên kết thanh toán không hợp lệ.');
        }

        // Capture đơn hàng trên PayPal
        $result = $paypal->captureOrder($paypalOrderId);

        if ($result && ($result['status'] ?? '') === 'COMPLETED') {
            $purchaseUnit = $result['purchase_units'][0] ?? [];
            $trackingCode = $purchaseUnit['custom_id'] ?? $purchaseUnit['reference_id'] ?? '';
            
            if (!$trackingCode) {
                return redirect()->route('home')->with('error', 'Không thể xác định thông tin đơn hàng từ PayPal.');
            }

            $order = Order::where('tracking_code', $trackingCode)->first();

            if (!$order) {
                return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng tương ứng.');
            }

            if ($order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'payment_method' => 'PayPal'
                ]);
            }

            if (!auth()->check()) {
                return redirect()->route('login')
                    ->with('success', 'Thanh toán thành công! Đăng nhập để xem đơn hàng ' . $trackingCode . '.');
            }

            return redirect()->route('payment.success', $order)
                ->with('success', 'Thanh toán qua PayPal thành công!');
        }

        return redirect()->route('home')->with('error', 'Thanh toán qua PayPal thất bại hoặc chưa hoàn tất.');
    }

    /**
     * PayPal cancel callback
     */
    public function paypalCancel(Order $order)
    {
        return redirect()->route('payment.index', $order)
            ->with('error', 'Bạn đã hủy thanh toán qua PayPal.');
    }

    /**
     * Redirect sang MoMo
     */
    public function redirectMoMo(Order $order, MoMoService $momo)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        // Nếu chưa cấu hình MoMo → fallback về VietQR
        if (! config('services.momo.partner_code')) {
            return redirect()->route('payment.vietqr', $order)
                ->with('warning', 'MoMo chưa được cấu hình. Vui lòng dùng VietQR hoặc COD.');
        }

        $order->update(['payment_method' => 'MoMo']);
        $result = $momo->createPayment($order);

        if ($result['success']) {
            return redirect()->away($result['pay_url']);
        }

        return redirect()->route('payment.index', $order)
            ->with('error', 'Không thể tạo link MoMo: ' . $result['message']);
    }

    /**
     * MoMo return callback
     */
    public function momoReturn(Request $request, MoMoService $momo)
    {
        $params = $request->all();

        $trackingCode = $momo->extractTrackingCode($params['orderId'] ?? '');
        $order        = Order::where('tracking_code', $trackingCode)->first();

        if (! $order) {
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng.');
        }

        if ($momo->isSuccess($params)) {
            $order->update(['payment_status' => 'paid']);
            return redirect()->route('payment.success', $order)
                ->with('success', 'Thanh toán MoMo thành công!');
        }

        return redirect()->route('payment.index', $order)
            ->with('error', 'Thanh toán MoMo thất bại.');
    }

    /**
     * Trang thanh toán thành công
     */
    public function success(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);
        $order->load('items');
        return view('shop.payment.success', compact('order'));
    }

    /**
     * Tạo VietQR động qua API vietqr.io
     */
    private function generateVietQR(Order $order): array
    {
        $bankId      = config('services.vietqr.bank_id') ?: '970422';        // MB Bank
        $accountNo   = config('services.vietqr.account_no') ?: '0801130171003';
        $accountName = config('services.vietqr.account_name') ?: 'NGUYEN XUAN DUONG';

        try {
            $response = Http::timeout(6)->post('https://api.vietqr.io/v2/generate', [
                'accountNo'   => $accountNo,
                'accountName' => $accountName,
                'acqId'       => $bankId,
                'amount'      => (int) $order->total,
                'addInfo'     => $order->tracking_code,
                'format'      => 'text',
                'template'    => 'compact',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (($data['code'] ?? '') === '00') {
                    return [
                        'success'     => true,
                        'qr_data_url' => $data['data']['qrDataURL'] ?? null,
                        'qr_code'     => $data['data']['qrCode']    ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            \Log::warning('VietQR API error: ' . $e->getMessage());
        }

        return ['success' => false, 'qr_data_url' => null, 'qr_code' => null];
    }
}
