# ☕ XDTHECOFFEEHOUSE — Website Quản Lý Cửa Hàng Cà Phê Trực Tuyến

> **Đồ án tốt nghiệp** — Sinh viên: **Nguyễn Xuân Dương**
> Giảng viên hướng dẫn: **Nguyễn Thị Mai Trang**
> Kiến trúc: **Laravel 11 MVC** | Ngôn ngữ: **PHP 8.3** | CSDL: **MySQL / SQLite**

---

## 📋 Mục lục

- [Giới thiệu dự án](#-giới-thiệu-dự-án)
- [Công nghệ sử dụng](#-công-nghệ-sử-dụng)
- [Cấu trúc thư mục](#-cấu-trúc-thư-mục)
- [Tính năng hệ thống](#-tính-năng-hệ-thống)
- [Hệ thống phân quyền RBAC](#-hệ-thống-phân-quyền-rbac)
- [Mô hình dữ liệu](#-mô-hình-dữ-liệu)
- [Tích hợp API bên ngoài](#-tích-hợp-api-bên-ngoài)
- [Hướng dẫn cài đặt](#-hướng-dẫn-cài-đặt)
- [Tài khoản mặc định](#-tài-khoản-mặc-định)
- [Tác giả](#-tác-giả)

---

## 🎯 Giới thiệu dự án

**XDTHECOFFEEHOUSE** là một website thương mại điện tử chuyên về cà phê, được xây dựng trên nền tảng **Laravel 11** theo mô hình **MVC** hiện đại. Dự án hướng tới việc mô phỏng hoàn chỉnh quy trình vận hành của một cửa hàng cà phê thực tế — từ giao diện đặt hàng cho khách hàng đến bảng quản trị đa vai trò cho nhân viên.

**Điểm nổi bật của dự án:**

- 🤖 **CaféAI Chatbot** — Trợ lý ảo tích hợp **Google Gemini 2.0 Flash**, tự động nhận diện ngôn ngữ, tư vấn đồ uống theo thời tiết và tâm trạng, kiểm tra trạng thái đơn hàng, thêm sản phẩm vào giỏ hàng ngay từ chat
- 💳 **Thanh toán đa cổng** — VietQR (webhook tự động xác nhận), MoMo, PayPal (Sandbox), COD
- 🔐 **Phân quyền RBAC chặt chẽ** — 5 vai trò: Admin, Staff, Cashier, Warehouse, Customer (Spatie Permission)
- 🎛️ **Admin Panel Filament PHP** — Giao diện quản trị hiện đại, chuyên nghiệp với tone màu Coffee Premium
- 🔑 **OAuth2 Google** — Đăng nhập nhanh bằng tài khoản Google qua Laravel Socialite
- 📊 **Thống kê & Báo cáo** — Biểu đồ doanh thu Chart.js, xuất báo cáo Excel
- 🍹 **Tùy biến đồ uống linh hoạt** — Chọn size (M/L/XL), mức đường, đá, sữa, topping

---

## 🛠 Công nghệ sử dụng

### Backend

| Công nghệ | Phiên bản | Mục đích |
|-----------|-----------|----------|
| **PHP** | 8.3+ | Ngôn ngữ backend chính |
| **Laravel** | 11 / Framework 13.x | Framework MVC chính |
| **Filament PHP** | 5.x | Admin Panel |
| **Laravel Breeze** | 2.x | Xác thực (Auth scaffolding) |
| **Livewire** | 4.x | Component động phía server |
| **Spatie Permission** | 7.x | Hệ thống phân quyền RBAC |
| **Spatie Media Library** | 11.x | Quản lý upload ảnh |
| **Spatie Activity Log** | 5.x | Ghi nhật ký hoạt động |
| **Laravel Scout** | 11.x | Full-text search |
| **Laravel Socialite** | 5.x | OAuth2 (Google, Facebook) |
| **Eloquent Sluggable** | 13.x | Tự động tạo slug SEO |
| **laravel-dompdf** | 3.x | Xuất PDF |
| **Fast Excel** | 5.x | Xuất báo cáo Excel |
| **Intervention Image** | 4.x | Xử lý, resize ảnh |

### Frontend

| Công nghệ | Phiên bản | Mục đích |
|-----------|-----------|----------|
| **TailwindCSS** | 3.x | CSS utility framework (Filament Admin) |
| **Bootstrap** | 4.5 | Framework CSS (Frontend khách hàng) |
| **Alpine.js** | 3.x | JavaScript nhẹ cho tương tác UI |
| **Vite** | 8.x | Build tool, HMR |
| **Owl Carousel** | — | Slider trang chủ |
| **SweetAlert2** | 11 | Hộp thoại thông báo |
| **Font Awesome** | 5.x | Bộ icon |
| **Chart.js** | — | Biểu đồ thống kê Admin |

### Dịch vụ & API bên ngoài

| Dịch vụ | Mục đích |
|---------|----------|
| **Google Gemini 2.0 Flash** | Chatbot CaféAI — tư vấn, hỗ trợ khách hàng |
| **OpenWeatherMap API** | Gợi ý đồ uống theo thời tiết thực tế |
| **VietQR API** | Tạo mã QR thanh toán ngân hàng |
| **Casso / SePay Webhook** | Tự động xác nhận thanh toán VietQR |
| **PayPal SDK** | Cổng thanh toán quốc tế (Sandbox) |
| **MoMo** | Ví điện tử (Sandbox) |
| **Google OAuth2** | Đăng nhập bằng tài khoản Google |

---

## 📁 Cấu trúc thư mục

```
coffeeshop-laravel/
│
├── 📄 artisan                      # Laravel CLI
├── 📄 composer.json                # PHP dependencies
├── 📄 package.json                 # Node.js dependencies (Vite, Tailwind)
├── 📄 vite.config.js               # Cấu hình Vite build
├── 📄 .env.example                 # Mẫu cấu hình môi trường
│
├── 📂 app/
│   ├── 📂 Filament/                # Admin Panel (Filament PHP)
│   │   ├── 📂 Pages/               # Trang Dashboard, Settings tùy chỉnh
│   │   ├── 📂 Resources/           # CRUD resources: Products, Orders, Users, Categories, Banners
│   │   └── 📂 Widgets/             # Widget thống kê, biểu đồ doanh thu
│   │
│   ├── 📂 Http/
│   │   ├── 📂 Controllers/
│   │   │   ├── 📂 Auth/            # Xác thực: Login, Register, Password Reset, Socialite (Google)
│   │   │   ├── 📂 Shop/            # Frontend khách hàng
│   │   │   │   ├── HomeController.php       # Trang chủ + sản phẩm nổi bật
│   │   │   │   ├── ProductController.php    # Danh sách + chi tiết sản phẩm, tìm kiếm
│   │   │   │   ├── CartController.php       # Quản lý giỏ hàng
│   │   │   │   ├── OrderController.php      # Đặt hàng, theo dõi đơn
│   │   │   │   ├── PaymentController.php    # Xử lý thanh toán (COD/QR/MoMo/PayPal)
│   │   │   │   ├── ReviewController.php     # Đánh giá sản phẩm
│   │   │   │   └── ProfileController.php    # Hồ sơ cá nhân khách hàng
│   │   │   ├── 📂 Api/             # API endpoint (Chatbot, Payment status…)
│   │   │   ├── ProfileController.php        # Hồ sơ người dùng (chung)
│   │   │   └── WebhookController.php        # Nhận callback thanh toán (VietQR, PayPal, MoMo)
│   │   ├── 📂 Middleware/
│   │   └── 📂 Requests/            # Form Request Validation
│   │
│   ├── 📂 Models/                  # Eloquent Models
│   │   ├── User.php                # Người dùng (Spatie Permission)
│   │   ├── Product.php             # Sản phẩm (Sluggable, Media Library, Scout)
│   │   ├── Category.php            # Danh mục
│   │   ├── Order.php               # Đơn hàng
│   │   ├── OrderItem.php           # Chi tiết đơn hàng
│   │   ├── OrderItemModifier.php   # Tuỳ chỉnh modifier trong đơn
│   │   ├── ProductSize.php         # Size đồ uống (M/L/XL + giá)
│   │   ├── Modifier.php            # Modifier: đường, đá, sữa, topping
│   │   ├── Review.php              # Đánh giá sản phẩm
│   │   ├── Discount.php            # Mã giảm giá
│   │   ├── Banner.php              # Slider banner trang chủ
│   │   ├── ChatLog.php             # Lịch sử chat CaféAI
│   │   ├── EmailTemplate.php       # Template email động
│   │   └── ProductRequest.php      # Yêu cầu sản phẩm từ khách
│   │
│   ├── 📂 Services/
│   │   ├── CartService.php         # Business logic giỏ hàng
│   │   ├── PayPalService.php       # Tích hợp PayPal API
│   │   └── MoMoService.php         # Tích hợp MoMo API
│   │
│   ├── 📂 Jobs/                    # Queue jobs (gửi email nền…)
│   ├── 📂 Mail/                    # Mailable classes (email thông báo)
│   ├── 📂 Notifications/           # Laravel Notifications
│   ├── 📂 Observers/               # Model Observers (Activity Log)
│   ├── 📂 Livewire/                # Livewire components động
│   ├── 📂 Exports/                 # Excel Export classes (Fast Excel)
│   └── 📂 Providers/               # Service Providers
│
├── 📂 database/
│   ├── 📂 migrations/              # 28 migration files (cấu trúc đầy đủ)
│   ├── 📂 seeders/                 # Seeder: Users, Products, Categories, Modifiers, Banners…
│   └── 📂 factories/               # Factories cho testing
│
├── 📂 resources/
│   ├── 📂 views/                   # Blade Templates (Frontend)
│   ├── 📂 css/                     # CSS nguồn
│   └── 📂 js/                      # JavaScript nguồn
│
├── 📂 routes/
│   ├── web.php                     # Toàn bộ route web (Frontend + Auth + Webhook)
│   └── api.php                     # API routes (Chatbot, Payment status...)
│
├── 📂 public/                      # Thư mục public (assets, uploads)
├── 📂 storage/                     # Storage (logs, media, cache)
├── 📂 config/                      # Cấu hình Laravel
├── 📂 tests/                       # PHPUnit tests
└── 📂 docs/                        # Tài liệu đồ án
```

---

## ✨ Tính năng hệ thống

### 👤 Dành cho Khách hàng (Frontend)

| Tính năng | Mô tả |
|-----------|-------|
| **Đăng ký / Đăng nhập** | Breeze Auth, xác minh email, đặt lại mật khẩu |
| **Đăng nhập Google** | OAuth2 nhanh qua Laravel Socialite |
| **Duyệt sản phẩm** | Danh sách theo danh mục, tìm kiếm (Scout), phân trang |
| **Chi tiết sản phẩm** | Gallery ảnh, chọn size (M/L/XL), sản phẩm liên quan, đánh giá |
| **Tùy biến đồ uống** | Modifier linh hoạt: mức đường, đá, sữa, topping |
| **Giỏ hàng** | Thêm/sửa/xóa · Tính tổng tiền có kích cỡ & modifier |
| **Đặt hàng** | Form giao hàng đầy đủ · Tính phí ship |
| **Thanh toán COD** | Thanh toán khi nhận hàng |
| **Thanh toán VietQR** | Quét QR ngân hàng · Webhook tự động xác nhận |
| **Thanh toán MoMo** | Ví điện tử MoMo (Sandbox) |
| **Thanh toán PayPal** | Cổng quốc tế PayPal (Sandbox) |
| **Theo dõi đơn hàng** | Stepper trạng thái realtime + trạng thái pha chế |
| **Thông báo In-app** | Bell notification khi đơn thay đổi trạng thái |
| **Thông báo Email** | Email HTML tự động khi đặt hàng & cập nhật trạng thái |
| **Hồ sơ cá nhân** | Cập nhật thông tin, upload avatar, đổi mật khẩu |
| **Đánh giá sản phẩm** | Viết review sau khi mua hàng (phân loại AI) |
| **CaféAI Chatbot** | Trợ lý ảo Gemini: tư vấn theo thời tiết, kiểm tra đơn, thêm giỏ hàng từ chat |

### 👨‍💼 Dành cho Quản trị (Admin Panel — Filament PHP)

| Tính năng | Mô tả |
|-----------|-------|
| **Dashboard** | Biểu đồ doanh thu Chart.js · KPI tổng quan · Widget thống kê |
| **Quản lý sản phẩm** | CRUD đầy đủ · Upload ảnh (Media Library) · Soft delete · Slug SEO tự động |
| **Quản lý size & modifier** | Thiết lập size M/L/XL + giá · Cấu hình modifier (đường/đá/sữa/topping) |
| **Quản lý danh mục** | CRUD · Phân cấp danh mục |
| **Quản lý đơn hàng** | Xem danh sách + chi tiết · Cập nhật trạng thái · Gửi email auto |
| **Quản lý người dùng** | Phân vai trò · Xem lịch sử mua hàng · Soft delete |
| **Quản lý banner** | Upload & quản lý slider trang chủ |
| **Quản lý mã giảm giá** | CRUD discount/voucher |
| **Thống kê & Báo cáo** | Doanh thu theo tháng · Top sản phẩm · Xuất Excel |
| **Email Templates** | Tùy biến nội dung email thông báo động |
| **Phân loại Review** | API Text Classification tự động duyệt review chất lượng |
| **Activity Log** | Nhật ký hoạt động hệ thống (Spatie Activity Log) |

---

## 🔐 Hệ thống phân quyền RBAC

Sử dụng **Spatie Laravel Permission** với 5 vai trò và 17 quyền chi tiết:

| Vai trò | Quyền |
|---------|-------|
| **Admin** | Toàn quyền hệ thống |
| **Staff** | Xem/tạo/sửa sản phẩm · Xem/sửa đơn hàng · Xem khách hàng |
| **Cashier** | Xem sản phẩm · Xem/sửa đơn hàng |
| **Warehouse** | Xem/sửa sản phẩm · Xem đơn hàng |
| **Customer** | Mua hàng, đánh giá (Frontend) |

> Filament Admin Panel chỉ cho phép truy cập với vai trò **Admin**, **Staff**, **Cashier**, **Warehouse**.

---

## 🗄 Mô hình dữ liệu

### Các bảng chính trong database (28 migration files)

```
users                    products                  categories
─────                    ────────                  ──────────
id                   ┌──► id                   ┌──► id
name                 │    name                  │    name
email                │    slug                  │    slug
password (bcrypt)    │    description           │
phone                │    category_id ──────────┘
address              │    price
provider (OAuth)     │    stock
roles (RBAC) ────────┘    is_active
                          (Media Library)
                          has_size / has_modifier

orders                    order_items               modifiers
──────                    ───────────               ─────────
id                    ┌──► id                   ─── id
user_id ─────────────►│   order_id (FK)             name
status                │   product_id (FK)            type (sugar/ice/milk/topping)
drink_status          │   product_size_id (FK)       price_adjustment
payment_method        │   quantity                   is_active
payment_status        │   price
total_amount          └── OrderItemModifiers (pivot)
shipping_fee
cancel_reason

product_sizes             discounts                 banners
─────────────             ─────────                 ───────
id                        id                        id
product_id (FK)           code                      title
size (M/L/XL)             type (percent/fixed)      image_path
price                     value                     link
                          expires_at                is_active

reviews                   chat_logs                 email_templates
───────                   ─────────                 ───────────────
id                        id                        id
user_id (FK)              user_id (FK)              name
product_id (FK)           message                   subject
rating                    response                  body (HTML động)
content                   created_at
is_approved
```

### Trạng thái đơn hàng

```
Chờ xử lý ──► Đang xử lý ──► Đang giao ──► Hoàn thành
     └───────────────────────────────────► Đã hủy
```

### Trạng thái pha chế (Drink Status)

```
Chờ pha ──► Đang pha chế ──► Sẵn sàng giao
```

---

## 🌐 Tích hợp API bên ngoài

### CaféAI Chatbot (Google Gemini)
- Tự động nhận diện ngôn ngữ (Tiếng Việt / Tiếng Anh)
- Tư vấn đồ uống dựa trên thời tiết thực tế (OpenWeatherMap)
- Kiểm tra trạng thái đơn hàng theo yêu cầu
- Thêm sản phẩm vào giỏ hàng trực tiếp từ khung chat
- Fallback sang Gemini API khi Intent Detection nội bộ không khớp

### Thanh toán VietQR
- Tạo mã QR động theo từng đơn hàng
- Polling + Webhook tự động xác nhận thanh toán (Casso/SePay)

### PayPal & MoMo (Sandbox)
- Tích hợp SDK, chạy môi trường thử nghiệm
- Xử lý webhook callback an toàn

---

## 🚀 Hướng dẫn cài đặt

### Yêu cầu hệ thống

- **PHP** >= 8.3
- **Composer** >= 2.x
- **Node.js** >= 18.x & npm
- **MySQL** >= 8.0 hoặc MariaDB >= 10.6 (hoặc SQLite cho dev)
- **Web Server**: Apache / Nginx (khuyến nghị dùng **Laragon**)

### Các bước cài đặt

**1. Clone dự án**
```bash
git clone <repo-url> coffeeshop-laravel
cd coffeeshop-laravel
```

**2. Cài đặt dependencies**
```bash
composer install
npm install
```

**3. Cấu hình môi trường**
```bash
cp .env.example .env
php artisan key:generate
```

Mở file `.env` và cập nhật các thông tin cần thiết:
```env
APP_NAME="XDTHECOFFEEHOUSE"
APP_URL=http://localhost/coffeeshop-laravel/public

# Database (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coffeeshop_db
DB_USERNAME=root
DB_PASSWORD=

# Mail (cấu hình SMTP thực tế nếu cần)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password

# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://localhost/coffeeshop-laravel/public/auth/google/callback

# Gemini AI (CaféAI Chatbot)
CLAUDE_API_KEY=your_gemini_api_key

# OpenWeatherMap
OPENWEATHER_API_KEY=your_openweather_key
SHOP_CITY=Ho Chi Minh City

# VietQR
VIETQR_CLIENT_ID=your_vietqr_client_id
VIETQR_API_KEY=your_vietqr_api_key
VIETQR_BANK_ID=970415
VIETQR_ACCOUNT_NO=your_account_number
VIETQR_ACCOUNT_NAME=XDTHECOFFEEHOUSE

# PayPal (Sandbox)
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_client_secret
PAYPAL_MODE=sandbox

# MoMo (Sandbox)
MOMO_PARTNER_CODE=your_partner_code
MOMO_ACCESS_KEY=your_access_key
MOMO_SECRET_KEY=your_secret_key
```

**4. Tạo database và chạy migration**
```bash
php artisan migrate --seed
```

> Lệnh `--seed` sẽ tự động tạo dữ liệu mẫu: tài khoản, sản phẩm, danh mục, banner, modifier và email templates.

**5. Tạo symbolic link cho storage**
```bash
php artisan storage:link
```

**6. Build assets frontend**
```bash
npm run build
```

**7. Truy cập ứng dụng**

```
# Website khách hàng:
http://localhost/coffeeshop-laravel/public/

# Trang quản trị Admin (Filament):
http://localhost/coffeeshop-laravel/public/admin
```

### Chạy môi trường phát triển (Development)

```bash
# Chạy đồng thời server + queue + vite (dùng composer script):
composer run dev

# Hoặc chạy từng service riêng:
php artisan serve       # Laravel dev server
php artisan queue:listen --tries=1   # Queue worker (email, jobs)
npm run dev             # Vite HMR
```

---

## 👤 Tài khoản mặc định

> Các tài khoản được tạo tự động khi chạy `php artisan migrate --seed`

### Admin (Toàn quyền hệ thống)
| Trường | Giá trị |
|--------|---------|
| **URL Admin Panel** | `/admin` |
| **Email** | `admin@coffeeshop.com` |
| **Mật khẩu** | `admin123456` |

### Staff (Nhân viên)
| Trường | Giá trị |
|--------|---------|
| **Email** | `staff@coffeeshop.com` |
| **Mật khẩu** | `staff123456` |

### Customer (Khách hàng mẫu)
| Trường | Giá trị |
|--------|---------|
| **URL Frontend** | `/` |
| **Email** | `khachhang@example.com` |
| **Mật khẩu** | `customer123` |

> **Lưu ý:** Tất cả mật khẩu được lưu dưới dạng **bcrypt hash** trong database.

---

## 📖 Quy ước trong dự án

| Loại | Quy ước | Ví dụ |
|------|---------|-------|
| Controller | `PascalCase` + `Controller` | `ProductController`, `PaymentController` |
| Model (Eloquent) | `PascalCase`, số ít | `Product`, `Order`, `User` |
| Service | `PascalCase` + `Service` | `CartService`, `PayPalService` |
| Migration | `snake_case` theo thời gian | `2026_05_16_110002_create_products_table` |
| Route name | `snake_case` phân cấp | `shop.product.show`, `shop.order.index` |
| View (Blade) | `snake_case` theo thư mục | `shop.home`, `shop.product.detail` |
| Filament Resource | `PascalCase` + `Resource` | `ProductResource`, `OrderResource` |

---

## 💡 Kiến trúc & Điểm kỹ thuật nổi bật

| Điểm nổi bật | Chi tiết |
|-------------|----------|
| **Laravel 11 MVC** | Routing, Middleware, Eloquent ORM, Blade Templates |
| **Filament v5** | CRUD Resource đầy đủ, Widget, Dashboard tùy biến |
| **Spatie Stack** | Permission (RBAC) + Media Library (upload) + Activity Log |
| **Queue & Jobs** | Gửi email nền không block request |
| **Webhook xử lý** | Nhận callback thanh toán VietQR, PayPal, MoMo an toàn |
| **Livewire** | Component realtime không reload trang |
| **Laravel Scout** | Full-text tìm kiếm sản phẩm |
| **Vite HMR** | Hot Module Replacement cho môi trường dev |
| **Soft Delete** | Bảo vệ dữ liệu sản phẩm & người dùng |
| **Eloquent Sluggable** | Tự động tạo URL slug SEO-friendly |

---

## 📊 Kết quả & Hạn chế

### Kết quả đạt được
- ✅ Hoàn thiện toàn bộ chức năng mua hàng từ đầu đến cuối
- ✅ Tích hợp thành công AI Chatbot (Gemini), VietQR, PayPal, MoMo
- ✅ Admin Panel chuyên nghiệp với RBAC 5 vai trò
- ✅ Cơ sở dữ liệu chuẩn hóa, 14 model Eloquent với quan hệ đầy đủ
- ✅ Giao diện responsive, hiệu ứng mượt mà

### Hạn chế & Hướng phát triển
- ⚠️ Chưa có Redis Cache cho truy vấn tĩnh (danh mục, sản phẩm hot)
- ⚠️ MoMo & PayPal đang chạy Sandbox, chưa có giấy phép kinh doanh thật
- ⚠️ Một số form tùy biến đồ uống trên màn hình nhỏ cần cải thiện responsive
- 🔮 Kế hoạch: Redis Cache, Mobile App đồng bộ API, thanh toán thật

---

## 👨‍💻 Tác giả

| Thông tin | Chi tiết |
|-----------|----------|
| **Sinh viên** | Nguyễn Xuân Dương |
| **Đồ án** | Đồ án Tốt Nghiệp |
| **Kiến trúc** | Laravel 11 MVC + Filament PHP + Spatie Stack |
| **Giảng viên HD** | Nguyễn Thị Mai Trang |
| **Thời gian** | 05/2026 — 07/2026 |

---

*© 2026 XDTHECOFFEEHOUSE — Đồ án Tốt Nghiệp | Nguyễn Xuân Dương*
