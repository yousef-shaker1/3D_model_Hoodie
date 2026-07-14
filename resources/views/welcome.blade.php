<!DOCTYPE html>
<html lang="ar" dir="rtl" style="background-color:#0f172a;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WearCraft — مصمم 3D</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Cairo:wght@300;400;600;700;900&family=Playfair+Display:ital,wght@0,700;1,400&family=Aref+Ruqaa:wght@400;700&family=Reem+Kufi:wght@400;700&family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Tajawal:wght@300;400;500;700&family=Changa:wght@300;400;600;700&family=Lalezar&family=Katibeh&family=Rakkas&family=Scheherazade+New:wght@400;700&family=Lateef:wght@400;700&family=El+Messiri:wght@400;700&family=Marhey:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/wearcraft.css') }}">
</head>
<body>
<script>
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
</script>

<!-- LOADING -->
<div id="loadingScreen">
    <div class="ls-bg-pattern"></div>
    <div class="ls-inner">
        <div class="ls-ornament"></div> 
                        <div class="ls-brand">Wear<em>C</em>raft</div>

        <div class="ls-ornament"></div>
        <div class="ls-tagline">3D Product Designer</div>
        <div class="ls-bar-wrap"><div class="ls-bar" id="lsBar"></div></div>
        <div class="ls-pct" id="lsPct">0%</div>
    </div>
</div>

<div class="app">
    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="sb-header">
            <button class="sidebar-close" onclick="document.getElementById('sidebar').classList.remove('open')">✕</button>
            <div class="sb-logo-wrap">
                <div class="sb-brand">Wear<em>C</em>raft</div>
            </div>
            <div class="sb-subtitle">3D Product Designer</div>
            <div class="sb-product-badge">هودي كلاسيك</div>
        </div>

        <div class="sb-body">
            <div class="sb-section-label">أقسام اللوجوهات</div>
            <div class="sections-grid" id="sectionsGrid">
                @foreach($sections as $section)
                <div class="section-item" data-section-id="{{ $section->id }}" onclick="selectSection(this, {{ $section->id }})">
                    @if($section->logo)
                        <img src="{{ asset('storage/' . $section->logo) }}" alt="{{ $section->name }}" title="{{ $section->name }}">
                    @else
                        <div style="text-align:center;position:relative;z-index:1">
                            <div class="section-item-icon">🏷️</div>
                            <div class="section-item-label">{{ $section->name }}</div>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="logos-panel" id="logosPanel">
                <div class="logos-panel-title" id="selectedSectionName"></div>
                <div class="logo-grid" id="logoGrid"></div>
            </div>

            <label class="upload-logo-btn" for="uploadLogoInput">
                <span>📎</span> ارفع لوجو من جهازك
            </label>
            <input type="file" id="uploadLogoInput" accept="image/*" style="display:none;" onchange="handleLogoUpload(this)">

            <div class="sb-section-label" style="margin-top:16px;">إضافة نص</div>
            <div class="add-text-container">
                <input type="text" id="addTextInput" class="add-text-input" placeholder="اكتب النص هنا..." oninput="handleTextLiveUpdate('text')">
                <div class="add-text-controls">
                    <!-- Hidden native select for form compatibility -->
                    <input type="hidden" id="addTextFont" value="'Cairo', sans-serif">

                    <!-- Custom Font Picker -->
                    <div class="font-picker" id="fontPickerWrap">
                        <button type="button" class="font-picker-btn" id="fontPickerBtn" onclick="toggleFontPicker()">
                            <span id="fontPickerLabel" style="font-family: 'Cairo', sans-serif;">القاهرة — Cairo</span>
                            <span class="fp-arrow">▼</span>
                        </button>
                        <div class="font-picker-dropdown" id="fontPickerDropdown">
                            <!-- ─── خطوط خطاطية احترافية ─── -->
                            <div class="fp-group-label">✦ خطوط خطاطية فنية</div>
                            <div class="font-picker-option" data-font="'Diwani', cursive" style="font-family: 'Diwani', cursive;" onclick="selectFont(this)">خط ديواني <span class="fp-check">✓</span></div>
                            <div class="font-picker-option" data-font="'ArefRuqaa', cursive" style="font-family: 'ArefRuqaa', cursive;" onclick="selectFont(this)">خط رقعة <span class="fp-check">✓</span></div>

                            <div class="font-picker-option" data-font="'Rakkas', cursive" style="font-family: 'Rakkas', cursive;" onclick="selectFont(this)">صُنع بإتقان <span class="fp-check">✓</span></div>
                            <div class="font-picker-option" data-font="'Katibeh', serif" style="font-family: 'Katibeh', serif;" onclick="selectFont(this)">فـنّ وإبـداع <span class="fp-check">✓</span></div>
                            <div class="font-picker-option" data-font="'Lateef', serif" style="font-family: 'Lateef', serif;" onclick="selectFont(this)">خط لطيف الناعم <span class="fp-check">✓</span></div>
                            <div class="font-picker-option" data-font="'El Messiri', sans-serif" style="font-family: 'El Messiri', sans-serif;" onclick="selectFont(this)">خط المسيري العصري <span class="fp-check">✓</span></div>
                            <div class="font-picker-option" data-font="'Marhey', sans-serif" style="font-family: 'Marhey', sans-serif;" onclick="selectFont(this)">خط مرحى الأنيق <span class="fp-check">✓</span></div>
                            <div class="font-picker-option" data-font="'Lalezar', display" style="font-family: 'Lalezar', display;" onclick="selectFont(this)">خط لاليزار <span class="fp-check">✓</span></div>
                            <!-- ─── خطوط عصرية ─── -->
                            <div class="fp-group-label">✦ خطوط عصرية</div>
                            <div class="font-picker-option selected" data-font="'Cairo', sans-serif" style="font-family: 'Cairo', sans-serif;" onclick="selectFont(this)">القاهرة Cairo <span class="fp-check">✓</span></div>
                            <div class="font-picker-option" data-font="'Reem Kufi', sans-serif" style="font-family: 'Reem Kufi', sans-serif;" onclick="selectFont(this)">خط ريم الكوفي <span class="fp-check">✓</span></div>
                            <div class="font-picker-option" data-font="'Tajawal', sans-serif" style="font-family: 'Tajawal', sans-serif;" onclick="selectFont(this)">خط تجوال <span class="fp-check">✓</span></div>
                            <div class="font-picker-option" data-font="'Changa', sans-serif" style="font-family: 'Changa', sans-serif;" onclick="selectFont(this)">خط تشانجا <span class="fp-check">✓</span></div>
                            <div class="font-picker-option" data-font="'Playfair Display', serif" style="font-family: 'Playfair Display', serif;" onclick="selectFont(this)">Playfair Display <span class="fp-check">✓</span></div>
                        </div>
                    </div>
                    <input type="color" id="addTextColor" class="text-color-picker" value="#ffffff" oninput="handleTextLiveUpdate('color')">
                </div>
                <div class="tashkeel-toggle-container">
                    <span class="tashkeel-label">تشكيل تزييني</span>
                    <label class="switch">
                        <input type="checkbox" id="tashkeelToggle" onchange="handleTextLiveUpdate('tashkeel')">
                        <span class="slider round"></span>
                    </label>
                </div>
                <button class="add-text-btn" onclick="handleAddText()">إضافة نص جديد</button>
            </div>

            <div class="sb-section-label">اختر لون الهودي</div>
            <div class="sections-grid" id="colorsGrid">
                @foreach($colors as $color)
                <div class="section-item {{ $color->hex_code === '#1a1a1a' ? 'active' : '' }}" 
                     data-color="{{ $color->hex_code }}" 
                     data-sizes="{{ json_encode($color->sizes ?? []) }}"
                     onclick="selectColorFromGrid(this)"
                     title="{{ $color->name }}">
                    <div style="width: 100%; height: 100%; border-radius: 6px; background-color: {{ $color->hex_code }}; position: relative; z-index: 1;"></div>
                </div>
                @endforeach
            </div>

            <div class="sb-section-label" style="margin-top:8px;">المقاسات المتاحة</div>
            <div class="sizes-grid" id="sizesGrid">
                <!-- Sizes will be generated here by JS -->
            </div>

            <div class="sb-section-label" style="margin-top:8px;">إرشادات</div>
            <div class="instructions">
                <div class="instruction-row">
                    <div class="instruction-icon">🖱️</div>
                    <span>اسحب اللوجو على الهودي</span>
                </div>
                <div class="instruction-row">
                    <div class="instruction-icon">📐</div>
                    <span>اضغط عليه للتحكم في الحجم والتدوير</span>
                </div>
                <div class="instruction-row">
                    <div class="instruction-icon">📸</div>
                    <span>صدّر صور التصميم بجودة عالية</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN -->
    <div class="main">
        <div class="top-bar">
            <button class="sidebar-toggle" id="sidebarToggle" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
            <div class="top-bar-btns">
                <button class="view-btn active" data-view="front">الوش</button>
                <div class="divider-dot"></div>
                <button class="view-btn" data-view="back">الظهر</button>
                <div class="divider-dot"></div>
                <button class="view-btn" data-view="left">يسار</button>
                <div class="divider-dot"></div>
                <button class="view-btn" data-view="right">يمين</button>
                <div class="divider-dot"></div>
                <button class="view-btn" id="freeControlBtn">تحكم حر</button>
                <div class="divider-dot"></div>
                <button class="view-btn" id="previewBtn">معاينة</button>
                <div class="divider-dot"></div>
                <button class="view-btn" id="themeToggleBtn" onclick="toggleTheme()">🌙 داكن</button>
            </div>
        </div>

        <div class="canvas-wrap" id="canvasWrap">
            <div class="corner corner-tl"></div>
            <div class="corner corner-tr"></div>
            <div class="corner corner-bl"></div>
            <div class="corner corner-br"></div>

            <div class="hoodie-container" id="hoodieContainer">
                <div class="hoodie-wrapper" id="hoodieWrapper" style="container-type: inline-size;">
                    <model-viewer
                        id="hoodieModel"
                        src="assets/3d_models/t-shirt-basic.glb"
                        alt="3D Hoodie" 
                        loading="eager" reveal="auto"
                        disable-zoom disable-pan touch-action="none"
                        camera-orbit="0deg 75deg 105%"
                        min-camera-orbit="auto 75deg auto"
                        max-camera-orbit="auto 75deg auto"
                        field-of-view="auto" camera-target="auto auto auto"
                        interaction-prompt="none">
                    </model-viewer>
                    <div class="color-overlay" id="colorOverlay"></div>
                    <div class="logos-overlay" id="logosOverlay"></div>
                </div>
            </div>
        </div>

        <div class="bottom-bar">
            <div class="status-hint">اضغط على اللوجو لـ <strong>أدوات التحكم</strong></div>
            <div class="bottom-actions">
                <button class="save-btn" onclick="openExportModal()">📸 تصدير صور</button>
                <button class="order-btn" onclick="openOrderModal()">إرسال الطلب ←</button>
            </div>
        </div>
    </div>
