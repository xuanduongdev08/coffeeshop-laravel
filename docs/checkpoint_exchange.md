# 📍 Checkpoint — Tiến Độ Chuyển Đổi PHP Thuần → Laravel
> **Dự án:** XDTHECOFFEEHOUSE  
> **File kế hoạch gốc:** `docs/Exchange_to_laravel.md`  
> **Mục đích:** Ghi lại trạng thái hiện tại của codebase, theo dõi tiến độ từng Phase, cập nhật mỗi khi có thay đổi file/folder.  
> **Cập nhật lần cuối:** 01/06/2026 — Hoàn thành Phase 5 (Form Requests + NotificationBell), Phase 6 (ProcessPaymentWebhook Job), Phase 7 (CaféAI Gemini + Livewire Widget)

---

## 🗂️ Trạng Thái Tổng Quan Các Phase

| Phase | Tên | Trạng thái | Ghi chú |
|-------|-----|-----------|---------|
| Phase 1 | Khởi tạo dự án Laravel & cấu hình | ✅ **Hoàn thành** | Laravel 11, Breeze, Vite, Tailwind đã cài |
| Phase 2 | Database: Migration + Seeder + Model | ✅ **Hoàn thành** | 23 migrations, 7 seeders, 13 models |
| Phase 3 | Giao diện: Blade Layout + Assets | 🔄 **Một phần** | Layouts + components cơ bản có, shop views có nhưng chưa đầy đủ UI cũ |
| Phase 4 | Xác thực & Phân quyền | 🔄 **Một phần** | Breeze + Spatie Permission cài xong, Socialite có controller nhưng chưa cấu hình OAuth |
| Phase 5 | Các Module Chức Năng Chính | ✅ **Hoàn thành** | Controllers + Form Requests + NotificationBell Livewire đầy đủ |
| Phase 6 | Thanh toán nâng cao | ✅ **Hoàn thành** | VNPayService + MoMoService + ProcessPaymentWebhook Job |
| Phase 7 | CaféAI Chatbox | ✅ **Hoàn thành** | ChatController (Gemini API) + CaféAIChatbox Livewire widget |
| Phase 8 | Admin Dashboard (Filament) | ⏳ **Chưa làm** | Đang dùng Admin controllers + Blade views thủ công, chưa cài Filament |
| Phase 9 | Hoàn thiện & Deploy | ⏳ **Chưa làm** | — |

---

## 📁 Cấu Trúc Thư Mục Hiện Tại (Snapshot 21/05/2026)

### Thư mục gốc — file đáng chú ý
```
coffeeshop-laravel/
├── index.php                  ← PHP thuần cũ (router), CHƯA xóa
├── config.php                 ← PHP thuần cũ (DB config), CHƯA xóa
├── ajax_thongbao.php          ← PHP thuần cũ, CHƯA migrate
├── check_payment_status.php   ← PHP thuần cũ, CHƯA migrate
├── cafe_ai_chatbox.html       ← HTML tĩnh cũ, CHƯA migrate
├── webhook_log.txt            ← Log webhook thủ công, CHƯA xóa
├── mycfshop_laravel.sql       ← SQL dump DB mới (tham khảo)
├── artisan                    ← Laravel CLI ✅
├── composer.json              ← Packages đã cài ✅
├── vite.config.js             ← Vite build ✅
├── tailwind.config.js         ← Tailwind CSS ✅
└── .env                       ← Cấu hình môi trường ✅
```

### PHP Thuần Cũ — CÒN TỒN TẠI (chưa xóa, dùng để tham khảo)
```
Controller/          ← 23 file PHP thuần (CartController, OrderController, PaymentController...)
Core/                ← Autoloader, BaseController, EmailService, Router, Validator
Model/               ← connect.php, CafeAI.php, hanghoa.php, khachhang.php...
View/                ← 15 file PHP view (home.php, cart.php, payment.php...)
Admin2/              ← Toàn bộ admin cũ (dashboard, hanghoa, donhang, thongke...)
api/chat.php         ← API chatbot cũ
```


---

## ✅ PHASE 1 — Khởi Tạo Dự Án Laravel
**Trạng thái: HOÀN THÀNH**

### Đã làm
- [x] Laravel 11.x đã được khởi tạo
- [x] `laravel/breeze` cài xong, chạy `breeze:install blade`
- [x] Vite + Tailwind CSS cấu hình xong (`vite.config.js`, `tailwind.config.js`)
- [x] `.env` đã cấu hình (DB, mail, app key)
- [x] Cấu trúc thư mục `app/Http/Controllers/{Admin,Api,Auth,Shop}` đã tạo
- [x] `node_modules` đã install

