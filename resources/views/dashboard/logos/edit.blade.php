@extends('layouts.the-index')

@section('title')
    تعديل لوجو
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

                        <h5 class="card-title">تعديل اللوجو</h5>

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

                        <form action="{{ route('logos.update', $logo) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- القسم -->
                            <div class="row mb-3">
                                <label class="col-md-2 col-form-label">القسم</label>
                                <div class="col-md-10">
                                    <select name="logo_section_id"
                                            class="form-select @error('logo_section_id') is-invalid @enderror">
                                        <option value="">اختر القسم</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}"
                                                {{ old('logo_section_id', $logo->logo_section_id) == $section->id ? 'selected' : '' }}>
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
                                <label class="col-md-2 col-form-label">الصورة الحالية</label>
                                <div class="col-md-10">
                                    <img id="logoPreview"
                                         src="{{ asset('storage/' . $logo->image) }}"
                                         width="80" height="80"
                                         style="object-fit:contain; background:#f5f5f5; border-radius:8px; padding:5px; display:block; margin-bottom:10px;">
                                    <input type="file"
                                           name="image"
                                           class="form-control @error('image') is-invalid @enderror"
                                           accept="image/*"
                                           onchange="previewLogo(this)">
                                    <small class="text-muted">اتركه فارغًا إذا لا تريد تغيير الصورة</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="row mb-3">
                                <div class="col-md-10 offset-md-2">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-save"></i> تحديث
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
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('logoPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection