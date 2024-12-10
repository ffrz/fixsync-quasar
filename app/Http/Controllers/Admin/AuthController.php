<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Nette\NotImplementedException;

class AuthController extends Controller
{
    private function _logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            // TODO:: log activity
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function login(Request $request)
    {
        if ($request->getMethod() === 'GET') {
            return inertia('admin/auth/Login');
        }

        // kode dibawah ini untuk handle post

        $validator = Validator::make($request->all(), [
            'company_code' => 'required|string|exists:companies,code',
            'username' => 'required',
            'password' => 'required',
        ], [
            'company_code.required' => 'Nama Perusahaan harus diisi.',
            'company_code.exists' => 'Nama Perusahaan tidak ditemukan.',
            'username.required' => 'ID Pengguna harus diisi.',
            'password.required' => 'Kata sandi harus diisi.',
        ]);

        // basic validations
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // extra validations
        $company = Company::where('code', $request->post('company_code', ''))->first();
        $data = $request->only(['username', 'password']);
        $data['company_id'] = $company->id;

        if (!$company) {
            $validator->errors()->add('company_code', 'Nama perusahaan tidak ditemukan.');
        } else if (!$company->active) {
            $validator->errors()->add('company_code', 'Akun perusahaan tidak aktif.');
        } else if (!Auth::attempt($data, $request->has('remember'))) {
            $validator->errors()->add('username', 'Username atau password salah!');
        } else if (!Auth::user()->active) {
            $validator->errors()->add('username', 'Akun anda tidak aktif. Silahkan hubungi administrator!');
            $this->_logout($request);
        } else {
            $request->session()->regenerate();
            return redirect(route('admin.dashboard'));
        }

        return redirect()->back()->withInput()->withErrors($validator);
    }

    public function logout(Request $request)
    {
        $this->_logout($request);
        return redirect(route('admin.auth.login'))->with('success', 'Anda telah logout.');
    }

    public function register(Request $request)
    {
        if ($request->getMethod() === 'GET') {
            return inertia('admin/auth/Register');
        }

        throw new NotImplementedException('Fitur ini belum diimplementasikan.');
    }
}
