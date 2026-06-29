@extends('layouts.shop')

@section('title', 'Xác nhận đặt hàng')

@section('content')

<section class="hero-page-header" style="background-image: url({{ asset('images/bg_3.jpg') }}); background-size: cover; background-position: center; height: 350px;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-md-7 col-sm-12 text-center ftco-animate">
                <h1 class="mb-3 mt-5 bread">Xác nhận đặt hàng</h1>
                <p class="breadcrumbs">
                    <span class="mr-2"><a href="{{ route('home') }}">Trang chủ</a></span>
                    <span class="mr-2"><a href="{{ route('cart.index') }}">Giỏ hàng</a></span>
                    <span>Đặt hàng</span>
                </p>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row">
            {{-- Form thông tin giao hàng --}}
            <div class="col-md-7">
                <div class="shipping-card">
                    <div class="shipping-card-header">
                        <h3 class="shipping-card-title">Thông tin giao hàng</h3>
                        <p class="shipping-card-subtitle">Vui lòng điền đầy đủ thông tin để chúng tôi giao hàng chính xác</p>
                    </div>
                    <div class="shipping-card-body">

                        <form method="POST" action="{{ route('orders.store') }}" id="order-form">
                            @csrf
                            <input type="hidden" name="province_code" id="province-code-input" value="{{ old('province_code', '') }}">

                            {{-- Email --}}
                            <div class="shipping-form-group">
                                <label class="shipping-label">Email tài khoản</label>
                                <input type="email" class="shipping-input shipping-input-readonly"
                                    value="{{ $user->email }}" readonly>
                                <small class="shipping-hint">Email được lấy từ tài khoản của bạn.</small>
                            </div>

                            {{-- Họ và tên --}}
                            <div class="shipping-form-group">
                                <label class="shipping-label">Họ và tên người nhận <span class="req">*</span></label>
                                <input type="text" name="recipient_name"
                                    class="shipping-input @error('recipient_name') is-invalid @enderror"
                                    value="{{ old('recipient_name', $user->name) }}"
                                    placeholder="Nhập họ và tên người nhận" required>
                                @error('recipient_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Số điện thoại --}}
                            <div class="shipping-form-group">
                                <label class="shipping-label">Số điện thoại <span class="req">*</span></label>
                                <input type="text" name="phone"
                                    class="shipping-input @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->phone ?? '') }}"
                                    placeholder="Nhập số điện thoại (10-11 số)" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Địa chỉ: 2 cột --}}
                            <div class="shipping-address-grid">
                                {{-- Tỉnh / Thành phố --}}
                                <div class="shipping-form-group">
                                    <label class="shipping-label">Tỉnh / Thành phố <span class="req">*</span></label>
                                    <div class="shipping-select-wrapper">
                                        <select id="province-select" name="province"
                                            class="shipping-select @error('province') is-invalid @enderror" required>
                                            <option value="">-- Chọn Tỉnh / TP --</option>
                                        </select>
                                        <span class="shipping-select-arrow">▾</span>
                                    </div>
                                    @error('province')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Quận / Huyện --}}
                                <div class="shipping-form-group">
                                    <label class="shipping-label">Quận / Huyện <span class="req">*</span></label>
                                    <div class="shipping-select-wrapper">
                                        <select id="district-select" name="district"
                                            class="shipping-select @error('district') is-invalid @enderror" required disabled>
                                            <option value="">-- Chọn Quận / Huyện --</option>
                                        </select>
                                        <span class="shipping-select-arrow">▾</span>
                                    </div>
                                    @error('district')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Phường / Xã --}}
                                <div class="shipping-form-group">
                                    <label class="shipping-label">Phường / Xã <span class="req">*</span></label>
                                    <div class="shipping-select-wrapper">
                                        <select id="ward-select" name="ward"
                                            class="shipping-select @error('ward') is-invalid @enderror" required disabled>
                                            <option value="">-- Chọn Phường / Xã --</option>
                                        </select>
                                        <span class="shipping-select-arrow">▾</span>
                                    </div>
                                    @error('ward')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Số nhà, tên đường --}}
                                <div class="shipping-form-group">
                                    <label class="shipping-label">Số nhà, tên đường <span class="req">*</span></label>
                                    <input type="text" name="street_address"
                                        class="shipping-input @error('street_address') is-invalid @enderror"
                                        value="{{ old('street_address') }}"
                                        placeholder="Ví dụ: 123 Nguyễn Huệ" required>
                                    @error('street_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Thông báo phí ship --}}
                            <div class="shipping-fee-notice">
                                <strong>Phí vận chuyển:</strong>
                                Nội thành TP.HCM: <strong>15.000đ</strong> &nbsp;|&nbsp;
                                Tỉnh/thành khác: <strong>25.000đ</strong>
                            </div>

                            {{-- Ghi chú --}}
                            <div class="shipping-form-group">
                                <label class="shipping-label">Ghi chú đơn hàng</label>
                                <textarea name="notes" class="shipping-input shipping-textarea" rows="2"
                                    placeholder="Ghi chú thêm cho đơn hàng (không bắt buộc)">{{ old('notes') }}</textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Tóm tắt đơn hàng --}}
            <div class="col-md-5">
                <div class="cart-detail cart-total p-3 p-md-4">
                    <h3 class="billing-heading mb-4">Tổng giỏ hàng</h3>

                    {{-- Danh sách sản phẩm --}}
                    <div class="mb-4">
                        @foreach($cart as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2"
                                style="border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px;">
                                <div class="d-flex align-items-center">
                                    <div class="img mr-2" style="width:50px;height:50px;background-image:url({{ $item['image'] ? asset($item['image']) : asset('images/menu-1.jpg') }});background-size:cover;border-radius:4px;"></div>
                                    <div>
                                        <span class="d-block text-white" style="font-size:14px;">{{ $item['name'] }}</span>
                                        @if(!empty($item['size']))
                                            <small class="text-warning">Size {{ $item['size'] }}</small>
                                        @endif
                                        @if(!empty($item['modifier_names']))
                                            <small class="d-block" style="color:rgba(255,255,255,0.6);font-size:11px;">{{ $item['modifier_names'] }}</small>
                                        @endif
                                        <small class="text-muted">x{{ $item['quantity'] }}</small>
                                    </div>
                                </div>
                                <span class="text-white">{{ number_format(($item['unit_price'] ?? $item['price']) * $item['quantity'], 0, ',', '.') }}đ</span>
                            </div>
                        @endforeach
                    </div>

                    <p class="d-flex">
                        <span>Tạm tính</span>
                        <span>{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                    </p>
                    <p class="d-flex">
                        <span>Phí vận chuyển</span>
                        <span id="shipping-fee-display">25.000đ</span>
                    </p>
                    <hr>
                    <p class="d-flex total-price">
                        <span>Tổng cộng</span>
                        <span id="total-price-display">{{ number_format($subtotal + 25000, 0, ',', '.') }}đ</span>
                    </p>

                    <p class="text-center mt-4">
                        <button type="submit" form="order-form" class="btn btn-primary py-3 px-4">
                            Xác nhận đặt hàng
                        </button>
                    </p>
                    <p class="text-center">
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary py-2 px-4">
                            ← Quay lại giỏ hàng
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* =========================================================
   SHIPPING INFO CARD
   ========================================================= */
.shipping-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 30px;
}

.shipping-card-header {
    background: linear-gradient(135deg, #c49b63 0%, #a07840 100%);
    padding: 24px 32px 20px;
}

.shipping-card-title {
    color: #fff !important;
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 4px;
    letter-spacing: 0.3px;
}

.shipping-card-subtitle {
    color: rgba(255,255,255,0.82);
    font-size: 0.88rem;
    margin-bottom: 0;
}

.shipping-card-body {
    padding: 28px 32px;
    background: #fff;
}

/* =========================================================
   FORM GROUPS
   ========================================================= */
.shipping-form-group {
    margin-bottom: 18px;
    position: relative;
}

.shipping-label {
    display: block;
    font-size: 0.82rem;
    font-weight: 600;
    color: #5a3e28;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
}

.req {
    color: #e05555;
}

.shipping-input {
    display: block;
    width: 100%;
    padding: 11px 14px;
    font-size: 0.95rem;
    color: #2d2d2d;
    background: #faf8f5;
    border: 1.5px solid #e0d6c8;
    border-radius: 10px;
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
    font-family: inherit;
}

.shipping-input:focus {
    border-color: #c49b63;
    box-shadow: 0 0 0 3px rgba(196,155,99,0.13);
    background: #fff;
}

.shipping-input-readonly {
    background: #f0ece6 !important;
    color: #888 !important;
    cursor: not-allowed;
}

.shipping-hint {
    display: block;
    margin-top: 4px;
    font-size: 0.78rem;
    color: #aaa;
}

.shipping-textarea {
    resize: vertical;
    min-height: 70px;
}

/* =========================================================
   ADDRESS GRID (2 columns)
   ========================================================= */
.shipping-address-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 20px;
}

/* =========================================================
   CUSTOM SELECT (VISUALLY HIDDEN BUT FOCUSABLE FOR VALIDATION)
   ========================================================= */
.shipping-select {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    border: 0 !important;
    opacity: 0 !important;
    pointer-events: none !important;
}

.shipping-select-wrapper {
    position: relative;
}

.shipping-select-arrow {
    display: none !important;
}

/* =========================================================
   CUSTOM SELECT DROPDOWN WIDGET
   ========================================================= */
.custom-select-wrapper {
    position: relative;
    user-select: none;
    width: 100%;
}

.custom-select-trigger {
    display: block;
    width: 100%;
    padding: 11px 36px 11px 14px;
    font-size: 0.95rem;
    color: #2d2d2d;
    background: #faf8f5;
    border: 1.5px solid #e0d6c8;
    border-radius: 10px;
    cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
    outline: none;
    font-family: inherit;
    position: relative;
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.custom-select-trigger:after {
    content: "▾";
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #c49b63;
    font-size: 1rem;
    pointer-events: none;
    line-height: 1;
    transition: transform 0.2s ease;
}

.custom-select-wrapper.open .custom-select-trigger {
    border-color: #c49b63;
    box-shadow: 0 0 0 3px rgba(196,155,99,0.13);
    background: #fff;
}

.custom-select-wrapper.open .custom-select-trigger:after {
    transform: translateY(-50%) rotate(180deg);
}

.custom-select-wrapper.disabled .custom-select-trigger {
    background: #f0ece6;
    color: #bbb;
    cursor: not-allowed;
    border-color: #e8e0d5;
}
.custom-select-wrapper.disabled .custom-select-trigger:after {
    color: #bbb;
}

/* Validation styling */
.custom-select-wrapper.is-invalid .custom-select-trigger {
    border-color: #e05555 !important;
    background: #fff0f0;
}

.custom-select-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    margin-top: 5px;
    background: #fff;
    border: 1.5px solid #e0d6c8;
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    z-index: 1050; /* Make sure it shows above everything */
    max-height: 250px;
    overflow-y: auto;
    display: none;
}

.custom-select-wrapper.open .custom-select-dropdown {
    display: block;
}

.custom-select-option {
    padding: 10px 14px;
    font-size: 0.95rem;
    color: #2d2d2d;
    cursor: pointer;
    transition: background-color 0.15s, color 0.15s;
    text-align: left;
}

.custom-select-option:hover {
    background-color: #c49b63 !important;
    color: #ffffff !important;
}

.custom-select-option.selected {
    background-color: #f7f3ed;
    color: #c49b63;
    font-weight: 600;
}

/* Scrollbar styling for options */
.custom-select-dropdown::-webkit-scrollbar {
    width: 6px;
}
.custom-select-dropdown::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}
.custom-select-dropdown::-webkit-scrollbar-thumb {
    background: #c49b63;
    border-radius: 10px;
}
.custom-select-dropdown::-webkit-scrollbar-thumb:hover {
    background: #a07840;
}

