<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'XDTHECOFFEEHOUSE') - Coffee Shop</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS Assets --}}
    <link rel="stylesheet" href="{{ asset('css/open-iconic-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.timepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icomoon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style_custom.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/cafeai.css') }}?v={{ time() }}">

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Cropper.js --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    @stack('styles')
</head>
<body>

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Flash Messages --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ icon: 'success', title: 'Thành công!', text: '{{ session('success') }}', timer: 2500, showConfirmButton: false, confirmButtonColor: '#c49b63' });
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ icon: 'error', title: 'Lỗi!', text: '{{ session('error') }}', confirmButtonColor: '#c49b63' });
            });
        </script>
    @endif
    @if(session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ icon: 'warning', title: 'Thông báo', text: '{{ session('warning') }}', confirmButtonColor: '#c49b63' });
            });
        </script>
    @endif

    {{-- Page Content --}}
    @yield('content')

    {{-- Footer --}}
    @include('components.footer')

    {{-- JS Assets --}}
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery-migrate-3.0.1.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.easing.1.3.js') }}"></script>
    <script src="{{ asset('js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('js/jquery.stellar.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('js/aos.js') }}"></script>
    <script src="{{ asset('js/jquery.animateNumber.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('js/jquery.timepicker.min.js') }}"></script>
    <script src="{{ asset('js/scrollax.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/cafeai.js') }}?v={{ time() }}"></script>

    {{-- Global JS: Add to Cart AJAX --}}
    <script>
    var isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
    var csrfToken = '{{ csrf_token() }}';

    $(document).ready(function() {
        // Back to top
        var backToTop = $('.back-to-top');
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) { backToTop.addClass('active'); }
            else { backToTop.removeClass('active'); }
        });
        backToTop.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: 0}, '300');
        });

        // Add to Cart AJAX
        $(document).on('click', '.btn-add-to-cart', function(e) {
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
                }).then((result) => {
                    if (result.isConfirmed) { window.location.href = '{{ route('register') }}'; }
                    else if (result.isDenied) { window.location.href = '{{ route('login') }}'; }
                });
                return;
            }

            var productId = $(this).data('product-id');
            var quantity = $('#quantity').length ? parseInt($('#quantity').val()) : 1;

            $.ajax({
                url: '{{ route('cart.add') }}',
                method: 'POST',
                data: { product_id: productId, quantity: quantity, _token: csrfToken },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Thành công!', text: response.message, timer: 1500, showConfirmButton: false });
                        $('.bag small').text(response.cart_count);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Lỗi', text: response.message, confirmButtonColor: '#c49b63' });
                    }
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Không thể kết nối server', confirmButtonColor: '#c49b63' });
                }
            });
        });

        // Polling check order updates
        if (isLoggedIn) {
            function checkOrderUpdates() {
                $.ajax({
                    url: '{{ route('orders.check-updates') }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(orders) {
                        if (!orders || orders.length === 0) return;
                        
                        orders.forEach(function(order) {
                            var statusKey = 'order_status_' + order.id;
                            var drinkKey = 'order_drink_' + order.id;
                            
                            var oldStatus = localStorage.getItem(statusKey);
                            var oldDrink = localStorage.getItem(drinkKey);
                            
                            // Khởi tạo trạng thái ban đầu để tránh hiển thị thông báo ngay khi tải trang
                            if (oldStatus === null) {
                                localStorage.setItem(statusKey, order.status);
                                localStorage.setItem(drinkKey, order.drink_status || '');
                                return;
                            }
                            
                            // Kiểm tra thay đổi trạng thái đơn hàng
                            if (order.status !== oldStatus) {
                                localStorage.setItem(statusKey, order.status);
                                Swal.fire({
                                    title: 'Cập nhật đơn hàng!',
                                    text: 'Đơn hàng #' + order.tracking_code + ' của bạn đã thay đổi sang: ' + order.status,
                                    icon: 'info',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 6000,
                                    timerProgressBar: true
                                });
                            }
                            
                            // Kiểm tra thay đổi trạng thái pha chế
                            if (order.drink_status !== oldDrink) {
                                localStorage.setItem(drinkKey, order.drink_status || '');
                                var drinkLabel = '';
                                if (order.drink_status === 'brewing') {
                                    drinkLabel = 'Đang pha chế';
                                } else if (order.drink_status === 'completed') {
                                    drinkLabel = 'Pha chế xong';
                                }
                                
                                if (drinkLabel) {
                                    Swal.fire({
                                        title: 'Pha chế đồ uống!',
                                        text: 'Đơn hàng #' + order.tracking_code + ': ' + drinkLabel,
                                        icon: 'success',
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 6000,
                                        timerProgressBar: true
                                    });
                                }
                            }
                        });
                    }
                });
            }
            
            // Chạy ngay lập tức và sau đó lặp lại mỗi 15 giây
            checkOrderUpdates();
            setInterval(checkOrderUpdates, 15000);
        }
    });
    </script>

    @stack('scripts')

</body>
</html>
