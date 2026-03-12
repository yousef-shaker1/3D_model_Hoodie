<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Models\order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── إحصائيات سريعة ──
        $totalOrders     = order::count();
        $pendingCount    = order::where('status', 'pending')->count();
        $processingCount = order::where('status', 'processing')->count();
        $doneCount       = order::where('status', 'done')->count();
        $cancelledCount  = order::where('status', 'cancelled')->count();

        // ── طلبات آخر 30 يوم (للرسم البياني الخطي) ──
        $last30Days = order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(29))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // نملأ الأيام الفاضية بـ 0
        $chartDates  = [];
        $chartCounts = [];
        for ($i = 29; $i >= 0; $i--) {
            $date          = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartDates[]  = Carbon::now()->subDays($i)->format('d/m');
            $chartCounts[] = $last30Days->has($date) ? $last30Days[$date]->count : 0;
        }

        // ── توزيع المقاسات ──
        $sizeStats = order::select('size', DB::raw('COUNT(*) as count'))
            ->groupBy('size')
            ->orderBy('count', 'desc')
            ->get();

        // ── توزيع الحالات (للـ Donut) ──
        $statusStats = [
            'pending'    => $pendingCount,
            'processing' => $processingCount,
            'done'       => $doneCount,
            'cancelled'  => $cancelledCount,
        ];

        // ── أحدث 8 طلبات ──
        $latestOrders = order::latest()->take(8)->get();

        // ── طلبات هذا الشهر vs الشهر الماضي ──
        $thisMonth = order::whereMonth('created_at', Carbon::now()->month)
                               ->whereYear('created_at', Carbon::now()->year)
                               ->count();

        $lastMonth = order::whereMonth('created_at', Carbon::now()->subMonth()->month)
                               ->whereYear('created_at', Carbon::now()->subMonth()->year)
                               ->count();

        $monthGrowth = $lastMonth > 0
            ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1)
            : ($thisMonth > 0 ? 100 : 0);

        // ── أكثر المقاسات طلباً ──
        $topSize = order::select('size', DB::raw('COUNT(*) as count'))
            ->groupBy('size')
            ->orderBy('count', 'desc')
            ->first();

        return view('dashboard.index', compact(
            'totalOrders',
            'pendingCount',
            'processingCount',
            'doneCount',
            'cancelledCount',
            'chartDates',
            'chartCounts',
            'sizeStats',
            'statusStats',
            'latestOrders',
            'thisMonth',
            'lastMonth',
            'monthGrowth',
            'topSize'
        ));
    }

    public function login()
    {
        return view('dashboard.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password',
        ]);
    }
}