/* =========================================================
   SHIPPING FEE NOTICE
   ========================================================= */
.shipping-fee-notice {
    background: linear-gradient(90deg, #fef9f0 0%, #fdf4e4 100%);
    border: 1.5px solid #f0d9a8;
    border-left: 4px solid #c49b63;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 0.87rem;
    color: #5a3e28;
    margin-bottom: 18px;
}

.shipping-fee-notice strong {
    color: #a07840;
}

/* =========================================================
   INVALID FEEDBACK
   ========================================================= */
.invalid-feedback {
    font-size: 0.82rem;
    color: #e05555;
    margin-top: 4px;
}
.shipping-input.is-invalid,
.shipping-select.is-invalid {
    border-color: #e05555 !important;
}

/* =========================================================
   RESPONSIVE
   ========================================================= */
@media (max-width: 600px) {
    .shipping-address-grid {
        grid-template-columns: 1fr;
    }
    .shipping-card-body {
        padding: 20px 18px;
    }
    .shipping-card-header {
        padding: 18px 18px 14px;
    }
}
</style>
@endpush

@push('scripts')
<script>
var subtotal = {{ $subtotal }};
var oldProvinceCode = "{{ old('province_code', '') }}";
var oldProvinceName = "{{ old('province', '') }}";
var oldDistrictName = "{{ old('district', '') }}";
var oldWardName     = "{{ old('ward', '') }}";

// HCM province code = 79
var HCM_CODE = 79;
var currentProvinceCode = null;

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
}

