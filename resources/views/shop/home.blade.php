@extends('layouts.shop')

@section('title', 'Trang chủ')

@section('content')

{{-- Hero Slider --}}
<section class="home-slider owl-carousel">
    @if($banners->count() > 0)
        @foreach($banners as $banner)
            <div class="slider-item" style="background-image: url({{ asset($banner->image) }});">
                <div class="overlay"></div>
                <div class="container">
                    <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">
                        <div class="col-md-8 col-sm-12 text-center ftco-animate">
                            <h1 class="mb-4">{{ $banner->title }}</h1>
                            <p class="mb-4 mb-md-5">Chúng tôi mang đến những sản phẩm cà phê chất lượng cao với hương vị đặc biệt.</p>
                            <p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary p-3 px-xl-4 py-xl-3">Đặt hàng ngay</a>
                                <a href="{{ route('products.index') }}" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">Xem sản phẩm</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="slider-item" style="background-image: url({{ asset('images/bg_1.jpg') }});">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">
                    <div class="col-md-8 col-sm-12 text-center ftco-animate">
                        <h1 class="mb-4">Trải nghiệm cà phê tuyệt vời nhất</h1>
                        <p class="mb-4 mb-md-5">Chúng tôi mang đến những sản phẩm cà phê chất lượng cao với hương vị đặc biệt.</p>
                        <p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary p-3 px-xl-4 py-xl-3">Đặt hàng ngay</a>
                            <a href="{{ route('products.index') }}" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">Xem sản phẩm</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="slider-item" style="background-image: url({{ asset('images/bg_2.jpg') }});">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">
                    <div class="col-md-8 col-sm-12 text-center ftco-animate">
                        <span class="subheading">Chào mừng</span>
                        <h1 class="mb-4">Hương vị tuyệt vời & Không gian đẹp</h1>
                        <p class="mb-4 mb-md-5">Khám phá thế giới cà phê với chúng tôi.</p>
                        <p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary p-3 px-xl-4 py-xl-3">Đặt hàng ngay</a>
                            <a href="{{ route('products.index') }}" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">Xem sản phẩm</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="slider-item" style="background-image: url({{ asset('images/bg_3.jpg') }});">
            <div class="overlay"></div>
            <div class="container">
                <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">
                    <div class="col-md-8 col-sm-12 text-center ftco-animate">
                        <span class="subheading">Chào mừng</span>
                        <h1 class="mb-4">Nóng hổi và sẵn sàng phục vụ</h1>
                        <p class="mb-4 mb-md-5">Những ly cà phê thơm ngon được pha chế tận tâm.</p>
                        <p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary p-3 px-xl-4 py-xl-3">Đặt hàng ngay</a>
                            <a href="{{ route('products.index') }}" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">Xem sản phẩm</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>

{{-- Info Bar --}}
<section class="ftco-intro">
    <div class="container-wrap">
        <div class="wrap d-md-flex align-items-xl-end">
            <div class="info">
                <div class="row no-gutters">
                    <div class="col-md-4 d-flex ftco-animate">
                        <div class="icon"><span class="icon-phone"></span></div>
                        <div class="text">
                            <h3>+84 978 853 110</h3>
                            <p>Liên hệ với chúng tôi bất cứ lúc nào</p>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex ftco-animate">
                        <div class="icon"><span class="icon-my_location"></span></div>
                        <div class="text">
                            <h3>93 Lê Cao Lãng</h3>
                            <p>Quận Tân Phú, TP.HCM, Việt Nam</p>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex ftco-animate">
                        <div class="icon"><span class="icon-clock-o"></span></div>
                        <div class="text">
                            <h3>Mở cửa Thứ 2 - Chủ nhật</h3>
                            <p>8:00AM - 21:00PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- About --}}
<section class="ftco-about d-md-flex">
    <div class="one-half img" style="background-image: url({{ asset('images/about.jpg') }});"></div>
    <div class="one-half ftco-animate">
        <div class="overlap">
            <div class="heading-section ftco-animate">
                <span class="subheading">Khám phá</span>
                <h2 class="mb-4">Câu chuyện của chúng tôi</h2>
            </div>
            <div>
                <p>Chúng tôi bắt đầu với niềm đam mê về cà phê và mong muốn mang đến những trải nghiệm tuyệt vời nhất cho khách hàng. Với nhiều năm kinh nghiệm, chúng tôi tự hào về chất lượng sản phẩm và dịch vụ của mình.</p>
            </div>
        </div>
    </div>
</section>

