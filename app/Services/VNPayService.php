<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;

/**
 * VNPay Payment Service
 * Tài liệu: https://sandbox.vnpayment.vn/apis/docs/thanh-toan-pay/pay.html
 */
class VNPayService
{
    private string $tmnCode;
    private string $hashSecret;
    private string $paymentUrl;
    private string $returnUrl;

    public function __construct()
    {
        $this->tmnCode    = config('services.vnpay.tmn_code') ?: '2QXG2YLS';
        $this->hashSecret = config('services.vnpay.hash_secret') ?: 'HPJSQDZQXJSQDZQXJSQDZQXJSQDZQXJS';
        $this->paymentUrl = config('services.vnpay.url') ?: 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
        $this->returnUrl  = route('payment.vnpay.return');
    }

    /**
     * Tạo URL thanh toán VNPay
     */
    public function createPaymentUrl(Order $order, string $ipAddr = '127.0.0.1'): string
    {
        $vnpParams = [
            'vnp_Version'    => '2.1.0',
            'vnp_Command'    => 'pay',
            'vnp_TmnCode'    => $this->tmnCode,
            'vnp_Amount'     => (int) ($order->total * 100), // VNPay tính theo đơn vị VND * 100
            'vnp_CurrCode'   => 'VND',
            'vnp_TxnRef'     => $order->tracking_code . '_' . time(), // Mã giao dịch duy nhất
            'vnp_OrderInfo'  => 'Thanh toan don hang ' . $order->tracking_code,
            'vnp_OrderType'  => 'other',
            'vnp_Locale'     => 'vn',
            'vnp_ReturnUrl'  => $this->returnUrl,
            'vnp_IpAddr'     => $ipAddr,
            'vnp_CreateDate' => now('Asia/Ho_Chi_Minh')->format('YmdHis'),
            'vnp_ExpireDate' => now('Asia/Ho_Chi_Minh')->addMinutes(15)->format('YmdHis'),
        ];

        ksort($vnpParams);

        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($vnpParams as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $paymentUrl = $this->paymentUrl . "?" . $query;
        if (isset($this->hashSecret) && !empty($this->hashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->hashSecret);
            $paymentUrl .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $paymentUrl;
    }

    /**
     * Xác thực chữ ký từ VNPay callback
     */
    public function verifyReturn(array $params): bool
    {
        $vnp_SecureHash = $params['vnp_SecureHash'] ?? '';
        unset($params['vnp_SecureHash'], $params['vnp_SecureHashType']);

        ksort($params);
        $i = 0;
        $hashData = "";
        foreach ($params as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->hashSecret);

        return hash_equals($secureHash, $vnp_SecureHash);
    }

    /**
     * Lấy tracking_code từ vnp_TxnRef (bỏ phần _timestamp)
     */
    public function extractTrackingCode(string $txnRef): string
    {
        return explode('_', $txnRef)[0];
    }

    /**
     * Kiểm tra giao dịch thành công
     */
    public function isSuccess(array $params): bool
    {
        return ($params['vnp_ResponseCode'] ?? '') === '00'
            && ($params['vnp_TransactionStatus'] ?? '') === '00';
    }
}
