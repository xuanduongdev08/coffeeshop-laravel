@extends('layouts.shop')

@section('title', $product->name)

@section('content')

{{-- Page Header --}}
<section class="hero-page-header" style="background-image: url({{ asset('images/bg_3.jpg') }}); background-size: cover; background-position: center; height: 350px;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Chi tiết sản phẩm</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('home') }}">Trang chủ</a></span>
                        <span class="mr-2"><a href="{{ route('products.index') }}">Sản phẩm</a></span>
                        <span>{{ $product->name }}</span>
                    </p>
                </div>
        </div>
    </div>
</section>

{{-- Product Detail --}}
<section class="ftco-section" id="product-detail-section">
    <div class="container">
        {{-- Back Button --}}
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ url()->previous() }}" style="display:inline-flex;align-items:center;gap:8px;padding:9px 20px;background:transparent;border:2px solid #b5883e;color:#b5883e;border-radius:25px;font-weight:600;font-size:14px;text-decoration:none;transition:all 0.3s ease;"
                    onmouseover="this.style.background='#b5883e';this.style.color='#fff';"
                    onmouseout="this.style.background='transparent';this.style.color='#b5883e';">
                    ← Quay lại trang sản phẩm
                </a>
            </div>
        </div>

        <div class="row">
            {{-- Product Image --}}
            <div class="col-lg-6 mb-5 ftco-animate">
                <a href="{{ $product->image ? asset($product->image) : asset('images/menu-1.jpg') }}"
                    id="main-product-image-link" class="image-popup {{ $product->stock == 0 ? 'out-of-stock-img' : '' }}">
                    @if($product->stock == 0)
                        <div class="out-of-stock-badge">Hết hàng</div>
                    @endif
                    <img src="{{ $product->image ? asset($product->image) : asset('images/menu-1.jpg') }}"
                        id="main-product-image" class="img-fluid" alt="{{ $product->name }}"
                        style="width:100%;height:500px;object-fit:cover;border-radius:12px;">
                </a>
            </div>

            {{-- Product Info --}}
            <div class="col-lg-6 product-details pl-md-5 ftco-animate">
                <h3>{{ $product->name }}</h3>

                @if($product->category)
                    <p class="text-muted mb-2">
                        <small>Danh mục: <a href="{{ route('categories.show', $product->category->slug) }}" style="color:#c49b63;">{{ $product->category->name }}</a></small>
                    </p>
                @endif

                {{-- Price --}}
                @if($product->discount_price)
                    @php $discountPct = round((($product->price - $product->discount_price) / $product->price) * 100); @endphp
                    <p class="price" style="display:flex;align-items:center;gap:10px;">
                        <span style="text-decoration:line-through;color:#999;font-size:18px;">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                        <span style="color:#c49b63;font-weight:700;font-size:24px;">{{ number_format($product->discount_price, 0, ',', '.') }}đ</span>
                        <span style="background:#c49b63;color:white;padding:4px 10px;border-radius:15px;font-size:13px;font-weight:700;">-{{ $discountPct }}%</span>
                    </p>
                @else
                    <p class="price"><span>{{ number_format($product->price, 0, ',', '.') }}đ</span></p>
                @endif

                <p>{{ $product->description }}</p>

                {{-- Stock Status --}}
                <p>
                    @if($product->stock > 0 && $product->stock <= 5)
                        <span style="color:#e67e22;font-weight:600;"><i class="icon-check"></i> Sản phẩm còn {{ $product->stock }} sản phẩm, mua ngay!</span>
                    @elseif($product->stock == 0)
                        <span style="color:#dc3545;font-weight:600;"><i class="icon-close"></i> Hết hàng</span>
                    @endif
                </p>

                {{-- Rating --}}
                @if($product->reviews->count() > 0)
                    <p>
                        @php $avgRating = $product->reviews->avg('rating'); @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <span style="color:{{ $i <= $avgRating ? '#FFD700' : '#ddd' }};font-size:18px;">★</span>
                        @endfor
                        <small class="text-muted ml-2">({{ $product->reviews->count() }} đánh giá)</small>
                    </p>
                @endif

                {{-- Size M/L/XL --}}
                @if($product->has_size && $product->sizes->count() > 0)
                    <div class="mb-3" id="size-selector">
                        <label class="font-weight-bold d-block mb-2">Chọn size <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2" style="gap:10px;flex-wrap:wrap;">
                            @foreach($product->sizes->where('is_active', true) as $sizeOpt)
                                <label class="size-option" style="cursor:pointer;">
                                    <input type="radio" name="size" value="{{ $sizeOpt->size }}"
                                        data-price="{{ $sizeOpt->price }}"
                                        style="display:none;" {{ $loop->first ? 'checked' : '' }}>
                                    <div class="size-btn {{ $loop->first ? 'active' : '' }}"
                                        style="padding:8px 18px;border:2px solid #c49b63;border-radius:8px;font-weight:600;font-size:14px;transition:all 0.2s;background:{{ $loop->first ? '#c49b63' : 'white' }};color:{{ $loop->first ? 'white' : '#c49b63' }};">
                                        {{ $sizeOpt->size }}<br>
                                        <small style="font-size:11px;font-weight:400;">{{ number_format($sizeOpt->price, 0, ',', '.') }}đ</small>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Modifiers: Đường, Đá, Sữa, Topping --}}
                @if($modifiers->isNotEmpty())
                    <div class="mb-3" id="modifier-selector">
                        @php
                            $typeLabels = ['sugar' => 'Mức đường', 'ice' => 'Mức đá', 'milk' => 'Loại sữa', 'topping' => 'Topping'];
                            $typeRadio  = ['sugar', 'ice', 'milk']; // radio (chọn 1)
                            $typeCheck  = ['topping'];               // checkbox (chọn nhiều)
                            $isJuice    = $product->has_topping && !$product->allow_milk;
                            if ($isJuice) $typeLabels['sugar'] = 'Mức ngọt';
                        @endphp
                        @foreach($modifiers as $type => $group)
                            <div class="modifier-group mb-3">
                                <label class="font-weight-bold d-block mb-2">{{ $typeLabels[$type] ?? $type }}</label>
                                <div class="d-flex flex-wrap" style="gap:8px;">
                                    {{-- Option "Không chọn" cho sugar và milk --}}
                                    @if($type === 'sugar')
                                        <label class="modifier-option" style="cursor:pointer;">
                                            <input type="radio" name="modifier_sugar"
                                                value=""
                                                data-extra="0"
                                                data-name="Không đường"
                                                class="modifier-input"
                                                style="display:none;">
                                            <div class="modifier-btn"
                                                style="padding:6px 14px;border:2px solid rgba(255,255,255,0.3);border-radius:20px;font-size:13px;transition:all 0.2s;white-space:nowrap;background:transparent;color:#fff;">
                                                Không đường
                                            </div>
                                        </label>
                                    @elseif($type === 'milk')
                                        <label class="modifier-option" style="cursor:pointer;">
                                            <input type="radio" name="modifier_milk"
                                                value=""
                                                data-extra="0"
                                                data-name="Không sữa"
                                                class="modifier-input"
                                                style="display:none;">
                                            <div class="modifier-btn"
                                                style="padding:6px 14px;border:2px solid rgba(255,255,255,0.3);border-radius:20px;font-size:13px;transition:all 0.2s;white-space:nowrap;background:transparent;color:#fff;">
                                                Không sữa
                                            </div>
                                        </label>
                                    @endif
                                    @foreach($group as $mod)
                                        <label class="modifier-option" style="cursor:pointer;">
                                            @if(in_array($type, $typeRadio))
                                                <input type="radio" name="modifier_{{ $type }}"
                                                    value="{{ $mod->id }}"
                                                    data-extra="{{ $mod->extra_price }}"
                                                    data-name="{{ $mod->name }}"
                                                    class="modifier-input"
                                                    style="display:none;">
                                            @else
                                                <input type="checkbox" name="modifier_{{ $type }}[]"
                                                    value="{{ $mod->id }}"
                                                    data-extra="{{ $mod->extra_price }}"
                                                    data-name="{{ $mod->name }}"
                                                    class="modifier-input"
                                                    style="display:none;">
                                            @endif
                                            <div class="modifier-btn"
                                                style="padding:6px 14px;border:2px solid rgba(255,255,255,0.3);border-radius:20px;font-size:13px;transition:all 0.2s;white-space:nowrap;background:transparent;color:#fff;">
                                                {{ $mod->name }}
                                                @if($mod->extra_price > 0)
                                                    <span style="color:#c49b63;font-size:11px;">+{{ number_format($mod->extra_price, 0, ',', '.') }}đ</span>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Giá hiển thị động --}}
                @if($product->has_size || $modifiers->isNotEmpty())
                    <div class="mb-3 p-3" style="background:#fdfaf7;border-radius:10px;border:1px solid #f0e8dd;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold">Giá:</span>
                            <span id="dynamic-price" style="color:#c49b63;font-size:22px;font-weight:700;">
                                {{ number_format($product->effective_price, 0, ',', '.') }}đ
                            </span>
                        </div>
                        <div id="price-breakdown" style="font-size:12px;color:#888;margin-top:4px;"></div>
                    </div>
                @endif

                {{-- Quantity + Add to Cart --}}
                <div class="row mt-4">
                    <div class="w-100"></div>
                    <div class="input-group col-md-6 d-flex mb-3">
                        <span class="input-group-btn mr-2">
                            <button type="button" class="quantity-left-minus btn {{ $product->stock == 0 ? 'disabled' : '' }}"
                                {{ $product->stock == 0 ? 'disabled' : '' }}>
                                <i class="icon-minus"></i>
                            </button>
                        </span>
                        <input type="text" id="quantity" name="quantity" class="form-control input-number"
                            value="1" min="1" max="{{ $product->stock }}"
                            {{ $product->stock == 0 ? 'disabled' : '' }}>
                        <span class="input-group-btn ml-2">
                            <button type="button" class="quantity-right-plus btn {{ $product->stock == 0 ? 'disabled' : '' }}"
                                {{ $product->stock == 0 ? 'disabled' : '' }}>
                                <i class="icon-plus"></i>
                            </button>
                        </span>
                    </div>
                </div>
                <p>
                    @if($product->stock == 0)
                        <button class="btn btn-primary btn-outline-primary py-3 px-5" disabled>Hết hàng</button>
                    @else
                        <button class="btn btn-primary btn-outline-primary py-3 px-5" id="btn-add-to-cart-custom"
                            data-product-id="{{ $product->id }}"
                            data-has-size="{{ $product->has_size ? '1' : '0' }}">
                            Thêm vào giỏ hàng
                        </button>
                    @endif
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Related Products --}}
@if($relatedProducts->count() > 0)
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
            <div class="col-md-7 heading-section ftco-animate text-center">
                <span class="subheading">Khám phá</span>
                <h2 class="mb-4">Sản phẩm liên quan</h2>
            </div>
        </div>
        <div class="row">
            @foreach($relatedProducts as $related)
                <div class="col-md-3">
                    <div class="menu-entry">
                        <a href="{{ route('products.show', $related->slug) }}"
                            class="img {{ $related->stock == 0 ? 'out-of-stock-img' : '' }}"
                            style="background-image: url({{ $related->image ? asset($related->image) : asset('images/menu-1.jpg') }}); position: relative;">
                            @if($related->stock == 0)
                                <div class="out-of-stock-badge">Hết hàng</div>
                            @endif
                        </a>
                        <div class="text text-center pt-4">
                            <h3><a href="{{ route('products.show', $related->slug) }}">{{ $related->name }}</a></h3>
                            <p class="price"><span>{{ number_format($related->effective_price, 0, ',', '.') }}đ</span></p>
                            <p>
                                @if($related->stock == 0)
                                    <button class="btn btn-primary btn-outline-primary" disabled>Hết hàng</button>
                                @else
                                    <button class="btn btn-primary btn-outline-primary btn-add-to-cart"
                                        data-product-id="{{ $related->id }}">
                                        Thêm vào giỏ
                                    </button>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Reviews Section --}}
