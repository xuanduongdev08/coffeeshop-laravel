# 📌 Quy Tắc Chỉnh Sửa Code — XDTHECOFFEEHOUSE

> **Mục đích:** Tránh sửa nhầm file PHP thuần từ dự án cũ trong quá trình phát triển Laravel.  
> **Nguyên tắc:** Các file/folder cũ chỉ dùng để **tham khảo**, không chỉnh sửa.  
> Khi dự án Laravel hoàn thiện, mới xóa toàn bộ file/folder cũ.

---

## ✅ Chỉ Chỉnh Sửa — Folder Laravel

Mọi thay đổi giao diện, logic, cấu hình đều phải nằm trong các folder sau:

### Giao diện (Blade)
```
resources/views/
├── layouts/
│   ├── shop.blade.php       ← Layout chính website khách hàng
│   ├── admin.blade.php      ← Layout trang quản trị Laravel
│   ├── app.blade.php        ← Layout mặc định Breeze (profile, dashboard)
│   └── guest.blade.php      ← Layout trang auth (login, register)
│
├── shop/                    ← Tất cả trang khách hàng
│   ├── home.blade.php
│   ├── products/            ← index.blade.php, show.blade.php
│   ├── cart/                ← index.blade.php
│   ├── orders/              ← checkout, history, show
│   ├── payment/             ← index, success, vietqr
│   └── profile/             ← edit, show
│
├── admin/                   ← Trang quản trị Laravel
│   ├── dashboard.blade.php
│   ├── products/
│   ├── orders/
│   ├── categories/
│   ├── users/
│   └── statistics/
│
├── auth/                    ← Login, register, forgot-password...
├── components/              ← navbar, footer, dropdown, modal...
└── livewire/                ← order-status-bell
```

### Logic & Controller
```
app/
├── Http/Controllers/        ← Tất cả controller Laravel
├── Models/                  ← Eloquent Models
├── Services/                ← CartService, MoMoService, VNPayService...
├── Livewire/                ← Livewire components
├── Notifications/           ← DrinkStatusUpdated...
├── Observers/               ← OrderObserver...
└── Providers/               ← AppServiceProvider...
```

### Cấu hình & Routes
```
config/                      ← services.php, app.php...
routes/                      ← web.php, auth.php, api.php
database/                    ← migrations, seeders, factories
```

---

## ❌ Không Chỉnh Sửa — Folder PHP Thuần Cũ

Các file/folder dưới đây là **di sản từ dự án PHP thuần**, chỉ dùng để tham khảo logic cũ:

| Folder / File | Mô tả | Trạng thái |
|---------------|-------|------------|
| `Admin2/` | Toàn bộ admin PHP thuần cũ | 🔒 Chỉ đọc |
| `Admin2/cafeai_dashboard.php` | Dashboard CaféAI PHP thuần | 🔒 Chỉ đọc |
| `Admin2/Model/` | Model PHP thuần cũ | 🔒 Chỉ đọc |
| `Admin2/View/` | View PHP thuần cũ (header, footer) | 🔒 Chỉ đọc |
| `ajax_thongbao.php` | AJAX thông báo PHP thuần | 🔒 Chỉ đọc |

> ⚠️ **Ngoại lệ duy nhất:** `api/chat.php` — file này đang được dùng cho chatbox CaféAI  
> và đã được cập nhật để dùng Gemini API. Được phép chỉnh sửa khi cần thiết.

---

## 🔄 Quy Trình Khi Cần Thay Đổi Giao Diện

```
1. Xác định trang cần sửa
        ↓
2. Tìm file blade tương ứng trong resources/views/
        ↓
3. Kiểm tra layout đang dùng (@extends)
        ↓
4. Chỉnh sửa đúng file blade
        ↓
5. KHÔNG đụng vào Admin2/ hay file PHP thuần
```

### Ví dụ thực tế

| Muốn sửa | File cần mở | File KHÔNG được sửa |
|----------|-------------|---------------------|
| Trang chủ | `resources/views/shop/home.blade.php` | `Admin2/index.php` |
| Navbar | `resources/views/components/navbar.blade.php` | `Admin2/View/header.php` |
| Footer | `resources/views/components/footer.blade.php` | `Admin2/View/footer.php` |
| Trang sản phẩm | `resources/views/shop/products/index.blade.php` | `Admin2/hanghoa.php` |
| Trang đơn hàng | `resources/views/shop/orders/history.blade.php` | `Admin2/donhang.php` |
| Admin dashboard | `resources/views/admin/dashboard.blade.php` | `Admin2/dashboard.php` |
| Login / Register | `resources/views/auth/login.blade.php` | `Admin2/dangnhap.php` |

---

## 🗑️ Kế Hoạch Xóa File Cũ (Sau Khi Hoàn Thiện)

Khi dự án Laravel hoàn chỉnh và đã kiểm tra kỹ, xóa các folder/file sau:

```bash
# Xóa toàn bộ folder PHP thuần cũ
Remove-Item -Recurse -Force Admin2/
Remove-Item ajax_thongbao.php
```

> **Lưu ý:** `api/chat.php` sẽ được migrate vào `app/Http/Controllers/ChatController.php`  
> trước khi xóa, để chatbox CaféAI hoạt động hoàn toàn trong hệ thống Laravel.
