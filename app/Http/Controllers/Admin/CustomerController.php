<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::role('customer')->with('roles');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.customers.index', compact('users'));
    }

    public function show(User $user)
    {
        if (!$user->hasRole('customer')) {
            abort(404);
        }
        $user->load('roles', 'orders');
        return view('admin.customers.show', compact('user'));
    }

    public function destroy(User $user)
    {
        if (!$user->hasRole('customer')) {
            abort(404);
        }

        // Không cho xóa nếu còn đơn đang xử lý (Chờ xử lý hoặc Đang giao)
        $activeOrders = $user->orders()->whereIn('status', ['Chờ xử lý', 'Đang giao'])->count();
        if ($activeOrders > 0) {
            return redirect()->route('admin.customers.index')
                ->with('error', "Không thể xóa tài khoản \"{$user->name}\" vì còn {$activeOrders} đơn hàng đang được xử lý.");
        }

        // Soft delete: user bị vô hiệu hóa nhưng lịch sử đơn hàng vẫn được giữ lại
        $user->delete();
        return redirect()->route('admin.customers.index')
            ->with('success', "Đã xóa tài khoản khách hàng \"{$user->name}\". Lịch sử đơn hàng vẫn được lưu lại.");
    }
}
