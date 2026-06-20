# 🚀 Kế Hoạch Chuyển Đổi PHP Thuần → Laravel
> **Dự án:** XDTHECOFFEEHOUSE — Website bán cà phê  
> **Database hiện tại:** `mycfshop` (15 bảng)  
> **Mục tiêu:** Chuyển toàn bộ sang Laravel với kiến trúc chuẩn, hiện đại hơn  
> **Ưu tiên giảng viên:** Database phải có Migration + Seeder + Eloquent Model chuẩn

---

## 📋 Tổng Quan Các Giai Đoạn

| Giai đoạn | Tên | Ưu tiên |
|-----------|-----|---------|
| Phase 1 | Khởi tạo dự án Laravel & cấu hình | 🔴 Bắt buộc trước |
| Phase 2 | Database: Migration + Seeder + Model | 🔴 Ưu tiên cao nhất (yêu cầu GV) |
| Phase 3 | Giao diện: Blade Layout + Assets | 🟡 Sau khi DB xong |
| Phase 4 | Xác thực & Phân quyền | 🟡 Song song Phase 3 |
| Phase 5 | Các Module Chức Năng Chính | 🟢 Core business |
| Phase 6 | Thanh toán nâng cao | 🟢 Tính năng hiện đại |
| Phase 7 | AI Chatbox & Tính năng đặc biệt | 🔵 Nâng cao |
| Phase 8 | Admin Dashboard (Filament) | ✅ **HOÀN THÀNH** |
| Phase 9 | Hoàn thiện & Deploy | ⚪ Cuối cùng |

---

## 📦 Kiến Trúc Package Tổng Thể

### Frontend & Customer Layer
| Package | Chức năng | Lý do chọn |
|---------|-----------|------------|
| `laravel/breeze` | Auth scaffolding (login/register) | Nhẹ, dễ tùy chỉnh với Blade |
| `laravel/socialite` | Đăng nhập Google / Facebook | Thay thế login thủ công hiện tại |
| `livewire/livewire` | UI động: giỏ hàng, thông báo real-time | Thay AJAX thuần, không cần viết JS nhiều |
| `spatie/laravel-medialibrary` | Upload ảnh sản phẩm, avatar | Thay hệ thống upload thủ công |
| `laravel/scout` | Tìm kiếm sản phẩm full-text | Nâng cấp tìm kiếm hiện tại |
| `cviebrock/eloquent-sluggable` | URL sản phẩm thân thiện SEO | `/san-pham/ca-phe-capuccino` |

### Order & Payment Layer
| Package | Chức năng | Lý do chọn |
|---------|-----------|------------|
| `darryldecode/cart` | Giỏ hàng session-based | Thay GioHang model thủ công |
| `laravel/cashier` hoặc tích hợp thủ công | VNPay / VietQR / MoMo | Xem chi tiết Phase 6 |
| `barryvdh/laravel-dompdf` | Xuất hóa đơn PDF | Thay export thủ công |
| `maatwebsite/excel` | Xuất báo cáo Excel | Thay export_stats.php |
| `laravel/notifications` | Email + SMS theo dõi đơn | Thay EmailService.php thủ công |

### Admin Dashboard Layer
| Package | Chức năng | Lý do chọn |
|---------|-----------|------------|
| `filament/filament` | Admin CRUD + Dashboard | Thay toàn bộ Admin2 folder |
| `spatie/laravel-permission` | Role: admin, staff, khách | Thay bảng quyen_nhanvien thủ công |
| `spatie/laravel-activitylog` | Log hoạt động nhân viên | Thay log thủ công |
| `spatie/laravel-backup` | Backup tự động DB + file | Tính năng mới |

### Backend & Core Layer
| Package | Chức năng | Lý do chọn |
|---------|-----------|------------|
| `laravel/telescope` | Debug, monitoring queries | Thay webhook_log.txt thủ công |
| `laravel/horizon` | Queue: email, thông báo | Thay gửi email đồng bộ |
| `intervention/image` | Resize, crop ảnh sản phẩm | Thay upload ảnh thủ công |
| `spatie/laravel-settings` | Cài đặt hệ thống (cafe_ai_config) | Thay bảng cafe_ai_config |

---

## 🔴 PHASE 1 — Khởi Tạo Dự Án Laravel

**Thời gian ước tính:** 1 ngày  
**Mục tiêu:** Tạo project Laravel mới, cấu hình môi trường, thiết lập cấu trúc thư mục

### Các bước thực hiện

```bash
# 1. Tạo project Laravel mới (Laravel 11.x)
composer create-project laravel/laravel coffeeshop-laravel

# 2. Cấu hình .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mycfshop_laravel   # Database mới, không ghi đè DB cũ
DB_USERNAME=root
DB_PASSWORD=

# 3. Cài các package cốt lõi ngay từ đầu
composer require laravel/breeze spatie/laravel-permission
php artisan breeze:install blade
npm install && npm run build
```

### Cấu trúc thư mục mục tiêu
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Thay Admin2/
│   │   ├── Auth/           # Breeze tự tạo
│   │   └── Shop/           # Các controller frontend
│   ├── Middleware/
│   │   ├── AdminMiddleware.php
│   │   └── CheckRole.php
│   └── Requests/           # Form validation (thay Validator.php)
├── Models/                 # Eloquent Models
├── Services/               # Business logic (EmailService, PaymentService...)
└── Notifications/          # Laravel Notifications

resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php   # Layout frontend
│   │   └── admin.blade.php # Layout admin
│   ├── components/         # Blade components tái sử dụng
│   ├── shop/               # Views frontend
│   └── admin/              # Views admin (hoặc dùng Filament)
```

### Routes cần thiết
```php
// routes/web.php — Frontend
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::prefix('san-pham')->group(...);   // SanPhamController
Route::prefix('gio-hang')->group(...);   // CartController
Route::prefix('dat-hang')->group(...);   // OrderController
Route::prefix('thanh-toan')->group(...); // PaymentController

// routes/web.php — Admin (bảo vệ bằng middleware)
Route::prefix('admin')->middleware(['auth', 'role:admin|staff'])->group(...);

