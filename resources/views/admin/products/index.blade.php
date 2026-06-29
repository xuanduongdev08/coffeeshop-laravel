@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')
@section('page-title', 'Quản lý sản phẩm')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div></div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-coffee">+ Thêm sản phẩm</a>
</div>

{{-- Filters --}}
<div class="admin-card mb-4">
    <form method="GET" class="row align-items-end">
        <div class="col-md-4 mb-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Tìm tên sản phẩm..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3 mb-2">
            <select name="category" class="form-control form-control-sm">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <select name="status" class="form-control form-control-sm">
                <option value="">Tất cả</option>
                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Đang bán</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <button type="submit" class="btn btn-sm btn-coffee w-100"><span class="ion-md-search" style="margin-right:4px;"></span>Lọc</button>
        </div>
    </form>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Flags</th>
                    <th>Trạng thái</th>
                    <th style="text-align: center; width: 160px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="{{ $product->trashed() ? 'table-secondary' : '' }}">
                        <td>
                            <img src="{{ $product->image ? asset($product->image) : asset('images/menu-1.jpg') }}"
                                alt="" style="width:45px;height:45px;object-fit:cover;border-radius:6px;">
                        </td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            @if($product->trashed())
                                <span class="badge badge-secondary ml-1">Đã xóa</span>
                            @endif
                            @if($product->is_featured)
                                <span class="badge badge-warning ml-1">⭐ Nổi bật</span>
                            @endif
                        </td>
                        <td>{{ $product->category?->name ?? '—' }}</td>
                        <td>
                            @if($product->discount_price)
                                <del class="text-muted" style="font-size:11px;">{{ number_format($product->price, 0, ',', '.') }}đ</del><br>
                                <strong style="color:#c49b63;">{{ number_format($product->discount_price, 0, ',', '.') }}đ</strong>
                            @else
                                {{ number_format($product->price, 0, ',', '.') }}đ
                            @endif
                        </td>
                        <td>
                            <span class="{{ $product->stock <= 5 ? 'text-danger font-weight-bold' : '' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td>
                            @if($product->has_size)    <span class="badge badge-info mr-1">Size</span> @endif
                            @if($product->has_topping) <span class="badge badge-info mr-1">Topping</span> @endif
                            @if($product->allow_sugar) <span class="badge badge-light mr-1">Đường</span> @endif
                            @if($product->allow_ice)   <span class="badge badge-light mr-1">Đá</span> @endif
                        </td>
                        <td>
                            @if($product->trashed())
                                <span class="badge badge-secondary">Đã xóa</span>
                            @elseif($product->is_active)
                                <span class="badge badge-success">Đang bán</span>
                            @else
                                <span class="badge badge-warning">Ẩn</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($product->trashed())
                                <form method="POST" action="{{ route('admin.products.restore', $product->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" style="font-size:11px;">Khôi phục</button>
                                </form>
                            @else
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;">Sửa</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-confirm" data-name="{{ $product->name }}" style="font-size:11px;">Xóa</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Không có sản phẩm nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div class="p-3">{{ $products->links() }}</div>
    @endif
</div>

@endsection
