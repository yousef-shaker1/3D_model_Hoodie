@extends('layouts.the-index')

@section('title')
    تفاصيل الطلب #{{ $order->id }}
@endsection

@section('css')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&family=Aref+Ruqaa:wght@400;700&family=Reem+Kufi:wght@400;700&family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Tajawal:wght@300;400;500;700&family=Changa:wght@300;400;600;700&family=Lalezar&family=Katibeh&family=Rakkas&family=Scheherazade+New:wght@400;700&family=Lateef:wght@400;700&family=El+Messiri:wght@400;700&family=Marhey:wght@300;400;600&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
<style>
    @font-face {
        font-family: 'Diwani';
        src: url('{{ asset('fonts/diwani.ttf') }}') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
    
    @font-face {
        font-family: 'ArefRuqaa';
        src: url('{{ asset('fonts/ArefRuqaa.ttf') }}') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
    
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
                            <div class="view-card" style="container-type: inline-size;">
                                <div class="view-label">{{ $viewData['label'] }}</div>

                                <model-viewer
                                    src="{{ asset('assets/3d_models/t-shirt-basic.glb') }}"
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
                                            left: {{ $logo['x_percent'] ?? 0 }}%;
                                            top: {{ $logo['y_percent'] ?? 0 }}%;
                                            @if(isset($logo['width_percent']) && $logo['width_percent'] !== 'auto')
                                                width: {{ $logo['width_percent'] }}%;
                                            @else
                                                width: auto;
                                            @endif
                                            @if(isset($logo['height_percent']) && $logo['height_percent'] !== 'auto')
                                                height: {{ $logo['height_percent'] }}%;
                                            @else
                                                height: auto;
                                            @endif
                                            transform: rotate({{ $logo['rotation'] ?? 0 }}deg);
                                        ">
                                            @if(isset($logo['type']) && $logo['type'] === 'text')
                                                <span style="
                                                    width:100%; height:100%;
                                                    display: flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    font-family: {{ $logo['font'] ?? 'Cairo, sans-serif' }};
                                                    color: {{ $logo['color'] ?? '#ffffff' }};
                                                    font-size: {{ $logo['fontSizeCqw'] ?? 5 }}cqw;
                                                    font-weight: normal;
                                                    text-shadow: 0 2px 6px rgba(0,0,0,0.3);
                                                    line-height: 1.2;
                                                    text-align: center;
                                                    @if(isset($logo['isFixedWidth']) && $logo['isFixedWidth'])
                                                        white-space: normal;
                                                    @else
                                                        white-space: nowrap;
                                                    @endif
                                                ">{{ $logo['text'] ?? '' }}</span>
                                            @else
                                                <img src="{{ $logo['src'] }}"
                                                     style="width:100%; height:100%; object-fit:contain;
                                                            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));">
                                            @endif
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
<h3>اللوجوهات والنصوص المستخدمة</h3>

