@extends('layouts.the-index')

@section('title')
    لوحة التحكم
@endsection

@section('css')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root {
    --bg:       #f0f2f8;
    --card:     #ffffff;
    --border:   #e8ecf4;
    --text:     #1a1f36;
    --muted:    #8992a8;
    --accent:   #5b5ef4;
    --accent2:  #0ec9a0;
    --warn:     #f5a623;
    --danger:   #e8455a;
    --blue:     #3b82f6;
    --shadow:   0 2px 12px rgba(0,0,0,.06);
    --shadow-lg:0 8px 32px rgba(0,0,0,.10);
}

* { box-sizing: border-box; }
#main { background: var(--bg) !important; font-family: 'Cairo', sans-serif; color: var(--text); }

/* ══ Page Header ══ */
.dash-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    flex-wrap: wrap;
    gap: 12px;
}
.dash-header h1 { font-size: 22px; font-weight: 900; margin: 0; }
.dash-date {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 8px 16px;
    font-size: 12px;
    color: var(--muted);
    display: flex; align-items: center; gap: 6px;
    box-shadow: var(--shadow);
}

/* ══ KPI Grid ══ */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.kpi-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 20px 18px;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow);
    transition: transform .2s, box-shadow .2s;
    cursor: default;
}
.kpi-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }
.kpi-card::after {
    content: '';
    position: absolute;
    bottom: -18px; right: -18px;
    width: 72px; height: 72px;
    border-radius: 50%;
    opacity: .08;
}
.kpi-card.k-total::after  { background: var(--accent);  width:90px; height:90px; }
.kpi-card.k-pend::after   { background: var(--warn); }
.kpi-card.k-proc::after   { background: var(--blue); }
.kpi-card.k-done::after   { background: var(--accent2); }
.kpi-card.k-cancel::after { background: var(--danger); }

.kpi-icon {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    margin-bottom: 14px;
}
.k-total  .kpi-icon { background: rgba(91,94,244,.12); color: var(--accent); }
.k-pend   .kpi-icon { background: rgba(245,166,35,.12); color: var(--warn); }
.k-proc   .kpi-icon { background: rgba(59,130,246,.12); color: var(--blue); }
.k-done   .kpi-icon { background: rgba(14,201,160,.12); color: var(--accent2); }
.k-cancel .kpi-icon { background: rgba(232,69,90,.12);  color: var(--danger); }

.kpi-num   { font-size: 30px; font-weight: 900; line-height: 1; }
.kpi-label { font-size: 12px; color: var(--muted); margin-top: 4px; font-weight: 600; }
.kpi-badge {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: 11px; font-weight: 700;
    padding: 2px 8px; border-radius: 20px;
    margin-top: 8px;
}
.kpi-badge.up   { background: rgba(14,201,160,.12); color: var(--accent2); }
.kpi-badge.down { background: rgba(232,69,90,.12);  color: var(--danger); }
.kpi-badge.flat { background: rgba(137,146,168,.12); color: var(--muted); }

/* ══ Charts Row ══ */
.charts-row {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px;
    margin-bottom: 20px;
}

/* ══ Card Base ══ */
.dash-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 22px 24px;
    box-shadow: var(--shadow);
}
.dash-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.dash-card-header h6 {
    margin: 0;
    font-size: 14px;
    font-weight: 800;
    display: flex; align-items: center; gap: 8px;
}
.dash-card-header h6 .dot {
    width: 7px; height: 7px; border-radius: 50%;
    animation: pulse 2s infinite;
}
.dot-accent  { background: var(--accent);  box-shadow: 0 0 6px var(--accent); }
.dot-accent2 { background: var(--accent2); box-shadow: 0 0 6px var(--accent2); }
.dot-warn    { background: var(--warn);    box-shadow: 0 0 6px var(--warn); }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.3} }

.card-tag {
    font-size: 11px; color: var(--muted);
    background: var(--bg);
    padding: 4px 10px; border-radius: 8px;
    border: 1px solid var(--border);
}

/* ══ Bottom Row ══ */
.bottom-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* ══ Donut ══ */
#donutChart { display: block; margin: 0 auto; }
.donut-legend { margin-top: 18px; display: flex; flex-direction: column; gap: 9px; }
.legend-item {
    display: flex; align-items: center; justify-content: space-between;
    font-size: 13px;
}
.legend-left { display: flex; align-items: center; gap: 8px; }
.legend-dot { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }
.legend-val { font-weight: 800; font-size: 14px; }
.legend-bar-wrap { height: 4px; background: var(--border); border-radius: 4px; margin-top: 3px; overflow: hidden; }
.legend-bar { height: 4px; border-radius: 4px; transition: width 1s ease; }

