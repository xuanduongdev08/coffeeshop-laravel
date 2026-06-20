# 🗑️ Danh Sách File & Folder PHP Thuần Cần Xóa

> **Dự án:** XDTHECOFFEEHOUSE — Sau khi hoàn thành chuyển đổi sang Laravel  
> **Kiểm tra lần cuối:** 01/06/2026 — Tất cả chức năng đã có tương đương trong Laravel ✅  
> **⚠️ Lưu ý:** Commit Git trước khi xóa để có thể rollback nếu cần.

---

## ✅ Trạng Thái Chuyển Đổi Tổng Quan

| Module | PHP thuần cũ | Laravel mới | Trạng thái |
|--------|-------------|-------------|-----------|
| Frontend Shop | `Controller/`, `View/` | `app/Http/Controllers/Shop/`, `resources/views/shop/` | ✅ Hoàn thành |
| Auth | `Controller/LoginController.php`, v.v. | `app/Http/Controllers/Auth/` (Breeze) | ✅ Hoàn thành |
| Admin Panel | `Admin2/` | `app/Http/Controllers/Admin/` + Filament `/panel` | ✅ Hoàn thành |
| API / AI Chat | `api/chat.php` | `app/Http/Controllers/Api/ChatController.php` | ✅ Hoàn thành |
| Models / DB | `Model/` | `app/Models/` + `database/migrations/` | ✅ Hoàn thành |
| Core Framework | `Core/` | Laravel built-in | ✅ Hoàn thành |
| Assets | `css/`, `js/`, `fonts/`, `images/` (root) | `public/css/`, `public/js/`, `public/fonts/`, `public/images/` | ✅ Đã copy vào public/ |
| Webhook | `check_payment_status.php` | `app/Http/Controllers/WebhookController.php` | ✅ Hoàn thành |
| Thông báo AJAX | `ajax_thongbao.php` | `app/Http/Controllers/Api/NotificationController.php` | ✅ Hoàn thành |

---

## 📁 FOLDERS CẦN XÓA

### 1. `Admin2/` — Toàn bộ admin panel PHP thuần
> Thay bằng: Filament Panel tại `/panel` + `app/Http/Controllers/Admin/`

| File | Đã thay bằng |
|------|-------------|
| `Admin2/index.php` | Filament Dashboard |
| `Admin2/dashboard.php` | `Admin/DashboardController` + Filament `StatsOverview` widget |
| `Admin2/dangnhap.php` | `Auth/AuthenticatedSessionController` + Filament Login `/panel/login` |
| `Admin2/logout.php` | `Auth/AuthenticatedSessionController` + Filament Logout |
| `Admin2/hanghoa.php` | `Admin/ProductController` + Filament `ProductResource` |
| `Admin2/loaisanpham.php` | `Admin/CategoryController` + Filament `CategoryResource` |
| `Admin2/donhang.php` | `Admin/OrderController` + Filament `OrderResource` |
| `Admin2/khachhang.php` | `Admin/UserController` + Filament `UserResource` |
| `Admin2/nhanvien.php` | `Admin/UserController` (filter role staff) + Filament `UserResource` |
| `Admin2/thongke.php` | `Admin/StatisticsController` + Filament `Statistics` page + `RevenueChart` widget |
| `Admin2/export_stats.php` | `Admin/StatisticsController@export` + `app/Exports/OrdersExport.php` |
| `Admin2/cafeai_dashboard.php` | Filament `Statistics` page + `StatsOverview` widget |
| `Admin2/profile.php` | `Shop/ProfileController` + Filament `AccountWidget` |
| `Admin2/settings.php` | *(Chưa có tương đương — xem ghi chú bên dưới)* |
| `Admin2/view_website.php` | Không cần (link đơn giản) |
| `Admin2/style.css` | Filament built-in CSS + Tailwind |
| `Admin2/Model/admin.php` | `app/Models/User.php` (role admin) |
| `Admin2/Model/content.php` | `app/Models/Product.php` + `app/Models/Category.php` |
| `Admin2/Model/nhanvien_model.php` | `app/Models/User.php` (role staff) |
| `Admin2/View/header.php` | `resources/views/layouts/admin.blade.php` |
| `Admin2/View/footer.php` | `resources/views/layouts/admin.blade.php` |

> ⚠️ **`Admin2/settings.php`:** Chưa có trang Settings trong Laravel. Nếu cần quản lý cấu hình hệ thống (API keys, thông tin shop), bổ sung Filament Settings page ở Phase 9 trước khi xóa.

---

### 2. `Controller/` — 23 controllers PHP thuần
> Thay bằng: `app/Http/Controllers/Shop/`, `app/Http/Controllers/Auth/`, `app/Http/Controllers/Api/`

