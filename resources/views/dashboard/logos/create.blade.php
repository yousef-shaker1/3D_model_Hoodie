@extends('layouts.the-index')

@section('title')
    إضافة لوجو
@endsection

@section('css')
@endsection

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Form Elements</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Forms</li>
                <li class="breadcrumb-item active">Elements</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <h5 class="card-title">إضافة لوجو جديد</h5>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <strong>⚠️ Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('logos.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- القسم -->
                            <div class="row mb-3">
                                <label class="col-md-2 col-form-label">القسم</label>
                                <div class="col-md-10">
                                    <select name="logo_section_id"
                                            class="form-select @error('logo_section_id') is-invalid @enderror">
                                        <option value="">اختر القسم</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}"
                                                {{ old('logo_section_id') == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('logo_section_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- صورة اللوجو -->
                            <div class="row mb-3">
                                <label class="col-md-2 col-form-label">صور اللوجوهات</label>
                                <div class="col-md-10">
                                    <input type="file"
                                           name="images[]"
                                           class="form-control @error('images') is-invalid @enderror"
                                           accept="image/*"
                                           multiple
                                           onchange="previewLogos(this)">
                                    @error('images')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-2" id="previewWrap" style="display:none;">
                                        <div id="logosPreview" class="d-flex flex-wrap gap-2"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="row mb-3">
                                <div class="col-md-10 offset-md-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> حفظ
                                    </button>
                                    <a href="{{ route('logos.index') }}" class="btn btn-secondary ms-2">
                                        إلغاء
                                    </a>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

</main>
@endsection

@section('js')
<script>
function previewLogos(input) {
    const previewWrap = document.getElementById('previewWrap');
    const logosPreview = document.getElementById('logosPreview');
    logosPreview.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        previewWrap.style.display = 'block';
        
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.width = 80;
                img.height = 80;
                img.style.objectFit = 'contain';
                img.style.background = '#f5f5f5';
                img.style.borderRadius = '8px';
                img.style.padding = '5px';
                logosPreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    } else {
        previewWrap.style.display = 'none';
    }
}
</script>
@endsection