/* ══ Size Bars ══ */
.size-list { display: flex; flex-direction: column; gap: 14px; }
.size-row { }
.size-top { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 5px; }
.size-name { font-weight: 700; }
.size-count { color: var(--muted); }
.size-track { height: 8px; background: var(--border); border-radius: 8px; overflow: hidden; }
.size-fill { height: 8px; border-radius: 8px; transition: width 1.2s cubic-bezier(.4,0,.2,1); }

/* ══ Latest Orders Table ══ */
.latest-table { width: 100%; border-collapse: collapse; }
.latest-table thead th {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .7px; color: var(--muted);
    padding: 8px 12px; background: var(--bg);
    border-bottom: 1px solid var(--border);
}
.latest-table tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
.latest-table tbody tr:last-child { border-bottom: none; }
.latest-table tbody tr:hover { background: #f8f9fd; }
.latest-table tbody td { padding: 11px 12px; font-size: 12px; vertical-align: middle; }

.mini-avatar {
    width: 30px; height: 30px; border-radius: 8px;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800; color: #fff;
}
.status-pill {
    display: inline-block; padding: 2px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 700;
}
.sp-pending    { background: rgba(245,166,35,.12);  color: var(--warn); }
.sp-processing { background: rgba(59,130,246,.12);  color: var(--blue); }
.sp-done       { background: rgba(14,201,160,.12);  color: var(--accent2); }
.sp-cancelled  { background: rgba(232,69,90,.12);   color: var(--danger); }

.view-link {
    width: 28px; height: 28px; border-radius: 8px;
    background: var(--bg); border: 1px solid var(--border);
    display: inline-flex; align-items: center; justify-content: center;
    color: var(--muted); font-size: 13px; text-decoration: none;
    transition: background .2s, color .2s;
}
.view-link:hover { background: rgba(91,94,244,.1); color: var(--accent); border-color: rgba(91,94,244,.3); }

/* ══ Animations ══ */
.dash-card, .kpi-card {
    animation: fadeUp .4s ease both;
}
.kpi-card:nth-child(1) { animation-delay: .05s; }
.kpi-card:nth-child(2) { animation-delay: .10s; }
.kpi-card:nth-child(3) { animation-delay: .15s; }
.kpi-card:nth-child(4) { animation-delay: .20s; }
.kpi-card:nth-child(5) { animation-delay: .25s; }
@keyframes fadeUp {
    from { opacity:0; transform: translateY(16px); }
    to   { opacity:1; transform: translateY(0); }
}

@media (max-width: 1100px) {
    .kpi-grid { grid-template-columns: repeat(3,1fr); }
    .charts-row { grid-template-columns: 1fr; }
    .bottom-row { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .kpi-grid { grid-template-columns: repeat(2,1fr); }
}
</style>
@endsection

@section('content')
<main id="main" class="main">

{{-- ══ Header ══ --}}
<div class="dash-header">
    <div>
        <h1>👋 مرحباً، لوحة التحكم</h1>
        <p style="margin:4px 0 0;font-size:13px;color:var(--muted);">نظرة عامة على أداء الطلبات</p>
    </div>
    <div class="dash-date">
        <i class="bi bi-calendar3"></i>
        {{ now()->translatedFormat('l، d F Y') }}
    </div>
</div>

{{-- ══ KPI Cards ══ --}}
<div class="kpi-grid">
    <div class="kpi-card k-total">
        <div class="kpi-icon"><i class="bi bi-bag-check-fill"></i></div>
        <div class="kpi-num">{{ $totalOrders }}</div>
        <div class="kpi-label">إجمالي الطلبات</div>
        <div class="kpi-badge {{ $monthGrowth > 0 ? 'up' : ($monthGrowth < 0 ? 'down' : 'flat') }}">
            <i class="bi bi-arrow-{{ $monthGrowth >= 0 ? 'up' : 'down' }}-short"></i>
            {{ abs($monthGrowth) }}% هذا الشهر
        </div>
    </div>
    <div class="kpi-card k-pend">
        <div class="kpi-icon"><i class="bi bi-hourglass-split"></i></div>
        <div class="kpi-num">{{ $pendingCount }}</div>
        <div class="kpi-label">قيد الانتظار</div>
    </div>
    <div class="kpi-card k-proc">
        <div class="kpi-icon"><i class="bi bi-gear-fill"></i></div>
        <div class="kpi-num">{{ $processingCount }}</div>
        <div class="kpi-label">جاري التنفيذ</div>
    </div>
    <div class="kpi-card k-done">
        <div class="kpi-icon"><i class="bi bi-check-circle-fill"></i></div>
        <div class="kpi-num">{{ $doneCount }}</div>
        <div class="kpi-label">تم التسليم</div>
    </div>
    <div class="kpi-card k-cancel">
        <div class="kpi-icon"><i class="bi bi-x-circle-fill"></i></div>
        <div class="kpi-num">{{ $cancelledCount }}</div>
        <div class="kpi-label">ملغي</div>
    </div>
</div>

{{-- ══ Charts Row ══ --}}
<div class="charts-row">

    {{-- Line Chart --}}
    <div class="dash-card">
        <div class="dash-card-header">
            <h6><span class="dot dot-accent"></span> الطلبات — آخر 30 يوم</h6>
            <span class="card-tag">{{ $thisMonth }} هذا الشهر</span>
        </div>
        <canvas id="lineChart" height="110"></canvas>
    </div>

    {{-- Donut --}}
    <div class="dash-card">
        <div class="dash-card-header">
            <h6><span class="dot dot-accent2"></span> توزيع الحالات</h6>
            <span class="card-tag">{{ $totalOrders }} طلب</span>
        </div>
        <canvas id="donutChart" height="170"></canvas>
        <div class="donut-legend">
            @php
                $statusLabels = ['pending'=>'انتظار','processing'=>'تنفيذ','done'=>'تسليم','cancelled'=>'ملغي'];
                $statusColors = ['pending'=>'#f5a623','processing'=>'#3b82f6','done'=>'#0ec9a0','cancelled'=>'#e8455a'];
                $total = max($totalOrders, 1);
            @endphp
            @foreach($statusStats as $key => $val)
            <div class="legend-item">
                <div class="legend-left">
                    <div class="legend-dot" style="background:{{ $statusColors[$key] }}"></div>
                    <span>{{ $statusLabels[$key] }}</span>
                </div>
                <span class="legend-val">{{ $val }}</span>
            </div>
            <div class="legend-bar-wrap">
                <div class="legend-bar" style="width:{{ round(($val/$total)*100) }}%; background:{{ $statusColors[$key] }}"></div>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- ══ Bottom Row ══ --}}
