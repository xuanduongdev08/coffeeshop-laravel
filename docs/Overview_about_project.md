# ☕ XDTHECOFFEEHOUSE — Tổng Quan Dự Án Website Bán Cà Phê Online

> **Đồ án tốt nghiệp** — Sinh viên: Nguyễn Xuân Dương
> Framework: **Laravel 11** | Ngôn ngữ: **PHP 8.x** | CSDL: **MySQL** | Frontend: **Blade + TailwindCSS + Bootstrap**

---

## 📋 Mục Lục

- [1. Giới thiệu tổng quan](#1-giới-thiệu-tổng-quan)
- [2. Công nghệ sử dụng](#2-công-nghệ-sử-dụng)
- [3. Kiến trúc hệ thống](#3-kiến-trúc-hệ-thống)
- [4. Hệ thống phân quyền](#4-hệ-thống-phân-quyền)
- [5. Chi tiết chức năng phía KHÁCH HÀNG](#5-chi-tiết-chức-năng-phía-khách-hàng)
  - [5.1 Trang chủ](#51-trang-chủ)
  - [5.2 Xác thực & Tài khoản](#52-xác-thực--tài-khoản)
  - [5.3 Danh sách sản phẩm](#53-danh-sách-sản-phẩm)
  - [5.4 Chi tiết sản phẩm](#54-chi-tiết-sản-phẩm)
  - [5.5 Giỏ hàng](#55-giỏ-hàng)
  - [5.6 Đặt hàng (Checkout)](#56-đặt-hàng-checkout)
  - [5.7 Thanh toán](#57-thanh-toán)
  - [5.8 Lịch sử & Chi tiết đơn hàng](#58-lịch-sử--chi-tiết-đơn-hàng)
  - [5.9 Hồ sơ cá nhân](#59-hồ-sơ-cá-nhân)
  - [5.10 Thông báo (Notification)](#510-thông-báo-notification)
  - [5.11 Đánh giá sản phẩm (Review)](#511-đánh-giá-sản-phẩm-review)
  - [5.12 CaféAI — Trợ lý ảo thông minh](#512-caféai--trợ-lý-ảo-thông-minh)
- [6. Chi tiết chức năng phía QUẢN TRỊ (Admin Panel)](#6-chi-tiết-chức-năng-phía-quản-trị-admin-panel)
  - [6.1 Dashboard](#61-dashboard)
  - [6.2 Quản lý Sản phẩm](#62-quản-lý-sản-phẩm)
  - [6.3 Quản lý Danh mục](#63-quản-lý-danh-mục)
  - [6.4 Quản lý Đơn hàng](#64-quản-lý-đơn-hàng)
  - [6.5 Quản lý trạng thái Pha chế (Drink Status)](#65-quản-lý-trạng-thái-pha-chế-drink-status)
  - [6.6 Quản lý Khách hàng](#66-quản-lý-khách-hàng)
  - [6.7 Quản lý Nhân viên](#67-quản-lý-nhân-viên)
  - [6.8 Thống kê & Báo cáo](#68-thống-kê--báo-cáo)
  - [6.9 Quản lý Mẫu Email](#69-quản-lý-mẫu-email)
  - [6.10 Hồ sơ Admin](#610-hồ-sơ-admin)
- [7. Chi tiết Giao diện người dùng (UI/UX)](#7-chi-tiết-giao-diện-người-dùng-uiux)
- [8. Mô hình dữ liệu (Database Schema)](#8-mô-hình-dữ-liệu-database-schema)
- [9. Luồng hoạt động chính](#9-luồng-hoạt-động-chính)
- [10. Tích hợp bên thứ ba (Third-party Integration)](#10-tích-hợp-bên-thứ-ba-third-party-integration)
- [11. Bảo mật](#11-bảo-mật)
- [12. Hệ thống Email tự động](#12-hệ-thống-email-tự-động)

---

## 1. Giới Thiệu Tổng Quan

**XDTHECOFFEEHOUSE** là một website thương mại điện tử chuyên về cà phê và đồ uống, được xây dựng trên nền tảng **Laravel 11** với kiến trúc MVC hiện đại. Website cung cấp đầy đủ các tính năng cho cả phía khách hàng (đặt hàng, thanh toán, theo dõi đơn) và phía quản trị (quản lý sản phẩm, đơn hàng, nhân viên, thống kê doanh thu).

### Điểm nổi bật của dự án:
- **Hệ thống thanh toán đa dạng**: COD, VietQR (chuyển khoản ngân hàng), PayPal, MoMo
- **CaféAI Chatbot**: Trợ lý ảo tích hợp Gemini AI, hỗ trợ tư vấn sản phẩm, gợi ý theo thời tiết, theo dõi đơn hàng
- **Hệ thống phân quyền chi tiết**: 5 vai trò (Admin, Staff, Cashier, Warehouse, Customer) với quyền truy cập khác nhau
- **Theo dõi trạng thái pha chế**: Hệ thống tracking realtime quá trình pha chế đồ uống
- **Hệ thống thông báo & email tự động**: Thông báo in-app và email khi trạng thái đơn hàng thay đổi
- **Tùy chỉnh đồ uống**: Hỗ trợ chọn size (M/L/XL), topping, mức đường, mức đá, loại sữa

---

## 2. Công Nghệ Sử Dụng

| Công nghệ | Phiên bản | Mục đích |
|-----------|-----------|----------|
| **Laravel** | 11.x | Framework PHP chính |
| **PHP** | 8.x | Ngôn ngữ backend |
| **MySQL** | 8.0+ | Cơ sở dữ liệu quan hệ |
| **Blade** | (Laravel built-in) | Template engine |
| **TailwindCSS** | 3.x | Framework CSS cho Admin Panel |
| **Bootstrap** | 4.5 | Framework CSS cho Shop Frontend |
| **Vite** | 6.x | Build tool cho frontend assets |
| **Spatie Permission** | Latest | Quản lý phân quyền (RBAC) |
| **Spatie Media Library** | Latest | Quản lý media/file upload |
| **Spatie Activity Log** | Latest | Ghi log hoạt động |
| **Eloquent Sluggable** | Latest | Tự động tạo slug cho URL SEO-friendly |
| **Chart.js** | Latest | Biểu đồ thống kê trên Dashboard |
| **SweetAlert2** | 11 | Thông báo & dialog tương tác |
| **Owl Carousel** | Latest | Slider trang chủ |
| **Google Gemini API** | gemini-2.0-flash | AI Chatbot |
| **OpenWeatherMap API** | 2.5 | Lấy thông tin thời tiết cho chatbot |
| **VietQR API** | v2 | Tạo mã QR thanh toán ngân hàng |
| **PayPal SDK** | v2 | Thanh toán quốc tế |
| **MoMo API** | v2 | Thanh toán ví điện tử |
| **Laravel Breeze** | Latest | Xác thực người dùng (Auth scaffolding) |
| **Rap2hpoutre FastExcel** | Latest | Xuất file Excel báo cáo |
| **Filament** | Latest | Admin panel framework (base) |

---

## 3. Kiến Trúc Hệ Thống

### 3.1 Cấu trúc Controller

```
app/Http/Controllers/
├── Admin/                          ← Bảng quản trị
│   ├── AuthController.php          ← Đăng nhập/đăng xuất admin
│   ├── DashboardController.php     ← Trang tổng quan
│   ├── ProductController.php       ← CRUD sản phẩm
│   ├── CategoryController.php      ← CRUD danh mục
│   ├── OrderController.php         ← Quản lý đơn hàng
│   ├── DrinkStatusController.php   ← Cập nhật trạng thái pha chế
│   ├── CustomerController.php      ← Quản lý khách hàng
│   ├── EmployeeController.php      ← Quản lý nhân viên
│   ├── StatisticsController.php    ← Thống kê doanh thu
│   ├── EmailTemplateController.php ← Quản lý mẫu email
│   └── ProfileController.php       ← Hồ sơ admin
├── Shop/                           ← Phía khách hàng
│   ├── HomeController.php          ← Trang chủ
│   ├── ProductController.php       ← Danh sách & chi tiết sản phẩm
│   ├── CartController.php          ← Giỏ hàng (Session-based)
│   ├── OrderController.php         ← Đặt hàng & lịch sử đơn
│   ├── PaymentController.php       ← Thanh toán (COD, VietQR, PayPal, MoMo)
│   ├── ProfileController.php       ← Hồ sơ cá nhân khách hàng
│   └── ReviewController.php        ← Đánh giá sản phẩm
├── Api/                            ← API endpoints
│   ├── ChatController.php          ← CaféAI Chatbot (680 dòng code)
│   ├── NotificationController.php  ← API thông báo (lấy, đánh dấu đã đọc)
│   └── PaymentStatusController.php ← Kiểm tra trạng thái thanh toán (polling)
├── Auth/                           ← Xác thực (Laravel Breeze)
└── WebhookController.php           ← Nhận callback từ Casso/MoMo/PayPal
```

### 3.2 Cấu trúc Model

```
app/Models/
├── User.php           ← Người dùng (SoftDeletes, HasRoles, Notifiable)
├── Product.php        ← Sản phẩm (SoftDeletes, Sluggable, HasMedia)
├── Category.php       ← Danh mục sản phẩm (Sluggable)
├── ProductSize.php    ← Size sản phẩm (M, L, XL)
├── Modifier.php       ← Tùy chỉnh đồ uống (đường, đá, sữa, topping)
├── Order.php          ← Đơn hàng
├── OrderItem.php      ← Chi tiết đơn hàng (sản phẩm trong đơn)
├── OrderItemModifier.php ← Modifier đã chọn cho mỗi order item
├── Review.php         ← Đánh giá sản phẩm
├── Banner.php         ← Banner trang chủ
├── Discount.php       ← Chương trình giảm giá theo danh mục
├── ChatLog.php        ← Lịch sử chat CaféAI
├── ProductRequest.php ← Ghi nhận sản phẩm khách tìm nhưng không có
└── EmailTemplate.php  ← Mẫu email tùy chỉnh
```

### 3.3 Cấu trúc View

```
resources/views/
├── layouts/
│   ├── shop.blade.php        ← Layout chính cho frontend (Header, Footer, Chat widget)
│   ├── admin.blade.php       ← Layout chính cho admin panel (Sidebar, Topbar)
│   ├── app.blade.php         ← Layout cho Breeze auth
│   └── guest.blade.php       ← Layout cho trang đăng nhập/đăng ký
├── shop/                     ← Giao diện khách hàng
│   ├── home.blade.php        ← Trang chủ
│   ├── products/             ← Sản phẩm (index, show)
│   ├── cart/                 ← Giỏ hàng (index)
│   ├── orders/               ← Đơn hàng (checkout, history, show)
│   ├── payment/              ← Thanh toán (index, vietqr, success)
│   └── profile/              ← Hồ sơ (show, edit)
├── admin/                    ← Giao diện quản trị
│   ├── dashboard.blade.php   ← Bảng điều khiển
│   ├── products/             ← Sản phẩm (index, create, edit)
│   ├── categories/           ← Danh mục (index, create, edit)
│   ├── orders/               ← Đơn hàng (index, show)
│   ├── customers/            ← Khách hàng (index, show)
│   ├── employees/            ← Nhân viên (index, create, show)
│   ├── statistics/           ← Thống kê (index)
│   ├── email-templates/      ← Mẫu email (index, edit)
│   ├── profile/              ← Hồ sơ admin (edit)
│   └── auth/                 ← Đăng nhập admin
├── auth/                     ← Xác thực (login, register, forgot-password, reset-password, verify-email)
└── emails/                   ← Template email (layout)
```

---

## 4. Hệ Thống Phân Quyền

Website sử dụng **Spatie Laravel Permission** để quản lý phân quyền theo vai trò (RBAC — Role-Based Access Control).

### 4.1 Các vai trò trong hệ thống

| Vai trò | Mô tả | Khu vực truy cập |
|---------|--------|-------------------|
| **`admin`** | Quản trị viên cấp cao | Toàn quyền trên admin panel |
| **`staff`** | Nhân viên pha chế | Xem đơn hàng, cập nhật trạng thái pha chế |
| **`cashier`** | Thu ngân | Xem đơn hàng, cập nhật trạng thái đơn & thanh toán, xem thống kê |
| **`warehouse`** | Nhân viên kho | Xem sản phẩm, cập nhật tồn kho |
| **`customer`** | Khách hàng | Chỉ truy cập frontend (mua hàng, xem đơn, hồ sơ) |

### 4.2 Ma trận phân quyền chi tiết

| Chức năng | Admin | Cashier | Staff | Warehouse |
|-----------|:-----:|:-------:|:-----:|:---------:|
| Dashboard (tổng quan) | ✅ | ✅ | ✅ | ✅ |
| Hồ sơ cá nhân | ✅ | ✅ | ✅ | ✅ |
| **Sản phẩm** — Xem danh sách | ✅ | ✅ | ✅ | ✅ |
| **Sản phẩm** — Tạo / Xóa / Khôi phục | ✅ | ❌ | ❌ | ❌ |
| **Sản phẩm** — Chỉnh sửa (toàn bộ thông tin) | ✅ | ❌ | ❌ | ❌ |
| **Sản phẩm** — Cập nhật tồn kho | ✅ | ❌ | ❌ | ✅ |
| **Danh mục** — Xem | ✅ | ✅ | ✅ | ✅ |
| **Danh mục** — Tạo / Sửa / Xóa | ✅ | ❌ | ❌ | ❌ |
| **Đơn hàng** — Xem danh sách & chi tiết | ✅ | ✅ | ✅ | ❌ |
| **Đơn hàng** — Cập nhật trạng thái đơn | ✅ | ✅ | ❌ | ❌ |
| **Đơn hàng** — Cập nhật trạng thái thanh toán | ✅ | ✅ | ❌ | ❌ |
| **Đơn hàng** — Cập nhật trạng thái pha chế | ✅ | ❌ | ✅ | ❌ |
| **Khách hàng** — Xem & quản lý | ✅ | ❌ | ❌ | ❌ |
| **Nhân viên** — Xem & quản lý | ✅ | ❌ | ❌ | ❌ |
| **Thống kê** — Xem & xuất báo cáo | ✅ | ✅ | ❌ | ❌ |
| **Mẫu Email** — Xem & chỉnh sửa | ✅ | ❌ | ❌ | ❌ |

### 4.3 Middleware bảo vệ

- **`AdminMiddleware`**: Kiểm tra người dùng có role admin/staff/cashier/warehouse để cho truy cập admin panel
- **`role:admin`**: Middleware kiểm tra role cụ thể (VD: `role:admin|cashier`)
- **`CheckOrderOwnership`**: Kiểm tra đơn hàng thuộc về user đang đăng nhập

---

## 5. Chi Tiết Chức Năng Phía KHÁCH HÀNG

### 5.1 Trang Chủ

**Route**: `GET /` → `Shop\HomeController@index`
**View**: `shop/home.blade.php`

#### Giao diện:

1. **Hero Slider (Owl Carousel)**
   - Hiển thị banner quảng cáo dạng slideshow toàn màn hình (full-width)
   - Lấy từ bảng `banners` (vị trí `home`, sắp xếp theo `sort_order`)
   - Mỗi slide có: ảnh nền, tiêu đề, mô tả, nút CTA ("Đặt hàng ngay", "Xem sản phẩm")
   - Nếu chưa có banner → hiển thị 3 slide mặc định với ảnh tĩnh

2. **Info Bar — Thanh thông tin liên hệ**
   - Số điện thoại: +84 978 853 110
   - Địa chỉ: 93 Lê Cao Lãng, Quận Tân Phú, TP.HCM
   - Giờ mở cửa: 8:00AM - 21:00PM (Thứ 2 - Chủ nhật)

3. **About Section — Câu chuyện thương hiệu**
   - Layout 2 cột: ảnh bên trái + nội dung giới thiệu bên phải
   - Mô tả về niềm đam mê cà phê và cam kết chất lượng

4. **Services — Dịch vụ nổi bật**
   - 3 card dịch vụ: "Dễ dàng đặt hàng", "Giao hàng nhanh", "Cà phê chất lượng"
   - Mỗi card có icon, tiêu đề, mô tả ngắn

5. **Sản phẩm bán chạy (Featured Products)**
   - Grid 4 cột, hiển thị tối đa 8 sản phẩm `is_featured = true`
   - Mỗi sản phẩm card gồm: ảnh, tên, mô tả ngắn (60 ký tự), giá
   - Badge giảm giá (-XX%) nếu sản phẩm có `discount_price`
   - Badge "Hết hàng" nếu `stock = 0` (ảnh bị mờ, nút disable)
   - Nút "Thêm vào giỏ" (AJAX, không reload trang)
   - Nút "Xem tất cả sản phẩm" ở dưới

6. **Counter Section**
   - Bộ đếm số liệu với animation: 100 Chi nhánh, 85 Giải thưởng, 10.567 Khách hàng hài lòng, 900 Nhân viên
   - Background parallax

7. **Danh mục sản phẩm**
   - Grid card trên nền tối, mỗi danh mục hiển thị: emoji icon, tên danh mục, mô tả
   - Click vào danh mục → chuyển đến trang sản phẩm theo danh mục

---

### 5.2 Xác Thực & Tài Khoản

Sử dụng **Laravel Breeze** với giao diện tùy chỉnh.

#### 5.2.1 Đăng ký tài khoản
**Route**: `GET /register` → `Auth\RegisteredUserController@create`
**View**: `auth/register.blade.php`

- **Form đăng ký** gồm: Họ tên, Email, Mật khẩu, Xác nhận mật khẩu
- **Đăng nhập bằng mạng xã hội**: Hỗ trợ Google OAuth (trường `provider`, `provider_id`, `provider_token` trong bảng `users`)
- Tự động gán vai trò `customer` sau khi đăng ký
- Gửi email chào mừng tự động qua template `register_success`

#### 5.2.2 Đăng nhập
**Route**: `GET /login` → `Auth\AuthenticatedSessionController@create`
**View**: `auth/login.blade.php`

- Form đăng nhập: Email + Mật khẩu
- Checkbox "Ghi nhớ đăng nhập"
- Link đăng nhập Google OAuth
- Link "Quên mật khẩu?"

#### 5.2.3 Quên mật khẩu
**Route**: `GET /forgot-password`
**View**: `auth/forgot-password.blade.php`

- Nhập email → Gửi link reset mật khẩu qua email
- Trang reset password: nhập mật khẩu mới + xác nhận

#### 5.2.4 Xác minh email
**Route**: `GET /verify-email`
**View**: `auth/verify-email.blade.php`

---

### 5.3 Danh Sách Sản Phẩm

**Route**: `GET /san-pham` → `Shop\ProductController@index`
**View**: `shop/products/index.blade.php`

#### Chức năng:

1. **Hiển thị sản phẩm dạng Grid**
   - Layout grid responsive (4 cột trên desktop, 2 cột trên tablet, 1 cột trên mobile)
   - Mỗi card sản phẩm: ảnh, tên, mô tả ngắn, giá (giá gốc bị gạch ngang nếu có giảm giá), nút "Thêm vào giỏ"
   - Badge giảm giá (-XX%) hiển thị góc phải ảnh
   - Sản phẩm hết hàng: ảnh mờ + badge "Hết hàng" + nút disable
   - Phân trang (12 sản phẩm/trang)

2. **Tìm kiếm sản phẩm**
   - Ô tìm kiếm theo tên sản phẩm và tên danh mục
   - **Không tìm theo mô tả** (tránh false-positive, VD: Bánh Tiramisu có "cà phê" trong mô tả)

3. **Lọc theo danh mục**
   - Thanh sidebar hoặc dropdown danh mục
   - Click danh mục → lọc sản phẩm thuộc danh mục đó
   - Hiển thị breadcrumb danh mục đang chọn

4. **Sắp xếp sản phẩm**
   - Mới nhất (mặc định)
   - Giá tăng dần
   - Giá giảm dần
   - Theo tên (A-Z)

5. **Xem sản phẩm theo danh mục**
   **Route**: `GET /danh-muc/{category:slug}` → `Shop\ProductController@byCategory`
   - Hiển thị tất cả sản phẩm thuộc danh mục cụ thể

---

### 5.4 Chi Tiết Sản Phẩm

**Route**: `GET /san-pham/{product:slug}` → `Shop\ProductController@show`
**View**: `shop/products/show.blade.php`

#### Giao diện:

1. **Ảnh sản phẩm**
   - Ảnh chính lớn (có thể zoom)
   - Gallery ảnh phụ (nếu có — sử dụng Spatie Media Library)

2. **Thông tin sản phẩm**
   - Tên sản phẩm
   - Giá (nếu có `discount_price`: hiển thị giá gốc gạch ngang + giá giảm nổi bật)
   - Mô tả chi tiết (HTML)
   - Danh mục
   - Trạng thái tồn kho (Còn hàng / Hết hàng)

3. **Tùy chỉnh đồ uống (Modifiers) — nếu sản phẩm hỗ trợ**

   | Loại modifier | Điều kiện hiển thị | Mô tả |
   |---------------|-------------------|-------|
   | **Chọn Size** (M / L / XL) | `has_size = true` | Mỗi size có giá riêng (`ProductSize`) |
   | **Mức đường** | `allow_sugar = true` | VD: 100% đường, 70% đường, 50% đường, 0% đường |
   | **Mức đá** | `allow_ice = true` | VD: Đá bình thường, Ít đá, Nhiều đá, Không đá |
   | **Loại sữa** | `allow_milk = true` | VD: Sữa tươi, Sữa đặc, Sữa yến mạch (+extra_price) |
   | **Topping** | `has_topping = true` | VD: Thạch trái cây, Trân châu, Pudding (+extra_price) |

   - Modifier được phân loại theo `type`: `sugar`, `ice`, `milk`, `topping`
   - Modifier có thể có `extra_price > 0` (phụ phí) hoặc miễn phí
   - Phân biệt modifier cho **đồ uống dùng ly** (`applies_to_drink`) và **trà/nước trái cây** (`applies_to_tea_juice`)

4. **Nút "Thêm vào giỏ"**
   - Chọn số lượng (tối thiểu 1, tối đa = stock)
   - Click → AJAX request, không reload trang
   - Hiển thị thông báo SweetAlert2 khi thêm thành công

5. **Đánh giá sản phẩm**
   - Hiển thị rating trung bình (sao)
   - Danh sách review từ khách hàng đã mua (chỉ hiển thị `is_approved = true`)
   - Mỗi review: tên người đánh giá, rating (1-5 sao), nội dung, ngày tạo

6. **Sản phẩm liên quan**
   - Grid 4 sản phẩm cùng danh mục (khác sản phẩm đang xem)

---

### 5.5 Giỏ Hàng

**Route**: `GET /gio-hang` → `Shop\CartController@index`
**View**: `shop/cart/index.blade.php`

#### Đặc điểm kỹ thuật:
- **Session-based cart** (không cần đăng nhập để thêm sản phẩm)
- Mỗi combination (sản phẩm + size + modifiers) được lưu với key duy nhất: `p{product_id}_{size}_{modifier_ids}`

#### Chức năng:

1. **Thêm vào giỏ (AJAX)**
   **Route**: `POST /gio-hang/them` → `Shop\CartController@add`
   - Kiểm tra sản phẩm `is_active`, tồn kho
   - Tính giá theo size (nếu có) + phụ phí modifier
   - Nếu combination đã tồn tại → cộng dồn số lượng
   - Kiểm tra số lượng không vượt quá tồn kho
   - Trả JSON: `success`, `message`, `cart_count`

2. **Hiển thị giỏ hàng**
   - Bảng chi tiết: ảnh, tên sản phẩm, size (nếu có), modifier đã chọn, đơn giá, số lượng, thành tiền
   - Tổng cộng giỏ hàng

3. **Cập nhật số lượng (AJAX)**
   **Route**: `PATCH /gio-hang/cap-nhat/{rowId}` → `Shop\CartController@update`
   - Kiểm tra tồn kho realtime
   - Trả JSON: `item_total`, `cart_total`, `cart_count`

4. **Xóa sản phẩm (AJAX)**
   **Route**: `DELETE /gio-hang/xoa/{rowId}` → `Shop\CartController@remove`
   - Trả JSON: `cart_total`, `cart_count`, `is_empty`

5. **Xóa toàn bộ giỏ**
   **Route**: `GET|DELETE /gio-hang/xoa-tat-ca` → `Shop\CartController@clear`
   - Hỗ trợ cả GET (link) và DELETE (form)

---

### 5.6 Đặt Hàng (Checkout)

**Route**: `GET /dat-hang/xac-nhan` → `Shop\OrderController@checkout` (yêu cầu đăng nhập)
**View**: `shop/orders/checkout.blade.php`

#### Chức năng:

1. **Form thông tin giao hàng**
   - Họ tên người nhận (`recipient_name`)
   - Số điện thoại (`phone`)
   - Địa chỉ giao hàng chi tiết:
     - Dropdown **Tỉnh/Thành phố** (API VietNam Provinces)
     - Dropdown **Quận/Huyện** (tự động load theo tỉnh)
     - Dropdown **Phường/Xã** (tự động load theo quận)
     - Input **Số nhà/Tên đường** (`street_address`)
   - Ghi chú đơn hàng (`notes`)
   - Tự động điền thông tin từ hồ sơ cá nhân (nếu có)

2. **Tính phí ship tự động**
   - TP.HCM (province_code = 79): **15.000đ**
   - Tỉnh thành khác: **25.000đ**

3. **Hiển thị tóm tắt đơn hàng**
   - Danh sách sản phẩm trong giỏ (tên, size, modifier, số lượng, giá)
   - Tạm tính (subtotal)
   - Phí ship
   - **Tổng cộng = subtotal + shipping_fee**

4. **Tạo đơn hàng**
   **Route**: `POST /dat-hang/tao` → `Shop\OrderController@store`
   - Sử dụng **DB Transaction** để đảm bảo tính toàn vẹn
   - Kiểm tra tồn kho từng sản phẩm trước khi tạo đơn
   - Tạo bản ghi `Order` + nhiều `OrderItem` + `OrderItemModifier`
   - Tự động tạo `tracking_code`: `XD` + ID (padding 5 số, VD: XD00001)
   - **Trừ tồn kho** ngay khi đặt hàng
   - Nếu đơn có đồ uống (sản phẩm có size) → set `drink_status = 'pending'`
   - Xóa giỏ hàng sau khi tạo đơn thành công
   - Redirect sang trang chọn phương thức thanh toán

---

### 5.7 Thanh Toán

**Route**: `GET /thanh-toan/{order}` → `Shop\PaymentController@index` (yêu cầu đăng nhập)
**View**: `shop/payment/index.blade.php`

#### 5.7.1 Trang chọn phương thức thanh toán
- Hiển thị thông tin đơn hàng (mã đơn, tổng tiền)
- 4 phương thức thanh toán:

| Phương thức | Mô tả | Route |
|-------------|-------|-------|
| **COD** | Thanh toán khi nhận hàng | `POST /thanh-toan/cod/{order}` |
| **VietQR** | Quét QR chuyển khoản ngân hàng | `GET /thanh-toan/vietqr/{order}` |
| **PayPal** | Thanh toán quốc tế qua PayPal | `POST /thanh-toan/paypal/{order}` |
| **MoMo** | Thanh toán ví điện tử MoMo | `POST /thanh-toan/momo/{order}` |

#### 5.7.2 Thanh toán COD
- Set `payment_method = 'COD'`, `payment_status = 'pending'`
- Redirect sang trang thành công

#### 5.7.3 Thanh toán VietQR
**View**: `shop/payment/vietqr.blade.php`
- Gọi **VietQR API** (`api.vietqr.io`) để tạo QR code động
- Hiển thị mã QR chuyển khoản MB Bank
- Nội dung chuyển khoản: `tracking_code` (VD: XD00001)
- **Polling tự động** kiểm tra trạng thái thanh toán qua `PaymentStatusController`
- Khi nhận webhook từ **SePay/Casso** → tự động cập nhật `payment_status = 'paid'`

#### 5.7.4 Thanh toán PayPal
- Sử dụng **PayPal SDK v2** (PayPalService)
- Tạo order trên PayPal → Redirect user sang trang PayPal
- User thanh toán xong → PayPal redirect về `paypalReturn`
- Capture payment → cập nhật `payment_status = 'paid'`
- Xác thực bằng chữ ký PayPal

#### 5.7.5 Thanh toán MoMo
- Sử dụng **MoMo API v2** (MoMoService)
- Tạo link thanh toán MoMo → Redirect user
- User thanh toán xong → MoMo redirect về `momoReturn`
- Xác thực chữ ký (signature) từ MoMo
- Nếu chưa cấu hình MoMo → fallback sang VietQR

#### 5.7.6 Trang thanh toán thành công
**Route**: `GET /thanh-toan/thanh-cong/{order}` → `Shop\PaymentController@success`
**View**: `shop/payment/success.blade.php`
- Hiển thị thông tin đơn hàng đã đặt thành công
- Mã tracking, tổng tiền, phương thức thanh toán
- Gửi **email xác nhận đơn hàng** (`order_placed` template)
  - Bảng sản phẩm HTML chuyên nghiệp (ảnh, tên, size, số lượng, thành tiền)
  - Thông tin giao hàng, tổng tiền, link xem chi tiết đơn

---

### 5.8 Lịch Sử & Chi Tiết Đơn Hàng

#### 5.8.1 Lịch sử đơn hàng
**Route**: `GET /dat-hang/lich-su` → `Shop\OrderController@history`
**View**: `shop/orders/history.blade.php`

- Danh sách tất cả đơn hàng của user, sắp xếp mới nhất
- Mỗi đơn hiển thị: mã tracking, ngày đặt, tổng tiền, trạng thái (badge màu), nút xem chi tiết
- Phân trang (10 đơn/trang)

#### 5.8.2 Chi tiết đơn hàng
**Route**: `GET /dat-hang/{order}` → `Shop\OrderController@show` (middleware `CheckOrderOwnership`)
**View**: `shop/orders/show.blade.php`

- **Stepper trạng thái đơn hàng** (progress bar):
  - Chờ xử lý → Đang giao → Hoàn thành
  - Hoặc: Chờ xử lý → Đã hủy
- **Stepper trạng thái pha chế** (nếu đơn có đồ uống):
  - ✅ Đã nhận đơn → ☕ Đang pha chế → 🎉 Đã hoàn thành
- Bảng sản phẩm chi tiết (tên, size, modifiers đã chọn, số lượng, đơn giá, thành tiền)
- Thông tin giao hàng (người nhận, SĐT, địa chỉ)
- Tóm tắt tài chính (tạm tính, phí ship, tổng cộng)
- Phương thức thanh toán + trạng thái thanh toán

#### 5.8.3 Hủy đơn hàng
**Route**: `PATCH /dat-hang/{order}/huy` → `Shop\OrderController@cancel`
- Chỉ hủy được đơn đang ở trạng thái "Chờ xử lý"
- Nhập lý do hủy (`cancel_reason`)
- **Hoàn kho tự động**: tăng `stock` cho từng sản phẩm trong đơn

#### 5.8.4 Kiểm tra cập nhật đơn hàng (AJAX Polling)
**Route**: `GET /dat-hang/check-updates` → `Shop\OrderController@checkUpdates`
- Trả JSON 5 đơn hàng mới nhất (ID, tracking_code, status, drink_status)
- Frontend polling định kỳ để cập nhật trạng thái realtime

---

### 5.9 Hồ Sơ Cá Nhân

#### 5.9.1 Xem hồ sơ
**Route**: `GET /ho-so` → `Shop\ProfileController@show`
**View**: `shop/profile/show.blade.php`

- Thông tin cá nhân: Họ tên, Email, SĐT, Địa chỉ
- Avatar (ảnh đại diện)
- Thống kê tổng đơn hàng

#### 5.9.2 Chỉnh sửa hồ sơ
**Route**: `GET /ho-so/chinh-sua` → `Shop\ProfileController@edit`
**View**: `shop/profile/edit.blade.php`

**Các thao tác:**

| Thao tác | Route | Validate |
|----------|-------|----------|
| Cập nhật thông tin | `PATCH /ho-so/cap-nhat` | name (bắt buộc), phone (10-11 số), address (max 500) |
| Đổi mật khẩu | `POST /ho-so/doi-mat-khau` | current_password (bắt buộc), password (min 8, confirmed) |
| Upload avatar | `POST /ho-so/upload-avatar` | image (JPEG/PNG, max 2MB). Trả JSON (AJAX) |
| Xóa tài khoản | `DELETE /ho-so/xoa-tai-khoan` | Xác nhận bằng mật khẩu. Soft delete |

---

### 5.10 Thông Báo (Notification)

#### API Endpoints:

| Route | Phương thức | Mô tả |
|-------|-------------|-------|
| `GET /api/notifications` | GET | Lấy danh sách thông báo (mới nhất) |
| `POST /api/notifications/read-all` | POST | Đánh dấu tất cả là đã đọc |
| `POST /api/notifications/{id}/read` | POST | Đánh dấu 1 thông báo đã đọc |

#### Cơ chế hoạt động:
- Sử dụng **Laravel Notifications** (database channel)
- Thông báo được gửi khi:
  - Trạng thái đơn hàng thay đổi (qua `OrderObserver`)
  - Trạng thái pha chế thay đổi (qua `DrinkStatusUpdated` notification)
- Hiển thị bell icon trên navigation bar với badge đếm thông báo chưa đọc
- Dropdown hiển thị danh sách thông báo gần đây

---

### 5.11 Đánh Giá Sản Phẩm (Review)

**Route**: `POST /san-pham/{product}/danh-gia` → `Shop\ReviewController@store` (yêu cầu đăng nhập)

- Khách hàng đánh giá sản phẩm sau khi mua
- Form: Rating (1-5 sao), Nội dung đánh giá, Upload ảnh (tùy chọn)
- Review có trường `is_approved` — chỉ hiển thị khi được duyệt (mặc định `true`)

---

### 5.12 CaféAI — Trợ Lý Ảo Thông Minh

**Route**: `POST /api/chat` → `Api\ChatController@handle`
**Giao diện**: Widget chatbox floating ở góc phải dưới trang web

#### 5.12.1 Kiến trúc CaféAI

CaféAI là một hệ thống chatbot hybrid kết hợp xử lý local (rule-based) và AI (Google Gemini API):

```
Tin nhắn user → Detect Language → Detect Intent → Process Locally
                                                     ↓ (null = không xử lý được)
                                                   Gemini API Fallback
                                                     ↓ (lỗi)
                                                   Fallback Response
```

#### 5.12.2 Các Intent được hỗ trợ

| Intent | Trigger (Tiếng Việt) | Trigger (English) | Xử lý |
|--------|----------------------|-------------------|-------|
| **greeting** | "xin chào", "chào" | "hello", "hi", "hey" | Gemini AI |
| **product_lookup** | "menu", "cà phê", "giá" | "coffee", "price", "how much" | Local → Gemini fallback |
| **order_tracking** | "đơn hàng", "theo dõi" | "track order", "my order" | Local (query DB) |
| **weather** | "thời tiết", "nóng quá" | "weather", "hot day" | Local (thuần thời tiết) hoặc Gemini (kết hợp gợi ý) |
| **mood** | "mệt", "buồn", "vui" | "tired", "sad", "happy" | Local (gợi ý theo tâm trạng) |
| **recommendation** | "gợi ý", "nên uống" | "recommend", "suggest" | Local (sản phẩm bán chạy) |
| **escalation** | "giúp đỡ", "hotline" | "help", "support" | Local (thông tin liên hệ) |
| **general** | (không match pattern nào) | | Gemini AI |

#### 5.12.3 Tính năng nổi bật

1. **Đa ngôn ngữ**: Tự động phát hiện Tiếng Việt / English dựa trên ký tự Unicode
2. **Tìm sản phẩm thông minh**: Tách từng từ khóa và search OR (tránh cả cụm không match)
3. **Gợi ý theo thời tiết**: Tích hợp OpenWeatherMap API
   - Trời nóng (≥30°C) → đề xuất đồ uống lạnh
   - Trời lạnh (≤20°C) → đề xuất đồ uống nóng
   - Trời dễ chịu → đề xuất bất kỳ
4. **Gợi ý theo tâm trạng**: Mệt → cà phê đậm, Stress → trà thư giãn, Buồn → latte ấm, Vui → đồ uống tràn năng lượng
5. **Theo dõi đơn hàng**: Tra cứu đơn theo mã tracking hoặc liệt kê 5 đơn gần nhất
6. **Thêm vào giỏ từ chat**: Action `add_to_cart` cho phép thêm sản phẩm vào giỏ ngay trong chatbox
7. **Context menu**: Hiển thị product cards với nút "Thêm vào giỏ" trong phản hồi
8. **Lịch sử chat**: Lưu vào `chat_logs` (user_id, session_id, role, message, intent, language)
9. **Market gap logging**: Ghi nhận sản phẩm khách tìm nhưng không có (`product_requests`)
10. **Gemini AI Context**: Khi fallback sang Gemini, gửi kèm full menu (30 sản phẩm), thông tin thời tiết, lịch sử chat (12 tin nhắn gần nhất)

---

## 6. Chi Tiết Chức Năng Phía QUẢN TRỊ (Admin Panel)

### Truy cập Admin Panel
- **URL**: `/admin`
- **Đăng nhập**: `/admin/login`
- **Layout**: Sidebar trái cố định + Topbar + Content area (TailwindCSS)

---

### 6.1 Dashboard

**Route**: `GET /admin` → `Admin\DashboardController@index`
**View**: `admin/dashboard.blade.php`

#### Giao diện Dashboard:

1. **KPI Cards (Thẻ thống kê)**

   | Card | Dữ liệu | Biểu tượng |
   |------|----------|-----------|
   | Tổng đơn hàng | `Order::count()` | 📦 |
   | Đơn chờ xử lý | `Order::where('status', 'Chờ xử lý')` | ⏳ |
   | Tổng doanh thu | `Order::where('payment_status', 'paid')->sum('total')` | 💰 |
   | Tổng khách hàng | `User::role('customer')->count()` | 👥 |
   | Tổng sản phẩm đang bán | `Product::where('is_active', true)->count()` | 📋 |
   | Sản phẩm sắp hết hàng | `Product::where('stock', '<=', 5)->count()` | ⚠️ |

2. **Biểu đồ doanh thu 7 ngày** (Chart.js)
   - Biểu đồ đường/cột hiển thị doanh thu và số đơn hàng 7 ngày gần nhất
   - Bao gồm cả ngày không có đơn (giá trị = 0)

3. **Bảng đơn hàng mới nhất** (10 đơn gần nhất)
   - Mã tracking, khách hàng, tổng tiền, trạng thái (badge màu)

4. **Đơn hàng đang pha chế** (5 đơn gần nhất)
   - Hiển thị đơn có `drink_status` = `pending` hoặc `brewing`
   - Nút "Bắt đầu pha chế" / "Hoàn thành pha chế"

---

### 6.2 Quản Lý Sản Phẩm

**Controller**: `Admin\ProductController`
**Views**: `admin/products/` (index, create, edit)

#### 6.2.1 Danh sách sản phẩm
**Route**: `GET /admin/products`

- **Bảng sản phẩm** bao gồm cả sản phẩm đã xóa mềm (`withTrashed()`)
- **Bộ lọc**:
  - Tìm kiếm theo tên sản phẩm
  - Lọc theo danh mục (dropdown)
  - Lọc theo trạng thái: Đang bán (active), Ngừng bán (inactive), Đã xóa (deleted)
- **Thông tin hiển thị**: Ảnh thumbnail, tên, danh mục, giá (gốc + giảm giá), tồn kho, trạng thái, hành động
- **Phân trang**: 15 sản phẩm/trang, giữ query string khi phân trang
- **Hành động**: Sửa, Xóa (soft delete), Khôi phục (đối với SP đã xóa)

#### 6.2.2 Thêm sản phẩm
**Route**: `GET /admin/products/create` (form) | `POST /admin/products` (lưu)
**Quyền**: Chỉ `admin`

**Form thêm sản phẩm bao gồm:**

| Trường | Kiểu | Validate | Mô tả |
|--------|------|----------|-------|
| `name` | Text | required, max:255 | Tên sản phẩm |
| `category_id` | Select | nullable, exists:categories | Danh mục |
| `description` | Textarea (WYSIWYG) | nullable | Mô tả chi tiết |
| `price` | Number | required, min:0 | Giá gốc |
| `discount_price` | Number | nullable, lt:price | Giá giảm |
| `stock` | Number | required, integer, min:0 | Tồn kho |
| `image` | File | nullable, image, max:2MB | Ảnh sản phẩm |
| `is_active` | Checkbox | boolean | Đang bán? |
| `is_featured` | Checkbox | boolean | Sản phẩm nổi bật? |
| **Tuỳ chỉnh đồ uống** | | | |
| `has_topping` | Checkbox | boolean | Cho phép chọn topping? |
| `allow_sugar` | Checkbox | boolean | Cho phép chọn mức đường? |
| `allow_ice` | Checkbox | boolean | Cho phép chọn mức đá? |
| `allow_milk` | Checkbox | boolean | Cho phép chọn loại sữa? |
| **Chọn Size** | | | |
| `has_size_m` | Checkbox | boolean | Có size M? |
| `price_m` | Number | required_if:has_size_m,1 | Giá size M |
| `has_size_l` | Checkbox | boolean | Có size L? |
| `price_l` | Number | required_if:has_size_l,1 | Giá size L |
| `has_size_xl` | Checkbox | boolean | Có size XL? |
| `price_xl` | Number | required_if:has_size_xl,1 | Giá size XL |

**Xử lý khi lưu:**
- Upload ảnh vào `storage/app/public/products/` (lưu path: `storage/products/xxx.jpg`)
- Tự động tạo slug từ tên (Eloquent Sluggable)
- Nếu có size → tạo bản ghi `ProductSize` tương ứng
- `has_size = true` nếu ít nhất 1 size được chọn

#### 6.2.3 Chỉnh sửa sản phẩm
**Route**: `GET /admin/products/{product}/edit` (form) | `PUT|PATCH /admin/products/{product}` (lưu)
**Quyền**: `admin` (chỉnh sửa toàn bộ) | `warehouse` (chỉ được cập nhật tồn kho `stock`)

- Nếu user có role `warehouse` → chỉ validate trường `stock`, không cho sửa thông tin khác
- Nếu upload ảnh mới → xóa ảnh cũ khỏi storage
- Xóa tất cả size cũ rồi tạo lại (delete + recreate)

#### 6.2.4 Xóa sản phẩm (Soft Delete)
**Route**: `DELETE /admin/products/{product}`
**Quyền**: Chỉ `admin`

- Sử dụng **SoftDeletes** — sản phẩm không bị xóa vĩnh viễn, chỉ đánh dấu `deleted_at`
- Sản phẩm đã xóa vẫn hiển thị trong danh sách (filter "Đã xóa") và có thể khôi phục

#### 6.2.5 Khôi phục sản phẩm
**Route**: `POST /admin/products/{id}/restore`
**Quyền**: Chỉ `admin`

- Restore sản phẩm đã xóa mềm (set `deleted_at = null`)

---

### 6.3 Quản Lý Danh Mục

**Controller**: `Admin\CategoryController`
**Views**: `admin/categories/` (index, create, edit)
**Quyền**: Xem — tất cả role admin panel | Tạo/Sửa/Xóa — chỉ `admin`

#### 6.3.1 Danh sách danh mục
**Route**: `GET /admin/categories`

- Bảng hiển thị: tên danh mục, số sản phẩm (`withCount('products')`), trạng thái, thứ tự sắp xếp
- Sắp xếp theo `sort_order`
- Phân trang: 20 danh mục/trang

#### 6.3.2 Thêm / Sửa danh mục
**Route**: `GET /admin/categories/create` | `POST /admin/categories` | `GET /admin/categories/{category}/edit` | `PUT /admin/categories/{category}`

| Trường | Kiểu | Validate | Mô tả |
|--------|------|----------|-------|
| `name` | Text | required, max:255 | Tên danh mục |
| `description` | Textarea | nullable | Mô tả |
| `image` | File | nullable, image, max:2MB | Ảnh danh mục |
| `is_active` | Checkbox | boolean | Trạng thái hoạt động |
| `sort_order` | Number | integer, min:0 | Thứ tự sắp xếp |

- Tự động tạo slug từ tên
- Upload ảnh vào `storage/app/public/categories/`

#### 6.3.3 Xóa danh mục
**Route**: `DELETE /admin/categories/{category}`

- **Không cho xóa** nếu danh mục đang có sản phẩm (`products()->count() > 0`)
- Phải di chuyển hoặc xóa hết sản phẩm trước

---

### 6.4 Quản Lý Đơn Hàng

**Controller**: `Admin\OrderController`
**Views**: `admin/orders/` (index, show)

#### 6.4.1 Danh sách đơn hàng
**Route**: `GET /admin/orders`
**Quyền**: `admin`, `cashier`, `staff`

- **Tab lọc nhanh** theo trạng thái (hiển thị số lượng mỗi trạng thái):
  - Tất cả | Chờ xử lý | Đang giao | Hoàn thành | Đã hủy
- **Bộ lọc nâng cao**:
  - Trạng thái thanh toán: `pending`, `paid`, `failed`, `refunded`
  - Tìm kiếm: theo mã tracking, tên người nhận, SĐT
  - Khoảng ngày: từ ngày — đến ngày
- **Bảng đơn hàng**: Mã tracking, khách hàng, SĐT, tổng tiền, phương thức TT, trạng thái TT, trạng thái đơn, ngày đặt, hành động
- **Phân trang**: 20 đơn/trang

#### 6.4.2 Chi tiết đơn hàng
**Route**: `GET /admin/orders/{order}`
**Quyền**: `admin`, `cashier`, `staff`

- **Thông tin đơn hàng**: mã tracking, ngày đặt, ghi chú
- **Thông tin khách hàng**: tên, email (link đến trang quản lý khách hàng)
- **Thông tin giao hàng**: người nhận, SĐT, địa chỉ
- **Bảng sản phẩm**: tên, size, modifiers, số lượng, đơn giá, thành tiền
- **Tóm tắt tài chính**: tạm tính, phí ship, tổng cộng
- **Trạng thái pha chế** (nếu có đồ uống): hiển thị timeline pending → brewing → completed
- **Form cập nhật trạng thái** (xem 6.4.3)

#### 6.4.3 Cập nhật trạng thái đơn hàng
**Route**: `PATCH /admin/orders/{order}/status`
**Quyền**: `admin`, `cashier`

- Dropdown chọn trạng thái: `Chờ xử lý` | `Đang giao` | `Hoàn thành` | `Đã hủy`
- **Logic nghiệp vụ**:
  - Đơn đã `Hoàn thành` hoặc `Đã hủy` → **không được thay đổi** nữa
  - Chuyển sang `Đã hủy` → **hoàn kho** tự động (tăng `stock` cho từng sản phẩm)
  - Chuyển sang `Hoàn thành` → tự động set `payment_status = 'paid'` (nếu chưa paid)
- **Gửi email tự động** cho khách hàng (template `order_status_updated`)
  - Nội dung: tên khách, mã đơn, trạng thái mới, địa chỉ giao, tổng tiền, link xem đơn

#### 6.4.4 Cập nhật trạng thái thanh toán
**Route**: `PATCH /admin/orders/{order}/payment-status`
**Quyền**: `admin`, `cashier`

- Dropdown: `pending` | `paid` | `failed` | `refunded`
- **Logic nghiệp vụ**:
  - Đã `paid` → chỉ được chuyển sang `refunded`
  - Đã `refunded` → **không được thay đổi**

---

### 6.5 Quản Lý Trạng Thái Pha Chế (Drink Status)

**Controller**: `Admin\DrinkStatusController`
**Route**: `PATCH /admin/orders/{order}/drink-status`
**Quyền**: `admin`, `staff`

#### Luồng trạng thái pha chế:

```
pending (Đã nhận đơn) ──→ brewing (Đang pha chế) ──→ completed (Đã hoàn thành)
```

- Chỉ áp dụng cho đơn có sản phẩm đồ uống (`has_drink = true`, tức `drink_status != null`)
- Đơn bị hủy (`status = 'Đã hủy'`) → **không cho cập nhật** trạng thái pha chế
- Mỗi lần cập nhật:
  - `pending → brewing`: ghi nhận `brewing_at` (thời điểm bắt đầu pha)
  - `brewing → completed`: ghi nhận `completed_at` (thời điểm hoàn thành)
- **Gửi email tự động** cho khách (template `drink_status_updated`):
  - `brewing`: "Đồ uống của bạn đang được pha chế. Vui lòng chờ trong giây lát!"
  - `completed`: "Đồ uống của bạn đã hoàn thành và sẵn sàng được giao."
- **Gửi thông báo in-app** qua `DrinkStatusUpdated` notification

---

### 6.6 Quản Lý Khách Hàng

**Controller**: `Admin\CustomerController`
**Views**: `admin/customers/` (index, show)
**Quyền**: Chỉ `admin`

#### 6.6.1 Danh sách khách hàng
**Route**: `GET /admin/customers`

- Lọc chỉ user có role `customer`
- **Tìm kiếm**: theo tên, email, SĐT
- **Bảng**: tên, email, SĐT, ngày đăng ký, hành động (xem chi tiết, xóa)
- **Phân trang**: 20 khách/trang

#### 6.6.2 Chi tiết khách hàng
**Route**: `GET /admin/customers/{user}`

- Thông tin cá nhân: tên, email, SĐT, địa chỉ, avatar
- Lịch sử đơn hàng của khách hàng
- Tổng chi tiêu

#### 6.6.3 Xóa khách hàng
**Route**: `DELETE /admin/customers/{user}`

- **Soft Delete**: tài khoản bị vô hiệu hóa nhưng lịch sử đơn hàng vẫn được giữ lại
- **Không cho xóa** nếu khách còn đơn hàng đang xử lý (trạng thái "Chờ xử lý" hoặc "Đang giao")

---

### 6.7 Quản Lý Nhân Viên

**Controller**: `Admin\EmployeeController`
**Views**: `admin/employees/` (index, create, show)
**Quyền**: Chỉ `admin`

#### 6.7.1 Danh sách nhân viên
**Route**: `GET /admin/employees`

- Hiển thị user có role `admin`, `staff`, hoặc `cashier`
- **Tìm kiếm**: theo tên, email, SĐT
- **Lọc theo vai trò**: Admin | Staff | Cashier
- **Bảng**: tên, email, SĐT, vai trò (badge), hành động
- **Phân trang**: 20 nhân viên/trang

#### 6.7.2 Thêm nhân viên
**Route**: `GET /admin/employees/create` | `POST /admin/employees`

| Trường | Validate | Mô tả |
|--------|----------|-------|
| `name` | required, max:255 | Họ tên |
| `email` | required, unique:users | Email đăng nhập |
| `password` | required, min:8, confirmed | Mật khẩu |
| `phone` | nullable, max:20 | SĐT |
| `role` | required, in:admin,staff,cashier | Vai trò |

- Tự động hash mật khẩu và gán role

#### 6.7.3 Chi tiết nhân viên
**Route**: `GET /admin/employees/{user}`

- Thông tin cá nhân, vai trò
- Lịch sử hoạt động (nếu có)

#### 6.7.4 Thay đổi vai trò
**Route**: `PATCH /admin/employees/{user}/role`

- Không cho phép tự thay đổi role của chính mình
- Sync role mới (xóa role cũ, gán role mới)

#### 6.7.5 Xóa nhân viên
**Route**: `DELETE /admin/employees/{user}`

- Không cho xóa tài khoản của chính mình
- Soft Delete

---

### 6.8 Thống Kê & Báo Cáo

**Controller**: `Admin\StatisticsController`
**View**: `admin/statistics/index.blade.php`
**Quyền**: `admin`, `cashier`

#### 6.8.1 Trang thống kê
**Route**: `GET /admin/statistics`

**Bộ lọc kỳ**: 7 ngày | 30 ngày | 90 ngày | 365 ngày (mặc định: 30 ngày)

**Các phần hiển thị:**

1. **KPI tổng quan**
   - Tổng doanh thu kỳ này
   - Tổng số đơn hàng
   - Khách hàng mới

2. **Biểu đồ doanh thu theo ngày** (Chart.js)
   - Doanh thu + số đơn hàng từng ngày trong kỳ
   - Chỉ tính đơn đã thanh toán (`payment_status = 'paid'`)

3. **Top 10 sản phẩm bán chạy**
   - Bảng: ảnh, tên sản phẩm, tổng số lượng bán, tổng doanh thu
   - Chỉ tính đơn đã thanh toán

4. **Doanh thu theo danh mục**
   - Bảng/biểu đồ: tên danh mục, doanh thu
   - Sắp xếp giảm dần theo doanh thu

5. **Phương thức thanh toán**
   - Bảng: phương thức (COD, VietQR, PayPal, MoMo), số lượng đơn, tổng doanh thu

#### 6.8.2 Xuất báo cáo Excel
**Route**: `GET /admin/statistics/export`

- Xuất file Excel (`.xlsx`) bằng **Rap2hpoutre FastExcel**
- Nội dung: Mã đơn, Khách hàng, SĐT, Địa chỉ, Tổng tiền, Phương thức TT, Trạng thái TT, Trạng thái đơn, Ngày đặt
- Tên file: `don-hang-YYYYMMDD-HHmmss.xlsx`

---

### 6.9 Quản Lý Mẫu Email

**Controller**: `Admin\EmailTemplateController`
**Views**: `admin/email-templates/` (index, edit)
**Quyền**: Chỉ `admin`

#### 6.9.1 Danh sách mẫu email
**Route**: `GET /admin/email-templates`

- Hiển thị tất cả template email trong hệ thống
- Mỗi template: tên, mã key, tiêu đề, hành động (xem trước, chỉnh sửa)

#### 6.9.2 Các mẫu email trong hệ thống

| Template Key | Mô tả | Placeholders |
|-------------|-------|-------------|
| `register_success` | Email chào mừng đăng ký | `{customer_name}`, `{customer_email}`, `{website_link}` |
| `order_placed` | Xác nhận đặt hàng thành công | `{customer_name}`, `{order_code}`, `{recipient_name}`, `{phone}`, `{shipping_address}`, `{items_list}`, `{total_price}`, `{payment_method}`, `{order_link}` |
| `order_status_updated` | Cập nhật trạng thái đơn | `{customer_name}`, `{order_code}`, `{order_status}`, `{shipping_address}`, `{total_price}`, `{order_link}` |
| `drink_status_updated` | Cập nhật trạng thái pha chế | `{customer_name}`, `{order_code}`, `{drink_status_label}`, `{extra_note}`, `{order_link}` |

#### 6.9.3 Xem trước mẫu email
**Route**: `GET /admin/email-templates/{emailTemplate}/preview`

- Render template với dữ liệu mock (tên giả, mã đơn giả, v.v.)
- Hiển thị email HTML đầy đủ trong layout chuyên nghiệp

#### 6.9.4 Chỉnh sửa mẫu email
**Route**: `GET /admin/email-templates/{emailTemplate}/edit` | `PATCH /admin/email-templates/{emailTemplate}`

- Chỉnh sửa: Tiêu đề (subject) + Nội dung HTML (content)
- Hiển thị danh sách placeholders khả dụng cho từng template

---

### 6.10 Hồ Sơ Admin

**Controller**: `Admin\ProfileController`
**View**: `admin/profile/edit.blade.php`
**Quyền**: Tất cả role trong admin panel

| Chức năng | Route | Mô tả |
|-----------|-------|-------|
| Xem/sửa thông tin | `GET /admin/profile` | Họ tên, SĐT, Địa chỉ |
| Cập nhật thông tin | `PATCH /admin/profile/update` | Validate: name (bắt buộc), phone (max:20), address (max:500) |
| Đổi mật khẩu | `POST /admin/profile/password` | Xác minh mật khẩu hiện tại, mật khẩu mới tối thiểu 8 ký tự |
| Upload avatar | `POST /admin/profile/avatar` | Image (JPG/JPEG/PNG/WEBP, max 2MB), xóa avatar cũ tự động |

---

## 7. Chi Tiết Giao Diện Người Dùng (UI/UX)

### 7.1 Layout Frontend (Shop)

**File layout**: `layouts/shop.blade.php`

- **Header/Navigation Bar**:
  - Logo thương hiệu
  - Menu điều hướng: Trang chủ, Sản phẩm, Danh mục (dropdown)
  - Ô tìm kiếm sản phẩm
  - Icon giỏ hàng (badge số lượng sản phẩm)
  - Icon thông báo (bell icon, badge đếm chưa đọc, dropdown danh sách)
  - Avatar/menu tài khoản (đăng nhập/đăng ký hoặc hồ sơ/đăng xuất)
- **Footer**:
  - Thông tin liên hệ (địa chỉ, SĐT, email)
  - Liên kết nhanh
  - Copyright
- **CaféAI Chat Widget**: Button floating góc phải dưới, mở chatbox popup

### 7.2 Layout Admin Panel

**File layout**: `layouts/admin.blade.php`

- **Sidebar trái cố định**:
  - Logo + tên hệ thống
  - Menu điều hướng theo role:
    - 📊 Dashboard
    - 📦 Đơn hàng
    - 🛍️ Sản phẩm
    - 📁 Danh mục
    - 👥 Khách hàng (admin only)
    - 👨‍💼 Nhân viên (admin only)
    - 📈 Thống kê (admin, cashier)
    - ✉️ Mẫu Email (admin only)
  - Active state highlight cho trang hiện tại
- **Topbar**:
  - Breadcrumb
  - Thông báo dropdown
  - Avatar + menu tài khoản (Hồ sơ, Đăng xuất)
- **Content Area**: Responsive, card-based design

### 7.3 Responsive Design
- **Frontend**: Bootstrap 4.5 grid system — responsive từ mobile → desktop
- **Admin Panel**: TailwindCSS — sidebar collapse trên mobile, bảng dữ liệu scroll ngang

### 7.4 UI Components đặc biệt
- **SweetAlert2**: Dialog xác nhận xóa, thông báo thành công/lỗi
- **Owl Carousel**: Slider trang chủ (autoplay, dots, arrows)
- **Chart.js**: Biểu đồ doanh thu (line + bar chart)
- **Animate.css / ftco-animate**: Animation on scroll cho frontend
- **Parallax**: Background parallax cho section counter

---

## 8. Mô Hình Dữ Liệu (Database Schema)

### 8.1 Sơ đồ quan hệ chính

```
users (1) ──────────── (N) orders
  │                         │
  │                         ├── (N) order_items
  │                         │        │
  │                         │        └── (N) order_item_modifiers ──── (1) modifiers
  │                         │
  │                         └── tracking_code (XD00001, XD00002, ...)
  │
  ├── (N) reviews
  └── (N) chat_logs

categories (1) ──── (N) products
  │                     │
  └── (N) discounts     ├── (N) product_sizes
                        ├── (N) reviews
                        └── (N) order_items

banners (standalone)
email_templates (standalone)
product_requests (standalone — log sản phẩm khách tìm không có)
```

### 8.2 Bảng chi tiết

#### `users`
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | BIGINT PK | |
| name | VARCHAR(255) | Họ tên |
| email | VARCHAR(255) UNIQUE | Email đăng nhập |
| password | VARCHAR(255) | Mật khẩu (hashed) |
| phone | VARCHAR(20) | SĐT |
| address | TEXT | Địa chỉ |
| avatar | VARCHAR(255) | Đường dẫn ảnh đại diện |
| provider | VARCHAR(255) | OAuth provider (google, facebook) |
| provider_id | VARCHAR(255) | OAuth provider user ID |
| provider_token | TEXT | OAuth token |
| email_verified_at | TIMESTAMP | Thời điểm xác minh email |
| deleted_at | TIMESTAMP | Soft delete |
| created_at / updated_at | TIMESTAMP | |

#### `products`
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | BIGINT PK | |
| category_id | BIGINT FK | Danh mục |
| name | VARCHAR(255) | Tên sản phẩm |
| slug | VARCHAR(255) UNIQUE | URL slug |
| description | TEXT | Mô tả |
| price | DECIMAL(10,2) | Giá gốc |
| discount_price | DECIMAL(10,2) | Giá giảm (nullable) |
| image | VARCHAR(255) | Đường dẫn ảnh |
| stock | INTEGER | Tồn kho |
| is_active | BOOLEAN | Đang bán? |
| is_featured | BOOLEAN | Nổi bật? |
| has_size | BOOLEAN | Có hỗ trợ chọn size? |
| has_topping | BOOLEAN | Có topping? |
| allow_sugar | BOOLEAN | Cho chọn mức đường? |
| allow_ice | BOOLEAN | Cho chọn mức đá? |
| allow_milk | BOOLEAN | Cho chọn loại sữa? |
| deleted_at | TIMESTAMP | Soft delete |

#### `orders`
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | BIGINT PK | |
| user_id | BIGINT FK | Khách hàng |
| recipient_name | VARCHAR(255) | Tên người nhận |
| shipping_address | TEXT | Địa chỉ giao |
| phone | VARCHAR(20) | SĐT người nhận |
| subtotal | DECIMAL(10,2) | Tạm tính |
| shipping_fee | DECIMAL(10,2) | Phí ship |
| total | DECIMAL(10,2) | Tổng cộng |
| payment_method | VARCHAR(50) | Phương thức TT (COD/VietQR/PayPal/MoMo) |
| payment_status | VARCHAR(20) | Trạng thái TT (pending/paid/failed/refunded) |
| status | VARCHAR(50) | Trạng thái đơn (Chờ xử lý/Đang giao/Hoàn thành/Đã hủy) |
| tracking_code | VARCHAR(20) | Mã tracking (XD00001) |
| notes | TEXT | Ghi chú |
| cancel_reason | TEXT | Lý do hủy |
| drink_status | VARCHAR(20) | Trạng thái pha chế (pending/brewing/completed) |
| brewing_at | TIMESTAMP | Thời điểm bắt đầu pha |
| completed_at | TIMESTAMP | Thời điểm hoàn thành pha |

#### `order_items`
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | BIGINT PK | |
| order_id | BIGINT FK | Đơn hàng |
| product_id | BIGINT FK | Sản phẩm |
| product_name | VARCHAR(255) | Snapshot tên SP |
| product_image | VARCHAR(255) | Snapshot ảnh SP |
| size | VARCHAR(10) | Size đã chọn (M/L/XL) |
| base_price | DECIMAL(10,2) | Giá gốc theo size |
| modifier_extra | DECIMAL(10,2) | Phụ phí modifier |
| unit_price | DECIMAL(10,2) | Đơn giá (base + modifier) |
| price | DECIMAL(10,2) | Alias cho unit_price |
| quantity | INTEGER | Số lượng |
| subtotal | DECIMAL(10,2) | Thành tiền |

#### `modifiers`
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | BIGINT PK | |
| name | VARCHAR(255) | Tên modifier (VD: "70% đường") |
| type | VARCHAR(20) | Loại: sugar/ice/milk/topping |
| extra_price | DECIMAL(10,2) | Phụ phí (0 = miễn phí) |
| applies_to_drink | BOOLEAN | Áp dụng cho đồ uống dùng ly |
| applies_to_tea_juice | BOOLEAN | Áp dụng cho trà/nước trái cây |
| is_active | BOOLEAN | Hoạt động? |
| sort_order | INTEGER | Thứ tự sắp xếp |

---

## 9. Luồng Hoạt Động Chính

### 9.1 Luồng mua hàng hoàn chỉnh

```
┌─────────────────┐     ┌──────────────────┐     ┌───────────────────┐
│  1. XEM SẢN PHẨM │────→│  2. THÊM VÀO GIỎ  │────→│  3. XEM GIỎ HÀNG  │
│  /san-pham       │     │  AJAX POST        │     │  /gio-hang        │
│  Lọc, tìm kiếm  │     │  Session-based    │     │  Sửa SL, xóa SP   │
└─────────────────┘     └──────────────────┘     └────────┬──────────┘
                                                          │
                                                          ▼
┌─────────────────┐     ┌──────────────────┐     ┌───────────────────┐
│  6. HOÀN THÀNH   │←────│  5. THANH TOÁN    │←────│  4. CHECKOUT       │
│  /thanh-cong     │     │  COD/VietQR/      │     │  /dat-hang/xac-nhan│
│  Gửi email      │     │  PayPal/MoMo      │     │  Form giao hàng   │
│  xác nhận       │     │                    │     │  Tính phí ship    │
└────────┬────────┘     └──────────────────┘     └───────────────────┘
         │
         ▼
┌─────────────────┐     ┌──────────────────┐
│  7. THEO DÕI ĐƠN │────→│  8. ADMIN XỬ LÝ  │
│  /dat-hang/lich-su│     │  Cập nhật trạng   │
│  Polling AJAX    │     │  thái → Email +    │
│  Thông báo bell  │     │  Notification      │
└─────────────────┘     └──────────────────┘
```

### 9.2 Luồng xử lý đơn hàng (Admin)

```
Đơn mới ──→ "Chờ xử lý" ──→ (Admin/Cashier) ──→ "Đang giao" ──→ "Hoàn thành"
                │                                        │
                └──────────→ "Đã hủy" ←─────────────────┘ (chỉ từ Chờ xử lý)
                              ↳ Hoàn kho tự động

Song song (nếu có đồ uống):
"pending" ──→ (Staff) "Bắt đầu pha" ──→ "brewing" ──→ (Staff) "Hoàn thành" ──→ "completed"
```

### 9.3 Luồng thanh toán VietQR

```
User chọn VietQR ──→ Gọi API vietqr.io tạo QR ──→ Hiển thị QR cho user quét
                                                           │
User chuyển khoản ──→ Ngân hàng xác nhận ──→ Webhook Casso/SePay ──→ Cập nhật paid
                                                                          │
Frontend polling ──→ Kiểm tra payment_status ──→ Nếu paid ──→ Redirect success
```

---

## 10. Tích Hợp Bên Thứ Ba (Third-party Integration)

### 10.1 Google Gemini AI
- **Mục đích**: CaféAI Chatbot
- **Model**: `gemini-2.0-flash`
- **Config**: `GEMINI_API_KEY` trong `.env`
- **Endpoint**: `https://generativelanguage.googleapis.com/v1beta/models/{model}:generateContent`

### 10.2 OpenWeatherMap
- **Mục đích**: Lấy thông tin thời tiết cho chatbot gợi ý đồ uống
- **Config**: `OPENWEATHER_API_KEY`, `OPENWEATHER_CITY` trong `.env`
- **Endpoint**: `https://api.openweathermap.org/data/2.5/weather`

### 10.3 VietQR
- **Mục đích**: Tạo mã QR thanh toán ngân hàng
- **Config**: `VIETQR_BANK_ID`, `VIETQR_ACCOUNT_NO`, `VIETQR_ACCOUNT_NAME` trong `.env`
- **Endpoint**: `https://api.vietqr.io/v2/generate`

### 10.4 SePay / Casso (Webhook)
- **Mục đích**: Nhận webhook khi chuyển khoản thành công → tự động xác nhận thanh toán
- **Webhook URL**: `/api/sepay` hoặc `/webhook/casso`

### 10.5 PayPal
- **Mục đích**: Thanh toán quốc tế
- **Service**: `App\Services\PayPalService`
- **Config**: `PAYPAL_CLIENT_ID`, `PAYPAL_CLIENT_SECRET`, `PAYPAL_MODE` trong `.env`

### 10.6 MoMo
- **Mục đích**: Thanh toán ví điện tử
- **Service**: `App\Services\MoMoService`
- **Config**: `MOMO_PARTNER_CODE`, `MOMO_ACCESS_KEY`, `MOMO_SECRET_KEY` trong `.env`

### 10.7 Google OAuth (Socialite)
- **Mục đích**: Đăng nhập bằng tài khoản Google
- **Config**: `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET` trong `.env`

### 10.8 Spatie Packages
- **Permission**: Quản lý RBAC (roles & permissions)
- **Media Library**: Quản lý file upload
- **Activity Log**: Ghi log hoạt động

---

## 11. Bảo Mật

| Kỹ thuật | Áp dụng | Mục đích |
|----------|---------|----------|
| **Bcrypt Password Hashing** | Đăng ký, đổi mật khẩu | Mã hóa mật khẩu một chiều |
| **CSRF Protection** | Tất cả form POST/PATCH/PUT/DELETE | Chống Cross-Site Request Forgery |
| **Middleware Authentication** | Routes yêu cầu đăng nhập | Kiểm tra user đã login |
| **RBAC (Spatie Permission)** | Admin panel | Phân quyền theo vai trò |
| **AdminMiddleware** | `/admin/*` | Chỉ cho phép role admin/staff/cashier/warehouse |
| **CheckOrderOwnership** | Xem/hủy đơn hàng | Kiểm tra đơn thuộc về user |
| **Request Validation** | Tất cả form input | Validate dữ liệu đầu vào (Laravel FormRequest) |
| **Eloquent ORM** | Tất cả truy vấn DB | Chống SQL Injection (Prepared Statements) |
| **`abort_if()`** | PaymentController | Kiểm tra quyền sở hữu đơn hàng |
| **Webhook Signature** | MoMo, PayPal callback | Xác thực chữ ký giao dịch chống spoofing |
| **SoftDeletes** | User, Product | Xóa mềm, bảo toàn dữ liệu lịch sử |
| **Rate Limiting** | API routes | Giới hạn số request/phút |
| **CORS** | API routes | Kiểm soát truy cập cross-origin |

---

## 12. Hệ Thống Email Tự Động

### 12.1 Kiến trúc

- **Mailable Class**: `App\Mail\DynamicTemplateMail` — Mail dynamic dựa trên template từ DB
- **Email Layout**: `resources/views/emails/layout.blade.php` — Template HTML email chuyên nghiệp
- **Email Templates**: Lưu trong bảng `email_templates`, quản lý qua Admin Panel
- **Placeholder System**: Sử dụng `{placeholder_name}` trong template, replace bằng dữ liệu thực

### 12.2 Các sự kiện trigger email

| Sự kiện | Template | Người nhận | Trigger bởi |
|---------|----------|-----------|------------|
| Đăng ký tài khoản | `register_success` | Khách hàng | AuthController (Breeze) |
| Đặt hàng thành công | `order_placed` | Khách hàng | PaymentController@success |
| Cập nhật trạng thái đơn | `order_status_updated` | Khách hàng | Admin\OrderController@updateStatus |
| Cập nhật trạng thái pha chế | `drink_status_updated` | Khách hàng | Admin\DrinkStatusController@update |

### 12.3 Xử lý lỗi email
- Tất cả việc gửi email được bọc trong `try-catch`
- Lỗi được ghi vào `Log::warning()` mà không làm gián đoạn luồng chính
- Sử dụng session flag (VD: `sent_order_email_{id}`) để tránh gửi email trùng lặp

---

*© 2026 XDTHECOFFEEHOUSE — Đồ án tốt nghiệp | Nguyễn Xuân Dương*
