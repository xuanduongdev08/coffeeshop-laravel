# Kế hoạch Cải thiện UI/UX và Bổ sung Chức năng Hệ thống Quản trị (Admin)

Kế hoạch này được lập ra nhằm nâng cấp toàn diện giao diện người dùng (UI), tối ưu hóa trải nghiệm sử dụng (UX), củng cố nghiệp vụ quản lý kho, phân quyền nhân viên, quản lý mẫu email tự động, và thiết lập cơ chế thông báo cho khách hàng của hệ thống **XDTHECOFFEEHOUSE**.

---

## 1. Cải tạo Giao diện UI/UX Hệ thống Quản trị (Admin)

### 1.1. Thiết kế UI Đẹp mắt & Mượt mà
*   **Tông màu chủ đạo (Coffee Premium Theme):**
    *   Sử dụng bảng màu nhất quán từ trang chủ (Home):
        *   Màu nền Sidebar: Nâu đen sẫm màu hạt cà phê `#1e130c` kết hợp với nâu ấm `#2c1810`.
        *   Màu nhấn (Accent color) khi Active / Hover: Vàng đồng sáng `#c49b63` hoặc vàng mật ong `#b89156`.
        *   Màu nền nội dung: Xám nhạt `#f8f9fa` kết hợp trắng sữa để tạo cảm giác sang trọng, thoáng đãng.
        *   Màu chữ chính: Trắng hoặc vàng nhạt `#f5efe6` ở sidebar; đen sẫm `#2d2522` ở nội dung.
    *   Loại bỏ các đường viền cứng, thay thế bằng đổ bóng mờ nhẹ (`box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05)`) và bo góc hiện đại (`border-radius: 12px` cho Card, Table, Form).
*   **Hiệu ứng mượt mà (Micro-animations):**
    *   Hiệu ứng Hover trên các liên kết sidebar và nút bấm: Thêm `transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1)`.
    *   Sidebar link: Khi hover/active sẽ trượt nhẹ sang phải (`transform: translateX(5px)`) và đổi màu viền trái.
*   **Tinh giản Icon ở Sidebar:**
    *   Hạn chế sử dụng icon màu mè hoặc Emoji. Chỉ sử dụng các icon vector đơn sắc (dạng SVG mỏng hoặc từ thư viện Ionicons / FontAwesome đơn giản).
    *   Kích thước icon nhỏ gọn (khoảng `16px`), màu sắc mờ nhạt khi chưa active và sáng lên cùng màu vàng đồng khi được chọn.

### 1.2. Tối ưu UX Sidebar
*   **Thêm chữ "Quản lý" vào các mục chính:**
    *   `Dashboard` $\rightarrow$ **Quản lý Dashboard** (hoặc *Quản lý tổng quan*).
    *   `Sản phẩm` $\rightarrow$ **Quản lý sản phẩm**.
    *   `Danh mục` $\rightarrow$ **Quản lý danh mục**.
    *   `Đơn hàng` $\rightarrow$ **Quản lý đơn hàng**.
*   **Bỏ các thẻ div tiêu đề phân khu:**
    *   Loại bỏ hoàn toàn các dòng tiêu đề màu xám nhạt chia khu vực ở sidebar bao gồm: `TỔNG QUAN`, `SẢN PHẨM`, `ĐƠN HÀNG`, `NGƯỜI DÙNG`, `BÁO CÁO`, `HỆ THỐNG`.
    *   Giúp giao diện liền mạch, gọn gàng, giảm chiều cao sidebar để tránh cuộn trang không cần thiết.

### 1.3. Dropdown Menu ở Avatar Header & Trang Hồ sơ cá nhân
*   **Dropdown Menu tại Header:**
    *   Khi người dùng click vào cụm Avatar ở góc trên cùng bên phải sẽ hiển thị một dropdown menu nhỏ màu trắng sữa, bo góc với các tùy chọn:
        1.  **Hồ sơ cá nhân** (Icon: Người dùng $\rightarrow$ Dẫn tới trang hồ sơ cá nhân).
        2.  **Đăng xuất** (Form POST logout an toàn).