function updateShipping() {
    var isHCM = (currentProvinceCode == HCM_CODE);
    var shippingFee = isHCM ? 15000 : 25000;
    document.getElementById('shipping-fee-display').textContent = formatCurrency(shippingFee);
    document.getElementById('total-price-display').textContent = formatCurrency(subtotal + shippingFee);
}

// ============================================================
// Load danh sách 63 tỉnh thành từ API công cộng
// Tên tỉnh/huyện/xã được dùng làm value để gửi lên server
// Province code được lưu vào hidden input để tính phí ship
// ============================================================
var API_BASE = 'https://provinces.open-api.vn/api';

fetch(API_BASE + '/?depth=1')
    .then(function(r) { return r.json(); })
    .then(function(provinces) {
        var sel = document.getElementById('province-select');
        provinces.forEach(function(p) {
            var opt = document.createElement('option');
            opt.value        = p.name;          // Gửi tên lên server
            opt.dataset.code = p.code;          // Lưu code để dùng JS
            opt.textContent  = p.name;
            if (oldProvinceName && oldProvinceName === p.name) {
                opt.selected = true;
                currentProvinceCode = p.code;
                document.getElementById('province-code-input').value = p.code;
            }
            sel.appendChild(opt);
        });
        updateShipping();
        if (oldProvinceName && currentProvinceCode) {
            loadDistricts(currentProvinceCode, oldDistrictName);
        }
    })
    .catch(function() {
        console.warn('Không thể tải danh sách tỉnh thành từ API. Vui lòng thử lại.');
    });

