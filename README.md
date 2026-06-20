# ☕ XDCOFFEEHOUSE — Website Bán Cà Phê Online

> **Đồ án môn PHP 2** — Sinh viên: Nguyễn Xuân Dương  
> Kiến trúc: **MVC + OOP** | Ngôn ngữ: **PHP 7.4+** | CSDL: **MySQL**

---

## 📋 Mục lục

- [Giới thiệu dự án](#-giới-thiệu-dự-án)
- [Công nghệ sử dụng](#-công-nghệ-sử-dụng)
- [Cấu trúc thư mục](#-cấu-trúc-thư-mục)
- [Tính năng hệ thống](#-tính-năng-hệ-thống)
- [Kiến trúc MVC & OOP](#-kiến-trúc-mvc--oop)
- [Các lớp Core](#-các-lớp-core)
- [Mô hình dữ liệu](#-mô-hình-dữ-liệu)
- [Luồng hoạt động chính](#-luồng-hoạt-động-chính)
- [Bảo mật](#-bảo-mật)
- [Hướng dẫn cài đặt](#-hướng-dẫn-cài-đặt)
- [Tài khoản mặc định](#-tài-khoản-mặc-định)
- [Tác giả](#-tác-giả)

---

## 🎯 Giới thiệu dự án

**XDCOFFEEHOUSE** là một website thương mại điện tử chuyên về cà phê, được xây dựng theo mô hình **MVC (Model-View-Controller)** kết hợp **Lập trình Hướng Đối tượng (OOP)** trong PHP thuần.

Dự án thể hiện đầy đủ các kỹ năng:
- Tổ chức code theo mô hình MVC rõ ràng, dễ bảo trì
- Áp dụng 4 tính chất OOP: Đóng gói, Kế thừa, Đa hình, Trừu tượng
- Sử dụng Design Pattern: **Singleton** (Database), **Front Controller** (index.php)
- Bảo mật với PDO Prepared Statements, password_hash/verify, Session Management
- Tích hợp thanh toán QR (Banking) và hệ thống email tự động

---

## 🛠 Công nghệ sử dụng

| Công nghệ | Phiên bản | Mục đích |
|-----------|-----------|----------|
| **PHP** | 7.4+ | Ngôn ngữ backend chính |
| **MySQL** | 5.7+ | Cơ sở dữ liệu |
| **Bootstrap** | 4.5 | Framework CSS responsive |
| **Chart.js** | Latest | Biểu đồ thống kê (Admin) |
| **SweetAlert2** | 11 | Thông báo đẹp |
| **Font Awesome** | 5.15 | Bộ icon |
| **html2pdf.js** | 0.10 | Xuất báo cáo PDF |
| **Laragon** | — | Môi trường phát triển local |

---

## 📁 Cấu trúc thư mục

```
DUANMAU/
│
├── 📄 index.php(username: djxuanduong01@gmail.com, password: xdbeo12345) # ← Entry point DUY NHẤT của ứng dụng (Front Controller)
├── 📄 config.php                  # Cấu hình toàn cục + khởi động Autoloader
├── 📄 ajax_thongbao.php           # AJAX endpoint lấy/đánh dấu thông báo (được gọi từ navbar)
├── 📄 check_payment_status.php    # AJAX polling kiểm tra trạng thái thanh toán QR
│
├── 📂 Core/                       # Lớp nền tảng OOP
│   ├── Autoloader.php             # PSR-4: tự động load class khi cần dùng
│   ├── BaseController.php         # Lớp CHA của mọi Controller (render, redirect, session...)
│   ├── Router.php                 # Điều hướng ?action= đến đúng Controller
│   ├── Validator.php              # Kiểm tra email, SĐT, mật khẩu, tên
│   └── EmailService.php           # Gửi email qua PHPMailer (đăng ký, đơn hàng)
│
├── 📂 Model/                      # Tầng dữ liệu — tương tác với MySQL
│   ├── connect.php                # class Database — Singleton Pattern, kết nối PDO
│   ├── khachhang.php              # class KhachHang — đăng ký, đăng nhập, CRUD
│   ├── hanghoa.php                # class HangHoa — CRUD sản phẩm, tìm kiếm, phân trang
│   ├── hoadon.php                 # class HoaDon — tạo & quản lý đơn giỏ hàng
│   ├── giohang.php                # class GioHang — giỏ hàng (Session), tính tiền, phí ship
│   ├── phantrang.php              # class PhanTrang — tính LIMIT/OFFSET, render nút trang
│   ├── giamgia.php                # class GiamGia — giảm giá theo danh mục
│   ├── reviews.php                # class Reviews — đánh giá sản phẩm
│   ├── thongbao.php               # class ThongBao — thông báo realtime cho khách hàng
│   └── mycfshop.sql               # Script SQL khởi tạo toàn bộ database
│
├── 📂 Controller/                 # Tầng điều khiển — xử lý logic nghiệp vụ
│   ├── HomeController.php         # Trang chủ
│   ├── LoginController.php        # Đăng nhập khách hàng
│   ├── RegistrationController.php # Đăng ký tài khoản
│   ├── LogoutController.php       # Đăng xuất
│   ├── ForgetController.php       # Quên mật khẩu (gửi email reset)
│   ├── SanPhamController.php      # Danh sách sản phẩm + phân trang + lọc/tìm kiếm
│   ├── SanPhamChiTietController.php  # Chi tiết sản phẩm + sản phẩm liên quan
│   ├── CartController.php         # Quản lý giỏ hàng (thêm/sửa/xóa)
│   ├── OrderController.php        # Đặt hàng (nhập địa chỉ, tính phí ship)
│   ├── OrderHistoryController.php # Lịch sử đơn hàng của khách
│   ├── OrderDetailController.php  # Chi tiết một đơn hàng
│   ├── PaymentController.php      # Thanh toán COD / Banking / QR
│   ├── PaymentQRController.php    # Tạo QR code, polling xác nhận
│   ├── PaymentSuccessController.php  # Trang xác nhận đặt hàng thành công
│   ├── WebhookController.php      # Nhận callback từ cổng thanh toán
│   ├── ProfileController.php      # Hồ sơ cá nhân
│   ├── AjaxPaymentStatusController.php  # AJAX: kiểm tra trạng thái thanh toán
│   ├── AjaxUpdateProfileController.php  # AJAX: cập nhật thông tin cá upload
│   ├── AjaxUploadAvatarController.php   # AJAX: upload ảnh đại diện
│   ├── cart.php                   # Wrapper: nhận link trực tiếp từ View → CartController
│   ├── ajax_upload_avatar.php     # Wrapper: nhận AJAX từ View → AjaxUploadAvatarController
│   ├── ajax_update_profile.php    # Wrapper: nhận AJAX từ View → AjaxUpdateProfileController
│   └── review.php                 # Nhận POST review từ form → lưu DB & redirect
│
├── 📂 View/                       # Tầng hiển thị — giao diện người dùng (HTML + PHP)
│   ├── headder.php                # Header + Navbar (giỏ hàng, thông báo, tài khoản)
│   ├── footer.php                 # Footer + JS chung
│   ├── home.php                   # Trang chủ
│   ├── login.php                  # Form đăng nhập
│   ├── registration.php           # Form đăng ký
│   ├── forgetpassword.php         # Form quên mật khẩu
│   ├── sanpham.php                # Danh sách sản phẩm + bộ lọc danh mục
│   ├── sanphamchitiet.php         # Chi tiết sản phẩm + gallery + review
│   ├── cart.php                   # Trang giỏ hàng
│   ├── order.php                  # Form điền thông tin giao hàng
│   ├── order_history.php          # Lịch sử đơn hàng
│   ├── order_detail.php           # Chi tiết đơn hàng (stepper trạng thái)
│   ├── payment.php                # Trang chọn phương thức thanh toán
│   ├── payment_qr.php             # Trang quét QR thanh toán
│   ├── payment_success.php        # Trang thanh toán thành công
│   └── profile.php                # Trang hồ sơ cá nhân
│
├── 📂 Admin2/  (username: admin, password: admin123456)                   # Khu vực quản trị (kiến trúc PHP Procedural)
│   ├── 📂 Model/                  # Model riêng cho Admin
│   │   ├── content.php            # Include kết nối DB vào biến $conn
│   │   └── admin.php              # class Admin — xác thực tài khoản quản trị
│   ├── 📂 View/                   # Layout riêng cho Admin
│   │   ├── header.php             # Sidebar + Navbar Admin
│   │   └── footer.php             # Footer + Script Admin
│   ├── index.php                  # Entry point Admin → tự redirect dashboard
│   ├── dangnhap.php               # Trang đăng nhập Admin
│   ├── dashboard.php              # Bảng điều khiển: KPI cards + biểu đồ Chart.js
│   ├── hanghoa.php                # CRUD sản phẩm + upload ảnh
│   ├── loaisanpham.php            # CRUD danh mục + thiết lập % giảm giá
│   ├── donhang.php                # Quản lý đơn hàng + cập nhật trạng thái + gửi email
│   ├── khachhang.php              # Xem & quản lý danh sách khách hàng
│   ├── thongke.php                # Thống kê doanh thu + top sản phẩm + xuất PDF/CSV
│   ├── profile.php                # Hồ sơ tài khoản Admin
│   ├── settings.php               # Cài đặt hệ thống
│   ├── export_stats.php           # Export báo cáo dạng CSV
│   └── style.css                  # CSS riêng của Admin panel
│
├── 📂 css/                        # CSS Frontend
├── 📂 js/                         # JavaScript Frontend
├── 📂 images/                     # Hình ảnh sản phẩm, banner, avatar
├── 📂 fonts/                      # Font chữ
├── 📂 scss/                       # Source SCSS (Bootstrap custom)
└── 📂 vendor/                     # Thư viện PHPMailer
```

---

## ✨ Tính năng hệ thống

### 👤 Dành cho Khách hàng (Frontend)

| Tính năng | Mô tả |
|-----------|-------|
| **Đăng ký tài khoản** | Validate đầy đủ: email, SĐT, mật khẩu · Gửi email xác nhận |
| **Đăng nhập / Đăng xuất** | Xác thực an toàn với password_hash/verify |
| **Quên mật khẩu** | Gửi OTP qua email · Reset mật khẩu |
| **Xem sản phẩm** | Danh sách + lọc theo danh mục + tìm kiếm + phân trang |
| **Chi tiết sản phẩm** | Gallery ảnh + sản phẩm liên quan + đánh giá/review |
| **Giỏ hàng** | Thêm/sửa/xóa sản phẩm · Tính tổng tiền có giảm giá |
| **Đặt hàng** | Form giao hàng · Tính phí ship theo địa chỉ |
| **Thanh toán COD** | Thanh toán khi nhận hàng |
| **Thanh toán QR** | Quét QR Banking · Polling tự động xác nhận |
| **Lịch sử đơn hàng** | Xem tất cả đơn đã đặt + trạng thái realtime |
| **Thông báo** | Bell notification khi trạng thái đơn thay đổi |
| **Hồ sơ cá nhân** | Cập nhật thông tin · Upload avatar · Đổi mật khẩu |
| **Đánh giá sản phẩm** | Viết review sau khi mua hàng |

### 👨‍💼 Dành cho Admin (Admin2)

| Tính năng | Mô tả |
|-----------|-------|
| **Dashboard** | Biểu đồ doanh thu 12 tháng · Thống kê tổng quan · Top danh mục |
| **Quản lý sản phẩm** | CRUD đầy đủ · Upload ảnh chính + ảnh phụ · Soft delete |
| **Quản lý danh mục** | CRUD · Thiết lập % giảm giá theo danh mục |
| **Quản lý đơn hàng** | Xem danh sách + chi tiết · Cập nhật trạng thái · Gửi email auto |
| **Quản lý khách hàng** | Xem thông tin · Lịch sử mua hàng · Tổng chi tiêu |
| **Thống kê** | Top sản phẩm bán chạy · Doanh thu theo tháng · Xuất PDF/CSV |
| **Thông báo** | Dropdown đơn hàng mới chờ xử lý |

---

## 🏗 Kiến trúc MVC & OOP

### Mô hình MVC

```
┌─────────────────────────────────────────────────────────┐
│                    TRÌNH DUYỆT (Browser)                │
└─────────────────────┬───────────────────────────────────┘
                      │  URL: ?action=sanpham&page=2
                      ▼
┌─────────────────────────────────────────────────────────┐
│              index.php — FRONT CONTROLLER               │
│  ① Load config.php → Autoloader::register()            │
│  ② session_start()                                      │
│  ③ $router = new Router()                              │
│  ④ $router->addRoutes([...])                           │
│  ⑤ $router->dispatch($action)                          │
└─────────────────────┬───────────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────┐
│           Router — Điều hướng đến Controller            │
│  'sanpham' → SanPhamController::index()                │
└─────────────────────┬───────────────────────────────────┘
                      │
          ┌───────────┼───────────┐
          ▼           ▼           ▼
    [Controller]   [Model]     [View]
   Xử lý logic    Truy vấn    Hiển thị
    SanPham-       HangHoa     sanpham
    Controller     PhanTrang   .php
```

### Các tính chất OOP trong dự án

#### 1. Đóng gói (Encapsulation)
```php
class Database {
    private static $instance = null; // Chỉ class này được truy cập
    private $conn;                   // Ẩn chi tiết kết nối
    private $host = 'localhost';

    public function getConnection() { // Interface công khai duy nhất
        return $this->conn;
    }
}
```

#### 2. Kế thừa (Inheritance)
```php
// Lớp CHA — chứa logic dùng chung
class BaseController {
    protected function render($view, $data = []) { /* load header + view + footer */ }
    protected function redirect($url) { /* chuyển hướng */ }
    protected function isLoggedIn() { return isset($_SESSION['khachhang']); }
    protected function requireLogin() { if (!$this->isLoggedIn()) $this->redirect('...'); }
    protected function json($data) { /* trả về JSON cho AJAX */ }
}

// Lớp CON — kế thừa toàn bộ, chỉ thêm logic riêng
class LoginController extends BaseController {
    public function index() {
        if ($this->isLoggedIn()) $this->redirect('index.php'); // Kế thừa
        // ...
        $this->render('login', ['error' => $error]);            // Kế thừa
    }
}
```

> **19 Controller** đều `extends BaseController`: Login, Registration, Cart, Order, Payment, Profile...

#### 3. Đa hình (Polymorphism)
Mỗi Controller implement phương thức `index()` theo cách riêng nhưng cùng interface:
```php
class HomeController extends BaseController {
    public function index() { /* hiển thị trang chủ với sản phẩm nổi bật */ }
}
class CartController extends BaseController {
    public function index() { /* hiển thị và xử lý giỏ hàng */ }
}
```

#### 4. Singleton Pattern (Database)
```php
class Database {
    private static $instance = null;
    private function __construct() { /* tạo kết nối PDO */ }
    private function __clone() {}    // Ngăn clone

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self(); // Chỉ tạo 1 lần duy nhất
        }
        return self::$instance;          // Luôn trả về instance cũ
    }
}
// Dùng ở mọi nơi: $this->conn = Database::getInstance()->getConnection();
```

---

## 🔧 Các lớp Core

### BaseController
Lớp cha trừu tượng hoá các tác vụ phổ biến:

| Phương thức | Mô tả |
|-------------|-------|
| `render($view, $data)` | Load header + view + footer, truyền data qua `extract()` |
| `renderPartial($view, $data)` | Chỉ load view (dùng cho AJAX, không có header/footer) |
| `redirect($url, $params)` | Redirect HTTP với query string tùy chọn |
| `post($key, $default)` | Lấy dữ liệu `$_POST` an toàn |
| `get($key, $default)` | Lấy dữ liệu `$_GET` an toàn |
| `isLoggedIn()` | Kiểm tra `$_SESSION['khachhang']` |
| `getCurrentUser()` | Trả về dữ liệu user đang đăng nhập |
| `requireLogin()` | Redirect về login nếu chưa đăng nhập |
| `json($data, $statusCode)` | Trả về JSON response cho AJAX |
| `validate($data, $rules)` | Validate dữ liệu với rules cấu hình |
| `setFlash($key, $message)` | Lưu flash message vào session |
| `getFlash($key)` | Lấy flash message và xóa ngay sau đó |

### Router
Điều hướng toàn bộ request qua 1 file duy nhất:

| Phương thức | Mô tả |
|-------------|-------|
| `addRoutes($routes)` | Đăng ký mảng `['action' => 'ControllerClass']` |
| `dispatch($action)` | Tìm route khớp, tạo controller, gọi method |

### Autoloader
```php
spl_autoload_register([__CLASS__, 'load']); // Đăng ký với PHP
// Khi gặp class chưa biết → tự tìm file trong /Core/, /Model/, /Controller/
```

### Validator
```php
Validator::isEmailValid($email);   // Kiểm tra định dạng email
Validator::isPhoneValid($phone);   // Bắt đầu bằng 0, đủ 10 số
Validator::isPasswordValid($pass); // Tối thiểu 6 ký tự
Validator::isNameValid($name);     // Không chứa số hay ký tự đặc biệt
```

---

## 🗄 Mô hình dữ liệu

### Các bảng chính trong database `mycfshop`

```
khachhang          hanghoa              loaisanpham
─────────          ───────              ───────────
id_khachhang  ←──┐ id_hanghoa      ┌──► id_loai
ten_khachhang    │ ten_hanghoa     │    ten_loai
email            │ mo_ta           │
password (hash)  │ gia             │  giamgia_danhmuc
dia_chi          │ hinh_anh        │  ────────────────
so_dien_thoai    │ id_loai ────────┘  id_giamgia
ngay_dang_ky     │ so_luong           id_loai (FK)
                 │ trang_thai         phan_tram_giam
donhang          │                    trang_thai
───────          │
id_donhang       │ chitietdonhang     reviews
id_khachhang ───►┘ ───────────────   ───────
ten_nguoi_nhan     id_chitiet         id_review
dia_chi_giao       id_donhang (FK)   id_hanghoa (FK)
so_dien_thoai      id_hanghoa (FK)   id_khachhang (FK)
tong_tien          so_luong           rating
phi_ship           gia                noi_dung
trang_thai         thanh_tien         ngay_tao
phuong_thuc_tt
ngay_dat
```

### Trạng thái đơn hàng
```
Chờ xử lý ──► Đang giao ──► Hoàn thành
     └─────────────────────► Đã hủy
```

---

## 🔄 Luồng hoạt động chính

### Luồng mua hàng hoàn chỉnh
```
1. [Xem sản phẩm] ?action=sanpham
   → SanPhamController → HangHoa::layTatCa(limit, offset) → View sanpham.php

2. [Thêm vào giỏ] ?action=cart (POST)
   → CartController → GioHang::them($id) → $_SESSION['giohang'][$id] = [...]

3. [Đặt hàng] ?action=order (POST)
   → OrderController → tinhPhiShip($dia_chi)
   → $_SESSION['order_info'] = [ten, dia_chi, sdt, phi_ship]
   → redirect payment

4. [Thanh toán] ?action=payment (POST)
   → PaymentController
   → HoaDon::taoHoaDon() → INSERT donhang + chitietdonhang
   → GioHang::xoaTatCa() → $_SESSION['giohang'] = []
   → redirect payment_success

5. [Admin cập nhật] Admin2/donhang.php POST action=update_status
   → HoaDon::capNhatTrangThai()
   → ThongBao::taoThongBao() → Khách nhận bell notification
   → EmailService::sendOrderStatusUpdateEmail() → Khách nhận email
```

### Luồng đăng ký khách hàng
```
Registration form POST
→ RegistrationController::handleRegistration()
→ Validator::isEmailValid(), isPhoneValid(), isPasswordValid()
→ KhachHang::kiemTraEmail() → Kiểm tra email trùng
→ password_hash($password, PASSWORD_DEFAULT) → Hash bcrypt
→ KhachHang::dangKy() → INSERT INTO khachhang
→ EmailService::sendRegistrationEmail() → Gửi email chào mừng
```

### Luồng phân trang
```
URL: ?action=sanpham&page=2&loai=1
→ SanPhamController::getProducts($idLoai=1, $keyword='', $page=2)
→ HangHoa::layTheoLoai($idLoai) → đếm tổng (tongSo)
→ PhanTrang($tongSo, $page=2, $perPage=12)
   ├── offset = (2-1) * 12 = 12
   ├── limit  = 12
   └── tongSoTrang = ceil(tongSo / 12)
→ HangHoa::layTheoLoai($idLoai, limit=12, offset=12) → SQL: LIMIT 12 OFFSET 12
→ PhanTrang::taoHTML($base_url) → Render nút số trang
```

---

## 🔒 Bảo mật

| Kỹ thuật | Áp dụng ở đâu | Mục đích |
|----------|---------------|----------|
| **`password_hash()` / `password_verify()`** | Đăng ký, Đăng nhập | Mã hoá mật khẩu một chiều (bcrypt) |
| **PDO Prepared Statements** | Tất cả Model | Chống SQL Injection |
| **`htmlspecialchars()`** | Tất cả View | Chống XSS |
| **Session Management** | Đăng nhập, Giỏ hàng | Phân biệt user, lưu trạng thái |
| **Session Key riêng biệt** | Admin vs Customer | `$_SESSION['admin']` ≠ `$_SESSION['khachhang']` |
| **Kiểm tra đăng nhập** | Controller / Admin files | `requireLogin()` hoặc kiểm tra session thủ công |
| **`basename($_SERVER['PHP_SELF'])`** | Admin files | Đảm bảo đúng file được load |
| **`ob_start()` + `ob_clean()`** | AJAX responses | Ngăn output HTML rác làm hỏng JSON |

---

## 🚀 Hướng dẫn cài đặt

### Yêu cầu hệ thống
- **PHP** >= 7.4 (khuyến nghị PHP 8.x)
- **MySQL** >= 5.7 hoặc MariaDB >= 10.3
- **Web Server**: Apache/Nginx (khuyến nghị dùng **Laragon**)
- **PHPMailer**: đã có sẵn trong `/vendor/`

### Các bước cài đặt

**1. Tải dự án về máy**
```bash
# Giải nén hoặc clone vào thư mục web server
# Ví dụ với Laragon:
C:\laragon\www\DUANMAU\
```

**2. Tạo database**
```sql
-- Tạo database
CREATE DATABASE mycfshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Import dữ liệu mẫu
-- Dùng phpMyAdmin hoặc command line:
mysql -u root mycfshop < Model/mycfshop.sql
```

**3. Cấu hình kết nối database**

Mở file `Model/connect.php` và cập nhật thông tin:
```php
private $host     = 'localhost';  // Host MySQL
private $dbname   = 'mycfshop';   // Tên database
private $username = 'root';       // Username MySQL
private $password = '';           // Password MySQL (Laragon = rỗng)
```

**4. Cấu hình email (tùy chọn)**

Mở `Core/EmailService.php` và cập nhật thông tin SMTP nếu cần gửi email thực tế.

**5. Truy cập ứng dụng**
```
# Website khách hàng:
http://localhost/DUANMAU/

# Trang quản trị Admin:
http://localhost/DUANMAU/Admin2/
```

---

## 👤 Tài khoản mặc định

### Tài khoản Admin
| Trường | Giá trị |
|--------|---------|
| **URL** | `http://localhost/DUANMAU/Admin2/` |
| **Tên đăng nhập** | `admin` |
| **Mật khẩu** | `admin123` |

### Tài khoản Nhân viên (nếu có)
| Trường | Giá trị |
|--------|---------|
| **Tên đăng nhập** | `nhanvien` |
| **Mật khẩu** | `nhanvien123` |

### Tài khoản Khách hàng mẫu
| Trường | Giá trị |
|--------|---------|
| **URL** | `http://localhost/DUANMAU/` |
| **Email** | Đăng ký mới qua trang Registration |

> **Lưu ý:** Mật khẩu admin được lưu dưới dạng hash bcrypt trong database. Không cần thay đổi nếu dùng đúng tài khoản trên.

---

## 📖 Quy ước đặt tên

| Loại | Quy ước | Ví dụ |
|------|---------|-------|
| Class Controller | `PascalCase` + `Controller` | `LoginController`, `SanPhamController` |
| Class Model | `PascalCase` | `KhachHang`, `HangHoa`, `HoaDon` |
| File Controller | Trùng tên class + `.php` | `LoginController.php` |
| File Model | `lowercase.php` | `khachhang.php`, `hanghoa.php` |
| Method public | `camelCase` | `index()`, `layTatCa()`, `dangNhap()` |
| Method private | `camelCase` | `handleLogin()`, `getCategories()` |
| Property | `camelCase` | `$khachHangModel`, `$hoaDonModel` |
| Biến SQL | Tiếng Việt không dấu | `$tong_don_hang`, `$doanh_thu` |

---

## 💡 Lợi ích kiến trúc OOP + MVC

| Lợi ích | Mô tả cụ thể |
|---------|-------------|
| 🔧 **Dễ bảo trì** | Mỗi class làm 1 việc — sửa logic đăng nhập chỉ cần đụng `LoginController` |
| ♻️ **Không lặp code** | 19 Controller dùng chung `render()`, `redirect()`, `isLoggedIn()` từ BaseController |
| 📈 **Dễ mở rộng** | Thêm tính năng mới: tạo file Controller mới + 1 dòng route |
| 🔒 **Bảo mật tập trung** | Kiểm tra login, JSON response, validation đều ở BaseController |
| 1️⃣ **Singleton DB** | Chỉ 1 kết nối MySQL duy nhất cho toàn bộ ứng dụng — tiết kiệm tài nguyên |

---

## 👨‍💻 Tác giả

| Thông tin | Chi tiết |
|-----------|----------|
| **Sinh viên** | Nguyễn Xuân Dương |
| **Dự án** | Đồ án môn PHP 2 |
| **Kiến trúc** | MVC + OOP (Singleton, Front Controller) |
| **Bắt đầu** | 08/12/2025 |
| **Hoàn thành** | 15/03/2026 |

---

*© 2026 XDCOFFEEHOUSE — Đồ án PHP 2 | Nguyễn Xuân Dương*
