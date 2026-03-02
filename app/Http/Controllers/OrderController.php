<?php

namespace App\Http\Controllers;

use App\Models\LogoSection;
use App\Models\order as OrderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $sections = LogoSection::with('logos')->get();
        return view('designer', compact('sections'));
    }

    // ===== استقبال الطلب من الـ AJAX =====
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                      => 'required|string|max:100',
            'phone'                     => 'required|string|max:20',
            'address'                   => 'required|string|max:200',
            'size'                      => 'required|in:S,M,L,XL,XXL',
            'notes'                     => 'nullable|string|max:500',
            'logos'                     => 'required|array|min:1',
            'logos.*.src'               => 'required|string',
            'logos.*.view'              => 'required|in:front,back,left,right',
            'logos.*.x_percent'         => 'required|numeric',
            'logos.*.y_percent'         => 'required|numeric',
            'logos.*.width_percent'     => 'required|numeric',
            'logos.*.height_percent'    => 'required|numeric',
            'logos.*.rotation'          => 'required|numeric',
        ]);

        $order = OrderModel::create($validated);

        return response()->json([
            'success'  => true,
            'order_id' => $order->id,
            'message'  => 'تم إرسال الطلب بنجاح',
        ]);
    }

    // ===== لوحة تحكم الأدمن - قائمة الطلبات =====
    public function adminIndex()
    {
        $orders = OrderModel::latest()->paginate(15);
        return view('dashboard.orders.index', compact('orders'));
    }

    // ===== عرض طلب واحد بالتفصيل =====
    public function adminShow(OrderModel $order)
    {
        return view('dashboard.orders.show', compact('order'));
    }

    // ===== تحديث الـ status =====
    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,done,cancelled'
        ]);

        $order = OrderModel::findOrFail($orderId);
        $order->update(['status' => $request->status]);

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    // دالة الـ AJAX - JSON دايماً
    public function updateStatusAjax(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,done,cancelled'
        ]);

        $order = OrderModel::findOrFail($orderId);
        $order->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }
    public function destroy($orderId)
    {
        $order = OrderModel::findOrFail($orderId);
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'تم حذف الطلب بنجاح');
    }
    public function uploadTemp(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp,svg|max:5120',
        ]);

        $file      = $request->file('image');
        $filename  = 'logo_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        // احفظ في نفس مجلد الـ logos الأصلية عشان يفضل متاح
        $path      = $file->storeAs('logos/images', $filename, 'public');

        return response()->json([
            'url'  => asset('storage/' . $path),
            'path' => $path,
        ]);
    }
}
