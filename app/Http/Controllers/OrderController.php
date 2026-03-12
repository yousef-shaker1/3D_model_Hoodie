<?php

namespace App\Http\Controllers;

use App\Http\Controllers\back\LogoController;
use App\Models\LogoSection;
use App\Models\order as OrderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $sections = LogoSection::with('logos')->get();
        // return view('designer', compact('sections'));
    }

    // ===== استقبال الطلب من الـ AJAX =====
    public function store(Request $request)
{
    $validated = $request->validate([
        'name'    => 'required|string',
        'phone'   => 'required|string',
        'address' => 'required|string',
        'size'    => 'required|string',
        'notes'   => 'nullable|string',
        'color'   => 'required|string',
        'logos'   => 'nullable|array',
    ]);

    // انقل كل صورة من temp لـ permanent
    $logoController = new LogoController();
    $logos = collect($request->logos ?? [])->map(function ($logo) use ($logoController) {
        if (isset($logo['path'])) {
            // انقلها وحدّث الـ URL
            $logo['url'] = $logoController->moveToPermanent($logo['path']);
            unset($logo['path']);
        }
        return $logo;
    })->toArray();

    $order = OrderModel::create([
        'name'    => $validated['name'],
        'phone'   => $validated['phone'],
        'address' => $validated['address'],
        'size'    => $validated['size'],
        'notes'   => $validated['notes'] ?? null,
        'color'   => $validated['color'],
        'logos'   => $logos,  // ← JSON مع URLs دائمة
    ]);

    return response()->json([
        'success'  => true,
        'order_id' => $order->id,
    ]);
}

    // ===== لوحة تحكم الأدمن - قائمة الطلبات =====
    public function adminIndex(Request $request)
    {
        $query = OrderModel::latest();

        // فلتر البحث (اسم أو هاتف)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // فلتر الحالة
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // فلتر المقاس
        if ($size = $request->input('size')) {
            $query->where('size', $size);
        }

        // فلتر التاريخ
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $orders = $query->paginate(15)->withQueryString();

        $pendingCount   = OrderModel::where('status', 'pending')->count();
        $doneCount      = OrderModel::where('status', 'done')->count();
        $cancelledCount = OrderModel::where('status', 'cancelled')->count();

        return view('dashboard.orders.index', compact(
            'orders',
            'pendingCount',
            'doneCount',
            'cancelledCount'
        ));
    }

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
    $request->validate(['image' => 'required|image|max:5120']);

    $path = $request->file('image')->store('logos/temp', 'public');

    return response()->json([
        'url' => Storage::disk('public')->url($path),
        'path' => $path,  // ← مهم: ابعته للـ frontend عشان تبعته مع الأوردر
    ]);
}

public function moveToPermanent(string $tempPath): string
{
    $filename  = basename($tempPath);
    $permPath  = 'logos/orders/' . $filename;

    Storage::disk('public')->move($tempPath, $permPath);

    return Storage::disk('public')->url($permPath);
}
}
