# HƯỚNG DẪN CẤU HÌNH VÀ TEST THANH TOÁN VÍ MOMO SANDBOX

Tài liệu này hướng dẫn chi tiết cách lấy các khóa bảo mật thử nghiệm (API Key) từ ví MoMo Sandbox, cấu hình Webhook (IPN), cấu hình biến môi trường trên website và các bước thực hiện giao dịch thử nghiệm.

---

## PHẦN 1: THÔNG TIN API KEY MOMO SANDBOX (DÙNG ĐỂ KIỂM THỬ)

Do việc đăng ký tài khoản doanh nghiệp MoMo thật (môi trường Live) cần phải có Giấy phép đăng ký kinh doanh và Mã số thuế, MoMo đã cung cấp các **khóa kiểm thử công khai (Public Sandbox Keys)** dành riêng cho cộng đồng lập trình viên để chạy thử API v2 mà không cần đăng ký tài khoản.

Dưới đây là bộ khóa Sandbox hoạt động tốt đã được cấu hình sẵn trong dự án của bạn:
- **Partner Code**: `MOMOBKUN20180529`
- **Access Key**: `klm05TvNBzhg7h7j`
- **Secret Key**: `at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa`
- **Endpoint (Sandbox)**: `https://test-payment.momo.vn/v2/gateway/api/create`

---

## PHẦN 2: CẤU HÌNH BIẾN MÔI TRƯỜNG TRÊN WEBSITE

