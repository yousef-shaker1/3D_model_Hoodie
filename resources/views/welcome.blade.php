<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DRAPE — مصمم 3D</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink:        #0a0a0a;
            --ink-soft:   #111111;
            --surface:    #161616;
            --border:     rgba(255,255,255,0.07);
            --gold:       #c9a84c;
            --gold-soft:  rgba(201,168,76,0.15);
            --gold-glow:  rgba(201,168,76,0.35);
            --white:      #f5f5f0;
            --muted:      rgba(245,245,240,0.4);
            --danger:     #e05252;
            --hoodie-bg:  #e8e4dc; /* ← لون خلفية الهودي */
            --font-display: 'Bebas Neue', sans-serif;
            --font-body:    'Cairo', sans-serif;
            --sidebar-w:  300px;
            --topbar-h:   52px;
            --bottombar-h:60px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; background: var(--ink); color: var(--white); font-family: var(--font-body); overflow: hidden; }

        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");
            background-size: 180px; pointer-events: none; z-index: 9999; opacity: 0.6;
        }

        /* ════ LOADING ════ */
        #loadingScreen {
            position: fixed; inset: 0; background: var(--ink);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            z-index: 99998; transition: opacity 0.8s ease, visibility 0.8s ease; gap: 28px;
        }
        #loadingScreen.hidden { opacity: 0; visibility: hidden; pointer-events: none; }
        .ls-brand { font-family: var(--font-display); font-size: clamp(48px,10vw,88px); letter-spacing: 0.15em; color: var(--white); line-height: 1; }
        .ls-brand span { color: var(--gold); }
        .ls-tagline { font-size: 11px; letter-spacing: 0.4em; color: var(--muted); text-transform: uppercase; }
        .ls-bar-wrap { width: 200px; height: 1px; background: var(--border); overflow: hidden; }
        .ls-bar { height: 100%; width: 0%; background: var(--gold); transition: width 0.3s ease; }
        .ls-pct { font-family: var(--font-display); font-size: 13px; letter-spacing: 0.2em; color: var(--gold); }

        /* ════ APP SHELL ════ */
        .app { display: flex; height: 100vh; width: 100vw; overflow: hidden; }

        /* ════ SIDEBAR ════ */
        .sidebar {
            width: var(--sidebar-w); flex-shrink: 0;
            background: var(--ink-soft); border-left: 1px solid var(--border);
            display: flex; flex-direction: column; overflow: hidden;
            position: relative; z-index: 10;
        }
        .sb-header {
            padding: 20px 22px 16px; border-bottom: 1px solid var(--border);
            flex-shrink: 0; position: relative;
        }
        .sb-brand { font-family: var(--font-display); font-size: 24px; letter-spacing: 0.12em; color: var(--white); margin-bottom: 2px; }
        .sb-brand span { color: var(--gold); }
        .sb-subtitle { font-size: 9px; letter-spacing: 0.3em; color: var(--muted); text-transform: uppercase; }
        .sb-product-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--gold-soft); border: 1px solid rgba(201,168,76,0.3);
            border-radius: 20px; padding: 4px 12px; margin-top: 10px;
            font-size: 11px; color: var(--gold); letter-spacing: 0.1em;
        }

        .sb-body { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 14px 18px; scrollbar-width: thin; scrollbar-color: var(--border) transparent; }
        .sb-body::-webkit-scrollbar { width: 3px; }
        .sb-body::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

        .sb-label { font-size: 9px; letter-spacing: 0.35em; color: var(--muted); text-transform: uppercase; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
        .sb-label::after { content: ''; flex: 1; height: 1px; background: var(--border); }

        /* sections */
        .sections-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 7px; margin-bottom: 14px; }
        .section-item {
            aspect-ratio: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;
            border: 1px solid var(--border); border-radius: 9px; cursor: pointer;
            background: var(--surface); transition: all 0.2s; padding: 5px; position: relative; overflow: hidden;
        }
        .section-item::before { content: ''; position: absolute; inset: 0; background: var(--gold-soft); opacity: 0; transition: opacity 0.2s; }
        .section-item:hover { border-color: rgba(201,168,76,0.4); transform: translateY(-2px); }
        .section-item:hover::before { opacity: 1; }
        .section-item.active { border-color: var(--gold); }
        .section-item.active::before { opacity: 1; }
        .section-item img { width: 100%; height: 100%; object-fit: contain; position: relative; z-index: 1; border-radius: 6px; }
        .section-item-icon { font-size: 20px; position: relative; z-index: 1; }
        .section-item-label { font-size: 7px; color: var(--muted); text-align: center; position: relative; z-index: 1; margin-top: 2px; }

        .logos-panel { margin-bottom: 14px; display: none; }
        .logos-panel.open { display: block; }
        .logos-panel-title { font-size: 10px; letter-spacing: 0.2em; color: var(--gold); text-transform: uppercase; margin-bottom: 8px; }
        .logo-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 6px; }
        .logo-item {
            aspect-ratio: 1; border: 1px solid var(--border); border-radius: 8px; cursor: grab;
            background: var(--surface); padding: 5px; object-fit: contain; transition: all 0.2s;
            filter: brightness(0.9); width: 100%;
        }
        .logo-item:hover { border-color: var(--gold); filter: brightness(1.15); transform: scale(1.06); box-shadow: 0 0 14px var(--gold-glow); }
        .no-logos-msg { color: var(--muted); font-size: 11px; text-align: center; grid-column: 1/-1; padding: 10px 0; }

        /* upload */
        .upload-logo-btn {
            width: 100%; padding: 9px; background: var(--surface); border: 1px dashed rgba(201,168,76,0.3);
            border-radius: 9px; color: var(--gold); font-family: var(--font-body); font-size: 11px;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;
            transition: all 0.2s; margin-bottom: 10px;
        }
        .upload-logo-btn:hover { border-color: var(--gold); background: var(--gold-soft); }

        /* instructions */
        .instructions { border: 1px solid var(--border); border-radius: 10px; padding: 10px 12px; background: var(--surface); }
        .instruction-row { display: flex; align-items: center; gap: 8px; font-size: 11px; color: var(--muted); padding: 3px 0; line-height: 1.4; }
        .instruction-row:not(:last-child) { border-bottom: 1px solid var(--border); }
        .instruction-icon { width: 20px; height: 20px; background: var(--gold-soft); border-radius: 5px; display: flex; align-items: center; justify-content: center; font-size: 10px; flex-shrink: 0; }

        /* ════ MAIN ════ */
        .main {
            flex: 1; display: flex; flex-direction: column;
            background: var(--ink); overflow: hidden;
            /* محتاجين نحسب الارتفاع بدقة */
            height: 100vh;
        }

        /* ════ TOP BAR ════ */
        .top-bar {
            height: var(--topbar-h); flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            padding: 0 16px; border-bottom: 1px solid var(--border);
            gap: 5px; flex-wrap: nowrap; overflow-x: auto;
        }
        .top-bar::-webkit-scrollbar { display: none; }
        .view-btn {
            font-family: var(--font-body); font-size: 11px; font-weight: 600;
            letter-spacing: 0.06em; padding: 6px 12px; border-radius: 8px;
            cursor: pointer; border: 1px solid var(--border); background: transparent;
            color: var(--muted); transition: all 0.2s; white-space: nowrap; flex-shrink: 0;
        }
        .view-btn:hover { border-color: rgba(201,168,76,0.4); color: var(--white); }
        .view-btn.active { background: var(--gold); border-color: var(--gold); color: var(--ink); font-weight: 700; box-shadow: 0 0 18px var(--gold-glow); }
        .divider-dot { width: 3px; height: 3px; border-radius: 50%; background: var(--border); flex-shrink: 0; }
        .sidebar-toggle {
            display: none; width: 34px; height: 34px; background: var(--surface);
            border: 1px solid var(--gold); border-radius: 8px; color: var(--gold);
            font-size: 14px; align-items: center; justify-content: center; cursor: pointer;
            flex-shrink: 0;
        }

        /* ════ CANVAS ════ */
        .canvas-wrap {
            flex: 1; display: flex; align-items: center; justify-content: center;
            padding: 20px; position: relative; overflow: hidden;
            /* الارتفاع = 100vh - topbar - bottombar */
            min-height: 0;
        }
        .canvas-wrap::before, .canvas-wrap::after {
            content: ''; position: absolute; width: 40px; height: 40px;
            border-color: rgba(201,168,76,0.12); border-style: solid; pointer-events: none;
        }
        .canvas-wrap::before { top: 10px; right: 10px; border-width: 1px 1px 0 0; }
        .canvas-wrap::after  { bottom: 10px; left: 10px; border-width: 0 0 1px 1px; }

        .hoodie-container {
            /* يأخذ الحجم الأقصى المتاح مع الحفاظ على نسبة */
            width: min(100%, calc(100vh - var(--topbar-h) - var(--bottombar-h) - 40px));
            height: min(100%, calc(100vh - var(--topbar-h) - var(--bottombar-h) - 40px));
            max-width: 520px; max-height: 520px;
            aspect-ratio: 1;
            position: relative; border-radius: 20px; overflow: hidden;
            border: 1px solid rgba(201,168,76,0.12);
            background: var(--hoodie-bg);
            box-shadow: 0 0 0 1px rgba(201,168,76,0.05), 0 30px 60px rgba(0,0,0,0.5);
            transition: background 0.3s;
        }
        .hoodie-container.grid-view {
            width: min(95vw, 900px); height: 75vh; max-width: none; max-height: none;
            aspect-ratio: unset; overflow-y: auto; background: var(--ink-soft);
        }
        .hoodie-grid { display: none; grid-template-columns: repeat(2,1fr); gap: 12px; padding: 12px; }
        .hoodie-container.grid-view .hoodie-grid { display: grid; }
        .hoodie-container.grid-view .hoodie-wrapper { display: none; }

        .grid-item {
            position: relative; background: var(--hoodie-bg); border-radius: 10px;
            overflow: hidden; border: 1px solid var(--border);
            width: 100%; height: 0; padding-bottom: 100%; transition: background 0.3s;
        }
        .grid-item-content { position: absolute; inset: 0; }
        .grid-item-label {
            position: absolute; top: 8px; left: 50%; transform: translateX(-50%);
            background: var(--gold); color: var(--ink); padding: 3px 10px;
            border-radius: 20px; font-size: 10px; font-weight: 700; z-index: 100;
        }
        .grid-item-content model-viewer { width: 100%; height: 100%; background-color: transparent; }
        .grid-item-content .logos-overlay { position: absolute; inset: 0; pointer-events: none; z-index: 10; }

        .hoodie-wrapper { width: 100%; height: 100%; position: relative; }
        .hoodie-wrapper.drag-over::after {
            content: 'أفلت هنا'; position: absolute; inset: 0;
            border: 2px dashed var(--gold); border-radius: 18px;
            pointer-events: none; z-index: 100;
            display: flex; align-items: center; justify-content: center;
            background: rgba(201,168,76,0.05); font-size: 18px; font-weight: 700;
            color: var(--gold);
        }

        model-viewer {
            width: 100%; height: 100%; border-radius: 18px;
            background-color: transparent; --poster-color: transparent;
        }
        model-viewer::part(default-progress-bar) { height: 2px; background: var(--gold); }

        .logos-overlay { position: absolute; inset: 0; pointer-events: none; z-index: 10; border-radius: 18px; }
        .color-overlay { position: absolute; inset: 0; border-radius: 18px; pointer-events: none; z-index: 5; mix-blend-mode: multiply; opacity: 0; transition: opacity 0.3s; }
        .color-overlay.active { opacity: 0.45; }

        /* logos on hoodie */
        body.free-control-active .logo-on-hoodie { pointer-events: none !important; }
        body.free-control-active .logo-on-hoodie .delete-btn,
        body.free-control-active .logo-on-hoodie .resize-handle { display: none !important; }
        .logo-on-hoodie { position: absolute; pointer-events: auto; cursor: move; user-select: none; opacity: 0; transition: opacity 0.3s; }
        .logo-on-hoodie.active  { opacity: 1; }
        .logo-on-hoodie.selected { outline: 2px solid var(--gold); outline-offset: 3px; box-shadow: 0 0 16px var(--gold-glow); }
        .logo-on-hoodie img { width: 100%; height: 100%; object-fit: contain; pointer-events: none; filter: drop-shadow(0 2px 8px rgba(0,0,0,0.35)); }
        .logo-on-hoodie .delete-btn {
            position: absolute; top: -10px; right: -10px;
            background: var(--danger); color: #fff; border: 2px solid var(--ink);
            border-radius: 50%; width: 24px; height: 24px; cursor: pointer;
            font-size: 12px; opacity: 0; transition: opacity 0.2s; z-index: 10;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-on-hoodie .resize-handle {
            position: absolute; bottom: -10px; right: -10px;
            width: 22px; height: 22px; background: var(--gold);
            border: 2px solid var(--ink); border-radius: 50%;
            cursor: nwse-resize; opacity: 0; transition: opacity 0.2s; z-index: 10;
        }
        @media (min-width: 769px) {
            .logo-on-hoodie:hover .delete-btn,
            .logo-on-hoodie:hover .resize-handle { opacity: 1; }
        }
        @media (max-width: 768px) {
            .logo-on-hoodie.selected .delete-btn,
            .logo-on-hoodie.selected .resize-handle { opacity: 1; }
        }

        /* ════ BOTTOM BAR ════ */
        .bottom-bar {
            height: var(--bottombar-h); flex-shrink: 0;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 20px; border-top: 1px solid var(--border); gap: 10px;
        }
        .status-hint { font-size: 11px; letter-spacing: 0.1em; color: var(--muted); white-space: nowrap; }
        .status-hint span { color: var(--gold); }
        .bottom-actions { display: flex; gap: 8px; flex-shrink: 0; }
        .save-btn {
            font-family: var(--font-body); font-size: 12px; font-weight: 600;
            padding: 9px 16px; border-radius: 9px; border: 1px solid var(--border);
            background: var(--surface); color: var(--white); cursor: pointer; transition: all 0.2s;
            white-space: nowrap;
        }
        .save-btn:hover { border-color: var(--gold); color: var(--gold); }
        .order-btn {
            font-family: var(--font-body); font-size: 13px; font-weight: 700;
            letter-spacing: 0.08em; padding: 9px 22px; border-radius: 9px; border: none;
            cursor: pointer; background: var(--gold); color: var(--ink); transition: all 0.2s;
            white-space: nowrap;
        }
        .order-btn:hover { box-shadow: 0 0 24px var(--gold-glow); transform: translateY(-1px); }

        /* ════ MOBILE LOGO TOOLBAR ════ */
        .logo-toolbar {
            position: fixed; bottom: calc(var(--bottombar-h) + 10px);
            left: 50%; transform: translateX(-50%);
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 14px; padding: 7px 10px;
            display: none; gap: 6px; z-index: 1000;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
        }
        .logo-toolbar.active { display: flex; }
        body.free-control-active .logo-toolbar { display: none !important; }
        .toolbar-btn {
            background: var(--ink-soft); color: var(--white); border: 1px solid var(--border);
            border-radius: 9px; width: 42px; height: 42px; cursor: pointer; font-size: 17px;
            display: flex; align-items: center; justify-content: center; transition: all 0.2s;
        }
        .toolbar-btn:active { background: var(--gold); color: var(--ink); border-color: var(--gold); }
        .toolbar-btn.danger { color: var(--danger); }
        .toolbar-btn.danger:active { background: var(--danger); color: #fff; }

        .drag-preview { position: fixed; width: 64px; pointer-events: none; z-index: 9999; opacity: 0.8; filter: drop-shadow(0 4px 12px rgba(201,168,76,0.4)); }

        /* ════ MODALS ════ */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.78);
            backdrop-filter: blur(8px); z-index: 10000;
            display: none; align-items: center; justify-content: center; padding: 16px;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: var(--ink-soft); border: 1px solid var(--border);
            border-radius: 20px; width: 100%; max-width: 460px; overflow: hidden;
            animation: modalIn 0.3s cubic-bezier(0.34,1.56,0.64,1);
            box-shadow: 0 40px 80px rgba(0,0,0,0.6);
            max-height: 90vh; display: flex; flex-direction: column;
        }
        @keyframes modalIn { from { opacity:0; transform: scale(0.9) translateY(14px); } to { opacity:1; transform: scale(1) translateY(0); } }
        .modal-header { padding: 18px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
        .modal-title { font-family: var(--font-display); font-size: 22px; letter-spacing: 0.1em; color: var(--white); }
        .modal-title span { color: var(--gold); }
        .modal-close {
            width: 28px; height: 28px; background: var(--surface); border: 1px solid var(--border);
            color: var(--muted); border-radius: 7px; cursor: pointer; font-size: 13px;
            display: flex; align-items: center; justify-content: center; transition: all 0.2s; flex-shrink: 0;
        }
        .modal-close:hover { border-color: var(--danger); color: var(--danger); }
        .modal-body { padding: 18px 20px; overflow-y: auto; flex: 1; }
        .form-group { margin-bottom: 13px; }
        .form-group label { display: block; font-size: 10px; letter-spacing: 0.3em; color: var(--muted); text-transform: uppercase; margin-bottom: 6px; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 10px 13px; background: var(--surface);
            border: 1px solid var(--border); border-radius: 9px;
            font-size: 13px; color: var(--white); font-family: var(--font-body);
            outline: none; transition: border-color 0.2s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-soft); }
        .form-group select option { background: var(--surface); }
        .form-group textarea { resize: none; }
        .modal-footer { padding: 13px 20px; border-top: 1px solid var(--border); display: flex; gap: 10px; flex-shrink: 0; }
        .btn-cancel { flex: 1; padding: 11px; background: var(--surface); border: 1px solid var(--border); border-radius: 9px; color: var(--muted); font-family: var(--font-body); font-size: 12px; cursor: pointer; transition: all 0.2s; }
        .btn-cancel:hover { color: var(--white); border-color: rgba(255,255,255,0.15); }
        .btn-submit { flex: 2; padding: 11px; background: var(--gold); border: none; border-radius: 9px; color: var(--ink); font-family: var(--font-body); font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s; }
        .btn-submit:hover { box-shadow: 0 0 20px var(--gold-glow); }
        .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }

        /* export */
        .export-previews { display: grid; grid-template-columns: repeat(2,1fr); gap: 8px; margin-top: 10px; }
        .export-preview-item { position: relative; border: 1px solid var(--border); border-radius: 10px; overflow: hidden; background: var(--hoodie-bg); }
        .export-preview-item img { width: 100%; display: block; }
        .export-preview-label { position: absolute; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; font-weight: 700; color: var(--ink); background: var(--gold); padding: 3px; letter-spacing: 0.1em; }
        .export-loading { text-align: center; padding: 28px 0; color: var(--gold); font-size: 13px; display: none; }

        .success-msg { text-align: center; padding: 24px 16px; }
        .success-icon { font-size: 44px; margin-bottom: 12px; }
        .success-msg h4 { font-family: var(--font-display); font-size: 24px; letter-spacing: 0.1em; color: var(--white); margin-bottom: 8px; }
        .success-msg p { color: var(--muted); font-size: 13px; }
        .success-msg strong { color: var(--gold); }

        /* sidebar close btn (mobile) */
        .sidebar-close {
            display: none; position: absolute; top: 12px; left: 12px;
            width: 32px; height: 32px; background: var(--gold); border: none;
            color: var(--ink); border-radius: 7px; cursor: pointer; font-size: 14px;
            font-weight: 700; align-items: center; justify-content: center; z-index: 10;
        }

        @keyframes slideIn { from { opacity:0; transform:translateY(5px); } to { opacity:1; transform:translateY(0); } }
        .slide-in { animation: slideIn 0.18s ease forwards; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ════ RESPONSIVE ════ */
        @media (max-width: 900px) {
            :root { --sidebar-w: 260px; }
        }

        @media (max-width: 768px) {
            :root {
                --topbar-h:   46px;
                --bottombar-h:56px;
            }
            html, body { overflow: hidden; }
            .app { flex-direction: column; height: 100vh; overflow: hidden; }

            /* sidebar كـ drawer */
            .sidebar {
                position: fixed; right: 0; top: 0; bottom: 0;
                width: min(300px, 85vw);
                transform: translateX(100%);
                transition: transform 0.3s ease;
                z-index: 2000;
                height: 100vh;
            }
            .sidebar.open { transform: translateX(0); box-shadow: -10px 0 40px rgba(0,0,0,0.5); }
            .sidebar-toggle { display: flex; }
            .sidebar-close { display: flex; }

            .main { height: 100vh; flex: 1; display: flex; flex-direction: column; }

            /* canvas responsive */
            .canvas-wrap { padding: 10px; }
            .hoodie-container {
                width: min(85vw, calc(100vh - var(--topbar-h) - var(--bottombar-h) - 20px));
                height: min(85vw, calc(100vh - var(--topbar-h) - var(--bottombar-h) - 20px));
                max-width: none; max-height: none;
            }
            .hoodie-container.grid-view { width: 100%; height: auto; aspect-ratio: unset; }
            .hoodie-grid { grid-template-columns: 1fr 1fr; gap: 8px; padding: 8px; }

            .top-bar { padding: 0 8px; gap: 3px; overflow-x: auto; }
            .view-btn { padding: 5px 9px; font-size: 10px; }
            .divider-dot { display: none; }

            /* bottom bar mobile */
            .bottom-bar { padding: 0 12px; }
            .status-hint { display: none; }
            .bottom-actions { width: 100%; gap: 8px; }
            .save-btn, .order-btn { flex: 1; text-align: center; padding: 9px 10px; font-size: 12px; }
        }

        @media (max-width: 400px) {
            .view-btn { padding: 4px 7px; font-size: 9px; }
            .hoodie-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- LOADING -->
<div id="loadingScreen">
    <div class="ls-brand">DR<span>A</span>PE</div>
    <div class="ls-tagline">3D Product Designer</div>
    <div class="ls-bar-wrap"><div class="ls-bar" id="lsBar"></div></div>
    <div class="ls-pct" id="lsPct">0%</div>
</div>

<div class="app">
    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="sb-header">
            <button class="sidebar-close" onclick="document.getElementById('sidebar').classList.remove('open')">✕</button>
            <div class="sb-brand">DR<span>A</span>PE</div>
            <div class="sb-subtitle">3D Product Designer</div>
            <div class="sb-product-badge"><span>👕</span> هودي كلاسيك</div>
        </div>

        <div class="sb-body">
            <div class="sb-label">أقسام اللوجوهات</div>
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

            <label class="upload-logo-btn" for="uploadLogoInput">
                <span>📎</span> ارفع لوجو من جهازك
            </label>
            <input type="file" id="uploadLogoInput" accept="image/*" style="display:none;" onchange="handleLogoUpload(this)">

            <div class="logos-panel" id="logosPanel">
                <div class="logos-panel-title" id="selectedSectionName"></div>
                <div class="logo-grid" id="logoGrid"></div>
            </div>

            <div class="sb-label" style="margin-top:8px;">إرشادات</div>
            <div class="instructions">
                <div class="instruction-row"><div class="instruction-icon">🖱️</div><span>اسحب اللوجو على الهودي</span></div>
                <div class="instruction-row"><div class="instruction-icon">📐</div><span>اضغط عليه للتحكم</span></div>
                <div class="instruction-row"><div class="instruction-icon">📸</div><span>صدّر صور التصميم</span></div>
            </div>
        </div>
    </div>

    <!-- MAIN -->
    <div class="main">
        <div class="top-bar">
            <button class="sidebar-toggle" id="sidebarToggle" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
            <div class="divider-dot"></div>
            <button class="view-btn active" data-view="front">الوش</button>
            <div class="divider-dot"></div>
            <button class="view-btn" data-view="back">الظهر</button>
            <div class="divider-dot"></div>
            <button class="view-btn" data-view="left">يسار</button>
            <div class="divider-dot"></div>
            <button class="view-btn" data-view="right">يمين</button>
            <div class="divider-dot"></div>
            <button class="view-btn" id="gridViewBtn">عرض الكل</button>
            <div class="divider-dot"></div>
            <button class="view-btn" id="freeControlBtn">تحكم حر</button>
            <div class="divider-dot"></div>
            <button class="view-btn" id="previewBtn">معاينة</button>
        </div>

        <div class="canvas-wrap">
            <div class="hoodie-container" id="hoodieContainer">
                <div class="hoodie-wrapper" id="hoodieWrapper">
                    <model-viewer
                        id="hoodieModel"
                        src="assets/img/3ds/t_shirt_hoodie_3d_model.glb"
                        alt="3D Hoodie"
                        poster="assets/img/3ds/hoodie_poster.webp"
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

                <div class="hoodie-grid" id="hoodieGrid">
                    <div class="grid-item"><div class="grid-item-content">
                        <div class="grid-item-label">الوش</div>
                        <model-viewer id="gridModelFront" src="assets/img/3ds/t_shirt_hoodie_3d_model.glb" poster="assets/img/3ds/hoodie_poster.webp" camera-orbit="0deg 75deg 105%" min-camera-orbit="0deg 75deg 105%" max-camera-orbit="0deg 75deg 105%" field-of-view="30deg" disable-zoom disable-pan disable-tap interaction-prompt="none" ar-modes=""></model-viewer>
                        <div class="logos-overlay" id="gridOverlayFront"></div>
                    </div></div>
                    <div class="grid-item"><div class="grid-item-content">
                        <div class="grid-item-label">الظهر</div>
                        <model-viewer id="gridModelBack" src="assets/img/3ds/t_shirt_hoodie_3d_model.glb" poster="assets/img/3ds/hoodie_poster.webp" camera-orbit="180deg 75deg 105%" min-camera-orbit="180deg 75deg 105%" max-camera-orbit="180deg 75deg 105%" field-of-view="30deg" disable-zoom disable-pan disable-tap interaction-prompt="none" ar-modes=""></model-viewer>
                        <div class="logos-overlay" id="gridOverlayBack"></div>
                    </div></div>
                    <div class="grid-item"><div class="grid-item-content">
                        <div class="grid-item-label">يسار</div>
                        <model-viewer id="gridModelLeft" src="assets/img/3ds/t_shirt_hoodie_3d_model.glb" poster="assets/img/3ds/hoodie_poster.webp" camera-orbit="90deg 75deg 105%" min-camera-orbit="90deg 75deg 105%" max-camera-orbit="90deg 75deg 105%" field-of-view="30deg" disable-zoom disable-pan disable-tap interaction-prompt="none" ar-modes=""></model-viewer>
                        <div class="logos-overlay" id="gridOverlayLeft"></div>
                    </div></div>
                    <div class="grid-item"><div class="grid-item-content">
                        <div class="grid-item-label">يمين</div>
                        <model-viewer id="gridModelRight" src="assets/img/3ds/t_shirt_hoodie_3d_model.glb" poster="assets/img/3ds/hoodie_poster.webp" camera-orbit="-90deg 75deg 105%" min-camera-orbit="-90deg 75deg 105%" max-camera-orbit="-90deg 75deg 105%" field-of-view="30deg" disable-zoom disable-pan disable-tap interaction-prompt="none" ar-modes=""></model-viewer>
                        <div class="logos-overlay" id="gridOverlayRight"></div>
                    </div></div>
                </div>
            </div>
        </div>

        <!-- BOTTOM BAR — الآن داخل .main فيظهر صح دايمًا -->
        <div class="bottom-bar">
            <div class="status-hint">اضغط على اللوجو لـ <span>أدوات التحكم</span></div>
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
            <div class="modal-title">تفاصيل <span>الطلب</span></div>
            <button class="modal-close" onclick="closeModal('orderModal')">✕</button>
        </div>
        <div class="modal-body" id="orderModalBody">
            <div class="form-group"><label>الاسم الكامل</label><input type="text" id="orderName" placeholder="اكتب اسمك"></div>
            <div class="form-group"><label>رقم الهاتف</label><input type="tel" id="orderPhone" placeholder="01xxxxxxxxx"></div>
            <div class="form-group"><label>العنوان</label><input type="text" id="orderAddress" placeholder="المحافظة / المدينة"></div>
            <div class="form-group"><label>المقاس</label>
                <select id="orderSize">
                    <option value="">اختر المقاس</option>
                    <option>S</option><option>M</option><option>L</option><option>XL</option><option>XXL</option>
                </select>
            </div>
            <div class="form-group"><label>ملاحظات (اختياري)</label><textarea id="orderNotes" rows="2" placeholder="أي ملاحظات..."></textarea></div>
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
            <div class="modal-title">تصدير <span>الصور</span></div>
            <button class="modal-close" onclick="closeModal('exportModal')">✕</button>
        </div>
        <div class="modal-body">
            <p style="color:var(--muted);font-size:12px;margin-bottom:10px;line-height:1.7;">
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

<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
<script>
/* ════════════════════════════════════════════
   بيانات الأقسام من DB
════════════════════════════════════════════ */
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

/* ════ REFS ════ */
const modelViewer     = document.getElementById('hoodieModel');
const hoodieWrapper   = document.getElementById('hoodieWrapper');
const logosOverlay    = document.getElementById('logosOverlay');
const colorOverlay    = document.getElementById('colorOverlay');
const viewButtons     = document.querySelectorAll('.view-btn[data-view]');
const hoodieContainer = document.getElementById('hoodieContainer');
const logoToolbar     = document.getElementById('logoToolbar');

/* ════ STATE ════ */
let currentView       = 'front';
let logoCounter       = 0;
let isPreviewMode     = false, isFreeControlMode = false, isGridView = false;
let previewInterval   = null;
let dragPreview       = null, isDraggingFromSidebar = false, currentDragSource = null;
let selectedLogo      = null, selectedLogoData = null;
let logosByView       = { front:[], back:[], left:[], right:[] };
let currentColor      = getComputedStyle(document.documentElement).getPropertyValue('--hoodie-bg').trim();
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
    setTimeout(syncGridCameraSettings, 600);
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
document.getElementById('gridViewBtn').addEventListener('click', () => isGridView ? stopGridView() : startGridView());

function startFreeControl() {
    if (isPreviewMode) stopPreview();
    deselectLogo();
    isFreeControlMode = true;
    document.getElementById('freeControlBtn').textContent = 'قفل';
    document.getElementById('freeControlBtn').classList.add('active');
    document.body.classList.add('free-control-active');
    modelViewer.setAttribute('camera-controls', '');
    modelViewer.setAttribute('touch-action', 'pan-y');
    viewButtons.forEach(b => b.classList.remove('active'));
}
function stopFreeControl() {
    isFreeControlMode = false;
    document.getElementById('freeControlBtn').textContent = 'تحكم حر';
    document.getElementById('freeControlBtn').classList.remove('active');
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
    document.getElementById('previewBtn').classList.add('active');
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
    document.getElementById('previewBtn').classList.remove('active');
    if (previewInterval) { clearInterval(previewInterval); previewInterval = null; }
    updateVisibleLogos();
}
function startGridView() {
    if (isPreviewMode) stopPreview();
    if (isFreeControlMode) stopFreeControl();
    deselectLogo();
    isGridView = true;
    document.getElementById('gridViewBtn').textContent = 'رجوع';
    document.getElementById('gridViewBtn').classList.add('active');
    hoodieContainer.classList.add('grid-view');
    syncLogosToGrid();
    viewButtons.forEach(b => b.classList.remove('active'));
}
function stopGridView() {
    isGridView = false;
    document.getElementById('gridViewBtn').textContent = 'عرض الكل';
    document.getElementById('gridViewBtn').classList.remove('active');
    hoodieContainer.classList.remove('grid-view');
    viewButtons.forEach(b => { if (b.dataset.view === currentView) b.classList.add('active'); });
}
function syncLogosToGrid() {
    ['Front','Back','Left','Right'].forEach(v => {
        const el = document.getElementById(`gridOverlay${v}`);
        if (el) el.innerHTML = '';
    });
    Object.keys(logosByView).forEach(view => {
        const overlay = document.getElementById(`gridOverlay${view.charAt(0).toUpperCase()+view.slice(1)}`);
        if (!overlay) return;
        logosByView[view].forEach(d => {
            const el = document.createElement('div');
            el.className = 'logo-on-hoodie active';
            el.style.cssText = `left:${d.xPercent}%;top:${d.yPercent}%;width:${d.widthPercent}%;height:${d.heightPercent}%;transform:rotate(${d.rotation}deg);pointer-events:none;`;
            const img = document.createElement('img'); img.src = d.src; img.draggable = false;
            el.appendChild(img); overlay.appendChild(el);
        });
    });
}
function syncGridCameraSettings() {
    try {
        const t = modelViewer.getCameraTarget();
        ['Front','Back','Left','Right'].forEach(v => {
            const m = document.getElementById(`gridModel${v}`);
            if (m) { m.cameraTarget = `${t.x}m ${t.y}m ${t.z}m`; m.fieldOfView = modelViewer.fieldOfView; }
        });
    } catch(e) {}
}

modelViewer.addEventListener('camera-change', () => {
    if (!isFreeControlMode) return;
    const orbit = modelViewer.getCameraOrbit();
    const deg = ((orbit.theta * 180 / Math.PI) % 360 + 360) % 360;
    const v = deg >= 315 || deg < 45 ? 'front' : deg < 135 ? 'right' : deg < 225 ? 'back' : 'left';
    if (v !== currentView) { currentView = v; updateVisibleLogos(); }
});

/* ════ TOOLBAR ════ */
document.getElementById('rotateCCW').addEventListener('click', () => { if (!selectedLogoData) return; selectedLogoData.rotation=(selectedLogoData.rotation||0)-15; selectedLogo.style.transform=`rotate(${selectedLogoData.rotation}deg)`; });
document.getElementById('rotateCW').addEventListener('click',  () => { if (!selectedLogoData) return; selectedLogoData.rotation=(selectedLogoData.rotation||0)+15; selectedLogo.style.transform=`rotate(${selectedLogoData.rotation}deg)`; });
document.getElementById('zoomIn').addEventListener('click',  () => { if (!selectedLogoData) return; const s=Math.min(80,selectedLogoData.widthPercent+5); selectedLogoData.widthPercent=selectedLogoData.heightPercent=s; selectedLogo.style.width=selectedLogo.style.height=s+'%'; });
document.getElementById('zoomOut').addEventListener('click', () => { if (!selectedLogoData) return; const s=Math.max(5,selectedLogoData.widthPercent-5); selectedLogoData.widthPercent=selectedLogoData.heightPercent=s; selectedLogo.style.width=selectedLogo.style.height=s+'%'; });
document.getElementById('deleteLogo').addEventListener('click', () => {
    if (!selectedLogoData||!selectedLogo) return;
    logosByView[selectedLogoData.view] = logosByView[selectedLogoData.view].filter(l=>l.id!==selectedLogoData.id);
    selectedLogo.remove(); deselectLogo();
});

document.addEventListener('click', e => { if (!e.target.closest('.logo-on-hoodie') && !e.target.closest('.logo-toolbar')) deselectAll(); });
document.addEventListener('touchend', e => { if (isDraggingFromSidebar) return; if (!e.target.closest('.logo-on-hoodie') && !e.target.closest('.logo-toolbar')) deselectAll(); }, {passive:true});

function deselectAll() { if (selectedLogo) selectedLogo.classList.remove('selected'); selectedLogo=selectedLogoData=null; logoToolbar.classList.remove('active'); }
function deselectLogo() { deselectAll(); }
function selectLogo(logo, data) {
    logosOverlay.querySelectorAll('.logo-on-hoodie').forEach(l=>l.classList.remove('selected'));
    selectedLogo=logo; selectedLogoData=data; logo.classList.add('selected'); logoToolbar.classList.add('active');
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

/* ════ ADD LOGO ════ */
function addLogo(src, x, y) {
    logoCounter++;
    const r = hoodieWrapper.getBoundingClientRect();
    const cx=(x/r.width)*100, cy=(y/r.height)*100;
    const wp=LOGO_SIZE_PCT, hp=LOGO_SIZE_PCT;
    const data = {
        id: logoCounter, src,
        centerXPercent: cx, centerYPercent: cy,
        xPercent: Math.max(0,Math.min(cx-wp/2,100-wp)),
        yPercent: Math.max(0,Math.min(cy-hp/2,100-hp)),
        widthPercent: wp, heightPercent: hp, rotation: 0, view: currentView
    };
    logosByView[currentView].push(data);
    const el = createLogoElement(data);
    setTimeout(() => selectLogo(el, data), 80);
}

function updateLogoCenter(d) { d.centerXPercent=d.xPercent+d.widthPercent/2; d.centerYPercent=d.yPercent+d.heightPercent/2; }

function createLogoElement(data) {
    const logo = document.createElement('div');
    logo.className='logo-on-hoodie'; logo.dataset.id=data.id; logo.dataset.view=data.view;
    logo.style.cssText=`left:${data.xPercent}%;top:${data.yPercent}%;width:${data.widthPercent}%;height:${data.heightPercent}%;transform:rotate(${data.rotation}deg);`;
    const img=document.createElement('img'); img.src=data.src; img.draggable=false;
    const del=document.createElement('button'); del.className='delete-btn'; del.innerHTML='✕';
    del.onclick=e=>{ e.stopPropagation(); logosByView[data.view]=logosByView[data.view].filter(l=>l.id!==data.id); logo.remove(); deselectLogo(); };
    const handle=document.createElement('div'); handle.className='resize-handle';
    logo.append(img,del,handle);
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
    const start=e=>{ if(isDraggingFromSidebar) return; if(e.target.classList.contains('delete-btn')||e.target.classList.contains('resize-handle')) return; if(e.touches&&e.touches.length>1) return; e.preventDefault(); e.stopPropagation(); dragging=true; const t=e.touches?e.touches[0]:e; sx=t.clientX;sy=t.clientY;sl=data.xPercent;st=data.yPercent; };
    const move=e=>{ if(!dragging) return; if(e.touches&&e.touches.length>1){stop();return;} e.preventDefault(); const t=e.touches?e.touches[0]:e; const r=hoodieWrapper.getBoundingClientRect(); data.xPercent=Math.max(0,Math.min(sl+((t.clientX-sx)/r.width)*100,100-data.widthPercent)); data.yPercent=Math.max(0,Math.min(st+((t.clientY-sy)/r.height)*100,100-data.heightPercent)); logo.style.left=data.xPercent+'%'; logo.style.top=data.yPercent+'%'; updateLogoCenter(data); };
    const stop=()=>{ dragging=false; };
    logo.addEventListener('mousedown',start); document.addEventListener('mousemove',move); document.addEventListener('mouseup',stop);
    logo.addEventListener('touchstart',start,{passive:false}); logo.addEventListener('touchmove',move,{passive:false}); logo.addEventListener('touchend',stop,{passive:false});
}

function makeResizable(logo,data,handle) {
    let resizing=false,sy,ss,raf=null;
    const start=e=>{ e.stopPropagation(); e.preventDefault(); resizing=true; const t=e.touches?e.touches[0]:e; sy=t.clientY; const cr=hoodieWrapper.getBoundingClientRect(),lr=logo.getBoundingClientRect(); ss=(lr.width/cr.width)*100; data.widthPercent=data.heightPercent=ss; };
    const move=e=>{ if(!resizing) return; e.preventDefault(); const t=e.touches?e.touches[0]:e; const cr=hoodieWrapper.getBoundingClientRect(); const ns=Math.max(5,Math.min(80,ss+((t.clientY-sy)/cr.height)*100)); if(raf) cancelAnimationFrame(raf); raf=requestAnimationFrame(()=>{ data.widthPercent=data.heightPercent=ns; logo.style.width=logo.style.height=ns+'%'; updateLogoCenter(data); }); };
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
    let timer=null;
    img.addEventListener('touchstart', e=>{ const t=e.touches[0],sx=t.clientX,sy=t.clientY; timer=setTimeout(()=>{ isDraggingFromSidebar=true; document.body.style.overflow='hidden'; currentDragSource=img; dragPreview=document.createElement('img'); dragPreview.src=src; dragPreview.className='drag-preview'; dragPreview.style.left=sx-32+'px'; dragPreview.style.top=sy-32+'px'; document.body.appendChild(dragPreview); document.getElementById('sidebar').classList.remove('open'); },150); },{passive:true});
    img.addEventListener('touchend', ()=>{ if(timer){clearTimeout(timer);timer=null;} },{passive:true});
    img.addEventListener('touchmove', e=>{ if(!isDraggingFromSidebar&&timer){ clearTimeout(timer);timer=null; const t=e.touches[0]; isDraggingFromSidebar=true; document.body.style.overflow='hidden'; currentDragSource=img; dragPreview=document.createElement('img'); dragPreview.src=src; dragPreview.className='drag-preview'; dragPreview.style.left=t.clientX-32+'px'; dragPreview.style.top=t.clientY-32+'px'; document.body.appendChild(dragPreview); document.getElementById('sidebar').classList.remove('open'); } },{passive:true});
    grid.appendChild(img);
}

/* ════ UPLOAD LOGO — يرفع للسيرفر فوراً ويضاف للـ grid ════ */
function handleLogoUpload(input) {
    const file = input.files[0]; if (!file) return;

    // اعرض loading toast
    showToast('جاري رفع الصورة...');

    // ارفع بـ FormData مباشرة (أسرع وأضمن من base64)
    const formData = new FormData();
    formData.append('image', file);

    fetch('/logos/upload-temp', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (!data.url) throw new Error(data.error || 'فشل الرفع');

        const serverUrl = data.url; // URL حقيقي على السيرفر
        uploadedLogos.push(serverUrl);

        const grid = document.getElementById('logoGrid');
        if (!currentSectionId) {
            document.getElementById('selectedSectionName').textContent = 'مرفوعاتي';
            grid.innerHTML = '';
            uploadedLogos.forEach(s => addLogoToGrid(s, grid));
            document.getElementById('logosPanel').classList.add('open');
        } else {
            const noMsg = grid.querySelector('.no-logos-msg');
            if (noMsg) noMsg.remove();
            addLogoToGrid(serverUrl, grid);
        }
        showToast('تم رفع اللوجو ✓ اسحبه على الهودي');
    })
    .catch(err => {
        console.error('Upload error:', err);
        // Fallback: استخدم base64 مؤقتاً لو السيرفر فشل
        const reader = new FileReader();
        reader.onload = e => {
            const src = e.target.result;
            uploadedLogos.push(src);
            const grid = document.getElementById('logoGrid');
            if (!currentSectionId) {
                document.getElementById('selectedSectionName').textContent = 'مرفوعاتي';
                grid.innerHTML = '';
                uploadedLogos.forEach(s => addLogoToGrid(s, grid));
                document.getElementById('logosPanel').classList.add('open');
            } else {
                const noMsg = grid.querySelector('.no-logos-msg');
                if (noMsg) noMsg.remove();
                addLogoToGrid(src, grid);
            }
            showToast('تم إضافة اللوجو (محلي) ✓ اسحبه على الهودي');
        };
        reader.readAsDataURL(file);
    });

    input.value = '';
}

function showToast(msg) {
    const t = document.createElement('div');
    t.style.cssText = 'position:fixed;top:16px;left:50%;transform:translateX(-50%);background:#c9a84c;color:#0a0a0a;padding:8px 18px;border-radius:20px;font-size:12px;font-weight:700;z-index:99999;pointer-events:none;transition:opacity 0.4s;';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(()=>{ t.style.opacity='0'; setTimeout(()=>t.remove(),400); }, 2200);
}

/* ════ EXPORT 4 IMAGES — باستخدام model-viewer toBlob ════ */
function openExportModal() {
    document.getElementById('exportPreviews').innerHTML = '';
    document.getElementById('exportLoading').style.display = 'none';
    document.getElementById('exportModalFooter').innerHTML = `
        <button class="btn-cancel" onclick="closeModal('exportModal')">إغلاق</button>
        <button class="btn-submit" id="generateExportBtn" onclick="generateExportImages()">📸 إنشاء الصور</button>`;
    document.getElementById('exportModal').classList.add('open');
}

async function generateExportImages() {
    const btn = document.getElementById('generateExportBtn');
    btn.disabled = true; btn.textContent = 'جاري الالتقاط...';
    document.getElementById('exportLoading').style.display = 'block';
    document.getElementById('exportPreviews').innerHTML = '';

    const viewConfigs = [
        { key:'front', label:'الوش',  orbit:'0deg 75deg 105%' },
        { key:'back',  label:'الظهر', orbit:'180deg 75deg 105%' },
        { key:'left',  label:'يسار',  orbit:'90deg 75deg 105%' },
        { key:'right', label:'يمين',  orbit:'-90deg 75deg 105%' },
    ];

    // ═══ Step 1: التقط 4 صور ═══
    const captured = [];
    logosOverlay.style.display = 'none';

    for (const vc of viewConfigs) {
        modelViewer.cameraOrbit = vc.orbit;
        await new Promise(r => setTimeout(r, 700));
        try {
            const blob = await modelViewer.toBlob({ idealAspect: false });
            const dataUrl = await blobToDataUrl(blob);
            captured.push({ ...vc, dataUrl });
        } catch(err) {
            captured.push({ ...vc, dataUrl: null });
        }
    }

    // أعِد الكاميرا والـ overlay
    modelViewer.cameraOrbit = cameraViews[currentView];
    logosOverlay.style.display = '';
    updateVisibleLogos();

    // ═══ Step 2: ارسم اللوجوهات على كل صورة ═══
    const composited = [];
    for (const item of captured) {
        if (!item.dataUrl) { composited.push({ ...item, dataUrl: null }); continue; }
        try {
            const finalUrl = await compositeLogoOnImage(item.dataUrl, item.key);
            composited.push({ ...item, dataUrl: finalUrl });
        } catch(e) {
            composited.push(item);
        }
    }

    // ═══ Step 3: ادمج الـ 4 في صورة واحدة 2×2 ═══
    const CELL = 800; // حجم كل خلية بالبكسل
    const GAP  = 12;  // مسافة بين الخلايا
    const LABEL_H = 40; // ارتفاع اللابل تحت كل صورة
    const PADDING = 20;
    const COLS = 2, ROWS = 2;

    const totalW = COLS * CELL + (COLS - 1) * GAP + PADDING * 2;
    const totalH = ROWS * (CELL + LABEL_H) + (ROWS - 1) * GAP + PADDING * 2 + 50; // 50 للـ header

    const finalCanvas = document.createElement('canvas');
    finalCanvas.width  = totalW;
    finalCanvas.height = totalH;
    const ctx = finalCanvas.getContext('2d');

    // خلفية
    ctx.fillStyle = '#0a0a0a';
    ctx.fillRect(0, 0, totalW, totalH);

    // Header "DRAPE Design"
    ctx.fillStyle = '#c9a84c';
    ctx.font = 'bold 28px "Bebas Neue", sans-serif';
    ctx.textAlign = 'center';
    ctx.letterSpacing = '4px';
    ctx.fillText('DRAPE — تصميم الهودي', totalW / 2, PADDING + 28);

    // ارسم كل خلية
    const positions = [
        { col: 0, row: 0 }, // front
        { col: 1, row: 0 }, // back
        { col: 0, row: 1 }, // left
        { col: 1, row: 1 }, // right
    ];

    await Promise.all(composited.map((item, i) => new Promise(resolve => {
        const { col, row } = positions[i];
        const x = PADDING + col * (CELL + GAP);
        const y = PADDING + 50 + row * (CELL + LABEL_H + GAP);

        // خلفية الخلية (لون الهودي)
        ctx.fillStyle = '#e8e4dc';
        ctx.beginPath();
        ctx.roundRect(x, y, CELL, CELL, 12);
        ctx.fill();

        // لابل الوجه (أعلى الخلية)
        ctx.fillStyle = '#c9a84c';
        ctx.beginPath();
        ctx.roundRect(x + CELL/2 - 40, y + 8, 80, 26, 13);
        ctx.fill();
        ctx.fillStyle = '#0a0a0a';
        ctx.font = 'bold 14px Cairo, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText(item.label, x + CELL/2, y + 26);

        if (!item.dataUrl) {
            ctx.fillStyle = 'rgba(255,255,255,0.15)';
            ctx.font = '14px Cairo, sans-serif';
            ctx.fillText('تعذّر الالتقاط', x + CELL/2, y + CELL/2);
            resolve(); return;
        }

        const img = new Image();
        img.onload = () => {
            ctx.save();
            ctx.beginPath();
            ctx.roundRect(x, y, CELL, CELL, 12);
            ctx.clip();
            ctx.drawImage(img, x, y, CELL, CELL);
            ctx.restore();
            resolve();
        };
        img.onerror = () => resolve();
        img.src = item.dataUrl;
    })));

    // Footer
    ctx.fillStyle = 'rgba(201,168,76,0.3)';
    ctx.fillRect(PADDING, totalH - 28, totalW - PADDING * 2, 1);
    ctx.fillStyle = 'rgba(201,168,76,0.6)';
    ctx.font = '12px Cairo, sans-serif';
    ctx.textAlign = 'center';
    ctx.fillText('DRAPE — 3D Product Designer', totalW / 2, totalH - 10);

    const finalDataUrl = finalCanvas.toDataURL('image/png');

    // ═══ Step 4: عرض Preview ═══
    document.getElementById('exportLoading').style.display = 'none';
    const previewsEl = document.getElementById('exportPreviews');
    previewsEl.style.gridTemplateColumns = '1fr'; // صورة واحدة عرض كامل
    const wrap = document.createElement('div');
    wrap.className = 'export-preview-item';
    wrap.style.background = '#0a0a0a';
    const prevImg = document.createElement('img');
    prevImg.src = finalDataUrl;
    prevImg.style.cssText = 'width:100%;display:block;border-radius:8px;';
    wrap.appendChild(prevImg);
    previewsEl.appendChild(wrap);

    window._exportFinalImage = finalDataUrl;

    // ═══ Step 5: زرار التحميل ═══
    const footerEl = document.getElementById('exportModalFooter');
    footerEl.innerHTML = '';
    const _closeBtn = document.createElement('button');
    _closeBtn.className = 'btn-cancel';
    _closeBtn.textContent = 'إغلاق';
    _closeBtn.onclick = () => closeModal('exportModal');
    const _dlBtn = document.createElement('button');
    _dlBtn.className = 'btn-submit';
    _dlBtn.textContent = '⬇️ تحميل الصورة';
    _dlBtn.onclick = () => {
        const a = document.createElement('a');
        a.href = window._exportFinalImage;
        a.download = 'drape-design.png';
        document.body.appendChild(a); a.click(); document.body.removeChild(a);
    };
    footerEl.appendChild(_closeBtn);
    footerEl.appendChild(_dlBtn);
}

/* رسم اللوجوهات فوق صورة الـ model-viewer */
async function compositeLogoOnImage(bgDataUrl, viewKey) {
    return new Promise((resolve) => {
        const canvas = document.createElement('canvas');
        const size = 800;
        canvas.width = size; canvas.height = size;
        const ctx = canvas.getContext('2d');

        const bg = new Image();
        bg.crossOrigin = 'anonymous';
        bg.onload = async () => {
            ctx.drawImage(bg, 0, 0, size, size);
            const logos = logosByView[viewKey] || [];
            for (const d of logos) {
                await new Promise(rLogo => {
                    const lImg = new Image();
                    lImg.crossOrigin = 'anonymous';
                    lImg.onload = () => {
                        ctx.save();
                        const lx = (d.xPercent / 100) * size;
                        const ly = (d.yPercent / 100) * size;
                        const lw = (d.widthPercent / 100) * size;
                        const lh = (d.heightPercent / 100) * size;
                        const cx = lx + lw/2, cy = ly + lh/2;
                        ctx.translate(cx, cy);
                        ctx.rotate((d.rotation||0) * Math.PI/180);
                        ctx.drawImage(lImg, -lw/2, -lh/2, lw, lh);
                        ctx.restore();
                        rLogo();
                    };
                    lImg.onerror = () => rLogo();
                    lImg.src = d.src;
                });
            }
            resolve(canvas.toDataURL('image/png'));
        };
        bg.onerror = () => resolve(bgDataUrl);
        bg.src = bgDataUrl;
    });
}

function blobToDataUrl(blob) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = e => resolve(e.target.result);
        reader.onerror = reject;
        reader.readAsDataURL(blob);
    });
}