document.getElementById('province-select').addEventListener('change', function() {
    var selectedOpt = this.options[this.selectedIndex];
    currentProvinceCode = selectedOpt.dataset.code ? parseInt(selectedOpt.dataset.code) : null;
    document.getElementById('province-code-input').value = currentProvinceCode || '';
    updateShipping();

    // Reset district & ward
    var distSel = document.getElementById('district-select');
    var wardSel = document.getElementById('ward-select');
    distSel.innerHTML = '<option value="">-- Chọn Quận / Huyện --</option>';
    wardSel.innerHTML  = '<option value="">-- Chọn Phường / Xã --</option>';
    distSel.disabled = true;
    wardSel.disabled  = true;

    if (!currentProvinceCode) return;
    loadDistricts(currentProvinceCode, null);
});

function loadDistricts(provinceCode, selectDistrictName) {
    fetch(API_BASE + '/p/' + provinceCode + '?depth=2')
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var sel = document.getElementById('district-select');
            sel.innerHTML = '<option value="">-- Chọn Quận / Huyện --</option>';
            sel.disabled = false;
            var foundCode = null;
            (data.districts || []).forEach(function(d) {
                var opt = document.createElement('option');
                opt.value        = d.name;      // Gửi tên lên server
                opt.dataset.code = d.code;
                opt.textContent  = d.name;
                if (selectDistrictName && selectDistrictName === d.name) {
                    opt.selected = true;
                    foundCode = d.code;
                }
                sel.appendChild(opt);
            });
            if (foundCode && oldWardName) {
                loadWards(foundCode, oldWardName);
            }
        });
}

document.getElementById('district-select').addEventListener('change', function() {
    var selectedOpt = this.options[this.selectedIndex];
    var code = selectedOpt.dataset.code;
    var wardSel = document.getElementById('ward-select');
    wardSel.innerHTML = '<option value="">-- Chọn Phường / Xã --</option>';
    wardSel.disabled  = true;
    if (!code) return;
    loadWards(code, null);
});

function loadWards(districtCode, selectWardName) {
    fetch(API_BASE + '/d/' + districtCode + '?depth=2')
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var sel = document.getElementById('ward-select');
            sel.innerHTML = '<option value="">-- Chọn Phường / Xã --</option>';
            sel.disabled = false;
            (data.wards || []).forEach(function(w) {
                var opt = document.createElement('option');
                opt.value       = w.name;       // Gửi tên lên server
                opt.textContent = w.name;
                if (selectWardName && selectWardName === w.name) opt.selected = true;
                sel.appendChild(opt);
            });
        });
}

