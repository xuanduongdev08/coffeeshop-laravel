@extends('layouts.shop')

@section('title', 'Giỏ hàng')

@section('content')

{{-- Page Header --}}
<section class="hero-page-header" style="background-image: url({{ asset('images/bg_3.jpg') }}); background-size: cover; background-position: center; height: 350px;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <h1 class="mb-3 mt-5 bread">Giỏ hàng</h1>
                    <p class="breadcrumbs">
                        <span class="mr-2"><a href="{{ route('home') }}">Trang chủ</a></span>
                        <span>Giỏ hàng</span>
                    </p>
                </div>
        </div>
    </div>
</section>

<section class="ftco-section ftco-cart">
    <div class="container">
        @if(!empty($cart))
            <div class="row">
                <div class="col-md-12 ftco-animate">
                    <div class="cart-list">
                        <table class="table">
                            <thead class="thead-primary">
                                <tr class="text-center">
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody id="cart-tbody">
                                @foreach($cart as $rowId => $item)
                                    <tr class="text-center" id="row-{{ $rowId }}">
                                        <td class="product-remove">
                                            <a href="#" class="remove-item-btn" data-row-id="{{ $rowId }}">
                                                <span class="icon-close"></span>
                                            </a>
                                        </td>
                                        <td class="image-prod">
                                            <div class="img" style="background-image: url({{ $item['image'] ? asset($item['image']) : asset('images/menu-1.jpg') }});"></div>
                                        </td>
                                        <td class="product-name">
                                            <h3>
                                                <a href="{{ route('products.show', $item['slug']) }}" style="color:inherit;">
                                                    {{ $item['name'] }}
                                                </a>
                                            </h3>
                                            @if(!empty($item['size']))
                                                <small class="text-muted">Size: <strong>{{ $item['size'] }}</strong></small>
                                            @endif
                                            @if(!empty($item['modifier_names']))
                                                <br><small class="text-muted">{{ $item['modifier_names'] }}</small>
                                            @endif
                                        </td>
                                        <td class="price">
                                            <span>{{ number_format($item['unit_price'] ?? $item['price'], 0, ',', '.') }}đ</span>
                                            @if(!empty($item['modifier_extra']) && $item['modifier_extra'] > 0)
                                                <br><small class="text-muted" style="font-size:11px;">
                                                    Gốc: {{ number_format($item['base_price'], 0, ',', '.') }}đ
                                                    + {{ number_format($item['modifier_extra'], 0, ',', '.') }}đ
                                                </small>
                                            @endif
                                        </td>
                                        <td class="quantity">
                                            <div class="input-group mb-3 justify-content-center">
                                                <input type="number"
                                                    class="quantity form-control input-number cart-qty-input"
                                                    data-row-id="{{ $rowId }}"
                                                    value="{{ $item['quantity'] }}"
                                                    min="1" max="100"
                                                    style="max-width:80px;">
                                            </div>
                                        </td>
                                        <td class="total item-total" id="total-{{ $rowId }}">
                                            {{ number_format(($item['unit_price'] ?? $item['price']) * $item['quantity'], 0, ',', '.') }}đ
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row justify-content-end">
                <div class="col col-lg-3 col-md-6 mt-5 cart-wrap ftco-animate">
                    <div class="cart-total mb-3">
                        <h3>Tổng giỏ hàng</h3>
                        <p class="d-flex">
                            <span>Tạm tính</span>
                            <span id="cart-subtotal">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </p>
                        <p class="d-flex">
                            <span>Phí vận chuyển</span>
                            <span>Tính khi đặt hàng</span>
                        </p>
                        <hr>
                        <p class="d-flex total-price">
                            <span>Tổng cộng</span>
                            <span id="cart-total">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </p>
                    </div>
                    @auth
                        <p class="text-center">
                            <a href="{{ route('orders.checkout') }}" class="btn btn-primary py-3 px-4">Thanh toán</a>
                        </p>
                    @else
                        <p class="text-center">
                            <a href="{{ route('login') }}" class="btn btn-primary py-3 px-4">Đăng nhập để thanh toán</a>
                        </p>
                    @endauth
                    <p class="text-center">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary py-3 px-4">Tiếp tục mua sắm</a>
                    </p>
                    <p class="text-center">
                        <a href="#" id="clear-cart-btn" class="btn btn-danger py-3 px-4">Xóa tất cả</a>
                    </p>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12 text-center py-5 my-4">
                    <h3 class="mb-3" style="color: #fff;">Giỏ hàng của bạn đang trống</h3>
                    <p class="text-muted mb-4">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary py-3 px-5">Tiếp tục mua sắm</a>
                </div>
            </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
var csrfToken = '{{ csrf_token() }}';

document.addEventListener('DOMContentLoaded', function() {
    // Cập nhật số lượng khi thay đổi input
    document.querySelectorAll('.cart-qty-input').forEach(function(input) {
        var timer;
        input.addEventListener('change', function() {
            clearTimeout(timer);
            var rowId = this.dataset.rowId;
            var qty = parseInt(this.value);
            if (isNaN(qty) || qty < 1) { this.value = 1; qty = 1; }

            timer = setTimeout(function() {
                fetch('/gio-hang/cap-nhat/' + rowId, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ quantity: qty })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('total-' + rowId).textContent = data.item_total;
                        document.getElementById('cart-subtotal').textContent = data.cart_total;
                        document.getElementById('cart-total').textContent = data.cart_total;
                        document.querySelector('.bag small').textContent = data.cart_count;
                    } else {
                        Swal.fire({ icon: 'error', title: 'Lỗi', text: data.message, confirmButtonColor: '#c49b63' });
                    }
                });
            }, 500);
        });
    });

    // Xóa từng sản phẩm
    document.querySelectorAll('.remove-item-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var rowId = this.dataset.rowId;
            Swal.fire({
                title: 'Xóa sản phẩm?',
                text: 'Sản phẩm sẽ bị xóa khỏi giỏ hàng!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c49b63',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then(function(result) {
                if (result.isConfirmed) {
                    fetch('/gio-hang/xoa/' + rowId, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('row-' + rowId).remove();
                            document.getElementById('cart-subtotal').textContent = data.cart_total;
                            document.getElementById('cart-total').textContent = data.cart_total;
                            document.querySelector('.bag small').textContent = data.cart_count;
                            if (data.is_empty) location.reload();
                        }
                    });
                }
            });
        });
    });

    // Xóa tất cả
    var clearBtn = document.getElementById('clear-cart-btn');
    if (clearBtn) {
        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Xóa toàn bộ giỏ hàng?',
                text: 'Tất cả sản phẩm sẽ bị xóa!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c49b63',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then(function(result) {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('cart.clear') }}';
                }
            });
        });
    }
});
</script>
@endpush
