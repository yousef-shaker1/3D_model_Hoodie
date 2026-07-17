@extends('layouts.the-index')

@section('title')
    إعدادات التسعير
@endsection

@section('css')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root {
    --pr-bg:     #f0f2f8;
    --pr-card:   #ffffff;
    --pr-border: #e8ecf4;
    --pr-text:   #1a1f36;
    --pr-muted:  #8992a8;
    --pr-accent: #5b5ef4;
    --pr-green:  #0ec9a0;
    --pr-shadow: 0 2px 12px rgba(0,0,0,.06);
    --pr-shadow-lg: 0 8px 32px rgba(0,0,0,.10);
}
* { box-sizing: border-box; }
#main { background: var(--pr-bg) !important; font-family: 'Cairo', sans-serif; color: var(--pr-text); }

.pr-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; }
.pr-header h1 { font-size:22px; font-weight:900; margin:0; display:flex; align-items:center; gap:10px; }
.pr-badge { background:linear-gradient(135deg,#5b5ef4,#8b5cf6); color:#fff; font-size:11px; font-weight:700; padding:4px 12px; border-radius:20px; }

.pr-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
@media(max-width:900px){ .pr-grid{ grid-template-columns:1fr; } }

.pr-card { background:var(--pr-card); border:1px solid var(--pr-border); border-radius:18px; box-shadow:var(--pr-shadow); overflow:hidden; }
.pr-card-head { padding:16px 22px; border-bottom:1px solid var(--pr-border); display:flex; align-items:center; gap:10px; }
.pr-card-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:17px; }
.pr-card-icon.blue  { background:rgba(91,94,244,.12); }
.pr-card-icon.green { background:rgba(14,201,160,.12); }
.pr-card-icon.gold  { background:rgba(245,166,35,.12); }
.pr-card-title { font-size:15px; font-weight:800; }
.pr-card-body { padding:22px; }

.field-group { margin-bottom:20px; }
.field-group label { display:block; font-size:12px; font-weight:700; color:var(--pr-muted); margin-bottom:7px; text-transform:uppercase; letter-spacing:.5px; }
.field-hint { font-size:11px; color:var(--pr-muted); margin-top:5px; line-height:1.5; text-transform:none; }

.input-addon { display:flex; align-items:center; border:1.5px solid var(--pr-border); border-radius:10px; overflow:hidden; transition:border-color .2s; }
.input-addon:focus-within { border-color:var(--pr-accent); }
.input-addon input { flex:1; border:none; outline:none; padding:11px 14px; font-family:'Cairo',sans-serif; font-size:16px; font-weight:700; color:var(--pr-text); background:transparent; }
.input-addon .unit { padding:11px 14px; background:var(--pr-bg); font-size:12px; font-weight:600; color:var(--pr-muted); white-space:nowrap; border-inline-start:1.5px solid var(--pr-border); }

/* ── Formula box ── */
.formula-box {
    background: linear-gradient(135deg, #1a1f36, #252d4a);
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 20px;
    color: #fff;
}
.formula-title { font-size:12px; font-weight:700; color:rgba(255,255,255,.5); text-transform:uppercase; letter-spacing:.5px; margin-bottom:14px; }
.formula-eq {
    font-size:13px;
    line-height:2;
    color:rgba(255,255,255,.85);
    font-weight:600;
}
.formula-eq .var { color:#a5b4fc; font-weight:800; }
.formula-eq .result { color:#e6c98a; font-size:15px; }

/* ── Calculator ── */
.calc-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px; }
.calc-input-wrap label { display:block; font-size:11px; font-weight:700; color:var(--pr-muted); margin-bottom:5px; text-transform:uppercase; letter-spacing:.5px; }
.calc-input { width:100%; border:1.5px solid var(--pr-border); border-radius:10px; padding:10px 14px; font-family:'Cairo',sans-serif; font-size:15px; font-weight:700; color:var(--pr-text); outline:none; transition:border-color .2s; background:transparent; }
.calc-input:focus { border-color:var(--pr-accent); }

.calc-result {
    background: linear-gradient(135deg, rgba(91,94,244,.08), rgba(139,92,246,.06));
    border: 1px solid rgba(91,94,244,.2);
    border-radius: 12px;
    padding: 16px;
}
.calc-result-row { display:flex; justify-content:space-between; align-items:center; padding:6px 0; border-bottom:1px solid rgba(91,94,244,.1); font-size:13px; }
.calc-result-row:last-child { border-bottom:none; padding-bottom:0; }
.calc-result-label { color:var(--pr-muted); }
.calc-result-val { font-weight:700; color:var(--pr-text); }
.calc-result-val.highlight { font-size:18px; font-weight:900; color:var(--pr-accent); }
.calc-result-val.cost { color:#e67e22; }

/* ── Save button ── */
.btn-save { display:inline-flex; align-items:center; gap:8px; padding:12px 28px; background:linear-gradient(135deg,#5b5ef4,#8b5cf6); color:#fff; border:none; border-radius:12px; font-family:'Cairo',sans-serif; font-size:15px; font-weight:700; cursor:pointer; transition:opacity .2s,transform .15s; box-shadow:0 4px 16px rgba(91,94,244,.35); }
.btn-save:hover { opacity:.9; transform:translateY(-1px); }

.alert-success-pr { background:rgba(14,201,160,.12); border:1px solid rgba(14,201,160,.3); color:#0a7d63; border-radius:12px; padding:12px 18px; font-size:13px; font-weight:600; margin-bottom:20px; display:flex; align-items:center; gap:8px; }

/* ── Info box ── */
.info-box { background:rgba(59,130,246,.06); border:1px solid rgba(59,130,246,.2); border-radius:12px; padding:14px 16px; font-size:12px; color:#1e40af; line-height:1.8; margin-bottom:16px; }
.info-box strong { color:#1e3a8a; }
</style>
@endsection

@section('content')
<main id="main" class="main">

    @if(session('success'))
        <div class="alert-success-pr">✓ {{ session('success') }}</div>
    @endif

    <div class="pr-header">
        <h1>⚙️ إعدادات التسعير <span class="pr-badge">DTF Formula</span></h1>
    </div>

    <form method="POST" action="{{ route('pricing.update') }}" id="pricingForm">
        @csrf
        <div class="pr-grid">

            {{-- ── عمود الإعدادات ── --}}
            <div style="display:flex; flex-direction:column; gap:20px;">

                {{-- سعر التيشيرت --}}
                <div class="pr-card">
                    <div class="pr-card-head">
                        <div class="pr-card-icon blue">👕</div>
                        <div class="pr-card-title">سعر التيشيرت الأساسي</div>
                    </div>
                    <div class="pr-card-body">
                        <div class="field-group" style="margin-bottom:0;">
                            <label>سعر البيزك تيشيرت</label>
                            <div class="input-addon">
                                <input type="number" name="tshirt_base_price" id="f_base"
                                       value="{{ $settings['tshirt_base_price'] }}"
                                       min="0" step="any" required oninput="recalc()">
                                <span class="unit">ج.م</span>
                            </div>
                            <div class="field-hint">سعر التيشيرت قبل إضافة أي طباعة</div>
                        </div>
                    </div>
                </div>

                {{-- إعدادات DTF --}}
                <div class="pr-card">
                    <div class="pr-card-head">
                        <div class="pr-card-icon green">🖨️</div>
                        <div class="pr-card-title">إعدادات DTF من المورد</div>
                    </div>
                    <div class="pr-card-body">

                        <div class="info-box">
                            <strong>كيف تعمل المعادلة؟</strong><br>
                            سعر م² = (سعر متر DTF) ÷ (عرض الفريم بالمتر)<br>
                            تكلفتك = مساحة التصميم م² × سعر م²<br>
                            سعر للعميل = تكلفة × (1 + هامش الربح%) ← أقل سعر: حد أدنى
                        </div>

                        <div class="field-group">
                            <label>سعر متر DTF من المورد</label>
                            <div class="input-addon">
                                <input type="number" name="dtf_price_per_meter" id="f_dtf"
                                       value="{{ $settings['dtf_price_per_meter'] }}"
                                       min="1" step="any"  oninput="recalc()">
                                <span class="unit">ج.م / متر</span>
                            </div>
                            <div class="field-hint">ما بتدفعه للمورد لكل متر طولي من الفيلم</div>
                        </div>

                        <div class="field-group">
                            <label>عرض الفريم / الرول</label>
                            <div class="input-addon">
                                <input type="number" name="frame_width_cm" id="f_frame"
                                       value="{{ $settings['frame_width_cm'] }}"
                                       min="10" max="200" step="any" required oninput="recalc()">
                                <span class="unit">سم</span>
                            </div>
                            <div class="field-hint">عرض رول DTF عندك (غالباً 59 أو 60 سم)</div>
                        </div>

                        <div class="field-group">
                            <label>هامش الربح</label>
                            <div class="input-addon">
                                <input type="number" name="profit_margin_percent" id="f_margin"
                                       value="{{ $settings['profit_margin_percent'] }}"
                                       min="0" max="500" step="any" required oninput="recalc()">
                                <span class="unit">%</span>
                            </div>
                            <div class="field-hint">هامش الربح فوق تكلفة الطباعة (مثلاً 80% = الكلفة × 1.8)</div>
                        </div>

                        <div class="field-group" style="margin-bottom:0;">
                            <label>أقل سعر طباعة</label>
                            <div class="input-addon">
                                <input type="number" name="min_print_price" id="f_min"
                                       value="{{ $settings['min_print_price'] }}"
                                       min="0" step="any" required oninput="recalc()">
                                <span class="unit">ج.م</span>
                            </div>
                            <div class="field-hint">حتى لو التصميم صغير جداً، ما يقلش عن هذا السعر</div>
                        </div>

                    </div>
                </div>

                {{-- أبعاد منطقة الطباعة --}}
                <div class="pr-card">
                    <div class="pr-card-head">
                        <div class="pr-card-icon gold">📐</div>
                        <div class="pr-card-title">منطقة الطباعة على التيشيرت</div>
                    </div>
                    <div class="pr-card-body" style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                        <div class="field-group" style="margin:0;">
                            <label>عرض المنطقة</label>
                            <div class="input-addon">
                                <input type="number" name="print_area_width_cm" id="f_pw"
                                       value="{{ $settings['print_area_width_cm'] }}"
                                       min="1" max="100" step="any" required oninput="recalc()">
                                <span class="unit">سم</span>
                            </div>
                        </div>
                        <div class="field-group" style="margin:0;">
                            <label>ارتفاع المنطقة</label>
                            <div class="input-addon">
                                <input type="number" name="print_area_height_cm" id="f_ph"
                                       value="{{ $settings['print_area_height_cm'] }}"
                                       min="1" max="100" step="any" required oninput="recalc()">
                                <span class="unit">سم</span>
                            </div>
                        </div>
                        <div style="grid-column:1/-1; font-size:11px; color:var(--pr-muted);">
                            هذه أبعاد المنطقة القابلة للطباعة على التيشيرت. تُستخدم لتحويل النسبة المئوية للتصميم إلى سنتيمترات حقيقية.
                        </div>
                    </div>
                </div>

                {{-- الحدود القصوى --}}
                <div class="pr-card">
                    <div class="pr-card-head">
                        <div class="pr-card-icon gold">📏</div>
                        <div class="pr-card-title">الحدود القصوى للتصميم</div>
                    </div>
                    <div class="pr-card-body">
                        <div style="font-size:12px; font-weight:bold; color:var(--pr-accent); margin-bottom:10px;">الحدود القصوى للصور (اللوجوهات)</div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom: 20px;">
                            <div class="field-group" style="margin:0;">
                                <label>أقصى عرض للصور</label>
                                <div class="input-addon">
                                    <input type="number" name="max_width_cm" id="f_max_width"
                                           value="{{ $settings['max_width_cm'] }}"
                                           min="1" max="100" step="any" required>
                                    <span class="unit">سم</span>
                                </div>
                            </div>
                            <div class="field-group" style="margin:0;">
                                <label>أقصى ارتفاع للصور</label>
                                <div class="input-addon">
                                    <input type="number" name="max_height_cm" id="f_max_height"
                                           value="{{ $settings['max_height_cm'] }}"
                                           min="1" max="100" step="any" required>
                                    <span class="unit">سم</span>
                                </div>
                            </div>
                        </div>

                        <div style="font-size:12px; font-weight:bold; color:var(--pr-accent); margin-bottom:10px;">الحدود القصوى للكتابة (النصوص)</div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                            <div class="field-group" style="margin:0;">
                                <label>أقصى عرض للكتابة</label>
                                <div class="input-addon">
                                    <input type="number" name="max_text_width_cm" id="f_max_text_width"
                                           value="{{ $settings['max_text_width_cm'] }}"
                                           min="1" max="100" step="any" required>
                                    <span class="unit">سم</span>
                                </div>
                            </div>
                            <div class="field-group" style="margin:0;">
                                <label>أقصى ارتفاع للكتابة</label>
                                <div class="input-addon">
                                    <input type="number" name="max_text_height_cm" id="f_max_text_height"
                                           value="{{ $settings['max_text_height_cm'] ?? 10.0 }}"
                                           min="1" max="100" step="any" required>
                                    <span class="unit">سم</span>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top:14px; font-size:11px; color:var(--pr-muted);">
                            هذه الحدود تُستخدم للتحقق من أبعاد التصميم وإظهار تحذيرات للعميل عند تجاوزها.
                        </div>
                    </div>
                </div>

                <div style="padding-top:4px;">
                    <button type="submit" class="btn-save">💾 حفظ الإعدادات</button>
                </div>
            </div>

            {{-- ── عمود الآلة الحاسبة ── --}}
            <div>
                <div class="pr-card" style="position:sticky; top:20px;">
                    <div class="pr-card-head">
                        <div class="pr-card-icon blue">🧮</div>
                        <div class="pr-card-title">آلة حاسبة تفاعلية</div>
                    </div>
                    <div class="pr-card-body">

                        <p style="font-size:12px;color:var(--pr-muted);margin:0 0 16px;line-height:1.7;">
                            جرّب أي حجم تصميم واشوف السعر اللي هيظهر للعميل بناءً على الإعدادات الحالية.
                        </p>

                        <div class="calc-grid">
                            <div class="calc-input-wrap">
                                <label>عرض التصميم (سم)</label>
                                <input type="number" class="calc-input" id="c_w" value="18.5" min="0.5" step="any" oninput="recalc()">
                            </div>
                            <div class="calc-input-wrap">
                                <label>ارتفاع التصميم (سم)</label>
                                <input type="number" class="calc-input" id="c_h" value="24.3" min="0.5" step="any" oninput="recalc()">
                            </div>
                        </div>

                        <div class="calc-result" id="calcResult">
                            <div class="calc-result-row">
                                <span class="calc-result-label">📐 مساحة التصميم</span>
                                <span class="calc-result-val" id="r_area">—</span>
                            </div>
                            <div class="calc-result-row">
                                <span class="calc-result-label">💵 سعر م² DTF</span>
                                <span class="calc-result-val" id="r_sqm">—</span>
                            </div>
                            <div class="calc-result-row">
                                <span class="calc-result-label">🏭 تكلفتك الفعلية</span>
                                <span class="calc-result-val cost" id="r_cost">—</span>
                            </div>
                            <div class="calc-result-row">
                                <span class="calc-result-label">💰 سعر الطباعة للعميل</span>
                                <span class="calc-result-val highlight" id="r_print">—</span>
                            </div>
                            <div class="calc-result-row">
                                <span class="calc-result-label">📦 إجمالي (تيشيرت + طباعة)</span>
                                <span class="calc-result-val highlight" id="r_total">—</span>
                            </div>
                        </div>

                        <div class="formula-box" style="margin-top:16px;">
                            <div class="formula-title">📊 المعادلة المستخدمة (بالمتر الطولي)</div>
                            <div class="formula-eq">
                                تكلفة الطباعة = (ارتفاع التصميم بالمتر) × سعر المتر الطولي<br>
                                تكلفة = <span class="var" id="fq_height_m">—</span> م × <span class="var" id="fq_dtf">—</span> ج.م<br>
                                = <span class="var" id="fq_rawcost">—</span> ج.م<br><br>
                                سعر بيع = max(<span class="var" id="fq_min">—</span>, <span class="var" id="fq_rawcost2">—</span> × <span class="var" id="fq_mult">—</span>)<br>
                                = <span class="result" id="fq_result">—</span> ج.م ← مقرّب لأقرب 5
                            </div>
                        </div>

                        {{-- جدول أمثلة سريعة --}}
                        <div style="margin-top:16px;">
                            <div style="font-size:12px;font-weight:700;color:var(--pr-muted);margin-bottom:10px;text-transform:uppercase;letter-spacing:.5px;">
                                📋 أمثلة سريعة
                            </div>
                            <table style="width:100%;border-collapse:collapse;font-size:12px;" id="examplesTable">
                                <thead>
                                    <tr style="background:var(--pr-bg);">
                                        <th style="text-align:right;padding:8px 10px;font-size:11px;color:var(--pr-muted);">الحجم</th>
                                        <th style="text-align:right;padding:8px 10px;font-size:11px;color:var(--pr-muted);">المساحة</th>
                                        <th style="text-align:right;padding:8px 10px;font-size:11px;color:var(--pr-muted);">سعر الطباعة</th>
                                        <th style="text-align:right;padding:8px 10px;font-size:11px;color:var(--pr-muted);">الإجمالي</th>
                                    </tr>
                                </thead>
                                <tbody id="examplesBody"></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </form>

</main>
@endsection

@section('js')
<script>
const EXAMPLES = [
    { label: 'لوجو جيب (5×5 سم)',   w: 5,    h: 5    },
    { label: 'صدر (10×10 سم)', w: 10,   h: 10   },
    { label: 'A5 (15×21 سم)', w: 15,   h: 21   },
    { label: 'A4 (21×30 سم)', w: 21,   h: 30   },
    { label: 'A3 (30×42 سم)', w: 30,   h: 42   },
];

function getParams() {
    return {
        base:   parseFloat(document.getElementById('f_base').value)   || 100,
        dtf:    parseFloat(document.getElementById('f_dtf').value)    || 100,
        frame:  parseFloat(document.getElementById('f_frame').value)  || 59,
        margin: parseFloat(document.getElementById('f_margin').value) || 80,
        minP:   parseFloat(document.getElementById('f_min').value)    || 25,
    };
}

function calcPrint(w, h, p) {
    const rawCost   = (h / 100) * p.dtf;
    const sell      = rawCost * (1 + p.margin / 100);
    const final     = Math.ceil(Math.max(p.minP, sell) / 5) * 5;
    return { rawCost, final };
}

function recalc() {
    const p  = getParams();
    const w  = parseFloat(document.getElementById('c_w').value) || 10;
    const h  = parseFloat(document.getElementById('c_h').value) || 10;
    const r  = calcPrint(w, h, p);

    // results
    document.getElementById('r_area').textContent  = (w * h).toFixed(1) + ' سم²';
    const r_sqm = document.getElementById('r_sqm');
    if(r_sqm) r_sqm.textContent = 'يُحسب بالطول';
    document.getElementById('r_cost').textContent  = r.rawCost.toFixed(2) + ' ج.م';
    document.getElementById('r_print').textContent = r.final + ' ج.م';
    document.getElementById('r_total').textContent = (r.final + p.base) + ' ج.م';

    // formula breakdown
    const mult = (1 + p.margin / 100).toFixed(2);
    document.getElementById('fq_height_m').textContent = (h / 100).toFixed(2);
    document.getElementById('fq_dtf').textContent      = p.dtf;
    document.getElementById('fq_rawcost').textContent  = r.rawCost.toFixed(2);
    document.getElementById('fq_min').textContent      = p.minP;
    document.getElementById('fq_rawcost2').textContent = r.rawCost.toFixed(2);
    document.getElementById('fq_mult').textContent     = mult;
    document.getElementById('fq_result').textContent   = r.final;

    // examples table
    const tbody = document.getElementById('examplesBody');
    tbody.innerHTML = '';
    EXAMPLES.forEach(ex => {
        const er = calcPrint(ex.w, ex.h, p);
        const tr = document.createElement('tr');
        tr.style.borderBottom = '1px solid var(--pr-border)';
        tr.innerHTML = `
            <td style="padding:8px 10px;font-weight:700;">${ex.label}</td>
            <td style="padding:8px 10px;color:var(--pr-muted);">${(ex.w*ex.h).toFixed(0)} سم²</td>
            <td style="padding:8px 10px;color:#5b5ef4;font-weight:800;">${er.final} ج.م</td>
            <td style="padding:8px 10px;font-weight:900;">${er.final + p.base} ج.م</td>
        `;
        tbody.appendChild(tr);
    });
}

document.addEventListener('DOMContentLoaded', recalc);
</script>
@endsection
