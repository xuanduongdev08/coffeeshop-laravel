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
                            <a href="{{ route('admin.email-templates.edit', $template) }}" class="btn btn-coffee btn-sm" style="border-radius:8px;padding:4px 12px;font-size:12px;">
                                <span class="ion-md-create mr-1"></span> Chỉnh sửa
                            </a>
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
@endsection
