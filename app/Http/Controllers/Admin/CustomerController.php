<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function index()
    {
        return inertia('admin/customer/Index');
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'name');
        $orderType = $request->get('order_type', 'asc');
        $search = $request->get('filter', '');

        $q = Customer::query();
        $q->orderBy($orderBy, $orderType);
        if (!empty($search)) {
            $q->where('name', 'like', '%' . $search . '%');
            $q->orWhere('phone', 'like', '%' . $search . '%');
            $q->orWhere('address', 'like', '%' . $search . '%');
        }

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }

    public function editor($id = 0)
    {
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
        $fields = ['name', 'phone', 'address'];

        $request->validate($rules);

        if (!$request->id) {
            $item = new Customer();
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
        $item = Customer::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => "Pelanggan $item->name telah dihapus"
        ]);
    }
}
