@extends('layouts.admin')

@section('title', 'Quản lý danh mục')
@section('page-title', 'Quản lý danh mục')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div></div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-coffee">+ Thêm danh mục</a>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th>Số SP</th>
                    <th>Thứ tự</th>
                    <th>Trạng thái</th>
                    <th style="text-align: center; width: 150px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>
                            @if($category->image)
                                <img src="{{ asset($category->image) }}" alt="" style="width:45px;height:45px;object-fit:cover;border-radius:6px;">
                            @else
                                <div style="width:45px;height:45px;background:#f0e8dd;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:20px;">📂</div>
                            @endif
                        </td>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td><code style="font-size:12px;">{{ $category->slug }}</code></td>
                        <td>
                            <span class="badge badge-info">{{ $category->products_count }} SP</span>
                        </td>
                        <td>{{ $category->sort_order }}</td>
                        <td>
                            @if($category->is_active)
                                <span class="badge badge-success">Hiển thị</span>
                            @else
                                <span class="badge badge-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;">Sửa</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-confirm" data-name="{{ $category->name }}" style="font-size:11px;">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Chưa có danh mục nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
        <div class="p-3">{{ $categories->links() }}</div>
    @endif
</div>

@endsection