// routes/api.php — AI Chatbox API
Route::prefix('api')->group(function() {
    Route::post('/chat', [ChatController::class, 'handle']);
    Route::post('/webhook/payment', [WebhookController::class, 'handle']);
});
```

---

## 🔴 PHASE 2 — Database: Migration + Seeder + Eloquent Model
> ⚠️ **Đây là giai đoạn ưu tiên cao nhất theo yêu cầu giảng viên**

**Thời gian ước tính:** 2-3 ngày  
**Mục tiêu:** Viết lại toàn bộ 15 bảng thành Migration chuẩn Laravel, tạo Seeder + Factory

### 2.1 — Danh sách Migration cần tạo

Thứ tự tạo migration phải đúng theo quan hệ khóa ngoại:

```bash
# Chạy theo thứ tự này
php artisan make:migration create_categories_table          # loaisanpham
php artisan make:migration create_products_table            # hanghoa
php artisan make:migration create_users_table               # khachhang (merge với users mặc định)
php artisan make:migration create_employees_table           # nhanvien
php artisan make:migration create_orders_table              # donhang
php artisan make:migration create_order_items_table         # chitietdonhang
php artisan make:migration create_reviews_table             # reviews
php artisan make:migration create_notifications_table       # thongbao
php artisan make:migration create_discounts_table           # giamgia_danhmuc
php artisan make:migration create_banners_table             # quangcao
php artisan make:migration create_chat_logs_table           # chat_logs (CaféAI)
php artisan make:migration create_product_requests_table    # product_requests (CaféAI)
php artisan make:migration create_settings_table            # cafe_ai_config → settings
php artisan make:migration create_product_sizes_table       # Mới: Bảng size M/L/XL
php artisan make:migration create_modifiers_table           # Mới: Tùy chỉnh (đường, đá, sữa, topping)
php artisan make:migration create_order_item_modifiers_table # Mới: Modifier đã chọn trong từng item
```

### 2.2 — Cải tiến Database so với bản cũ

| Bảng cũ | Bảng mới | Thay đổi |
|---------|----------|----------|
| `khachhang` | `users` | Dùng bảng `users` mặc định Laravel, thêm `avatar`, `provider` (Google/Facebook) |
| `admin` + `nhanvien` | `users` + `roles` | Gộp vào `users`, phân quyền bằng `spatie/permission` |
| `quyen_nhanvien` | `roles` + `permissions` | Dùng Spatie Permission thay bảng thủ công |
| `donhang` | `orders` | Đổi tên cột sang tiếng Anh chuẩn, thêm `tracking_code` |
| `chitietdonhang` | `order_items` | Chuẩn hóa tên |
| `hanghoa` | `products` | Thêm `slug`, `is_featured`, `discount_price` |
| `loaisanpham` | `categories` | Thêm `slug`, hỗ trợ danh mục cha-con |
| `cafe_ai_config` | `settings` | Dùng `spatie/laravel-settings` |
| _(chưa có)_ | `product_sizes` | **Mới:** Size M/L/XL với giá riêng cho từng sản phẩm dùng ly |
| _(chưa có)_ | `modifiers` | **Mới:** Nhóm tùy chỉnh: đường, đá, loại sữa, topping |
| _(chưa có)_ | `order_item_modifiers` | **Mới:** Lưu modifier đã chọn cho từng order item |

### 2.3 — Ví dụ Migration chuẩn

```php
// database/migrations/xxxx_create_products_table.php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained()->nullOnDelete();
    $table->string('name');
    $table->string('slug')->unique();           // Mới: SEO-friendly URL
    $table->text('description')->nullable();
    $table->decimal('price', 10, 2);
    $table->decimal('discount_price', 10, 2)->nullable(); // Mới
    $table->integer('stock')->default(0);
    $table->boolean('is_active')->default(true);
    $table->boolean('is_featured')->default(false); // Mới
    $table->timestamps();                        // created_at + updated_at chuẩn Laravel
    $table->softDeletes();                       // Mới: xóa mềm
});
```

### 2.4 — Eloquent Models cần tạo

```bash
php artisan make:model Category -mfs    # -m migration, -f factory, -s seeder
php artisan make:model Product -mfs
php artisan make:model Order -mfs
php artisan make:model OrderItem -mfs
php artisan make:model Review -mfs
php artisan make:model Notification -mfs
php artisan make:model Discount -mfs
php artisan make:model Banner -mfs
php artisan make:model ChatLog -mfs
php artisan make:model ProductRequest -mfs
php artisan make:model ProductSize -mfs      # Mới: Size M/L/XL
php artisan make:model Modifier -mfs         # Mới: Nhóm modifier
php artisan make:model OrderItemModifier -m  # Mới: Modifier đã chọn
```

### 2.5 — Relationships trong Model

```php
// app/Models/Product.php
class Product extends Model {
    use HasSlug, SoftDeletes, HasMedia;  // Sluggable + MediaLibrary

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }
    public function orderItems(): HasMany {
        return $this->hasMany(OrderItem::class);
    }
    public function reviews(): HasMany {
        return $this->hasMany(Review::class);
    }
}

// app/Models/Order.php
class Order extends Model {
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function items(): HasMany {
        return $this->hasMany(OrderItem::class);
    }
}
```

### 2.6 — Seeders & Factories

```bash
# Chạy seed dữ liệu mẫu
php artisan db:seed --class=CategorySeeder    # 4 danh mục cà phê
php artisan db:seed --class=ProductSeeder     # 17 sản phẩm từ DB cũ
php artisan db:seed --class=UserSeeder        # Admin + khách hàng mẫu
php artisan db:seed --class=BannerSeeder      # 3 banner quảng cáo

