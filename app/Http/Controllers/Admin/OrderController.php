<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        if (method_exists($this, 'middleware')) {
            $this->middleware(['auth','admin']);
        }
    }

    public function index(Request $request)
    {
        $query = Order::with('product')->orderByDesc('created_at');

        if ($request->filled('search')) {
            $q = $request->input('search');
            $query->where(function($w) use ($q) {
                $w->where('customer_name', 'like', "%{$q}%")
                  ->orWhere('customer_phone', 'like', "%{$q}%");
            });
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('from')) {
            $from = \Carbon\Carbon::parse($request->input('from'))->startOfDay();
            $query->where('created_at', '>=', $from);
        }

        if ($request->filled('to')) {
            $to = \Carbon\Carbon::parse($request->input('to'))->endOfDay();
            $query->where('created_at', '<=', $to);
        }

        // Debug (tests): log SQL when running tests
        if (app()->environment('testing')) {
            \Illuminate\Support\Facades\Log::info('Order query SQL', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);
        }

        // Export CSV
        if ($request->input('export') === 'csv') {
            $rows = $query->get()->map(function($o) {
                return [
                    'id' => $o->id,
                    'product' => $o->product->name ?? '',
                    'customer_name' => $o->customer_name,
                    'customer_phone' => $o->customer_phone,
                    'total_price' => $o->total_price,
                    'status' => $o->status,
                    'created_at' => $o->created_at->toDateTimeString(),
                ];
            })->toArray();

            $filename = 'orders_'.now()->format('Ymd_His').'.csv';
            $handle = fopen('php://memory','r+');
            // header
            fputcsv($handle, array_keys($rows[0] ?? []));
            foreach ($rows as $r) {
                fputcsv($handle, $r);
            }
            rewind($handle);
            $contents = stream_get_contents($handle);
            fclose($handle);

            return response($contents, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);
        }

        $orders = $query->paginate(20)->appends($request->only(['search','product_id','status','from','to']));

        $products = \App\Models\Product::select('id','name')->orderBy('name')->get();

        return view('admin.orders.index', compact('orders','products'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate(['status' => 'required|string']);
        $order->update(['status' => $data['status']]);

        return redirect()->back()->with('success', 'Statut mis à jour.');
    }
}
