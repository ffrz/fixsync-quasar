<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return inertia('admin/user/Index');
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'name');
        $orderType = $request->get('order_type', 'asc');
        $search = $request->get('filter', '');

        $q = User::query();
        $q->orderBy($orderBy, $orderType);
        $q->where('company_id', Auth::user()->company->id);

        if (!empty($search)) {
            $q->where('name', 'like', '%' . $search . '%');
            $q->orWhere('email', 'like', '%' . $search . '%');
        }

        $users = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($users);
    }

    public function editor($id = 0)
    {
        $user = $id ? User::findOrFail($id) : new User();

        if (!$id) {
            $user->active = true;
            $user->admin = true;
        } else if ($user == Auth::user()) {
            return redirect(route('admin.user.index'))->with('warning', 'TIdak dapat mengubah akun anda sendiri.');
        }

        return inertia('admin/user/Editor', [
            'data' => $user,
        ]);
    }

    public function save(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'username' => ['required', 'string', 'max:100'],
            'email' => 'email|min:3|max:100',
            'password' => 'required|min:5|max:40',
            'role' => 'required',
        ];
        $messages = [
            'name.required' => 'Nama pengguna harus diisi',
            'name.max' => 'Nama pengguna maksimal 255 karakter',
            'username.required' => 'ID pengguna harus diisi',
            'username.max' => 'Nama pengguna maksimal 100 karakter',
            'username.unique' => 'ID Pengguna sudah digunakan',
            'password.required' => 'Kata sandi harus diisi',
            'password.min' => 'Kata sandi minimal 5 karakter',
            'password.max' => 'Kata sandi maksimal 40 karakter',
            'role.required' => 'Role harus diisi',
        ];
        $user = null;
        $message = '';
        $fields = ['name', 'username', 'email', 'role', 'active'];
        $password = $request->get('password');
        $companyId = Auth::user()->company_id;
        if (!$request->id) {
            // username harus unik untuk masing-masing company_id
            $rules['username'] = "required|string|max:255|unique:users,username,NULL,id,company_id,{$companyId}";
            $request->validate($rules, $messages);
            $user = new User();
            $user->company_id = $companyId;
            $message = 'Pengguna baru telah dibuat.';
        } else {
            // username harus unik untuk masing-masing company_id, exclude id
            $rules['username'] = "required|string|max:255|unique:users,username,{$request->id},id,company_id,{$companyId}";
            if (empty($request->get('password'))) {
                // kalau password tidak diisi, skip validation dan jangan update password
                unset($rules['password']);
                unset($fields['password']);
            }
            $request->validate($rules, $messages);
            $user = User::findOrFail($request->id);
            $message = "Pengguna {$user->username} telah diperbarui.";
        }

        if (!empty($password)) {
            $user->password = Hash::make($password);
        }
        $user->fill($request->only($fields));
        $user->save();

        return redirect(route('admin.user.index'))->with('success', $message);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        if ($user->company_id != Auth::user()->company_id) {
            return response()->json([
                'message' => 'Akses ditolak, tidak bisa menghapus akun berbeda perusahaan.'
            ], 403);
        }

        if ($user->id == Auth::user()->id) {
            return response()->json([
                'message' => 'Tidak dapat menghapus akun sendiri!'
            ], 409);
        }

        $user->delete();

        return response()->json([
            'message' => "Pengguna {$user->username} telah dihapus!"
        ]);
    }
}
