# HƯỚNG DẪN CẤU HÌNH VÀ TEST THANH TOÁN PAYPAL SANDBOX

Tài liệu này hướng dẫn chi tiết cách tạo tài khoản nhà phát triển PayPal (Developer), lấy các khóa bảo mật (Client ID & Secret), cấu hình môi trường và các bước thực nghiệm quy trình thanh toán trên môi trường thử nghiệm (Sandbox).

---

## PHẦN 1: HƯỚNG DẪN LẤY KEY API PAYPAL (SANDBOX)

Môi trường **Sandbox** là môi trường thử nghiệm giả lập hoàn chỉnh của PayPal, cho phép bạn thực hiện giao dịch mà không cần dùng tiền thật.

### Bước 1: Đăng nhập trang PayPal Developer
1. Truy cập vào trang quản trị lập trình viên: [https://developer.paypal.com/](https://developer.paypal.com/)
2. Nhấp vào **Log in to Dashboard** ở góc phải màn hình.
3. Đăng nhập bằng tài khoản PayPal cá nhân của bạn (nếu chưa có, bạn có thể đăng ký một tài khoản thông thường miễn phí).

### Bước 2: Tạo ứng dụng thử nghiệm (Create App)
1. Tại cột menu bên trái, tìm mục **Apps & Credentials**.
2. Phía trên cùng màn hình, đảm bảo bạn đang chọn tab **Sandbox** (không phải Live).
3. Nhấp vào nút **Create App** ở góc phải.
4. Cấu hình thông tin ứng dụng:
   - **App Name**: Điền `XDTHECOFFEEHOUSE` hoặc tên bất kỳ.
   - **App Type**: Chọn `Merchant` (hoặc để mặc định).
   - **Sandbox Developer Account**: Chọn email Sandbox doanh nghiệp mặc định do PayPal tự động gợi ý.
5. Nhấp nút **Create App**.

### Bước 3: Lấy Client ID và Secret Key
1. Sau khi tạo ứng dụng thành công, bạn sẽ được đưa vào trang chi tiết của App.
2. Tại đây, bạn sẽ thấy:
   - **Client ID**: Dãy ký tự dài công khai (Dùng cho cấu hình).
   - **Secret**: Nhấp vào **Show** để hiển thị khóa bảo mật (Copy cẩn thận).
3. Sao chép 2 giá trị này để chuẩn bị điền vào file `.env`.

---

## PHẦN 2: CẤU HÌNH BIẾN MÔI TRƯỜNG TRÊN WEBSITE

Mở file [.env](file:///c:/laragon/www/DOANTOTNGHIEP_NGUYENXUANDUONG/DUANWEB/coffeeshop-laravel/.env) trong dự án của bạn và cấu hình các biến sau:

```env
# 1. Các thông tin Client ID và Secret lấy ở Phần 1
PAYPAL_CLIENT_ID=Nhập_Client_ID_của_bạn_ở_đây
PAYPAL_CLIENT_SECRET=Nhập_Secret_Key_của_bạn_ở_đây

# 2. Chế độ chạy: sandbox (thử nghiệm) hoặc live (chạy thực tế)
PAYPAL_MODE=sandbox

# 3. Tỷ giá quy đổi VND sang USD (do PayPal không hỗ trợ tiền tệ VND trực tiếp)
# Mặc định: 25000đ = 1 USD (Ví dụ đơn hàng 50.000đ sẽ tương ứng 2.00 USD)
PAYPAL_VND_TO_USD_RATE=25000

# 4. Webhook ID dùng để xác thực chữ ký (sẽ lấy ở Phần 3 nếu muốn test Webhook)
PAYPAL_WEBHOOK_ID=
```

---

## PHẦN 3: LẤY WEBHOOK ID ĐỂ TEST WEBHOOK (TÙY CHỌN)

*Webhook giúp hệ thống tự động xác nhận đơn hàng khi PayPal gọi về kể cả khi người dùng tắt trình duyệt ngay sau khi thanh toán.*

### Bước 1: Tạo tunnel Ngrok (Nếu test ở localhost)
Do PayPal không thể gửi dữ liệu Webhook đến địa chỉ `http://localhost` hay `http://127.0.0.1`, bạn cần tạo một đường dẫn internet công khai bằng ngrok:
1. Mở terminal và chạy lệnh:
   ```bash
   ngrok http 8000
   ```
2. Ngrok sẽ cấp cho bạn một đường dẫn bảo mật dạng: `https://xxxx-xxxx.ngrok-free.dev`
3. Cập nhật biến `APP_URL` trong file `.env` thành đường dẫn ngrok đó:
   ```env
   APP_URL=https://xxxx-xxxx.ngrok-free.dev
   ```

### Bước 2: Đăng ký Webhook trên PayPal Developer App
1. Quay lại trang chi tiết ứng dụng của bạn trên [PayPal Developer](https://developer.paypal.com/dashboard/applications/sandbox).
2. Kéo xuống dưới cùng tìm mục **Webhooks**, nhấp vào **Add Webhook**.
3. Điền các trường thông tin:
   - **Webhook URL**: Điền đường dẫn public của bạn kèm theo endpoint webhook:
     `https://xxxx-xxxx.ngrok-free.dev/webhook/paypal` (thay `xxxx-xxxx` bằng mã ngrok của bạn).
   - **Event types**: Kéo xuống tick chọn duy nhất sự kiện:
     `Payment capture completed` (hoặc gõ tìm kiếm sự kiện **`PAYMENT.CAPTURE.COMPLETED`**).
4. Nhấn **Save**.
5. Sau khi lưu, bạn sẽ thấy cột **Webhook ID** xuất hiện. Sao chép chuỗi này điền vào file `.env`:
   ```env
   PAYPAL_WEBHOOK_ID=chuỗi_webhook_id_vừa_tạo
   ```

---

## PHẦN 4: LẤY TÀI KHOẢN NGƯỜI MUA GIẢ LẬP (SANDBOX BUYER ACCOUNT)

Để thanh toán thử nghiệm, bạn **không được** dùng tài khoản PayPal thật mà phải dùng tài khoản người mua giả lập (Buyer) có sẵn tiền ảo.

1. Trên menu bên trái của trang **PayPal Developer**, tìm mục **Sandbox** -> nhấp vào **Accounts**.
2. Bạn sẽ thấy danh sách các tài khoản ảo được tạo sẵn. Tìm tài khoản có type là **Personal (Buyer Account)** (Ví dụ: `sb-xxxx-personal@personal.example.com`).
3. Click vào biểu tượng **3 dấu chấm** bên cạnh tài khoản đó -> chọn **View/edit account**.
4. Chuyển sang tab **Profile** để xem thông tin:
   - **Email Address**: Email đăng nhập.
   - **Password**: Mật khẩu đăng nhập giả lập.

---

## PHẦN 5: QUY TRÌNH THỰC HIỆN CÁC BƯỚC TEST TRÊN WEB

### Bước 1: Tiến hành mua hàng
1. Truy cập vào website của bạn (qua link local hoặc ngrok).
2. Thêm một số sản phẩm vào giỏ hàng và tiến hành **Đặt hàng**.
3. Điền thông tin người nhận, ghi chú đầy đủ.

### Bước 2: Chọn phương thức thanh toán PayPal
1. Tại màn hình chọn phương thức thanh toán, nhấp chọn **Thanh toán qua PayPal**.
2. Hệ thống sẽ tự động quy đổi số tiền VND sang USD và chuyển hướng trình duyệt của bạn sang trang thanh toán chính thức của PayPal Sandbox (`sandbox.paypal.com`).

### Bước 3: Đăng nhập & Xác nhận thanh toán
1. Nhập **Email Address** và **Password** của tài khoản **Buyer** ảo mà bạn đã lấy ở **Phần 4**.
2. Nhấn đăng nhập, bạn sẽ thấy số tiền thanh toán hiển thị bằng USD (ví dụ: `$2.00 USD`).
3. Chọn nguồn tiền (Ví dụ: PayPal Balance hoặc thẻ tín dụng giả lập có sẵn trong tài khoản ảo đó).
4. Nhấn **Pay Now** (Thanh toán ngay).

### Bước 4: Kiểm tra kết quả
1. Sau khi nhấn thanh toán, PayPal sẽ xử lý và chuyển hướng bạn quay trở lại đường dẫn callback của website: `/thanh-toan/paypal/ket-qua`.
2. Controller sẽ tự động gọi API `captureOrder` của PayPal để hoàn tất giao dịch.
3. Nếu thành công, bạn sẽ nhận được thông báo: **"Thanh toán qua PayPal thành công!"** và được chuyển hướng về trang hoàn thành đơn hàng.
4. Trạng thái thanh toán của đơn hàng trong cơ sở dữ liệu và trang quản trị Filament Admin sẽ được cập nhật tự động từ **Chờ thanh toán (pending)** thành **Đã thanh toán (paid)** với phương thức là **PayPal**.