<section class="ftco-section" id="reviewSection">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-md-12 text-center">
                <h2 style="color:#6F4E37;font-weight:700;">Đánh giá sản phẩm</h2>
            </div>
        </div>

        @php
            $reviews = $product->reviews()->with('user')->latest()->get();
            $total = $reviews->count();
            $avg = $total > 0 ? round($reviews->avg('rating'), 1) : 0;
        @endphp

        {{-- Rating Summary --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <div style="background:linear-gradient(135deg,#6F4E37 0%,#8B6F47 100%);color:white;border-radius:10px;padding:30px;">
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center" style="border-right:2px solid rgba(255,255,255,0.3);">
                            <h1 style="font-size:48px;margin:0;color:#FFD700;">{{ $avg }}</h1>
                            <div style="font-size:20px;color:#FFD700;">
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $avg ? '★' : '☆' }}
                                @endfor
                            </div>
                            <p style="margin:10px 0 0 0;">{{ $total }} đánh giá</p>
                        </div>
                        <div class="col-md-8">
                            @for($star = 5; $star >= 1; $star--)
                                @php
                                    $count = $reviews->where('rating', $star)->count();
                                    $percent = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                                @endphp
                                <div class="d-flex align-items-center mb-2">
                                    <span style="min-width:60px;font-weight:600;">{{ $star }} ★</span>
                                    <div class="progress" style="flex:1;height:10px;margin:0 15px;background:rgba(255,255,255,0.3);">
                                        <div class="progress-bar" style="width:{{ $percent }}%;background:#FFD700;"></div>
                                    </div>
                                    <span style="min-width:50px;text-align:right;font-weight:600;">{{ $percent }}%</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Review List --}}
        @forelse($reviews as $review)
            <div style="border:1px solid #e0e0e0;border-radius:10px;padding:20px;margin-bottom:15px;background:white;">
                <div class="d-flex align-items-start">
                    <div style="width:50px;height:50px;border-radius:50%;background:linear-gradient(135deg,#6F4E37,#8B6F47);color:white;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:bold;margin-right:15px;flex-shrink:0;">
                        {{ strtoupper(substr($review->user->name ?? 'K', 0, 1)) }}
                    </div>
                    <div style="flex:1;">
                        <h5 style="margin:0 0 5px 0;color:#333;">{{ $review->user->name ?? 'Khách hàng' }}</h5>
                        <div style="color:#FFD700;margin-bottom:10px;">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $review->rating ? '★' : '☆' }}
                            @endfor
                        </div>
                        <p style="color:#555;margin-bottom:5px;">{{ $review->comment }}</p>
                        <small style="color:#999;">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center p-5" style="background:#f8f9fa;border-radius:10px;">
                <div style="font-size:48px;color:#ccc;">💬</div>
                <h4 style="color:#666;margin-top:20px;">Chưa có đánh giá nào</h4>
                <p style="color:#999;">Hãy là người đầu tiên đánh giá sản phẩm này!</p>
            </div>
        @endforelse

        {{-- Write Review Form --}}
        @auth
            <div class="mt-4">
                <div style="border:2px solid #6F4E37;border-radius:10px;padding:30px;background:white;">
                    <h5 style="color:#6F4E37;margin-bottom:20px;">Viết đánh giá của bạn</h5>
                    <form action="{{ route('reviews.store', $product) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label style="font-weight:600;color:#000;">Đánh giá <span class="text-danger">*</span></label>
                            <div class="star-rating" style="font-size:32px;color:#ddd;cursor:pointer;">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star" data-rating="{{ $i }}">★</span>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating" required>
                            @error('rating') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label style="font-weight:600;color:#000;">Nhận xét <span class="text-danger">*</span></label>
                            <textarea name="comment" class="form-control" rows="4"
                                placeholder="Chia sẻ trải nghiệm của bạn..." required
                                style="border:2px solid #e0e0e0;border-radius:8px;font-size:14px;padding:10px;">{{ old('comment') }}</textarea>
                            @error('comment') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary"
                            style="background:linear-gradient(135deg,#6F4E37,#8B6F47);border:none;padding:12px 40px;border-radius:25px;font-weight:600;">
                            Gửi đánh giá
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="mt-4 text-center">
                <div class="alert alert-info" style="border-radius:10px;padding:20px;">
                    Vui lòng <a href="{{ route('login') }}" style="color:#6F4E37;font-weight:bold;">đăng nhập</a> để viết đánh giá
                </div>
            </div>
        @endauth
    </div>
