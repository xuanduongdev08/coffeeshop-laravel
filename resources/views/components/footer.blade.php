<footer class="ftco-footer ftco-section img">
    <div class="overlay"></div>
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">Về chúng tôi</h2>
                    <p>Chúng tôi cam kết mang đến những sản phẩm chất lượng nhất với dịch vụ tốt nhất cho khách hàng.</p>
                    <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                        <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                        <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                        <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-5 mb-md-5">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">Liên kết nhanh</h2>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="py-2 d-block">Trang chủ</a></li>
                        <li><a href="{{ route('products.index') }}" class="py-2 d-block">Sản phẩm</a></li>
                        <li><a href="{{ route('cart.index') }}" class="py-2 d-block">Giỏ hàng</a></li>
                        @auth
                            <li><a href="{{ route('orders.history') }}" class="py-2 d-block">Đơn hàng của tôi</a></li>
                            <li><a href="{{ route('profile.show') }}" class="py-2 d-block">Tài khoản</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="py-2 d-block">Đăng nhập</a></li>
                            <li><a href="{{ route('register') }}" class="py-2 d-block">Đăng ký</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-5 mb-md-5">
                <div class="ftco-footer-widget mb-4 ml-md-4">
                    <h2 class="ftco-heading-2">Dịch vụ</h2>
                    <ul class="list-unstyled">
                        <li><a href="#" class="py-2 d-block">Giao hàng</a></li>
                        <li><a href="#" class="py-2 d-block">Chất lượng</a></li>
                        <li><a href="#" class="py-2 d-block">Hỗ trợ</a></li>
                        <li><a href="#" class="py-2 d-block">Liên hệ</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5 mb-md-5">
                <div class="ftco-footer-widget mb-4">
                    <h2 class="ftco-heading-2">Có câu hỏi?</h2>
                    <div class="block-23 mb-3">
                        <ul>
                            <li><span class="icon icon-map-marker"></span><span class="text">93 Lê Cao Lãng, Quận Tân Phú, TP.HCM</span></li>
                            <li><a href="tel:+84978853110"><span class="icon icon-phone"></span><span class="text">+84 978 853 110</span></a></li>
                            <li><a href="mailto:dn250621@coffeeshop.com"><span class="icon icon-envelope"></span><span class="text">dn250621@coffeeshop.com</span></a></li>
                        </ul>
                    </div>
                    {{-- Google Maps Embed --}}
                    <div class="mt-3" style="position:relative; width:100%; padding-bottom:60%; height:0; overflow:hidden; border-radius:8px; opacity:0.85;">
                        <iframe
                            src="https://maps.google.com/maps?width=600&height=400&hl=vi&q=93%20%C4%90.%20L%C3%AA%20Cao%20L%C3%A3ng%2C%20Ph%C3%BA%20Th%E1%BA%A1nh%2C%20H%E1%BB%93%20Ch%C3%AD%20Minh%2C%20Vi%E1%BB%87t%20Nam&t=&z=19&ie=UTF8&iwloc=B&output=embed"
                            frameborder="0"
                            scrolling="no"
                            marginheight="0"
                            marginwidth="0"
                            style="position:absolute; top:0; left:0; width:100%; height:100%; border:0; border-radius:8px;"
                            loading="lazy"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <p>Copyright &copy; {{ date('Y') }} All rights reserved | XDTHECOFFEEHOUSE</p>
            </div>
        </div>
    </div>
</footer>

{{-- Loader --}}
<div id="ftco-loader" class="show fullscreen">
    <svg class="circular" width="48px" height="48px">
        <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
        <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/>
    </svg>
</div>

{{-- Back to top --}}
<a href="#" class="back-to-top"><span class="icon-arrow-up"></span></a>