*   **Trang Hồ sơ cá nhân (`/admin/profile`):**
    *   Áp dụng cho tất cả tài khoản đăng nhập vào hệ thống quản trị (Admin, Quản lý, Nhân viên, Thủ kho).
    *   Hiển thị: Ảnh đại diện hiện tại, Họ tên, Email, Số điện thoại, Vai trò (Role - chỉ xem, không được tự sửa).
    *   Chức năng:
        *   Cho phép cập nhật Họ tên, Số điện thoại.
        *   Cho phép đổi mật khẩu (yêu cầu mật khẩu hiện tại và mật khẩu mới).
        *   Cho phép upload & cắt (crop) ảnh đại diện (avatar).
    *   **Phân quyền chỉnh sửa:**
        *   Mỗi nhân viên chỉ được chỉnh sửa thông tin cá nhân của **chính mình**.
        *   Các trường quan trọng như Email đăng nhập, Phân quyền vai trò, hoặc Trạng thái kích hoạt tài khoản chỉ được chỉnh sửa bởi Admin/Quản lý cấp cao tại tab **Quản lý nhân viên**.

---

## 2. Phân tích codebase & Đánh giá Nghiệp vụ hiện tại

### 2.1. Đánh giá tính đầy đủ của các Module quản trị
Hiện tại hệ thống có các module cơ bản:
1.  **Dashboard:** Hiển thị doanh thu 7 ngày gần nhất, đơn hàng đang pha chế, đơn hàng mới nhất.
2.  **Sản phẩm:** Quản lý CRUD sản phẩm (hỗ trợ size, topping, đường, đá, sữa), bộ lọc, soft delete & restore.
3.  **Danh mục:** Quản lý CRUD danh mục sản phẩm.
4.  **Đơn hàng:** Quản lý danh sách, xem chi tiết, cập nhật trạng thái đơn hàng, trạng thái thanh toán và trạng thái pha chế (`drink_status`).
5.  **Khách hàng:** Quản lý danh sách khách hàng, xem thông tin và xóa tài khoản.
6.  **Nhân viên:** Quản lý danh sách nhân viên, gán vai trò (`admin`, `staff`, `cashier`, `warehouse`).
7.  **Thống kê:** Thống kê doanh thu kỳ hạn, sản phẩm bán chạy, doanh thu danh mục, phương thức thanh toán, xuất Excel.

### 2.2. Các thiếu sót nghiệp vụ cần bổ sung gấp
Qua đọc codebase, phát hiện các tác vụ tính toán và xử lý kho hàng chưa đồng bộ:
1.  **Thiếu cơ chế trừ kho hàng khi đặt hàng:**
    *   Trong `OrderController::store` (frontend), đơn hàng được tạo và lưu nhưng số lượng tồn kho (`stock` trong bảng `products`) không hề bị giảm đi.
    *   *Đề xuất bổ sung:* Khi khách hàng đặt hàng thành công, hệ thống phải duyệt qua các item và thực hiện trừ kho (`$product->decrement('stock', $quantity)`). Cần kiểm tra tồn kho tại thời điểm checkout, nếu `stock < quantity` thì từ chối đặt hàng và thông báo cho khách hàng sản phẩm nào đã hết.
2.  **Thiếu cơ chế hoàn trả kho hàng khi hủy đơn:**
    *   Khi đơn hàng chuyển sang trạng thái "Đã hủy" (do khách hàng tự hủy hoặc admin hủy), số lượng sản phẩm đã đặt không được cộng trả lại kho hàng.
    *   *Đề xuất bổ sung:* Tại hàm cập nhật trạng thái đơn hàng (`updateStatus` trong admin hoặc `cancel` ở frontend), nếu trạng thái mới là `Đã hủy` và trạng thái cũ khác `Đã hủy`, hệ thống sẽ cộng trả số lượng vào kho (`$product->increment('stock', $item->quantity)`).