</section>

@endsection

@push('styles')
<style>
.modifier-btn.active span {
    color: rgba(255, 255, 255, 0.85) !important;
}
#btn-add-to-cart-custom {
    transition: all 0.3s ease !important;
}
#btn-add-to-cart-custom:hover {
    color: #fff !important;
    background: #c49b63 !important;
    border-color: transparent !important;
}
.product-details .btn-outline-primary:disabled {
    color: #c49b63 !important;
    background: transparent !important;
    opacity: 0.5 !important;
    border-color: #c49b63 !important;
    cursor: not-allowed;
}
</style>
@endpush

@push('scripts')
<script>
var hasSize = {{ $product->has_size ? 'true' : 'false' }};
var basePrice = {{ $product->effective_price }};

// ── Size selector ──────────────────────────────────────────────
document.querySelectorAll('.size-option').forEach(function(label) {
    label.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var radio = label.querySelector('input[type="radio"]');
        var btn   = label.querySelector('.size-btn');
        // Deactivate tất cả size
        document.querySelectorAll('.size-option').forEach(function(l) {
            l.querySelector('input[type="radio"]').checked = false;
            var b = l.querySelector('.size-btn');
            b.style.background = 'transparent';
            b.style.color      = '#c49b63';
            b.style.border     = '2px solid #c49b63';
        });
        // Activate size này
        radio.checked          = true;
        btn.style.background   = '#c49b63';
        btn.style.color        = 'white';
        btn.style.border       = '2px solid #c49b63';
        updateDynamicPrice();
    });
});