// ============================================================
// HỆ THỐNG CUSTOM SELECT DROPDOWN (HOVER MÀU NÂU)
// ============================================================
function initCustomSelect(selectId) {
    var select = document.getElementById(selectId);
    if (!select) return;

    // Ẩn select gốc nhưng giữ lại khả năng focus để HTML5 validation hoạt động
    select.style.position = 'absolute';
    select.style.opacity = '0';
    select.style.width = '1px';
    select.style.height = '1px';
    select.style.padding = '0';
    select.style.margin = '-1px';
    select.style.overflow = 'hidden';
    select.style.pointerEvents = 'none';

    // Ẩn mũi tên mặc định của wrapper
    var wrapper = select.parentElement;
    var oldArrow = wrapper.querySelector('.shipping-select-arrow');
    if (oldArrow) {
        oldArrow.style.display = 'none';
    }

    // Tạo các element cho custom select
    var customWrapper = document.createElement('div');
    customWrapper.className = 'custom-select-wrapper';
    if (select.classList.contains('is-invalid')) {
        customWrapper.classList.add('is-invalid');
    }

    var trigger = document.createElement('div');
    trigger.className = 'custom-select-trigger';
    
    var dropdown = document.createElement('div');
    dropdown.className = 'custom-select-dropdown';

    customWrapper.appendChild(trigger);
    customWrapper.appendChild(dropdown);
    wrapper.appendChild(customWrapper);

    // Cập nhật trạng thái disabled
    function updateDisabledState() {
        if (select.disabled) {
            customWrapper.classList.add('disabled');
        } else {
            customWrapper.classList.remove('disabled');
        }
    }
    updateDisabledState();

    // Dựng lại danh sách option
    function rebuildOptions() {
        dropdown.innerHTML = '';
        var options = select.options;
        for (var i = 0; i < options.length; i++) {
            var opt = options[i];
            var customOpt = document.createElement('div');
            customOpt.className = 'custom-select-option';
            customOpt.textContent = opt.textContent;
            customOpt.dataset.value = opt.value;
            customOpt.dataset.index = i;

            if (opt.selected) {
                customOpt.classList.add('selected');
                trigger.textContent = opt.textContent;
            }

            customOpt.addEventListener('click', function(e) {
                e.stopPropagation();
                if (select.disabled) return;
                
                var idx = parseInt(this.dataset.index);
                select.selectedIndex = idx;
                
                // Kích hoạt sự kiện change trên select gốc
                var event = new Event('change', { bubbles: true });
                select.dispatchEvent(event);
                
                customWrapper.classList.remove('open');
            });

            dropdown.appendChild(customOpt);
        }
        
        // Cập nhật text hiển thị ở trigger
        var selectedOpt = select.options[select.selectedIndex];
        trigger.textContent = selectedOpt ? selectedOpt.textContent : '';
    }

    rebuildOptions();

    // Lắng nghe sự thay đổi của options (childList) và thuộc tính disabled từ select gốc
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                rebuildOptions();
            } else if (mutation.type === 'attributes' && mutation.attributeName === 'disabled') {
                updateDisabledState();
            }
        });
    });

    observer.observe(select, {
        childList: true,
        attributes: true,
        attributeFilter: ['disabled']
    });

    // Lắng nghe sự thay đổi giá trị từ JS
    select.addEventListener('change', function() {
        var selectedOpt = select.options[select.selectedIndex];
        trigger.textContent = selectedOpt ? selectedOpt.textContent : '';
        
        // Cập nhật class selected cho custom options
        var customOpts = dropdown.querySelectorAll('.custom-select-option');
        customOpts.forEach(function(co, idx) {
            if (idx === select.selectedIndex) {
                co.classList.add('selected');
            } else {
                co.classList.remove('selected');
            }
        });

        if (select.value !== "") {
            customWrapper.classList.remove('is-invalid');
        }
    });

    // Lắng nghe sự kiện validation thất bại để hiển thị viền đỏ
    select.addEventListener('invalid', function() {
        customWrapper.classList.add('is-invalid');
    });

    // Toggle dropdown khi click vào trigger
    trigger.addEventListener('click', function(e) {
        e.stopPropagation();
        if (select.disabled) return;

        // Đóng toàn bộ dropdown khác trước khi mở cái này
        document.querySelectorAll('.custom-select-wrapper').forEach(function(cw) {
            if (cw !== customWrapper) {
                cw.classList.remove('open');
            }
        });

        customWrapper.classList.toggle('open');
    });

    // Đóng dropdown khi click ra ngoài
    document.addEventListener('click', function() {
        customWrapper.classList.remove('open');
    });
}

// Khởi tạo các custom select dropdown
initCustomSelect('province-select');
initCustomSelect('district-select');
initCustomSelect('ward-select');

updateShipping();
</script>
@endpush
