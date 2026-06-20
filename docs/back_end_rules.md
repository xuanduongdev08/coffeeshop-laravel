# ⚙️ Nguyên Tắc Backend — XDTHECOFFEEHOUSE

> **Mục đích:** Tài liệu tổng hợp toàn bộ kiến trúc backend, database schema, business logic, modules, và quy tắc phát triển của hệ thống XDTHECOFFEEHOUSE.  
> Framework: **Laravel 11** | Database: **MySQL** (Laragon) | Auth: **Laravel Breeze + Spatie Permission**

---

## 📋 Mục Lục

1. [Kiến Trúc Hệ Thống](#-kiến-trúc-hệ-thống)
2. [Database Schema](#-database-schema)
3. [Models & Relationships](#-models--relationships)
4. [Routes](#-routes)
5. [Controllers](#-controllers)
6. [Services](#-services)
7. [Middleware & Authorization](#-middleware--authorization)
8. [Authentication](#-authentication)
9. [Payment Integration](#-payment-integration)
10. [CaféAI Chatbot](#-caféai-chatbot)
11. [Notifications & Real-time](#-notifications--real-time)
12. [Observers & Events](#-observers--events)
13. [Thư Viện Bên Ngoài](#-thư-viện-bên-ngoài)
14. [Cấu Hình Môi Trường](#-cấu-hình-môi-trường)
15. [Quy Tắc Phát Triển](#-quy-tắc-phát-triển)

---

## 🏗️ Kiến Trúc Hệ Thống

### Stack Công Nghệ

| Layer | Công nghệ |
|-------|-----------|
| Framework | Laravel 11 |
| PHP | 8.2+ |
| Database | MySQL (via Laragon) |
| Session | Database driver |
| Cache | Database driver |
| Queue | Database driver |
| Auth | Laravel Breeze + Spatie Permission |
| Social Auth | Laravel Socialite (Google, Facebook) |
| Real-time | Livewire 3 (polling) |
| Media | Spatie MediaLibrary |
| Cart | darryldecode/cart (session-based) |
| Slug | cviebrock/eloquent-sluggable |
| Excel Export | rap2hpoutre/fast-excel |

### Cấu Trúc Thư Mục

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          ← 7 controllers quản trị
│   │   ├── Api/            ← ChatController (CaféAI)
│   │   ├── Auth/           ← Breeze auth controllers
│   │   ├── Shop/           ← 7 controllers shop
│   │   ├── Controller.php  ← Base controller
│   │   ├── ProfileController.php
│   │   └── WebhookController.php
│   ├── Middleware/
│   │   ├── AdminMiddleware.php
│   │   └── CheckOrderOwnership.php
│   └── Requests/           ← Form Request validation
├── Livewire/
│   └── OrderStatusBell.php ← Notification bell (polling 5s)
├── Models/                 ← 13 Eloquent models
├── Notifications/
│   └── DrinkStatusUpdated.php
├── Observers/
│   └── OrderObserver.php
├── Providers/
│   └── AppServiceProvider.php
└── Services/
    ├── CartService.php
    ├── MoMoService.php
    └── VNPayService.php
```

---

## 🗄️ Database Schema

### Danh Sách Bảng

| Bảng | Mô tả |
|------|-------|
| `users` | Tài khoản người dùng (khách hàng + admin) |
| `sessions` | Session database driver |
| `password_reset_tokens` | Token đặt lại mật khẩu |
| `categories` | Danh mục sản phẩm |
| `products` | Sản phẩm (soft delete) |
| `product_sizes` | Giá theo size M/L/XL |
| `modifiers` | Tùy chọn thêm (đường, đá, sữa, topping) |
| `orders` | Đơn hàng |
| `order_items` | Chi tiết sản phẩm trong đơn |
| `order_item_modifiers` | Modifier đã chọn cho từng order item |
| `reviews` | Đánh giá sản phẩm |
| `notifications` | Thông báo database (Laravel Notifications) |
| `discounts` | Mã giảm giá (theo danh mục) |
| `banners` | Banner trang chủ |
| `chat_logs` | Lịch sử chat CaféAI |
| `product_requests` | Sản phẩm khách hàng yêu cầu (market gap) |
| `media` | Spatie MediaLibrary |
| `activity_log` | Spatie ActivityLog |
| `jobs` / `failed_jobs` | Queue jobs |
| `cache` | Cache database driver |
| `permissions` / `roles` / `model_has_roles` | Spatie Permission |

### Chi Tiết Bảng Quan Trọng

#### `users`
```
id, name, email (unique), email_verified_at, password (nullable),
phone, address, avatar, provider (google/facebook), provider_id,
provider_token, remember_token, created_at, updated_at
```

#### `categories`
```
id, name, slug (unique), description, image,
is_active (bool, default true), sort_order (int, default 0),
created_at, updated_at
```

#### `products`
```
id, category_id (FK → categories, nullOnDelete),
name, slug (unique), description, price (decimal 10,2),
discount_price (decimal 10,2, nullable), image, stock (int, default 0),
is_active (bool), is_featured (bool),
has_size (bool), has_topping (bool), allow_sugar (bool),
allow_ice (bool), allow_milk (bool),
created_at, updated_at, deleted_at (soft delete)
```

#### `product_sizes`
```
id, product_id (FK → products, cascadeOnDelete),
size (enum: M/L/XL), price (decimal 10,2),
is_active (bool), created_at, updated_at
UNIQUE: (product_id, size)
```

#### `modifiers`
```
id, name, type (enum: sugar/ice/milk/topping),
extra_price (decimal 8,2, default 0),
applies_to_drink (bool, default true),
applies_to_tea_juice (bool, default false),
is_active (bool), sort_order (int),
created_at, updated_at
```

#### `orders`
```
id, user_id (FK → users, nullOnDelete),
recipient_name, shipping_address, phone,
subtotal (decimal 10,2), shipping_fee (decimal 10,2), total (decimal 10,2),
payment_method (COD/VietQR/MoMo/VNPay),
payment_status (pending/paid/failed),
status (Chờ xử lý/Đang giao/Hoàn thành/Đã hủy),
tracking_code (VD: XD00001), notes,
drink_status (enum: pending/brewing/completed, nullable),
brewing_at (timestamp, nullable), completed_at (timestamp, nullable),
created_at, updated_at
```

#### `order_items`
```
id, order_id (FK → orders, cascadeOnDelete),
product_id (FK → products, nullOnDelete),
product_name (snapshot), product_image (snapshot),
size (nullable), base_price (decimal), modifier_extra (decimal),
unit_price (decimal), price (decimal), quantity (int),
subtotal (decimal), created_at, updated_at
```

#### `order_item_modifiers`
```
id, order_item_id (FK → order_items, cascadeOnDelete),
modifier_id (FK → modifiers, cascadeOnDelete),
extra_price_snapshot (decimal), created_at, updated_at
```

#### `reviews`
```
id, product_id (FK), user_id (FK),
reviewer_name, rating (1–5), comment, image,
is_approved (bool, default false), created_at, updated_at
```

#### `chat_logs`
```
id, user_id (FK, nullable), session_id,
role (user/assistant), message, intent, language (vi/en),
metadata (JSON), created_at, updated_at
```

---

## 🧩 Models & Relationships

### Sơ Đồ Quan Hệ

```
User ──────────────── hasMany ──→ Order
User ──────────────── hasMany ──→ Review
User ──────────────── hasMany ──→ ChatLog

Category ──────────── hasMany ──→ Product
Category ──────────── hasMany ──→ Discount

Product ───────────── belongsTo → Category
Product ───────────── hasMany ──→ OrderItem
Product ───────────── hasMany ──→ ProductSize (orderBy M→L→XL)
Product ───────────── hasMany ──→ Review (where is_approved=true)

Order ──────────────── belongsTo → User
Order ──────────────── hasMany ──→ OrderItem

OrderItem ──────────── belongsTo → Order
OrderItem ──────────── belongsTo → Product
OrderItem ──────────── belongsToMany → Modifier (via order_item_modifiers)
OrderItem ──────────── hasMany ──→ OrderItemModifier

Modifier ───────────── belongsToMany → OrderItem (via order_item_modifiers)
```

### Model: `Product`

**Traits:** `HasFactory`, `Sluggable`, `SoftDeletes`, `InteractsWithMedia`

**Scopes:**
- `scopeActive()` — `is_active = true`
- `scopeFeatured()` — `is_featured = true`
- `scopeInStock()` — `stock > 0`
- `scopeHasSize()` — `has_size = true`
- `scopeHasTopping()` — `has_topping = true`

**Accessors:**
- `getEffectivePriceAttribute()` — trả về `discount_price ?? price`
- `getAverageRatingAttribute()` — avg rating từ approved reviews
- `getFormattedPriceAttribute()` — format VND
- `getSizePricesAttribute()` — mảng `[size => price]` cho dropdown

**Methods:**
- `priceBySize(string $size): float` — giá theo size đã chọn

### Model: `Order`

**Auto-generate tracking_code** trong `booted()`:
```php
static::created(fn($order) => $order->update([
    'tracking_code' => 'XD' . str_pad($order->id, 5, '0', STR_PAD_LEFT)
]));
```

**Scopes:** `scopePending()`, `scopePaid()`, `scopeDrinkInProgress()`, `scopeByDrinkStatus()`

**Accessors:**
- `getFormattedTotalAttribute()` — format VND
- `getStatusBadgeColorAttribute()` — Bootstrap color class
- `getDrinkStatusLabelAttribute()` — emoji + label
- `getHasDrinkAttribute()` — bool
- `getNextDrinkStatusAttribute()` — pending→brewing→completed

### Model: `Modifier`

**Scopes:**
- `scopeActive()` — `is_active = true`, order by `sort_order`
- `scopeForDrink()` — `applies_to_drink = true`
- `scopeForTeaJuice()` — `applies_to_tea_juice = true`
- `scopeOfType(string $type)` — filter by type

**Types:** `sugar` | `ice` | `milk` | `topping`

### Model: `ProductRequest`

**Static method:** `logRequest(string $productName, string $query)` — tự động increment nếu đã tồn tại, tạo mới nếu chưa có. Dùng để track market gap khi CaféAI không tìm thấy sản phẩm.

### Model: `User`

**Traits:** `HasFactory`, `Notifiable`, `HasRoles` (Spatie)

**Roles hệ thống:** `admin` | `staff` | `cashier` | `warehouse` | `customer`

---

## 🛣️ Routes

### Frontend Routes (`routes/web.php`)

| Method | URL | Controller | Middleware | Name |
|--------|-----|-----------|-----------|------|
| GET | `/` | `HomeController@index` | — | `home` |
| GET | `/san-pham` | `ProductController@index` | — | `products.index` |
| GET | `/san-pham/{product:slug}` | `ProductController@show` | — | `products.show` |
| GET | `/danh-muc/{category:slug}` | `ProductController@byCategory` | — | `categories.show` |
| GET | `/gio-hang` | `CartController@index` | — | `cart.index` |
| POST | `/gio-hang/them` | `CartController@add` | — | `cart.add` |
| PATCH | `/gio-hang/cap-nhat/{rowId}` | `CartController@update` | — | `cart.update` |
| DELETE | `/gio-hang/xoa/{rowId}` | `CartController@remove` | — | `cart.remove` |
| GET/DELETE | `/gio-hang/xoa-tat-ca` | `CartController@clear` | — | `cart.clear` |
| GET | `/dat-hang/xac-nhan` | `OrderController@checkout` | `auth` | `orders.checkout` |
| POST | `/dat-hang/tao` | `OrderController@store` | `auth` | `orders.store` |
| GET | `/dat-hang/lich-su` | `OrderController@history` | `auth` | `orders.history` |
| GET | `/dat-hang/{order}` | `OrderController@show` | `auth`, `order.owner` | `orders.show` |
| PATCH | `/dat-hang/{order}/huy` | `OrderController@cancel` | `auth`, `order.owner` | `orders.cancel` |
| GET | `/thanh-toan/{order}` | `PaymentController@index` | `auth` | `payment.index` |
| POST | `/thanh-toan/cod/{order}` | `PaymentController@processCOD` | `auth` | `payment.cod` |
| POST | `/thanh-toan/vnpay/{order}` | `PaymentController@redirectVNPay` | `auth` | `payment.vnpay` |
| POST | `/thanh-toan/momo/{order}` | `PaymentController@redirectMoMo` | `auth` | `payment.momo` |
| GET | `/thanh-toan/vietqr/{order}` | `PaymentController@showVietQR` | `auth` | `payment.vietqr` |
| GET | `/thanh-toan/vnpay/ket-qua` | `PaymentController@vnpayReturn` | `auth` | `payment.vnpay.return` |
| GET | `/thanh-toan/momo/ket-qua` | `PaymentController@momoReturn` | `auth` | `payment.momo.return` |
| GET | `/thanh-toan/thanh-cong/{order}` | `PaymentController@success` | `auth` | `payment.success` |
| POST | `/san-pham/{product}/danh-gia` | `ReviewController@store` | `auth` | `reviews.store` |
| GET | `/ho-so` | `ProfileController@show` | `auth` | `profile.show` |
| GET | `/ho-so/chinh-sua` | `ProfileController@edit` | `auth` | `profile.edit` |
| PATCH | `/ho-so/cap-nhat` | `ProfileController@update` | `auth` | `profile.update` |
| POST | `/ho-so/doi-mat-khau` | `ProfileController@changePassword` | `auth` | `profile.password` |
| POST | `/ho-so/upload-avatar` | `ProfileController@uploadAvatar` | `auth` | `profile.avatar` |
| DELETE | `/ho-so/xoa-tai-khoan` | `ProfileController@destroy` | `auth` | `profile.destroy` |

### Admin Routes (`/admin/*`)

Middleware: `auth` + `AdminMiddleware` (roles: admin/staff/cashier/warehouse)

| Method | URL | Controller | Name |
|--------|-----|-----------|------|
| GET | `/admin` | `DashboardController@index` | `admin.dashboard` |
| RESOURCE | `/admin/products` | `Admin\ProductController` | `admin.products.*` |
| POST | `/admin/products/{id}/restore` | `Admin\ProductController@restore` | `admin.products.restore` |
| RESOURCE | `/admin/categories` | `Admin\CategoryController` | `admin.categories.*` |
| GET | `/admin/orders` | `Admin\OrderController@index` | `admin.orders.index` |
| GET | `/admin/orders/{order}` | `Admin\OrderController@show` | `admin.orders.show` |
| PATCH | `/admin/orders/{order}/status` | `Admin\OrderController@updateStatus` | `admin.orders.status` |
| PATCH | `/admin/orders/{order}/payment-status` | `Admin\OrderController@updatePaymentStatus` | `admin.orders.payment-status` |
| PATCH | `/admin/orders/{order}/drink-status` | `Admin\DrinkStatusController@update` | `admin.orders.drink-status.update` |
| GET | `/admin/users` | `Admin\UserController@index` | `admin.users.index` |
| GET | `/admin/users/{user}` | `Admin\UserController@show` | `admin.users.show` |
| PATCH | `/admin/users/{user}/role` | `Admin\UserController@updateRole` | `admin.users.role` |
| DELETE | `/admin/users/{user}` | `Admin\UserController@destroy` | `admin.users.destroy` |
| GET | `/admin/statistics` | `Admin\StatisticsController@index` | `admin.statistics.index` |
| GET | `/admin/statistics/export` | `Admin\StatisticsController@export` | `admin.statistics.export` |

### Webhook Routes (`/webhook/*`)

Không cần auth, không cần CSRF (exempt trong `bootstrap/app.php`)

| Method | URL | Controller | Name |
|--------|-----|-----------|------|
| POST | `/webhook/casso` | `WebhookController@handleCasso` | `webhook.casso` |
| POST | `/webhook/momo` | `WebhookController@handleMoMo` | `webhook.momo` |
| POST | `/webhook/vnpay` | `WebhookController@handleVNPay` | `webhook.vnpay` |

### API Routes (`routes/api.php`)

| Method | URL | Controller | Name |
|--------|-----|-----------|------|
| POST | `/api/chat` | `Api\ChatController@handle` | — |

---

## 🎮 Controllers

### Shop Controllers

#### `HomeController`
- `index()` — Trang chủ: load featured products, categories, banners, sliders

#### `ProductController`
- `index()` — Danh sách sản phẩm, filter theo category, search, sort, paginate
- `show(Product $product)` — Chi tiết sản phẩm: load sizes, modifiers, reviews
- `byCategory(Category $category)` — Lọc sản phẩm theo danh mục

#### `CartController`
- `index()` — Hiển thị giỏ hàng từ session
- `add(Request $request)` — Thêm sản phẩm (dùng `CartService::addToCart()`)
- `update(Request $request, $rowId)` — Cập nhật số lượng
- `remove($rowId)` — Xóa 1 sản phẩm
- `clear()` — Xóa toàn bộ giỏ hàng

#### `OrderController`
- `checkout()` — Trang xác nhận đặt hàng (kiểm tra giỏ hàng không rỗng)
- `store(Request $request)` — Tạo đơn hàng (DB transaction, tính phí ship, lưu modifiers)
- `history()` — Lịch sử đơn hàng (paginate 10)
- `show(Order $order)` — Chi tiết đơn hàng (load items.modifiers)
- `cancel(Order $order)` — Hủy đơn (chỉ khi status = "Chờ xử lý")

#### `PaymentController`
- `index(Order $order)` — Trang chọn phương thức thanh toán
- `processCOD(Order $order)` — Xử lý COD
- `showVietQR(Order $order)` — Tạo QR động qua vietqr.io API
- `redirectVNPay(Order $order, VNPayService $vnpay)` — Redirect sang VNPay
- `vnpayReturn(Request $request, VNPayService $vnpay)` — Xử lý callback VNPay
- `redirectMoMo(Order $order, MoMoService $momo)` — Redirect sang MoMo
- `momoReturn(Request $request, MoMoService $momo)` — Xử lý callback MoMo
- `success(Order $order)` — Trang thanh toán thành công

#### `ReviewController`
- `store(Request $request, Product $product)` — Lưu đánh giá (cần đã mua sản phẩm)

#### `ProfileController`
- `show()` — Xem hồ sơ
- `edit()` — Form chỉnh sửa
- `update(Request $request)` — Cập nhật thông tin
- `changePassword(Request $request)` — Đổi mật khẩu
- `uploadAvatar(Request $request)` — Upload avatar (Cropper.js)
- `destroy(Request $request)` — Xóa tài khoản

### Admin Controllers

#### `DashboardController`
- `index()` — Thống kê tổng quan: total orders, pending, revenue, customers, products, low stock
- Chart doanh thu 7 ngày gần nhất (Chart.js)
- Danh sách 10 đơn hàng mới nhất
- Đơn hàng đang pha chế (drink_status: pending/brewing)

#### `Admin\ProductController`
- CRUD đầy đủ + soft delete + restore
- Upload ảnh sản phẩm
- Quản lý sizes (M/L/XL) và modifier flags

#### `Admin\CategoryController`
- CRUD danh mục + upload ảnh

#### `Admin\OrderController`
- `index()` — Danh sách đơn hàng, filter theo status/payment
- `show(Order $order)` — Chi tiết đơn hàng
- `updateStatus(Order $order)` — Cập nhật trạng thái đơn
- `updatePaymentStatus(Order $order)` — Cập nhật trạng thái thanh toán

#### `Admin\DrinkStatusController`
- `update(Order $order)` — Cập nhật drink_status (pending→brewing→completed)
- Tự động set `brewing_at` / `completed_at` timestamp
- Trigger `OrderObserver` → gửi `DrinkStatusUpdated` notification

#### `Admin\UserController`
- `index()` — Danh sách người dùng
- `show(User $user)` — Chi tiết user + lịch sử đơn hàng
- `updateRole(User $user)` — Thay đổi role (Spatie)
- `destroy(User $user)` — Xóa tài khoản

#### `Admin\StatisticsController`
- `index(Request $request)` — Thống kê theo period (7/30/90/365 ngày)
  - Doanh thu theo ngày
  - Top 10 sản phẩm bán chạy
  - Doanh thu theo danh mục
  - Phân tích phương thức thanh toán
  - Khách hàng mới
- `export(Request $request)` — Export Excel (FastExcel)

### WebhookController
- `handleCasso(Request $request)` — Tự động xác nhận thanh toán VietQR qua Casso/SePay
- `handleMoMo(Request $request, MoMoService $momo)` — IPN MoMo
- `handleVNPay(Request $request, VNPayService $vnpay)` — IPN VNPay

---

## 🔧 Services

### `CartService`

**Đăng ký:** Singleton trong `AppServiceProvider::register()`

**Methods:**

```php
calculateItemPrice(Product $product, ?string $size, array $modifierIds): array
// Returns: [base_price, modifier_extra, unit_price, modifiers]

addToCart(Product $product, int $quantity, ?string $size, array $modifierIds): void
// cartId = "{product_id}_{size}_{mod1-mod2}" — phân biệt cùng sản phẩm khác lựa chọn

getAvailableModifiers(Product $product): array
// Returns: [sugar => [...], ice => [...], milk => [...], topping => [...]]
// Filter theo product flags (allow_sugar, allow_ice, allow_milk, has_topping)
```

**Logic tính giá:**
1. `base_price` = `product.priceBySize(size)` nếu có size, ngược lại `effective_price`
2. `modifier_extra` = tổng `extra_price` của các modifier đã chọn
3. `unit_price` = `base_price + modifier_extra`

### `VNPayService`

**Config keys:** `services.vnpay.tmn_code`, `services.vnpay.hash_secret`, `services.vnpay.url`, `services.vnpay.return_url`

**Methods:**
```php
createPaymentUrl(Order $order, string $ipAddr): string
// Tạo URL thanh toán VNPay
// Amount = order.total * 100 (VNPay tính VND * 100)
// TxnRef = tracking_code + '_' + time() (unique)
// Ký HMAC-SHA512, ksort params trước khi hash

verifyReturn(array $params): bool
// Xác thực chữ ký callback từ VNPay
// Loại bỏ vnp_SecureHash, ksort, hash_hmac SHA512

extractTrackingCode(string $txnRef): string
// Lấy tracking_code từ "XD00001_1234567890"

isSuccess(array $params): bool
// vnp_ResponseCode === '00' && vnp_TransactionStatus === '00'
```

### `MoMoService`

**Config keys:** `services.momo.partner_code`, `services.momo.access_key`, `services.momo.secret_key`, `services.momo.endpoint`

**Methods:**
```php
createPayment(Order $order): array
// Returns: ['success' => bool, 'pay_url' => string, 'message' => string]
// Ký HMAC-SHA256 theo chuỗi rawHash cố định
// requestType = 'payWithMethod'
// ipnUrl = /webhook/momo

verifySignature(array $data): bool
// Xác thực IPN từ MoMo (HMAC-SHA256)

isSuccess(array $data): bool
// resultCode === 0

extractTrackingCode(string $orderId): string
// Lấy tracking_code từ "XD00001_1234567890"
```

---

## 🔐 Middleware & Authorization

### `AdminMiddleware`

```php
// Kiểm tra user có 1 trong 4 roles: admin, staff, cashier, warehouse
if (!$request->user()->hasAnyRole(['admin', 'staff', 'cashier', 'warehouse'])) {
    abort(403);
}
```

Áp dụng cho toàn bộ route group `/admin/*`

### `CheckOrderOwnership`

```php
// Kiểm tra order.user_id === auth()->id()
// Áp dụng cho: orders.show, orders.cancel
```

### Spatie Permission Middleware

Đăng ký alias trong `bootstrap/app.php`:
```php
'role'               => RoleMiddleware::class,
'permission'         => PermissionMiddleware::class,
'role_or_permission' => RoleOrPermissionMiddleware::class,
```

### CSRF Exemption

Webhook routes được exempt CSRF trong `bootstrap/app.php`:
```php
$middleware->validateCsrfTokens(except: ['webhook/*']);
```

---

## 🔑 Authentication

### Luồng Đăng Ký / Đăng Nhập

```
1. Breeze (email + password)
   - Register → hash password (bcrypt, 12 rounds) → assign role 'customer'
   - Login → session-based auth
   - Forgot password → email token → reset

2. Social Login (Socialite)
   - Google: GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET
   - Facebook: FACEBOOK_CLIENT_ID, FACEBOOK_CLIENT_SECRET
   - Callback: tạo/cập nhật user với provider + provider_id
   - password = null cho social users
```

### Roles & Permissions (Spatie)

| Role | Quyền truy cập |
|------|---------------|
| `admin` | Toàn bộ admin panel |
| `staff` | Admin panel (quản lý đơn hàng, sản phẩm) |
| `cashier` | Admin panel (xử lý thanh toán, đơn hàng) |
| `warehouse` | Admin panel (quản lý kho, sản phẩm) |
| `customer` | Shop frontend (đặt hàng, profile, review) |

### Session Configuration

```
SESSION_DRIVER=database
SESSION_LIFETIME=120 (phút)
```

---

## 💳 Payment Integration

### Luồng Thanh Toán Tổng Quát

```
Khách đặt hàng (OrderController@store)
    ↓
Redirect → PaymentController@index (chọn phương thức)
    ↓
┌─────────────────────────────────────────────────────┐
│ COD      → processCOD() → payment.success           │
│ VietQR   → showVietQR() → QR động → Casso webhook   │
│ VNPay    → redirectVNPay() → VNPay gateway          │
│           → vnpayReturn() hoặc webhook/vnpay        │
│ MoMo     → redirectMoMo() → MoMo gateway            │
│           → momoReturn() hoặc webhook/momo          │
└─────────────────────────────────────────────────────┘
    ↓
payment_status = 'paid' → payment.success
```

### VNPay

- **Sandbox URL:** `https://sandbox.vnpayment.vn/paymentv2/vpcpay.html`
- **Chữ ký:** HMAC-SHA512
- **Amount:** `order.total * 100` (VNPay tính VND * 100)
- **TxnRef:** `tracking_code + '_' + time()` (unique per attempt)
- **Expire:** 15 phút sau khi tạo
- **Callback:** `GET /thanh-toan/vnpay/ket-qua`
- **IPN:** `POST /webhook/vnpay`
- **Success:** `vnp_ResponseCode === '00' && vnp_TransactionStatus === '00'`

### MoMo

- **Sandbox URL:** `https://test-payment.momo.vn/v2/gateway/api/create`
- **Chữ ký:** HMAC-SHA256
- **requestType:** `payWithMethod`
- **orderId:** `tracking_code + '_' + time()`
- **Callback:** `GET /thanh-toan/momo/ket-qua`
- **IPN:** `POST /webhook/momo`
- **Success:** `resultCode === 0`

### VietQR

- **API:** `https://api.vietqr.io/v2/generate`
- **Config:** `VIETQR_BANK_ID`, `VIETQR_ACCOUNT_NO`, `VIETQR_ACCOUNT_NAME`
- **Tự động xác nhận:** Casso/SePay webhook → tìm mã `XD\d+` trong nội dung chuyển khoản
- **Tolerance:** Cho phép sai lệch ±1.000đ

### COD

- Không cần cổng thanh toán
- `payment_status = 'pending'` cho đến khi giao hàng thành công

### Phí Vận Chuyển

```php
// Tính trong OrderController@store
$hcmKeywords = ['hồ chí minh', 'hcm', 'tp.hcm', 'tp hcm', 'tphcm'];
$isHCM = collect($hcmKeywords)->contains(fn($kw) => str_contains($address, $kw));
$shippingFee = $isHCM ? 10000 : 30000;  // 10k nội thành, 30k ngoại thành
```

---

## 🤖 CaféAI Chatbot

### Kiến Trúc

```
Frontend (cafeai.js) → POST /api/chat → Api\ChatController@handle
                                              ↓
                                    detectLanguage() → vi / en
                                              ↓
                                    detectIntent() → intent string
                                              ↓
                                    processLocally() → local handler
                                              ↓ (nếu null)
                                    callClaudeAPI() → Claude Sonnet
                                              ↓
                                    ChatLog::create() → lưu lịch sử
```

### Intent Detection (Regex-based)

| Intent | Pattern |
|--------|---------|
| `order_tracking` | đơn hàng, theo dõi, XD\d+, track order |
| `weather` | thời tiết, weather, nóng quá, lạnh quá |
| `mood` | buồn, vui, mệt, stress, tired, happy |
| `recommendation` | gợi ý, recommend, nên uống, best drink |
| `product_lookup` | menu, sản phẩm, cà phê, coffee, giá, price |
| `greeting` | xin chào, hello, hi, hey |
| `escalation` | giúp đỡ, hỗ trợ, hotline, liên hệ |
| `general` | fallback → Claude API |

### Language Detection

```php
// Phát hiện tiếng Việt qua Unicode characters
if (preg_match('/[àáâãèéê...]/u', $message)) return 'vi';
return 'en';
```

### Local Processing

- **greeting** — Chào hỏi + thời tiết hiện tại
- **product_lookup** — `smartProductSearch()` (LIKE name/description)
- **order_tracking** — Tìm đơn theo tracking_code hoặc ID, cần đăng nhập
- **weather** — OpenWeather API → gợi ý đồ uống theo nhiệt độ
- **mood** — Phát hiện cảm xúc → gợi ý đồ uống phù hợp
- **recommendation** — Top sản phẩm active + in stock
- **escalation** — Thông tin liên hệ (hotline, email, địa chỉ)

### Claude API Fallback

- **Model:** `claude-sonnet-4-20250514` (configurable)
- **Context:** Menu 20 sản phẩm + thời tiết + lịch sử chat 10 tin nhắn
- **Product extraction:** Parse `[ID:X]` từ response → load Product models
- **Timeout:** 30 giây

### Weather Integration

- **API:** OpenWeather API (`api.openweathermap.org/data/2.5/weather`)
- **City:** `SHOP_CITY=Ho Chi Minh City`
- **Fallback:** `{city: 'TP.HCM', temp: 32, humidity: 75, description: 'Nắng nóng'}`
- **Logic gợi ý:**
  - `temp >= 30°C` → đồ uống lạnh (trà đá, nước lạnh, iced)
  - `temp <= 20°C` → đồ uống nóng (latte, capuccino, espresso)
  - `20–30°C` → bất kỳ

### Market Gap Tracking

Khi `product_lookup` không tìm thấy kết quả:
```php
ProductRequest::logRequest($message, $message);
// Tự động increment request_count nếu đã tồn tại
// Admin có thể xem để biết khách hàng muốn gì
```

---

## 🔔 Notifications & Real-time

### Drink Status Notification Flow

```
Admin cập nhật drink_status (DrinkStatusController@update)
    ↓
Order model updated → OrderObserver::updated()
    ↓ (nếu drink_status thay đổi)
$order->user->notify(new DrinkStatusUpdated($order))
    ↓
Lưu vào bảng notifications (channel: database)
    ↓
Livewire OrderStatusBell polling 5s → hiển thị trên navbar
```

### `DrinkStatusUpdated` Notification

- **Channel:** `database` (implements `ShouldQueue`)
- **Data:**
  ```php
  [
      'order_id', 'tracking_code', 'drink_status',
      'status_label', 'icon', 'message',
      'brewing_at', 'completed_at'
  ]
  ```
- **Messages:**
  - `pending` → "Đơn hàng #XD00001 đã được nhận. Chúng tôi đang chuẩn bị!"
  - `brewing` → "Đơn hàng #XD00001 đang được pha chế. Vui lòng chờ trong giây lát!"
  - `completed` → "Đơn hàng #XD00001 đã hoàn thành. Mời bạn nhận đồ!"

### `OrderStatusBell` Livewire Component

```php
// Polling mỗi 5 giây
#[Poll('5s')]
public function refreshCount(): void

// Hiển thị đơn hàng có drink_status: pending hoặc brewing
// Lấy tối đa 5 đơn gần nhất
```

---

## 👁️ Observers & Events

### `OrderObserver`

Đăng ký trong `AppServiceProvider::boot()`:
```php
Order::observe(OrderObserver::class);
```

**Method `updated(Order $order)`:**
- Kiểm tra `$order->isDirty('drink_status')`
- Nếu có thay đổi và `user_id` tồn tại → gửi `DrinkStatusUpdated` notification

---

## 📦 Thư Viện Bên Ngoài

### Composer Packages

| Package | Mục đích |
|---------|----------|
| `laravel/breeze` | Authentication scaffolding |
| `laravel/socialite` | OAuth (Google, Facebook) |
| `spatie/laravel-permission` | Role & Permission management |
| `spatie/laravel-medialibrary` | File/media management |
| `spatie/laravel-activitylog` | Activity logging |
| `cviebrock/eloquent-sluggable` | Auto-generate slugs |
| `darryldecode/cart` | Session-based shopping cart |
| `rap2hpoutre/fast-excel` | Excel export |
| `livewire/livewire` | Real-time components (polling) |

### NPM Packages

| Package | Mục đích |
|---------|----------|
| `vite` | Build tool |
| `laravel-vite-plugin` | Laravel + Vite integration |
| `tailwindcss` | CSS framework (chỉ auth pages) |
| `alpinejs` | Reactive JS (Breeze) |
| `postcss` | CSS processing |
| `autoprefixer` | CSS vendor prefixes |

---

## ⚙️ Cấu Hình Môi Trường

### `.env` Keys Quan Trọng

```env
# App
APP_NAME=Laravel
APP_ENV=local
APP_URL=http://127.0.0.1:8000

# Database
DB_CONNECTION=sqlite  (dev) / mysql (production)

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Google OAuth
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback

# Facebook OAuth
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT_URI=http://127.0.0.1:8000/auth/facebook/callback

# Claude AI (CaféAI)
CLAUDE_API_KEY=
CLAUDE_MODEL=claude-sonnet-4-20250514

# OpenWeather (CaféAI)
OPENWEATHER_API_KEY=
SHOP_CITY=Ho Chi Minh City

# VNPay
VNPAY_TMN_CODE=
VNPAY_HASH_SECRET=
VNPAY_URL=https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
VNPAY_RETURN_URL=http://127.0.0.1:8000/thanh-toan/vnpay/ket-qua

# MoMo
MOMO_PARTNER_CODE=
MOMO_ACCESS_KEY=
MOMO_SECRET_KEY=
MOMO_ENDPOINT=https://test-payment.momo.vn/v2/gateway/api/create

# VietQR
VIETQR_BANK_ID=970415
VIETQR_ACCOUNT_NO=0978853110
VIETQR_ACCOUNT_NAME=XDTHECOFFEEHOUSE
```

### Config Files

| File | Nội dung |
|------|----------|
| `config/services.php` | VNPay, MoMo, VietQR, Claude, OpenWeather, Google, Facebook |
| `config/auth.php` | Guards, providers |
| `config/permission.php` | Spatie Permission config |
| `bootstrap/app.php` | Middleware aliases, CSRF exemptions, routing |

---

## 📏 Quy Tắc Phát Triển

### 1. Business Logic

- **Giá sản phẩm:** Luôn dùng `effective_price` (= `discount_price ?? price`)
- **Snapshot giá:** Khi tạo `OrderItem`, lưu `product_name`, `product_image`, `base_price`, `unit_price` — không phụ thuộc vào giá hiện tại của sản phẩm
- **Tracking code:** Tự động tạo `XD` + 5 chữ số trong `Order::booted()`
- **Phí ship:** 10.000đ nội thành HCM, 30.000đ ngoại thành (detect từ địa chỉ)
- **Drink status:** Chỉ set khi đơn có sản phẩm có `size` (đồ uống dùng ly)

### 2. Database

- Dùng **DB Transaction** cho mọi thao tác tạo đơn hàng (`DB::beginTransaction()`)
- **Soft delete** cho Product — không xóa cứng, dùng `restore()` để khôi phục
- **Snapshot** tên và giá sản phẩm trong `order_items` — tránh thay đổi lịch sử đơn hàng
- **nullOnDelete** cho `product_id` trong `order_items` — giữ lịch sử khi sản phẩm bị xóa

### 3. Validation

- Phone: `regex:/^[0-9]{10,11}$/`
- Rating: `integer|min:1|max:5`
- Message (chat): `string|max:1000`
- Luôn dùng custom Vietnamese error messages trong `validate()`

### 4. Security

- **CSRF:** Bật cho tất cả routes, chỉ exempt `/webhook/*`
- **Order ownership:** Middleware `CheckOrderOwnership` cho mọi route xem/hủy đơn
- **Admin access:** `AdminMiddleware` kiểm tra role trước khi vào admin panel
- **Payment signature:** Luôn verify HMAC trước khi cập nhật `payment_status`
- **Webhook tolerance:** VietQR cho phép sai lệch ±1.000đ, log warning nếu sai lệch lớn hơn

### 5. Logging

```php
Log::channel('daily')->info('Casso Webhook', $request->all());
Log::warning('MoMo IPN: Invalid signature');
Log::error('CaféAI Error: ' . $e->getMessage());
```

- Dùng `daily` channel cho webhook logs
- Dùng `Log::error()` cho exceptions trong ChatController
- Dùng `Log::warning()` cho invalid signatures

### 6. Pagination

- Shop: `paginate(12)` cho danh sách sản phẩm
- Admin: `paginate(20)` cho danh sách đơn hàng, users
- Orders history: `paginate(10)`
- Dùng Bootstrap 4 pagination (cấu hình trong `AppServiceProvider::boot()`)

### 7. Naming Conventions

```
Controllers: PascalCase, suffix Controller (ProductController)
Models: PascalCase, singular (Product, Order, OrderItem)
Routes: kebab-case, tiếng Việt không dấu (san-pham, gio-hang, dat-hang)
Route names: dot notation (products.index, orders.show, admin.dashboard)
Migrations: snake_case, timestamp prefix
```

### 8. Quy Tắc Thêm Module Mới

1. Tạo migration → `php artisan migrate`
2. Tạo Model với relationships, scopes, accessors
3. Tạo Controller (Shop hoặc Admin namespace)
4. Đăng ký routes trong `web.php` (đúng middleware group)
5. Tạo Blade views trong `resources/views/shop/` hoặc `resources/views/admin/`
6. Nếu cần notification → tạo Notification class + Observer
7. Nếu cần real-time → tạo Livewire component với `#[Poll]`

---

## 📌 Tóm Tắt Modules Chính

| Module | Controller | Service | Model |
|--------|-----------|---------|-------|
| **Shop** | `HomeController` | — | `Product`, `Category`, `Banner` |
| **Cart** | `CartController` | `CartService` | Session (darryldecode/cart) |
| **Order** | `OrderController` | — | `Order`, `OrderItem`, `OrderItemModifier` |
| **Payment** | `PaymentController` | `VNPayService`, `MoMoService` | `Order` |
| **Drink Status** | `DrinkStatusController` | — | `Order` + Observer + Notification |
| **Review** | `ReviewController` | — | `Review` |
| **Profile** | `ProfileController` | — | `User` |
| **Admin** | `Admin\*Controller` | — | All models |
| **Statistics** | `StatisticsController` | — | `Order`, `Product`, `User` |
| **CaféAI** | `Api\ChatController` | Claude API, OpenWeather | `ChatLog`, `ProductRequest` |
| **Notifications** | — | — | `Notification` (database) + Livewire |
| **Auth** | `Auth\*Controller` | Socialite | `User` + Spatie Permission |