# Hoặc chạy tất cả
php artisan migrate:fresh --seed
```

### 2.7 — Migration: Chi Tiết Sản Phẩm & Modifier

> 🆕 **Tính năng mới:** Cho phép khách chọn size, đường, đá, sữa, topping — hệ thống tự tính giá

#### Bảng `product_sizes` — Size cho đồ uống dùng ly
```php
// database/migrations/xxxx_create_product_sizes_table.php
Schema::create('product_sizes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->enum('size', ['M', 'L', 'XL']);
    $table->decimal('price', 10, 2);   // M=giá thấp, L=giá vừa, XL=giá cao
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    $table->unique(['product_id', 'size']); // mỗi sản phẩm chỉ có 1 giá mỗi size
});
```

#### Bảng `modifiers` — Nhóm tùy chỉnh (áp dụng cho tất cả sản phẩm)
```php
// database/migrations/xxxx_create_modifiers_table.php
// Modifier type: 'sugar' | 'ice' | 'milk' | 'topping'
Schema::create('modifiers', function (Blueprint $table) {
    $table->id();
    $table->string('name');                        // VD: "Thêm đường", "Sữa tươi", "Trân châu"
    $table->enum('type', ['sugar', 'ice', 'milk', 'topping']);
    $table->decimal('extra_price', 8, 2)->default(0); // Phụ phí thêm (topping có giá)
    $table->boolean('applies_to_drink')->default(true);  // Đồ uống dùng ly
    $table->boolean('applies_to_tea_juice')->default(false); // Trà/nước trái cây (topping)
    $table->boolean('is_active')->default(true);
    $table->integer('sort_order')->default(0);
    $table->timestamps();
});
```

#### Bảng `order_item_modifiers` — Lưu modifier đã chọn
```php
// database/migrations/xxxx_create_order_item_modifiers_table.php
Schema::create('order_item_modifiers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
    $table->foreignId('modifier_id')->constrained()->cascadeOnDelete();
    $table->decimal('extra_price_snapshot', 8, 2)->default(0); // snapshot giá lúc đặt
    $table->timestamps();
});
```

#### Cập nhật bảng `products` — Thêm cờ phân loại
```php
// Thêm vào migration create_products_table
$table->boolean('has_size')->default(false);        // true = đồ uống dùng ly (có M/L/XL)
$table->boolean('has_topping')->default(false);     // true = trà / nước trái cây
$table->boolean('allow_sugar')->default(true);      // Cho phép chọn đường
$table->boolean('allow_ice')->default(true);        // Cho phép chọn đá
$table->boolean('allow_milk')->default(false);      // Cho phép chọn loại sữa
```

#### Cập nhật bảng `order_items` — Lưu size & giá tính toán
```php
// Thêm vào migration create_order_items_table
$table->enum('size', ['M', 'L', 'XL'])->nullable();          // Size đã chọn
$table->decimal('base_price', 10, 2);                        // Giá gốc theo size
$table->decimal('modifier_extra', 8, 2)->default(0);         // Tổng phụ phí modifier
$table->decimal('unit_price', 10, 2);                        // Đơn giá cuối = base + modifier
```

### 2.8 — Seeder Modifier Mặc Định

```php
// database/seeders/ModifierSeeder.php
class ModifierSeeder extends Seeder {
    public function run(): void {
        $modifiers = [
            // --- Đường ---
            ['name' => 'Ít đường (30%)',  'type' => 'sugar', 'extra_price' => 0, 'applies_to_drink' => true, 'applies_to_tea_juice' => true],
            ['name' => 'Nửa đường (50%)', 'type' => 'sugar', 'extra_price' => 0, 'applies_to_drink' => true, 'applies_to_tea_juice' => true],
            ['name' => 'Bình thường',     'type' => 'sugar', 'extra_price' => 0, 'applies_to_drink' => true, 'applies_to_tea_juice' => true],
            ['name' => 'Thêm đường',      'type' => 'sugar', 'extra_price' => 0, 'applies_to_drink' => true, 'applies_to_tea_juice' => true],
            // --- Đá ---
            ['name' => 'Không đá',   'type' => 'ice', 'extra_price' => 0, 'applies_to_drink' => true, 'applies_to_tea_juice' => true],
            ['name' => 'Ít đá',      'type' => 'ice', 'extra_price' => 0, 'applies_to_drink' => true, 'applies_to_tea_juice' => true],
            ['name' => 'Bình thường','type' => 'ice', 'extra_price' => 0, 'applies_to_drink' => true, 'applies_to_tea_juice' => true],
            ['name' => 'Thêm đá',    'type' => 'ice', 'extra_price' => 0, 'applies_to_drink' => true, 'applies_to_tea_juice' => true],
            // --- Sữa ---
            ['name' => 'Sữa tươi',  'type' => 'milk', 'extra_price' => 5000,  'applies_to_drink' => true, 'applies_to_tea_juice' => false],
            ['name' => 'Sữa đặc',   'type' => 'milk', 'extra_price' => 3000,  'applies_to_drink' => true, 'applies_to_tea_juice' => false],
            // --- Topping (chỉ cho trà / nước trái cây) ---
            ['name' => 'Trân châu trắng', 'type' => 'topping', 'extra_price' => 10000, 'applies_to_drink' => false, 'applies_to_tea_juice' => true],
            ['name' => 'Trân châu đen',   'type' => 'topping', 'extra_price' => 10000, 'applies_to_drink' => false, 'applies_to_tea_juice' => true],
            ['name' => 'Thạch cà phê',    'type' => 'topping', 'extra_price' => 8000,  'applies_to_drink' => false, 'applies_to_tea_juice' => true],
            ['name' => 'Thạch lá dứa',    'type' => 'topping', 'extra_price' => 8000,  'applies_to_drink' => false, 'applies_to_tea_juice' => true],
            ['name' => 'Pudding trứng',   'type' => 'topping', 'extra_price' => 12000, 'applies_to_drink' => false, 'applies_to_tea_juice' => true],
        ];
        foreach ($modifiers as $i => $m) {
            Modifier::create(array_merge($m, ['sort_order' => $i, 'is_active' => true]));
        }
    }
}
```

### 2.9 — Relationships Model Cho Modifier

```php
// app/Models/Product.php — thêm quan hệ
public function sizes(): HasMany {
    return $this->hasMany(ProductSize::class)->orderBy('size');
}
// Helper: lấy giá theo size
public function priceBySize(string $size): float {
    return $this->sizes->firstWhere('size', $size)?->price ?? $this->price;
}

// app/Models/OrderItem.php
public function modifiers(): BelongsToMany {
    return $this->belongsToMany(Modifier::class, 'order_item_modifiers')
                ->withPivot('extra_price_snapshot')
                ->withTimestamps();
}
// Tự tính đơn giá cuối
public function calculateUnitPrice(): float {
    return $this->base_price + $this->modifier_extra;
}

