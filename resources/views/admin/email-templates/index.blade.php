@extends('layouts.admin')

@section('title', 'Quản lý Email Template')
@section('page-title', 'Quản lý Email Template')

@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1" style="border:none;padding:0;">Mẫu email tự động</h5>
            <p class="text-muted small mb-0">Quản lý và điều chỉnh nội dung các email tự động gửi cho khách hàng.</p>
        </div>
    </div>

    <div class="admin-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Mã Template (Key)</th>
                    <th>Tiêu đề (Subject)</th>
                    <th>Mô tả</th>
                    <th>Cập nhật lúc</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $template)
                    <tr>
                        <td>
                            <code style="background:var(--coffee-cream);color:var(--coffee);padding:3px 8px;border-radius:4px;font-size:12px;">
                                {{ $template->template_key }}
                            </code>
                        </td>
                        <td class="font-weight-600">{{ $template->subject }}</td>
                        <td><span class="text-muted">{{ $template->description }}</span></td>
                        <td>{{ $template->updated_at ? $template->updated_at->format('H:i d/m/Y') : 'Chưa cập nhật' }}</td>
                        <td class="text-center">
                            <div class="d-flex flex-wrap justify-content-center align-items-center" style="gap: 8px;">
                                <button type="button" class="btn btn-outline-coffee btn-sm" 
                                        style="border-radius:8px;padding:4px 12px;font-size:12px; min-width: 95px;"
                                        onclick="openPreview('{{ route('admin.email-templates.preview', $template) }}', '{{ $template->subject }}')">
                                    <span class="ion-md-eye mr-1"></span> Xem trước
                                </button>
                                <a href="{{ route('admin.email-templates.edit', $template) }}" class="btn btn-coffee btn-sm" 
                                   style="border-radius:8px;padding:4px 12px;font-size:12px; min-width: 95px;">
                                    <span class="ion-md-create mr-1"></span> Chỉnh sửa
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            Chưa có mẫu email nào được tạo.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Xem trước Email --}}
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 700px;">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: 1px solid #f0e8dd; box-shadow: 0 10px 30px rgba(111,78,55,0.15);">
            <div class="modal-header" style="background: linear-gradient(135deg, #6f4e37 0%, #8b6f47 100%); color: #fff; padding: 14px 20px; border-bottom: none; display: flex; justify-content: space-between; align-items: center;">
                <h5 class="modal-title" id="previewModalLabel" style="color: #fff; font-weight: 700; font-size: 16px; margin: 0;">Xem trước Email</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff; opacity: 0.8; text-shadow: none; background: none; border: none; font-size: 24px; padding: 0; line-height: 1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 0; background-color: #fbf9f6;">
                <iframe id="previewIframe" src="" style="width: 100%; height: 550px; border: none; display: block;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openPreview(url, subject) {
        document.getElementById('previewIframe').src = url;
        document.getElementById('previewModalLabel').textContent = 'Xem trước: ' + subject;
        $('#previewModal').modal('show');
    }
    
    // Xóa src của iframe khi đóng modal để tránh hiện lại template cũ khi mở cái mới
    $('#previewModal').on('hidden.bs.modal', function () {
        document.getElementById('previewIframe').src = '';
    });
</script>
@endpush
