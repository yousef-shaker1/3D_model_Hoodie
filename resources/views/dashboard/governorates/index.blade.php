@extends('layouts.the-index')

@section('title', 'إدارة المحافظات والشحن')

@section('css')
<!-- تضمين الخطوط وبعض التنسيقات الإضافية -->
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    /* التنسيقات المشابهة لصفحة الألوان مع تعديلات بسيطة */
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #4cc9f0;
        --danger-color: #f72585;
        --dark-color: #2b2d42;
        --light-color: #f8f9fa;
        --border-radius: 12px;
    }

    body {
        font-family: 'Cairo', sans-serif;
        background-color: #f5f6fa;
    }

    .page-header {
        background: white;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title {
        margin: 0;
        color: var(--dark-color);
        font-weight: 700;
    }

    .btn-add {
        background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
        border: none;
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        transition: transform 0.3s ease;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        color: white;
    }

    .table-container {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    .table th {
        background-color: var(--light-color);
        color: var(--dark-color);
        font-weight: 600;
        border-bottom: 2px solid #edf2f7;
    }

    .table td {
        vertical-align: middle;
    }

    .action-btn {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-edit { background-color: #e0fbfc; color: #0077b6; }
    .btn-edit:hover { background-color: #0077b6; color: white; }

    .btn-delete { background-color: #ffe5d9; color: #d62828; }
    .btn-delete:hover { background-color: #d62828; color: white; }

</style>
@endsection

@section('content')
<main id="main" class="main">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h4 class="page-title">إدارة المحافظات والشحن</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 mt-2">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">المحافظات</li>
                </ol>
            </nav>
        </div>
        <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addGovernorateModal">
            <i class="bi bi-plus-lg me-2"></i> إضافة محافظة جديدة
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-octagon me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Table Container -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>اسم المحافظة</th>
                        <th>سعر الشحن (ج.م)</th>
                        <th>الحالة</th>
                        <th width="150" class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($governorates as $gov)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $gov->name }}</strong></td>
                            <td><span class="badge bg-primary fs-6">{{ $gov->shipping_price }} ج.م</span></td>
                            <td>
                                @if($gov->is_active)
                                    <span class="badge bg-success">متاحة</span>
                                @else
                                    <span class="badge bg-secondary">غير متاحة</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="action-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $gov->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('governorates.destroy', $gov->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه المحافظة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn btn-delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $gov->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold">تعديل بيانات المحافظة</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('governorates.update', $gov->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label class="form-label">اسم المحافظة</label>
                                                <input type="text" class="form-control" name="name" value="{{ $gov->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">سعر الشحن (ج.م)</label>
                                                <input type="number" class="form-control" name="shipping_price" value="{{ $gov->shipping_price }}" min="0" required>
                                            </div>
                                            <div class="mb-4 form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active" id="active_{{ $gov->id }}" {{ $gov->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label" for="active_{{ $gov->id }}">متاحة للشحن</label>
                                            </div>
                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                                                <button type="submit" class="btn btn-primary px-4">حفظ التعديلات</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    لا توجد محافظات مضافة حتى الآن.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</main>

<!-- Add Governorate Modal -->
<div class="modal fade" id="addGovernorateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">إضافة محافظة جديدة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('governorates.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">اسم المحافظة</label>
                        <input type="text" class="form-control" name="name" required placeholder="مثال: القاهرة">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">سعر الشحن (ج.م)</label>
                        <input type="number" class="form-control" name="shipping_price" value="50" min="0" required>
                    </div>
                    <div class="mb-4 form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="new_active" checked>
                        <label class="form-check-label" for="new_active">متاحة للشحن</label>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary px-4">إضافة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