// app/Models/Modifier.php
public function orderItems(): BelongsToMany {
    return $this->belongsToMany(OrderItem::class, 'order_item_modifiers')
                ->withPivot('extra_price_snapshot');
}
```

### 2.10 — Logic Tính Giá Tự Động (CartService)

```php
// app/Services/CartService.php
class CartService {
    /**
     * Tính đơn giá cuối = giá theo size + tổng phụ phí modifier
     */
    public function calculateItemPrice(
        Product $product,
        ?string $size,
        array $modifierIds
    ): array {
        // 1. Giá gốc theo size (nếu có)
        $basePrice = $product->has_size && $size
            ? $product->priceBySize($size)
            : $product->price;

        // 2. Tổng phụ phí modifier
        $modifiers  = Modifier::whereIn('id', $modifierIds)->get();
        $extraTotal = $modifiers->sum('extra_price');

        return [
            'base_price'     => $basePrice,
            'modifier_extra' => $extraTotal,
            'unit_price'     => $basePrice + $extraTotal,
            'modifiers'      => $modifiers,
        ];
    }

    /**
     * Thêm sản phẩm vào giỏ hàng (session)
     */
    public function addToCart(
        Product $product,
        int $quantity,
        ?string $size,
        array $modifierIds
    ): void {
        $priceData = $this->calculateItemPrice($product, $size, $modifierIds);

        // Dùng darryldecode/cart với extra options
        Cart::add([
            'id'         => $product->id . '_' . $size . '_' . implode('-', $modifierIds),
            'name'       => $product->name,
            'price'      => $priceData['unit_price'],
            'quantity'   => $quantity,
            'attributes' => [
                'size'           => $size,
                'base_price'     => $priceData['base_price'],
                'modifier_extra' => $priceData['modifier_extra'],
                'modifier_ids'   => $modifierIds,
                'modifier_names' => $priceData['modifiers']->pluck('name')->join(', '),
                'image'          => $product->getFirstMediaUrl('products'),
            ],
        ]);
    }
}
```

### 2.11 — Thông Báo Real-Time Trạng Thái Đơn Hàng

> 🔔 **Yêu cầu:** Áp dụng cho đồ uống dùng ly (cà phê, trà, nước trái cây). Thông báo hiển thị trên Header.
> **Luồng:** `Đã nhận đơn` → `Đang pha chế` → `Đã hoàn thành`

#### Migration bổ sung cho `orders`
```php
// Thêm vào create_orders_table
$table->enum('drink_status', ['pending', 'brewing', 'completed'])
      ->nullable()       // null = không phải đơn đồ uống
      ->comment('Chỉ dùng cho đơn có sản phẩm dùng ly');
$table->timestamp('brewing_at')->nullable();   // Thời điểm bắt đầu pha chế
$table->timestamp('completed_at')->nullable(); // Thời điểm hoàn thành
```

#### Livewire Component: Chuông thông báo Header
```bash
php artisan make:livewire OrderStatusBell   # Hiển thị trên header
php artisan make:livewire OrderStatusBadge  # Badge mini số đơn đang xử lý
```

```php
// app/Livewire/OrderStatusBell.php
class OrderStatusBell extends Component {
    // Livewire polling mỗi 5 giây (hoặc dùng Echo nếu có Pusher)
    #[\Livewire\Attributes\Poll('5s')]
    public function render() {
        $orders = [];
        if (auth()->check()) {
            $orders = Order::where('user_id', auth()->id())
                ->whereIn('drink_status', ['pending', 'brewing'])
                ->latest()
                ->take(5)
                ->get();
        }
        return view('livewire.order-status-bell', compact('orders'));
    }
}
```

```blade
{{-- resources/views/livewire/order-status-bell.blade.php --}}
<div class="notification-bell">
    @if($orders->count() > 0)
        <span class="badge">{{ $orders->count() }}</span>
    @endif
    <div class="dropdown-menu">
        @forelse($orders as $order)
            <div class="notification-item status-{{ $order->drink_status }}">
                @php
                    $statusLabel = match($order->drink_status) {
                        'pending'  => '✅ Đã nhận đơn',
                        'brewing'  => '☕ Đang pha chế',
                        'completed'=> '🎉 Đã hoàn thành',
                    };
                @endphp
                <strong>#{{ $order->tracking_code }}</strong>
                <span>{{ $statusLabel }}</span>
                <small>{{ $order->updated_at->diffForHumans() }}</small>
            </div>
        @empty
            <p>Không có đơn hàng đang xử lý</p>
        @endforelse
    </div>
</div>
```

#### Observer tự động cập nhật trạng thái
```bash
php artisan make:observer OrderObserver --model=Order
```

```php
// app/Observers/OrderObserver.php
class OrderObserver {
    public function updated(Order $order): void {
        // Khi drink_status thay đổi → gửi notification cho khách
        if ($order->isDirty('drink_status')) {
            $order->user->notify(new DrinkStatusUpdated($order));
        }
    }
}

// app/Notifications/DrinkStatusUpdated.php
class DrinkStatusUpdated extends Notification {
    public function __construct(public Order $order) {}

    public function via($notifiable): array {
        return ['database']; // Lưu vào bảng notifications → Livewire đọc
    }

