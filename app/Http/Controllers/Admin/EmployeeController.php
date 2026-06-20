<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['admin', 'staff', 'cashier']);
        })->with('roles');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        $roles = Role::whereIn('name', ['admin', 'staff', 'cashier'])->get();

        return view('admin.employees.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::whereIn('name', ['admin', 'staff', 'cashier'])->get();
        return view('admin.employees.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
            'role'     => 'required|in:admin,staff,cashier',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => \Hash::make($request->password),
            'phone'    => $request->phone,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.employees.index')
            ->with('success', "Đã thêm nhân viên \"{$user->name}\" thành công.");
    }

    public function show(User $user)
    {
        if (!$user->hasAnyRole(['admin', 'staff', 'cashier'])) {
            abort(404);
        }
        $user->load('roles', 'orders');
        return view('admin.employees.show', compact('user'));
    }

    public function updateRole(Request $request, User $user)
    {
        if (!$user->hasAnyRole(['admin', 'staff', 'cashier'])) {
            abort(404);
        }

        $request->validate([
            'role' => 'required|in:admin,staff,cashier',
        ]);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể thay đổi role của chính mình.');
        }

        $user->syncRoles([$request->role]);

        return back()->with('success', "Đã cập nhật vai trò của \"{$user->name}\" thành \"{$request->role}\".");
    }

    public function destroy(User $user)
    {
        if (!$user->hasAnyRole(['admin', 'staff', 'cashier'])) {
            abort(404);
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể xóa tài khoản của chính mình.');
        }

        $user->delete();
        return redirect()->route('admin.employees.index')->with('success', "Đã xóa tài khoản nhân viên \"{$user->name}\".");
    }
}