3.  **Bất cập trong tính toán Doanh thu:**
    *   Thống kê doanh thu dựa vào các đơn hàng có `payment_status = 'paid'`. Tuy nhiên, khi chuyển trạng thái đơn hàng thành `Hoàn thành` (đặc biệt là các đơn ship COD), hệ thống không tự động chuyển `payment_status` sang `paid` mà quản trị viên phải sửa thủ công.
    *   *Đề xuất bổ sung:* Khi đơn hàng chuyển sang `Hoàn thành`, tự động cập nhật `payment_status` thành `paid` nếu trước đó chưa thanh toán.

---

## 3. Quản lý Mẫu Email (Email Templates) và Gửi Email tự động

Hệ thống sẽ bổ sung một module mới có tên **Quản lý Email Template** dành cho Admin.

### 3.1. Thiết kế Cơ sở dữ liệu (`email_templates`)
Tạo migration để tạo bảng `email_templates` lưu trữ các mẫu email động:
*   `id`: BIGINT, tự tăng.
*   `template_key`: VARCHAR(50), unique (ví dụ: `register_success`, `order_status_updated`, `drink_status_updated`).
*   `subject`: VARCHAR(255), tiêu đề email mẫu.
*   `content`: TEXT, nội dung email dưới dạng HTML hỗ trợ placeholder.
*   `description`: VARCHAR(255), mô tả tác vụ mẫu email này.
*   `created_at`, `updated_at`: Timestamp.

### 3.2. Trang quản trị Email Template (`/admin/email-templates`)
*   Giao diện hiển thị danh sách các mẫu email hiện có.
*   Trang chỉnh sửa tiêu đề và nội dung HTML của từng mẫu. Có danh sách gợi ý các placeholder hợp lệ cho từng mẫu.
*   **Các mẫu email mặc định khởi tạo ban đầu:**
    1.  **Đăng ký thành công (`register_success`):**
        *   *Mô tả:* Gửi khi khách hàng đăng ký tài khoản thành công trên website.
        *   *Nội dung:* Chúc mừng thành viên mới, cung cấp thông tin tài khoản đăng nhập (Email) và nhắc nhở bảo mật mật khẩu đã tạo.
        *   *Placeholders:* `{customer_name}`, `{customer_email}`, `{website_link}`.
    2.  **Cập nhật Trạng thái Pha chế (`drink_status_updated`):**
        *   *Mô tả:* Gửi khi nhân viên bắt đầu pha chế đồ uống hoặc pha chế xong.
        *   *Placeholders:* `{customer_name}`, `{order_code}`, `{drink_status_label}`, `{order_link}`.
    3.  **Cập nhật Trạng thái Đơn hàng (`order_status_updated`):**
        *   *Mô tả:* Gửi khi đơn hàng thay đổi trạng thái (Chờ xử lý, Đang giao, Hoàn thành, Đã hủy).
        *   *Placeholders:* `{customer_name}`, `{order_code}`, `{order_status}`, `{shipping_address}`, `{total_price}`, `{order_link}`.

### 3.3. Logic xử lý Gửi Mail trong Controller
Sử dụng Mail class trong Laravel (ví dụ: `App\Mail\DynamicTemplateMail`) biên dịch nội dung template động bằng cách thay thế các placeholder thực tế trước khi gửi đi qua SMTP (cấu hình trong `.env`).
*   **Đăng ký:** Kích hoạt gửi mail tại `RegisteredUserController::store` ngay sau khi tạo User thành công.
*   **Trạng thái đơn hàng:** Gửi mail tại `OrderController::updateStatus` khi trạng thái đơn hàng thay đổi.
*   **Trạng thái pha chế:** Gửi mail tại `DrinkStatusController::update` khi trạng thái pha chế đồ uống thay đổi.

---

## 4. Giải pháp Thông báo Realtime ra trang chủ cho Khách hàng

Hiện tại, khi Admin thay đổi trạng thái đơn hàng hoặc pha chế, trang chủ của khách hàng không nhận được thông báo tức thời do chưa có kết nối realtime.