    public function toDatabase($notifiable): array {
        return [
            'order_id'      => $this->order->id,
            'tracking_code' => $this->order->tracking_code,
            'drink_status'  => $this->order->drink_status,
            'message'       => match($this->order->drink_status) {
                'pending'   => 'Đơn hàng #' . $this->order->tracking_code . ' đã được nhận.',
                'brewing'   => 'Đơn hàng #' . $this->order->tracking_code . ' đang được pha chế.',
                'completed' => 'Đơn hàng #' . $this->order->tracking_code . ' đã hoàn thành. Mời bạn nhận đồ!',
            },
        ];
    }
}
```

#### Dashboard Admin — Cập nhật trạng thái pha chế
```php
// app/Http/Controllers/Admin/DrinkStatusController.php
class DrinkStatusController extends Controller {
    // Nhân viên bấm nút chuyển trạng thái từ dashboard
    public function update(Request $request, Order $order) {
        $nextStatus = match($order->drink_status) {
            'pending'  => 'brewing',
            'brewing'  => 'completed',
            default    => null,
        };
        if ($nextStatus) {
            $order->update([
                'drink_status' => $nextStatus,
                'brewing_at'   => $nextStatus === 'brewing'   ? now() : $order->brewing_at,
                'completed_at' => $nextStatus === 'completed' ? now() : null,
            ]);
        }
        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }
}
```

---

## 🟡 PHASE 3 — Giao Diện: Blade Layout + Assets

**Thời gian ước tính:** 2-3 ngày  
**Mục tiêu:** Chuyển toàn bộ HTML/CSS/JS cũ sang Blade template, tái sử dụng UI hiện có

### 3.1 — Cài đặt Assets

```bash
# Vite đã có sẵn trong Laravel 11
# Copy CSS/JS cũ vào resources/
cp -r css/ resources/css/
cp -r js/ resources/js/
cp -r images/ public/images/

# vite.config.js — thêm các file cần bundle
export default defineConfig({
    plugins: [laravel({
        input: ['resources/css/app.css', 'resources/js/app.js'],
    })],
});
```

### 3.2 — Blade Layouts

```
resources/views/layouts/
├── app.blade.php          # Layout chính frontend (header + footer)
├── admin.blade.php        # Layout admin panel
└── auth.blade.php         # Layout trang login/register
```

**Cách chuyển header.php → header component:**
```blade
{{-- resources/views/components/navbar.blade.php --}}
<nav class="navbar">
    {{-- Chuyển HTML từ View/header.php sang đây --}}
    {{-- Thay $_SESSION['khachhang'] bằng auth()->user() --}}
    @auth
        <span>Xin chào, {{ auth()->user()->name }}</span>
    @endauth
</nav>
```

### 3.3 — Blade Components tái sử dụng

```bash
php artisan make:component ProductCard      # Card sản phẩm
php artisan make:component CartBadge        # Badge số lượng giỏ hàng
php artisan make:component Alert            # Thông báo flash
php artisan make:component Modal            # Modal popup
php artisan make:component Pagination       # Phân trang
```

### 3.4 — Chuyển đổi PHP thuần → Blade

| PHP thuần cũ | Blade tương đương |
|-------------|-------------------|
| `<?php if($user): ?>` | `@if(auth()->check())` |
| `$_SESSION['khachhang']` | `auth()->user()` |
| `include 'header.php'` | `@include('components.navbar')` |
| `foreach($products as $p)` | `@foreach($products as $product)` |
| `echo htmlspecialchars($name)` | `{{ $name }}` (tự escape) |
| `header('Location: ...')` | `return redirect()->route('...')` |

---

## 🟡 PHASE 4 — Xác Thực & Phân Quyền

**Thời gian ước tính:** 2 ngày  
**Packages:** `laravel/breeze`, `laravel/socialite`, `spatie/laravel-permission`

### 4.1 — Authentication với Breeze

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
# Tự tạo: login, register, forgot-password, reset-password views
```

**Cải tiến so với bản cũ:** Thêm đăng nhập Google + Facebook

```bash
composer require laravel/socialite
```

```php
// config/services.php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT_URI'),
],
```

```php
// app/Http/Controllers/Auth/SocialiteController.php
public function redirectToGoogle() {
    return Socialite::driver('google')->redirect();
}
public function handleGoogleCallback() {
    $googleUser = Socialite::driver('google')->user();
    $user = User::updateOrCreate(
        ['email' => $googleUser->email],
        ['name' => $googleUser->name, 'provider' => 'google', 'provider_id' => $googleUser->id]
    );
    Auth::login($user);
    return redirect()->route('home');
}
```

### 4.2 — Phân Quyền với Spatie Permission

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

```php
// Các roles thay thế bảng quyen_nhanvien cũ
$roles = ['admin', 'staff', 'cashier', 'warehouse', 'customer'];

// Middleware bảo vệ route
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(...);
Route::middleware(['auth', 'role:admin|staff'])->group(...);
```

### 4.3 — Middleware

```bash
php artisan make:middleware EnsureUserIsAdmin
php artisan make:middleware CheckOrderOwnership
```

### 4.4 — Google Maps API (Nhúng Bản Đồ Footer)

> 🗺️ **Mục tiêu:** Hiển thị địa chỉ cửa hàng trực quan ở Footer bằng Google Maps Embed API  
> **Địa chỉ shop:** 93 Đ. Lê Cao Lãng, Phú Thạnh, Tân Phú, Hồ Chí Minh  
> **Link map:** https://maps.app.goo.gl/DhHLaTuGPkzn3s177

#### Cấu hình `.env`
```env
GOOGLE_MAPS_API_KEY=your_google_maps_api_key_here
SHOP_ADDRESS="93 Đ. Lê Cao Lãng, Phú Thạnh, Tân Phú, Hồ Chí Minh, Việt Nam"
SHOP_LAT=10.7578
SHOP_LNG=106.6245
SHOP_MAPS_URL=https://maps.app.goo.gl/DhHLaTuGPkzn3s177
```

#### Thêm vào `config/app.php` hoặc `config/shop.php`
```php
// config/shop.php — tạo mới file này
return [
    'name'      => env('SHOP_NAME', 'XDTHECOFFEEHOUSE'),
    'address'   => env('SHOP_ADDRESS', '93 Đ. Lê Cao Lãng, Phú Thạnh, Tân Phú, Hồ Chí Minh'),
    'lat'       => env('SHOP_LAT', '10.7578'),
    'lng'       => env('SHOP_LNG', '106.6245'),
    'maps_url'  => env('SHOP_MAPS_URL', 'https://maps.app.goo.gl/DhHLaTuGPkzn3s177'),
    'google_maps_key' => env('GOOGLE_MAPS_API_KEY'),
];
```

#### Blade Component: Footer Map
```bash
php artisan make:component ShopMap   # Component bản đồ footer
```

