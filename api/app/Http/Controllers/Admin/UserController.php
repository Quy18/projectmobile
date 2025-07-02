<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách người dùng
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Lọc theo từ khóa tìm kiếm
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('phone', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        // Lọc theo vai trò
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }
        
        // Lọc theo trạng thái
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Sắp xếp
        $query->orderBy('id', 'desc');
        
        // Phân trang
        $perPage = $request->input('per_page', 20);
        $users = $query->paginate($perPage);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Hiển thị form tạo người dùng mới
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Lưu người dùng mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role' => 'required|in:user,admin',
            'status' => 'required|in:active,inactive',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
            'status' => $request->status,
        ];
        
        // Xử lý upload avatar
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '_' . $avatar->getClientOriginalName();
            $path = $avatar->storeAs('avatars', $filename, 'public');
            $data['avatar'] = $path;
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được tạo thành công');
    }

    /**
     * Hiển thị thông tin người dùng
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        $user->load(['orders.orderDetails.product', 'reviews.product']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Hiển thị form chỉnh sửa người dùng
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role' => 'required|in:user,admin,staff',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        // Xử lý upload avatar
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu có
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $avatar = $request->file('avatar');
            $filename = time() . '_' . $avatar->getClientOriginalName();
            $path = $avatar->storeAs('avatars', $filename, 'public');
            $data['avatar'] = $path;
        }
        
        // Xử lý xóa avatar nếu được yêu cầu
        if ($request->has('remove_avatar') && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = null;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Thông tin người dùng đã được cập nhật');
    }
    
    /**
     * Thay đổi trạng thái người dùng
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể thay đổi trạng thái của chính mình');
        }
        
        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();
        
        $status = $user->status === 'active' ? 'kích hoạt' : 'vô hiệu hóa';
        return back()->with('success', "Đã {$status} tài khoản người dùng thành công");
    }

    /**
     * Xóa người dùng
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể xóa tài khoản của chính mình');
        }
        
        // Kiểm tra nếu người dùng có đơn hàng
        if ($user->orders()->count() > 0) {
            return back()->with('error', 'Không thể xóa người dùng này vì có đơn hàng liên quan');
        }

        // Xóa avatar nếu có
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Xóa các liên kết với giỏ hàng và wishlist
        $user->carts()->delete();
        $user->wishlist()->delete();
        $user->reviews()->delete();
        
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được xóa thành công');
    }

    /**
     * Đặt lại mật khẩu cho người dùng
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(User $user)
    {
        // Tạo mật khẩu ngẫu nhiên có 10 ký tự
        $password = \Illuminate\Support\Str::random(10);
        
        // Cập nhật mật khẩu mới cho người dùng
        $user->password = Hash::make($password);
        $user->save();

        // Gửi email thông báo mật khẩu mới (có thể thêm code gửi email ở đây)
        // Mail::to($user->email)->send(new ResetPasswordMail($user, $password));
        
        return back()->with('success', 'Đã đặt lại mật khẩu cho người dùng thành công. Mật khẩu mới: ' . $password);
    }
} 