// ── Modifier selector ──────────────────────────────────────────
document.querySelectorAll('.modifier-option').forEach(function(label) {
    var input = label.querySelector('.modifier-input');
    var btn   = label.querySelector('.modifier-btn');

    label.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        if (input.type === 'radio') {
            var alreadyChecked = input.checked;
            // Deactivate tất cả trong nhóm
            document.querySelectorAll('input[name="' + input.name + '"]').forEach(function(r) {
                r.checked = false;
                var b = r.nextElementSibling;
                b.style.borderColor = 'rgba(255,255,255,0.3)';
                b.style.background  = 'transparent';
                b.style.color       = '#fff';
                b.classList.remove('active');
            });
            if (!alreadyChecked) {
                // Chọn cái này
                input.checked = true;
                btn.style.borderColor = '#c49b63';
                btn.style.background  = '#c49b63';
                btn.style.color       = '#fff';
                btn.classList.add('active');
            }
            // Nếu alreadyChecked → bấm lại = bỏ chọn
        } else {
            // Checkbox toggle
            input.checked = !input.checked;
            if (input.checked) {
                btn.style.borderColor = '#c49b63';
                btn.style.background  = '#c49b63';
                btn.style.color       = '#fff';
                btn.classList.add('active');
            } else {
                btn.style.borderColor = 'rgba(255,255,255,0.3)';
                btn.style.background  = 'transparent';
                btn.style.color       = '#fff';
                btn.classList.remove('active');
            }
        }
        updateDynamicPrice();
    });
});