### Packages đã cài (composer.json)
| Package | Version | Mục đích |
|---------|---------|---------|
| `laravel/framework` | ^13.8 | Core Laravel |
| `laravel/breeze` | ^2.4 | Auth scaffolding |
| `laravel/socialite` | ^5.27 | Đăng nhập Google/Facebook |
| `livewire/livewire` | ^4.3 | UI động real-time |
| `spatie/laravel-permission` | ^7.4 | Phân quyền role/permission |
| `spatie/laravel-medialibrary` | ^11.22 | Upload ảnh sản phẩm |
| `spatie/laravel-activitylog` | ^5.0 | Log hoạt động |
| `cviebrock/eloquent-sluggable` | ^13.0 | Slug URL SEO |
| `laravel/scout` | ^11.2 | Tìm kiếm full-text |
| `intervention/image-laravel` | ^4.0 | Resize/crop ảnh |
| `barryvdh/laravel-dompdf` | ^3.1 | Xuất PDF hóa đơn |
| `rap2hpoutre/fast-excel` | ^5.7 | Xuất Excel báo cáo |

### Packages DEV
| Package | Mục đích |
|---------|---------|
| `laravel/pint` | Code style formatter |
| `laravel/pail` | Log viewer CLI |
| `phpunit/phpunit` | Testing |

### Còn thiếu so với kế hoạch
- [ ] `darryldecode/cart` — giỏ hàng session (CartService hiện tự viết thủ công)
- [ ] `laravel/horizon` — Queue management
- [ ] `laravel/telescope` — Debug/monitoring
- [ ] `spatie/laravel-backup` — Backup tự động
- [ ] `spatie/laravel-settings` — Cài đặt hệ thống (thay cafe_ai_config)
- [ ] `filament/filament` — Admin dashboard (Phase 8)

### Config files đã tạo
```
config/app.php, auth.php, cache.php, database.php
config/filesystems.php, logging.php, mail.php
config/permission.php    ← Spatie Permission config
config/queue.php, services.php, session.php
config/sluggable.php     ← Eloquent Sluggable config
```
> ⚠️ `config/shop.php` chưa tạo (kế hoạch Phase 4 — Google Maps config)


---

## ✅ PHASE 2 — Database: Migration + Seeder + Model
**Trạng thái: HOÀN THÀNH**

### Migrations đã tạo (23 file)
```
database/migrations/
├── 0001_01_01_000000_create_users_table.php          ← Users (Breeze default, đã mở rộng)
├── 0001_01_01_000001_create_cache_table.php
├── 0001_01_01_000002_create_jobs_table.php
├── 2026_05_16_104753_create_permission_tables.php    ← Spatie Permission (roles, permissions)
├── 2026_05_16_104803_create_media_table.php          ← Spatie MediaLibrary
├── 2026_05_16_104821_create_activity_log_table.php   ← Spatie ActivityLog
├── 2026_05_16_110001_create_categories_table.php     ← Thay loaisanpham
├── 2026_05_16_110002_create_products_table.php       ← Thay hanghoa
├── 2026_05_16_110003_create_orders_table.php         ← Thay donhang
├── 2026_05_16_110004_create_order_items_table.php    ← Thay chitietdonhang
├── 2026_05_16_110005_create_reviews_table.php        ← Thay reviews
├── 2026_05_16_110006_create_notifications_table.php  ← Thay thongbao
├── 2026_05_16_110007_create_discounts_table.php      ← Thay giamgia_danhmuc
├── 2026_05_16_110008_create_banners_table.php        ← Thay quangcao
├── 2026_05_16_110009_create_chat_logs_table.php      ← Thay chat_logs
├── 2026_05_16_110010_create_product_requests_table.php ← Thay product_requests
├── 2026_05_17_000001_add_product_image_to_order_items_table.php
├── 2026_05_17_000002_create_product_sizes_table.php  ← MỚI: Size M/L/XL
├── 2026_05_17_000003_create_modifiers_table.php      ← MỚI: Đường/đá/sữa/topping
├── 2026_05_17_000004_create_order_item_modifiers_table.php ← MỚI: Modifier đã chọn
├── 2026_05_17_000005_add_modifier_flags_to_products_table.php ← has_size, has_topping...
├── 2026_05_17_000006_add_size_and_modifier_to_order_items_table.php ← size, base_price...
└── 2026_05_17_000007_add_drink_status_to_orders_table.php ← drink_status, brewing_at...
```

### Seeders đã tạo (7 file)
```
database/seeders/
├── DatabaseSeeder.php      ← Orchestrator chạy tất cả
├── CategorySeeder.php      ← 4 danh mục cà phê
├── ProductSeeder.php       ← 17 sản phẩm từ DB cũ
├── ProductSizeSeeder.php   ← Giá M/L/XL cho từng sản phẩm
├── ModifierSeeder.php      ← 15 modifiers (đường, đá, sữa, topping)
├── UserSeeder.php          ← Admin + khách hàng mẫu
└── BannerSeeder.php        ← 3 banner quảng cáo
```