<div class="bottom-row">

    {{-- Latest Orders --}}
    <div class="dash-card" style="padding:0;overflow:hidden;">
        <div class="dash-card-header" style="padding:20px 24px 0;">
            <h6><span class="dot dot-accent"></span> أحدث الطلبات</h6>
            <a href="{{ route('orders.index') }}" style="font-size:12px;color:var(--accent);text-decoration:none;font-weight:700;">
                عرض الكل <i class="bi bi-arrow-left"></i>
            </a>
        </div>
        <div style="overflow-x:auto;">
        <table class="latest-table">
            <thead>
                <tr>
                    <th>العميل</th>
                    <th>المقاس</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestOrders as $order)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div class="mini-avatar">{{ mb_substr($order->name,0,1) }}</div>
                            <div>
                                <div style="font-weight:700;font-size:12px;">{{ $order->name }}</div>
                                <div style="font-size:11px;color:var(--muted);">{{ $order->phone }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-weight:800;font-size:12px;color:var(--accent);">{{ $order->size }}</span>
                    </td>
                    <td>
                        <span class="status-pill sp-{{ $order->status }}">
                            @php $labels=['pending'=>'انتظار','processing'=>'تنفيذ','done'=>'تسليم','cancelled'=>'ملغي'] @endphp
                            {{ $labels[$order->status] }}
                        </span>
                    </td>
                    <td style="color:var(--muted);font-size:11px;">{{ $order->created_at->format('d/m H:i') }}</td>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" class="view-link">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:30px;color:var(--muted);">لا توجد طلبات</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    {{-- Size Distribution --}}
    <div class="dash-card">
        <div class="dash-card-header">
            <h6><span class="dot dot-warn"></span> توزيع المقاسات</h6>
            @if($topSize)
            <span class="card-tag">الأكثر: {{ $topSize->size }}</span>
            @endif
        </div>
        <div class="size-list">
            @php
                $sizeColors = ['S'=>'#5b5ef4','M'=>'#3b82f6','L'=>'#0ec9a0','XL'=>'#f5a623','XXL'=>'#e8455a'];
                $maxSize = $sizeStats->max('count') ?: 1;
                $total   = max($totalOrders, 1);
            @endphp
            @forelse($sizeStats as $s)
            <div class="size-row">
                <div class="size-top">
                    <span class="size-name">{{ $s->size }}</span>
                    <span class="size-count">{{ $s->count }} طلب &nbsp;
                        <span style="color:var(--text);font-weight:700;">{{ round(($s->count/$total)*100) }}%</span>
                    </span>
                </div>
                <div class="size-track">
                    <div class="size-fill"
                         style="width:{{ round(($s->count/$maxSize)*100) }}%;
                                background:{{ $sizeColors[$s->size] ?? '#5b5ef4' }}">
                    </div>
                </div>
            </div>
            @empty
            <p style="text-align:center;color:var(--muted);font-size:13px;">لا توجد بيانات</p>
            @endforelse
        </div>

        {{-- Bar Chart للمقاسات --}}
        <div style="margin-top:24px;">
            <canvas id="sizeChart" height="140"></canvas>
        </div>
    </div>

</div>

</main>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'Cairo', sans-serif";
Chart.defaults.color = '#8992a8';

// ── Line Chart ──
const lineCtx = document.getElementById('lineChart').getContext('2d');
const lineGrad = lineCtx.createLinearGradient(0, 0, 0, 220);
lineGrad.addColorStop(0, 'rgba(91,94,244,.25)');
lineGrad.addColorStop(1, 'rgba(91,94,244,.0)');

new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartDates) !!},
        datasets: [{
            label: 'الطلبات',
            data: {!! json_encode($chartCounts) !!},
            borderColor: '#5b5ef4',
            borderWidth: 2.5,
            backgroundColor: lineGrad,
            fill: true,
            tension: .4,
            pointBackgroundColor: '#5b5ef4',
            pointRadius: 3,
            pointHoverRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#1a1f36',
                bodyColor: '#5b5ef4',
                borderColor: '#e8ecf4',
                borderWidth: 1,
                padding: 10,
                callbacks: { label: ctx => ` ${ctx.parsed.y} طلب` }
            }
        },
        scales: {
            x: { grid: { display: false }, border: { display: false }, ticks: { maxTicksLimit: 8, font:{size:11} } },
            y: { grid: { color: '#f0f2f8' }, border: { display: false }, beginAtZero: true, ticks: { stepSize: 1, font:{size:11} } }
        }
    }
});