</div>

<!-- MOBILE LOGO TOOLBAR -->
<div class="logo-toolbar" id="logoToolbar">
    <button class="toolbar-btn" id="rotateCCW" title="تدوير يسار">↶</button>
    <button class="toolbar-btn" id="zoomOut" title="تصغير">−</button>
    <button class="toolbar-btn" id="zoomIn" title="تكبير">+</button>
    <button class="toolbar-btn" id="rotateCW" title="تدوير يمين">↷</button>
    <button class="toolbar-btn danger" id="deleteLogo" title="حذف">✕</button>
</div>

<!-- ORDER MODAL -->
<div class="modal-overlay" id="orderModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">تفاصيل <em>الطلب</em></div>
            <button class="modal-close" onclick="closeModal('orderModal')">✕</button>
        </div>
        <div class="modal-body" id="orderModalBody">
            <div class="form-group"><label>الاسم الكامل</label><input type="text" id="orderName" placeholder="اكتب اسمك"></div>
            <div class="form-group"><label>رقم الهاتف</label><input type="tel" id="orderPhone" placeholder="01xxxxxxxxx"></div>
            <div class="form-group">
                <label>المحافظة</label>
                <select id="orderGovernorate" onchange="updateShippingDisplay()">
                    <option value="">اختر المحافظة</option>
                    @foreach($governorates as $gov)
                        <option value="{{ $gov->id }}" data-price="{{ $gov->shipping_price }}">{{ $gov->name }}</option>
                    @endforeach
                </select>
            </div>
            <div id="shippingPriceRow" style="display:none; background:rgba(67,97,238,0.08); border-radius:10px; padding:8px 14px; margin-bottom:8px; font-size:13px; color:#4361ee; display:none; align-items:center; gap:8px;">
                <i>🚚</i> سعر الشحن: <strong id="shippingPriceVal">0</strong> ج.م
            </div>
            <div class="form-group"><label>العنوان التفصيلي</label><input type="text" id="orderAddress" placeholder="الشارع / المنطقة / الرقم"></div>
            <div class="form-group"><label>المقاس</label>
                <select id="orderSize">
                    <option value="">اختر المقاس</option>
                    <option>S</option><option>M</option><option>L</option><option>XL</option><option>XXL</option>
                </select>
            </div>
            <div class="form-group"><label>ملاحظات (اختياري)</label><textarea id="orderNotes" rows="2" placeholder="أي ملاحظات..."></textarea></div>
            <div class="form-group"><label>كود الخصم (اختياري)</label><input type="text" id="orderPromoCode" placeholder="أدخل كود الخصم" onblur="validatePromoCode()"><div id="promoCodeMessage" style="font-size: 12px; margin-top: 5px;"></div></div>
        </div>
        <div class="modal-footer" id="orderModalFooter">
            <button class="btn-cancel" onclick="closeModal('orderModal')">إلغاء</button>
            <button class="btn-submit" id="submitOrderBtn" onclick="submitOrder()">
                <span id="submitBtnText">تأكيد الطلب</span>
                <span id="submitBtnLoader" style="display:none;">جاري الإرسال...</span>
            </button>
        </div>
    </div>
</div>

<!-- EXPORT MODAL -->
<div class="modal-overlay" id="exportModal">
    <div class="modal-box" style="max-width:520px;">
        <div class="modal-header">
            <div class="modal-title">تصدير <em>الصور</em></div>
            <button class="modal-close" onclick="closeModal('exportModal')">✕</button>
        </div>
        <div class="modal-body">
            <p style="color:var(--muted);font-size:12px;margin-bottom:10px;line-height:1.8;">
                اضغط "إنشاء" لالتقاط 4 صور — وش / ظهر / يسار / يمين.
            </p>
            <div class="export-loading" id="exportLoading">
                <span style="animation:spin 1s linear infinite;display:inline-block">✦</span>
                جاري التقاط الصور...
            </div>
            <div class="export-previews" id="exportPreviews"></div>
        </div>
        <div class="modal-footer" id="exportModalFooter">
            <button class="btn-cancel" onclick="closeModal('exportModal')">إغلاق</button>
            <button class="btn-submit" id="generateExportBtn" onclick="generateExportImages()">📸 إنشاء الصور</button>
        </div>
    </div>
</div>

<!-- UPLOAD PROGRESS -->
<div id="uploadProgressBar"><div id="uploadProgressFill"></div></div>
<div id="uploadProgressLabel">جاري المعالجة...</div>

<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
<script>
const SECTIONS_DATA = {
    @foreach($sections as $section)
    {{ $section->id }}: {
        id: {{ $section->id }},
        name: "{{ addslashes($section->name) }}",
        logos: [@foreach($section->logos as $logo)"{{ asset('storage/' . $logo->image) }}",@endforeach]
    },
    @endforeach
};

const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const modelViewer     = document.getElementById('hoodieModel');
const hoodieWrapper   = document.getElementById('hoodieWrapper');
const logosOverlay    = document.getElementById('logosOverlay');
const colorOverlay    = document.getElementById('colorOverlay');
const viewButtons     = document.querySelectorAll('.view-btn[data-view]');
const hoodieContainer = document.getElementById('hoodieContainer');
const canvasWrap      = document.getElementById('canvasWrap');
const logoToolbar     = document.getElementById('logoToolbar');

let currentView       = 'front';
let logoCounter       = 0;
let isPreviewMode     = false, isFreeControlMode = false;
let previewInterval   = null;
let dragPreview       = null, isDraggingFromSidebar = false, currentDragSource = null;
let selectedLogo      = null, selectedLogoData = null;
let logosByView       = { front:[], back:[], left:[], right:[] };
let currentColor      = '#1a1a1a';
let currentSize       = null;
let uploadedLogos     = [];
let currentSectionId  = null;

const LOGO_SIZE_PCT = 18;
const cameraViews = {
    front: '0deg 75deg 105%',
    back:  '180deg 75deg 105%',
    left:  '90deg 75deg 105%',
    right: '-90deg 75deg 105%'
};

/* ════ LOADING ════ */
let loadPct = 0;
const lsBar = document.getElementById('lsBar');
const lsPct = document.getElementById('lsPct');
const lsInterval = setInterval(() => {
    loadPct = Math.min(loadPct + Math.random() * 7, 90);
    lsBar.style.width = loadPct + '%';
    lsPct.textContent = Math.floor(loadPct) + '%';
}, 200);

modelViewer.addEventListener('load', () => {
    clearInterval(lsInterval);
    lsBar.style.width = '100%'; lsPct.textContent = '100%';
    setTimeout(() => document.getElementById('loadingScreen').classList.add('hidden'), 500);
    modelViewer.cameraOrbit = cameraViews.front;
    applyColorToModel(currentColor);
    
    // Initialize sizes for the default selected color
    const defaultColorEl = document.querySelector('#colorsGrid .section-item.active');
    if (defaultColorEl) {
        updateSizesForColor(defaultColorEl.dataset.sizes);
    }
    
    updateContainerBackground(currentColor);
});
setTimeout(() => {
    const ls = document.getElementById('loadingScreen');
    if (!ls.classList.contains('hidden')) {
        clearInterval(lsInterval);
        lsBar.style.width = '100%'; lsPct.textContent = '100%';
        setTimeout(() => ls.classList.add('hidden'), 300);
    }
}, 9000);

/* ════ VIEWS ════ */
viewButtons.forEach(btn => btn.addEventListener('click', function () {
    if (isPreviewMode) stopPreview();
    if (isFreeControlMode) stopFreeControl();
    viewButtons.forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    currentView = this.dataset.view;
    modelViewer.cameraOrbit = cameraViews[currentView];
    updateVisibleLogos();
}));

document.getElementById('freeControlBtn').addEventListener('click', () => isFreeControlMode ? stopFreeControl() : startFreeControl());
document.getElementById('previewBtn').addEventListener('click', () => isPreviewMode ? stopPreview() : startPreview());

function startFreeControl() {
    if (isPreviewMode) stopPreview();
    deselectLogo();
    isFreeControlMode = true;
    document.getElementById('freeControlBtn').textContent = 'قفل';
    document.getElementById('freeControlBtn').classList.add('gold-active');
    document.body.classList.add('free-control-active');
    modelViewer.setAttribute('camera-controls', '');
    modelViewer.setAttribute('touch-action', 'pan-y');
    viewButtons.forEach(b => b.classList.remove('active'));
}
function stopFreeControl() {
    isFreeControlMode = false;
    document.getElementById('freeControlBtn').textContent = 'تحكم حر';
    document.getElementById('freeControlBtn').classList.remove('gold-active');
    document.body.classList.remove('free-control-active');
    modelViewer.removeAttribute('camera-controls');
    modelViewer.setAttribute('touch-action', 'none');
    modelViewer.cameraOrbit = cameraViews[currentView];
    updateVisibleLogos();
    viewButtons.forEach(b => { if (b.dataset.view === currentView) b.classList.add('active'); });
}
function startPreview() {
    if (isFreeControlMode) stopFreeControl();
    isPreviewMode = true;
    document.getElementById('previewBtn').textContent = 'إيقاف';
    document.getElementById('previewBtn').classList.add('gold-active');
    const views = ['front', 'right', 'back', 'left']; let i = 0;
    previewInterval = setInterval(() => {
        currentView = views[i];
        modelViewer.cameraOrbit = cameraViews[currentView];
        updateVisibleLogos();
        viewButtons.forEach(b => b.classList.toggle('active', b.dataset.view === currentView));
        i = (i + 1) % views.length;
    }, 1600);
}
function stopPreview() {
    isPreviewMode = false;
    document.getElementById('previewBtn').textContent = 'معاينة';
    document.getElementById('previewBtn').classList.remove('gold-active');
    if (previewInterval) { clearInterval(previewInterval); previewInterval = null; }
    updateVisibleLogos();
}

modelViewer.addEventListener('camera-change', () => {
    if (!isFreeControlMode) return;
    const orbit = modelViewer.getCameraOrbit();
    const deg = ((orbit.theta * 180 / Math.PI) % 360 + 360) % 360;
    const v = (deg >= 315 || deg < 45) ? 'front' : (deg >= 45 && deg < 135) ? 'left' : (deg >= 135 && deg < 225) ? 'back' : 'right';
    if (v !== currentView) { currentView = v; updateVisibleLogos(); }
});

/* ════ SIDEBAR SWIPE ════ */
(function() {
    const sidebar = document.getElementById('sidebar');
    let startX = 0, startY = 0, isSwiping = false;
    sidebar.addEventListener('touchstart', e => { startX = e.touches[0].clientX; startY = e.touches[0].clientY; isSwiping = false; }, { passive: true });
    sidebar.addEventListener('touchmove', e => {
        const dx = e.touches[0].clientX - startX;
        const dy = Math.abs(e.touches[0].clientY - startY);
        if (!isSwiping && Math.abs(dx) > 10 && dy < 40) isSwiping = true;
        if (isSwiping && dx > 40) { sidebar.classList.remove('open'); isSwiping = false; }
    }, { passive: true });
})();

/* ════ TOOLBAR ════ */
document.getElementById('rotateCCW').addEventListener('click', () => { if (!selectedLogoData) return; selectedLogoData.rotation=(selectedLogoData.rotation||0)-15; selectedLogo.style.transform=`rotate(${selectedLogoData.rotation}deg)`; });
document.getElementById('rotateCW').addEventListener('click',  () => { if (!selectedLogoData) return; selectedLogoData.rotation=(selectedLogoData.rotation||0)+15; selectedLogo.style.transform=`rotate(${selectedLogoData.rotation}deg)`; });
document.getElementById('zoomIn').addEventListener('click',  () => { if (!selectedLogoData) return; selectedLogoData.widthPercent=Math.min(100, selectedLogoData.widthPercent*1.1); selectedLogoData.heightPercent=Math.min(100, selectedLogoData.heightPercent*1.1); selectedLogo.style.width=selectedLogoData.widthPercent+'%'; selectedLogo.style.height=selectedLogoData.heightPercent+'%'; });
document.getElementById('zoomOut').addEventListener('click', () => { if (!selectedLogoData) return; selectedLogoData.widthPercent=Math.max(2, selectedLogoData.widthPercent*0.9); selectedLogoData.heightPercent=Math.max(2, selectedLogoData.heightPercent*0.9); selectedLogo.style.width=selectedLogoData.widthPercent+'%'; selectedLogo.style.height=selectedLogoData.heightPercent+'%'; });
document.getElementById('deleteLogo').addEventListener('click', () => {
    if (!selectedLogoData||!selectedLogo) return;
    logosByView[selectedLogoData.view] = logosByView[selectedLogoData.view].filter(l=>l.id!==selectedLogoData.id);
    selectedLogo.remove(); deselectLogo();
});

document.addEventListener('click', e => { if (!e.target.closest('.logo-on-hoodie') && !e.target.closest('.logo-toolbar') && !e.target.closest('.sidebar')) deselectAll(); });
document.addEventListener('touchend', e => { if (isDraggingFromSidebar) return; if (!e.target.closest('.logo-on-hoodie') && !e.target.closest('.logo-toolbar') && !e.target.closest('.sidebar')) deselectAll(); }, {passive:true});

function deselectAll() { if (selectedLogo) selectedLogo.classList.remove('selected'); selectedLogo=selectedLogoData=null; logoToolbar.classList.remove('active'); }
function deselectLogo() { deselectAll(); }
function selectLogo(logo, data) {
    if (isFreeControlMode) stopFreeControl();
    logosOverlay.querySelectorAll('.logo-on-hoodie').forEach(l=>l.classList.remove('selected'));
    selectedLogo=logo; selectedLogoData=data; logo.classList.add('selected'); logoToolbar.classList.add('active');
    
    if (data.type === 'text') {
        document.getElementById('addTextInput').value = data.rawText || data.text || '';
        document.getElementById('addTextColor').value = data.color || '#ffffff';
        document.getElementById('addTextFont').value = data.font || "'Cairo', sans-serif";
        const toggle = document.getElementById('tashkeelToggle');
        if (toggle) toggle.checked = data.hasTashkeel || false;
    }
}

/* ════ DRAG FROM SIDEBAR ════ */
document.addEventListener('touchmove', e => {
    if (!isDraggingFromSidebar||!dragPreview) return;
    e.preventDefault();
    const t = e.touches[0];
    dragPreview.style.left = t.clientX-32+'px'; dragPreview.style.top = t.clientY-32+'px';
    const r = hoodieWrapper.getBoundingClientRect();
    hoodieWrapper.classList.toggle('drag-over', t.clientX>=r.left&&t.clientX<=r.right&&t.clientY>=r.top&&t.clientY<=r.bottom);
}, {passive:false});

document.addEventListener('touchend', e => {
    if (!isDraggingFromSidebar) return;
    const t = e.changedTouches[0];
    const r = hoodieWrapper.getBoundingClientRect();
    if (t.clientX>=r.left&&t.clientX<=r.right&&t.clientY>=r.top&&t.clientY<=r.bottom)
        addLogo(currentDragSource.src, t.clientX-r.left, t.clientY-r.top);
    if (dragPreview) { dragPreview.remove(); dragPreview=null; }
    isDraggingFromSidebar=false; document.body.style.overflow=''; currentDragSource=null;
    hoodieWrapper.classList.remove('drag-over');
});