| File | Đã thay bằng |
|------|-------------|
| `Controller/HomeController.php` | `Shop/HomeController.php` |
| `Controller/SanPhamController.php` | `Shop/ProductController@index` |
| `Controller/SanPhamChiTietController.php` | `Shop/ProductController@show` |
| `Controller/CartController.php` | `Shop/CartController.php` |
| `Controller/cart.php` | `Shop/CartController.php` |
| `Controller/OrderController.php` | `Shop/OrderController.php` |
| `Controller/OrderDetailController.php` | `Shop/OrderController@show` |
| `Controller/OrderHistoryController.php` | `Shop/OrderController@history` |
| `Controller/PaymentController.php` | `Shop/PaymentController.php` |
| `Controller/PaymentQRController.php` | `Shop/PaymentController@showVietQR` |
| `Controller/PaymentSuccessController.php` | `Shop/PaymentController@success` |
| `Controller/ProfileController.php` | `Shop/ProfileController.php` |
| `Controller/LoginController.php` | `Auth/AuthenticatedSessionController.php` |
| `Controller/LogoutController.php` | `Auth/AuthenticatedSessionController.php` |
| `Controller/RegistrationController.php` | `Auth/RegisteredUserController.php` |
| `Controller/ForgetController.php` | `Auth/PasswordResetLinkController.php` |
| `Controller/WebhookController.php` | `app/Http/Controllers/WebhookController.php` |
| `Controller/AjaxPaymentStatusController.php` | `Api/PaymentStatusController.php` |
| `Controller/AjaxUpdateProfileController.php` | `Shop/ProfileController@update` |
| `Controller/AjaxUploadAvatarController.php` | `Shop/ProfileController@uploadAvatar` |
| `Controller/ajax_update_profile.php` | `Shop/ProfileController@update` |
| `Controller/ajax_upload_avatar.php` | `Shop/ProfileController@uploadAvatar` |
| `Controller/review.php` | `Shop/ReviewController.php` |

---

### 3. `Core/` — Framework tự viết
> Thay bằng: Laravel built-in

| File | Đã thay bằng |
|------|-------------|
| `Core/Autoloader.php` | Composer autoload (`vendor/autoload.php`) |
| `Core/BaseController.php` | `app/Http/Controllers/Controller.php` |
| `Core/Router.php` | `routes/web.php` + Laravel Router |
| `Core/Validator.php` | Laravel Validation + `app/Http/Requests/` |
| `Core/EmailService.php` | Laravel Mail + `app/Notifications/` |

---

### 4. `Model/` — Models PHP thuần + SQL dumps
> Thay bằng: `app/Models/` (Eloquent) + `database/migrations/` + `database/seeders/`

| File | Đã thay bằng |
|------|-------------|
| `Model/connect.php` | `config/database.php` + `.env` |
| `Model/khachhang.php` | `app/Models/User.php` |
| `Model/hanghoa.php` | `app/Models/Product.php` |
| `Model/hoadon.php` | `app/Models/Order.php` + `app/Models/OrderItem.php` |
| `Model/giohang.php` | `app/Services/CartService.php` + darryldecode/cart |
| `Model/giamgia.php` | `app/Models/Discount.php` |
| `Model/reviews.php` | `app/Models/Review.php` |
| `Model/thongbao.php` | `app/Notifications/DrinkStatusUpdated.php` + `app/Models/ChatLog.php` |
| `Model/CafeAI.php` | `app/Http/Controllers/Api/ChatController.php` + `app/Livewire/CafeAIChatbox.php` |
| `Model/phantrang.php` | Laravel Pagination built-in |
| `Model/mycfshop.sql` | `database/migrations/` + `database/seeders/` |
| `Model/cafe_ai_migration.sql` | `database/migrations/` |

---

### 5. `View/` — Views PHP thuần
> Thay bằng: `resources/views/` (Blade templates)

| File | Đã thay bằng |
|------|-------------|
| `View/home.php` | `resources/views/shop/home.blade.php` |
| `View/sanpham.php` | `resources/views/shop/products/index.blade.php` |
| `View/sanphamchitiet.php` | `resources/views/shop/products/show.blade.php` |
| `View/cart.php` | `resources/views/shop/cart/index.blade.php` |
| `View/order.php` | `resources/views/shop/orders/checkout.blade.php` |
| `View/order_detail.php` | `resources/views/shop/orders/show.blade.php` |
| `View/order_history.php` | `resources/views/shop/orders/history.blade.php` |
| `View/payment.php` | `resources/views/shop/payment/index.blade.php` |
| `View/payment_qr.php` | `resources/views/shop/payment/vietqr.blade.php` |
| `View/payment_success.php` | `resources/views/shop/payment/success.blade.php` |
| `View/login.php` | `resources/views/auth/login.blade.php` |
| `View/registration.php` | `resources/views/auth/register.blade.php` |
| `View/forgetpassword.php` | `resources/views/auth/forgot-password.blade.php` |
| `View/profile.php` | `resources/views/shop/profile/edit.blade.php` |
| `View/headder.php` | `resources/views/components/navbar.blade.php` |
| `View/footer.php` | `resources/views/components/footer.blade.php` |

---

