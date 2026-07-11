@extends('layouts.the-index')

@section('title')
    أكواد الخصم
@endsection

@section('css')
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
        <h1>أكواد الخصم</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active">أكواد الخصم</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="padding: 20px;">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">أكواد الخصم</h5>
                        <a href="{{ route('promo-codes.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> إضافة كود جديد
                        </a>
                    </div>

                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الكود</th>
                                <th>خصم نسبة</th>
                                <th>خصم ثابت</th>
                                <th>الاستخدام</th>
                                <th>تاريخ الانتهاء</th>
                                <th>الحالة</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promoCodes as $promoCode)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $promoCode->code }}</strong></td>
                                    <td>{{ $promoCode->discount_percent > 0 ? $promoCode->discount_percent . '%' : '—' }}</td>
                                    <td>{{ $promoCode->discount_fixed > 0 ? number_format($promoCode->discount_fixed, 2) : '—' }}</td>
                                    <td>
                                        @if($promoCode->max_uses)
                                            {{ $promoCode->used_count }} / {{ $promoCode->max_uses }}
                                        @else
                                            {{ $promoCode->used_count }} (غير محدود)
                                        @endif
                                    </td>
                                    <td>{{ $promoCode->expires_at ? $promoCode->expires_at->format('d/m/Y') : 'غير محدود' }}</td>
                                    <td>
                                        @if($promoCode->is_active)
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-danger">غير نشط</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('promo-codes.edit', $promoCode->id) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('promo-codes.destroy', $promoCode->id) }}"
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
                                    <td colspan="8" class="text-center text-muted">
                                        لا توجد أكواد خصم حاليًا
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $promoCodes->links() }}
                    </div>

                </div>
            </div>
        </div>
    </section>

</main>
@endsection

@section('js')
@endsection
