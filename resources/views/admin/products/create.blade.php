@extends('layouts.admin')

@section('title', 'Thêm sản phẩm')
@section('page-title', 'Thêm sản phẩm mới')

@section('content')

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="admin-card mb-4">
                <h5>Thông tin cơ bản</h5>
                <div class="form-group">
                    <label class="font-weight-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Danh mục</label>
                    <select name="category_id" class="form-control">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Mô tả</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Giá gốc (đ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price') }}" min="0" step="1000" required>
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Giá khuyến mãi (đ)</label>
                            <input type="number" name="discount_price" class="form-control"
                                value="{{ old('discount_price') }}" min="0" step="1000">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Tồn kho <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                value="{{ old('stock', 50) }}" min="0" required>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Size M/L/XL --}}
            <div class="admin-card mb-4" id="sizeSection" style="display:none;">
                <h5>📏 Giá theo Size M/L/XL</h5>
                <div class="row">
                    @foreach(['M', 'L', 'XL'] as $size)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Size {{ $size }} (đ)</label>
                                <input type="number" name="sizes[{{ $loop->index }}][price]"
                                    class="form-control" min="0" step="1000"
                                    placeholder="VD: {{ $size === 'M' ? '49000' : ($size === 'L' ? '59000' : '69000') }}">
                                <input type="hidden" name="sizes[{{ $loop->index }}][size]" value="{{ $size }}">
                            </div>
                        </div>
                    @endforeach
                </div>
                <small class="text-muted">Nếu để trống, sẽ dùng giá gốc cho tất cả size.</small>
            </div>
        </div>

        <div class="col-md-4">
            {{-- Ảnh --}}
            <div class="admin-card mb-4">
                <h5>🖼️ Ảnh sản phẩm</h5>
                <div class="form-group">
                    <input type="file" name="image" class="form-control-file" accept="image/*"
                        onchange="previewImage(this)">
                    <img id="imagePreview" src="{{ asset('images/menu-1.jpg') }}"
                        style="width:100%;height:180px;object-fit:cover;border-radius:8px;margin-top:10px;">
                </div>
            </div>

            {{-- Flags --}}
            <div class="admin-card mb-4">
                <h5>⚙️ Tùy chọn</h5>
                <div class="form-check mb-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Đang bán</label>
                </div>
                <div class="form-check mb-2">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_featured">⭐ Nổi bật (hiển thị trang chủ)</label>
                </div>
                <hr>
                <p class="small font-weight-bold text-muted mb-2">Đồ uống:</p>
                <div class="form-check mb-2">
                    <input type="hidden" name="has_size" value="0">
                    <input type="checkbox" name="has_size" id="has_size" class="form-check-input" value="1" {{ old('has_size') ? 'checked' : '' }}
                        onchange="document.getElementById('sizeSection').style.display = this.checked ? 'block' : 'none'">
                    <label class="form-check-label" for="has_size">Có size M/L/XL</label>
                </div>
                <div class="form-check mb-2">
                    <input type="hidden" name="has_topping" value="0">
                    <input type="checkbox" name="has_topping" id="has_topping" class="form-check-input" value="1" {{ old('has_topping') ? 'checked' : '' }}>
                    <label class="form-check-label" for="has_topping">Có topping</label>
                </div>
                <div class="form-check mb-2">
                    <input type="hidden" name="allow_sugar" value="0">
                    <input type="checkbox" name="allow_sugar" id="allow_sugar" class="form-check-input" value="1" {{ old('allow_sugar', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="allow_sugar">Chọn mức đường</label>
                </div>
                <div class="form-check mb-2">
                    <input type="hidden" name="allow_ice" value="0">
                    <input type="checkbox" name="allow_ice" id="allow_ice" class="form-check-input" value="1" {{ old('allow_ice', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="allow_ice">Chọn mức đá</label>
                </div>
                <div class="form-check mb-2">
                    <input type="hidden" name="allow_milk" value="0">
                    <input type="checkbox" name="allow_milk" id="allow_milk" class="form-check-input" value="1" {{ old('allow_milk') ? 'checked' : '' }}>
                    <label class="form-check-label" for="allow_milk">Chọn loại sữa</label>
                </div>
            </div>

            <button type="submit" class="btn btn-coffee btn-block">💾 Lưu sản phẩm</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-block mt-2">Hủy</a>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
// Show size section if has_size is checked on load
if (document.getElementById('has_size').checked) {
    document.getElementById('sizeSection').style.display = 'block';
}
</script>
@endpush
