@extends('layouts.shop')

@section('title', $currentCategory ? $currentCategory->name : 'Sản phẩm')

@section('content')

{{-- Page Header --}}
<section class="hero-page-header" style="background-image: url({{ asset('images/bg_3.jpg') }}); background-size: cover; background-position: center; height: 350px; position: relative;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-md-7 col-sm-12 text-center ftco-animate" style="padding-top: 60px;">
                <h1 class="mb-3 bread">{{ $currentCategory ? $currentCategory->name : 'Sản phẩm' }}</h1>
                <p class="breadcrumbs">
                    <span class="mr-2"><a href="{{ route('home') }}">Trang chủ</a></span>
                    @if($currentCategory)
                        <span class="mr-2"><a href="{{ route('products.index') }}">Sản phẩm</a></span>
                        <span>{{ $currentCategory->name }}</span>
                    @else
                        <span>Sản phẩm</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Products Section --}}
<section class="ftco-section">
    <div class="container">
        {{-- Filter Bar --}}
        <div class="row mb-4 d-flex align-items-center">
            <div class="col-md-4">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="categoryDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="oi oi-menu"></span>
                        {{ $currentCategory ? $currentCategory->name : 'Danh mục' }}
                    </button>
                    <div class="dropdown-menu" aria-labelledby="categoryDropdown">
                        <a class="dropdown-item {{ !$currentCategory ? 'active' : '' }}"
                            href="{{ route('products.index') }}">Tất cả danh mục</a>
                        @foreach($categories as $cat)
                            <a class="dropdown-item {{ $currentCategory && $currentCategory->id == $cat->id ? 'active' : '' }}"
                                href="{{ route('categories.show', $cat->slug) }}">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <form method="GET" action="{{ route('products.index') }}" class="form-inline">
                    <input type="text" name="search" class="form-control mr-2"
                        placeholder="Tìm kiếm sản phẩm..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Tìm</button>
                </form>
            </div>
            <div class="col-md-4 d-flex justify-content-end">
                <form method="GET" action="{{ route('products.index') }}" class="form-inline">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <select name="sort" class="form-control mr-2" onchange="this.form.submit()">
                        <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                        <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                        <option value="name" {{ $sort == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                    </select>
                </form>
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="row">
            @forelse($products as $product)
                <div class="col-md-3 mb-4 product-item-col" id="product-{{ $product->id }}">
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
                                <div style="position:absolute;top:10px;right:10px;background:#c49b63;color:white;padding:5px 12px;border-radius:20px;font-size:13px;font-weight:700;">
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
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Không tìm thấy sản phẩm nào.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Xem tất cả sản phẩm</a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="row mt-5">
                <div class="col-12 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    </div>
</section>

@endsection