### Factories đã tạo (3 file)
```
database/factories/
├── UserFactory.php
├── CategoryFactory.php
└── ProductFactory.php
```

### Eloquent Models đã tạo (13 file)
```
app/Models/
├── User.php              ← Thay khachhang + admin + nhanvien (dùng Spatie roles)
├── Category.php          ← Thay loaisanpham, có slug
├── Product.php           ← Thay hanghoa, có slug + SoftDeletes + MediaLibrary
├── Order.php             ← Thay donhang, có tracking_code + drink_status
├── OrderItem.php         ← Thay chitietdonhang, có size + modifier_extra
├── OrderItemModifier.php ← MỚI: Pivot modifier đã chọn
├── Review.php            ← Thay reviews
├── Discount.php          ← Thay giamgia_danhmuc
├── Banner.php            ← Thay quangcao
├── ChatLog.php           ← Thay chat_logs
├── ProductRequest.php    ← Thay product_requests
├── ProductSize.php       ← MỚI: Size M/L/XL
└── Modifier.php          ← MỚI: Nhóm modifier
```

### Còn thiếu so với kế hoạch
- [ ] `Employee` model riêng (hiện tại dùng `User` + Spatie roles)
- [ ] `Notification` model riêng (dùng Laravel built-in notifications table)
- [ ] `Setting` model (chờ cài `spatie/laravel-settings`)


---

## 🔄 PHASE 3 — Giao Diện: Blade Layout + Assets
**Trạng thái: MỘT PHẦN**

### Layouts đã tạo
```
resources/views/layouts/
├── app.blade.php       ← Layout Breeze mặc định
├── shop.blade.php      ← Layout frontend shop (header + footer)
├── admin.blade.php     ← Layout admin panel
├── guest.blade.php     ← Layout trang login/register
└── navigation.blade.php ← Navigation Breeze
```

### Blade Components đã tạo
```
resources/views/components/
├── navbar.blade.php              ← Thay View/headder.php
├── footer.blade.php              ← Thay View/footer.php
├── modal.blade.php               ← Modal popup
├── application-logo.blade.php    ← Logo app
├── auth-session-status.blade.php ← Flash status auth
├── danger-button.blade.php
├── dropdown.blade.php
├── dropdown-link.blade.php
├── input-error.blade.php
├── input-label.blade.php
├── nav-link.blade.php
├── primary-button.blade.php
├── responsive-nav-link.blade.php
├── secondary-button.blade.php
└── text-input.blade.php
```

### View Components PHP
```
app/View/Components/
├── AppLayout.php
└── GuestLayout.php
```

### Shop Views đã tạo
```
resources/views/shop/
├── home.blade.php                    ← Thay View/home.php
├── products/index.blade.php          ← Thay View/sanpham.php
├── products/show.blade.php           ← Thay View/sanphamchitiet.php
├── cart/index.blade.php              ← Thay View/cart.php
├── orders/checkout.blade.php         ← Thay View/order.php
├── orders/history.blade.php          ← Thay View/order_history.php
├── orders/show.blade.php             ← Thay View/order_detail.php
├── payment/index.blade.php           ← Thay View/payment.php
├── payment/success.blade.php         ← Thay View/payment_success.php
├── payment/vietqr.blade.php          ← Thay View/payment_qr.php
├── profile/edit.blade.php            ← Thay View/profile.php
└── profile/show.blade.php            ← Mới
```

### Auth Views (Breeze tự tạo)
```
resources/views/auth/
├── login.blade.php
├── register.blade.php
├── forgot-password.blade.php
├── reset-password.blade.php
├── confirm-password.blade.php
└── verify-email.blade.php
```

### Livewire Views
```
resources/views/livewire/
└── order-status-bell.blade.php   ← Chuông thông báo trạng thái đơn
```

### Assets
```
css/          ← CSS cũ (bootstrap, animate, owl, cafeai...) — CHƯA migrate vào resources/
js/           ← JS cũ (jquery, main.js, cafeai.js...) — CHƯA migrate vào resources/
images/       ← Ảnh sản phẩm + UI — CHƯA copy vào public/images/
resources/css/ ← Chỉ có app.css (Tailwind)
resources/js/  ← Chỉ có app.js (Alpine.js)
public/build/  ← Vite build output ✅
```

### Còn thiếu so với kế hoạch
- [ ] Copy CSS/JS cũ vào `resources/` và bundle qua Vite
- [ ] `x-shop-map` component (Google Maps footer) — Phase 4
- [ ] `x-product-card` component
- [ ] `x-cart-badge` component
- [ ] `x-alert` component (flash messages)
- [ ] `x-pagination` component
- [x] ~~Owl Carousel nav arrows trên trang con~~ — **FIXED 21/05**: Đổi sang `hero-page-header`
- [x] ~~Nền trắng section danh mục trang chủ~~ — **FIXED 21/05**: Đổi sang dark theme
- [x] ~~Pagination hiển thị text key~~ — **FIXED 21/05**: `Paginator::useBootstrap()`