hoodieWrapper.addEventListener('dragover',  e => { e.preventDefault(); e.dataTransfer.dropEffect='copy'; hoodieWrapper.classList.add('drag-over'); });
hoodieWrapper.addEventListener('dragleave', () => hoodieWrapper.classList.remove('drag-over'));
hoodieWrapper.addEventListener('drop', e => {
    e.preventDefault(); hoodieWrapper.classList.remove('drag-over');
    if (!currentDragSource) return;
    const r = hoodieWrapper.getBoundingClientRect();
    addLogo(currentDragSource.src, e.clientX-r.left, e.clientY-r.top);
});

/* ════ ADD LOGO / TEXT ════ */
function addLogo(src, x, y) {
    if (isFreeControlMode) stopFreeControl();
    logoCounter++;
    const r = hoodieWrapper.getBoundingClientRect();
    const cx=(x/r.width)*100, cy=(y/r.height)*100;
    const wp=LOGO_SIZE_PCT, hp=LOGO_SIZE_PCT;
    const data = {
        id: logoCounter, type: 'image', src,
        centerXPercent: cx, centerYPercent: cy,
        xPercent: Math.max(0,Math.min(cx-wp/2,100-wp)),
        yPercent: Math.max(0,Math.min(cy-hp/2,100-hp)),
        widthPercent: wp, heightPercent: hp, rotation: 0, view: currentView
    };
    logosByView[currentView].push(data);
    const el = createLogoElement(data);
    setTimeout(() => selectLogo(el, data), 80);
}

function handleAddText() {
    if (isFreeControlMode) stopFreeControl();
    deselectLogo();
    document.getElementById('addTextInput').value = '';
    document.getElementById('addTextInput').focus();
}

/* ════ CUSTOM FONT PICKER ════ */
function toggleFontPicker() {
    const btn = document.getElementById('fontPickerBtn');
    const dd  = document.getElementById('fontPickerDropdown');
    btn.classList.toggle('open');
    dd.classList.toggle('open');
}

function selectFont(el) {
    // remove selected from all options
    document.querySelectorAll('.font-picker-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');

    const fontVal = el.dataset.font;
    // update hidden input
    document.getElementById('addTextFont').value = fontVal;

    // update button label with chosen font
    const label = document.getElementById('fontPickerLabel');
    label.textContent = el.textContent.replace('✓', '').trim();
    label.style.fontFamily = fontVal;

    // close dropdown
    document.getElementById('fontPickerBtn').classList.remove('open');
    document.getElementById('fontPickerDropdown').classList.remove('open');

    // trigger live update
    handleTextLiveUpdate('font');
}

// Close font picker when clicking outside
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('fontPickerWrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('fontPickerBtn')?.classList.remove('open');
        document.getElementById('fontPickerDropdown')?.classList.remove('open');
    }
});

function applyDecorativeTashkeel(text, isActive) {
    if (!isActive) return text;
    const marks = ['\u064E', '\u064F', '\u0650', '\u0652', '\u064C', '\u064B'];
    let result = '';
    for (let i = 0; i < text.length; i++) {
        const char = text[i];
        result += char;
        if (/[\u0621-\u064A]/.test(char) && (i === text.length - 1 || !/[\u064B-\u065F]/.test(text[i+1]))) {
            const hash = char.charCodeAt(0) + i;
            if (hash % 3 !== 0) { 
                result += marks[hash % marks.length];
            }
        }
    }
    return result;
}

function handleTextLiveUpdate(type) {
    const inputVal = document.getElementById('addTextInput').value;
    const colorVal = document.getElementById('addTextColor').value;
    const fontVal = document.getElementById('addTextFont').value;
    const isTashkeel = document.getElementById('tashkeelToggle').checked;
    
    const processedText = applyDecorativeTashkeel(inputVal, isTashkeel);
    
    if (selectedLogoData && selectedLogoData.type === 'text') {
        if (type === 'text' || type === 'tashkeel') {
            selectedLogoData.rawText = inputVal;
            selectedLogoData.text = processedText;
            selectedLogoData.hasTashkeel = isTashkeel;
            selectedLogo.querySelector('span').textContent = processedText;
        } else if (type === 'color') {
            selectedLogoData.color = colorVal;
            selectedLogo.querySelector('span').style.color = colorVal;
        } else if (type === 'font') {
            selectedLogoData.font = fontVal;
            selectedLogo.querySelector('span').style.fontFamily = fontVal;
        }
    } else {
        if ((type === 'text' || type === 'tashkeel') && inputVal.trim() !== '') {
            const r = hoodieWrapper.getBoundingClientRect();
            addText(inputVal, processedText, colorVal, fontVal, isTashkeel, r.width / 2, r.height / 2);
        }
    }
}

function addText(rawText, processedText, color, font, hasTashkeel, x, y) {
    if (isFreeControlMode) stopFreeControl();
    logoCounter++;
    const r = hoodieWrapper.getBoundingClientRect();
    const cx=(x/r.width)*100, cy=(y/r.height)*100;
    const data = {
        id: logoCounter, type: 'text', text: processedText, rawText, color, font, hasTashkeel,
        centerXPercent: cx, centerYPercent: cy,
        fontSizeCqw: 5, isFixedWidth: false,
        xPercent: cx - 10, yPercent: cy - 3,
        widthPercent: 'auto', heightPercent: 'auto', rotation: 0, view: currentView
    };
    logosByView[currentView].push(data);
    const el = createLogoElement(data);
    selectLogo(el, data);
}

function updateLogoCenter(d) { 
    const el = logosOverlay.querySelector(`.logo-on-hoodie[data-id="${d.id}"]`);
    if (el) {
        const cr = hoodieWrapper.getBoundingClientRect();
        const lr = el.getBoundingClientRect();
        d.centerXPercent = ((lr.left - cr.left + lr.width/2) / cr.width) * 100;
        d.centerYPercent = ((lr.top - cr.top + lr.height/2) / cr.height) * 100;
    } else {
        d.centerXPercent=d.xPercent+(d.widthPercent==='auto'?10:d.widthPercent/2); 
        d.centerYPercent=d.yPercent+(d.heightPercent==='auto'?5:d.heightPercent/2); 
    }
}

function createLogoElement(data) {
    const logo = document.createElement('div');
    logo.className='logo-on-hoodie'; logo.dataset.id=data.id; logo.dataset.view=data.view;
    logo.style.left = data.xPercent + '%';
    logo.style.top = data.yPercent + '%';
    if (data.widthPercent !== 'auto') logo.style.width = data.widthPercent + '%';
    else logo.style.width = 'auto';
    if (data.heightPercent !== 'auto') logo.style.height = data.heightPercent + '%';
    else logo.style.height = 'auto';
    logo.style.transform = `rotate(${data.rotation}deg)`;
    
    if (data.type === 'text') {
        logo.classList.add('text-type');
        if (data.isFixedWidth) logo.classList.add('fixed-width');
        const span = document.createElement('span');
        span.textContent = data.text;
        span.style.color = data.color;
        span.style.fontFamily = data.font;
        span.style.fontSize = data.fontSizeCqw + 'cqw';
        logo.appendChild(span);
        
        const handleX = document.createElement('div');
        handleX.className = 'resize-handle-x';
        logo.appendChild(handleX);
        makeResizableTextX(logo, data, handleX);
    } else {
        const img=document.createElement('img'); img.src=data.src; img.draggable=false;
        logo.appendChild(img);
    }

    const del=document.createElement('button'); del.className='delete-btn'; del.innerHTML='✕';
    del.onclick=e=>{ e.stopPropagation(); logosByView[data.view]=logosByView[data.view].filter(l=>l.id!==data.id); logo.remove(); deselectLogo(); };
    const handle=document.createElement('div'); handle.className='resize-handle';
    logo.append(del,handle);
    
    logo.addEventListener('click', e=>{ e.stopPropagation(); const d=logosByView[logo.dataset.view].find(l=>l.id===parseInt(logo.dataset.id)); if(d) selectLogo(logo,d); });
    let tStart=0, tPos={x:0,y:0};
    logo.addEventListener('touchstart', e=>{ tStart=Date.now(); if(e.touches[0]) tPos={x:e.touches[0].clientX,y:e.touches[0].clientY}; },{passive:true});
    logo.addEventListener('touchend', e=>{ const dur=Date.now()-tStart, t=e.changedTouches[0]; if(t&&dur<200&&Math.hypot(t.clientX-tPos.x,t.clientY-tPos.y)<10){ const d=logosByView[logo.dataset.view].find(l=>l.id===parseInt(logo.dataset.id)); if(d) selectLogo(logo,d); } },{passive:true});
    logosOverlay.appendChild(logo);
    if (data.view===currentView) logo.classList.add('active');
    makeDraggable(logo,data); makeResizable(logo,data,handle);
    return logo;
}