function getSelectedSize() {
    var radio = document.querySelector('input[name="size"]:checked');
    return radio ? { price: parseFloat(radio.dataset.price), size: radio.value } : null;
}

function getSelectedModifiers() {
    var total = 0;
    var names = [];
    document.querySelectorAll('.modifier-input:checked').forEach(function(input) {
        if (input.value === '') return; // bỏ qua "Không đường" / "Không sữa"
        var extra = parseFloat(input.dataset.extra || 0);
        total += extra;
        if (extra > 0) {
            names.push(input.dataset.name + ' (+' + new Intl.NumberFormat('vi-VN').format(extra) + 'đ)');
        } else {
            names.push(input.dataset.name);
        }
    });
    return { total: total, names: names };
}

function updateDynamicPrice() {
    var priceEl = document.getElementById('dynamic-price');
    var breakEl = document.getElementById('price-breakdown');
    if (!priceEl) return;

    var sizeData = getSelectedSize();
    var base     = sizeData ? sizeData.price : basePrice;
    var mods     = getSelectedModifiers();
    var total    = base + mods.total;

    priceEl.textContent = new Intl.NumberFormat('vi-VN').format(total) + 'đ';

    var parts = [];
    if (sizeData) parts.push('Size ' + sizeData.size + ': ' + new Intl.NumberFormat('vi-VN').format(base) + 'đ');
    if (mods.names.length) parts = parts.concat(mods.names);
    breakEl.textContent = parts.join(' | ');
}

// ── Add to Cart với size + modifiers ──────────────────────────
var addBtn = document.getElementById('btn-add-to-cart-custom');
if (addBtn) {
    addBtn.addEventListener('click', function(e) {
        e.preventDefault();

        if (!isLoggedIn) {
            Swal.fire({
                title: 'Thông báo',
                text: 'Bạn chưa đăng nhập, vui lòng đăng nhập để thêm vào giỏ hàng',
                icon: 'warning',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonColor: '#c49b63',
                denyButtonColor: '#6f4e37',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đăng ký',
                denyButtonText: 'Đăng nhập',
                cancelButtonText: 'Đóng'
            }).then(function(result) {
                if (result.isConfirmed) window.location.href = '{{ route('register') }}';
                else if (result.isDenied) window.location.href = '{{ route('login') }}';
            });
            return;
        }

        // Validate size nếu sản phẩm có size
        var sizeData = getSelectedSize();
        if (hasSize && !sizeData) {
            Swal.fire({ icon: 'warning', title: 'Chọn size', text: 'Vui lòng chọn size trước khi thêm vào giỏ hàng.', confirmButtonColor: '#c49b63' });
            return;
        }

        var productId  = addBtn.dataset.productId;
        var quantity   = parseInt(document.getElementById('quantity').value) || 1;
        var modifierIds = [];
        document.querySelectorAll('.modifier-input:checked').forEach(function(input) {
            if (input.value !== '') modifierIds.push(input.value);
        });

        var formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);
        formData.append('_token', csrfToken);
        if (sizeData) formData.append('size', sizeData.size);
        modifierIds.forEach(function(id) { formData.append('modifier_ids[]', id); });

        fetch('{{ route('cart.add') }}', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Thành công!', text: data.message, timer: 1800, showConfirmButton: false });
                document.querySelector('.bag small').textContent = data.cart_count;
            } else {
                Swal.fire({ icon: 'error', title: 'Lỗi', text: data.message, confirmButtonColor: '#c49b63' });
            }
        })
        .catch(function() {
            Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Không thể kết nối server', confirmButtonColor: '#c49b63' });
        });
    });
}

// Quantity controls
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(e) {
        if (e.target.closest('.quantity-right-plus')) {
            e.preventDefault();
            var input = document.getElementById('quantity');
            var val = parseInt(input.value);
            var max = parseInt(input.max) || 999;
            if (!isNaN(val) && val < max) input.value = val + 1;
        }
        if (e.target.closest('.quantity-left-minus')) {
            e.preventDefault();
            var input = document.getElementById('quantity');
            var val = parseInt(input.value);
            if (!isNaN(val) && val > 1) input.value = val - 1;
        }
    });

    // Auto-scroll to product detail
    setTimeout(function() {
        var section = document.getElementById('product-detail-section');
        if (section) {
            var navH = document.querySelector('.navbar') ? document.querySelector('.navbar').offsetHeight : 70;
            window.scrollTo({ top: section.getBoundingClientRect().top + window.pageYOffset - navH, behavior: 'smooth' });
        }
    }, 300);

    // Init price display
    updateDynamicPrice();
});

// Star rating
document.querySelectorAll('.star').forEach(function(star) {
    star.addEventListener('click', function() {
        var rating = this.dataset.rating;
        document.getElementById('rating').value = rating;
        document.querySelectorAll('.star').forEach(function(s, i) {
            s.style.color = i < rating ? '#FFD700' : '#ddd';
        });
    });
    star.addEventListener('mouseenter', function() {
        var rating = this.dataset.rating;
        document.querySelectorAll('.star').forEach(function(s, i) {
            s.style.color = i < rating ? '#FFD700' : '#ddd';
        });
    });
});
var starRating = document.querySelector('.star-rating');
if (starRating) {
    starRating.addEventListener('mouseleave', function() {
        var rating = document.getElementById('rating') ? document.getElementById('rating').value : 0;
        document.querySelectorAll('.star').forEach(function(s, i) {
            s.style.color = i < rating ? '#FFD700' : '#ddd';
        });
    });
}
</script>
@endpush