---

## 🔄 PHASE 4 — Xác Thực & Phân Quyền
**Trạng thái: MỘT PHẦN**

### Đã làm
- [x] `laravel/breeze` cài + `breeze:install blade` — login/register/forgot-password views có
- [x] `laravel/socialite` cài xong
- [x] `spatie/laravel-permission` cài + migration chạy
- [x] `SocialiteController.php` đã tạo tại `app/Http/Controllers/Auth/`
- [x] `AdminMiddleware.php` đã tạo
- [x] `CheckOrderOwnership.php` đã tạo
- [x] `config/permission.php` đã publish

### Auth Controllers (Breeze)
```
app/Http/Controllers/Auth/
├── AuthenticatedSessionController.php   ← Login/Logout
├── RegisteredUserController.php         ← Register
├── PasswordResetLinkController.php      ← Forgot password
├── NewPasswordController.php            ← Reset password
├── ConfirmablePasswordController.php
├── EmailVerificationPromptController.php
├── EmailVerificationNotificationController.php
├── VerifyEmailController.php
├── PasswordController.php
└── SocialiteController.php              ← Google/Facebook login
```

### Middleware
```
app/Http/Middleware/
├── AdminMiddleware.php        ← Bảo vệ route /admin
└── CheckOrderOwnership.php   ← Kiểm tra đơn hàng thuộc về user
```

### Form Requests
```
app/Http/Requests/
├── ProfileUpdateRequest.php
└── Auth/LoginRequest.php
```

### Còn thiếu so với kế hoạch
- [ ] Cấu hình OAuth credentials trong `.env` (GOOGLE_CLIENT_ID, FACEBOOK_CLIENT_ID)
- [ ] Test luồng đăng nhập Google/Facebook thực tế
- [ ] `EnsureUserIsAdmin` middleware (hiện dùng `AdminMiddleware`)
- [ ] Seed roles: `admin`, `staff`, `cashier`, `warehouse`, `customer`
- [ ] `StoreOrderRequest` Form Request (validation đặt hàng)
- [ ] `StoreReviewRequest` Form Request (validation đánh giá)
- [ ] `config/shop.php` + `x-shop-map` component (Google Maps footer)
- [ ] Cấu hình `GOOGLE_MAPS_API_KEY` trong `.env`


---

## 🔄 PHASE 5 — Các Module Chức Năng Chính
**Trạng thái: HOÀN THÀNH**

### Controllers Shop đã tạo
```
app/Http/Controllers/Shop/
├── HomeController.php       ← Thay Controller/HomeController.php
├── ProductController.php    ← Thay SanPhamController + SanPhamChiTietController
├── CartController.php       ← Thay Controller/CartController.php (dùng AddToCartRequest)
├── OrderController.php      ← Thay Controller/OrderController.php (dùng StoreOrderRequest)
├── PaymentController.php    ← Thay Controller/PaymentController.php
├── ProfileController.php    ← Thay Controller/ProfileController.php
└── ReviewController.php     ← Thay Controller/review.php (dùng StoreReviewRequest)
```

### Form Requests đã tạo
```
app/Http/Requests/
├── ProfileUpdateRequest.php     ← Có sẵn từ Breeze
├── StoreOrderRequest.php        ← MỚI: Validate đặt hàng
├── StoreReviewRequest.php       ← MỚI: Validate đánh giá sản phẩm
└── AddToCartRequest.php         ← MỚI: Validate thêm vào giỏ hàng
```

### Services đã tạo
```
app/Services/
├── CartService.php    ← Logic giỏ hàng (tính giá size + modifier)
├── VNPayService.php   ← Tích hợp VNPay (Phase 6)
└── MoMoService.php    ← Tích hợp MoMo (Phase 6)
```

### Livewire Components
```
app/Livewire/
├── OrderStatusBell.php    ← Chuông thông báo trạng thái đơn hàng (polling 5s)
├── NotificationBell.php   ← MỚI: Chuông thông báo hệ thống (thay ajax_thongbao.php)
└── CafeAIChatbox.php      ← MỚI: CaféAI widget (Phase 7)
```

### Observers & Notifications
```
app/Observers/
└── OrderObserver.php          ← Tự động gửi notification khi drink_status thay đổi

app/Notifications/
└── DrinkStatusUpdated.php     ← Notification trạng thái pha chế
```

### API Controllers
```
app/Http/Controllers/Api/
├── ChatController.php           ← CaféAI (Gemini API)
├── NotificationController.php   ← CRUD notifications
└── PaymentStatusController.php  ← Polling trạng thái thanh toán
```

