<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user()->load('orders');
        return view('shop.profile.show', compact('user'));
    }

    public function edit()
    {
        return view('shop.profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'nullable|regex:/^[0-9]{10,11}$/',
            'address' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'phone.regex'   => 'Số điện thoại không hợp lệ.',
        ]);

        $user->update($request->only('name', 'phone', 'address'));

        return back()->with('success', 'Cập nhật thông tin thành công.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.confirmed'        => 'Mật khẩu xác nhận không khớp.',
            'password.min'              => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
        ]);

        $user = auth()->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Đổi mật khẩu thành công.');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();

        // Xóa avatar cũ
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return response()->json(['success' => true, 'avatar' => Storage::url($path)]);
    }

    public function destroy(Request $request)
    {
        $request->validate(['password' => 'required']);

        $user = auth()->user();

        if (! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mật khẩu không đúng.']);
        }

        auth()->logout();
        $user->delete();

        return redirect()->route('home')->with('success', 'Tài khoản đã được xóa.');
    }
}
