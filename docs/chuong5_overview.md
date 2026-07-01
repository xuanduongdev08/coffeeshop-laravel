# CHƯƠNG 5: ĐÁNH GIÁ VÀ KẾT LUẬN

## 5.1. Phân tích kết quả đạt được và hạn chế

### 5.1.1. Kết quả đạt được
*   **Xây dựng website bán cà phê và quản lý cửa hàng trực quan, dễ sử dụng:**
    *   *Giao diện phía khách hàng:* Được xây dựng bằng **Blade Template + Bootstrap 4.5**, tối ưu trải nghiệm người dùng (UX) thông qua các hiệu ứng mượt mà (như Owl Carousel ở trang chủ), menu phân loại sản phẩm rõ ràng, các popup SweetAlert2 thân thiện và luồng mua hàng ngắn gọn.
    *   *Giao diện phía quản trị (Admin Panel):* Phát triển dựa trên nền tảng **Filament PHP** và **TailwindCSS** với tone màu nâu đen & vàng đồng sang trọng (*Coffee Premium Theme*), giúp nhân viên dễ dàng cập nhật thông tin và theo dõi hoạt động kinh doanh trực quan.
*   **Xây dựng hoàn thiện các chức năng cốt lõi cho khách hàng:**
    *   *Đặt hàng & Tùy biến đồ uống:* Hỗ trợ chọn size (M, L, XL) đi kèm giá riêng biệt, các modifier lựa chọn mức đường, đá, sữa, topping linh hoạt.
    *   *Theo dõi đơn hàng (Real-time tracking):* Người dùng có thể theo dõi tiến trình đơn hàng (Chờ xử lý &rarr; Đang giao &rarr; Hoàn thành) và trạng thái pha chế (*Drink Status*) trực quan.
    *   *Tích hợp AI & Chatbot thông minh:* Trợ lý ảo **CaféAI** tích hợp mô hình **Gemini 2.0 Flash**  giúp tự động nhận diện ngôn ngữ, tư vấn đồ uống theo thời tiết (OpenWeatherMap API) hoặc tâm trạng, hỗ trợ kiểm tra trạng thái đơn hàng và cho phép thêm nhanh sản phẩm vào giỏ ngay từ khung chat.
    *   *Cổng thanh toán đa dạng:* Tích hợp thanh toán COD, quét mã **VietQR** động (tự động nhận diện thanh toán qua Casso/SePay webhook), ví điện tử **MoMo** và cổng thanh toán quốc tế **PayPal** 
    *   *Xác thực & Tiện ích:* Hỗ trợ đăng nhập nhanh bằng tài khoản Google (Google OAuth2 via Socialite), hệ thống thông báo realtime (Notification Bell) in-app và gửi email thông báo tự động (Email HTML chuyên nghiệp).
*   **Xây dựng phần quản trị linh hoạt với hệ thống phân quyền chi tiết (RBAC):**
    *   Quản lý phân quyền chặt chẽ thông qua **Spatie Laravel Permission** chia làm 5 vai trò: *Admin* (quản lý toàn diện), *Staff* (nhân viên pha chế), *Cashier* (thu ngân), *Warehouse* (nhân viên kho) và *Customer* (khách hàng).
    *   Bảng quản trị tích hợp các biểu đồ thống kê doanh thu (Chart.js), quản lý tồn kho sản phẩm (Soft Delete bảo vệ dữ liệu), tùy biến nội dung email động (Email Templates), và xuất báo cáo tài chính ra file Excel.
    *   Phân loại đánh giá và feedback của khách hàng bằng API Text Classification giúp ban quản trị nhanh chóng phê duyệt review sản phẩm chất lượng.
*   **Sử dụng công nghệ hiện đại giúp tối ưu hiệu năng và trải nghiệm:**
    *   Hệ thống chạy trên nền tảng **Laravel 11** và **PHP 8.x**, kết hợp cơ chế load asset qua **Vite** giúp giảm thời gian phản hồi của trang web.
*   **Tối ưu hóa quy trình đặt hàng và tìm kiếm thực đơn:**
    *   Thanh tìm kiếm thông minh kết hợp bộ lọc danh mục giúp khách hàng nhanh chóng tìm thấy sản phẩm mong muốn mà không gặp khó khăn về giao diện.

