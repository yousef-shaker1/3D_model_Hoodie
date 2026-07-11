@extends('layouts.the-index')

@section('title')
    إدارة الألوان
@endsection

@section('css')
<style>
    .color-preview {
        width: 45px;
        height: 45px;
        border-radius: 8px;
        border: 2px solid #ddd;
        display: inline-block;
    }
    .status-badge {
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-active {
        background: #d1e7dd;
        color: #0f5132;
    }
    .status-inactive {
        background: #e9ecef;
        color: #6c757d;
    }
    .stat-box {
        background: #fff;
        border-radius: 8px;
        padding: 18px;
        text-align: center;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }
    .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #4154f1;
    }
    .stat-label {
        color: #6c757d;
        font-size: 13px;
    }
    .toggle-switch {
        position: relative;
        width: 50px;
        height: 26px;
        display: inline-block;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .toggle-slider {
        background-color: #4154f1;
    }
    input:checked + .toggle-slider:before {
        transform: translateX(24px);
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
        <h1>إدارة الألوان</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Colors</li>
            </ol>
        </nav>
    </div>

    <section class="section">

        <!-- Stats -->
        <div class="row mb-3">
            <div class="col-lg-4 col-md-4">
                <div class="stat-box">
                    <div class="stat-number">{{ $colors->count() }}</div>
                    <div class="stat-label">إجمالي الألوان</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="stat-box">
                    <div class="stat-number">{{ $colors->where('active', true)->count() }}</div>
                    <div class="stat-label">الألوان النشطة</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="stat-box">
                    <div class="stat-number">{{ $colors->where('active', false)->count() }}</div>
                    <div class="stat-label">الألوان غير النشطة</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="padding: 20px;">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">إدارة الألوان</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addColorModal">
                            <i class="bi bi-plus-circle"></i> إضافة لون جديد
                        </button>
                    </div>

                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-3" id="colorTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                                جميع الألوان ({{ $colors->count() }})
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab">
                                الألوان النشطة ({{ $colors->where('active', true)->count() }})
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="inactive-tab" data-bs-toggle="tab" data-bs-target="#inactive" type="button" role="tab">
                                الألوان غير النشطة ({{ $colors->where('active', false)->count() }})
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="colorTabsContent">

                        <!-- All Colors -->
                        <div class="tab-pane fade show active" id="all" role="tabpanel">
                            <table class="table table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اللون</th>
                                        <th>الاسم</th>
                                        <th>كود اللون</th>
                                        <th>الحالة</th>
                                        <th>الترتيب</th>
                                        <th class="text-center">التحكم</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($colors as $color)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="color-preview" style="background-color: {{ $color->hex_code }};"></div>
                                            </td>
                                            <td>{{ $color->name }}</td>
                                            <td><code>{{ $color->hex_code }}</code></td>
                                            <td>
                                                @if($color->active)
                                                    <span class="status-badge status-active">نشط</span>
                                                @else
                                                    <span class="status-badge status-inactive">غير نشط</span>
                                                @endif
                                            </td>
                                            <td>{{ $color->sort_order }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('colors.toggle', $color->id) }}" method="POST">
                                                    @csrf
                                                    <label class="toggle-switch">
                                                        <input type="checkbox" {{ $color->active ? 'checked' : '' }} onchange="this.form.submit()">
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editColorModal{{ $color->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="{{ route('colors.destroy', $color->id) }}"
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

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editColorModal{{ $color->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">تعديل اللون</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('colors.update', $color->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">اسم اللون</label>
                                                                <input type="text" class="form-control" name="name" value="{{ $color->name }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">كود اللون (HEX)</label>
                                                                <div class="input-group">
                                                                    <input type="color" class="form-control form-control-color" name="color_preview" value="{{ $color->hex_code }}" style="width: 60px;" onchange="document.getElementById('hex_code{{ $color->id }}').value = this.value">
                                                                    <input type="text" class="form-control" name="hex_code" id="hex_code{{ $color->id }}" value="{{ $color->hex_code }}" required pattern="^#[0-9A-Fa-f]{6}$">
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">الحالة</label>
                                                                <select class="form-select" name="active">
                                                                    <option value="1" {{ $color->active ? 'selected' : '' }}>نشط - يظهر في الموقع</option>
                                                                    <option value="0" {{ !$color->active ? 'selected' : '' }}>غير نشط - لا يظهر</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">الترتيب</label>
                                                                <input type="number" class="form-control" name="sort_order" value="{{ $color->sort_order }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">
                                                لا توجد ألوان حاليًا
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Active Colors -->
                        <div class="tab-pane fade" id="active" role="tabpanel">
                            <table class="table table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اللون</th>
                                        <th>الاسم</th>
                                        <th>كود اللون</th>
                                        <th>الترتيب</th>
                                        <th class="text-center">التحكم</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($colors->where('active', true) as $color)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="color-preview" style="background-color: {{ $color->hex_code }};"></div>
                                            </td>
                                            <td>{{ $color->name }}</td>
                                            <td><code>{{ $color->hex_code }}</code></td>
                                            <td>{{ $color->sort_order }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('colors.toggle', $color->id) }}" method="POST">
                                                    @csrf
                                                    <label class="toggle-switch">
                                                        <input type="checkbox" checked onchange="this.form.submit()">
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editColorModal{{ $color->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="{{ route('colors.destroy', $color->id) }}"
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
                                            <td colspan="7" class="text-center text-muted">
                                                لا توجد ألوان نشطة حالياً
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Inactive Colors -->
                        <div class="tab-pane fade" id="inactive" role="tabpanel">
                            <table class="table table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اللون</th>
                                        <th>الاسم</th>
                                        <th>كود اللون</th>
                                        <th>الترتيب</th>
                                        <th class="text-center">التحكم</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($colors->where('active', false) as $color)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="color-preview" style="background-color: {{ $color->hex_code }}; opacity:0.5;"></div>
                                            </td>
                                            <td>{{ $color->name }}</td>
                                            <td><code>{{ $color->hex_code }}</code></td>
                                            <td>{{ $color->sort_order }}</td>
                                            <td class="text-center">
                                                <form action="{{ route('colors.toggle', $color->id) }}" method="POST">
                                                    @csrf
                                                    <label class="toggle-switch">
                                                        <input type="checkbox" onchange="this.form.submit()">
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editColorModal{{ $color->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="{{ route('colors.destroy', $color->id) }}"
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
                                            <td colspan="7" class="text-center text-muted">
                                                جميع الألوان نشطة حالياً
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </section>

    <!-- Add Color Modal -->
    <div class="modal fade" id="addColorModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة لون جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('colors.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم اللون</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">كود اللون (HEX)</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" name="color_preview" value="#1a1a1a" style="width: 60px;" onchange="document.getElementById('hex_code_new').value = this.value">
                                <input type="text" class="form-control" name="hex_code" id="hex_code_new" value="#1a1a1a" required pattern="^#[0-9A-Fa-f]{6}$">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الحالة</label>
                            <select class="form-select" name="active">
                                <option value="1" selected>نشط - يظهر في الموقع</option>
                                <option value="0">غير نشط - لا يظهر</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الترتيب</label>
                            <input type="number" class="form-control" name="sort_order" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">إضافة اللون</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>
@endsection

@section('js')
@endsection