<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ServiceOrder;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $filter = $request->get('filter', []);

        $q = ServiceOrder::query();
        $q->orderBy($orderBy, $orderType);
        $q->where('company_id', Auth::user()->company_id);

        if (!empty($filter['order_status'] && $filter['order_status'] != 'all')) {
            $q->where('order_status', '=', $filter['order_status']);
        }

        if (!empty($filter['service_status'] && $filter['service_status'] != 'all')) {
            $q->where('service_status', '=', $filter['service_status']);
        }

        if (!empty($filter['payment_status'] && $filter['payment_status'] != 'all')) {
            $q->where('payment_status', '=', $filter['payment_status']);
        }

        if (!empty($filter['search'])) {
            $search = $filter['search'];
            $q->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', '%' . $search . '%');
                $q->orWhere('customer_phone', 'like', '%' . $search . '%');
                $q->orWhere('customer_address', 'like', '%' . $search . '%');
                $q->orWhere('device', 'like', '%' . $search . '%');
            });
        }

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }

    public function editor($id = 0)
    {
        $item = $id ? ServiceOrder::with([
            'createdBy:id,username,name',
            'updatedBy:id,username,name',
            'closedBy:id,username,name',
        ])
            ->findOrFail($id) : new ServiceOrder();

        $companyId = Auth::user()->company_id;

        if ($id && $item->company_id != $companyId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$item->id) {
            $item->received_datetime = date('Y-m-d H:i:s');
            $item->order_status = 'open';
            $item->service_status = 'received';
            $item->payment_status = 'unpaid';
        }

        $customers = Customer::where('company_id', $companyId)
            ->get(['id', 'name', 'phone', 'address']);

        $technicians = Technician::where('company_id', $companyId)
            ->get(['id', 'name']);

        return inertia('admin/service-order/Editor', [
            'data' => $item,
            'technicians' => $technicians,
            'customers' => $customers,
        ]);
    }

    public function save(Request $request)
    {
        $rules = [
            // 'name' => 'required|max:255',
            // 'phone' => 'required|max:100',
            // 'address' => 'required|max:1000',
        ];
        $item = null;
        $message = '';
        $fields = [
            'order_status',
            'customer_id',
            'customer_name',
            'customer_phone',
            'customer_address',
            'device',
            'equipments',
            'device_sn',
            'problems',
            'actions',
            'service_status',
            'technician_id',
            'received_datetime',
            'checked_datetime',
            'worked_datetime',
            'completed_datetime',
            'picked_datetime',
            'payment_status',
            'down_payment',
            'estimated_cost',
            'total_cost',
            'warranty_start_date',
            'warranty_day_count',
            'notes'
        ];

        $request->validate($rules);

        $data = $request->only($fields);
        $data['device_sn'] = $data['device_sn'] ?? '';
        $data['notes'] = $data['notes'] ?? '';
        $data['estimated_cost'] = $data['estimated_cost'] ?? 0;
        $data['down_payment'] = $data['down_payment'] ?? 0;
        $data['total_cost'] = $data['total_cost'] ?? 0;
        $data['warranty_start_date'] = $data['warranty_start_date'] ?? null;
        $data['warranty_day_count'] = $data['warranty_day_count'] ?? 0;

        if (!$request->id) {
            $item = new ServiceOrder();
            $item->company_id = Auth::user()->company_id;
            $item->created_by_uid = Auth::user()->id;
            $item->created_datetime = date('Y-M-d H:i:s');
            $message = "Order Servis baru #$item->id telah ditambahkan.";
        } else {
            $item = ServiceOrder::findOrFail($request->post('id', 0));
            $item->updated_by_uid = Auth::user()->id;
            $item->updated_datetime = date('Y-M-d H:i:s');
            $message = "Order Servis #$item->id telah diperbarui.";
        }

        if ($item->company_id != Auth::user()->company_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($data['order_status'] == 'closed') {
            $item->closed_by_uid = Auth::user()->id;
            $item->closed_datetime = date('Y-M-d H:i:s');
        } else {
            $item->closed_by_uid = null;
            $item->closed_datetime = null;
        }

        DB::transaction(function () use ($data, $item, $request) {
            if (!$data['customer_id']) {
                $customer = Customer::create([
                    'company_id' => Auth::user()->company_id,
                    'name' => $request->input('customer_name'),
                    'phone' => $request->input('customer_phone'),
                    'address' => $request->input('customer_address'),
                ]);
                $data['customer_id'] = $customer->id;
            }

            $item->fill($data);
            $item->save();
        });

        return redirect(route('admin.service-order.index'))->with('success', $message);
    }

    public function delete($id)
    {
        allowed_roles(['admin']);

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
