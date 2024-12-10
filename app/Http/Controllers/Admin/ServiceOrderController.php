<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceOrderController extends Controller
{
    //
    public function index()
    {
        return inertia('admin/service-order/Index');
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'id');
        $orderType = $request->get('order_type', 'desc');
        $search = $request->get('filter', '');

        $q = ServiceOrder::query();
        $q->orderBy($orderBy, $orderType);
        $q->where('company_id', Auth::user()->company_id);
        if (!empty($search)) {
            $q->where('customer_name', 'like', '%' . $search . '%');
            $q->orWhere('customer_phone', 'like', '%' . $search . '%');
            $q->orWhere('customer_address', 'like', '%' . $search . '%');
            $q->orWhere('device', 'like', '%' . $search . '%');
        }

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }

    public function editor($id = 0)
    {
        $item = $id ? ServiceOrder::findOrFail($id) : new ServiceOrder();

        return inertia('admin/service-order/Editor', [
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
            $item = new ServiceOrder();
            $item->company_id = Auth::user()->company_id;
            $message = 'Pelanggan baru telah ditambahkan.';
        } else {
            $item = ServiceOrder::findOrFail($request->post('id', 0));
            $message = 'Pelanggan telah diperbarui.';
        }

        $item->fill($request->only($fields));
        $item->save();

        return redirect(route('admin.service-order.index'))->with('success', $message);
    }

    public function delete($id)
    {
        $item = ServiceOrder::findOrFail($id);
        if ($item->company_id != Auth::user()->company_id) {
            return response()->json([
                'message' => 'Akses ditolak, tidak bisa menghapus item berbeda perusahaan.'
            ], 403);
        }
        $item->delete();

        return response()->json([
            'message' => "Pesanan $item->id telah dihapus"
        ]);
    }
}