function updateVisibleLogos() {
    logosOverlay.querySelectorAll('.logo-on-hoodie').forEach(l=>l.classList.toggle('active', l.dataset.view===currentView));
}

function makeDraggable(logo,data) {
    let dragging=false,sx,sy,sl,st;
    const start=e=>{ if(isDraggingFromSidebar) return; if(e.target.classList.contains('delete-btn')||e.target.classList.contains('resize-handle')||e.target.classList.contains('resize-handle-x')) return; if(e.touches&&e.touches.length>1) return; e.preventDefault(); e.stopPropagation(); dragging=true; const t=e.touches?e.touches[0]:e; sx=t.clientX;sy=t.clientY;sl=data.xPercent;st=data.yPercent; };
    const move=e=>{ 
        if(!dragging) return; if(e.touches&&e.touches.length>1){stop();return;} e.preventDefault(); 
        const t=e.touches?e.touches[0]:e; const r=hoodieWrapper.getBoundingClientRect(); 
        const wPct = data.widthPercent === 'auto' ? (logo.offsetWidth/r.width)*100 : data.widthPercent;
        const hPct = data.heightPercent === 'auto' ? (logo.offsetHeight/r.height)*100 : data.heightPercent;
        data.xPercent=Math.max(0,Math.min(sl+((t.clientX-sx)/r.width)*100,100-wPct)); 
        data.yPercent=Math.max(0,Math.min(st+((t.clientY-sy)/r.height)*100,100-hPct)); 
        logo.style.left=data.xPercent+'%'; logo.style.top=data.yPercent+'%'; updateLogoCenter(data); 
    };
    const stop=()=>{ dragging=false; };
    logo.addEventListener('mousedown',start); document.addEventListener('mousemove',move); document.addEventListener('mouseup',stop);
    logo.addEventListener('touchstart',start,{passive:false}); logo.addEventListener('touchmove',move,{passive:false}); logo.addEventListener('touchend',stop,{passive:false});
}

function makeResizable(logo,data,handle) {
    let resizing=false,sx,sy,ssW,ssH,sFs,raf=null;
    const start=e=>{ 
        e.stopPropagation(); e.preventDefault(); resizing=true; 
        const t=e.touches?e.touches[0]:e; sy=t.clientY; 
        ssW=data.widthPercent; ssH=data.heightPercent; sFs=data.fontSizeCqw; 
    };
    const move=e=>{ 
        if(!resizing) return; e.preventDefault(); 
        const t=e.touches?e.touches[0]:e; 
        const cr=hoodieWrapper.getBoundingClientRect(); 
        const deltaYPct = ((t.clientY-sy)/cr.height)*100;
        
        if (data.type === 'text') {
            const scale = Math.max(0.1, Math.min(5, 1 + deltaYPct/10)); 
            const nsFs = sFs * scale;
            if (nsFs < 1 || nsFs > 50) return;
            if(raf) cancelAnimationFrame(raf); 
            raf=requestAnimationFrame(()=>{ 
                data.fontSizeCqw = nsFs;
                logo.querySelector('span').style.fontSize = nsFs + 'cqw';
                if (data.isFixedWidth && ssW !== 'auto') {
                    data.widthPercent = ssW * scale;
                    logo.style.width = data.widthPercent + '%';
                }
                updateLogoCenter(data); 
            }); 
        } else {
            const scale = Math.max(0.1, Math.min(5, 1 + deltaYPct/ssH)); 
            const nsH = ssH * scale;
            const nsW = ssW * scale;
            if (nsH < 2 || nsW < 2 || nsH > 90 || nsW > 90) return; 
            if(raf) cancelAnimationFrame(raf); 
            raf=requestAnimationFrame(()=>{ 
                data.widthPercent=nsW; data.heightPercent=nsH; 
                logo.style.width=nsW+'%'; logo.style.height=nsH+'%'; 
                updateLogoCenter(data); 
            }); 
        }
    };
    const stop=()=>{ if(raf){cancelAnimationFrame(raf);raf=null;} resizing=false; };
    handle.addEventListener('mousedown',start,true); document.addEventListener('mousemove',move); document.addEventListener('mouseup',stop);
    handle.addEventListener('touchstart',start,{passive:false,capture:true}); document.addEventListener('touchmove',move,{passive:false}); document.addEventListener('touchend',stop);
}

function makeResizableTextX(logo,data,handle) {
    let resizing=false,sx,ssW,raf=null;
    const start=e=>{ 
        e.stopPropagation(); e.preventDefault(); resizing=true; 
        const t=e.touches?e.touches[0]:e; sx=t.clientX; 
        if (data.widthPercent === 'auto') {
            const cr = hoodieWrapper.getBoundingClientRect();
            const lr = logo.getBoundingClientRect();
            ssW = (lr.width / cr.width) * 100;
            data.widthPercent = ssW;
            data.isFixedWidth = true;
            logo.classList.add('fixed-width');
        } else {
            ssW = data.widthPercent;
        }
    };
    const move=e=>{ 
        if(!resizing) return; e.preventDefault(); 
        const t=e.touches?e.touches[0]:e; 
        const cr=hoodieWrapper.getBoundingClientRect(); 
        const deltaXPct = ((t.clientX-sx)/cr.width)*100;
        const nsW = ssW + deltaXPct;
        if (nsW < 2 || nsW > 90) return; 
        if(raf) cancelAnimationFrame(raf); 
        raf=requestAnimationFrame(()=>{ 
            data.widthPercent=nsW; logo.style.width=nsW+'%'; 
            updateLogoCenter(data); 
        }); 
    };
    const stop=()=>{ if(raf){cancelAnimationFrame(raf);raf=null;} resizing=false; };
    handle.addEventListener('mousedown',start,true); document.addEventListener('mousemove',move); document.addEventListener('mouseup',stop);
    handle.addEventListener('touchstart',start,{passive:false,capture:true}); document.addEventListener('touchmove',move,{passive:false}); document.addEventListener('touchend',stop);
}

/* ════ SECTIONS ════ */
function selectSection(el, sectionId) {
    const sid = String(sectionId);
    if (currentSectionId === sid) {
        el.classList.remove('active');
        currentSectionId = null;
        document.getElementById('logosPanel').classList.remove('open');
        return;
    }
    document.querySelectorAll('.section-item').forEach(s=>s.classList.remove('active'));
    el.classList.add('active');
    currentSectionId = sid;
    const section = SECTIONS_DATA[sectionId];
    if (!section) return;
    document.getElementById('selectedSectionName').textContent = section.name;
    const grid = document.getElementById('logoGrid');
    grid.innerHTML = '';
    const allLogos = [...(section.logos||[]), ...uploadedLogos];
    if (!allLogos.length) {
        const p = document.createElement('p'); p.className='no-logos-msg'; p.textContent='لا توجد لوجوهات'; grid.appendChild(p);
    } else {
        allLogos.forEach(src=>addLogoToGrid(src,grid));
    }
    document.getElementById('logosPanel').classList.add('open');
    grid.classList.add('slide-in');
}

function addLogoToGrid(src, grid) {
    const img = document.createElement('img');
    img.src=src; img.className='logo-item'; img.alt='Logo'; img.draggable=true;
    img.addEventListener('dragstart', e=>{ currentDragSource=img; e.dataTransfer.effectAllowed='copy'; e.dataTransfer.setData('text/plain',src); document.getElementById('sidebar').classList.remove('open'); });
    img.addEventListener('dragend', ()=>currentDragSource=null);
    img.addEventListener('click', () => {
        const r = hoodieWrapper.getBoundingClientRect();
        addLogo(src, r.width / 2, r.height / 2);
        document.getElementById('sidebar').classList.remove('open');
        showToast('تم إضافة اللوجو في المنتصف ✓');
    });
    let timer=null, touchMoved=false;
    img.addEventListener('touchstart', e=>{ 
        touchMoved = false;
        const t=e.touches[0],sx=t.clientX,sy=t.clientY; 
        timer=setTimeout(()=>{ isDraggingFromSidebar=true; document.body.style.overflow='hidden'; currentDragSource=img; dragPreview=document.createElement('img'); dragPreview.src=src; dragPreview.className='drag-preview'; dragPreview.style.left=sx-32+'px'; dragPreview.style.top=sy-32+'px'; document.body.appendChild(dragPreview); document.getElementById('sidebar').classList.remove('open'); },150); 
    },{passive:true});
    img.addEventListener('touchmove', e=>{ 
        touchMoved = true;
        if(!isDraggingFromSidebar&&timer){ clearTimeout(timer);timer=null; const t=e.touches[0]; isDraggingFromSidebar=true; document.body.style.overflow='hidden'; currentDragSource=img; dragPreview=document.createElement('img'); dragPreview.src=src; dragPreview.className='drag-preview'; dragPreview.style.left=t.clientX-32+'px'; dragPreview.style.top=t.clientY-32+'px'; document.body.appendChild(dragPreview); document.getElementById('sidebar').classList.remove('open'); } 
    },{passive:true});
    img.addEventListener('touchend', e=>{ 
        if(timer){clearTimeout(timer);timer=null;} 
        if(!isDraggingFromSidebar && !touchMoved) {
            const r = hoodieWrapper.getBoundingClientRect();
            addLogo(src, r.width / 2, r.height / 2);
            document.getElementById('sidebar').classList.remove('open');
            showToast('تم إضافة اللوجو في المنتصف ✓');
        }
    },{passive:true});
    grid.appendChild(img);
}

