@extends('layouts.admin')

@section('title', 'Sửa: ' . $product->name)
@section('page-title', 'Sửa sản phẩm: ' . $product->name)

@section('content')

@php $isWarehouse = auth()->user()->hasRole('warehouse'); @endphp

<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-md-8">
            <div class="admin-card mb-4">
                <h5>Thông tin cơ bản</h5>
                <div class="form-group">
                    <label class="font-weight-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $product->name) }}" required {{ $isWarehouse ? 'disabled' : '' }}>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Danh mục</label>
                    <select name="category_id" class="form-control" {{ $isWarehouse ? 'disabled' : '' }}>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Mô tả</label>
                    <textarea name="description" class="form-control" rows="3" {{ $isWarehouse ? 'disabled' : '' }}>{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Giá gốc (đ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price', $product->price) }}" min="0" step="1000" required {{ $isWarehouse ? 'disabled' : '' }}>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Giá khuyến mãi (đ)</label>
                            <input type="number" name="discount_price" class="form-control"
                                value="{{ old('discount_price', $product->discount_price) }}" min="0" step="1000" {{ $isWarehouse ? 'disabled' : '' }}>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Tồn kho <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                value="{{ old('stock', $product->stock) }}" min="0" required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Size M/L/XL --}}
            <div class="admin-card mb-4" id="sizeSection" style="{{ ($product->sizes->contains('size', 'M') || $product->sizes->contains('size', 'L') || $product->sizes->contains('size', 'XL')) ? '' : 'display:none;' }}">
                <h5>📏 Giá theo các Size đã chọn</h5>
                <div class="row">
                    @php 
                        $sizeMPrice = $product->sizes->firstWhere('size', 'M')?->price;
                        $sizeLPrice = $product->sizes->firstWhere('size', 'L')?->price;
                        $sizeXLPrice = $product->sizes->firstWhere('size', 'XL')?->price;
                    @endphp
                    
                    <div class="col-md-4" id="sizeCol_M" style="{{ old('has_size_m', $product->sizes->contains('size', 'M')) ? '' : 'display:none;' }}">
                        <div class="form-group">
                            <label class="font-weight-bold">Size M (đ) <span class="text-danger">*</span></label>
                            <input type="number" name="price_m" id="price_m"
                                class="form-control @error('price_m') is-invalid @enderror" min="0" step="1000"
                                value="{{ old('price_m', $sizeMPrice ? (float)$sizeMPrice : '') }}" {{ $isWarehouse ? 'disabled' : '' }}>
                            @error('price_m') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4" id="sizeCol_L" style="{{ old('has_size_l', $product->sizes->contains('size', 'L')) ? '' : 'display:none;' }}">
                        <div class="form-group">
                            <label class="font-weight-bold">Size L (đ) <span class="text-danger">*</span></label>
                            <input type="number" name="price_l" id="price_l"
                                class="form-control @error('price_l') is-invalid @enderror" min="0" step="1000"
                                value="{{ old('price_l', $sizeLPrice ? (float)$sizeLPrice : '') }}" {{ $isWarehouse ? 'disabled' : '' }}>
                            @error('price_l') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4" id="sizeCol_XL" style="{{ old('has_size_xl', $product->sizes->contains('size', 'XL')) ? '' : 'display:none;' }}">
                        <div class="form-group">
                            <label class="font-weight-bold">Size XL (đ) <span class="text-danger">*</span></label>
                            <input type="number" name="price_xl" id="price_xl"
                                class="form-control @error('price_xl') is-invalid @enderror" min="0" step="1000"
                                value="{{ old('price_xl', $sizeXLPrice ? (float)$sizeXLPrice : '') }}" {{ $isWarehouse ? 'disabled' : '' }}>
                            @error('price_xl') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            {{-- Ảnh --}}
            <div class="admin-card mb-4">
                <h5>🖼️ Ảnh sản phẩm</h5>
                <div class="form-group">
                    <input type="file" name="image" class="form-control-file" accept="image/*"
                        onchange="previewImage(this)" {{ $isWarehouse ? 'disabled' : '' }}>
                    <img id="imagePreview"
                        src="{{ $product->image ? asset($product->image) : asset('images/menu-1.jpg') }}"
                        style="width:100%;height:180px;object-fit:cover;border-radius:8px;margin-top:10px;">
                </div>
            </div>

            {{-- Flags --}}
            <div class="admin-card mb-4">
                <h5>⚙️ Tùy chọn</h5>
                <div class="form-check mb-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} {{ $isWarehouse ? 'disabled' : '' }}>
                    <label class="form-check-label" for="is_active">Đang bán</label>
                </div>
                <div class="form-check mb-2">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} {{ $isWarehouse ? 'disabled' : '' }}>
                    <label class="form-check-label" for="is_featured">⭐ Nổi bật</label>
                </div>
                <hr>
                <div class="form-check mb-2">
                    <input type="hidden" name="has_size_m" value="0">
                    <input type="checkbox" name="has_size_m" id="has_size_m" class="form-check-input size-checkbox" value="1" 
                        {{ old('has_size_m', $product->sizes->contains('size', 'M')) ? 'checked' : '' }} {{ $isWarehouse ? 'disabled' : '' }}
                        onchange="toggleSizeInput('M', this.checked)">
                    <label class="form-check-label" for="has_size_m">Có size M</label>
                </div>
                <div class="form-check mb-2">
                    <input type="hidden" name="has_size_l" value="0">
                    <input type="checkbox" name="has_size_l" id="has_size_l" class="form-check-input size-checkbox" value="1" 
                        {{ old('has_size_l', $product->sizes->contains('size', 'L')) ? 'checked' : '' }} {{ $isWarehouse ? 'disabled' : '' }}
                        onchange="toggleSizeInput('L', this.checked)">
                    <label class="form-check-label" for="has_size_l">Có size L</label>
                </div>
                <div class="form-check mb-2">
                    <input type="hidden" name="has_size_xl" value="0">
                    <input type="checkbox" name="has_size_xl" id="has_size_xl" class="form-check-input size-checkbox" value="1" 
                        {{ old('has_size_xl', $product->sizes->contains('size', 'XL')) ? 'checked' : '' }} {{ $isWarehouse ? 'disabled' : '' }}
                        onchange="toggleSizeInput('XL', this.checked)">
                    <label class="form-check-label" for="has_size_xl">Có size XL</label>
                </div>
                <div class="form-check mb-2">
                    <input type="hidden" name="has_topping" value="0">
                    <input type="checkbox" name="has_topping" id="has_topping" class="form-check-input" value="1" {{ old('has_topping', $product->has_topping) ? 'checked' : '' }} {{ $isWarehouse ? 'disabled' : '' }}>
                    <label class="form-check-label" for="has_topping">Có topping</label>
                </div>
                <div class="form-check mb-2">
                    <input type="hidden" name="allow_sugar" value="0">
                    <input type="checkbox" name="allow_sugar" id="allow_sugar" class="form-check-input" value="1" {{ old('allow_sugar', $product->allow_sugar) ? 'checked' : '' }} {{ $isWarehouse ? 'disabled' : '' }}>
                    <label class="form-check-label" for="allow_sugar">Chọn mức đường</label>
                </div>
                <div class="form-check mb-2">
                    <input type="hidden" name="allow_ice" value="0">
                    <input type="checkbox" name="allow_ice" id="allow_ice" class="form-check-input" value="1" {{ old('allow_ice', $product->allow_ice) ? 'checked' : '' }} {{ $isWarehouse ? 'disabled' : '' }}>
                    <label class="form-check-label" for="allow_ice">Chọn mức đá</label>
                </div>
                <div class="form-check mb-2">
                    <input type="hidden" name="allow_milk" value="0">
                    <input type="checkbox" name="allow_milk" id="allow_milk" class="form-check-input" value="1" {{ old('allow_milk', $product->allow_milk) ? 'checked' : '' }} {{ $isWarehouse ? 'disabled' : '' }}>
                    <label class="form-check-label" for="allow_milk">Chọn loại sữa</label>
                </div>
            </div>

            <button type="submit" class="btn btn-coffee btn-block">💾 Lưu thay đổi</button>
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
        reader.onload = function(e) { document.getElementById('imagePreview').src = e.target.result; };
        reader.readAsDataURL(input.files[0]);
    }
}

function toggleSizeInput(size, isChecked) {
    var col = document.getElementById('sizeCol_' + size);
    var input = document.getElementById('price_' + size.toLowerCase());
    
    if (col) {
        col.style.display = isChecked ? 'block' : 'none';
    }
    
    if (isChecked && input) {
        input.setAttribute('required', 'required');
    } else if (input) {
        input.removeAttribute('required');
    }
    
    var checkedCount = document.querySelectorAll('.size-checkbox:checked').length;
    var sizeSection = document.getElementById('sizeSection');
    if (sizeSection) {
        sizeSection.style.display = checkedCount > 0 ? 'block' : 'none';
    }
}

// Khởi tạo lúc load trang
document.addEventListener('DOMContentLoaded', function() {
    ['M', 'L', 'XL'].forEach(function(size) {
        var cb = document.getElementById('has_size_' + size.toLowerCase());
        if (cb) {
            toggleSizeInput(size, cb.checked);
        }
    });
});
</script>
@endpush