// ── Donut Chart ──
new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        labels: ['انتظار', 'تنفيذ', 'تسليم', 'ملغي'],
        datasets: [{
            data: [
                {{ $statusStats['pending'] }},
                {{ $statusStats['processing'] }},
                {{ $statusStats['done'] }},
                {{ $statusStats['cancelled'] }}
            ],
            backgroundColor: ['#f5a623','#3b82f6','#0ec9a0','#e8455a'],
            borderWidth: 0,
            hoverOffset: 8,
        }]
    },
    options: {
        cutout: '70%',
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#1a1f36',
                bodyColor: '#5b5ef4',
                borderColor: '#e8ecf4',
                borderWidth: 1,
                padding: 10,
            }
        }
    }
});

// ── Size Bar Chart ──
@php
    $sizeLabels = $sizeStats->pluck('size')->toArray();
    $sizeCounts = $sizeStats->pluck('count')->toArray();
    $sizeColorArr = $sizeStats->map(fn($s) => $sizeColors[$s->size] ?? '#5b5ef4')->toArray();
@endphp
new Chart(document.getElementById('sizeChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($sizeLabels) !!},
        datasets: [{
            data: {!! json_encode($sizeCounts) !!},
            backgroundColor: {!! json_encode(array_values($sizeColorArr)) !!},
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} طلب` } } },
        scales: {
            x: { grid: { display: false }, border: { display: false } },
            y: { grid: { color: '#f0f2f8' }, border: { display: false }, beginAtZero: true, ticks: { stepSize: 1 } }
        }
    }
});
</script>
@endsection