Mở file [.env](file:///c:/laragon/www/DOANTOTNGHIEP_NGUYENXUANDUONG/DUANWEB/coffeeshop-laravel/.env) trong dự án của bạn và cấu hình các biến như sau (hệ thống đã tự động cấu hình sẵn cho bạn):

```env
# 1. Các thông tin cấu hình Sandbox công khai từ MoMo
MOMO_PARTNER_CODE=MOMOBKUN20180529
MOMO_ACCESS_KEY=klm05TvNBzhg7h7j
MOMO_SECRET_KEY=at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa

# 2. Endpoint của môi trường Sandbox (thử nghiệm)
MOMO_ENDPOINT=https://test-payment.momo.vn/v2/gateway/api/create
```

*Lưu ý:* Sau khi hoàn tất và chuyển sang chạy thực tế (Production), bạn chỉ cần thay đổi 3 khóa trên bằng khóa **Live** của bạn và đổi `MOMO_ENDPOINT` thành `https://payment.momo.vn/v2/gateway/api/create`.

---

## PHẦN 3: CẤU HÌNH WEBHOOK (IPN URL) ĐỂ TỰ ĐỘNG XÁC NHẬN ĐƠN HÀNG

Webhook (hoặc IPN - Instant Payment Notification) của MoMo là cơ chế cực kỳ quan trọng. Khi khách hàng thanh toán thành công, MoMo sẽ tự động gọi trực tiếp vào API Webhook của bạn để cập nhật trạng thái đơn hàng thành **Đã thanh toán (paid)** kể cả khi người dùng đóng trình duyệt đột ngột.

### Bước 1: Tạo tunnel Ngrok (Nếu test trên localhost)
Vì MoMo không thể gửi dữ liệu Webhook đến địa chỉ local như `http://127.0.0.1` hay `http://localhost`, bạn cần xuất bản localhost của mình ra Internet bằng Ngrok:
1. Mở terminal và chạy lệnh (chạy trên cổng của Laravel Serve, mặc định là 8000):
   ```bash
   ngrok http 8000
   ```
2. Ngrok sẽ cấp cho bạn một URL công khai dạng: `https://xxxx-xxxx.ngrok-free.dev`
3. Cập nhật biến `APP_URL` trong file `.env` thành địa chỉ Ngrok này:
   ```env
   APP_URL=https://xxxx-xxxx.ngrok-free.dev
   ```

### Bước 2: Hoạt động tự động của Webhook trong code
Khác với một số cổng thanh toán khác, MoMo cho phép truyền động `ipnUrl` và `redirectUrl` trong mỗi request tạo link thanh toán. Trong file [MoMoService.php](file:///c:/laragon/www/DOANTOTNGHIEP_NGUYENXUANDUONG/DUANWEB/coffeeshop-laravel/app/Services/MoMoService.php):
- **Redirect URL** (Trang quay lại sau thanh toán): Tự động cấu hình là `APP_URL + /thanh-toan/momo/ket-qua`.
- **IPN URL** (Webhook nhận kết quả): Tự động cấu hình là `APP_URL + /webhook/momo`.

Do đó, bạn **không cần** phải cấu hình cố định Webhook URL trên trang MoMo Business Portal. Hệ thống sẽ tự động gửi link Ngrok của bạn cho MoMo xử lý trong quá trình test!

---

## PHẦN 4: HƯỚNG DẪN TẢI ỨNG DỤNG TEST & TÀI KHOẢN MUA GIẢ LẬP

Để quét mã QR và thanh toán MoMo Sandbox, bạn cần có ứng dụng MoMo phiên bản Test và tài khoản ví Test.

### 1. Tải ứng dụng MoMo Test (MoMo Sandbox App)
Do ứng dụng MoMo trên App Store/Google Play chỉ chạy trên môi trường Live, bạn cần cài đặt app test:
- **Hệ điều hành Android**: Tải file APK trực tiếp tại link hướng dẫn của MoMo Developer Portal (tìm kiếm mục "Tải ứng dụng MoMo Test").
- **Hệ điều hành iOS**: Sử dụng TestFlight để tải ứng dụng test (yêu cầu điền email đăng ký nhận lời mời TestFlight từ MoMo Business Portal).

### 2. Sử dụng tài khoản Ví Test
MoMo cung cấp các tài khoản ví Test mặc định trong hệ thống Sandbox.
- Bạn có thể vào mục **Tài khoản Test** trên [MoMo Business](https://business.momo.vn/) để lấy số điện thoại test hoặc sử dụng số điện thoại của chính bạn đã đăng ký làm Tester.
- Thông tin đăng nhập mặc định cho các tài khoản Test MoMo:
  - Mật khẩu đăng nhập: `000000` (hoặc `123456` tùy tài khoản).
  - Mã OTP xác thực: `000000` (hoặc bất kỳ 6 số nào).
  - Có sẵn số dư giả lập (thường là 50.000.000đ) để bạn thoải mái test.

---

## PHẦN 5: QUY TRÌNH THỰC HIỆN CÁC BƯỚC TEST TRÊN WEBSITE

### Bước 1: Tạo đơn hàng mới
1. Truy cập vào website cửa hàng của bạn.
2. Thêm đồ uống/sản phẩm vào giỏ hàng và điền thông tin đặt hàng tại trang checkout.
3. Nhấp nút **Đặt hàng**.

### Bước 2: Chọn thanh toán MoMo
1. Tại trang chọn phương thức thanh toán, chọn **Thanh toán với Ví MoMo**.
2. Hệ thống sẽ tự động gọi API MoMo gửi thông tin đơn hàng, số tiền, và chuyển hướng bạn sang trang thanh toán của MoMo Sandbox (`test-payment.momo.vn`).

### Bước 3: Thực hiện thanh toán trên MoMo Sandbox
Trang thanh toán của MoMo sẽ hiển thị Mã QR lớn và form đăng nhập. Bạn có 2 cách test:
*   **Cách 1 (Quét QR - Khuyên dùng)**: Mở ứng dụng **MoMo Test** đã cài trên điện thoại -> Chọn Quét mã -> Quét mã QR hiển thị trên màn hình máy tính -> Nhập mật khẩu xác nhận thanh toán.
*   **Cách 2 (Thanh toán trực tiếp trên Web - Nhanh gọn)**: Nhập số điện thoại ví test MoMo và làm theo các bước hướng dẫn xác thực OTP trên giao diện web Sandbox để thanh toán trực tiếp.

### Bước 4: Kiểm tra kết quả
1. Sau khi giao dịch thành công, MoMo Sandbox sẽ tự động chuyển hướng bạn quay lại trang web của mình: `/thanh-toan/momo/ket-qua`.
2. Controller `PaymentController` sẽ xác thực chữ ký số do MoMo gửi kèm để đảm bảo an toàn bảo mật.
3. Nếu chữ ký hợp lệ và giao dịch thành công:
   - Bạn sẽ nhận được thông báo **"Thanh toán MoMo thành công!"** trên giao diện.
   - Trạng thái thanh toán của đơn hàng sẽ chuyển từ **Chờ thanh toán (pending)** sang **Đã thanh toán (paid)**.
   - Phương thức thanh toán được ghi nhận là **MoMo**.
4. Kiểm tra trong trang quản trị Filament Admin để đảm bảo đơn hàng đã được cập nhật chính xác doanh thu.