@if($order->logos && count($order->logos) > 0)
    <div class="logos-grid">
        @foreach($order->logos as $logo)
            <div class="logo-card">
                @if(isset($logo['type']) && $logo['type'] === 'text')
                    @php
                        $fsCqw = (isset($logo['fontSizeCqw']) && $logo['fontSizeCqw'] > 0) ? $logo['fontSizeCqw'] : 5;
                        $wPct = (isset($logo['width_percent']) && $logo['width_percent'] !== 'auto') ? $logo['width_percent'] : null;
                        $isFixed = isset($logo['isFixedWidth']) && $logo['isFixedWidth'];
                        
                        $baseFontSize = 24; 
                        $computedWidth = 'auto';
                        
                        if ($isFixed && $wPct) {
                            $ratio = $wPct / $fsCqw;
                            $computedWidth = ($baseFontSize * $ratio) . 'px';
                        }
                    @endphp
                    <div style="
                        min-width: 120px; min-height: 120px;
                        display: flex; align-items: center; justify-content: center;
                        border: 1px solid #ddd; border-radius: 8px;
                        background: #000000;
                        padding: 20px;
                        overflow: hidden;
                    ">
                        <span style="
                            width: {{ $computedWidth }};
                            font-family: {{ $logo['font'] ?? 'Cairo, sans-serif' }};
                            color: {{ $logo['color'] ?? '#ffffff' }};
                            font-size: {{ $baseFontSize }}px;
                            font-weight: normal;
                            text-align: center;
                            line-height: 1.2;
                            @if($isFixed)
                                white-space: normal;
                                word-wrap: break-word;
                            @else
                                white-space: nowrap;
                            @endif
                        ">{{ $logo['text'] ?? '' }}</span>
                    </div>
                @else
                    <img
                        src="{{ $logo['src'] }}"
                        alt="لوجو - {{ $logo['view'] }}"
                        style="width:120px; height:120px; object-fit:contain; border:1px solid #ddd; border-radius:8px;"
                    >
                @endif
                <div class="logo-meta">
                    @if(isset($logo['type']) && $logo['type'] === 'text')
                        <span>النوع: نص مخصص</span>
                        <span>النص: {{ Str::limit($logo['text'] ?? '', 20) }}</span>
                        <span>الخط: {{ $logo['font'] ?? 'Cairo' }}</span>
                    @else
                        <span>النوع: لوجو</span>
                    @endif
                    <span>الوجه: {{ $logo['view'] }}</span>
                    <span>الحجم: {{ $logo['width_percent'] }}%</span>
                    <span>الموضع X: {{ $logo['x_percent'] }}%</span>
                    <span>الموضع Y: {{ $logo['y_percent'] }}%</span>
                    <span>الدوران: {{ $logo['rotation'] }}°</span>
                </div>
                <div class="mt-2 text-center w-100">
                @if(!isset($logo['type']) || $logo['type'] !== 'text')
                    <button type="button" onclick="downloadImage('{{ $logo['src'] }}', '{{ basename(parse_url($logo['src'], PHP_URL_PATH)) }}')" class="btn btn-sm btn-primary">
                        <i class="bi bi-download"></i> تحميل الصورة
                    </button>
                @else
                    <button type="button" data-logo="{{ json_encode($logo) }}" onclick="downloadTextLogoDTF(event)" class="btn btn-sm btn-success">
                        <i class="bi bi-printer"></i> تحميل ملف الطباعة DTF
                    </button>
                @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <p>لا توجد لوجوهات أو نصوص</p>
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
    // Apply color to 3D model
    function applyColorToModel(modelViewer, color) {
        if (!modelViewer) return;
        
        modelViewer.addEventListener('load', () => {
            try {
                const model = modelViewer.model;
                if (model && model.materials) {
                    model.materials.forEach(material => {
                        if (material && material.pbrMetallicRoughness) {
                            material.pbrMetallicRoughness.setBaseColorFactor(color);
                        }
                    });
                }
            } catch (e) {
                console.warn('Could not apply color to model:', e);
            }
        });
    }

    // Apply color to all model-viewers when page loads
    document.addEventListener('DOMContentLoaded', () => {
        const orderColor = '{{ $order->color }}';
        const modelViewers = document.querySelectorAll('model-viewer');
        modelViewers.forEach(viewer => {
            applyColorToModel(viewer, orderColor);
        });
    });

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

    async function downloadImage(url, filename) {
        try {
            // Create a temporary link element
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            link.target = '_blank';
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } catch(e) {
            // Fallback: open in new tab
            window.open(url, '_blank');
        }
    }

    async function downloadTextLogoDTF(event) {
        const btn = event.currentTarget;
        const logoDataStr = btn.getAttribute('data-logo');
        const d = JSON.parse(logoDataStr);
        
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> جاري التحضير...';
        btn.disabled = true;

        try {
            // خط كبير جداً لاستخراج جودة عالية جدا للطباعة
            const fontPx = 2000; 
            const fontFamily = d.font || "'Cairo', sans-serif";
            
            try {
                await document.fonts.load(`${fontPx}px ${fontFamily}`);
            } catch (e) {}
            
            // كانفاس وهمي لحساب المقاسات
            const tCanvas = document.createElement('canvas');
            const tCtx = tCanvas.getContext('2d');
            tCtx.font = `${fontPx}px ${fontFamily}`;
            
            const fsCqw = (d.fontSizeCqw || 5);
            const wPct = (d.width_percent && d.width_percent !== 'auto') ? d.width_percent : null;
            const isFixed = d.isFixedWidth || false;
            
            let lw = 9999999;
            if (isFixed && wPct) {
                const ratio = wPct / fsCqw;
                lw = fontPx * ratio;
            }
            
            const words = (d.text || '').split(' ');
            const lines = [];
            let currentLine = words[0] || '';

            for (let i = 1; i < words.length; i++) {
                let word = words[i];
                let width = tCtx.measureText(currentLine + " " + word).width;
                if (isFixed && width > lw) {
                    lines.push(currentLine);
                    currentLine = word;
                } else {
                    currentLine += " " + word;
                }
            }
            if (currentLine) lines.push(currentLine);

            const lineHeight = fontPx * 1.2;
            const totalHeight = lines.length * lineHeight;
            
            let maxLineWidth = 0;
            for(let l of lines) {
                let w = tCtx.measureText(l).width;
                if(w > maxLineWidth) maxLineWidth = w;
            }

            // زيادة الـ padding بشكل كبير ليتناسب مع الخطوط الكبيرة ويمنع قص الحواف
            const padding = fontPx * 0.5; 
            const canvasWidth = maxLineWidth + padding * 2;
            const canvasHeight = totalHeight + padding * 2;
            
            // الكانفاس الحقيقي
            const canvas = document.createElement('canvas');
            canvas.width = canvasWidth;
            canvas.height = canvasHeight;
            const ctx = canvas.getContext('2d');
            
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.font = `${fontPx}px ${fontFamily}`;
            ctx.fillStyle = d.color || '#ffffff';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.direction = 'rtl';
            
            const cx = canvas.width / 2;
            const cy = canvas.height / 2;
            
            ctx.save();
            ctx.translate(cx, cy);
            
            // إيقاف الدوران هنا أفضل حتى تكون الصورة مقصوصة على قد الكلام بالظبط 
            // يمكن للعميل تدويرها براحته على برنامج الطباعة
            
            const startY = - (totalHeight / 2) + (lineHeight / 2);
            
            for (let i = 0; i < lines.length; i++) {
                ctx.fillText(lines[i], 0, startY + (i * lineHeight));
            }
            ctx.restore();
            
            const dataUrl = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.href = dataUrl;
            link.download = `DTF_Text_${d.view || 'front'}_${Date.now()}.png`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } catch(e) {
            console.error(e);
            alert('حدث خطأ أثناء استخراج الصورة للطباعة.');
        }
        
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
</script>
@endsection
