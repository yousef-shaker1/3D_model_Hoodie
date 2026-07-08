<!DOCTYPE html>
<html lang="ar" dir="rtl" style="background-color:#0f172a;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WearCraft — مصمم 3D</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Cairo:wght@300;400;600;700;900&family=Playfair+Display:ital,wght@0,700;1,400&display=swap" rel="stylesheet">
    <style>
        :root {
            /* ─── Navy Blue Dark Theme (الكحلي الغامق) ─── */
            --cream:      #0f172a;
            --cream-dark: #1e293b;
            --parchment:  #334155;
            --gold:       #818cf8;
            --gold-deep:  #6366f1;
            --gold-light: #a5b4fc;
            --gold-soft:  rgba(129, 140, 248, 0.15);
            --gold-glow:  rgba(129, 140, 248, 0.4);
            --ink:        #f8fafc;
            --ink-soft:   #e2e8f0;
            --charcoal:   #cbd5e1;
            --muted:      #94a3b8;
            --muted-light:#64748b;
            --border:     rgba(255, 255, 255, 0.1);
            --border-soft:rgba(255, 255, 255, 0.05);
            --danger:     #ef4444;
            --white:      #1e293b;
            --sidebar-bg: #0f172a;
            --sidebar-w:  288px;
            --topbar-h:   64px;
            --bottombar-h:72px;
            --font-display: 'Playfair Display', serif;
            --font-serif:   'Playfair Display', serif;
            --font-body:    'Cairo', sans-serif;
            --radius:     16px;
            --radius-sm:  10px;
            --radius-lg:  24px;
            --shadow-sm:  0 4px 12px rgba(0,0,0,0.3);
            --shadow-md:  0 12px 32px rgba(0,0,0,0.4);
            --shadow-lg:  0 24px 64px rgba(0,0,0,0.5);
            --shadow-gold:0 0 32px rgba(129, 140, 248, 0.25);
        }

        html[data-theme="dark"] {
            /* ─── Modern Dark Theme (Black/Indigo) ─── */
            --cream:      #09090b;
            --cream-dark: #18181b;
            --parchment:  #27272a;
            --gold:       #6366f1;
            --gold-deep:  #4f46e5;
            --gold-light: #818cf8;
            --gold-soft:  rgba(99, 102, 241, 0.15);
            --gold-glow:  rgba(99, 102, 241, 0.4);
            --ink:        #f8fafc;
            --ink-soft:   #e2e8f0;
            --charcoal:   #cbd5e1;
            --muted:      #a1a1aa;
            --muted-light:#71717a;
            --border:     rgba(255, 255, 255, 0.12);
            --border-soft:rgba(255, 255, 255, 0.06);
            --danger:     #ef4444;
            --white:      #18181b;
            --sidebar-bg: #09090b;
            --shadow-sm:  0 4px 12px rgba(0,0,0,0.5);
            --shadow-md:  0 12px 32px rgba(0,0,0,0.6);
            --shadow-lg:  0 24px 64px rgba(0,0,0,0.8);
            --shadow-gold:0 0 32px rgba(79, 70, 229, 0.25);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; background: var(--cream); color: var(--ink); font-family: var(--font-body); overflow: hidden; transition: background-color 0.4s ease, color 0.4s ease; }

        /* Subtle grain overlay */
        body::after {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
            background-size: 200px;
            pointer-events: none; z-index: 9999; opacity: 0.5;
        }

        /* ════ LOADING ════ */
        #loadingScreen {
            position: fixed; inset: 0;
            background: #0f172a;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            z-index: 99998; transition: opacity 0.9s ease, visibility 0.9s ease;
            gap: 20px;
        }
        #loadingScreen.hidden { opacity: 0; visibility: hidden; pointer-events: none; }

        .ls-bg-pattern {
            position: absolute; inset: 0;
            background-image: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 60px,
                rgba(184,146,74,0.03) 60px,
                rgba(184,146,74,0.03) 61px
            );
        }

        .ls-inner { position: relative; z-index: 1; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 20px; }

        .ls-ornament {
            width: 60px; height: 1px; background: linear-gradient(90deg, transparent, var(--gold), transparent);
            margin: 0 auto;
        }

        .ls-brand {
            font-family: var(--font-display);
            font-size: clamp(24px, 6vw, 60px); font-weight: 300;
            letter-spacing: 0.35em;
            color: #ffffff;
            line-height: 1;
            text-indent: 0.35em;
        }
        .ls-brand em { font-style: normal; color: #a5b4fc; font-weight: 600; }

        .ls-tagline {
            font-size: 10px; letter-spacing: 0.5em; color: var(--muted-light);
            text-transform: uppercase; font-weight: 400;
        }

        .ls-bar-wrap {
            width: 180px; height: 1px;
            background: rgba(255,255,255,0.08);
            position: relative; overflow: hidden;
        }
        .ls-bar {
            position: absolute; top: 0; left: 0;
            height: 100%; width: 0%;
            background: linear-gradient(90deg, var(--gold-deep), var(--gold-light));
            transition: width 0.4s ease;
            box-shadow: 0 0 10px var(--gold-glow);
        }
        .ls-pct {
            font-family: var(--font-display);
            font-size: 12px; letter-spacing: 0.3em;
            color: var(--gold-light); font-weight: 300;
        }

        /* ════ APP SHELL ════ */
        .app { display: flex; height: 100vh; width: 100vw; overflow: hidden; }

        /* ════ SIDEBAR ════ */
        .sidebar {
            width: var(--sidebar-w); flex-shrink: 0;
            background: var(--cream-dark);
            border-left: 1px solid var(--border);
            display: flex; flex-direction: column; overflow: hidden;
            position: relative; z-index: 10;
        }
        .sidebar::before { display: none; }

        .sb-header {
            padding: 16px 16px 14px;
            border-bottom: 1px solid var(--border);
            background: var(--cream-dark);
            flex-shrink: 0; position: relative;
        }

        .sb-logo-wrap { display: flex; align-items: baseline; gap: 2px; margin-bottom: 2px; }
        .sb-brand {
            font-family: var(--font-display);
            font-size: 22px; font-weight: 700;
            letter-spacing: 0.1em; color: var(--ink);
        }
        .sb-brand em { font-style: normal; color: var(--gold-light); }

        .sb-subtitle {
            font-size: 9px; letter-spacing: 0.3em;
            color: var(--muted); text-transform: uppercase;
            margin-bottom: 10px;
        }

        .sb-product-badge {
            display: inline-flex; align-items: center; gap: 5px;
            background: var(--gold-soft);
            border: 1px solid var(--border);
            border-radius: 100px; padding: 4px 10px;
            font-size: 10px; color: var(--gold-light);
        }
        .sb-product-badge::before {
            content: '';
            width: 5px; height: 5px;
            background: var(--gold);
            border-radius: 50%;
        }

        .sb-body {
            flex: 1; overflow-y: auto; overflow-x: hidden;
            padding: 12px 12px; scrollbar-width: thin;
            scrollbar-color: var(--border) transparent;
        }
        .sb-body::-webkit-scrollbar { width: 2px; }
        .sb-body::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

        .sb-section-label {
            font-size: 8px; letter-spacing: 0.4em;
            color: var(--muted); text-transform: uppercase;
            margin-bottom: 8px; margin-top: 12px;
            display: flex; align-items: center; gap: 8px;
        }
        .sb-section-label::after {
            content: ''; flex: 1; height: 1px;
            background: var(--border);
        }
        .sb-section-label:first-child { margin-top: 0; }

        /* Sections Grid */
        .sections-grid {
            display: grid; grid-template-columns: repeat(5, 1fr);
            gap: 5px; margin-bottom: 10px;
        }
        .section-item {
            aspect-ratio: 1;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            border: 1px solid var(--border);
            border-radius: 8px; cursor: pointer;
            background: var(--cream);
            transition: all 0.2s ease;
            padding: 4px; position: relative; overflow: hidden;
        }
        .section-item:hover { border-color: var(--gold); transform: translateY(-1px); box-shadow: 0 3px 10px rgba(0,0,0,0.2); }
        .section-item.active { border-color: var(--gold); box-shadow: 0 0 0 1px var(--gold-soft); }
        .section-item img { width: 100%; height: 100%; object-fit: contain; border-radius: 5px; }
        .section-item-icon { font-size: 14px; }
        .section-item-label { font-size: 6px; color: var(--muted); text-align: center; margin-top: 2px; }

        /* Logos Panel */
        .logos-panel { margin-bottom: 10px; display: none; }
        .logos-panel.open { display: block; }
        .logos-panel-title {
            font-size: 9px; letter-spacing: 0.3em;
            color: var(--gold-light); text-transform: uppercase;
            margin-bottom: 6px;
        }
        .logo-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 5px; }
        .logo-item {
            aspect-ratio: 1; border: 1px solid var(--border);
            border-radius: 7px; cursor: grab;
            background: var(--cream); padding: 4px;
            object-fit: contain; transition: all 0.2s; width: 100%;
        }
        .logo-item:hover {
            border-color: var(--gold);
            transform: scale(1.05);
            box-shadow: 0 0 10px var(--gold-soft);
        }
        .no-logos-msg { color: var(--muted); font-size: 11px; text-align: center; grid-column: 1/-1; padding: 8px 0; }

        /* Upload Button */
        .upload-logo-btn {
            width: 100%; padding: 8px;
            background: transparent;
            border: 1px dashed var(--border);
            border-radius: 8px; color: var(--muted);
            font-family: var(--font-body); font-size: 11px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            transition: all 0.2s; margin-bottom: 10px;
        }
        .upload-logo-btn:hover {
            border-color: var(--gold);
            color: var(--gold-light);
            background: var(--gold-soft);
        }

        /* Instructions */
        .instructions {
            border: 1px solid var(--border);
            border-radius: 8px; padding: 8px 10px;
            background: var(--cream);
        }
        .instruction-row {
            display: flex; align-items: center; gap: 8px;
            font-size: 10px; color: var(--muted);
            padding: 4px 0; line-height: 1.4;
        }
        .instruction-row:not(:last-child) { border-bottom: 1px solid var(--border); }
        .instruction-icon {
            width: 20px; height: 20px;
            background: var(--gold-soft);
            border: 1px solid var(--border);
            border-radius: 5px;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; flex-shrink: 0;
        }

        /* ════ MAIN ════ */
        .main {
            flex: 1; display: flex; flex-direction: column;
            background: var(--cream); height: 100vh; overflow: hidden; min-width: 0;
        }

        /* ════ TOP BAR ════ */
        .top-bar {
            height: var(--topbar-h); flex-shrink: 0;
            display: flex; align-items: center;
            padding: 0 8px 0 20px;
            border-bottom: 1px solid var(--border-soft);
            gap: 4px;
            background: var(--white);
            box-shadow: var(--shadow-sm);
            position: relative; z-index: 5;
        }
        .top-bar::-webkit-scrollbar { display: none; }
        .top-bar-btns {
            display: flex; align-items: center; justify-content: center;
            gap: 4px; flex: 1; flex-wrap: nowrap;
        }

        .view-btn {
            font-family: var(--font-body); font-size: 11px; font-weight: 600;
            letter-spacing: 0.06em; padding: 7px 14px;
            border-radius: 100px; cursor: pointer;
            border: 1px solid transparent;
            background: transparent; color: var(--muted);
            transition: all 0.2s; white-space: nowrap; flex-shrink: 0;
        }
        .view-btn:hover { color: var(--ink); background: var(--cream-dark); }
        .view-btn.active {
            color: var(--cream);
    box-shadow: var(--shadow-sm);
    background: linear-gradient(135deg, var(--gold-deep) 0%, var(--gold) 50%, var(--gold-light) 100%);
        }
        .view-btn.gold-active {
            background: var(--gold); color: var(--white);
            border-color: var(--gold);
            box-shadow: var(--shadow-gold);
        }

        .divider-dot { width: 3px; height: 3px; border-radius: 50%; background: var(--parchment); flex-shrink: 0; }

        .sidebar-toggle {
            display: none; width: 36px; height: 36px;
            background: var(--cream-dark); border: 1px solid var(--border-soft);
            border-radius: 10px; color: var(--charcoal);
            font-size: 16px; align-items: center; justify-content: center;
            cursor: pointer; flex-shrink: 0; transition: all 0.2s;
        }
        .sidebar-toggle:hover { background: var(--ink); color: var(--cream); border-color: var(--ink); }

        /* ════ CANVAS WRAP ════ */
        .canvas-wrap {
            flex: 1; min-height: 0;
            display: flex; align-items: center; justify-content: center;
            padding: 24px; position: relative; overflow: hidden;
            background: var(--cream);
        }

        /* Subtle cross pattern */
        .canvas-wrap::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(var(--border-soft) 1px, transparent 1px),
                linear-gradient(90deg, var(--border-soft) 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: radial-gradient(ellipse at center, transparent 40%, black 100%);
            opacity: 0.4;
        }

        /* Corner brackets */
        .corner { position: absolute; width: 28px; height: 28px; pointer-events: none; z-index: 1; }
        .corner-tl { top: 12px; right: 12px; border-top: 1.5px solid var(--gold-light); border-right: 1.5px solid var(--gold-light); }
        .corner-br { bottom: 12px; left: 12px; border-bottom: 1.5px solid var(--gold-light); border-left: 1.5px solid var(--gold-light); }
        .corner-tr { top: 12px; left: 12px; border-top: 1.5px solid var(--gold-light); border-left: 1.5px solid var(--gold-light); }
        .corner-bl { bottom: 12px; right: 12px; border-bottom: 1.5px solid var(--gold-light); border-right: 1.5px solid var(--gold-light); }

        /* ════ HOODIE CONTAINER ════ */
        .hoodie-container {
            width: min(calc(100vh - var(--topbar-h) - var(--bottombar-h) - 48px), calc(100% - 48px));
            height: min(calc(100vh - var(--topbar-h) - var(--bottombar-h) - 48px), calc(100% - 48px));
            max-width: 520px; max-height: 520px;
            aspect-ratio: 1; position: relative;
            border-radius: 20px; overflow: hidden;
            border: 1px solid rgba(184,146,74,0.2);
            background: #ede9e0;
            box-shadow:
                0 0 0 4px rgba(255,255,255,0.8),
                0 1px 3px rgba(184,146,74,0.15) inset,
                var(--shadow-lg),
                0 0 60px rgba(184,146,74,0.06);
            transition: background 0.3s;
            flex-shrink: 0;
        }

        .hoodie-wrapper { width: 100%; height: 100%; position: relative; }
        .hoodie-wrapper.drag-over::after {
            content: 'أفلت هنا';
            position: absolute; inset: 0;
            border: 2px dashed var(--gold); border-radius: 18px;
            pointer-events: none; z-index: 100;
            display: flex; align-items: center; justify-content: center;
            background: rgba(184,146,74,0.06);
            font-size: 16px; font-weight: 700; color: var(--gold);
        }

        model-viewer {
            width: 100%; height: 100%; border-radius: 18px;
            background-color: transparent; --poster-color: transparent;
        }
        model-viewer::part(default-progress-bar) { height: 2px; background: var(--gold); }

        .logos-overlay { position: absolute; inset: 0; pointer-events: none; z-index: 10; }
        .color-overlay { position: absolute; inset: 0; border-radius: 18px; pointer-events: none; z-index: 5; mix-blend-mode: multiply; opacity: 0; transition: opacity 0.3s; }
        .color-overlay.active { opacity: 0.45; }

        /* Color Picker */
        .color-picker-btn {
            width: 100%; padding: 10px;
            background: transparent;
            border: 1px dashed rgba(184,146,74,0.25);
            border-radius: 10px; color: var(--gold-light);
            font-family: var(--font-body); font-size: 11px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 7px;
            transition: all 0.2s; margin-bottom: 12px;
            letter-spacing: 0.05em;
        }
        .color-picker-btn:hover {
            border-color: var(--gold);
            background: rgba(184,146,74,0.07);
            box-shadow: 0 0 20px rgba(184,146,74,0.1);
        }
        .color-picker-btn .current-color {
            width: 20px; height: 20px;
            border-radius: 50%;
            border: 2px solid var(--gold);
            box-shadow: 0 0 8px rgba(184,146,74,0.3);
        }

        .color-palette {
            display: none;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(184,146,74,0.15);
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 14px;
        }
        .color-palette.open { display: block; }
        .color-palette-title {
            font-size: 9px; letter-spacing: 0.3em;
            color: var(--gold-light); text-transform: uppercase;
            margin-bottom: 10px;
        }
        .color-options {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
        }
        .color-option {
            width: 100%; aspect-ratio: 1;
            border-radius: 8px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s;
            position: relative;
        }
        .color-option:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .color-option.selected {
            border-color: var(--gold);
            box-shadow: 0 0 0 2px rgba(184,146,74,0.3);
        }
        .color-option.selected::after {
            content: '✓';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
        }

        /* Logo controls */
        body.free-control-active .logo-on-hoodie { pointer-events: none !important; }
        body.free-control-active .logo-on-hoodie .delete-btn,
        body.free-control-active .logo-on-hoodie .resize-handle { display: none !important; }

        .logo-on-hoodie { position: absolute; pointer-events: auto; cursor: move; user-select: none; opacity: 0; transition: opacity 0.3s; }
        .logo-on-hoodie.active { opacity: 1; }
        .logo-on-hoodie.selected {
            outline: 2px solid var(--gold);
            outline-offset: 4px;
            box-shadow: 0 0 20px rgba(184,146,74,0.35);
        }
        .logo-on-hoodie img { width: 100%; height: 100%; object-fit: contain; pointer-events: none; }
        .logo-on-hoodie .delete-btn {
            position: absolute; top: -10px; right: -10px;
            background: var(--danger); color: #fff;
            border: 2px solid var(--white);
            border-radius: 50%; width: 22px; height: 22px; cursor: pointer;
            font-size: 10px; opacity: 0; transition: opacity 0.2s; z-index: 10;
            display: flex; align-items: center; justify-content: center;
            box-shadow: var(--shadow-sm);
        }
        .logo-on-hoodie .resize-handle {
            position: absolute; bottom: -10px; right: -10px;
            width: 20px; height: 20px; background: var(--gold);
            border: 2px solid var(--white); border-radius: 50%;
            cursor: nwse-resize; opacity: 0; transition: opacity 0.2s; z-index: 10;
            box-shadow: var(--shadow-sm);
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
            flex-shrink: 0; height: var(--bottombar-h);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px;
            border-top: 1px solid var(--border-soft);
            gap: 10px;
            background: var(--white);
            box-shadow: 0 -4px 16px rgba(26,22,18,0.05);
            z-index: 10;
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }

        .status-hint { font-size: 11px; letter-spacing: 0.05em; color: var(--muted); white-space: nowrap; }
        .status-hint strong { color: var(--gold-deep); font-weight: 600; }

        .bottom-actions { display: flex; gap: 8px; flex-shrink: 0; }

        .save-btn {
            font-family: var(--font-body); font-size: 12px; font-weight: 600;
            padding: 10px 18px; border-radius: 100px;
            border: 1.5px solid var(--border-soft);
            background: var(--cream-dark); color: var(--charcoal);
            cursor: pointer; transition: all 0.2s; white-space: nowrap;
            letter-spacing: 0.04em;
        }
        .save-btn:hover {
            border-color: var(--gold);
            color: var(--gold-deep);
            background: var(--cream);
            box-shadow: 0 0 0 3px var(--gold-soft);
        }

        .order-btn {
            font-family: var(--font-body); font-size: 13px; font-weight: 700;
            letter-spacing: 0.06em; padding: 10px 24px;
            border-radius: 100px; border: none; cursor: pointer;
            background: linear-gradient(135deg, var(--gold-deep) 0%, var(--gold) 50%, var(--gold-light) 100%);
            color: var(--white); transition: all 0.25s; white-space: nowrap;
            box-shadow: 0 4px 16px rgba(184,146,74,0.35);
            position: relative; overflow: hidden;
        }
        .order-btn::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            opacity: 0; transition: opacity 0.2s;
        }
        .order-btn:hover { transform: translateY(-1px); box-shadow: 0 8px 28px rgba(184,146,74,0.45); }
        .order-btn:hover::after { opacity: 1; }
        .order-btn:active { transform: translateY(0); }

        /* ════ MOBILE LOGO TOOLBAR ════ */
        .logo-toolbar {
            position: fixed; bottom: calc(var(--bottombar-h) + 12px);
            left: 50%; transform: translateX(-50%);
            background: var(--ink); border: 1px solid rgba(184,146,74,0.2);
            border-radius: 100px; padding: 7px 10px;
            display: none; gap: 4px; z-index: 1000;
            box-shadow: var(--shadow-lg), 0 0 0 1px rgba(255,255,255,0.05) inset;
        }
        .logo-toolbar.active { display: flex; }
        body.free-control-active .logo-toolbar { display: none !important; }

        .toolbar-btn {
            background: rgba(255,255,255,0.06); color: var(--cream);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 100px; width: 40px; height: 40px;
            cursor: pointer; font-size: 16px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.15s;
        }
        .toolbar-btn:active { background: var(--gold); color: var(--ink); border-color: var(--gold); }
        .toolbar-btn.danger { color: #ff7b6b; }
        .toolbar-btn.danger:active { background: var(--danger); color: #fff; border-color: var(--danger); }

        .drag-preview {
            position: fixed; width: 64px;
            pointer-events: none; z-index: 9999;
            opacity: 0.85; filter: drop-shadow(0 8px 20px rgba(184,146,74,0.5));
        }

        /* ════ MODALS ════ */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(26,22,18,0.65);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 10000;
            display: none; align-items: center; justify-content: center;
            padding: 16px;
        }
        .modal-overlay.open { display: flex; }

        .modal-box {
            background: var(--white);
            border: 1px solid var(--border-soft);
            border-radius: 20px; width: 100%; max-width: 460px;
            overflow: hidden;
            animation: modalIn 0.35s cubic-bezier(0.34,1.4,0.64,1);
            box-shadow: var(--shadow-lg), 0 0 0 1px rgba(184,146,74,0.08);
            max-height: 90vh; display: flex; flex-direction: column;
        }
        @media (max-width: 480px) {
            .modal-box {
                max-width: 95vw;
                border-radius: 16px;
            }
            .modal-header {
                padding: 16px 18px 14px;
            }
            .modal-title {
                font-size: 22px;
            }
            .modal-body {
                padding: 16px 18px;
            }
            .modal-footer {
                padding: 12px 18px;
            }
        }
        @keyframes modalIn {
            from { opacity:0; transform: scale(0.94) translateY(16px); }
            to   { opacity:1; transform: scale(1) translateY(0); }
        }

        .modal-header {
            padding: 20px 22px 16px;
            border-bottom: 1px solid var(--border-soft);
            display: flex; align-items: center; justify-content: space-between;
            flex-shrink: 0;
            background: linear-gradient(180deg, var(--cream) 0%, var(--white) 100%);
        }
        .modal-title {
            font-family: var(--font-display);
            font-size: 26px; font-weight: 300;
            letter-spacing: 0.1em; color: var(--ink);
        }
        .modal-title em { font-style: normal; color: var(--gold); font-weight: 600; }

        .modal-close {
            width: 30px; height: 30px; background: var(--cream-dark);
            border: 1px solid var(--border-soft); color: var(--muted);
            border-radius: 8px; cursor: pointer; font-size: 12px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s; flex-shrink: 0;
        }
        .modal-close:hover { background: #fee; border-color: #fcc; color: var(--danger); }

        .modal-body { padding: 20px 22px; overflow-y: auto; flex: 1; }

        .form-group { margin-bottom: 14px; }
        .form-group label {
            display: block; font-size: 9px; letter-spacing: 0.4em;
            color: var(--muted); text-transform: uppercase; margin-bottom: 7px;
            font-weight: 600;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 11px 14px;
            background: var(--cream);
            border: 1.5px solid var(--border-soft);
            border-radius: 10px; font-size: 13px;
            color: var(--ink); font-family: var(--font-body);
            outline: none; transition: all 0.2s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: var(--gold);
            background: var(--white);
            box-shadow: 0 0 0 3px var(--gold-soft);
        }
        .form-group select option { background: var(--white); }
        .form-group textarea { resize: none; }

        .modal-footer {
            padding: 14px 22px;
            border-top: 1px solid var(--border-soft);
            display: flex; gap: 10px; flex-shrink: 0;
            background: var(--cream);
        }
        .btn-cancel {
            flex: 1; padding: 12px;
            background: var(--white); border: 1.5px solid var(--border-soft);
            border-radius: 100px; color: var(--muted);
            font-family: var(--font-body); font-size: 12px;
            cursor: pointer; transition: all 0.2s; font-weight: 600;
        }
        .btn-cancel:hover { color: var(--ink); border-color: var(--charcoal); }

        .btn-submit {
            flex: 2; padding: 12px;
            background: linear-gradient(135deg, var(--gold-deep), var(--gold));
            border: none; border-radius: 100px;
            color: var(--white); font-family: var(--font-body);
            font-size: 13px; font-weight: 700; cursor: pointer;
            transition: all 0.2s; letter-spacing: 0.04em;
            box-shadow: 0 4px 14px rgba(184,146,74,0.3);
        }
        .btn-submit:hover { box-shadow: 0 6px 20px rgba(184,146,74,0.45); transform: translateY(-1px); }
        .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        /* Export */
        .export-previews { display: grid; grid-template-columns: repeat(2,1fr); gap: 8px; margin-top: 10px; }
        .export-preview-item {
            position: relative; border: 1px solid var(--border-soft);
            border-radius: 10px; overflow: hidden; background: #ede9e0;
        }
        .export-preview-item img { width: 100%; display: block; }
        .export-preview-label {
            position: absolute; bottom: 0; left: 0; right: 0;
            text-align: center; font-size: 10px; font-weight: 700;
            color: var(--white);
            background: linear-gradient(180deg, transparent, rgba(26,22,18,0.7));
            padding: 8px 3px 3px;
            letter-spacing: 0.1em;
        }
        .export-loading {
            text-align: center; padding: 28px 0; color: var(--gold);
            font-size: 13px; display: none;
        }

        /* Success */
        .success-msg { text-align: center; padding: 28px 16px; }
        .success-icon {
            font-size: 40px; margin-bottom: 14px;
            display: block;
            animation: successPulse 0.6s ease;
        }
        @keyframes successPulse { from { transform: scale(0.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .success-msg h4 { font-family: var(--font-display); font-size: 26px; font-weight: 300; color: var(--ink); margin-bottom: 8px; }
        .success-msg h4 em { font-style: normal; color: var(--gold); font-weight: 600; }
        .success-msg p { color: var(--muted); font-size: 13px; line-height: 1.6; }
        .success-msg strong { color: var(--gold-deep); }

        /* Sidebar close button (mobile) */
        .sidebar-close {
            display: none; position: absolute; top: 14px; left: 14px;
            width: 30px; height: 30px;
            background: rgba(184,146,74,0.15); border: 1px solid rgba(184,146,74,0.25);
            color: var(--gold-light); border-radius: 8px; cursor: pointer;
            font-size: 13px; font-weight: 700;
            align-items: center; justify-content: center; z-index: 10;
            transition: all 0.2s;
        }
        .sidebar-close:hover { background: var(--gold); color: var(--ink); }

        /* ════ UPLOAD PROGRESS ════ */
        #uploadProgressBar {
            position: fixed; top: 0; left: 0; right: 0;
            height: 3px; background: var(--border-soft);
            z-index: 999999; display: none;
        }
        #uploadProgressFill {
            height: 100%; width: 0%;
            background: linear-gradient(90deg, var(--gold-deep), var(--gold-light));
            transition: width 0.3s ease;
            box-shadow: 0 0 10px var(--gold-glow);
        }
        #uploadProgressLabel {
            position: fixed; top: 10px; left: 50%; transform: translateX(-50%);
            background: var(--ink); border: 1px solid rgba(184,146,74,0.3);
            color: var(--gold-light); padding: 6px 18px; border-radius: 100px;
            font-size: 11px; font-weight: 700; letter-spacing: 0.08em;
            z-index: 999999; display: none; white-space: nowrap; pointer-events: none;
            box-shadow: var(--shadow-md);
        }

        /* Animations */
        @keyframes slideIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }
        .slide-in { animation: slideIn 0.2s ease forwards; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ════ RESPONSIVE ════ */
        @media (max-width: 1024px) { :root { --sidebar-w: 280px; } }

        @media (max-width: 900px) { :root { --sidebar-w: 268px; } }

        @media (max-width: 768px) {
            :root { --topbar-h: 52px; --bottombar-h: 68px; --sidebar-w: 300px; }
            html, body { overflow: hidden; }
            .app { flex-direction: column; height: 100dvh; overflow: hidden; }

            .sidebar {
                position: fixed; right: 0; top: 0; bottom: 0;
                width: min(320px, 90vw);
                transform: translateX(100%);
                transition: transform 0.32s cubic-bezier(0.4,0,0.2,1);
                z-index: 2000; height: 100dvh;
            }
            .sidebar.open { transform: translateX(0); box-shadow: -20px 0 60px rgba(26,22,18,0.4); }
            .sidebar.open::before {
                content: ''; position: fixed; inset: 0;
                background: rgba(0,0,0,0.5); z-index: -1;
            }
            .sidebar-toggle { display: flex; }
            .sidebar-close { display: flex; }

            .main { height: 100dvh; flex: 1; display: flex; flex-direction: column; overflow: hidden; }

            .canvas-wrap {
                flex: 1; min-height: 0; padding: 16px 12px;
                max-height: calc(100vh - var(--topbar-h) - var(--bottombar-h) - env(safe-area-inset-bottom,0px));
                overflow: hidden;
            }
            .hoodie-container {
                width: min(85vw, calc(100vh - var(--topbar-h) - var(--bottombar-h) - 32px));
                height: min(85vw, calc(100vh - var(--topbar-h) - var(--bottombar-h) - 32px));
                max-width: 400px; max-height: 400px;
            }

            .top-bar { 
                padding: 0 8px;
                gap: 2px;
                justify-content: flex-start;
                overflow: hidden;
            }
            /* scrollable buttons area */
            .top-bar-btns {
                display: flex;
                align-items: center;
                gap: 2px;
                overflow-x: auto;
                flex: 1;
                scrollbar-width: none;
                -webkit-overflow-scrolling: touch;
            }
            .top-bar-btns::-webkit-scrollbar { display: none; }
            .view-btn { 
                padding: 8px 12px; 
                font-size: 11px; 
                flex-shrink: 0;
            }
            .divider-dot { display: none; }
            .sidebar-toggle {
                position: static;
                transform: none;
                flex-shrink: 0;
                margin-right: 4px;
            }

            .bottom-bar {
                height: var(--bottombar-h) !important;
                padding: 0 12px !important;
                justify-content: center !important;
                gap: 10px;
            }
            .status-hint { display: none; }
            .bottom-actions { width: 100%; gap: 10px; }
            .save-btn, .order-btn {
                flex: 1; text-align: center; padding: 12px 10px;
                font-size: 13px; display: flex !important;
                align-items: center; justify-content: center; min-height: 48px;
                border-radius: 12px;
            }

            .logo-toolbar {
                bottom: calc(var(--bottombar-h) + 16px);
                padding: 8px 12px;
                gap: 6px;
            }
            .toolbar-btn {
                width: 44px;
                height: 44px;
                font-size: 18px;
                border-radius: 12px;
            }

            .corner { width: 20px; height: 20px; }
            .corner-tl { top: 8px; right: 8px; }
            .corner-br { bottom: 8px; left: 8px; }
            .corner-tr { top: 8px; left: 8px; }
            .corner-bl { bottom: 8px; right: 8px; }
        }

        @media (max-width: 480px) {
            :root { --topbar-h: 48px; --bottombar-h: 64px; }
            .view-btn { 
                padding: 6px 10px; 
                font-size: 10px; 
            }
            .hoodie-container {
                width: min(90vw, calc(100vh - var(--topbar-h) - var(--bottombar-h) - 24px));
                height: min(90vw, calc(100vh - var(--topbar-h) - var(--bottombar-h) - 24px));
                max-width: 350px; max-height: 350px;
            }
            .canvas-wrap { padding: 12px 8px; }
            .save-btn, .order-btn { 
                font-size: 12px; 
                padding: 10px 8px;
                min-height: 44px;
            }
            .logo-toolbar {
                bottom: calc(var(--bottombar-h) + 12px);
                padding: 6px 10px;
            }
            .toolbar-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
        }

        @media (max-width: 360px) {
            .view-btn { padding: 5px 8px; font-size: 9px; }
            .save-btn, .order-btn { font-size: 11px; padding: 8px 6px; }
            .hoodie-container {
                max-width: 300px; max-height: 300px;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .hoodie-container {
                max-width: 480px; max-height: 480px;
            }
        }

        @media (min-height: 500px) and (max-height: 600px) and (max-width: 768px) {
            .hoodie-container {
                max-width: 280px; max-height: 280px;
            }
        }

        /* Landscape mobile */
        @media (max-height: 500px) and (orientation: landscape) {
            :root { --topbar-h: 44px; --bottombar-h: 52px; }
            .hoodie-container {
                max-width: 250px; max-height: 250px;
            }
            .canvas-wrap { padding: 8px; }
            .view-btn { padding: 5px 8px; font-size: 9px; }
        }
    </style>
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

            <div class="sb-section-label">اختر لون الهودي</div>
            <div class="sections-grid" id="colorsGrid">
                @foreach($colors as $color)
                <div class="section-item {{ $color->hex_code === '#1a1a1a' ? 'active' : '' }}" 
                     data-color="{{ $color->hex_code }}" 
                     onclick="selectColorFromGrid(this)"
                     title="{{ $color->name }}">
                    <div style="width: 100%; height: 100%; border-radius: 6px; background-color: {{ $color->hex_code }}; position: relative; z-index: 1;"></div>
                </div>
                @endforeach
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
                <div class="hoodie-wrapper" id="hoodieWrapper">
                    <model-viewer
                        id="hoodieModel"
                        src="assets/3d_models/t-shirt-basic.glb"
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
    const v = deg >= 315 || deg < 45 ? 'front' : deg < 135 ? 'right' : deg < 225 ? 'back' : 'left';
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
    
    showToast('تم تغيير لون الهودي ✓');
}

function applyColorToModel(color) {
    if (!modelViewer) return;
    
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
                await new Promise(rLogo=>{
                    const lImg=new Image(); lImg.crossOrigin='anonymous';
                    lImg.onload=()=>{ ctx.save(); const lx=(d.xPercent/100)*size,ly=(d.yPercent/100)*size,lw=(d.widthPercent/100)*size,lh=(d.heightPercent/100)*size,cx=lx+lw/2,cy=ly+lh/2; ctx.translate(cx,cy); ctx.rotate((d.rotation||0)*Math.PI/180); ctx.drawImage(lImg,-lw/2,-lh/2,lw,lh); ctx.restore(); rLogo(); };
                    lImg.onerror=()=>rLogo(); lImg.src=d.src;
                });
            }
            resolve(canvas.toDataURL('image/png'));
        };
        bg.onerror=()=>resolve(bgDataUrl); bg.src=bgDataUrl;
    });
}

