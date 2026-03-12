@extends('layouts.the-index')

@section('title')
    تفاصيل الطلب #{{ $order->id }}
@endsection

@section('css')
<style>
    .info-label {
        font-size: 11px;
        font-weight: 600;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .info-value {
        font-size: 15px;
        font-weight: 600;
        color: #333;
    }
    .view-card {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background: #f5f5f5;
        aspect-ratio: 1;
        border: 1px solid #e0e0e0;
    }
    .view-label {
        position: absolute;
        top: 10px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        z-index: 100;
        white-space: nowrap;
    }
    .logos-layer {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 10;
    }
    model-viewer {
        width: 100%;
        height: 100%;
        --poster-color: transparent;
    }
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
        <h1>تفاصيل الطلب #{{ $order->id }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">الطلبات</a></li>
                <li class="breadcrumb-item active">#{{ $order->id }}</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">

            {{-- بيانات العميل --}}
            <div class="col-lg-4">
                <div class="card" style="padding: 20px;">
                    <h5 class="card-title">👤 بيانات العميل</h5>

                    <div class="mb-3">
                        <div class="info-label">الاسم</div>
                        <div class="info-value">{{ $order->name }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="info-label">الهاتف</div>
                        <div class="info-value">
                            <a href="tel:{{ $order->phone }}">{{ $order->phone }}</a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="info-label">العنوان</div>
                        <div class="info-value">{{ $order->address }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="info-label">المقاس</div>
                        <div class="info-value">
                            <span class="badge bg-primary fs-6">{{ $order->size }}</span>
                        </div>
                    </div>
                    @if($order->notes)
                    <div class="mb-3">
                        <div class="info-label">ملاحظات</div>
                        <div class="info-value" style="font-size:14px; font-weight:400; color:#555;">
                            {{ $order->notes }}
                        </div>
                    </div>
                    @endif
                    <div class="mb-3">
                        <div class="info-label">تاريخ الطلب</div>
                        <div class="info-value" style="font-size:13px;">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <hr>

                    {{-- تغيير الحالة --}}
                    <div>
                        <div class="info-label mb-2">الحالة</div>
                        <form action="{{ route('orders.status', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select mb-2">
                                @foreach(['pending' => 'قيد الانتظار', 'processing' => 'جاري التنفيذ', 'done' => 'تم التسليم', 'cancelled' => 'ملغي'] as $val => $label)
                                    <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-circle"></i> حفظ الحالة
                            </button>
                        </form>
                    </div>


                    <hr>

                    <a href="{{ route('orders.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-right"></i> رجوع للطلبات
                    </a>
                </div>
            </div>

            {{-- الهودي بالأربع وجوه --}}
            <div class="col-lg-8">
                <div class="card" style="padding: 20px;">
                    <h5 class="card-title">👕 التصميم على الهودي</h5>

                    <div class="row g-3">
                        @php
                            $views = [
                                'front' => ['label' => 'الوش',  'orbit' => '0deg 75deg 105%'],
                                'back'  => ['label' => 'الظهر', 'orbit' => '180deg 75deg 105%'],
                                'left'  => ['label' => 'يسار',  'orbit' => '90deg 75deg 105%'],
                                'right' => ['label' => 'يمين',  'orbit' => '-90deg 75deg 105%'],
                            ];
                        @endphp

                        @foreach($views as $viewKey => $viewData)
                        <div class="col-6">
                            <div class="view-card">
                                <div class="view-label">{{ $viewData['label'] }}</div>

                                <model-viewer
                                    src="{{ asset('assets/img/3ds/t_shirt_hoodie_3d_model.glb') }}"
                                    camera-orbit="{{ $viewData['orbit'] }}"
                                    min-camera-orbit="{{ $viewData['orbit'] }}"
                                    max-camera-orbit="{{ $viewData['orbit'] }}"
                                    field-of-view="30deg"
                                    disable-zoom disable-pan disable-tap
                                    interaction-prompt="none"
                                    ar-modes="">
                                </model-viewer>

                                {{-- لوجوهات هذا الوجه --}}
                                <div class="logos-layer">
                                    @foreach($order->logos as $logo)
                                        @if($logo['view'] === $viewKey)
                                        <div style="
                                            position: absolute;
                                            left: {{ $logo['x_percent'] }}%;
                                            top: {{ $logo['y_percent'] }}%;
                                            width: {{ $logo['width_percent'] }}%;
                                            height: {{ $logo['height_percent'] }}%;
                                            transform: rotate({{ $logo['rotation'] }}deg);
                                        ">
                                            <img src="{{ $logo['src'] }}"
                                                 style="width:100%; height:100%; object-fit:contain;
                                                        filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));">
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
<h3>اللوجوهات المستخدمة</h3>

@if($order->logos && count($order->logos) > 0)
    <div class="logos-grid">
        @foreach($order->logos as $logo)
            <div class="logo-card">
                <img
                    src="{{ $logo['src'] }}"
                    alt="لوجو - {{ $logo['view'] }}"
                    style="width:120px; height:120px; object-fit:contain; border:1px solid #ddd; border-radius:8px;"
                >
                <div class="logo-meta">
                    <span>الوجه: {{ $logo['view'] }}</span>
                    <span>الحجم: {{ $logo['width_percent'] }}%</span>
                    <span>الموضع X: {{ $logo['x_percent'] }}%</span>
                    <span>الموضع Y: {{ $logo['y_percent'] }}%</span>
                    <span>الدوران: {{ $logo['rotation'] }}°</span>
                </div>
                <a href="{{ $logo['src'] }}" download target="_blank">
                    تحميل الصورة
                </a>
            </div>
        @endforeach
    </div>
@else
    <p>لا توجد لوجوهات</p>
@endif
                </div>
            </div>

        </div>
    </section>

</main>
@endsection

@section('js')
<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
<script>
    function updateStatus() {
        const status = document.getElementById('statusSelect').value;

        fetch(`/admin/orders/{{ $order->id }}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status })
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                const flash = document.createElement('div');
                flash.className = 'alert alert-success alert-dismissible fade show position-fixed';
                flash.style.cssText = 'top:20px; left:50%; transform:translateX(-50%); z-index:9999; min-width:250px;';
                flash.innerHTML = '✅ تم تحديث الحالة بنجاح <button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                document.body.appendChild(flash);
                setTimeout(() => flash.remove(), 2500);
            } else {
                alert('حدث خطأ في تحديث الحالة');
            }
        })
        .catch(() => alert('حدث خطأ في الاتصال'));
    }
</script>
@endsection