### 6. `api/` — API PHP thuần
> Thay bằng: `app/Http/Controllers/Api/ChatController.php` + `routes/api.php`

| File | Đã thay bằng |
|------|-------------|
| `api/chat.php` | `app/Http/Controllers/Api/ChatController.php` |

---

### 7. Assets trùng lặp ở root
> **Đã được copy y hệt vào `public/`** — thư mục root chỉ là bản gốc PHP thuần, không còn dùng nữa.

| Thư mục root | Đã có trong | Ghi chú |
|---|---|---|
| `css/` | `public/css/` ✅ | Trùng hoàn toàn — an toàn xóa |
| `js/` | `public/js/` ✅ | Trùng hoàn toàn — an toàn xóa |
| `fonts/` | `public/fonts/` ✅ | Trùng hoàn toàn — an toàn xóa |
| `scss/` | Không dùng (dùng Tailwind) ✅ | An toàn xóa |
| `images/` | `public/images/` ⚠️ | **Kiểm tra kỹ** — `public/images/` chứa ảnh upload thực tế (product_, avatar_, review_). Chỉ xóa `images/` ở root nếu đã xác nhận `public/images/` có đầy đủ. |

---

## 📄 FILES LẺ CẦN XÓA (ở root)

| File | Mô tả | Đã thay bằng |
|------|-------|-------------|
| `index.php` | Router PHP thuần (entry point cũ) | `public/index.php` (Laravel entry point) |
| `config.php` | Cấu hình DB + API keys PHP thuần | `.env` + `config/database.php` + `config/services.php` |
| `ajax_thongbao.php` | AJAX polling thông báo PHP thuần | `app/Http/Controllers/Api/NotificationController.php` |
| `check_payment_status.php` | Kiểm tra trạng thái thanh toán PHP thuần | `app/Http/Controllers/Api/PaymentStatusController.php` |
| `cafe_ai_chatbox.html` | HTML demo chatbox standalone | `app/Livewire/CafeAIChatbox.php` + Blade view |
| `webhook_log.txt` | Log webhook ghi file thủ công | `storage/logs/laravel.log` + Laravel Log |
| `mycfshop_laravel.sql` | SQL dump database (đã có migrations) | `database/migrations/` + `database/seeders/` |

---

## 📄 FILES DIAGRAM (giữ lại để tham khảo)

> Các file XML/diagram dưới đây **không phải PHP thuần** — là tài liệu thiết kế, nên giữ lại trong `docs/` hoặc xóa tùy ý.

| File | Ghi chú |
|------|---------|
| `activity_diagrams.xml` | Sơ đồ hoạt động — tài liệu |
| `erd_diagram.xml` | ERD database — tài liệu |
| `usecase_diagram.xml` | Use case diagram — tài liệu |

---

## ✅ LỆNH XÓA (PowerShell — chạy sau khi đã commit Git)

```powershell
# ============================================================
# BƯỚC 1: Xóa toàn bộ folders PHP thuần
# ============================================================
Remove-Item -Recurse -Force `
    Admin2, `
    Controller, `
    Core, `
    Model, `
    View, `
    api

# ============================================================
# BƯỚC 2: Xóa assets trùng lặp ở root
# (chỉ chạy sau khi đã xác nhận public/ có đầy đủ)
# ============================================================
Remove-Item -Recurse -Force css, js, fonts, scss
# images/ — kiểm tra kỹ trước:
# Compare-Object (Get-ChildItem images/ -Recurse) (Get-ChildItem public/images/ -Recurse)
# Remove-Item -Recurse -Force images   # chỉ xóa khi đã xác nhận

# ============================================================
# BƯỚC 3: Xóa files PHP thuần ở root
# ============================================================
Remove-Item -Force `
    index.php, `
    config.php, `
    ajax_thongbao.php, `
    check_payment_status.php, `
    cafe_ai_chatbox.html, `
    webhook_log.txt, `
    mycfshop_laravel.sql

# ============================================================
# BƯỚC 4 (tùy chọn): Xóa files diagram nếu không cần
# ============================================================
# Remove-Item -Force activity_diagrams.xml, erd_diagram.xml, usecase_diagram.xml
```

---

## 🔍 CHECKLIST TRƯỚC KHI XÓA

- [ ] Đã chạy `git add . && git commit -m "chore: backup before removing legacy PHP files"`
- [ ] Đã test website Laravel chạy bình thường tại `http://127.0.0.1:8000`
- [ ] Đã test Filament admin panel tại `http://127.0.0.1:8000/panel`
- [ ] Đã xác nhận `public/images/` có đầy đủ ảnh sản phẩm, avatar, banner
- [ ] Đã xác nhận `public/css/`, `public/js/`, `public/fonts/` có đầy đủ assets
- [ ] Đã chạy `php artisan route:list` không có lỗi
- [ ] Đã chạy `php artisan config:clear && php artisan optimize:clear` thành công

---

*Cập nhật: 01/06/2026 — Phase 8 hoàn thành | Kiểm tra bởi context-gatherer*