/* ════ COLOR PICKER ════ */
function selectColorFromGrid(colorItem) {
    const color = colorItem.dataset.color;
    currentColor = color;
    
    // Update UI
    document.querySelectorAll('#colorsGrid .section-item').forEach(item => item.classList.remove('active'));
    colorItem.classList.add('active');
    
    // Apply color to model
    applyColorToModel(color);
    
    // Update sizes
    updateSizesForColor(colorItem.dataset.sizes);
    
    showToast('تم تغيير لون الهودي ✓');
}

function updateSizesForColor(sizesJson) {
    const grid = document.getElementById('sizesGrid');
    grid.innerHTML = '';
    currentSize = null; // Reset selected size when color changes
    
    let availableSizes = [];
    try {
        if (sizesJson) availableSizes = JSON.parse(sizesJson);
    } catch(e) {}
    
    const allSizes = ['S', 'M', 'L', 'XL', 'XXL'];
    
    allSizes.forEach(size => {
        const isAvailable = availableSizes.includes(size);
        const el = document.createElement('div');
        el.className = `size-item ${!isAvailable ? 'unavailable' : ''}`;
        el.textContent = size;
        
        if (isAvailable) {
            el.onclick = () => selectSize(el, size);
        }
        
        grid.appendChild(el);
    });
}

function selectSize(el, size) {
    document.querySelectorAll('#sizesGrid .size-item').forEach(item => item.classList.remove('active'));
    el.classList.add('active');
    currentSize = size;
    
    // Auto update the order modal select if it exists
    const orderSizeSelect = document.getElementById('orderSize');
    if (orderSizeSelect) {
        orderSizeSelect.value = size;
    }
}

function applyColorToModel(color) {
    if (!modelViewer) return;
    
    // Update background based on color brightness
    updateContainerBackground(color);
    
    try {
        // Try to change material color using model-viewer API
        const model = modelViewer.model;
        if (model && model.materials) {
            model.materials.forEach(material => {
                if (material && material.pbrMetallicRoughness) {
                    material.pbrMetallicRoughness.setBaseColorFactor(color);
                }
            });
        }
    } catch (e) {
        // Fallback: use color overlay
        colorOverlay.style.backgroundColor = color;
        colorOverlay.classList.add('active');
    }
}

function updateContainerBackground(color) {
    const container = document.getElementById('hoodieContainer');
    if (!container) return;
    
    // Calculate brightness
    const hex = color.replace('#', '');
    const r = parseInt(hex.substr(0, 2), 16);
    const g = parseInt(hex.substr(2, 2), 16);
    const b = parseInt(hex.substr(4, 2), 16);
    const brightness = (r * 299 + g * 587 + b * 114) / 1000;
    
    // If color is light (brightness > 128), use dark background
    if (brightness > 128) {
        container.style.background = 'radial-gradient(circle at center, #2a2a2a 0%, #1a1a1a 100%)';
        container.style.boxShadow = 'inset 0 0 40px rgba(0,0,0,0.3)';
    } else {
        // If color is dark, use light background
        container.style.background = 'radial-gradient(circle at center, #f0f0f0 0%, #e0e0e0 100%)';
        container.style.boxShadow = 'inset 0 0 40px rgba(0,0,0,0.1)';
    }
}

/* ════ UPLOAD PROGRESS ════ */
function showProgress(pct, label) {
    const bar=document.getElementById('uploadProgressBar'), fill=document.getElementById('uploadProgressFill'), lbl=document.getElementById('uploadProgressLabel');
    bar.style.display='block'; lbl.style.display='block'; fill.style.width=pct+'%'; lbl.textContent=label;
}
function hideProgress() {
    const bar=document.getElementById('uploadProgressBar'), fill=document.getElementById('uploadProgressFill'), lbl=document.getElementById('uploadProgressLabel');
    fill.style.width='100%';
    setTimeout(()=>{ bar.style.display='none'; lbl.style.display='none'; fill.style.width='0%'; },500);
}

/* ════ UPLOAD LOGO ════ */
async function handleLogoUpload(input) {
    const file = input.files[0]; if (!file) return;
    if (isFreeControlMode) stopFreeControl();
    input.value = "";
    const originalBase64 = await new Promise(res => { const r=new FileReader(); r.onload=e=>res(e.target.result); r.readAsDataURL(file); });
    showProgress(10, 'جاري تحميل الـ AI...');
    let finalSrc = originalBase64;
    try {
        const { removeBackground } = await import("https://cdn.jsdelivr.net/npm/@imgly/background-removal@1.4.5/+esm");
        showProgress(20, 'جاري إزالة الخلفية...');
        const blob = await removeBackground(file, { progress: (key, current, total) => { if (key==="compute:inference"&&total>0) { const pct=20+Math.round((current/total)*65); showProgress(pct,'جاري المعالجة '+Math.round((current/total)*100)+'%'); } } });
        if (blob && blob.size > 1000) { finalSrc = await new Promise(res=>{ const r=new FileReader(); r.onload=e=>res(e.target.result); r.readAsDataURL(blob); }); showProgress(88, 'تمت إزالة الخلفية ✓'); } else { showProgress(88, 'جاري الرفع...'); }
    } catch(e) { console.warn("bg removal failed:", e); showProgress(88, 'جاري الرفع...'); }
    showProgress(92, 'جاري الرفع...');
    try {
        const res = await fetch("/logos/upload-temp", { method:"POST", headers:{"X-CSRF-TOKEN":CSRF_TOKEN,"Accept":"application/json"}, body:(()=>{ const fd=new FormData(); const arr=finalSrc.split(','); const mime=arr[0].match(/:(.*?);/)[1]; const bstr=atob(arr[1]); let n=bstr.length; const u8=new Uint8Array(n); while(n--) u8[n]=bstr.charCodeAt(n); fd.append("image",new Blob([u8],{type:mime}),"logo.png"); return fd; })() });
        const data = await res.json();
        if (data.url) { try { const urlPath=new URL(data.url).pathname; finalSrc=urlPath; } catch { finalSrc=data.url; } }
    } catch(e) {}
    showProgress(100, 'تم ✓');
    hideProgress();
    const r = hoodieWrapper.getBoundingClientRect();
    addLogo(finalSrc, r.width/2, r.height/2);
    document.getElementById('sidebar').classList.remove('open');
    addUploadedLogo(finalSrc);
}

function addUploadedLogo(src) {
    uploadedLogos.push(src);
    const grid = document.getElementById('logoGrid');
    if (!currentSectionId) {
        document.getElementById('selectedSectionName').textContent = 'مرفوعاتي';
        grid.innerHTML = ''; uploadedLogos.forEach(s=>addLogoToGrid(s,grid));
        document.getElementById('logosPanel').classList.add('open');
    } else { const noMsg=grid.querySelector('.no-logos-msg'); if(noMsg) noMsg.remove(); addLogoToGrid(src,grid); }
}

function showToast(msg) {
    const t = document.createElement('div');
    t.style.cssText = 'position:fixed;top:16px;left:50%;transform:translateX(-50%);background:var(--ink);color:var(--gold-light);border:1px solid rgba(184,146,74,0.3);padding:8px 20px;border-radius:100px;font-size:11px;font-weight:700;z-index:99999;pointer-events:none;transition:opacity 0.4s;letter-spacing:0.04em;box-shadow:0 8px 24px rgba(26,22,18,0.25);';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(()=>{ t.style.opacity='0'; setTimeout(()=>t.remove(),400); }, 2200);
}

/* ════ EXPORT ════ */
function openExportModal() {
    document.getElementById('exportPreviews').innerHTML = '';
    document.getElementById('exportLoading').style.display = 'none';
    document.getElementById('exportModalFooter').innerHTML = `<button class="btn-cancel" onclick="closeModal('exportModal')">إغلاق</button><button class="btn-submit" id="generateExportBtn" onclick="generateExportImages()">📸 إنشاء الصور</button>`;
    document.getElementById('exportModal').classList.add('open');
}

