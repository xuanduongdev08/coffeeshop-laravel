@extends('layouts.shop')

@section('title', 'Thông tin tài khoản')

@section('content')

<section class="hero-page-header" style="background-image: url({{ asset('images/bg_3.jpg') }}); background-size: cover; background-position: center; height: 350px;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-md-7 col-sm-12 text-center ftco-animate">
                <h1 class="mb-3 bread" style="white-space:nowrap;font-size:30px;">Thông tin tài khoản</h1>
                <p class="breadcrumbs">
                    <span class="mr-2"><a href="{{ route('home') }}">Trang chủ</a></span>
                    <span>Thông tin</span>
                </p>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section profile-section">
    <div class="container">
        <div class="row icon-view-profile">

            {{-- Avatar Card --}}
            <div class="col-md-4 mb-4">
                <div class="card profile-card text-center p-4">
                    <div class="profile-img mb-3">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="profile-avatar-img"
                                style="width:170px;height:170px;border-radius:50%;object-fit:cover;border:3px solid #c49b63;">
                        @else
                            <div class="d-flex justify-content-center align-items-center mx-auto"
                                style="width:170px;height:170px;background:rgba(196,155,99,0.05);border-radius:50%;border:3px dashed rgba(196,155,99,0.4);">
                                <span class="icon-user" style="font-size:70px;color:#c49b63;"></span>
                            </div>
                        @endif
                    </div>

                    <button type="button" class="btn btn-primary mb-3" id="btn-upload-avatar">
                        <span class="icon-camera mr-2"></span> Đổi ảnh đại diện
                    </button>
                    <input type="file" id="input-avatar" accept="image/png,image/jpeg" style="display:none;">

                    <h3 class="mb-1">{{ $user->name }}</h3>
                    <p class="text-white-50 small mb-2">
                        <i class="icon-verified_user mr-1"></i>
                        @if($user->hasRole('admin')) Quản trị viên
                        @elseif($user->hasRole('staff')) Nhân viên
                        @else Khách hàng thành viên
                        @endif
                    </p>
                    <p class="text-white-50 small mb-0">
                        <i class="icon-shopping-bag mr-1"></i>
                        {{ $user->orders->count() }} đơn hàng
                    </p>
                </div>
            </div>

            {{-- Info Card --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h3 class="mb-0">Thông tin cá nhân</h3>
                            <div class="d-flex">
                                <a href="{{ route('orders.history') }}" class="btn btn-primary mr-2">
                                    <span class="icon-list-alt mr-1"></span> Lịch sử mua hàng
                                </a>
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                                    <span class="icon-edit mr-1"></span> Chỉnh sửa
                                </a>
                            </div>
                        </div>

                        <div class="form-group row align-items-center mb-4">
                            <label class="col-sm-3 col-form-label">
                                <i class="icon-user-o mr-2"></i> Họ và tên
                            </label>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext text-white">{{ $user->name }}</p>
                            </div>
                        </div>

                        <div class="form-group row align-items-center mb-4">
                            <label class="col-sm-3 col-form-label">
                                <i class="icon-envelope-o mr-2"></i> Email
                            </label>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext text-white">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="form-group row align-items-center mb-4">
                            <label class="col-sm-3 col-form-label">
                                <i class="icon-phone mr-2"></i> Số điện thoại
                            </label>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext text-white">{{ $user->phone ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>

                        <div class="form-group row align-items-start mb-4">
                            <label class="col-sm-3 col-form-label mt-1">
                                <i class="icon-map-marker mr-2"></i> Địa chỉ
                            </label>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext text-white">{{ $user->address ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>

                        @if($user->provider)
                            <div class="form-group row align-items-center mb-4">
                                <label class="col-sm-3 col-form-label">
                                    <i class="icon-link mr-2"></i> Đăng nhập qua
                                </label>
                                <div class="col-sm-9">
                                    <span class="badge badge-info" style="font-size:13px;padding:6px 12px;">
                                        {{ ucfirst($user->provider) }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Avatar Crop Modal --}}
<div class="modal fade" id="cropModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color:#6f4e37;font-weight:bold;">Cập nhật ảnh đại diện</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="image-to-crop" src="" style="max-width:100%;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="btn-crop-upload">Đặt ảnh đại diện</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var inputAvatar = document.getElementById('input-avatar');
    var imageToCrop = document.getElementById('image-to-crop');
    var cropModal   = $('#cropModal');
    var cropper;

    document.getElementById('btn-upload-avatar').addEventListener('click', function() {
        inputAvatar.value = '';
        inputAvatar.click();
    });

    inputAvatar.addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;
        var url = URL.createObjectURL(file);
        imageToCrop.src = url;
        cropModal.modal('show');
    });

    cropModal.on('shown.bs.modal', function() {
        cropper = new Cropper(imageToCrop, { aspectRatio: 1, viewMode: 1 });
    }).on('hidden.bs.modal', function() {
        if (cropper) { cropper.destroy(); cropper = null; }
        inputAvatar.value = '';
    });

    document.getElementById('btn-crop-upload').addEventListener('click', function() {
        if (!cropper) return;
        var canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
        canvas.toBlob(function(blob) {
            var formData = new FormData();
            formData.append('avatar', blob, 'avatar.png');
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('profile.avatar') }}', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                cropModal.modal('hide');
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Thành công', text: 'Cập nhật ảnh đại diện thành công', confirmButtonColor: '#c49b63' })
                        .then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: data.message || 'Có lỗi xảy ra', confirmButtonColor: '#c49b63' });
                }
            })
            .catch(() => {
                cropModal.modal('hide');
                Swal.fire({ icon: 'error', title: 'Lỗi kết nối', confirmButtonColor: '#c49b63' });
            });
        }, 'image/png');
    });
});
</script>
@endpush
