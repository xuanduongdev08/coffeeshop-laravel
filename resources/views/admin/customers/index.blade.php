@extends('layouts.admin')

@section('title', 'Quản lý khách hàng')
@section('page-title', 'Quản lý khách hàng')

@section('content')

{{-- Filters --}}
<div class="admin-card mb-4">
    <form method="GET" class="row align-items-end">
        <div class="col-md-6 mb-2">
            <input type="text" name="search" class="form-control form-control-sm"
                placeholder="Tìm tên, email, SĐT của khách hàng..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2 mb-2">
            <button type="submit" class="btn btn-sm btn-coffee w-100"><span class="ion-md-search" style="margin-right:4px;"></span>Tìm kiếm</button>
        </div>
    </form>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Tên khách hàng</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Đăng nhập qua</th>
                    <th>Ngày tham gia</th>
                    <th style="text-align: center; width: 120px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt=""
                                    style="width:38px;height:38px;border-radius:50%;object-fit:cover;">
                            @else
                                <div style="width:38px;height:38px;border-radius:50%;background:#6f4e37;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $user->name }}</strong>
                        </td>
                        <td style="font-size:13px;">{{ $user->email }}</td>
                        <td style="font-size:13px;">{{ $user->phone ?? '—' }}</td>
                        <td>
                            @if($user->provider)
                                <span class="badge badge-light">{{ ucfirst($user->provider) }}</span>
                            @else
                                <span class="text-muted" style="font-size:12px;">Email</span>
                            @endif
                        </td>
                        <td style="font-size:12px;color:#888;">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td style="text-align: center;">
                            <a href="{{ route('admin.customers.show', $user) }}" class="btn btn-sm btn-outline-secondary" style="font-size:11px;">Chi tiết</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Không có khách hàng nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="p-3">{{ $users->links() }}</div>
    @endif
</div>

@endsection