async function generateExportImages() {
    const btn = document.getElementById('generateExportBtn');
    btn.disabled=true; btn.textContent='جاري الالتقاط...';
    document.getElementById('exportLoading').style.display='block';
    document.getElementById('exportPreviews').innerHTML='';
    const viewConfigs=[{key:'front',label:'الوش',orbit:'0deg 75deg 105%'},{key:'back',label:'الظهر',orbit:'180deg 75deg 105%'},{key:'left',label:'يسار',orbit:'90deg 75deg 105%'},{key:'right',label:'يمين',orbit:'-90deg 75deg 105%'}];
    const captured = [];
    logosOverlay.style.display = 'none';
    for (const vc of viewConfigs) {
        modelViewer.cameraOrbit=vc.orbit; await new Promise(r=>setTimeout(r,700));
        try { const blob=await modelViewer.toBlob({idealAspect:false}); const dataUrl=await blobToDataUrl(blob); captured.push({...vc,dataUrl}); } catch(err) { captured.push({...vc,dataUrl:null}); }
    }
    modelViewer.cameraOrbit=cameraViews[currentView]; logosOverlay.style.display=''; updateVisibleLogos();
    const composited=[];
    for (const item of captured) {
        if(!item.dataUrl){composited.push({...item,dataUrl:null});continue;}
        try{const finalUrl=await compositeLogoOnImage(item.dataUrl,item.key);composited.push({...item,dataUrl:finalUrl});}catch(e){composited.push(item);}
    }
    const CELL=800,GAP=12,LABEL_H=40,PADDING=20,COLS=2,ROWS=2;
    const totalW=COLS*CELL+(COLS-1)*GAP+PADDING*2, totalH=ROWS*(CELL+LABEL_H)+(ROWS-1)*GAP+PADDING*2+50;
    const finalCanvas=document.createElement('canvas'); finalCanvas.width=totalW; finalCanvas.height=totalH;
    const ctx=finalCanvas.getContext('2d');
    ctx.fillStyle='#1a1612'; ctx.fillRect(0,0,totalW,totalH);
    ctx.fillStyle='#b8924a'; ctx.font='bold 28px "Cormorant Garamond",serif'; ctx.textAlign='center';
    ctx.fillText('WearCraft — تصميم الهودي',totalW/2,PADDING+28);
    const positions=[{col:0,row:0},{col:1,row:0},{col:0,row:1},{col:1,row:1}];
    await Promise.all(composited.map((item,i)=>new Promise(resolve=>{
        const{col,row}=positions[i],x=PADDING+col*(CELL+GAP),y=PADDING+50+row*(CELL+LABEL_H+GAP);
        ctx.fillStyle='#ede9e0'; ctx.beginPath(); ctx.roundRect(x,y,CELL,CELL,12); ctx.fill();
        ctx.fillStyle='#b8924a'; ctx.beginPath(); ctx.roundRect(x+CELL/2-40,y+8,80,26,13); ctx.fill();
        ctx.fillStyle='#ffffff'; ctx.font='bold 14px Cairo,sans-serif'; ctx.textAlign='center';
        ctx.fillText(item.label,x+CELL/2,y+26);
        if(!item.dataUrl){resolve();return;}
        const img=new Image(); img.onload=()=>{ ctx.save(); ctx.beginPath(); ctx.roundRect(x,y,CELL,CELL,12); ctx.clip(); ctx.drawImage(img,x,y,CELL,CELL); ctx.restore(); resolve(); };
        img.onerror=()=>resolve(); img.src=item.dataUrl;
    })));
    const finalDataUrl=finalCanvas.toDataURL('image/png');
    document.getElementById('exportLoading').style.display='none';
    const previewsEl=document.getElementById('exportPreviews');
    previewsEl.style.gridTemplateColumns='1fr';
    const wrap=document.createElement('div'); wrap.className='export-preview-item'; wrap.style.background='#1a1612';
    const prevImg=document.createElement('img'); prevImg.src=finalDataUrl; prevImg.style.cssText='width:100%;display:block;border-radius:8px;';
    wrap.appendChild(prevImg); previewsEl.appendChild(wrap);
    window._exportFinalImage=finalDataUrl;
    const footerEl=document.getElementById('exportModalFooter');
    footerEl.innerHTML='';
    const _c=document.createElement('button'); _c.className='btn-cancel'; _c.textContent='إغلاق'; _c.onclick=()=>closeModal('exportModal');
    const _d=document.createElement('button'); _d.className='btn-submit'; _d.textContent='⬇️ تحميل الصورة';
    _d.onclick=()=>{ const a=document.createElement('a'); a.href=window._exportFinalImage; a.download='WearCraft-design.png'; document.body.appendChild(a); a.click(); document.body.removeChild(a); };
    footerEl.appendChild(_c); footerEl.appendChild(_d);
}

async function compositeLogoOnImage(bgDataUrl, viewKey) {
    return new Promise((resolve)=>{
        const canvas=document.createElement('canvas'); const size=800; canvas.width=size; canvas.height=size;
        const ctx=canvas.getContext('2d'); const bg=new Image(); bg.crossOrigin='anonymous';
        bg.onload=async()=>{
            ctx.drawImage(bg,0,0,size,size);
            const logos=logosByView[viewKey]||[];
            for(const d of logos){
                if (d.type === 'text') {
                    await drawTextOnCanvas(ctx, d, size);
                } else {
                    await new Promise(rLogo=>{
                        const lImg=new Image(); lImg.crossOrigin='anonymous';
                        lImg.onload=()=>{ ctx.save(); const lx=(d.xPercent/100)*size,ly=(d.yPercent/100)*size,lw=(d.widthPercent/100)*size,lh=(d.heightPercent/100)*size,cx=lx+lw/2,cy=ly+lh/2; ctx.translate(cx,cy); ctx.rotate((d.rotation||0)*Math.PI/180); ctx.drawImage(lImg,-lw/2,-lh/2,lw,lh); ctx.restore(); rLogo(); };
                        lImg.onerror=()=>rLogo(); lImg.src=d.src;
                    });
                }
            }
            resolve(canvas.toDataURL('image/png'));
        };
        bg.onerror=()=>resolve(bgDataUrl); bg.src=bgDataUrl;
    });
}

// دالة جديدة لرسم النص على الكانفاس
async function drawTextOnCanvas(ctx, d, size) {
    // الحصول على العنصر الفعلي في DOM
    const el = logosOverlay.querySelector(`.logo-on-hoodie[data-id="${d.id}"]`);
    
    if (!el) {
        // إذا لم نجد العنصر، نستخدم الحساب القديم
        const fontPx = (d.fontSizeCqw / 100) * size;
        const fontFamily = d.font || "'Cairo', sans-serif";
        try {
            await document.fonts.load(`${fontPx}px ${fontFamily}`);
        } catch (e) {}
        
        const lx = (d.xPercent / 100) * size;
        const ly = (d.yPercent / 100) * size;
        
        ctx.font = `${fontPx}px ${fontFamily}`;
        const textMetrics = ctx.measureText(d.text);
        const textWidth = textMetrics.width;
        const textHeight = fontPx;
        
        const cx = lx + textWidth / 2;
        const cy = ly + textHeight / 2;
        
        ctx.save();
        ctx.translate(cx, cy);
        ctx.rotate((d.rotation || 0) * Math.PI / 180);
        ctx.font = `${fontPx}px ${fontFamily}`;
        ctx.fillStyle = d.color || '#ffffff';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.direction = 'rtl';
        ctx.fillText(d.text, 0, 0);
        ctx.restore();
        return;
    }
    
    // استخدام العرض والارتفاع الفعلي من DOM
    const elRect = el.getBoundingClientRect();
    const containerRect = hoodieWrapper.getBoundingClientRect();
    
    // حساب نسبة التكبير
    const scale = size / containerRect.width;
    
    // حجم الخط الفعلي في DOM
    const span = el.querySelector('span');
    const computedStyle = window.getComputedStyle(span);
    const realFontSize = parseFloat(computedStyle.fontSize);
    
    // حجم الخط في الكانفاس
    const fontPx = realFontSize * scale;
    
    const fontFamily = d.font || "'Cairo', sans-serif";
    try {
        await document.fonts.load(`${fontPx}px ${fontFamily}`);
    } catch (e) {}
    
    // Site in DOM
    const lx = (d.xPercent / 100) * size;
    const ly = (d.yPercent / 100) * size;
    
    // Width and height in canvas
    const lw = (elRect.width / containerRect.width) * size;
    const lh = (elRect.height / containerRect.height) * size;
    
    // Center
    const cx = lx + lw / 2;
    const cy = ly + lh / 2;
    
    ctx.save();
    ctx.translate(cx, cy);
    ctx.rotate((d.rotation || 0) * Math.PI / 180);
    ctx.font = `${fontPx}px ${fontFamily}`;
    ctx.fillStyle = d.color || '#ffffff';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.direction = 'rtl';

    // Wrap text if needed
    const words = d.text.split(' ');
    const lines = [];
    let currentLine = words[0] || '';

    for (let i = 1; i < words.length; i++) {
        let word = words[i];
        let width = ctx.measureText(currentLine + " " + word).width;
        // if d.isFixedWidth is false, it shouldn't wrap (unless explicit newlines but we handle words here)
        // wait, the DOM wraps based on lw
        if (d.isFixedWidth && width > lw) {
            lines.push(currentLine);
            currentLine = word;
        } else {
            currentLine += " " + word;
        }
    }
    if (currentLine) lines.push(currentLine);

    const lineHeight = fontPx * 1.2;
    const totalHeight = lines.length * lineHeight;
    const startY = - (totalHeight / 2) + (lineHeight / 2);

    for (let i = 0; i < lines.length; i++) {
        ctx.fillText(lines[i], 0, startY + (i * lineHeight));
    }
    
    ctx.restore();
}

