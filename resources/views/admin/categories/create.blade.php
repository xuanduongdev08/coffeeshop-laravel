@extends('layouts.admin')

@section('title', 'Thêm danh mục')
@section('page-title', 'Thêm danh mục mới')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="admin-card">
            <h5>Thông tin danh mục</h5>
            <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="font-weight-bold">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required placeholder="VD: Cà phê">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Mô tả</label>
                    <textarea name="description" class="form-control" rows="3"
                        placeholder="Mô tả ngắn về danh mục">{{ old('description') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Thứ tự hiển thị</label>
                            <input type="number" name="sort_order" class="form-control"
                                value="{{ old('sort_order', 0) }}" min="0">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Ảnh danh mục</label>
                            <input type="file" name="image" class="form-control-file" accept="image/*"
                                onchange="previewImage(this)">
                            <img id="imagePreview" src="{{ asset('images/menu-1.jpg') }}"
                                style="width:100%;height:120px;object-fit:cover;border-radius:8px;margin-top:8px;">
                        </div>
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Hiển thị danh mục</label>
                </div>

                <div class="d-flex gap-2" style="gap:10px;">
                    <button type="submit" class="btn btn-coffee">💾 Lưu danh mục</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

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
</script>
@endpush