### Đề xuất Giải pháp tích hợp:
1.  **Phương án A (Realtime hoàn toàn - Khuyên dùng):**
    *   Sử dụng **Pusher** hoặc **Laravel Reverb (WebSockets)**.
    *   Khi thay đổi trạng thái đơn hàng hoặc pha chế, hệ thống phát đi một sự kiện (Event) truyền thông tin của User sở hữu đơn hàng.
    *   Ở giao diện trang chủ (`layouts/shop.blade.php`), tích hợp JS lắng nghe kênh thông báo qua Pusher/Echo của User đang đăng nhập.
    *   Khi nhận được sự kiện, hiển thị Toast thông báo đẹp mắt bằng thư viện **SweetAlert2** ngay góc màn hình (ví dụ: *"Đơn hàng #XD00002 của bạn đã bắt đầu được pha chế!"*).
2.  **Phương án B (Polling AJAX đơn giản - Dự phòng):**
    *   Tự động gửi request AJAX từ phía client lên một API kiểm tra thông báo chưa đọc hoặc kiểm tra trạng thái đơn hàng đang hoạt động sau mỗi 15 giây.
    *   Nếu phát hiện sự thay đổi trạng thái so với phiên trước, hiển thị thông báo SweetAlert2 ra màn hình.
3.  **Kết hợp Email:** Việc gửi email ngay lập tức (ở Phần 3) sẽ hỗ trợ thông báo đến khách hàng ngay cả khi họ đã đóng trình duyệt, tạo thành chuỗi thông tin khép kín.

---

## 5. Phân quyền chi tiết tại Tab Quản lý Nhân viên

Trong tab quản lý nhân viên (`/admin/employees`), Admin sẽ quản lý danh sách và cấu hình vai trò một cách chặt chẽ. Quyền truy cập các tính năng sẽ được giới hạn cụ thể như sau:

| Vai trò (Role) | Mô tả công việc | Quyền hạn trên hệ thống quản trị |
| :--- | :--- | :--- |
| **Admin** | Quản trị viên tối cao | Toàn quyền kiểm soát (CRUD Sản phẩm, Danh mục, Đơn hàng, Khách hàng, Nhân viên, Phân quyền vai trò, Cấu hình hệ thống, Email Template, Xem thống kê doanh thu). |
| **Cashier** | Nhân viên thu ngân / Quản lý đơn | Có quyền xem sản phẩm, danh mục. Có quyền xem, xử lý đơn hàng (Đổi trạng thái đơn hàng, Cập nhật trạng thái thanh toán). Có quyền xem báo cáo thống kê cơ bản. Không được quyền thêm/sửa/xóa sản phẩm, danh mục, email template hay quản lý nhân viên. |
| **Staff** | Nhân viên pha chế | Chỉ có quyền xem danh sách sản phẩm/danh mục để phục vụ việc pha chế. Có quyền xem danh sách đơn hàng và **chỉ được phép cập nhật trạng thái pha chế (`drink_status`)** của đồ uống. Không được phép chỉnh sửa đơn hàng, thanh toán, kho hàng, hay quản lý nhân viên. |
| **Warehouse** | Nhân viên kho | Có quyền xem sản phẩm, danh mục. **Được phép chỉnh sửa số lượng tồn kho (`stock`)** của sản phẩm trong phần quản lý sản phẩm. Không có quyền can thiệp vào đơn hàng, thanh toán, pha chế hay nhân viên. |

### Cách thức triển khai phân quyền:
*   Sử dụng gói `spatie/laravel-permission` đã tích hợp sẵn trong codebase.
*   Tạo các Permission tương ứng (ví dụ: `view products`, `edit stock`, `manage orders`, `update drink status`, `manage employees`, `manage templates`).
*   Gán quyền cho các Role (`admin`, `staff`, `cashier`, `warehouse`) trong file Seeder hoặc thiết lập giao diện gán quyền.
*   Áp dụng các Middleware kiểm tra quyền truy cập (ví dụ: `@can('edit stock')` trong Blade và `$this->authorize('edit stock')` trong Controller) để ngăn chặn hành vi truy cập trái phép.

