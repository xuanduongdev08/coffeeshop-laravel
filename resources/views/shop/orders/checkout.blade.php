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
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-4">Thông tin giao hàng</h3>

                        <form method="POST" action="{{ route('orders.store') }}" id="order-form">
                            @csrf

                            {{-- Hidden: province code dùng để tính phí ship --}}
                            <input type="hidden" name="province_code" id="province-code-input" value="{{ old('province_code', '') }}">

                            {{-- Email (chỉ đọc) --}}
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control"
                                    value="{{ $user->email }}" readonly
                                    style="background:#f0f0f0; cursor:not-allowed; color:#666;">
                                <small class="text-muted">Email tài khoản của bạn.</small>
                            </div>

                            {{-- Họ và tên --}}
                            <div class="form-group">
                                <label>Họ và tên người nhận <span class="text-danger">*</span></label>
                                <input type="text" name="recipient_name"
                                    class="form-control @error('recipient_name') is-invalid @enderror"
                                    value="{{ old('recipient_name', $user->name) }}" required>
                                @error('recipient_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tỉnh / Thành phố --}}
                            <div class="form-group">
                                <label>Tỉnh / Thành phố <span class="text-danger">*</span></label>
                                <select id="province-select" name="province"
                                    class="form-control @error('province') is-invalid @enderror" required>
                                    <option value="">-- Chọn Tỉnh / Thành phố --</option>
                                </select>
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Quận / Huyện --}}
                            <div class="form-group">
                                <label>Quận / Huyện <span class="text-danger">*</span></label>
                                <select id="district-select" name="district"
                                    class="form-control @error('district') is-invalid @enderror" required disabled>
                                    <option value="">-- Chọn Quận / Huyện --</option>
                                </select>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Phường / Xã --}}
                            <div class="form-group">
                                <label>Phường / Xã <span class="text-danger">*</span></label>
                                <select id="ward-select" name="ward"
                                    class="form-control @error('ward') is-invalid @enderror" required disabled>
                                    <option value="">-- Chọn Phường / Xã --</option>
                                </select>
                                @error('ward')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Số nhà, tên đường --}}
                            <div class="form-group">
                                <label>Số nhà, tên đường <span class="text-danger">*</span></label>
                                <input type="text" name="street_address"
                                    class="form-control @error('street_address') is-invalid @enderror"
                                    value="{{ old('street_address') }}"
                                    placeholder="Ví dụ: 123 Nguyễn Huệ" required>
                                @error('street_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Thông báo phí ship --}}
                            <div class="alert alert-info py-2 px-3 mb-3 checkout-shipping-note" style="font-size:13px; border-radius:8px;">
                                <i class="icon-info mr-1"></i>
                                <strong>Phí vận chuyển:</strong>
                                Nội thành TP.HCM: <strong>15.000đ</strong> &nbsp;|&nbsp;
                                Tỉnh/thành khác: <strong>25.000đ</strong>
                            </div>

                            {{-- Số điện thoại --}}
                            <div class="form-group">
                                <label>Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->phone ?? '') }}"
                                    placeholder="Nhập số điện thoại (10-11 số)" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Ghi chú --}}
                            <div class="form-group">
                                <label>Ghi chú</label>
                                <textarea name="notes" class="form-control" rows="2"
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

updateShipping();
</script>
@endpush