function blobToDataUrl(blob) {
    return new Promise((resolve,reject)=>{ const reader=new FileReader(); reader.onload=e=>resolve(e.target.result); reader.onerror=reject; reader.readAsDataURL(blob); });
}

/* ════ ORDER ════ */
function openOrderModal() {
    const all=Object.values(logosByView).flat();
    // if(!all.length){showToast('من فضلك ضيف لوجو الأول!');return;}
    document.getElementById('orderModal').classList.add('open');
}

function updateShippingDisplay() {
    const sel = document.getElementById('orderGovernorate');
    const row = document.getElementById('shippingPriceRow');
    const valEl = document.getElementById('shippingPriceVal');
    if (sel.value) {
        const price = sel.options[sel.selectedIndex].dataset.price || '0';
        valEl.textContent = price;
        row.style.display = 'flex';
    } else {
        row.style.display = 'none';
    }
}

async function validatePromoCode() {
    const code = document.getElementById('orderPromoCode').value.trim();
    const messageDiv = document.getElementById('promoCodeMessage');
    
    if (!code) {
        messageDiv.innerHTML = '';
        return;
    }
    
    try {
        const res = await fetch('/promo-codes/validate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: JSON.stringify({ code })
        });
        
        const data = await res.json();
        
        if (data.valid) {
            messageDiv.innerHTML = `<span style="color: green;">✓ ${data.message}`;
            if (data.discount_percent > 0) {
                messageDiv.innerHTML += ` - خصم ${data.discount_percent}%`;
            }
            if (data.discount_fixed > 0) {
                messageDiv.innerHTML += ` - خصم ${data.discount_fixed}`;
            }
            messageDiv.innerHTML += '</span>';
        } else {
            messageDiv.innerHTML = `<span style="color: red;">✗ ${data.message}</span>`;
        }
    } catch (e) {
        console.error('Error validating promo code:', e);
        messageDiv.innerHTML = `<span style="color: red;">حدث خطأ في التحقق من الكود</span>`;
    }
}

async function submitOrder() {
    const name=document.getElementById('orderName').value.trim();
    const phone=document.getElementById('orderPhone').value.trim();
    const address=document.getElementById('orderAddress').value.trim();
    const size=document.getElementById('orderSize').value || currentSize;
    const governorateId=document.getElementById('orderGovernorate').value;
    const promoCode=document.getElementById('orderPromoCode').value.trim();
    if(!name||!phone||!address||!size){showToast('من فضلك املأ كل الحقول وتأكد من اختيار المقاس');return;}
    if(!governorateId){showToast('من فضلك اختر المحافظة');return;}
    const btn=document.getElementById('submitOrderBtn');
    document.getElementById('submitBtnText').style.display='none';
    document.getElementById('submitBtnLoader').style.display='';
    btn.disabled=true;
    const logosData=Object.values(logosByView).flat().map(l=>{
        const baseData = {
            view:l.view,
            x_percent:parseFloat(parseFloat(l.xPercent || 0).toFixed(2)),
            y_percent:parseFloat(parseFloat(l.yPercent || 0).toFixed(2)),
            width_percent:l.widthPercent === 'auto' ? 'auto' : parseFloat(parseFloat(l.widthPercent || 0).toFixed(2)),
            height_percent:l.heightPercent === 'auto' ? 'auto' : parseFloat(parseFloat(l.heightPercent || 0).toFixed(2)),
            rotation:l.rotation||0,
            centerXPercent:parseFloat(parseFloat(l.centerXPercent || 0).toFixed(2)),
            centerYPercent:parseFloat(parseFloat(l.centerYPercent || 0).toFixed(2))
        };
        
        if (l.type === 'text') {
            baseData.type = 'text';
            baseData.text = l.text || '';
            baseData.rawText = l.rawText || '';
            baseData.color = l.color || '#ffffff';
            baseData.font = l.font || "'Cairo', sans-serif";
            baseData.hasTashkeel = l.hasTashkeel || false;
            baseData.fontSizeCqw = l.fontSizeCqw || 5;
            baseData.isFixedWidth = l.isFixedWidth || false;
        } else {
            baseData.type = 'logo';
            baseData.src = l.src;
        }
        
        return baseData;
    });
    try {
        console.log('logosData before sending:', logosData);
        const payload = {
            name, phone, address, size,
            governorate_id: governorateId,
            notes: document.getElementById('orderNotes').value,
            product: 'hoodie',
            color: currentColor,
            logos: logosData,
            promo_code: promoCode
        };
        console.log('Full payload:', payload);
        
        let jsonPayload;
        try {
            jsonPayload = JSON.stringify(payload);
            console.log('JSON payload length:', jsonPayload.length);
        } catch(jsonError) {
            console.error('JSON stringify error:', jsonError);
            showToast('حدث خطأ في تجهيز البيانات');
            btn.disabled=false;
            document.getElementById('submitBtnText').style.display='';
            document.getElementById('submitBtnLoader').style.display='none';
            return;
        }
        
        const res=await fetch('/orders',{
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'Accept':'application/json',
                'X-CSRF-TOKEN':CSRF_TOKEN
            },
            body: jsonPayload
        });
        
        console.log('Response status:', res.status);
        console.log('Response ok:', res.ok);
        
        let data;
        try {
            data = await res.json();
            console.log('Response data:', data);
        } catch(parseError) {
            console.error('JSON parse error:', parseError);
            console.log('Response text:', await res.text());
            showToast('حدث خطأ في قراءة الرد');
            btn.disabled=false;
            document.getElementById('submitBtnText').style.display='';
            document.getElementById('submitBtnLoader').style.display='none';
            return;
        }
        
        if(data.success){
            document.getElementById('orderModalBody').innerHTML=`<div class="success-msg"><span class="success-icon">✦</span><h4>تم إرسال <em>طلبك</em></h4><p>رقم الطلب: <strong>#${data.order_id||'—'}</strong></p><p style="margin-top:6px;">هنتواصل معاك على ${phone} قريباً</p></div>`;
            document.getElementById('orderModalFooter').innerHTML=`<button class="btn-submit" onclick="closeModal('orderModal')" style="flex:1">حسناً ✓</button>`;
        } else {
            console.error('Server returned error:', data);
            showToast(data.message||'حدث خطأ من السيرفر'); 
            btn.disabled=false;
            document.getElementById('submitBtnText').style.display=''; 
            document.getElementById('submitBtnLoader').style.display='none';
        }
    } catch(e) {
        console.error('Network error:', e);
        showToast('حدث خطأ في الاتصال: '+e.message); 
        btn.disabled=false;
        document.getElementById('submitBtnText').style.display=''; 
        document.getElementById('submitBtnLoader').style.display='none';
    }
}

function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(m=>{ m.addEventListener('click',e=>{ if(e.target===m) m.classList.remove('open'); }); });

function toggleTheme() {
    const html = document.documentElement;
    const isDark = html.getAttribute('data-theme') === 'dark';
    const btn = document.getElementById('themeToggleBtn');
    if (isDark) {
        html.removeAttribute('data-theme');
        localStorage.setItem('theme', 'light');
        if (btn) btn.innerHTML = '🌙 داكن';
    } else {
        html.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
        if (btn) btn.innerHTML = '☀️ رصاصي';
    }
}
document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('theme') === 'dark') {
        const btn = document.getElementById('themeToggleBtn');
        if (btn) btn.innerHTML = '☀️ رصاصي';
    }
});
</script>
</body>
</html>