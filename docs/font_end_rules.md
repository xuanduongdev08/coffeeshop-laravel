# 🎨 Nguyên Tắc Thiết Kế Front-End — XDTHECOFFEEHOUSE

> **Mục đích:** Tài liệu tổng hợp toàn bộ nguyên tắc thiết kế giao diện của website XDTHECOFFEEHOUSE.  
> Bao gồm: bảng màu, typography, CSS architecture, responsive design, animations, component patterns.

---

## 📋 Mục Lục

1. [Bảng Màu (Color Palette)](#-bảng-màu-color-palette)
2. [Typography (Phông chữ)](#-typography-phông-chữ)
3. [Kiến Trúc CSS](#-kiến-trúc-css)
4. [Hệ Thống Layout](#-hệ-thống-layout)
5. [Component UI Patterns](#-component-ui-patterns)
6. [Hiệu Ứng & Animations](#-hiệu-ứng--animations)
7. [Responsive Design](#-responsive-design)
8. [Trang Admin](#-trang-admin)
9. [CaféAI Chatbox](#-caféai-chatbox)
10. [Thư Viện Bên Ngoài](#-thư-viện-bên-ngoài)
11. [Quy Tắc Viết CSS](#-quy-tắc-viết-css)

---

## 🎨 Bảng Màu (Color Palette)

### Màu chính (Primary Colors)

| Tên | Mã HEX | Sử dụng |
|-----|---------|---------|
| **Coffee Gold** | `#c49b63` | Màu chủ đạo — buttons, links, borders, badges, focus state |
| **Coffee Brown** | `#6f4e37` | Màu phụ — navbar, footer, pagination text, back-to-top |
| **Dark Mocha** | `#4E342E` | Badge "Ngưng kinh doanh", dark accents |
| **Coffee Dark** | `#8b5a2b` | CaféAI primary, gradient starts |
| **Coffee Light** | `#8b6f47` | Admin sidebar hover, lighter brown |

### Màu nền (Background Colors)

| Tên | Mã HEX | Sử dụng |
|-----|---------|---------|
| **Pure Dark** | `#0b0b0b` | Profile page background |
| **Dark Coffee Matte** | `rgba(26,26,26,0.95)` | Profile card (glassmorphism) |
| **Admin BG** | `#f4f6f9` | Admin panel background |
| **Café BG** | `#f5f0eb` | CaféAI chat background |
| **Café Cream** | `#fdf6ec` | CaféAI accent background |
| **White** | `#ffffff` | Card backgrounds, form inputs |

### Màu trạng thái (Status Colors)

| Trạng thái | Mã HEX | CSS Class |
|------------|---------|-----------|
| Warning (Chờ xử lý) | `#fff3cd` bg / `#856404` text | `.badge-pending` |
| Success (Hoàn thành) | `#d1e7dd` bg / `#0f5132` text | `.badge-paid`, `.badge-completed` |
| Danger (Đã hủy) | `#f8d7da` bg / `#842029` text | `.badge-failed` |
| Info (Đang pha) | `#cff4fc` bg / `#055160` text | `.badge-brewing` |
| Error Red | `#e74c3c` | Notification badge, sidebar badge |
| Facebook Blue | `#1877F2` | Social login button |
| Google Red | Bootstrap `.btn-outline-danger` | Social login button |

### Màu text

| Mục đích | Mã HEX |
|----------|---------|
| Body text (dark) | `#000000` |
| Text trên dark bg | `#ffffff` |
| Text muted (light bg) | `#6c757d` |
| Text muted (dark bg) | `rgba(255,255,255,0.5)` |
| Placeholder | `#6c757d` |
| CaféAI text | `#3a2d1f` |
| CaféAI text light | `#7a6b5d` |

### CSS Variables (CaféAI)

```css
:root {
    --cafe-primary: #8b5a2b;
    --cafe-primary-light: #c49b63;
    --cafe-primary-dark: #6f4e37;
    --cafe-accent: #c49b63;
    --cafe-accent-light: #d4b483;
    --cafe-cream: #fdf6ec;
    --cafe-text: #3a2d1f;
    --cafe-text-light: #7a6b5d;
    --cafe-border: #e8e0d5;
    --cafe-success: #2e7d32;
    --cafe-bg: #f5f0eb;
    --cafe-shadow: 0 10px 30px rgba(139, 90, 43, 0.2);
    --cafe-shadow-lg: 0 20px 50px rgba(0, 0, 0, 0.15);
}
```

### CSS Variables (Admin)

```css
:root {
    --coffee: #6f4e37;
    --coffee-light: #8b6f47;
    --coffee-pale: #c49b63;
    --sidebar-w: 260px;
}
```

---

## 🔤 Typography (Phông chữ)

### Google Fonts được sử dụng

| Font | Weight | Sử dụng |
|------|--------|---------|
| **Poppins** | 300–700 | Font chính cho nội dung website |
| **Josefin Sans** | 400, 700 | Tiêu đề, heading decorative |
| **Great Vibes** | — | Script font cho các yếu tố trang trí (subheading) |
| **Be Vietnam Pro** | 400–700 | CaféAI chatbox, font tiếng Việt |
| **Figtree** | — | Tailwind default (Breeze auth pages) |
| **Segoe UI** | — | Admin panel system font |

### Quy tắc Typography

```
- Heading (h1–h3): Font-weight 700, color phù hợp với context
- Subheading: Great Vibes hoặc text-uppercase + letter-spacing
- Body text: 14–16px, line-height 1.5–1.6
- Product name: 18px, -webkit-line-clamp: 2 (max 2 dòng)
- Product description: 14px, -webkit-line-clamp: 3 (max 3 dòng)
- Price: Font-weight bold, color #c49b63
- Admin: 13–14px cho table, 16px cho heading
```

### Định dạng giá tiền

```
Format: number_format($price, 0, ',', '.') + 'đ'
Ví dụ: 45.000đ, 120.000đ
```

---

## 📁 Kiến Trúc CSS

### Cấu trúc file CSS

```
css/
├── style.css              ← CSS chính (theme template gốc ~250KB) — KHÔNG CHỈNH SỬA
├── style_custom.css       ← CSS tùy chỉnh cho project (~19KB) — CHỈNH SỬA CHÍNH
├── responsive.css         ← Responsive breakpoints (~17KB)
├── cafeai.css             ← CaféAI chatbox styles (~12KB)
├── review.css             ← Đánh giá sản phẩm styles (~10KB)
├── animate.css            ← Animation library
├── aos.css                ← Animate On Scroll library
├── bootstrap.min.css      ← Bootstrap 4.x
├── owl.carousel.min.css   ← Carousel plugin
├── magnific-popup.css     ← Popup/lightbox plugin
├── ionicons.min.css       ← Icon font
├── icomoon.css            ← Icon font
├── flaticon.css           ← Icon font
└── open-iconic-bootstrap.min.css ← Icon font
```

### Thứ tự load CSS (shop layout)

```html
1. Google Fonts (Poppins, Josefin Sans, Great Vibes, Be Vietnam Pro)
2. open-iconic-bootstrap.min.css
3. animate.css
4. owl.carousel.min.css + owl.theme.default.min.css
5. magnific-popup.css
6. aos.css
7. ionicons.min.css
8. bootstrap-datepicker.css + jquery.timepicker.css
9. flaticon.css + icomoon.css
10. style.css (theme chính)
11. style_custom.css?v={{ time() }}     ← cache-busting
12. responsive.css?v={{ time() }}       ← cache-busting
13. cafeai.css?v={{ time() }}           ← cache-busting
14. SweetAlert2 (CDN)
15. Cropper.js CSS (CDN)
16. @stack('styles')                    ← page-specific styles
```

### File CSS quan trọng cần chỉnh sửa

| File | Mục đích |
|------|----------|
| `css/style_custom.css` | **Chỉnh sửa chính** — Tất cả custom styles nằm ở đây |
| `css/responsive.css` | Responsive breakpoints và mobile optimization |
| `css/cafeai.css` | Styles riêng cho chatbot CaféAI |
| `css/review.css` | Styles cho phần đánh giá sản phẩm |

> ⚠️ **KHÔNG chỉnh sửa** `css/style.css` — đây là theme template gốc, rất lớn (~250KB).  
> Mọi override phải nằm trong `style_custom.css`.

---

## 📐 Hệ Thống Layout

### Layout Blade Templates

| Layout | File | Sử dụng |
|--------|------|---------|
| **Shop** | `layouts/shop.blade.php` | Tất cả trang khách hàng (home, products, cart, orders, profile) |
| **Admin** | `layouts/admin.blade.php` | Trang quản trị (dashboard, products, orders, users, statistics) |
| **App** | `layouts/app.blade.php` | Layout mặc định Breeze (profile mặc định, dashboard) |
| **Guest** | `layouts/guest.blade.php` | Trang auth (login, register, forgot-password) |

### Cấu trúc Shop Layout

```
┌─────────────────────────────────────┐
│         @include('components.navbar')│  ← Navbar sticky on scroll
├─────────────────────────────────────┤
│         Flash Messages (SweetAlert2) │  ← success/error/warning
├─────────────────────────────────────┤
│         @yield('content')            │  ← Page content
├─────────────────────────────────────┤
│         @include('components.footer')│  ← Footer
├─────────────────────────────────────┤
│         JS Assets                    │  ← jQuery + plugins
│         Global Add-to-Cart AJAX      │
│         @stack('scripts')            │
└─────────────────────────────────────┘
```

### Cấu trúc Admin Layout

```
┌──────────┬──────────────────────────┐
│          │      Admin Topbar        │  ← Sticky, user info + role badge
│ Sidebar  ├──────────────────────────┤
│ (260px)  │      Flash Messages      │
│ Fixed    ├──────────────────────────┤
│          │      @yield('content')   │
│          │                          │
└──────────┴──────────────────────────┘
```

### Grid System

- **Bootstrap 4** grid (12 columns)
- Product grid: `col-md-3` (4 sản phẩm/hàng trên desktop)
- Responsive: `col-md-3` → `col-sm-6` → `col-12` (mobile)
- Container max-widths: 540px / 720px / 960px / 1140px

---

## 🧩 Component UI Patterns

### 1. Navbar (`components/navbar.blade.php`)

- Class: `.ftco_navbar .navbar-dark .bg-dark .ftco-navbar-light`
- Sticky on scroll (JS adds `.scrolled` class at 150px)
- Z-index: `2000` (cao nhất)
- Bao gồm: Logo, Menu, Category dropdown, Notification bell (Livewire), User dropdown, Cart badge

### 2. Product Card (`.menu-entry`)

```css
.menu-entry {
    display: flex;
    flex-direction: column;
    width: 100%;
    margin-bottom: 40px;
}
/* Product name: max 2 dòng, min-height: 3.2em */
/* Description: max 3 dòng, min-height: 4.5em */
/* Price + Button: margin-top: auto (đẩy xuống đáy) */
```

**Trạng thái sản phẩm:**
- **Hết hàng**: `.out-of-stock-img` (opacity 0.5, grayscale 100%)
- **Ngưng kinh doanh**: `.suspended-product` (sepia filter, strikethrough text)
- **Có giảm giá**: Badge `position: absolute` trên ảnh

### 3. Hero Page Header (`.hero-page-header`)

- Dùng cho các trang con (sản phẩm, giỏ hàng, đơn hàng...)
- Background image + overlay `rgba(0,0,0,0.55)`
- Breadcrumbs với link màu `#c49b63`

**Typography của Hero Header:**

```css
/* Tiêu đề trang (h1.bread) */
.hero-page-header .bread {
    color: #fff;
    font-size: 40px;
    font-weight: 700;
    text-transform: uppercase;   /* In hoa toàn bộ */
    letter-spacing: 2px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.4);
}

/* Breadcrumb text (p.breadcrumbs) */
.hero-page-header .breadcrumbs {
    color: #f0e6d3;              /* Kem sáng — dễ đọc trên nền tối */
    font-size: 17px;             /* Tăng từ 14px lên 17px */
    font-weight: 500;
    letter-spacing: 0.5px;
    text-shadow: 0 1px 6px rgba(0,0,0,0.5);
}
```

> **Quy tắc:** `.bread` luôn in hoa (`text-transform: uppercase`) + `letter-spacing: 2px` để nổi bật.  
> `.breadcrumbs` dùng màu `#f0e6d3` (kem sáng) thay vì `rgba(255,255,255,0.8)` để tương phản tốt hơn trên overlay tối.

### 4. Profile Card (Glassmorphism)

```css
.profile-card {
    border: 1px solid rgba(196, 155, 99, 0.2);
    border-radius: 25px;
    background: rgba(26, 26, 26, 0.95);  /* Dark Coffee matte */
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(10px);
}
```

### 5. Pagination (Coffee Theme)

```css
.pagination .page-item.active .page-link {
    background: #c49b63;    /* Gold */
    border-color: #c49b63;
    color: #fff;
}
.pagination .page-item .page-link {
    color: #6f4e37;         /* Coffee brown */
    border-radius: 8px;
}
```

### 6. Category Cards (Dark Theme — Trang chủ)

```css
.category-card-dark {
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(196, 155, 99, 0.2);
    backdrop-filter: blur(6px);
}
```

### 7. Buttons

| Button | Style |
|--------|-------|
| Primary (Gold) | `background: #c49b63; color: #000 hoặc #fff` |
| Coffee | `background: #6f4e37; color: #fff` (Admin) |
| Edit | `border: 1px solid #c49b63; color: #c49b63` (outline) |
| Hover chung | `transition: all 0.3s ease` |
| Back-to-top | `background: #6f4e37; border-radius: 50%; bottom-left` |

### 8. Flash Messages

- Sử dụng **SweetAlert2** cho shop layout
- Sử dụng **Bootstrap Alerts** cho admin layout (auto-dismiss 4s)
- `confirmButtonColor: '#c49b63'` — luôn dùng màu Gold cho confirm button

### 9. Notification Bell (Livewire)

- Component: `<livewire:order-status-bell />` — polling mỗi 5 giây
- Vị trí: trong navbar, bên trái user dropdown
- Badge: `.notif-badge` — `background: #e74c3c`, animation pulse
- Dropdown: `width: 360px`, `border-radius: 12px`, gradient header
- Hiển thị đơn hàng đang ở trạng thái `pending` hoặc `brewing`

### 10. Form Inputs

```css
/* Light theme (Auth pages) */
body .ftco-section .card .form-control {
    background-color: #ffffff;
    color: #000000;
    border: 1px solid #ced4da;
}
body .ftco-section .card .form-control:focus {
    border-color: #c49b63;
    box-shadow: 0 0 0 0.2rem rgba(196, 155, 99, 0.25);
}

/* Dark theme (Profile page) */
.profile-input {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(196, 155, 99, 0.5);
    border-radius: 12px;
}
```

### 11. Modifier Selector (Product Detail)

- Hiển thị khi sản phẩm có `has_size`, `allow_sugar`, `allow_ice`, `allow_milk`, `has_topping`
- Size selector: Radio buttons M / L / XL — cập nhật giá realtime qua JS
- Modifier groups: Sugar, Ice, Milk, Topping — pill-shaped buttons
- Giá tự động cộng `modifier_extra` vào `unit_price` khi chọn

---

## ✨ Hiệu Ứng & Animations

### 1. Product Image Hover (Premium)

```css
/* Ảnh nổi lên + shadow */
.menu-entry:hover .img {
    transform: translateY(-12px) scale(1.01);
    box-shadow: 0 20px 40px rgba(111, 78, 55, 0.3);
}

/* Vệt sáng vụt qua (Golden Glow Sweep) */
.menu-entry .img::after {
    background: linear-gradient(115deg, ... golden glow ...);
    filter: blur(15px);
}
.menu-entry:hover .img::after {
    left: 150%;  /* Sweep from left to right */
    transition: left 1.2s cubic-bezier(0.19, 1, 0.22, 1);
}
```

### 2. AOS (Animate On Scroll)

```javascript
AOS.init({ duration: 800, easing: 'slide' });
```
- Sử dụng class `.ftco-animate` + waypoints cho scroll animations
- Effects: `fadeIn`, `fadeInLeft`, `fadeInRight`, `fadeInUp`

### 3. Owl Carousel (Home Slider)

```javascript
$('.home-slider').owlCarousel({
    loop: true,
    autoplay: true,
    animateOut: 'fadeOut',
    animateIn: 'fadeIn',
    nav: false,
    items: 1
});
```

### 4. Scroll Effects

- **Navbar sticky**: Adds `.scrolled` class at 150px, `.awake` at 350px
- **Back-to-top**: Appears at 300px scroll, `transition: all 0.3s ease`
- **Parallax**: jQuery Stellar plugin for parallax backgrounds

### 5. Category Card Hover

```css
.category-card-dark:hover {
    background: rgba(196, 155, 99, 0.15);
    border-color: #c49b63;
    transform: translateY(-8px);
    box-shadow: 0 16px 40px rgba(196, 155, 99, 0.25);
}
```

### 6. CaféAI Animations

- Toggle button: `scale(1.05)` on hover, `scale(0.95)` on active
- Chat container: `scale(0.95) → scale(1)`, `opacity 0 → 1`
- Message bubbles: `cafeai-slide-fade` (translateY + opacity)
- Typing indicator: Bouncing dots animation
- Status dot: `cafeai-pulse-dot` (opacity pulse)

### 7. Easing Functions thường dùng

```
- cubic-bezier(0.165, 0.84, 0.44, 1)   → Smooth deceleration
- cubic-bezier(0.4, 0, 0.2, 1)         → Material Design standard
- cubic-bezier(0.19, 1, 0.22, 1)       → Dramatic ease-out
- ease-in-out                           → General purpose
```

---

## 📱 Responsive Design

### Breakpoints

| Breakpoint | Kích thước | Thiết bị |
|-----------|-----------|----------|
| Mobile (base) | `320px – 575px` | Điện thoại |
| iPhone 14 Pro Max | `≤ 430px` | Specific fix |
| Small devices | `576px – 767px` | Điện thoại lớn |
| Tablets | `768px – 991px` | Máy tính bảng |
| Desktops | `992px – 1199px` | Laptop |
| Large desktops | `≥ 1200px` | Màn hình lớn |

### Mobile-First Approach

```css
/* Base styles (mobile) */
.col-md-3.mb-4 {
    flex: 0 0 100%;     /* 1 cột trên mobile */
}

/* Tablet (≥576px) */
@media (min-width: 576px) {
    .ftco-section .col-md-3 {
        flex: 0 0 50%;   /* 2 cột */
    }
}

/* Desktop (≥992px) */
@media (min-width: 992px) {
    .ftco-section .col-md-3 {
        flex: 0 0 25%;   /* 4 cột */
    }
}
```

### Product Grid Responsive

| Viewport | Số cột | Image height |
|----------|--------|-------------|
| Mobile | 1 | 250px |
| Small | 2 | 280px |
| Tablet | 3 | 300px |
| Desktop | 4 | 320px |
| Large | 4 | 350px |

### Cart Table Mobile

- Mobile: `thead` ẩn, mỗi `<tr>` thành card riêng (`display: block`)
- Tablet+: Reset về table bình thường (`display: table`)

### Touch Optimization

```css
@media (max-width: 991px) {
    a, button, .btn, .nav-link {
        min-height: 44px;   /* Apple HIG touch target */
        min-width: 44px;
    }
}
```

### Performance Optimization

```css
.home-slider, .menu-entry .img {
    will-change: transform;
    transform: translateZ(0);              /* GPU acceleration */
    -webkit-backface-visibility: hidden;
}
html { scroll-behavior: smooth; }
```

### Admin Responsive

- Sidebar: `transform: translateX(-100%)` khi `≤768px`
- Toggle button hiện ra trên mobile
- Main content: `margin-left: 0` khi sidebar ẩn

---

## 🔧 Trang Admin

### Design System

- **Sidebar**: Fixed left, width 260px, gradient `#2c1810 → #6f4e37`
- **Topbar**: Sticky, white background, user avatar + role badge
- **Stat Cards**: White, `border-radius: 12px`, `border-left: 4px solid #c49b63`
- **Tables**: Header `background: #6f4e37`, hover row `#fdfaf7`
- **Cards**: `border-radius: 12px`, heading color `#6f4e37`

### Admin Components

| Component | Style |
|-----------|-------|
| `.stat-card` | White card, coffee left border, large number |
| `.admin-table` | Rounded, coffee header, zebra hover |
| `.admin-card` | White card, coffee heading with bottom border |
| `.btn-coffee` | `background: #6f4e37; color: #fff` |
| `.sidebar-link.active` | `border-left: 3px solid #c49b63` |
| `.sidebar-badge` | `background: #e74c3c` (đếm đơn chờ xử lý) |

### Drink Status Badges (Admin)

| Status | Badge Style |
|--------|-------------|
| `pending` | `.badge-pending` — vàng nhạt |
| `brewing` | `.badge-brewing` — xanh info |
| `completed` | `.badge-completed` — xanh success |

---

## 🤖 CaféAI Chatbox

### Vị trí & Kích thước

- Toggle button: `bottom: 100px; right: 24px; 60×60px`
- Chat container: `bottom: 165px; right: 24px; 380×600px`
- Z-index: toggle `9999`, container `9998`

### Design

- **Header**: Gradient `#8b5a2b → #c49b63`, avatar + status dot
- **User bubble**: `background: #c49b63; color: white; border-radius: 16px 16px 4px 16px`
- **Bot bubble**: `background: white; border: 1px solid #f3f4f6; border-radius: 16px 16px 16px 4px`
- **Input**: Rounded box, focus ring `rgba(196, 155, 99, 0.1)`
- **Send button**: Gradient `#9c6f44 → #c49b63`, circular
- **Product cards**: `165px` wide, image hover zoom `scale(1.1)`
- **Quick menu**: Horizontal scroll, pill-shaped buttons

---

## 📦 Thư Viện Bên Ngoài

### CSS Libraries

| Thư viện | Phiên bản | Mục đích |
|----------|----------|----------|
| Bootstrap | 4.x | Grid, components, utilities |
| Animate.css | — | CSS animations library |
| AOS | — | Animate On Scroll |
| Owl Carousel | — | Image carousel/slider |
| Magnific Popup | — | Image lightbox/popup |
| Ionicons | — | Icon font |
| Icomoon | — | Icon font |
| Flaticon | — | Icon font |
| Open Iconic | — | Icon font (Bootstrap) |
| Cropper.js | 1.5.13 | Crop avatar (CDN) |

### JS Libraries

| Thư viện | Mục đích |
|----------|----------|
| jQuery | 3.2.1+ | DOM manipulation |
| jQuery Migrate | 3.0.1 | Backward compatibility |
| Popper.js | — | Tooltip/dropdown positioning |
| Bootstrap JS | 4.x | Components interactivity |
| jQuery Waypoints | — | Scroll event triggers |
| jQuery Stellar | — | Parallax scrolling |
| jQuery animateNumber | — | Counter animations |
| Scrollax | — | Scroll animations |
| Owl Carousel JS | — | Carousel functionality |
| Magnific Popup JS | — | Lightbox functionality |
| SweetAlert2 | 11.x (CDN) | Beautiful alert dialogs |
| Chart.js | 4.4.0 (CDN) | Admin statistics charts |
| AlpineJS | 3.4.2 (npm) | Reactive components (Breeze) |

### Build Tools

| Tool | Config |
|------|--------|
| Vite | `vite.config.js` — Laravel Vite Plugin |
| Tailwind CSS | 3.1 — Chỉ dùng cho Breeze auth pages |
| PostCSS | `postcss.config.js` |

> ⚠️ **Lưu ý:** Tailwind CSS chỉ được dùng cho các trang auth của Breeze (`resources/css/app.css`).  
> Phần shop và admin sử dụng **Bootstrap 4 + Custom CSS thuần**.

---

## ✏️ Quy Tắc Viết CSS

### 1. Tổ chức code

```css
/* 
================================================================
TÊN SECTION — MÔ TẢ NGẮN
================================================================
*/
```

### 2. Specificity (Độ ưu tiên)

- Dùng `body .parent .child` để tăng specificity khi cần override theme
- Sử dụng `!important` chỉ khi thật sự cần thiết (override Bootstrap/theme)
- Ưu tiên class-based selectors, tránh ID selectors

### 3. Naming Convention

```
.page-section        → Section wrapper (ví dụ: .profile-section)
.component-element   → Component parts (ví dụ: .profile-card, .profile-input)
.state-modifier      → States (ví dụ: .out-of-stock-img, .suspended-product)
.btn-purpose         → Buttons (ví dụ: .btn-edit-custom, .btn-coffee)
```

### 4. Cache Busting

```html
<!-- File thường xuyên chỉnh sửa: thêm ?v={{ time() }} -->
<link rel="stylesheet" href="{{ asset('css/style_custom.css') }}?v={{ time() }}">

<!-- File ít thay đổi: không cần cache bust -->
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
```

### 5. Transition Standards

```css
/* Standard transition */
transition: all 0.3s ease;

/* Premium transition */
transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);

/* Quick feedback */
transition: all 0.2s;
```

### 6. Z-index Scale

| Z-index | Sử dụng |
|---------|---------|
| `1` | Hero slider, owl-carousel |
| `2` | Hero content overlay |
| `3` | Product badges |
| `10` | Out-of-stock badge |
| `20` | Suspended badge, profile avatar |
| `100` | Admin topbar |
| `1000` | Admin sidebar |
| `1040` | Modal backdrop |
| `1050` | Modal |
| `2000` | Navbar |
| `9998` | CaféAI container |
| `9999` | CaféAI toggle, Back-to-top |
| `10001` | CaféAI toast |

### 7. Box Shadow Standards

```css
/* Light shadow */
box-shadow: 0 2px 5px rgba(0,0,0,0.05);

/* Medium shadow */
box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);

/* Heavy shadow */
box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);

/* Gold glow */
box-shadow: 0 0 30px rgba(196, 155, 99, 0.2);

/* Coffee hover glow */
box-shadow: 0 16px 40px rgba(196, 155, 99, 0.25);
```

### 8. Border Radius Standards

```css
/* Small */    border-radius: 4px–5px;    /* Form inputs, small badges */
/* Medium */   border-radius: 8px–12px;   /* Cards, pagination, buttons */
/* Large */    border-radius: 16px–25px;  /* Category cards, profile cards */
/* Circle */   border-radius: 50%;        /* Avatars, back-to-top, toggle */
/* Pill */     border-radius: 9999px;     /* CaféAI chips, quick buttons */
```

---

## 📌 Quy Tắc Quan Trọng

1. **Màu `#c49b63` là kim chỉ nam** — Sử dụng cho mọi yếu tố tương tác chính (buttons, focus, links, badges)
2. **Không sửa `css/style.css`** — Override trong `css/style_custom.css`
3. **Mobile-first** — Viết CSS base cho mobile, dùng `@media (min-width:)` mở rộng
4. **SweetAlert2** — Luôn dùng `confirmButtonColor: '#c49b63'` cho shop
5. **Flash messages**: SweetAlert2 cho shop, Bootstrap Alert cho admin
6. **Cache busting** — Thêm `?v={{ time() }}` cho các file CSS/JS custom
7. **Glassmorphism** — Dùng cho profile page (dark bg + backdrop-filter + subtle borders)
8. **Hover effects** — Mỗi element tương tác phải có hover state với transition
9. **Livewire components** — `<livewire:order-status-bell />` chỉ render khi `@auth`
10. **Modifier UI** — Hiển thị size/modifier selector chỉ khi product có flag tương ứng
