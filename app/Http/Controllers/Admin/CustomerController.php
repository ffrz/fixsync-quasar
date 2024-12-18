<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return inertia('admin/customer/Index');
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'name');
        $orderType = $request->get('order_type', 'asc');
        $filter = $request->get('filter', []);

        $q = Customer::query();
        $q->where('company_id', Auth::user()->company_id);

        if (!empty($filter['search'])) {
            $q->where(function ($q) use ($filter) {
                $q->where('name', 'like', '%' . $filter['search'] . '%');
                $q->orWhere('phone', 'like', '%' . $filter['search'] . '%');
                $q->orWhere('address', 'like', '%' . $filter['search'] . '%');
            });
        }

        if (!empty($filter['status']) && ($filter['status'] == 'active' || $filter['status'] == 'inactive')) {
            $q->where('active', '=', $filter['status'] == 'active' ? true : false);
        }

        $q->orderBy($orderBy, $orderType);

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }

    public function editor($id = 0)
    {
        allowed_roles(['admin', 'cashier']);

        $item = $id ? Customer::findOrFail($id) : new Customer();

        return inertia('admin/customer/Editor', [
            'data' => $item,
        ]);
    }

    public function save(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'phone' => 'required|max:100',
            'address' => 'required|max:1000',
        ];
        $item = null;
        $message = '';
        $fields = ['name', 'phone', 'address', 'active'];

        $request->validate($rules);

        if (!$request->id) {
            $item = new Customer();
            $item->company_id = Auth::user()->company_id;
            $message = 'Pelanggan baru telah ditambahkan.';
        } else {
            $item = Customer::findOrFail($request->post('id', 0));
            $message = 'Pelanggan telah diperbarui.';
        }

        $item->fill($request->only($fields));
        $item->save();

        return redirect(route('admin.customer.index'))->with('success', $message);
    }

    public function delete($id)
    {
        allowed_roles(['admin']);

        $item = Customer::findOrFail($id);
        if ($item->company_id != Auth::user()->company_id) {
            return response()->json([
                'message' => 'Akses ditolak, tidak bisa menghapus item berbeda perusahaan.'
            ], 403);
        }
        $item->delete();

        return response()->json([
            'message' => "Pelanggan $item->name telah dihapus"
        ]);
    }
}