```blade
{{-- resources/views/components/shop-map.blade.php --}}
<div class="shop-map-wrapper">
    <h4>📍 Tìm Chúng Tôi</h4>
    <p>{{ config('shop.address') }}</p>

    {{-- Cách 1: Google Maps Embed API (cần API Key, có marker) --}}
    <iframe
        id="shop-google-map"
        class="shop-map-iframe"
        width="100%"
        height="280"
        style="border:0; border-radius: 12px;"
        loading="lazy"
        allowfullscreen
        referrerpolicy="no-referrer-when-downgrade"
        src="https://www.google.com/maps/embed/v1/place
             ?key={{ config('shop.google_maps_key') }}
             &q={{ urlencode(config('shop.address')) }}
             &zoom=16
             &language=vi">
    </iframe>

    {{-- Cách 2: Fallback nếu không có API Key (dùng iframe share từ Google Maps) --}}
    {{-- <iframe
        src="https://www.google.com/maps?q=93+Le+Cao+Lang+Phu+Thanh+Ho+Chi+Minh&hl=vi&z=16&output=embed"
        width="100%" height="280" style="border:0; border-radius:12px;"
        loading="lazy" allowfullscreen>
    </iframe> --}}

    <a href="{{ config('shop.maps_url') }}" target="_blank" rel="noopener noreferrer"
       class="btn-directions">
        🧭 Xem đường đi
    </a>
</div>
```

#### Sử dụng trong Footer
```blade
{{-- resources/views/components/footer.blade.php --}}
<footer class="site-footer">
    <div class="footer-grid">
        {{-- ... các cột khác ... --}}

        <div class="footer-col footer-map-col">
            <x-shop-map />
        </div>
    </div>
</footer>
```

#### Lấy API Key Google Maps (miễn phí đến $200/tháng)
```
1. Vào https://console.cloud.google.com/
2. Tạo project mới → APIs & Services → Enable APIs
3. Bật: "Maps Embed API" (miễn phí hoàn toàn, không tính credit)
4. Tạo API Key → Restrict key: HTTP referrers (chỉ domain của bạn)
5. Copy key vào .env → GOOGLE_MAPS_API_KEY=AIza...
```

> 💡 **Lưu ý:** `Maps Embed API` (iframe) **miễn phí không giới hạn**. Chỉ dùng key để bảo mật referrer, không tốn chi phí.

---

## 🟢 PHASE 5 — Các Module Chức Năng Chính

**Thời gian ước tính:** 4-5 ngày  
**Mục tiêu:** Chuyển toàn bộ Controller PHP thuần sang Laravel Controller

### 5.1 — Module Sản Phẩm

```bash
php artisan make:controller Shop/ProductController --resource
php artisan make:controller Shop/CategoryController
```

| Controller cũ | Controller mới | Cải tiến |
|--------------|----------------|----------|
| `SanPhamController.php` | `Shop/ProductController.php` | Thêm slug URL, Scout search |
| `SanPhamChiTietController.php` | `ProductController@show` | Gộp vào resource controller |

```php
// Tìm kiếm với Laravel Scout
Route::get('/san-pham', [ProductController::class, 'index'])->name('products.index');
// URL: /san-pham/ca-phe-capuccino thay vì ?id=1
Route::get('/san-pham/{product:slug}', [ProductController::class, 'show'])->name('products.show');
```

### 5.2 — Module Giỏ Hàng

```bash
composer require darryldecode/cart
php artisan make:controller Shop/CartController
```

```php
// Thay $_SESSION['giohang'] bằng Cart facade
Cart::add($product->id, $product->name, $quantity, $product->price);
Cart::update($rowId, ['qty' => $newQty]);
Cart::remove($rowId);
Cart::total();  // Tổng tiền
```

### 5.3 — Module Đặt Hàng & Thanh Toán COD

```bash
php artisan make:controller Shop/OrderController
php artisan make:controller Shop/PaymentController
php artisan make:request StoreOrderRequest    # Validation thay Validator.php
```

```php
// Form Request validation (thay validate thủ công)
class StoreOrderRequest extends FormRequest {
    public function rules(): array {
        return [
            'recipient_name' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'required|regex:/^[0-9]{10,11}$/',
        ];
    }
}
```

### 5.4 — Module Lịch Sử Đơn Hàng

```bash
php artisan make:controller Shop/OrderHistoryController
```

### 5.5 — Module Review Sản Phẩm

```bash
php artisan make:controller Shop/ReviewController
php artisan make:request StoreReviewRequest
```

### 5.6 — Module Thông Báo (Real-time với Livewire)

```bash
composer require livewire/livewire
php artisan make:livewire NotificationBell   # Thay ajax_thongbao.php
```

```php
// Livewire component tự động cập nhật số thông báo
// Thay thế polling AJAX thủ công
class NotificationBell extends Component {
    public function render() {
        return view('livewire.notification-bell', [
            'count' => auth()->user()->unreadNotifications->count()
        ]);
    }
}
```

### 5.7 — Module Profile & Avatar

```bash
php artisan make:controller Shop/ProfileController
# Dùng Spatie MediaLibrary thay upload thủ công
composer require spatie/laravel-medialibrary
```

---

## 🟢 PHASE 6 — Thanh Toán Nâng Cao (VNPay / VietQR / MoMo)

**Thời gian ước tính:** 3-4 ngày  
**Mục tiêu:** Nâng cấp hệ thống thanh toán hiện tại (VietQR + MoMo thủ công) lên tích hợp chuẩn

### 6.1 — Tổng quan phương thức thanh toán

| Phương thức | Hiện tại | Laravel mới | Package |
|------------|----------|-------------|---------|
| COD | ✅ Có | ✅ Giữ nguyên | Không cần |
| VietQR | ✅ Có (QR tĩnh) | ✅ Nâng cấp QR động | `vietqr/vietqr-php` hoặc API trực tiếp |
| MoMo | ✅ Có (webhook) | ✅ Tích hợp MoMo API v3 | Tích hợp thủ công |
| VNPay | ❌ Chưa có | ✅ Thêm mới | `vanhoangha/laravel-vnpay` |
| Webhook (Casso/SePay) | ✅ Có | ✅ Giữ + cải tiến | Laravel Queue |

### 6.2 — VNPay Integration (Mới)

```bash
composer require vanhoangha/laravel-vnpay
# hoặc tích hợp thủ công theo tài liệu VNPay
```

