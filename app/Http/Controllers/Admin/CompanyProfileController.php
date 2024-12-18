<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyProfileController extends Controller
{
    public function __construct()
    {
        allowed_roles('admin');
    }

    /**
     * Display the company's profile form.
     */
    public function edit()
    {
        $data = Company::find(Auth::user()->company_id);
        return inertia('admin/company-profile/Edit', compact('data'));
    }

    /**
     * Update the company's profile information.
     */
    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'required|regex:/^(\+?\d{1,4})?[\s.-]?\(?\d{1,4}\)?[\s.-]?\d{1,4}[\s.-]?\d{1,9}$/|max:40',
            'address' => 'required|max:1000',
        ];
        $messages = [
            'name.required' => 'Nama harus diisi',
            'name.min' => 'Nama minimal 2 karakter',
            'name.max' => 'Nama maksimal 100 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Email terlalu panjang, maksimal 255 karakter',
            'phone.required' => 'No Telepon harus diisi',
            'phone.regex' => 'Format No Telepon tidak valid',
            'phone.max' => 'No Telepon terlalu panjang, maksimal 15 karakter',
            'address.required' => 'Alamat harus diisi',
            'address.max' => 'Alamat terlalu panjang, maksimal 1000 karakter',
        ];

        $request->validate($rules, $messages);
        $company = Company::find(Auth::user()->company_id);
        $company->fill($request->only(['name', 'email', 'phone', 'address']));
        $company->save();

        $request->session()->flash('success', 'Profil perusahaan berhasil diperbarui.');

        return back();
    }
}
