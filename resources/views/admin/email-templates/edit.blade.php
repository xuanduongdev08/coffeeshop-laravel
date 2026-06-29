@extends('layouts.admin')

@section('title', 'Chỉnh sửa Email Template')
@section('page-title', 'Chỉnh sửa Email Template')

@section('content')
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="admin-card">
            <h5>Cấu hình mẫu email: <code>{{ $emailTemplate->template_key }}</code></h5>
            
            <form action="{{ route('admin.email-templates.update', $emailTemplate) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label for="subject" style="font-size:13px;font-weight:600;">Tiêu đề Email (Subject)</label>
                    <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject', $emailTemplate->subject) }}" required style="border-radius:8px;">
                    @error('subject') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="content" style="font-size:13px;font-weight:600;">Nội dung HTML (Content)</label>
                    <textarea name="content" id="content" class="form-control" rows="12" required style="border-radius:8px;font-family:monospace;font-size:13px;line-height:1.5;background:#fafafa;">{{ old('content', $emailTemplate->content) }}</textarea>
                    @error('content') <small class="text-danger">{{ $message }}</small> @enderror
                    <small class="form-text text-muted mt-2">Hỗ trợ định dạng HTML. Bạn có thể chèn các thẻ <code>&lt;p&gt;</code>, <code>&lt;strong&gt;</code>, <code>&lt;a&gt;</code>,... để làm email đẹp mắt hơn.</small>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-coffee" style="border-radius:8px;padding:8px 24px;">
                        Lưu thay đổi
                    </button>
                    <a href="{{ route('admin.email-templates.index') }}" class="btn btn-light" style="border-radius:8px;padding:8px 24px;border:1px solid #ddd;">
                        Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <h5 style="border-bottom:1px solid rgba(0,0,0,0.08);padding-bottom:10px;margin-bottom:15px;font-size:14px;color:var(--coffee);">Các biến placeholder khả dụng</h5>
            <p class="text-muted small">Các ký hiệu dưới đây sẽ tự động được thay bằng dữ liệu thực tế khi gửi thư. Vui lòng copy chính xác ký hiệu:</p>
            
            <div class="d-flex flex-column gap-3 mt-3">
                @foreach($placeholders as $ph)
                    <div style="background:#fcfcfc;border:1px solid rgba(0,0,0,0.04);border-radius:8px;padding:10px 12px;">
                        <code style="background:var(--coffee-cream);color:var(--coffee);padding:2px 6px;border-radius:4px;font-size:13px;font-weight:600;display:inline-block;margin-bottom:4px;">{{ $ph }}</code>
                        <div class="text-muted" style="font-size:11.5px;">
                            @if($ph === '{customer_name}') Tên hiển thị của khách hàng.
                            @elseif($ph === '{customer_email}') Email tài khoản đăng nhập của khách hàng.
                            @elseif($ph === '{website_link}') Đường dẫn link trang chủ cửa hàng.
                            @elseif($ph === '{order_code}') Mã tra cứu đơn hàng (ví dụ: XD0012).
                            @elseif($ph === '{order_status}') Trạng thái đơn hàng (Chờ xử lý, Đang giao,...).
                            @elseif($ph === '{shipping_address}') Địa chỉ giao nhận hàng của khách.
                            @elseif($ph === '{total_price}') Tổng giá trị đơn hàng (bao gồm phí vận chuyển).
                            @elseif($ph === '{order_link}') Đường dẫn xem chi tiết đơn hàng ở trang cá nhân.
                            @elseif($ph === '{drink_status_label}') Trạng thái pha chế (Đang pha chế, Hoàn thành).
                            @elseif($ph === '{extra_note}') Ghi chú bổ sung tự động: "đang pha chế" hoặc "đã hoàn thành và sẵn sàng giao".
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="admin-card">
            <h5 style="border:none;padding:0;margin-bottom:10px;font-size:14px;color:var(--coffee);">Mô tả chức năng</h5>
            <p style="font-size:12.5px;color:#555;line-height:1.5;margin-bottom:0;">
                {{ $emailTemplate->description }}
            </p>
        </div>
    </div>
</div>
@endsection