### Routes đã định nghĩa (routes/web.php)
**Frontend:**
- `GET /` — Trang chủ
- `GET /san-pham` — Danh sách sản phẩm
- `GET /san-pham/{slug}` — Chi tiết sản phẩm
- `GET /danh-muc/{slug}` — Sản phẩm theo danh mục
- `GET /gio-hang` — Giỏ hàng
- `POST /gio-hang/them` — Thêm vào giỏ
- `PATCH /gio-hang/cap-nhat/{rowId}` — Cập nhật số lượng
- `DELETE /gio-hang/xoa/{rowId}` — Xóa item
- `GET|DELETE /gio-hang/xoa-tat-ca` — Xóa toàn bộ
- `GET /dat-hang/xac-nhan` — Trang checkout *(auth)*
- `POST /dat-hang/tao` — Tạo đơn hàng *(auth)*
- `GET /dat-hang/lich-su` — Lịch sử đơn *(auth)*
- `GET /dat-hang/{order}` — Chi tiết đơn *(auth + ownership)*
- `PATCH /dat-hang/{order}/huy` — Hủy đơn *(auth + ownership)*
- `POST /san-pham/{product}/danh-gia` — Đánh giá *(auth)*
- `GET|PATCH|POST|DELETE /ho-so/*` — Profile *(auth)*

**Admin:**
- `GET /admin` — Dashboard *(auth + admin)*
- `RESOURCE /admin/products` — CRUD sản phẩm
- `RESOURCE /admin/categories` — CRUD danh mục
- `GET|PATCH /admin/orders/*` — Quản lý đơn hàng
- `GET|PATCH|DELETE /admin/users/*` — Quản lý người dùng
- `GET /admin/statistics` — Thống kê + export

**API (routes/api.php):**
- `POST /api/chat` — CaféAI chatbot
- `GET /api/payment-status/{order}` — Kiểm tra thanh toán *(auth)*
- `GET|POST /api/notifications/*` — Thông báo *(auth)*

**Webhook:**
- `POST /webhook/casso` — Webhook Casso
- `POST /webhook/momo` — Webhook MoMo
- `POST /webhook/vnpay` — Webhook VNPay

### Còn thiếu so với kế hoạch
- [ ] `darryldecode/cart` chưa cài — CartService hiện tự viết (cần kiểm tra hoạt động)
- [ ] `StoreOrderRequest` Form Request
- [ ] `StoreReviewRequest` Form Request
- [ ] `NotificationBell` Livewire component (thay `ajax_thongbao.php`)
- [ ] Module tìm kiếm Scout chưa cấu hình (`SCOUT_DRIVER=database`)
- [ ] `spatie/laravel-medialibrary` chưa cấu hình disk cho upload ảnh sản phẩm


---

## 🔄 PHASE 6 — Thanh Toán Nâng Cao
**Trạng thái: HOÀN THÀNH**

### Đã làm
- [x] `VNPayService.php` — createPaymentUrl, verifyReturn, isSuccess, extractTrackingCode
- [x] `MoMoService.php` — createPayment, verifySignature, isSuccess, extractTrackingCode
- [x] `WebhookController.php` — verify signature → dispatch `ProcessPaymentWebhook` Job
- [x] `ProcessPaymentWebhook.php` — **MỚI**: Job xử lý webhook bất đồng bộ (Casso/MoMo/VNPay)
- [x] Routes thanh toán đầy đủ (COD, VNPay, MoMo, VietQR)
- [x] Routes webhook (Casso, MoMo, VNPay) — CSRF exempt
- [x] Views: `payment/index.blade.php`, `payment/success.blade.php`, `payment/vietqr.blade.php`
- [x] VietQR động qua API vietqr.io (trong PaymentController::generateVietQR)

### Jobs đã tạo
```
app/Jobs/
└── ProcessPaymentWebhook.php   ← MỚI: Xử lý webhook bất đồng bộ
                                   - processCasso(): tìm XD\d+ trong description
                                   - processMoMo(): verify resultCode=0
                                   - processVNPay(): verify ResponseCode=00
                                   - tries: 3, timeout: 30s
```

### Còn thiếu (không bắt buộc)
- [ ] Test thực tế VNPay sandbox (cần VNPAY_TMN_CODE + VNPAY_HASH_SECRET)
- [ ] Test thực tế MoMo sandbox (cần MOMO_PARTNER_CODE + MOMO_ACCESS_KEY + MOMO_SECRET_KEY)
- [ ] `laravel/horizon` — Queue management UI (tùy chọn)

---

## 🔄 PHASE 7 — CaféAI Chatbox
**Trạng thái: HOÀN THÀNH**

### Đã làm
- [x] `ChatController.php` — đầy đủ intent detection + local processing + Gemini API fallback
- [x] `ChatLog` model + migration — lưu lịch sử chat
- [x] `ProductRequest` model + migration — log sản phẩm khách hỏi nhưng không có
- [x] Route `POST /api/chat` — không cần auth (guest OK)
- [x] **Fix:** ChatController đã được cập nhật dùng `config('services.gemini.api_key')` thay vì `config('services.claude.api_key')` — đồng bộ với `.env` có `GEMINI_API_KEY`
- [x] **MỚI:** `CafeAIChatbox` Livewire component — thay `cafe_ai_chatbox.html` cũ
- [x] **MỚI:** `resources/views/livewire/cafe-ai-chatbox.blade.php` — UI widget đầy đủ
- [x] CaféAI widget đã được thêm vào `layouts/shop.blade.php`

