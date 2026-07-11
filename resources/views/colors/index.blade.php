<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الألوان</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #f5f5f5;
        }
        .color-preview {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            border: 3px solid #ddd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .status-badge {
            padding: 6px 16px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
        }
        .status-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .status-inactive {
            background: #e9ecef;
            color: #6c757d;
        }
        .card {
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-radius: 16px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 10px 24px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px 10px 0 0;
        }
        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .nav-tabs {
            border-bottom: none;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
        }
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .stat-label {
            color: #6c757d;
            font-size: 14px;
        }
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        .toggle-switch {
            position: relative;
            width: 60px;
            height: 30px;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .toggle-slider {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        input:checked + .toggle-slider:before {
            transform: translateX(30px);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-number">{{ $colors->count() }}</div>
                            <div class="stat-label">إجمالي الألوان</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-number">{{ $colors->where('active', true)->count() }}</div>
                            <div class="stat-label">الألوان النشطة</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-number">{{ $colors->where('active', false)->count() }}</div>
                            <div class="stat-label">الألوان غير النشطة</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-white py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">إدارة الألوان</h4>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addColorModal">
                                <i class="fas fa-plus"></i> إضافة لون جديد
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Tabs -->
                        <ul class="nav nav-tabs mb-4" id="colorTabs" role="tablist">
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

                        <!-- Tab Content -->
                        <div class="tab-content" id="colorTabsContent">
                            <!-- All Colors -->
                            <div class="tab-pane fade show active" id="all" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>اللون</th>
                                                <th>الاسم</th>
                                                <th>كود اللون</th>
                                                <th>المقاسات المتاحة</th>
                                                <th>الحالة</th>
                                                <th>الترتيب</th>
                                                <th>التحكم</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($colors as $color)
                                            <tr>
                                                <td>
                                                    <div class="color-preview" style="background-color: {{ $color->hex_code }};"></div>
                                                </td>
                                                <td><strong>{{ $color->name }}</strong></td>
                                                <td><code>{{ $color->hex_code }}</code></td>
                                                <td>
                                                    @if(is_array($color->sizes) && count($color->sizes) > 0)
                                                        @foreach($color->sizes as $size)
                                                            <span class="badge bg-secondary">{{ $size }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted small">الكل</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($color->active)
                                                        <span class="status-badge status-active">نشط - يظهر في الموقع</span>
                                                    @else
                                                        <span class="status-badge status-inactive">غير نشط - لا يظهر</span>
                                                    @endif
                                                </td>
                                                <td>{{ $color->sort_order }}</td>
                                                <td>
                                                    <form action="{{ route('colors.toggle', $color->id) }}" method="POST">
                                                        @csrf
                                                        <label class="toggle-switch">
                                                            <input type="checkbox" {{ $color->active ? 'checked' : '' }} onchange="this.form.submit()">
                                                            <span class="toggle-slider"></span>
                                                        </label>
                                                    </form>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#editColorModal{{ $color->id }}"
                                                                title="تعديل">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('colors.destroy', $color->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا اللون؟');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
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
                                                                    <label class="form-label">المقاسات المتاحة لهذا اللون</label>
                                                                    <div class="d-flex gap-3 flex-wrap">
                                                                        @php $availableSizes = is_array($color->sizes) ? $color->sizes : []; @endphp
                                                                        @foreach(['S', 'M', 'L', 'XL', 'XXL'] as $size)
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $size }}" id="size_{{ $size }}_{{ $color->id }}" {{ in_array($size, $availableSizes) ? 'checked' : '' }}>
                                                                            <label class="form-check-label" for="size_{{ $size }}_{{ $color->id }}">
                                                                                {{ $size }}
                                                                            </label>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <small class="text-muted">إذا لم تختر أي مقاس، فلن يظهر أي مقاس متاح.</small>
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
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Active Colors -->
                            <div class="tab-pane fade" id="active" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>اللون</th>
                                                <th>الاسم</th>
                                                <th>كود اللون</th>
                                                <th>الترتيب</th>
                                                <th>التحكم</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($colors->where('active', true) as $color)
                                            <tr>
                                                <td>
                                                    <div class="color-preview" style="background-color: {{ $color->hex_code }};"></div>
                                                </td>
                                                <td><strong>{{ $color->name }}</strong></td>
                                                <td><code>{{ $color->hex_code }}</code></td>
                                                <td>
                                                    @if(is_array($color->sizes) && count($color->sizes) > 0)
                                                        @foreach($color->sizes as $size)
                                                            <span class="badge bg-secondary">{{ $size }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted small">الكل</span>
                                                    @endif
                                                </td>
                                                <td>{{ $color->sort_order }}</td>
                                                <td>
                                                    <form action="{{ route('colors.toggle', $color->id) }}" method="POST">
                                                        @csrf
                                                        <label class="toggle-switch">
                                                            <input type="checkbox" checked onchange="this.form.submit()">
                                                            <span class="toggle-slider"></span>
                                                        </label>
                                                    </form>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#editColorModal{{ $color->id }}"
                                                                title="تعديل">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('colors.destroy', $color->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا اللون؟');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @if($colors->where('active', true)->count() === 0)
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <p class="text-muted">لا توجد ألوان نشطة حالياً</p>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Inactive Colors -->
                            <div class="tab-pane fade" id="inactive" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>اللون</th>
                                                <th>الاسم</th>
                                                <th>كود اللون</th>
                                                <th>الترتيب</th>
                                                <th>التحكم</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($colors->where('active', false) as $color)
                                            <tr>
                                                <td>
                                                    <div class="color-preview" style="background-color: {{ $color->hex_code }}; opacity: 0.5;"></div>
                                                </td>
                                                <td><strong>{{ $color->name }}</strong></td>
                                                <td><code>{{ $color->hex_code }}</code></td>
                                                <td>
                                                    @if(is_array($color->sizes) && count($color->sizes) > 0)
                                                        @foreach($color->sizes as $size)
                                                            <span class="badge bg-secondary">{{ $size }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted small">الكل</span>
                                                    @endif
                                                </td>
                                                <td>{{ $color->sort_order }}</td>
                                                <td>
                                                    <form action="{{ route('colors.toggle', $color->id) }}" method="POST">
                                                        @csrf
                                                        <label class="toggle-switch">
                                                            <input type="checkbox" onchange="this.form.submit()">
                                                            <span class="toggle-slider"></span>
                                                        </label>
                                                    </form>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#editColorModal{{ $color->id }}"
                                                                title="تعديل">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('colors.destroy', $color->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا اللون؟');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @if($colors->where('active', false)->count() === 0)
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <p class="text-muted">جميع الألوان نشطة حالياً</p>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                            <label class="form-label">المقاسات المتاحة لهذا اللون</label>
                            <div class="d-flex gap-3 flex-wrap">
                                @foreach(['S', 'M', 'L', 'XL', 'XXL'] as $size)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $size }}" id="new_size_{{ $size }}" checked>
                                    <label class="form-check-label" for="new_size_{{ $size }}">
                                        {{ $size }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <small class="text-muted">إذا لم تختر أي مقاس، فلن يظهر أي مقاس متاح.</small>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</body>
</html>
