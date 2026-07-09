<!DOCTYPE html>
<html lang="ar" dir="rtl" style="background:#0f172a;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="WearCraft — صمّم هوديك بنفسك بتقنية 3D. اختر اللون، أضف لوجوك، واطلب بسهولة.">
    <title>WearCraft — صمّم هوديك بنفسك</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg:        #0f172a;
            --bg2:       #1e293b;
            --bg3:       #334155;
            --bg-light:  #f8fafc;
            --bg2-light: #ffffff;
            --primary:   #6366f1;
            --primary-h: #4f46e5;
            --primary-l: #818cf8;
            --primary-xl:#a5b4fc;
            --glow:      rgba(99,102,241,0.35);
            --glow-soft: rgba(99,102,241,0.15);
            --ink:       #f8fafc;
            --ink2:      #cbd5e1;
            --ink-light: #1e293b;
            --ink2-light: #475569;
            --muted:     #64748b;
            --muted-light: #94a3b8;
            --border:    rgba(255,255,255,0.08);
            --border-light: rgba(0,0,0,0.08);
            --radius:    16px;
            --radius-lg: 28px;
        }

        body.light-mode {
            --bg:        #ffffff;
            --bg2:       #f8fafc;
            --bg3:       #e2e8f0;
            --ink:       #1e293b;
            --ink2:      #475569;
            --muted:     #64748b;
            --border:    rgba(0,0,0,0.1);
            --glow:      rgba(99,102,241,0.2);
            --glow-soft: rgba(99,102,241,0.08);
        }

        body.light-mode .nav {
            background: rgba(255,255,255,0.95);
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }

        body.light-mode .nav.scrolled {
            background: rgba(255,255,255,0.98);
        }

        body.light-mode .nav-logo {
            color: #1e293b;
        }

        body.light-mode .nav-links a {
            color: #475569;
        }

        body.light-mode .nav-links a:hover {
            color: #6366f1;
        }

        body.light-mode .feature-card {
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        }

        body.light-mode .feature-card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        body.light-mode .cta-card {
            background: #ffffff;
            border: 1px solid rgba(99,102,241,0.15);
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        body.light-mode .contact-card {
            background: #f8fafc;
            border: 1px solid rgba(0,0,0,0.08);
        }

        body.light-mode .social-pill {
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.1);
        }

        body.light-mode .how {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 50%, #ffffff 100%);
        }

        body.light-mode .contact {
            background: #f8fafc;
        }

        body.light-mode .hero h1 {
            background: linear-gradient(135deg, #1e293b 30%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        body.light-mode .hero h1 span {
            color: #6366f1;
            -webkit-text-fill-color: #6366f1;
        }

        body.light-mode .hero p {
            color: #475569;
        }

        body.light-mode .hero-badge {
            background: rgba(99,102,241,0.1);
            border: 1px solid rgba(99,102,241,0.3);
            color: #6366f1;
        }

        body.light-mode .blob-1 {
            background: radial-gradient(circle, rgba(99,102,241,0.15), transparent 70%);
        }

        body.light-mode .blob-2 {
            background: radial-gradient(circle, rgba(129,140,248,0.1), transparent 70%);
        }

        body.light-mode .hero-stats {
            gap: 48px;
        }

        body.light-mode .stat-num {
            background: linear-gradient(135deg, #1e293b, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        body.light-mode .stat-label {
            color: #64748b;
        }

        body.light-mode .section-title {
            color: #1e293b;
        }

        body.light-mode .section-desc {
            color: #475569;
        }

        body.light-mode .section-tag {
            color: #6366f1;
        }

        body.light-mode .footer {
            background: #ffffff;
            border-top: 1px solid rgba(0,0,0,0.1);
        }

        body.light-mode .footer-logo {
            color: #1e293b;
        }

        body.light-mode .footer p {
            color: #64748b;
        }

        body.light-mode .footer-links a {
            color: #64748b;
        }

        body.light-mode .footer-links a:hover {
            color: #6366f1;
        }

        /* ══ (تم نقل ستايل dark-mode-toggle القديم بالـ position:fixed لقسم جديد أسفل داخل الـ NAV ) ══ */

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--bg);
            color: var(--ink);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* ══ SCROLLBAR ══ */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--bg3); border-radius: 3px; }

        /* ══ NOISE OVERLAY ══ */
        body::before {
            content: '';
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
            background-size: 200px; opacity: 0.4;
        }

        /* ══ NAVBAR ══ */
        .nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            padding: 0 5%;
            display: flex; align-items: center; justify-content: space-between;
            height: 64px;
            background: rgba(15,23,42,0.8);
            backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            transition: all 0.3s;
        }
        .nav.scrolled { background: rgba(15,23,42,0.95); }

        .nav-logo {
            font-family: 'Playfair Display', serif;
            font-size: 22px; font-weight: 700;
            color: var(--ink); text-decoration: none;
            letter-spacing: 0.05em;
            flex-shrink: 0;
        }
        .nav-logo em { font-style: normal; color: var(--primary-xl); }

        /* ══ NAV RIGHT WRAPPER (يجمع الروابط + CTA + زر الوضع + الهامبرجر) ══ */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-links {
            display: flex; align-items: center; gap: 32px; list-style: none;
        }
        .nav-links a {
            text-decoration: none; color: var(--ink2);
            font-size: 14px; font-weight: 600;
            transition: color 0.2s; letter-spacing: 0.03em;
        }
        .nav-links a:hover { color: var(--ink); }

        .nav-cta {
            text-decoration: none;
            background: var(--primary);
            color: #fff; font-weight: 700; font-size: 14px;
            padding: 10px 24px; border-radius: 100px;
            transition: all 0.25s;
            box-shadow: 0 4px 16px var(--glow);
            white-space: nowrap;
        }
        .nav-cta:hover { background: var(--primary-h); transform: translateY(-1px); box-shadow: 0 6px 24px var(--glow); }

        /* ══ DARK MODE TOGGLE (بقى عنصر flex عادي جوه NAV مش fixed) ══ */
        .dark-mode-toggle {
            position: relative;
            width: clamp(38px, 9vw, 44px);
            height: clamp(38px, 9vw, 44px);
            border-radius: 50%;
            background: var(--bg2);
            border: 2px solid var(--primary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            padding: 0;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .dark-mode-toggle:hover {
            transform: scale(1.08);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }
        .dark-mode-toggle:active { transform: scale(0.95); }

        .dmt-icon {
            position: absolute;
            font-size: clamp(16px, 4vw, 20px);
            line-height: 1;
            transition: opacity 0.3s ease, transform 0.4s cubic-bezier(0.4,0,0.2,1);
        }
        .dmt-moon { opacity: 1; transform: rotate(0deg) scale(1); }
        .dmt-sun  { opacity: 0; transform: rotate(-90deg) scale(0.5); }

        body.light-mode .dmt-moon { opacity: 0; transform: rotate(90deg) scale(0.5); }
        body.light-mode .dmt-sun  { opacity: 1; transform: rotate(0deg) scale(1); }

        body.light-mode .dark-mode-toggle {
            background: #ffffff;
            border-color: #6366f1;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .nav-hamburger {
            display: none; flex-direction: column; gap: 5px;
            cursor: pointer; padding: 4px; flex-shrink: 0;
        }
        .nav-hamburger span {
            display: block; width: 22px; height: 2px;
            background: var(--ink2); border-radius: 2px;
            transition: all 0.3s;
        }
        .nav-mobile {
            display: none; position: fixed; inset: 0; z-index: 99;
            background: rgba(15,23,42,0.98);
            flex-direction: column; align-items: center; justify-content: center;
            gap: 32px;
        }
        .nav-mobile.open { display: flex; }
        .nav-mobile a {
            text-decoration: none; color: var(--ink2);
            font-size: 20px; font-weight: 700;
            transition: color 0.2s;
        }
        .nav-mobile a:hover { color: var(--primary-xl); }
        .nav-mobile-close {
            position: absolute; top: 20px; left: 5%;
            font-size: 24px; color: var(--muted); cursor: pointer;
            background: none; border: none;
        }

        /* ══ HERO ══ */
        .hero {
            min-height: 100vh;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 100px 5% 60px;
            position: relative; overflow: hidden;
            text-align: center;
        }

        /* Gradient blobs */
        .blob {
            position: absolute; border-radius: 50%;
            filter: blur(80px); pointer-events: none; z-index: 0;
            animation: float 8s ease-in-out infinite;
        }
        .blob-1 {
            width: clamp(300px,40vw,600px); height: clamp(300px,40vw,600px);
            background: radial-gradient(circle, rgba(99,102,241,0.25), transparent 70%);
            top: -10%; right: -10%;
        }
        .blob-2 {
            width: clamp(200px,30vw,400px); height: clamp(200px,30vw,400px);
            background: radial-gradient(circle, rgba(129,140,248,0.15), transparent 70%);
            bottom: 5%; left: -5%;
            animation-delay: -4s;
        }
        @keyframes float {
            0%,100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        /* Dot grid */
        .hero::after {
            content: '';
            position: absolute; inset: 0; pointer-events: none;
            background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 32px 32px;
            mask-image: radial-gradient(ellipse at center, black 30%, transparent 80%);
        }

        .hero-content { position: relative; z-index: 1; max-width: 760px; }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--glow-soft); border: 1px solid rgba(99,102,241,0.3);
            border-radius: 100px; padding: 6px 16px;
            font-size: 13px; font-weight: 600; color: var(--primary-xl);
            margin-bottom: 28px; letter-spacing: 0.05em;
        }
        .hero-badge-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--primary-xl);
            box-shadow: 0 0 8px var(--primary-xl);
            animation: pulse-dot 2s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%,100% { opacity: 1; } 50% { opacity: 0.4; }
        }

        .hero h1 {
            font-size: clamp(36px, 6vw, 72px);
            font-weight: 900; line-height: 1.15;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #f8fafc 30%, #a5b4fc 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero h1 span { color: var(--primary-xl); -webkit-text-fill-color: var(--primary-xl); }

        .hero p {
            font-size: clamp(15px, 2vw, 20px);
            color: var(--ink2); margin-bottom: 40px;
            max-width: 560px; margin-left: auto; margin-right: auto;
        }

        .hero-actions { display: flex; gap: 16px; flex-wrap: wrap; justify-content: center; }

        .btn-primary {
            text-decoration: none;
            background: var(--primary); color: #fff;
            font-weight: 700; font-size: 16px;
            padding: 16px 40px; border-radius: 100px;
            transition: all 0.3s;
            box-shadow: 0 8px 32px var(--glow);
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-primary:hover { background: var(--primary-h); transform: translateY(-2px); box-shadow: 0 12px 40px var(--glow); }

        .btn-ghost {
            text-decoration: none;
            border: 1px solid var(--border);
            color: var(--ink2); font-weight: 600; font-size: 16px;
            padding: 16px 32px; border-radius: 100px;
            transition: all 0.3s; background: transparent;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-ghost:hover { border-color: var(--primary-l); color: var(--ink); background: var(--glow-soft); }

        .hero-stats {
            display: flex; gap: 48px; justify-content: center;
            margin-top: 64px; flex-wrap: wrap;
            position: relative; z-index: 1;
        }
        .stat { text-align: center; }
        .stat-num {
            font-size: clamp(28px,4vw,40px); font-weight: 900;
            color: var(--ink);
            background: linear-gradient(135deg, var(--ink), var(--primary-xl));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .stat-label { font-size: 13px; color: var(--muted); font-weight: 600; margin-top: 2px; }
        .stat-divider { width: 1px; background: var(--border); align-self: stretch; }

        /* ══ SECTION COMMON ══ */
        section { position: relative; z-index: 1; }

        .section-wrap { max-width: 1200px; margin: 0 auto; padding: 0 5%; }

        .section-tag {
            display: inline-block;
            font-size: 11px; font-weight: 700; letter-spacing: 0.2em;
            color: var(--primary-l); text-transform: uppercase;
            margin-bottom: 14px;
        }
        .section-title {
            font-size: clamp(26px,4vw,44px); font-weight: 900;
            line-height: 1.2; color: var(--ink); margin-bottom: 16px;
        }
        .section-desc {
            font-size: 16px; color: var(--ink2); max-width: 520px; margin-bottom: 56px;
        }

        /* ══ FEATURES ══ */
        .features { padding: 100px 0; }

        .features-header { text-align: center; }
        .features-header .section-desc { margin-left: auto; margin-right: auto; }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px,1fr));
            gap: 20px; margin-top: 56px;
        }

        .feature-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 36px 28px;
            transition: all 0.35s cubic-bezier(0.4,0,0.2,1);
            position: relative; overflow: hidden;
        }
        .feature-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, var(--primary-l), transparent);
            opacity: 0; transition: opacity 0.35s;
        }
        .feature-card:hover { border-color: rgba(99,102,241,0.3); transform: translateY(-6px); box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .feature-card:hover::before { opacity: 1; }

        .feature-icon-wrap {
            width: 52px; height: 52px;
            background: var(--glow-soft);
            border: 1px solid rgba(99,102,241,0.2);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; margin-bottom: 20px;
        }
        .feature-card h3 { font-size: 18px; font-weight: 700; color: var(--ink); margin-bottom: 10px; }
        .feature-card p { font-size: 14px; color: var(--ink2); line-height: 1.7; }

        /* ══ HOW IT WORKS ══ */
        .how { padding: 100px 0; background: linear-gradient(180deg, var(--bg) 0%, var(--bg2) 50%, var(--bg) 100%); }
        .how .section-wrap { display: flex; gap: 80px; align-items: center; flex-wrap: wrap; }
        .how-text { flex: 1; min-width: 280px; }
        .how-steps { flex: 1; min-width: 280px; display: flex; flex-direction: column; gap: 0; }

        .how-step {
            display: flex; gap: 20px; align-items: flex-start;
            padding: 24px 0;
            position: relative;
        }
        .how-step:not(:last-child)::after {
            content: '';
            position: absolute; left: auto; right: 19px; top: 60px; bottom: -8px;
            width: 2px; background: linear-gradient(180deg, var(--primary), transparent);
        }
        html[dir="rtl"] .how-step:not(:last-child)::after { right: 19px; left: auto; }

        .how-num {
            width: 40px; height: 40px; flex-shrink: 0;
            background: var(--glow-soft); border: 1px solid rgba(99,102,241,0.3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 800; color: var(--primary-xl);
        }
        .how-step-text h4 { font-size: 17px; font-weight: 700; color: var(--ink); margin-bottom: 6px; }
        .how-step-text p { font-size: 14px; color: var(--ink2); line-height: 1.7; }

        /* ══ CTA ══ */
        .cta-section {
            padding: 100px 5%;
            text-align: center;
            position: relative; overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at center, rgba(99,102,241,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .cta-card {
            max-width: 680px; margin: 0 auto;
            background: var(--bg2);
            border: 1px solid rgba(99,102,241,0.2);
            border-radius: var(--radius-lg);
            padding: clamp(40px,6vw,72px) clamp(24px,5vw,64px);
            position: relative; z-index: 1;
            box-shadow: 0 0 60px rgba(99,102,241,0.1), 0 40px 80px rgba(0,0,0,0.3);
        }
        .cta-card h2 { font-size: clamp(26px,4vw,40px); font-weight: 900; color: var(--ink); margin-bottom: 14px; }
        .cta-card p { font-size: 16px; color: var(--ink2); margin-bottom: 36px; }

        /* ══ FOOTER ══ */
        .footer {
            padding: 32px 5%;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 16px;
        }
        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-size: 18px; font-weight: 700; color: var(--ink);
        }
        .footer-logo em { font-style: normal; color: var(--primary-xl); }
        .footer p { font-size: 13px; color: var(--muted); }
        .footer-links { display: flex; gap: 24px; list-style: none; }
        .footer-links a { text-decoration: none; font-size: 13px; color: var(--muted); transition: color 0.2s; }
        .footer-links a:hover { color: var(--ink); }

        /* ══ ANIMATIONS ══ */
        .reveal {
            opacity: 0; transform: translateY(30px);
            transition: opacity 0.7s cubic-bezier(0.4,0,0.2,1), transform 0.7s cubic-bezier(0.4,0,0.2,1);
        }
        .reveal.visible { opacity: 1; transform: none; }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-3 { transition-delay: 0.4s; }

        /* ══ RESPONSIVE ══ */
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .nav-cta { display: none; }
            .nav-hamburger { display: flex; }
            .nav-right { gap: 12px; }

            .hero-stats { gap: 28px; }
            .stat-divider { display: none; }

            .how .section-wrap { flex-direction: column; gap: 48px; }
            .how-text { text-align: center; }
            .how-text .section-desc { margin-left: auto; margin-right: auto; }

            .footer { flex-direction: column; text-align: center; }
            .footer-links { justify-content: center; }
        }

        @media (max-width: 480px) {
            .hero-actions { flex-direction: column; align-items: center; }
            .btn-primary, .btn-ghost { width: 100%; max-width: 300px; justify-content: center; }
            .features-grid { grid-template-columns: 1fr; }
            .nav-right { gap: 8px; }
        }

        /* ══ HERO SPLIT ══ */
        .hero { text-align: right; }
        .hero-inner {
            display: flex; align-items: center; gap: 60px;
            max-width: 1100px; width: 100%;
            position: relative; z-index: 1;
        }
        .hero-content { flex: 1; min-width: 0; max-width: none; }
        .hero-img-wrap {
            flex: 1; min-width: 0;
            position: relative; display: flex; align-items: center; justify-content: center;
        }
        .hero-img-wrap::before {
            content: '';
            position: absolute; inset: -20px;
            background: radial-gradient(circle, rgba(99,102,241,0.2), transparent 70%);
            border-radius: 50%; filter: blur(30px);
        }
        .hero-img {
            width: 100%; max-width: 524px;
            border-radius: 20px;
            box-shadow: 0 32px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05);
            position: relative; z-index: 1;
            animation: imgFloat 6s ease-in-out infinite;
        }
        @keyframes imgFloat {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }
        .hero-stats { justify-content: flex-start; }

        @media (max-width: 900px) {
            .hero { text-align: center; }
            .hero-inner { flex-direction: column-reverse; gap: 40px; }
            .hero-img { max-width: 320px; }
            .hero-stats { justify-content: center; }
        }

        /* ══ CONTACT ══ */
        .contact { padding: 100px 0; background: var(--bg2); }
        .contact .section-wrap { display: flex; gap: 80px; align-items: center; flex-wrap: wrap; }
        .contact-text { flex: 1; min-width: 260px; }
        .contact-cards { flex: 1; min-width: 260px; display: flex; flex-direction: column; gap: 16px; }

        .contact-card {
            display: flex; align-items: center; gap: 18px;
            background: var(--bg); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 20px 24px;
            text-decoration: none; color: inherit;
            transition: all 0.3s;
        }
        .contact-card:hover { border-color: rgba(99,102,241,0.4); transform: translateX(-4px); box-shadow: 0 8px 32px rgba(0,0,0,0.2); }
        .contact-icon {
            width: 48px; height: 48px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 22px;
        }
        .contact-icon.whatsapp { background: rgba(37,211,102,0.15); }
        .contact-icon.instagram { background: rgba(225,48,108,0.15); }
        .contact-icon.tiktok { background: rgba(255,255,255,0.06); }
        .contact-icon.facebook { background: rgba(24,119,242,0.15); }
        .contact-icon.phone { background: var(--glow-soft); }
        .contact-card-label { font-size: 12px; color: var(--muted); font-weight: 600; margin-bottom: 2px; }
        .contact-card-value { font-size: 15px; font-weight: 700; color: var(--ink); }

        .social-row {
            display: flex; gap: 12px; margin-top: 24px; flex-wrap: wrap;
        }
        .social-pill {
            display: flex; align-items: center; gap: 8px;
            padding: 10px 18px; border-radius: 100px;
            border: 1px solid var(--border); background: var(--bg);
            text-decoration: none; color: var(--ink2);
            font-size: 13px; font-weight: 600;
            transition: all 0.25s;
        }
        .social-pill:hover { border-color: var(--primary-l); color: var(--ink); background: var(--glow-soft); }

        @media (max-width: 768px) {
            .contact .section-wrap { flex-direction: column; gap: 40px; }
            .contact-text { text-align: center; }
            .social-row { justify-content: center; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="nav" id="navbar">
    <a href="#" class="nav-logo">Wear<em>C</em>raft</a>

    <div class="nav-right">
        <ul class="nav-links">
            <li><a href="#features">المميزات</a></li>
            <li><a href="#how">كيف يعمل</a></li>
            <li><a href="#contact">تواصل معنا</a></li>
        </ul>
        <a href="{{ route('designer') }}" class="nav-cta">ابدأ التصميم</a>

        <!-- DARK MODE TOGGLE — دلوقتي جوه الـ nav، متمركز تلقائيًا وجنب الهامبرجر بمسافة ثابتة -->
        <button class="dark-mode-toggle" onclick="toggleDarkMode()" title="تبديل الوضع" aria-label="تبديل الوضع الليلي والنهاري">
            <span class="dmt-icon dmt-moon">🌙</span>
            <span class="dmt-icon dmt-sun">☀️</span>
        </button>

        <div class="nav-hamburger" id="hamburger" onclick="openMobile()">
            <span></span><span></span><span></span>
        </div>
    </div>
</nav>

<!-- MOBILE MENU -->
<div class="nav-mobile" id="mobileMenu">
    <button class="nav-mobile-close" onclick="closeMobile()">✕</button>
    <a href="#features" onclick="closeMobile()">المميزات</a>
    <a href="#how" onclick="closeMobile()">كيف يعمل</a>
    <a href="#contact" onclick="closeMobile()">تواصل معنا</a>
    <a href="{{ route('designer') }}" class="btn-primary" style="margin-top:8px;">ابدأ التصميم ←</a>
</div>

<!-- HERO -->
<section class="hero">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-badge reveal">
                <span class="hero-badge-dot"></span>
                تقنية 3D تفاعلية
            </div>
            <h1 class="reveal reveal-delay-1">
                صمّم هوديك<br>
                <span>بنفسك</span>
            </h1>
            <p class="reveal reveal-delay-2">
                أداة تصميم احترافية تمكّنك من اختيار اللون وإضافة لوجوك ومعاينة التصميم بتقنية ثلاثية الأبعاد — كل ده في ثوانٍ.
            </p>
            <div class="hero-actions reveal reveal-delay-3">
                <a href="{{ route('designer') }}" class="btn-primary">
                    ابدأ التصميم الآن ←
                </a>
                <a href="#features" class="btn-ghost">
                    اعرف أكتر ↓
                </a>
            </div>

            <div class="hero-stats reveal reveal-delay-3">
                <div class="stat">
                    <div class="stat-num">3D</div>
                    <div class="stat-label">معاينة فورية</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat">
                    <div class="stat-num">∞</div>
                    <div class="stat-label">خيارات ألوان</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat">
                    <div class="stat-num">100%</div>
                    <div class="stat-label">تصميمك فريد</div>
                </div>
            </div>
        </div>

        <div class="hero-img-wrap reveal reveal-delay-2">
            <img src="{{ asset('assets/img/scrin.png') }}" alt="واجهة مصمم WearCraft" class="hero-img">
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="features" id="features">
    <div class="section-wrap">
        <div class="features-header">
            <span class="section-tag reveal">لماذا WearCraft؟</span>
            <h2 class="section-title reveal reveal-delay-1">كل اللي تحتاجه في مكان واحد</h2>
            <p class="section-desc reveal reveal-delay-2">من الفكرة للتصميم للطلب — تجربة سلسة وممتعة بدون تعقيد</p>
        </div>
        <div class="features-grid">
            <div class="feature-card reveal reveal-delay-1">
                <div class="feature-icon-wrap">🎨</div>
                <h3>تصميم سهل وسريع</h3>
                <p>واجهة بسيطة تخليك تعمل تصميم احترافي في دقائق بدون أي خبرة سابقة</p>
            </div>
            <div class="feature-card reveal reveal-delay-2">
                <div class="feature-icon-wrap">🧊</div>
                <h3>معاينة 3D فورية</h3>
                <p>شوف تصميمك على الهودي بتقنية ثلاثية الأبعاد من كل الاتجاهات قبل ما تطلب</p>
            </div>
            <div class="feature-card reveal reveal-delay-3">
                <div class="feature-icon-wrap">✏️</div>
                <h3>كتابة مخصصة</h3>
                <p>اكتب كلمتك الخاصة واختر لونها لإضافتها على تيشيرتك بشكل فريد</p>
            </div>
            <div class="feature-card reveal reveal-delay-3">
                <div class="feature-icon-wrap">🖼️</div>
                <h3>لوجوك أو من مكتبتنا</h3>
                <p>ارفع لوجوك الخاص أو اختر من مكتبة اللوجوهات المتاحة بكل حرية</p>
            </div>
            <div class="feature-card reveal reveal-delay-1">
                <div class="feature-icon-wrap">🎨</div>
                <h3>ألوان متنوعة</h3>
                <p>اختر من مجموعة واسعة من الألوان المتاحة لتيشيرتك</p>
            </div>
            <div class="feature-card reveal reveal-delay-2">
                <div class="feature-icon-wrap">🚀</div>
                <h3>طلب سريع وسهل</h3>
                <p>بعد ما تخلص التصميم، اطلب بسهولة وهنتواصل معاك في أسرع وقت</p>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="how" id="how">
    <div class="section-wrap">
        <div class="how-text">
            <span class="section-tag reveal">الخطوات</span>
            <h2 class="section-title reveal reveal-delay-1">كيف يعمل؟</h2>
            <p class="section-desc reveal reveal-delay-2">أربع خطوات بسيطة توصّلك لهوديك المميز</p>
            <a href="{{ route('designer') }}" class="btn-primary reveal reveal-delay-3" style="margin-top:8px; display:inline-flex;">
                جرّب دلوقتي ←
            </a>
        </div>
        <div class="how-steps">
            <div class="how-step reveal">
                <div class="how-num">١</div>
                <div class="how-step-text">
                    <h4>اختر اللون</h4>
                    <p>اختر لون الهودي المفضل ليك من مجموعة ألوان متنوعة ومتجددة</p>
                </div>
            </div>
            <div class="how-step reveal reveal-delay-1">
                <div class="how-num">٢</div>
                <div class="how-step-text">
                    <h4>أضف اللوجو</h4>
                    <p>ارفع لوجوك أو اختر من المكتبة، وضعّه على الهودي زي ما تحب</p>
                </div>
            </div>
            <div class="how-step reveal reveal-delay-2">
                <div class="how-num">٣</div>
                <div class="how-step-text">
                    <h4>اكتب كلمتك</h4>
                    <p>أضف كلمة خاصة بك واختر لونها لتظهر على تيشيرتك</p>
                </div>
            </div>
            <div class="how-step reveal reveal-delay-3">
                <div class="how-num">٤</div>
                <div class="how-step-text">
                    <h4>ضبط التصميم</h4>
                    <p>تحكم في الحجم والموضع مع معاينة 3D فورية من كل زاوية</p>
                </div>
            </div>
            <div class="how-step reveal reveal-delay-3">
                <div class="how-num">٥</div>
                <div class="how-step-text">
                    <h4>اطلب الآن</h4>
                    <p>أكمل طلبك بسهولة وهنتواصل معاك ونوصّلك تصميمك</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-card reveal">
        <h2>جاهز تبدأ؟</h2>
        <p>انضم لآلاف العملاء اللي صمّموا هوديهم الخاص مع WearCraft</p>
        <a href="{{ route('designer') }}" class="btn-primary" style="font-size:17px; padding:18px 48px;">
            ابدأ التصميم المجاني ←
        </a>
    </div>
</section>

<!-- CONTACT -->
<section class="contact" id="contact">
    <div class="section-wrap">
        <div class="contact-text">
            <span class="section-tag reveal">تواصل معنا</span>
            <h2 class="section-title reveal reveal-delay-1">نحن هنا لمساعدتك</h2>
            <p class="section-desc reveal reveal-delay-2">عندك سؤال أو عايز تتواصل معنا، تقدر توصللنا على أي منصة تختارها</p>

            <div class="social-row reveal reveal-delay-3">
                <a href="https://wa.me/201101336383" target="_blank" class="social-pill">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#25d366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    واتساب
                </a>
                <a href="https://instagram.com/wearcraft" target="_blank" class="social-pill">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="url(#ig)"><defs><linearGradient id="ig" x1="0" y1="1" x2="1" y2="0"><stop offset="0%" stop-color="#f09433"/><stop offset="25%" stop-color="#e6683c"/><stop offset="50%" stop-color="#dc2743"/><stop offset="75%" stop-color="#cc2366"/><stop offset="100%" stop-color="#bc1888"/></linearGradient></defs><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    انستجرام
                </a>
                <a href="https://facebook.com/wearcraft" target="_blank" class="social-pill">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#1877f2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    فيسبوك
                </a>
            </div>
        </div>

        <div class="contact-cards">
            <a href="tel:+201000000000" class="contact-card reveal">
                <div class="contact-icon phone">📞</div>
                <div>
                    <div class="contact-card-label">اتصل بينا</div>
                    <div class="contact-card-value">201101336383+</div>
                </div>
            </a>
            <a href="https://wa.me/201000000000" target="_blank" class="contact-card reveal reveal-delay-1">
                <div class="contact-icon whatsapp">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="#25d366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                </div>
                <div>
                    <div class="contact-card-label">واتساب</div>
                    <div class="contact-card-value">201101336383+</div>
                </div>
            </a>
            <a href="https://instagram.com/wearcraft" target="_blank" class="contact-card reveal reveal-delay-2">
                <div class="contact-icon instagram">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="url(#ig2)"><defs><linearGradient id="ig2" x1="0" y1="1" x2="1" y2="0"><stop offset="0%" stop-color="#f09433"/><stop offset="100%" stop-color="#bc1888"/></linearGradient></defs><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                </div>
                <div>
                    <div class="contact-card-label">انستجرام</div>
                    <div class="contact-card-value">@wearcraft</div>
                </div>
            </a>
            <a href="https://facebook.com/wearcraft" target="_blank" class="contact-card reveal reveal-delay-3">
                <div class="contact-icon facebook">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="#1877f2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </div>
                <div>
                    <div class="contact-card-label">فيسبوك</div>
                    <div class="contact-card-value">WearCraft</div>
                </div>
            </a>
            <a href="https://tiktok.com/@wearcraft" target="_blank" class="contact-card reveal reveal-delay-4">
                <div class="contact-icon tiktok">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="#fff"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V9.49a8.16 8.16 0 004.77 1.52V7.57a4.85 4.85 0 01-1-.88z"/></svg>
                </div>
                <div>
                    <div class="contact-card-label">تيك توك</div>
                    <div class="contact-card-value">@wearcraft</div>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-logo">Wear<em>C</em>raft</div>
    <p>© 2024 WearCraft. جميع الحقوق محفوظة</p>
    <ul class="footer-links">
        <li><a href="#features">المميزات</a></li>
        <li><a href="#how">كيف يعمل</a></li>
        <li><a href="#contact">تواصل معنا</a></li>
    </ul>
</footer>

<script>
    // ══ Dark Mode Toggle (مبسّط: بس بيبدّل الكلاس، الأيقونات بتتبدّل بالـ CSS بأنيميشن) ══
    function toggleDarkMode() {
        document.body.classList.toggle('light-mode');
        localStorage.setItem('lightMode', document.body.classList.contains('light-mode'));
    }

    // استرجاع تفضيل الوضع المحفوظ
    if (localStorage.getItem('lightMode') === 'true') {
        document.body.classList.add('light-mode');
    }

    // Mobile Menu
    function openMobile() {
        document.getElementById('mobileMenu').classList.add('open');
    }

    function closeMobile() {
        document.getElementById('mobileMenu').classList.remove('open');
    }

    // Navbar Scroll Effect
    window.addEventListener('scroll', function() {
        const nav = document.getElementById('navbar');
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });

    // Reveal Animations
    function reveal() {
        const reveals = document.querySelectorAll('.reveal');
        reveals.forEach(element => {
            const windowHeight = window.innerHeight;
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;

            if (elementTop < windowHeight - elementVisible) {
                element.classList.add('visible');
            }
        });
    }

    window.addEventListener('scroll', reveal);
    reveal(); // Initial check
</script>
</body>
</html>