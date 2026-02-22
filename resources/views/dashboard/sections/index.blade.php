@extends('layouts.the-index')

@section('title')
    أقسام اللوجو
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
        <h1>Logo Sections</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">General</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card" style="padding: 20px;">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">أقسام اللوجو</h5>
                        <a href="{{ route('sections.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> إضافة قسم جديد
                        </a>
                    </div>

                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اللوجو</th>
                                <th>الاسم</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sections as $section)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $section->logo) }}"
                                             alt="{{ $section->name }}"
                                             width="55" height="55"
                                             style="object-fit:contain; background:#f5f5f5; border-radius:8px; padding:4px;">
                                    </td>
                                    <td>{{ $section->name }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('sections.edit', $section->id) }}"
                                           class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('sections.destroy', $section->id) }}"
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
                                    <td colspan="4" class="text-center text-muted">
                                        لا توجد أقسام حاليًا
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $sections->links() }}
                    </div>

                </div>
            </div>
        </div>
    </section>

</main>
@endsection

@section('js')
@endsection