{{-- Services --}}
<section class="ftco-section ftco-services">
    <div class="container">
        <div class="row">
            <div class="col-md-4 ftco-animate">
                <div class="media d-block text-center block-6 services">
                    <div class="icon d-flex justify-content-center align-items-center mb-5">
                        <span class="flaticon-choices"></span>
                    </div>
                    <div class="media-body">
                        <h3 class="heading">Dễ dàng đặt hàng</h3>
                        <p>Quy trình đặt hàng đơn giản và nhanh chóng, chỉ vài cú click.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 ftco-animate">
                <div class="media d-block text-center block-6 services">
                    <div class="icon d-flex justify-content-center align-items-center mb-5">
                        <span class="flaticon-delivery-truck"></span>
                    </div>
                    <div class="media-body">
                        <h3 class="heading">Giao hàng nhanh</h3>
                        <p>Dịch vụ giao hàng nhanh chóng và đảm bảo chất lượng.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 ftco-animate">
                <div class="media d-block text-center block-6 services">
                    <div class="icon d-flex justify-content-center align-items-center mb-5">
                        <span class="flaticon-coffee-bean"></span>
                    </div>
                    <div class="media-body">
                        <h3 class="heading">Cà phê chất lượng</h3>
                        <p>Chúng tôi chỉ sử dụng những hạt cà phê tốt nhất.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Featured Products --}}
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
            <div class="col-md-7 heading-section ftco-animate text-center">
                <h1 class="mb-4">SẢN PHẨM BÁN CHẠY</h1>
                <p>Những sản phẩm được yêu thích nhất của chúng tôi.</p>
            </div>
        </div>
        <div class="row">
            @forelse($featuredProducts as $product)
                <div class="col-md-3">
                    <div class="menu-entry">
                        <a href="{{ route('products.show', $product->slug) }}"
                            class="img {{ $product->stock == 0 ? 'out-of-stock-img' : '' }}"
                            style="background-image: url({{ $product->image ? asset($product->image) : asset('images/menu-1.jpg') }}); position: relative;">
                            @if($product->stock == 0)
                                <div class="out-of-stock-badge">Hết hàng</div>
                            @endif
                            @if($product->discount_price)
                                @php
                                    $discountPct = round((($product->price - $product->discount_price) / $product->price) * 100);
                                @endphp
                                <div style="position:absolute;top:10px;right:10px;background:#c49b63;color:white;padding:5px 12px;border-radius:20px;font-size:13px;font-weight:700;box-shadow:0 2px 10px rgba(196,155,99,0.4);">
                                    -{{ $discountPct }}%
                                </div>
                            @endif
                        </a>
                        <div class="text text-center pt-4">
                            <h3>
                                <a href="{{ route('products.show', $product->slug) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p>{{ Str::limit($product->description, 60) }}</p>
                            @if($product->discount_price)
                                <p class="price">
                                    <span style="text-decoration:line-through;color:#999;font-size:14px;margin-right:5px;">
                                        {{ number_format($product->price, 0, ',', '.') }}đ
                                    </span>
                                    <span style="color:#c49b63;font-weight:700;">
                                        {{ number_format($product->discount_price, 0, ',', '.') }}đ
                                    </span>
                                </p>
                            @else
                                <p class="price"><span>{{ number_format($product->price, 0, ',', '.') }}đ</span></p>
                            @endif
                            <p>
                                @if($product->stock == 0)
                                    <button class="btn btn-primary btn-outline-primary" disabled>Hết hàng</button>
                                @else
                                    <button class="btn btn-primary btn-outline-primary btn-add-to-cart"
                                        data-product-id="{{ $product->id }}">
                                        Thêm vào giỏ
                                    </button>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>Chưa có sản phẩm nào.</p>
                </div>
            @endforelse
        </div>
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('products.index') }}" class="btn btn-primary px-4 py-3">Xem tất cả sản phẩm</a>
            </div>
        </div>
    </div>
</section>

{{-- Counter --}}
<section class="ftco-counter ftco-bg-dark img" id="section-counter"
    style="background-image: url({{ asset('images/bg_2.jpg') }});" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center">
                            <div class="text">
                                <div class="icon"><span class="flaticon-coffee-cup"></span></div>
                                <strong class="number" data-number="100">0</strong>
                                <span>Chi nhánh</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center">
                            <div class="text">
                                <div class="icon"><span class="flaticon-coffee-cup"></span></div>
                                <strong class="number" data-number="85">0</strong>
                                <span>Giải thưởng</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center">
                            <div class="text">
                                <div class="icon"><span class="flaticon-coffee-cup"></span></div>
                                <strong class="number" data-number="10567">0</strong>
                                <span>Khách hàng hài lòng</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center">
                            <div class="text">
                                <div class="icon"><span class="flaticon-coffee-cup"></span></div>
                                <strong class="number" data-number="900">0</strong>
                                <span>Nhân viên</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Categories Section --}}
<section class="ftco-section ftco-bg-dark img" style="background-image: url({{ asset('images/bg_4.jpg') }});">
    <div class="overlay"></div>
    <div class="container" style="position:relative;z-index:2;">
        <div class="row justify-content-center mb-5 pb-3">
            <div class="col-md-7 heading-section ftco-animate text-center">
                <h2 class="mb-4" style="color:#fff;">DANH MỤC SẢN PHẨM</h2>
                <p style="color:rgba(255,255,255,0.75);">Khám phá các loại sản phẩm đa dạng của chúng tôi.</p>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach($categories as $category)
                <div class="col-md-3 col-sm-6 mb-4">
                    <a href="{{ route('categories.show', $category->slug) }}" class="text-decoration-none">
                        <div class="text-center h-100 category-card-dark">
                            <div style="font-size:48px;margin-bottom:15px;line-height:1;">
                                @switch($category->name)
                                    @case('Cà phê') ☕ @break
                                    @case('Hạt cà phê') 🫘 @break
                                    @case('Nước trái cây') 🥤 @break
                                    @case('Bánh ngọt') 🍰 @break
                                    @default 🛍️
                                @endswitch
                            </div>
                            <h5 style="color:#c49b63;font-weight:700;margin-bottom:8px;">{{ $category->name }}</h5>
                            <p style="color:rgba(255,255,255,0.6);font-size:13px;margin-bottom:0;">{{ $category->description }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
