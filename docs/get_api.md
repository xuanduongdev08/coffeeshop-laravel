# Hướng dẫn lấy API Key cho đăng nhập Google & Facebook

> Dự án đã tích hợp sẵn **Laravel Socialite** với đầy đủ controller, route và giao diện.  
> Bạn chỉ cần lấy Client ID / Secret từ Google và Facebook rồi điền vào `.env` là xong.

---

## 1. Google OAuth

### Bước 1 — Vào Google Cloud Console

Truy cập: **https://console.cloud.google.com/**

Đăng nhập bằng tài khoản Google của bạn.

---

### Bước 2 — Tạo Project mới (nếu chưa có)

1. Nhấn vào dropdown tên project ở góc trên bên trái → **"New Project"**
2. Đặt tên project (ví dụ: `CoffeeShop`) → **"Create"**
3. Chờ tạo xong rồi chọn project vừa tạo

---

### Bước 3 — Bật Google+ API / OAuth

1. Vào menu bên trái: **"APIs & Services"** → **"Library"**
2. Tìm kiếm `Google+ API` → Nhấn **"Enable"**  
   *(Hoặc tìm `Google Identity` nếu không thấy Google+)*

---

### Bước 4 — Cấu hình OAuth Consent Screen

1. Vào **"APIs & Services"** → **"OAuth consent screen"**
2. Chọn **"External"** → **"Create"**
3. Điền thông tin:
   - **App name**: `XDTHECOFFEEHOUSE`
   - **User support email**: email của bạn
   - **Developer contact information**: email của bạn
4. Nhấn **"Save and Continue"** qua các bước (Scopes, Test users) → **"Back to Dashboard"**

---

### Bước 5 — Tạo OAuth Client ID

1. Vào **"APIs & Services"** → **"Credentials"**
2. Nhấn **"+ Create Credentials"** → **"OAuth client ID"**
3. Chọn **Application type**: `Web application`
4. Đặt tên: `CoffeeShop Web`
5. Phần **"Authorized redirect URIs"** — nhấn **"Add URI"** và thêm:
   ```
   http://localhost/auth/google/callback
   ```
   > Nếu dùng Laragon với domain khác (ví dụ `coffeeshop.test`), thêm thêm:
   > `http://coffeeshop.test/auth/google/callback`
6. Nhấn **"Create"**

---

### Bước 6 — Copy Client ID và Client Secret

Sau khi tạo, một popup hiện ra với:
- **Your Client ID** → copy vào `GOOGLE_CLIENT_ID`
- **Your Client Secret** → copy vào `GOOGLE_CLIENT_SECRET`

---



## 4. Xóa cache config

Sau khi điền `.env`, chạy lệnh sau để Laravel nhận giá trị mới:

```bash
php artisan config:clear
php artisan cache:clear
```

---

## 5. Kiểm tra hoạt động

1. Mở trình duyệt, vào trang **Đăng nhập** (`/login`)
2. Nhấn nút **"Google"** 
3. Sau khi xác thực thành công, bạn sẽ được redirect về trang chủ
4. **Navbar sẽ hiển thị tên và avatar** lấy từ tài khoản Google

---

## Lưu ý khi deploy lên production

| Mục | Cần làm |
|-----|---------|
| Google Console | Thêm domain production vào **Authorized redirect URIs** |

| `.env` | Cập nhật `APP_URL`, `GOOGLE_REDIRECT_URI`,  |

---

## Cấu trúc đã có sẵn trong dự án

Dự án đã tích hợp đầy đủ, bạn **không cần code thêm gì**:

```
routes/auth.php                              ← Route /auth/google 
app/Http/Controllers/Auth/SocialiteController.php  ← Xử lý callback, tạo/cập nhật user
resources/views/auth/login.blade.php         ← Nút đăng nhập Google 
resources/views/components/navbar.blade.php  ← Hiển thị avatar + tên sau đăng nhập
```

Sau khi đăng nhập thành công, navbar tự động hiển thị:
- **Avatar** từ Google (hoặc ảnh đã upload nếu user cập nhật sau)
- **Tên** từ tài khoản Google