### Intents được xử lý
| Intent | Xử lý |
|--------|-------|
| `greeting` | Chào hỏi + thời tiết |
| `product_lookup` | Tìm sản phẩm theo tên |
| `order_tracking` | Xem đơn hàng (cần auth) |
| `weather` | Thời tiết + gợi ý đồ uống |
| `mood` | Gợi ý theo tâm trạng (mệt/vui/buồn/stress) |
| `recommendation` | Gợi ý sản phẩm bán chạy |
| `escalation` | Thông tin liên hệ shop |
| `general` | Fallback → Gemini API |

### Cấu hình cần thiết trong .env
```env
GEMINI_API_KEY=AIzaSy...          # Đã có
GEMINI_MODEL=gemini-2.0-flash     # Đã có
OPENWEATHER_API_KEY=629aab2f...   # Đã có
SHOP_CITY="Ho Chi Minh City"      # Đã có
```

### Còn thiếu (tùy chọn)
- [ ] `spatie/laravel-settings` — thay bảng `cafe_ai_config` (không bắt buộc)
- [ ] Intent `ordering` — add to cart trực tiếp từ chat (tính năng nâng cao)

---

## ⏳ PHASE 8 — Admin Dashboard (Filament)
**Trạng thái: CHƯA LÀM**

### Hiện tại (thay thế tạm)
Đang dùng Admin controllers + Blade views thủ công:
```
app/Http/Controllers/Admin/
├── DashboardController.php
├── ProductController.php
├── CategoryController.php
├── OrderController.php
├── UserController.php
├── StatisticsController.php
└── DrinkStatusController.php

resources/views/admin/
├── dashboard.blade.php
├── products/{index,create,edit}.blade.php
├── categories/{index,create,edit}.blade.php
├── orders/{index,show}.blade.php
├── users/{index,show}.blade.php
└── statistics/index.blade.php
```

### Còn thiếu so với kế hoạch
- [ ] `filament/filament` chưa cài
- [ ] Filament Resources chưa tạo
- [ ] Filament Widgets (StatsOverview, RevenueChart, LatestOrders) chưa tạo
- [ ] `maatwebsite/excel` chưa cài (hiện dùng `rap2hpoutre/fast-excel`)
- [ ] `spatie/laravel-activitylog` đã cài nhưng chưa tích hợp vào Admin views
- [ ] Export PDF hóa đơn chưa implement (dompdf đã cài)

---

## ⏳ PHASE 9 — Hoàn Thiện & Deploy
**Trạng thái: CHƯA LÀM**

### Còn thiếu
- [ ] `laravel/telescope` chưa cài
- [ ] `laravel/horizon` chưa cài
- [ ] `spatie/laravel-backup` chưa cài
- [ ] `QUEUE_CONNECTION=database` chưa cấu hình
- [ ] Email SMTP chưa cấu hình thực tế
- [ ] `OrderConfirmed` Notification chưa tạo
- [ ] `PaymentReceived` Notification chưa tạo
- [ ] `php artisan migrate:fresh --seed` chưa verify chạy thành công
- [ ] `php artisan optimize` chưa chạy


---

## 🗺️ Mapping File Cũ → File Mới (Trạng Thái Hiện Tại)