```php
// app/Services/VNPayService.php
class VNPayService {
    public function createPaymentUrl(Order $order): string {
        // Tạo URL thanh toán VNPay
        // Redirect khách hàng đến cổng VNPay
    }
    public function verifyReturn(array $vnpayData): bool {
        // Xác thực chữ ký từ VNPay callback
    }
}
```

### 6.3 — VietQR Nâng Cấp (QR Động)

```php
// app/Services/VietQRService.php
// Tích hợp API VietQR để tạo QR code động với số tiền cụ thể
// Thay vì QR tĩnh như hiện tại
class VietQRService {
    public function generateQR(Order $order): string {
        // Gọi API VietQR: https://api.vietqr.io/v2/generate
        // Trả về base64 QR image với đúng số tiền đơn hàng
    }
}
```

### 6.4 — MoMo Integration Nâng Cấp

```php
// app/Services/MoMoService.php
// Tích hợp MoMo Payment API v3 (nhận tiền online thực sự)
// Thay vì chỉ dùng webhook như hiện tại
class MoMoService {
    public function createPayment(Order $order): array {
        // Gọi MoMo API tạo link thanh toán
        // Khách scan QR hoặc click link → thanh toán trực tiếp
    }
    public function handleCallback(Request $request): void {
        // Xử lý IPN từ MoMo
    }
}
```

### 6.5 — Webhook Cải Tiến với Laravel Queue

```php
// app/Http/Controllers/WebhookController.php
// Thay file_put_contents('webhook_log.txt') bằng Laravel Log
// Thay xử lý đồng bộ bằng Queue Job

class WebhookController extends Controller {
    public function handleCasso(Request $request) {
        // Validate webhook signature
        // Dispatch job thay vì xử lý trực tiếp
        ProcessPaymentWebhook::dispatch($request->all());
        return response()->json(['status' => 'queued']);
    }
}

// app/Jobs/ProcessPaymentWebhook.php
class ProcessPaymentWebhook implements ShouldQueue {
    public function handle() {
        // Tìm đơn hàng theo mã XD{id}
        // Cập nhật payment_status
        // Gửi email xác nhận qua Notification
        // Log bằng Telescope thay webhook_log.txt
    }
}
```

### 6.6 — Payment Routes

```php
Route::prefix('thanh-toan')->middleware('auth')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('/cod', [PaymentController::class, 'processCOD'])->name('payment.cod');
    Route::post('/vnpay', [PaymentController::class, 'redirectVNPay'])->name('payment.vnpay');
    Route::get('/vnpay/return', [PaymentController::class, 'vnpayReturn'])->name('payment.vnpay.return');
    Route::post('/momo', [PaymentController::class, 'redirectMoMo'])->name('payment.momo');
    Route::get('/vietqr/{order}', [PaymentController::class, 'showVietQR'])->name('payment.vietqr');
    Route::get('/thanh-cong/{order}', [PaymentController::class, 'success'])->name('payment.success');
});

// Webhook (không cần auth)
Route::post('/webhook/casso', [WebhookController::class, 'handleCasso']);
Route::post('/webhook/momo', [WebhookController::class, 'handleMoMo']);
Route::post('/webhook/vnpay', [WebhookController::class, 'handleVNPay']);
```

---

## 🔵 PHASE 7 — CaféAI Chatbox (Tích Hợp Laravel)

**Thời gian ước tính:** 2-3 ngày  
**Mục tiêu:** Chuyển `api/chat.php` sang Laravel API Controller + Livewire

### 7.1 — Cấu trúc mới

```bash
php artisan make:controller Api/ChatController
php artisan make:livewire CafeAIChatbox      # Widget chat UI
php artisan make:model ChatLog -m
php artisan make:model ProductRequest -m
```

### 7.2 — Cải tiến so với bản cũ

| Tính năng | Hiện tại | Laravel mới |
|-----------|----------|-------------|
| API endpoint | `api/chat.php` | `POST /api/chat` (Laravel API) |
| Session chat | PHP session | Laravel session + DB |
| Claude API key | Lưu trong DB | Lưu trong `.env` + `config/services.php` |
| Weather API key | Lưu trong DB | Lưu trong `.env` |
| Cấu hình AI | Bảng `cafe_ai_config` | `spatie/laravel-settings` |
| Log chat | Bảng `chat_logs` | Giữ nguyên + Telescope |

```php
// config/services.php
'claude' => [
    'api_key' => env('CLAUDE_API_KEY'),
    'model' => env('CLAUDE_MODEL', 'claude-sonnet-4-20250514'),
],
'openweather' => [
    'api_key' => env('OPENWEATHER_API_KEY'),
    'city' => env('SHOP_CITY', 'Ho Chi Minh City'),
],
```

### 7.3 — Livewire Chat Component

```php
// app/Livewire/CafeAIChatbox.php
class CafeAIChatbox extends Component {
    public array $messages = [];
    public string $userInput = '';

    public function sendMessage() {
        // Gọi ChatService (thay vì gọi API trực tiếp)
        $response = app(ChatService::class)->handle($this->userInput, auth()->user());
        $this->messages[] = ['role' => 'assistant', 'content' => $response];
        $this->userInput = '';
    }
}
```

---

## 🔵 PHASE 8 — Admin Dashboard với Filament

**Thời gian ước tính:** 3-4 ngày  
**Mục tiêu:** Thay toàn bộ `Admin2/` folder bằng Filament PHP

### 8.1 — Cài đặt Filament

```bash
composer require filament/filament:"^3.0"
php artisan filament:install --panels
php artisan make:filament-user   # Tạo tài khoản admin
```

### 8.2 — Filament Resources (thay các file Admin2/)

```bash
# Thay hanghoa.php
php artisan make:filament-resource Product --generate

# Thay loaisanpham.php
php artisan make:filament-resource Category --generate

# Thay donhang.php
php artisan make:filament-resource Order --generate

# Thay khachhang.php
php artisan make:filament-resource User --generate

# Thay nhanvien.php
php artisan make:filament-resource Employee --generate
```

### 8.3 — Dashboard Widgets (thay dashboard.php + thongke.php)

