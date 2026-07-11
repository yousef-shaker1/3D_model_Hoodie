@extends('layouts.the-index')

@section('title')
    تعديل كود الخصم
@endsection

@section('css')
@endsection

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>تعديل كود الخصم</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('promo-codes.index') }}">أكواد الخصم</a></li>
                <li class="breadcrumb-item active">تعديل</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card" style="padding: 20px;">
                    <h5 class="card-title">تعديل كود الخصم: {{ $promoCode->code }}</h5>

                    <form action="{{ route('promo-codes.update', $promoCode->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="code" class="form-label">الكود</label>
                            <input type="text" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   id="code" 
                                   name="code" 
                                   value="{{ old('code', $promoCode->code) }}"
                                   placeholder="مثال: SALE2024"
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_percent" class="form-label">خصم نسبة (%)</label>
                                    <input type="number" 
                                           class="form-control @error('discount_percent') is-invalid @enderror" 
                                           id="discount_percent" 
                                           name="discount_percent" 
                                           value="{{ old('discount_percent', $promoCode->discount_percent) }}"
                                           min="0" 
                                           max="100" 
                                           step="0.01"
                                           placeholder="مثال: 20">
                                    @error('discount_percent')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_fixed" class="form-label">خصم ثابت</label>
                                    <input type="number" 
                                           class="form-control @error('discount_fixed') is-invalid @enderror" 
                                           id="discount_fixed" 
                                           name="discount_fixed" 
                                           value="{{ old('discount_fixed', $promoCode->discount_fixed) }}"
                                           min="0" 
                                           step="0.01"
                                           placeholder="مثال: 50">
                                    @error('discount_fixed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="max_uses" class="form-label">الحد الأقصى للاستخدام (اختياري)</label>
                            <input type="number" 
                                   class="form-control @error('max_uses') is-invalid @enderror" 
                                   id="max_uses" 
                                   name="max_uses" 
                                   value="{{ old('max_uses', $promoCode->max_uses) }}"
                                   min="1"
                                   placeholder="اتركه فارغاً لعدم التحديد">
                            @error('max_uses')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="expires_at" class="form-label">تاريخ الانتهاء (اختياري)</label>
                            <input type="date" 
                                   class="form-control @error('expires_at') is-invalid @enderror" 
                                   id="expires_at" 
                                   name="expires_at" 
                                   value="{{ old('expires_at', $promoCode->expires_at ? $promoCode->expires_at->format('Y-m-d') : '') }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                       <div class="mb-3">
    <div class="form-check">
        <input class="form-check-input" 
               type="checkbox" 
               id="is_active" 
               name="is_active" 
               value="1"
               {{ old('is_active', $promoCode->is_active) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">
            نشط
        </label>
    </div>
</div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> حفظ التعديلات
                            </button>
                            <a href="{{ route('promo-codes.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-right"></i> رجوع
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

</main>
@endsection

@section('js')
@endsection
