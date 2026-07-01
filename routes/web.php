<?php

use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\PaymentController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\ProfileController;
use App\Http\Controllers\Shop\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes — XDTHECOFFEEHOUSE
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Sản phẩm
Route::prefix('san-pham')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{product:slug}', [ProductController::class, 'show'])->name('show');
});

// Danh mục
Route::get('/danh-muc/{category:slug}', [ProductController::class, 'byCategory'])->name('categories.show');

// Giỏ hàng (không cần đăng nhập để xem)
Route::prefix('gio-hang')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/them', [CartController::class, 'add'])->name('add');
    Route::patch('/cap-nhat/{rowId}', [CartController::class, 'update'])->name('update');
    Route::delete('/xoa/{rowId}', [CartController::class, 'remove'])->name('remove');
    // Hỗ trợ cả GET (href link) và DELETE (form) để xóa toàn bộ giỏ hàng
    Route::get('/xoa-tat-ca', [CartController::class, 'clear'])->name('clear');
    Route::delete('/xoa-tat-ca', [CartController::class, 'clear'])->name('clear.delete');
});

// Đặt hàng & Thanh toán (cần đăng nhập)
Route::middleware('auth')->group(function () {

    // Đặt hàng
    Route::prefix('dat-hang')->name('orders.')->group(function () {
        Route::get('/xac-nhan', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/tao', [OrderController::class, 'store'])->name('store');
        Route::get('/lich-su', [OrderController::class, 'history'])->name('history');
        Route::get('/check-updates', [OrderController::class, 'checkUpdates'])->name('check-updates');
        Route::get('/{order}', [OrderController::class, 'show'])
            ->middleware('App\Http\Middleware\CheckOrderOwnership')
            ->name('show');
        Route::get('/{order}/pdf', [OrderController::class, 'pdf'])
            ->middleware('App\Http\Middleware\CheckOrderOwnership')
            ->name('pdf');
        Route::patch('/{order}/huy', [OrderController::class, 'cancel'])
            ->middleware('App\Http\Middleware\CheckOrderOwnership')
            ->name('cancel');
    });

    // Thanh toán
    Route::prefix('thanh-toan')->name('payment.')->group(function () {

        Route::get('/{order}',                [PaymentController::class, 'index'])->name('index');
        Route::post('/cod/{order}',           [PaymentController::class, 'processCOD'])->name('cod');
        Route::post('/paypal/{order}',        [PaymentController::class, 'redirectPayPal'])->name('paypal');
        Route::post('/momo/{order}',          [PaymentController::class, 'redirectMoMo'])->name('momo');
        Route::get('/vietqr/{order}',         [PaymentController::class, 'showVietQR'])->name('vietqr');
        Route::get('/status/{order}',         [\App\Http\Controllers\Api\PaymentStatusController::class, 'check'])->name('status');
        Route::get('/thanh-cong/{order}',     [PaymentController::class, 'success'])->name('success');
    });

    // Đánh giá sản phẩm
    Route::post('/san-pham/{product}/danh-gia', [ReviewController::class, 'store'])->name('reviews.store');

    // Hồ sơ cá nhân
    Route::prefix('ho-so')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/chinh-sua', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/cap-nhat', [ProfileController::class, 'update'])->name('update');
        Route::post('/doi-mat-khau', [ProfileController::class, 'changePassword'])->name('password');
        Route::post('/upload-avatar', [ProfileController::class, 'uploadAvatar'])->name('avatar');
        Route::delete('/xoa-tai-khoan', [ProfileController::class, 'destroy'])->name('destroy');
    });
});

// ── Callback redirect từ VNPay/MoMo — đặt NGOÀI auth vì user redirect từ domain ngoài về ──
// Bảo mật do chữ ký (vnp_SecureHash / MoMo signature) được xác thực bên trong controller
Route::prefix('thanh-toan')->name('payment.')->group(function () {
    Route::get('/paypal/ket-qua', [PaymentController::class, 'paypalReturn'])->name('paypal.return');
    Route::get('/paypal/huy/{order}', [PaymentController::class, 'paypalCancel'])->name('paypal.cancel');
    Route::get('/momo/ket-qua',   [PaymentController::class, 'momoReturn'])->name('momo.return');
});

// Webhook thanh toán (không cần auth, không cần CSRF)
Route::prefix('webhook')->name('webhook.')->group(function () {
    Route::post('/casso', [\App\Http\Controllers\WebhookController::class, 'handleCasso'])->name('casso');
    Route::post('/momo', [\App\Http\Controllers\WebhookController::class, 'handleMoMo'])->name('momo');
    Route::post('/paypal', [\App\Http\Controllers\WebhookController::class, 'handlePayPal'])->name('paypal');
});

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['App\Http\Middleware\AdminMiddleware'])
    ->group(function () {

        // Logout
        Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Hồ sơ cá nhân Admin (mọi role trong admin panel đều được vào)
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('edit');
            Route::patch('/update', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('update');
            Route::post('/password', [\App\Http\Controllers\Admin\ProfileController::class, 'changePassword'])->name('password');
            Route::post('/avatar', [\App\Http\Controllers\Admin\ProfileController::class, 'uploadAvatar'])->name('avatar');
        });

        // Khách hàng -> chỉ admin
        Route::middleware('role:admin')->group(function() {
            Route::get('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
            Route::get('/customers/{user}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');
            Route::delete('/customers/{user}', [\App\Http\Controllers\Admin\CustomerController::class, 'destroy'])->name('customers.destroy');
        });

        // Nhân viên -> chỉ admin
        Route::middleware('role:admin')->group(function() {
            Route::get('/employees', [\App\Http\Controllers\Admin\EmployeeController::class, 'index'])->name('employees.index');
            Route::get('/employees/create', [\App\Http\Controllers\Admin\EmployeeController::class, 'create'])->name('employees.create');
            Route::post('/employees', [\App\Http\Controllers\Admin\EmployeeController::class, 'store'])->name('employees.store');
            Route::get('/employees/{user}', [\App\Http\Controllers\Admin\EmployeeController::class, 'show'])->name('employees.show');
            Route::patch('/employees/{user}/role', [\App\Http\Controllers\Admin\EmployeeController::class, 'updateRole'])->name('employees.role');
            Route::delete('/employees/{user}', [\App\Http\Controllers\Admin\EmployeeController::class, 'destroy'])->name('employees.destroy');
        });

        // Email Templates -> chỉ admin
        Route::middleware('role:admin')->group(function() {
            Route::prefix('email-templates')->name('email-templates.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'index'])->name('index');
                Route::get('/{emailTemplate}/preview', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'preview'])->name('preview');
                Route::get('/{emailTemplate}/edit', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'edit'])->name('edit');
                Route::patch('/{emailTemplate}', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'update'])->name('update');
            });
        });

        // Thống kê -> admin và cashier
        Route::middleware('role:admin|cashier')->group(function() {
            Route::get('/statistics', [\App\Http\Controllers\Admin\StatisticsController::class, 'index'])->name('statistics.index');
            Route::get('/statistics/export', [\App\Http\Controllers\Admin\StatisticsController::class, 'export'])->name('statistics.export');
        });

        // Danh mục (Categories)
        // Xem danh mục: admin, cashier, staff, warehouse
        Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
        // Quản lý danh mục: chỉ admin
        Route::middleware('role:admin')->group(function() {
            Route::get('/categories/create', [\App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
            Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
            Route::get('/categories/{category}/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
            Route::put('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
            Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
        });

        // Sản phẩm (Products)
        // Xem sản phẩm: admin, cashier, staff, warehouse
        Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
        // Tạo, xóa, khôi phục sản phẩm: chỉ admin
        Route::middleware('role:admin')->group(function() {
            Route::get('/products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
            Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
            Route::delete('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');
            Route::post('/products/{id}/restore', [\App\Http\Controllers\Admin\ProductController::class, 'restore'])->name('products.restore');
        });
        // Chỉnh sửa sản phẩm: admin và warehouse (nhân viên kho chỉ được sửa stock trong controller)
        Route::middleware('role:admin|warehouse')->group(function() {
            Route::get('/products/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
            Route::patch('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update.patch');
        });

        // Đơn hàng (Orders)
        // Xem đơn hàng: admin, cashier, staff
        Route::middleware('role:admin|cashier|staff')->group(function() {
            Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        });
        // Xử lý đơn hàng, thanh toán: admin, cashier
        Route::middleware('role:admin|cashier')->group(function() {
            Route::patch('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
            Route::patch('/orders/{order}/payment-status', [\App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.payment-status');
        });
        // Cập nhật trạng thái pha chế: admin, staff
        Route::middleware('role:admin|staff')->group(function() {
            Route::patch('/orders/{order}/drink-status', [\App\Http\Controllers\Admin\DrinkStatusController::class, 'update'])->name('orders.drink-status.update');
        });
    });

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