---

## 6. Kế hoạch triển khai & Kiểm thử (Verification Plan)

### 6.1. Các bước thực hiện
1.  **Bước 1:** Cải tạo lại giao diện Sidebar và Header trong `layouts/admin.blade.php`, viết lại CSS cho mượt mà, đổi tông màu nâu đen-vàng đồng sang trọng, bỏ tiêu đề phân khu và tích hợp dropdown avatar.
2.  **Bước 2:** Xây dựng Migration cho bảng `email_templates` và seeder khởi tạo dữ liệu mẫu mặc định.
3.  **Bước 3:** Phát triển Module CRUD Email Template trong Admin (Route, Controller, Blade).
4.  **Bước 4:** Xây dựng class Mail `DynamicTemplateMail` và tích hợp gửi email tự động vào các sự kiện (Đăng ký, Trạng thái đơn hàng, Trạng thái pha chế).
5.  **Bước 5:** Bổ sung logic trừ kho hàng khi đặt hàng (`OrderController::store`), cộng lại kho khi hủy đơn, tự động cập nhật trạng thái thanh toán sang `paid` khi đơn chuyển thành `Hoàn thành`.
6.  **Bước 6:** Tích hợp cơ chế thông báo realtime (sử dụng Pusher/Laravel Echo hoặc Polling AJAX) hiển thị Toast ra trang khách hàng khi trạng thái đơn hàng/pha chế thay đổi.
7.  **Bước 7:** Phân quyền chi tiết cho các vai trò `admin`, `staff`, `cashier`, `warehouse` bằng Middleware kiểm tra Role/Permission trên từng Route và Controller tương ứng.
8.  **Bước 8:** Tạo trang Hồ sơ cá nhân (`admin/profile`) và phân quyền chỉnh sửa thông tin cá nhân.

### 6.2. Kịch bản kiểm thử (Manual Verification)
*   **Kiểm thử giao diện:** Truy cập admin, thu nhỏ màn hình, kiểm tra hiệu ứng hover, chuyển màu active, kiểm tra dropdown avatar hoạt động tốt không bị vỡ giao diện.
*   **Kiểm thử kho hàng:** Đặt thử sản phẩm có số lượng tồn kho là 10.
    *   *Kịch bản 1:* Giỏ hàng đặt 11 sản phẩm $\rightarrow$ Hệ thống phải báo lỗi và chặn lại.
    *   *Kịch bản 2:* Đặt hàng thành công 3 sản phẩm $\rightarrow$ Kho hàng của sản phẩm đó trong admin phải giảm xuống còn 7.
    *   *Kịch bản 3:* Hủy đơn hàng vừa đặt $\rightarrow$ Kho hàng phải tự động tăng lại thành 10.
*   **Kiểm thử gửi Email:** Đăng ký tài khoản mới $\rightarrow$ Kiểm tra hộp thư nhận email chào mừng có đúng template và placeholder không. Thay đổi trạng thái pha chế thành "Đang pha chế" $\rightarrow$ Kiểm tra email thông báo gửi về khách hàng.
*   **Kiểm thử Realtime:** Đăng nhập tài khoản khách hàng ở trình duyệt Chrome, tài khoản Admin ở trình duyệt Firefox. Admin bấm "Bắt đầu pha chế" đồ uống của đơn hàng $\rightarrow$ Chrome của khách hàng hiển thị Toast thông báo trạng thái thay đổi ngay lập tức mà không cần F5.
*   **Kiểm thử Phân quyền:** Đăng nhập bằng tài khoản có vai trò `staff` (nhân viên pha chế) $\rightarrow$ Truy cập các trang thống kê doanh thu hoặc sửa sản phẩm $\rightarrow$ Hệ thống phải trả về trang lỗi `403 Forbidden`. Bấm cập nhật trạng thái pha chế $\rightarrow$ Phải hoạt động bình thường.