/* ════ ORDER ════ */
function openOrderModal() {
    const all = Object.values(logosByView).flat();
    if (!all.length) { showToast('من فضلك ضيف لوجو الأول!'); return; }
    document.getElementById('orderModal').classList.add('open');
}

async function submitOrder() {
    const name    = document.getElementById('orderName').value.trim();
    const phone   = document.getElementById('orderPhone').value.trim();
    const address = document.getElementById('orderAddress').value.trim();
    const size    = document.getElementById('orderSize').value;
    if (!name||!phone||!address||!size) { showToast('من فضلك املأ كل الحقول'); return; }

    const btn = document.getElementById('submitOrderBtn');
    document.getElementById('submitBtnText').style.display = 'none';
    document.getElementById('submitBtnLoader').style.display = '';
    btn.disabled = true;

    // اللوجوهات اتحفظت على السيرفر بالفعل وقت الرفع — بس نجمع البيانات
    const logosData = Object.values(logosByView).flat().map(l => ({
        src:            l.src,  // دايمًا URL حقيقي (اترفع مسبقاً)
        view:           l.view,
        x_percent:      parseFloat(l.xPercent.toFixed(2)),
        y_percent:      parseFloat(l.yPercent.toFixed(2)),
        width_percent:  parseFloat(l.widthPercent.toFixed(2)),
        height_percent: parseFloat(l.heightPercent.toFixed(2)),
        rotation:       l.rotation || 0
    }));

    try {
        const res = await fetch('/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':        'application/json',
                'X-CSRF-TOKEN':  CSRF_TOKEN,
            },
            body: JSON.stringify({
                name, phone, address, size,
                notes: document.getElementById('orderNotes').value,
                product: 'hoodie', color: currentColor,
                logos: logosData
            })
        });
        const data = await res.json();
        if (data.success) {
            document.getElementById('orderModalBody').innerHTML = `
                <div class="success-msg">
                    <div class="success-icon">✦</div>
                    <h4>تم إرسال <span>طلبك</span></h4>
                    <p>رقم الطلب: <strong>#${data.order_id||'—'}</strong></p>
                    <p style="margin-top:6px;">هنتواصل معاك على ${phone} قريباً</p>
                </div>`;
            document.getElementById('orderModalFooter').innerHTML = `<button class="btn-submit" onclick="closeModal('orderModal')" style="flex:1">حسناً ✓</button>`;
        } else {
            showToast(data.message||'حدث خطأ'); btn.disabled=false;
            document.getElementById('submitBtnText').style.display=''; document.getElementById('submitBtnLoader').style.display='none';
        }
    } catch(e) {
        showToast('حدث خطأ: '+e.message); btn.disabled=false;
        document.getElementById('submitBtnText').style.display=''; document.getElementById('submitBtnLoader').style.display='none';
    }
}

/* ════ UTILS ════ */
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    // FIX: إغلاق الـ modal ما يشيلش اللوجوهات من الـ state
    // اللوجوهات موجودة في logosByView وهي منفصلة عن الـ DOM
}

document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) m.classList.remove('open'); });
});
</script>
</body>
</html>