| File PHP thuần cũ | File Laravel mới | Trạng thái |
|-------------------|-----------------|-----------|
| `index.php` (router) | `routes/web.php` | ✅ Xong |
| `config.php` | `.env` + `config/database.php` | ✅ Xong |
| `Model/connect.php` | `config/database.php` + Eloquent | ✅ Xong |
| `Core/BaseController.php` | `app/Http/Controllers/Controller.php` | ✅ Xong |
| `Core/EmailService.php` | `app/Notifications/` | 🔄 Một phần |
| `Core/Validator.php` | `app/Http/Requests/` | 🔄 Một phần |
| `Core/Router.php` | `routes/web.php` | ✅ Xong |
| `Controller/LoginController.php` | `app/Http/Controllers/Auth/` (Breeze) | ✅ Xong |
| `Controller/CartController.php` | `app/Http/Controllers/Shop/CartController.php` | ✅ Xong |
| `Controller/OrderController.php` | `app/Http/Controllers/Shop/OrderController.php` | ✅ Xong |
| `Controller/PaymentController.php` | `app/Http/Controllers/Shop/PaymentController.php` | ✅ Xong |
| `Controller/WebhookController.php` | `app/Http/Controllers/WebhookController.php` | ✅ Xong |
| `Controller/ProfileController.php` | `app/Http/Controllers/Shop/ProfileController.php` | ✅ Xong |
| `Controller/SanPhamController.php` | `app/Http/Controllers/Shop/ProductController.php` | ✅ Xong |
| `api/chat.php` | `app/Http/Controllers/Api/ChatController.php` | 🔄 Một phần |
| `ajax_thongbao.php` | `app/Livewire/NotificationBell.php` | ⏳ Chưa làm |
| `Admin2/` (toàn bộ) | `app/Http/Controllers/Admin/` + Blade views | 🔄 Tạm thay (chưa Filament) |
| `Admin2/thongke.php` | `app/Http/Controllers/Admin/StatisticsController.php` | ✅ Xong |
| `Admin2/export_stats.php` | `StatisticsController@export` (fast-excel) | 🔄 Một phần |
| `View/header.php` | `resources/views/components/navbar.blade.php` | ✅ Xong |
| `View/footer.php` | `resources/views/components/footer.blade.php` | ✅ Xong |
| `View/home.php` | `resources/views/shop/home.blade.php` | ✅ Xong |
| `View/cart.php` | `resources/views/shop/cart/index.blade.php` | ✅ Xong |
| `View/payment.php` | `resources/views/shop/payment/index.blade.php` | ✅ Xong |
| `Model/mycfshop.sql` | `database/migrations/` + `database/seeders/` | ✅ Xong |

---

## � Nhật Ký Thay Đổi (Changelog)

### 01/06/2026 — Hoàn thành Phase 5, 6, 7

#### Phase 5 — Form Requests + NotificationBell Livewire
- **Tạo mới:** `app/Http/Requests/StoreOrderRequest.php` — validate đặt hàng
- **Tạo mới:** `app/Http/Requests/StoreReviewRequest.php` — validate đánh giá sản phẩm
- **Tạo mới:** `app/Http/Requests/AddToCartRequest.php` — validate thêm vào giỏ hàng
- **Cập nhật:** `OrderController::store()` — dùng `StoreOrderRequest` thay validate inline
- **Cập nhật:** `ReviewController::store()` — dùng `StoreReviewRequest` thay validate inline
- **Cập nhật:** `CartController::add()` — dùng `AddToCartRequest` thay validate inline
- **Tạo mới:** `app/Livewire/NotificationBell.php` — thay `ajax_thongbao.php` cũ
- **Tạo mới:** `resources/views/livewire/notification-bell.blade.php` — UI dropdown thông báo

#### Phase 6 — ProcessPaymentWebhook Job
- **Tạo mới:** `app/Jobs/ProcessPaymentWebhook.php` — xử lý webhook bất đồng bộ
  - `processCasso()` — tìm mã XD trong nội dung chuyển khoản
  - `processMoMo()` — verify resultCode=0
  - `processVNPay()` — verify ResponseCode=00
  - tries=3, timeout=30s, failed() log lỗi
- **Cập nhật:** `WebhookController.php` — verify signature → dispatch Job (thay xử lý trực tiếp)

#### Phase 7 — CaféAI Gemini + Livewire Widget
- **Fix:** `ChatController.php` — đổi `config('services.claude.api_key')` → `config('services.gemini.api_key')`, cập nhật API call sang Gemini format (`/v1beta/models/{model}:generateContent`)
- **Tạo mới:** `app/Livewire/CafeAIChatbox.php` — Livewire component toggle open/close
- **Tạo mới:** `resources/views/livewire/cafe-ai-chatbox.blade.php` — UI chatbox đầy đủ (messages, typing indicator, product cards, quick suggestions)
- **Cập nhật:** `layouts/shop.blade.php` — thêm `<livewire:cafe-a-i-chatbox />` trước `</body>`

---

### 21/05/2026 — Bugfix: Search logic + Auth UI fixes

**Vấn đề đã fix:**

#### 1. Tìm kiếm "trà" / "cà phê" trả về Bánh Tiramisu sai
- **Nguyên nhân:** Query search cũ dùng `orWhere('description', 'like', "%{$search}%")` — Bánh Tiramisu có description chứa "cà phê, rượu rum..." nên match nhầm
- **Fix:** Bỏ search theo `description`, chỉ search theo `name` sản phẩm và `name` danh mục
- **File thay đổi:** `app/Http/Controllers/Shop/ProductController.php`

#### 2. Chữ "Ghi nhớ đăng nhập", "Chưa có tài khoản", "Đã có tài khoản" màu trắng không đọc được
- **Nguyên nhân:** CSS `.text-muted` bị override thành `rgba(255,255,255,0.5)` từ profile section styles
- **Fix:** Đổi `class="text-muted"` thành `style="color:#555"` trực tiếp trong HTML; thêm CSS override trong `style_custom.css`
- **Files thay đổi:** `resources/views/auth/login.blade.php`, `resources/views/auth/register.blade.php`, `css/style_custom.css`