```bash
php artisan make:filament-widget StatsOverview    # Doanh thu, đơn hàng, khách hàng
php artisan make:filament-widget RevenueChart     # Biểu đồ doanh thu (thay thongke.php)
php artisan make:filament-widget LatestOrders     # Đơn hàng mới nhất
php artisan make:filament-widget CafeAIDashboard  # Thay cafeai_dashboard.php
```

### 8.4 — Xuất báo cáo (thay export_stats.php)

```bash
composer require maatwebsite/excel
composer require barryvdh/laravel-dompdf

# Xuất Excel
php artisan make:export OrdersExport --model=Order

# Xuất PDF hóa đơn
php artisan make:controller Admin/InvoiceController
```

### 8.5 — Activity Log (thay log thủ công)

```bash
composer require spatie/laravel-activitylog
# Tự động log: ai tạo/sửa/xóa đơn hàng, sản phẩm
activity()->causedBy($user)->performedOn($order)->log('updated status');
```

---

## ⚪ PHASE 9 — Hoàn Thiện, Testing & Deploy

**Thời gian ước tính:** 2-3 ngày

### 9.1 — Dev Tools

```bash
# Debug & Monitoring
composer require laravel/telescope --dev
php artisan telescope:install
# Thay webhook_log.txt thủ công → xem log đẹp trên /telescope

# Code style
composer require laravel/pint --dev
./vendor/bin/pint   # Format code tự động

# Debug bar
composer require barryvdh/laravel-debugbar --dev
```

### 9.2 — Queue & Email

```bash
# Cài Laravel Horizon để quản lý Queue
composer require laravel/horizon
php artisan horizon:install

# Cấu hình .env
QUEUE_CONNECTION=database
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=app_password

# Tạo Notification thay EmailService.php
php artisan make:notification OrderConfirmed
php artisan make:notification OrderStatusUpdated
php artisan make:notification PaymentReceived
```

### 9.3 — Backup tự động

```bash
composer require spatie/laravel-backup
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
# Cấu hình backup hàng ngày vào storage/backups/
```

### 9.4 — Tìm kiếm sản phẩm nâng cao

```bash
composer require laravel/scout
# Dùng database driver (không cần Elasticsearch)
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
# SCOUT_DRIVER=database trong .env
```

### 9.5 — Checklist trước khi deploy

- [ ] `php artisan migrate:fresh --seed` chạy thành công
- [ ] Tất cả routes hoạt động (`php artisan route:list`)
- [ ] Login/Register/Socialite hoạt động
- [ ] Giỏ hàng → Đặt hàng → Thanh toán COD hoạt động
- [ ] VietQR QR code hiển thị đúng
- [ ] Webhook nhận tín hiệu và cập nhật đơn hàng
- [ ] Email xác nhận đơn hàng gửi được
- [ ] CaféAI chatbox phản hồi đúng
- [ ] Admin Filament: CRUD sản phẩm, đơn hàng
- [ ] Phân quyền: admin thấy tất cả, staff chỉ thấy đơn hàng
- [ ] `php artisan optimize` trước khi deploy

---

## 📅 Timeline Tổng Thể

```
Tuần 1:  Phase 1 (Khởi tạo) + Phase 2 (Database - ưu tiên GV)
Tuần 2:  Phase 3 (Blade UI) + Phase 4 (Auth + Socialite)
Tuần 3:  Phase 5 (Các module chức năng chính)
Tuần 4:  Phase 6 (Thanh toán VNPay/MoMo/VietQR)
Tuần 5:  Phase 7 (CaféAI) + Phase 8 (Filament Admin)
Tuần 6:  Phase 9 (Hoàn thiện, test, deploy)
```

---

## 🗂️ Mapping File Cũ → File Mới

| File PHP thuần cũ | File Laravel mới |
|-------------------|-----------------|
| `index.php` (router) | `routes/web.php` + `Core/Router.php` → xóa |
| `config.php` | `.env` + `config/database.php` |
| `Model/connect.php` | `config/database.php` + Eloquent |
| `Core/BaseController.php` | `app/Http/Controllers/Controller.php` |
| `Core/EmailService.php` | `app/Notifications/` + Laravel Mail |
| `Core/Validator.php` | `app/Http/Requests/` (Form Requests) |
| `Controller/LoginController.php` | `app/Http/Controllers/Auth/` (Breeze) |
| `Controller/CartController.php` | `app/Http/Controllers/Shop/CartController.php` |
| `Controller/OrderController.php` | `app/Http/Controllers/Shop/OrderController.php` |
| `Controller/PaymentController.php` | `app/Http/Controllers/Shop/PaymentController.php` |
| `Controller/WebhookController.php` | `app/Http/Controllers/WebhookController.php` + Queue Job |
| `Controller/ProfileController.php` | `app/Http/Controllers/Shop/ProfileController.php` |
| `api/chat.php` | `app/Http/Controllers/Api/ChatController.php` |
| `ajax_thongbao.php` | `app/Livewire/NotificationBell.php` |
| `Admin2/` (toàn bộ) | Filament Panel (`/admin`) |
| `Admin2/thongke.php` | Filament Widget + Laravel Charts |
| `Admin2/export_stats.php` | `app/Exports/` (Laravel Excel) |
| `View/header.php` | `resources/views/components/navbar.blade.php` |
| `View/footer.php` | `resources/views/components/footer.blade.php` |
| `Model/mycfshop.sql` | `database/migrations/` + `database/seeders/` |

---

## ⚡ Lưu Ý Quan Trọng

1. **Không xóa project PHP cũ** — giữ nguyên để tham khảo logic nghiệp vụ khi viết lại
2. **Database mới tên khác** — dùng `mycfshop_laravel` để không ảnh hưởng DB cũ đang chạy
3. **Ưu tiên Phase 2 trước** — giảng viên sẽ kiểm tra Migration/Seeder/Model trước tiên
4. **Commit từng Phase** — mỗi giai đoạn hoàn thành thì commit Git riêng để dễ rollback
5. **Giữ nguyên UI** — không cần thiết kế lại, chỉ chuyển HTML sang Blade template
6. **Test webhook locally** — dùng `ngrok` để test webhook thanh toán trên localhost

---

*Kế hoạch được tạo dựa trên phân tích codebase thực tế của dự án XDTHECOFFEEHOUSE*  
*Ngày tạo: 16/05/2026*
