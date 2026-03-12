@extends('layouts.the-index')

@section('title')
    الطلبات
@endsection

@section('css')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --clr-bg:        #f4f6fb;
        --clr-card:      #ffffff;
        --clr-border:    #e5e9f2;
        --clr-accent:    #6c63ff;
        --clr-accent2:   #00c49a;
        --clr-text:      #1e2438;
        --clr-muted:     #8a94a6;
        --clr-pending:   #d97706;
        --clr-process:   #2563eb;
        --clr-done:      #059669;
        --clr-cancelled: #dc2626;
    }

    #main { background: var(--clr-bg) !important; font-family: 'Cairo', sans-serif; color: var(--clr-text); }

    /* ── Stats Bar ── */
    .stats-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }
    .stat-card {
        background: var(--clr-card);
        border: 1px solid var(--clr-border);
        border-radius: 16px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform .2s, box-shadow .2s;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.09); }

    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .s-total   .stat-icon { background: rgba(108,99,255,.12); color: var(--clr-accent); }
    .s-pending .stat-icon { background: rgba(217,119,6,.12);   color: var(--clr-pending); }
    .s-done    .stat-icon { background: rgba(5,150,105,.12);    color: var(--clr-done); }
    .s-cancel  .stat-icon { background: rgba(220,38,38,.12);    color: var(--clr-cancelled); }

    .stat-info .num  { font-size: 28px; font-weight: 800; line-height: 1; color: var(--clr-text); }
    .stat-info .lbl  { font-size: 12px; color: var(--clr-muted); margin-top: 4px; }

    /* ── Filters Panel ── */
    .filters-panel {
        background: var(--clr-card);
        border: 1px solid var(--clr-border);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: flex-end;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }
    .filter-group { display: flex; flex-direction: column; gap: 6px; }
    .filter-group label { font-size: 11px; color: var(--clr-muted); font-weight: 700; text-transform: uppercase; letter-spacing: .6px; }
    .filter-input {
        background: #f8fafd;
        border: 1px solid var(--clr-border);
        border-radius: 10px;
        padding: 9px 14px;
        color: var(--clr-text);
        font-family: 'Cairo', sans-serif;
        font-size: 13px;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
        min-width: 160px;
    }
    .filter-input:focus { border-color: var(--clr-accent); box-shadow: 0 0 0 3px rgba(108,99,255,.1); }

    .btn-filter {
        background: var(--clr-accent);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 9px 22px;
        font-family: 'Cairo', sans-serif;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: opacity .2s, transform .2s;
        display: flex; align-items: center; gap: 7px;
    }
    .btn-filter:hover { opacity: .88; transform: translateY(-1px); }

    .btn-reset {
        background: transparent;
        color: var(--clr-muted);
        border: 1px solid var(--clr-border);
        border-radius: 10px;
        padding: 9px 16px;
        font-family: 'Cairo', sans-serif;
        font-size: 13px;
        cursor: pointer;
        transition: color .2s, border-color .2s, background .2s;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .btn-reset:hover { color: var(--clr-text); border-color: var(--clr-muted); background: #f4f6fb; }

    /* ── Table Card ── */
    .table-card {
        background: var(--clr-card);
        border: 1px solid var(--clr-border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }
    .table-card-header {
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--clr-border);
    }
    .table-card-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: var(--clr-text);
        display: flex; align-items: center; gap: 10px;
    }
    .table-card-header h5 .dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: var(--clr-accent2);
        box-shadow: 0 0 6px var(--clr-accent2);
        animation: pulse 2s infinite;
    }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.35} }

    /* ── Table ── */
    .orders-table { width: 100%; border-collapse: collapse; }
    .orders-table thead th {
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
        color: var(--clr-muted);
        background: #f8fafd;
        border-bottom: 1px solid var(--clr-border);
        white-space: nowrap;
    }
    .orders-table tbody tr {
        border-bottom: 1px solid var(--clr-border);
        transition: background .15s;
    }
    .orders-table tbody tr:last-child { border-bottom: none; }
    .orders-table tbody tr:hover { background: #f8fafd; }
    .orders-table tbody td { padding: 13px 16px; font-size: 13px; vertical-align: middle; }

    .avatar {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--clr-accent), var(--clr-accent2));
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 14px; color: #fff;
        flex-shrink: 0;
    }
    .name-cell { display: flex; align-items: center; gap: 10px; }
    .name-cell .name-text { font-weight: 700; color: var(--clr-text); }
    .name-cell .phone-text { font-size: 11px; color: var(--clr-muted); margin-top: 1px; }

    .size-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 800;
        background: rgba(108,99,255,.1);
        color: var(--clr-accent);
        border: 1px solid rgba(108,99,255,.2);
        letter-spacing: .5px;
    }
    .logo-count {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 12px; color: var(--clr-muted);
    }
    .logo-count i { color: var(--clr-accent2); }

    /* Status Select */
    .status-select {
        appearance: none;
        border: none;
        border-radius: 20px;
        padding: 5px 14px;
        font-family: 'Cairo', sans-serif;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        outline: none;
        transition: transform .15s;
    }
    .status-select:hover { transform: scale(1.04); }
    .status-select.s-pending    { background: rgba(217,119,6,.12);  color: var(--clr-pending); }
    .status-select.s-processing { background: rgba(37,99,235,.12);  color: var(--clr-process); }
    .status-select.s-done       { background: rgba(5,150,105,.12);  color: var(--clr-done); }
    .status-select.s-cancelled  { background: rgba(220,38,38,.12);  color: var(--clr-cancelled); }

    .date-cell { font-size: 12px; color: var(--clr-text); }
    .date-cell .time { display: block; font-size: 11px; color: var(--clr-muted); }

    /* Action Buttons */
    .action-btn {
        width: 32px; height: 32px;
        border-radius: 9px;
        border: 1px solid var(--clr-border);
        background: transparent;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 14px;
        cursor: pointer;
        transition: background .2s, color .2s, border-color .2s, transform .15s;
        text-decoration: none;
        color: var(--clr-muted);
    }
    .action-btn:hover { transform: scale(1.12); }
    .action-btn.view-btn:hover { background: rgba(37,99,235,.1);  color: #2563eb; border-color: rgba(37,99,235,.3); }
    .action-btn.del-btn:hover  { background: rgba(220,38,38,.1);  color: #dc2626; border-color: rgba(220,38,38,.3); }

    /* Empty state */
    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state .empty-icon { font-size: 48px; opacity: .3; margin-bottom: 16px; }
    .empty-state p { color: var(--clr-muted); font-size: 14px; }

    /* Pagination */
    .pagination-wrap { padding: 20px 24px; border-top: 1px solid var(--clr-border); }
    .pagination-wrap .pagination .page-link {
        background: var(--clr-card);
        border-color: var(--clr-border);
        color: var(--clr-text);
        font-family: 'Cairo', sans-serif;
        border-radius: 8px !important;
        margin: 0 2px;
    }
    .pagination-wrap .pagination .page-item.active .page-link {
        background: var(--clr-accent);
        border-color: var(--clr-accent);
        color: #fff;
    }

    /* Toast */
    .toast-msg {
        position: fixed;
        top: 24px; left: 50%;
        transform: translateX(-50%) translateY(-20px);
        background: var(--clr-card);
        border: 1px solid var(--clr-border);
        border-radius: 12px;
        padding: 12px 22px;
        font-size: 13px;
        font-family: 'Cairo', sans-serif;
        font-weight: 600;
        color: var(--clr-text);
        z-index: 9999;
        opacity: 0;
        transition: opacity .3s, transform .3s;
        display: flex; align-items: center; gap: 9px;
        box-shadow: 0 8px 32px rgba(0,0,0,.12);
    }
    .toast-msg.show { opacity: 1; transform: translateX(-50%) translateY(0); }
    .toast-msg.success { border-color: rgba(5,150,105,.35); }
    .toast-msg.error   { border-color: rgba(220,38,38,.35); }

    @media (max-width: 768px) {
        .stats-bar { grid-template-columns: repeat(2,1fr); }
        .filters-panel { flex-direction: column; }
        .filter-input { min-width: 100%; }
    }
</style>
@endsection

@section('content')
<main id="main" class="main">

<div id="toast" class="toast-msg"></div>

<div class="pagetitle" style="margin-bottom:24px;">
    <h1 style="font-size:24px;font-weight:800;">لوحة الطلبات</h1>
    <nav>
        <ol class="breadcrumb" style="font-size:12px;">
            <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
            <li class="breadcrumb-item active">الطلبات</li>
        </ol>
    </nav>
</div>

<section class="section">

    {{-- ── Stats Bar ── --}}
    <div class="stats-bar">
        <div class="stat-card s-total">
            <div class="stat-icon"><i class="bi bi-bag-check"></i></div>
            <div class="stat-info">
                <div class="num">{{ $orders->total() }}</div>
                <div class="lbl">إجمالي الطلبات</div>
            </div>
        </div>
        <div class="stat-card s-pending">
            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-info">
                <div class="num">{{ $pendingCount }}</div>
                <div class="lbl">قيد الانتظار</div>
            </div>
        </div>
        <div class="stat-card s-done">
            <div class="stat-icon"><i class="bi bi-check2-circle"></i></div>
            <div class="stat-info">
                <div class="num">{{ $doneCount }}</div>
                <div class="lbl">تم التسليم</div>
            </div>
        </div>
        <div class="stat-card s-cancel">
            <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
            <div class="stat-info">
                <div class="num">{{ $cancelledCount }}</div>
                <div class="lbl">ملغي</div>
            </div>
        </div>
    </div>

    {{-- ── Filters Panel ── --}}
    <form method="GET" action="{{ route('orders.index') }}" id="filterForm">
        <div class="filters-panel">
            <div class="filter-group" style="flex:1;min-width:180px;">
                <label><i class="bi bi-search"></i> بحث</label>
                <input type="text" name="search" class="filter-input"
                       placeholder="الاسم أو رقم الهاتف..."
                       value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <label><i class="bi bi-circle-half"></i> الحالة</label>
                <select name="status" class="filter-input">
                    <option value="">كل الحالات</option>
                    <option value="pending"    {{ request('status')=='pending'    ? 'selected':'' }}>قيد الانتظار</option>
                    <option value="processing" {{ request('status')=='processing' ? 'selected':'' }}>جاري التنفيذ</option>
                    <option value="done"       {{ request('status')=='done'       ? 'selected':'' }}>تم التسليم</option>
                    <option value="cancelled"  {{ request('status')=='cancelled'  ? 'selected':'' }}>ملغي</option>
                </select>
            </div>
            <div class="filter-group">
                <label><i class="bi bi-rulers"></i> المقاس</label>
                <select name="size" class="filter-input">
                    <option value="">كل المقاسات</option>
                    @foreach(['S','M','L','XL','XXL'] as $s)
                        <option value="{{ $s }}" {{ request('size')==$s ? 'selected':'' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label><i class="bi bi-calendar-range"></i> من</label>
                <input type="date" name="date_from" class="filter-input" value="{{ request('date_from') }}">
            </div>
            <div class="filter-group">
                <label><i class="bi bi-calendar-range"></i> إلى</label>
                <input type="date" name="date_to" class="filter-input" value="{{ request('date_to') }}">
            </div>
            <div class="filter-group">
                <label>&nbsp;</label>
                <div style="display:flex;gap:8px;">
                    <button type="submit" class="btn-filter">
                        <i class="bi bi-funnel-fill"></i> فلتر
                    </button>
                    <a href="{{ route('orders.index') }}" class="btn-reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- ── Table Card ── --}}
    <div class="table-card">
        <div class="table-card-header">
            <h5>
                <span class="dot"></span>
                الطلبات
                <span style="font-size:13px;color:var(--clr-muted);font-weight:400;">({{ $orders->total() }} نتيجة)</span>
            </h5>
            <div style="font-size:12px;color:var(--clr-muted);">
                <i class="bi bi-clock"></i>
                آخر تحديث: {{ now()->format('H:i') }}
            </div>
        </div>

        <div style="overflow-x:auto;">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>العميل</th>
                    <th>العنوان</th>
                    <th>المقاس</th>
                    <th>اللوجوهات</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th style="text-align:center;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr data-id="{{ $order->id }}">
                    <td style="color:var(--clr-muted);font-size:12px;">
                        #{{ $orders->firstItem() + $loop->index }}
                    </td>
                    <td>
                        <div class="name-cell">
                            <div class="avatar">{{ mb_substr($order->name,0,1) }}</div>
                            <div>
                                <div class="name-text">{{ $order->name }}</div>
                                <div class="phone-text"><i class="bi bi-telephone"></i> {{ $order->phone }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--clr-muted);max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $order->address }}">
                        <i class="bi bi-geo-alt" style="color:var(--clr-accent);"></i>
                        {{ $order->address }}
                    </td>
                    <td><span class="size-badge">{{ $order->size }}</span></td>
                    <td>
                        <span class="logo-count">
                            <i class="bi bi-images"></i>
                            {{ count($order->logos) }} لوجو
                        </span>
                    </td>
                    <td>
                        <select class="status-select s-{{ $order->status }}"
                                data-id="{{ $order->id }}"
                                onchange="updateStatus(this)">
                            @foreach(['pending'=>'قيد الانتظار','processing'=>'جاري التنفيذ','done'=>'تم التسليم','cancelled'=>'ملغي'] as $val=>$lbl)
                                <option value="{{ $val }}" {{ $order->status===$val?'selected':'' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="date-cell">
                        {{ $order->created_at->format('d/m/Y') }}
                        <span class="time">{{ $order->created_at->format('H:i') }}</span>
                    </td>
                    <td style="text-align:center;">
                        <div style="display:flex;gap:6px;justify-content:center;">
                            <a href="{{ route('orders.show', $order->id) }}" class="action-btn view-btn" title="عرض">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('orders.destroy', $order->id) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirmDelete(event, this)">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn del-btn" title="حذف">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="empty-icon">📦</div>
                            <p>لا توجد طلبات تطابق معايير البحث</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        @if($orders->hasPages())
        <div class="pagination-wrap">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</section>
</main>
@endsection

@section('js')
<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content
              || '{{ csrf_token() }}';

    function showToast(msg, type = 'success') {
        const t = document.getElementById('toast');
        t.className = `toast-msg ${type}`;
        t.innerHTML = (type === 'success' ? '✅ ' : '❌ ') + msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2800);
    }

    function updateStatus(select) {
        const orderId = select.dataset.id;
        const status  = select.value;
        select.className = `status-select s-${status}`;

        fetch(`/orders/${orderId}/status-ajax`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ status })
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) showToast('تم تحديث الحالة بنجاح');
            else showToast('حدث خطأ في التحديث', 'error');
        })
        .catch(() => showToast('تعذر الاتصال بالخادم', 'error'));
    }

    function confirmDelete(e, form) {
        e.preventDefault();
        if (!confirm('هل أنت متأكد من حذف هذا الطلب؟')) return;
        const btn = form.querySelector('button');
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i>';
        btn.disabled = true;
        form.submit();
    }

    document.querySelectorAll('#filterForm select').forEach(s => {
        s.addEventListener('change', () => document.getElementById('filterForm').submit());
    });
</script>
@endsection