#### 3. Nút Facebook nhỏ hơn nút Google
- **Nguyên nhân:** `btn-block` + `btn-outline-primary` có padding/sizing khác nhau giữa 2 nút
- **Fix:** Bỏ `btn-block`, dùng `flex:1` + `min-height:46px` + `padding:10px 16px` đồng nhất cho cả 2 nút; Facebook dùng `border:2px solid #1877F2` thay vì `btn-outline-primary` để tránh Bootstrap override
- **Files thay đổi:** `resources/views/auth/login.blade.php`, `resources/views/auth/register.blade.php`

---

### 21/05/2026 — Bugfix UI: Pagination + Page Header + Category Section

**Vấn đề đã fix:**

#### 1. Pagination hiển thị text key thay vì nút bấm (`pagination.previous` / `pagination.next`)
- **Nguyên nhân:** Laravel 11 mặc định dùng Tailwind pagination view, nhưng project dùng Bootstrap CSS
- **Fix:** Thêm `Paginator::useBootstrap()` vào `AppServiceProvider::boot()`
- **File thay đổi:** `app/Providers/AppServiceProvider.php`

#### 2. Mũi tên Owl Carousel to xuất hiện trên trang sản phẩm
- **Nguyên nhân:** Tất cả trang con (sản phẩm, giỏ hàng, đặt hàng...) dùng `<section class="home-slider owl-carousel">` làm page header tĩnh → Owl Carousel JS khởi tạo và render nav arrows `<` `>` to
- **Fix:** Thay toàn bộ bằng `<section class="hero-page-header">` (div tĩnh, không có JS carousel)
- **Files thay đổi (16 file):**
  - `resources/views/shop/products/index.blade.php` ✅
  - `resources/views/shop/products/show.blade.php` ✅ (đã có sẵn)
  - `resources/views/shop/cart/index.blade.php` ✅ (đã có sẵn)
  - `resources/views/shop/orders/checkout.blade.php` ✅ (đã có sẵn)
  - `resources/views/shop/orders/history.blade.php` ✅ (đã có sẵn)
  - `resources/views/shop/orders/show.blade.php` ✅ (đã có sẵn)
  - `resources/views/shop/payment/index.blade.php` ✅ (đã có sẵn)
  - `resources/views/shop/payment/success.blade.php` ✅ (đã có sẵn)
  - `resources/views/shop/payment/vietqr.blade.php` ✅ **fixed**
  - `resources/views/shop/profile/edit.blade.php` ✅ (đã có sẵn)
  - `resources/views/shop/profile/show.blade.php` ✅ **fixed**
  - `resources/views/auth/login.blade.php` ✅ (đã có sẵn)
  - `resources/views/auth/register.blade.php` ✅ (đã có sẵn)
  - `resources/views/auth/forgot-password.blade.php` ✅ (đã có sẵn)
  - `resources/views/auth/reset-password.blade.php` ✅ (đã có sẵn)
  - `resources/views/auth/verify-email.blade.php` ✅ (đã có sẵn)
  - `resources/views/auth/confirm-password.blade.php` ✅ (đã có sẵn)
- **CSS mới thêm:** `.hero-page-header` styles vào `css/style_custom.css`

#### 3. Mục "DANH MỤC SẢN PHẨM" trang chủ có nền trắng xấu
- **Nguyên nhân:** Section dùng class `bg-light` (Bootstrap nền trắng xám) không phù hợp với dark theme
- **Fix:** Đổi sang `ftco-bg-dark img` với background ảnh `bg_4.jpg` + overlay tối, card danh mục dùng glassmorphism dark
- **File thay đổi:** `resources/views/shop/home.blade.php`
- **CSS mới thêm:** `.category-card-dark` styles vào `css/style_custom.css`

---

## �📝 Hướng Dẫn Cập Nhật File Này

Mỗi khi hoàn thành một tác vụ, cập nhật file này theo quy tắc:

### Khi tạo file/folder mới
Thêm vào mục tương ứng của Phase, đổi trạng thái `⏳ Chưa làm` → `🔄 Một phần` → `✅ Xong`

### Khi hoàn thành một Phase
1. Đổi trạng thái trong bảng "Tổng Quan Các Phase" thành `✅ **Hoàn thành**`
2. Cập nhật mục Phase tương ứng, tick hết các checkbox `[ ]` → `[x]`
3. Cập nhật ngày "Cập nhật lần cuối" ở đầu file

### Ký hiệu trạng thái
| Ký hiệu | Nghĩa |
|---------|-------|
| ✅ | Hoàn thành |
| 🔄 | Đang làm / Một phần |
| ⏳ | Chưa bắt đầu |
| ❌ | Bỏ qua / Không làm |

---

*Checkpoint được tạo tự động dựa trên phân tích codebase thực tế*  
*Ngày tạo: 21/05/2026*
