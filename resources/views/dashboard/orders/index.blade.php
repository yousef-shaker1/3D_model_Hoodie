@extends('layouts.the-index')

@section('title')
    الطلبات
@endsection

@section('css')
<style>
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-pending    { background: #fff3cd; color: #856404; }
    .status-processing { background: #cfe2ff; color: #084298; }
    .status-done       { background: #d1e7dd; color: #0a3622; }
    .status-cancelled  { background: #f8d7da; color: #842029; }
</style>
@endsection

@section('content')
<main id="main" class="main">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="pagetitle">
        <h1>الطلبات</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Orders</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="padding: 20px;">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">الطلبات ({{ $orders->total() }})</h5>
                    </div>

                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>الهاتف</th>
                                <th>العنوان</th>
                                <th>المقاس</th>
                                <th>اللوجوهات</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $order->name }}</strong></td>
                                    <td>{{ $order->phone }}</td>
                                    <td>{{ $order->address }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $order->size }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ count($order->logos) }} لوجو</span>
                                    </td>
                                    <td>
                                        <select class="status-select form-select form-select-sm"
                                                data-id="{{ $order->id }}"
                                                style="width: auto; min-width: 130px;">
                                            @foreach(['pending' => 'قيد الانتظار', 'processing' => 'جاري التنفيذ', 'done' => 'تم التسليم', 'cancelled' => 'ملغي'] as $val => $label)
                                                <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-muted" style="font-size: 13px;">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('orders.show', $order->id) }}"
                                           class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <form action="{{ route('orders.destroy', $order->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        لا توجد طلبات حاليًا
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>

                </div>
            </div>
        </div>
    </section>

</main>
@endsection

@section('js')
<script>
    // تحديث الحالة مباشرة من القائمة
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function () {
            const orderId = this.dataset.id;
            const status  = this.value;

            fetch(`/orders/${orderId}/status-ajax`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value 
                },
                body: JSON.stringify({ status })
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    // فلاش صغير بالنجاح
                    const flash = document.createElement('div');
                    flash.className = 'alert alert-success alert-dismissible fade show position-fixed';
                    flash.style.cssText = 'top:20px; left:50%; transform:translateX(-50%); z-index:9999; min-width:250px;';
                    flash.innerHTML = '✅ تم تحديث الحالة <button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                    document.body.appendChild(flash);
                    setTimeout(() => flash.remove(), 2500);
                } else {
                    alert('حدث خطأ في تحديث الحالة');
                }
            })
            .catch(() => alert('حدث خطأ في الاتصال'));
        });
    });
</script>
@endsection