function blobToDataUrl(blob) {
    return new Promise((resolve,reject)=>{ const reader=new FileReader(); reader.onload=e=>resolve(e.target.result); reader.onerror=reject; reader.readAsDataURL(blob); });
}

/* ════ ORDER ════ */
function openOrderModal() {
    const all=Object.values(logosByView).flat();
    if(!all.length){showToast('من فضلك ضيف لوجو الأول!');return;}
    document.getElementById('orderModal').classList.add('open');
}

async function submitOrder() {
    const name=document.getElementById('orderName').value.trim();
    const phone=document.getElementById('orderPhone').value.trim();
    const address=document.getElementById('orderAddress').value.trim();
    const size=document.getElementById('orderSize').value;
    if(!name||!phone||!address||!size){showToast('من فضلك املأ كل الحقول');return;}
    const btn=document.getElementById('submitOrderBtn');
    document.getElementById('submitBtnText').style.display='none';
    document.getElementById('submitBtnLoader').style.display='';
    btn.disabled=true;
    const logosData=Object.values(logosByView).flat().map(l=>({src:l.src,view:l.view,x_percent:parseFloat(l.xPercent.toFixed(2)),y_percent:parseFloat(l.yPercent.toFixed(2)),width_percent:parseFloat(l.widthPercent.toFixed(2)),height_percent:parseFloat(l.heightPercent.toFixed(2)),rotation:l.rotation||0}));
    try {
        const res=await fetch('/orders',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF_TOKEN},body:JSON.stringify({name,phone,address,size,notes:document.getElementById('orderNotes').value,product:'hoodie',color:currentColor,logos:logosData})});
        const data=await res.json();
        if(data.success){
            document.getElementById('orderModalBody').innerHTML=`<div class="success-msg"><span class="success-icon">✦</span><h4>تم إرسال <em>طلبك</em></h4><p>رقم الطلب: <strong>#${data.order_id||'—'}</strong></p><p style="margin-top:6px;">هنتواصل معاك على ${phone} قريباً</p></div>`;
            document.getElementById('orderModalFooter').innerHTML=`<button class="btn-submit" onclick="closeModal('orderModal')" style="flex:1">حسناً ✓</button>`;
        } else {
            showToast(data.message||'حدث خطأ'); btn.disabled=false;
            document.getElementById('submitBtnText').style.display=''; document.getElementById('submitBtnLoader').style.display='none';
        }
    } catch(e) {
        showToast('حدث خطأ: '+e.message); btn.disabled=false;
        document.getElementById('submitBtnText').style.display=''; document.getElementById('submitBtnLoader').style.display='none';
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