### 5.1.2. Hạn chế (Nhược điểm)
*   **Chưa tối ưu được tốc độ tải các dữ liệu:** Hệ thống chưa triển khai giải pháp bộ nhớ đệm (Caching như Redis/Memcached) cho các truy vấn dữ liệu tĩnh như danh mục sản phẩm, dẫn đến việc tăng tải cho MySQL database khi có nhiều người truy cập cùng lúc.
*   **Cần cải thiện và bổ sung thêm tính năng:** Hệ thống quản lý mã giảm giá (Coupon/Voucher) còn ở mức cơ bản, chưa hỗ trợ các kịch bản khuyến mãi phức tạp (như giảm giá theo khung giờ vàng, coupon cá nhân hóa theo từng khách hàng).
*   **Chưa tích hợp được đa dạng phương thức thanh toán thực tế:** Cổng thanh toán MoMo và PayPal hiện tại vẫn đang hoạt động trên môi trường thử nghiệm (Sandbox), chưa đưa vào vận hành thực tế do yêu cầu về mặt pháp lý và giấy phép đăng ký kinh doanh của cửa hàng.
*   **Giao diện người dùng trên thiết bị di động cần cải thiện thêm:** Mặc dù đã hoàn thiện đáp ứng cơ bản (Responsive), tuy nhiên một số cấu phần hiển thị bảng thống kê ở Admin Panel và các form tùy biến đồ uống (Modifiers) trên các thiết bị màn hình quá nhỏ đôi khi bị tràn dòng hoặc khó bấm nút.
*   **Xử lý chatbot CaféAI chưa được chặt chẽ:** Cơ chế nhận diện ý định khách hàng (Intent Detection) bằng biểu thức chính quy (Regex) ở local đôi khi bỏ sót từ khóa đồng nghĩa. Khi fallback qua Gemini API, thời gian phản hồi đôi khi còn chậm do phụ thuộc vào hạ tầng mạng của bên thứ ba.

---

## 5.2. Kết luận

Trong vòng 2 tháng thực hiện đồ án, dự án website quản lý cửa hàng cà phê trực tuyến **XDTHECOFFEEHOUSE** đã đạt được những kết quả cơ bản. Hệ thống đã xây dựng hoàn thiện các chức năng cốt lõi đáp ứng nhu cầu thực tế của khách hàng và ban quản trị. Nhờ có sự hướng dẫn tận tình của cô **Nguyễn Thị Mai Trang** và sự nỗ lực tự nghiên cứu của bản thân, em đã hoàn thành đồ án ngành đúng tiến độ đề ra. Cụ thể:

*   **Về mặt sản phẩm & kỹ thuật:**
    1.  Hoàn thành xây dựng trọn vẹn mã nguồn ứng dụng web bán hàng theo kiến trúc **MVC** hiện đại trên nền tảng **Laravel 11**.
    2.  Thiết kế và chuẩn hóa cơ sở dữ liệu quan hệ **MySQL** với 14 bảng dữ liệu logic, tối ưu hóa quan hệ khóa ngoại (foreign key) và chỉ mục để truy vấn dữ liệu hiệu quả.
    3.  Tích hợp thành công các dịch vụ API hiện đại như **Google Gemini API** (chatbot hỗ trợ thông minh), **VietQR API** (tạo mã thanh toán tự động), **PayPal SDK** và **OpenWeatherMap API** (gợi ý đồ uống theo thời tiết thực tế).
    4.  Xây dựng trang quản trị thông minh phân quyền chặt chẽ (RBAC) với Filament, có thống kê trực quan và bộ lọc đa năng giúp kiểm soát doanh thu chặt chẽ.
*   **Về mặt kiến thức & kỹ năng đạt được:**
    1.  Nắm vững quy trình phát triển dự án thực tế bằng PHP hiện đại và các kỹ thuật tối ưu hóa trong framework Laravel.
    2.  Nâng cao kỹ năng làm việc với các hệ thống API của bên thứ ba, hiểu rõ luồng xử lý Webhook và Polling an toàn để đảm bảo giao dịch tài chính không bị lỗi.
    3.  Biết cách ứng dụng AI và xử lý ngôn ngữ tự nhiên (NLP) vào sản phẩm thực tế để tăng tính cạnh tranh cho ứng dụng thương mại điện tử.
*   **Hướng phát triển tương lai:**
    1.  Nghiên cứu áp dụng Redis Cache để cải thiện tốc độ phản hồi hệ thống khi quy mô dữ liệu và lượt truy cập tăng cao.
    2.  Hoàn thiện cấu hình cổng thanh toán thật khi đưa website vào chạy thực tế.
    3.  Phát triển thêm ứng dụng di động (Mobile App) đồng bộ dữ liệu qua API để khách hàng có thể quét mã gọi món ngay tại bàn trong cửa hàng vật lý.
