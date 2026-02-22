<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>مصمم الهودي 3D</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
            display: flex;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 0;
            overflow-y: auto;
            z-index: 10;
            transition: transform 0.3s ease;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
            position: relative;
            border-left: 1px solid rgba(255, 255, 255, 0.3);
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 15px 20px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .sidebar-header h2 {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
            color: white;
        }

        .sidebar-close {
            display: none;
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.4);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            line-height: 1;
        }

        .sidebar-close:hover {
            background: rgba(255,255,255,0.35);
        }

        @media (max-width: 768px) {
            .sidebar-close {
                display: flex;
            }
        }

        .sidebar-content {
            padding: 15px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .instructions-box {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.08) 100%);
            border: 1px solid rgba(102, 126, 234, 0.3);
            border-radius: 12px;
            padding: 12px;
            font-size: 11px;
            line-height: 1.6;
            color: #333;
        }

        .instructions-box p {
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .instructions-box p:last-child {
            margin-bottom: 0;
        }

        /* Toggle sidebar button */
        .sidebar-toggle {
            position: fixed;
            right: 20px;
            top: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 28px rgba(102, 126, 234, 0.5);
        }

        .sidebar-toggle:active {
            transform: scale(0.95);
        }

        /* Main area */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: transparent;
            position: relative;
            padding: 20px;
        }

        /* View controls */
        .view-controls {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 100;
            flex-wrap: wrap;
            justify-content: center;
            max-width: 90%;
            background: rgba(255, 255, 255, 0.95);
            padding: 8px;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .view-btn {
            background: white;
            color: #333;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
        }

        .view-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.15), transparent);
            transition: left 0.5s;
        }

        .view-btn:hover::before {
            left: 100%;
        }

        .view-btn:hover {
            background: #f8f8f8;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(102, 126, 234, 0.2);
        }

        .view-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(102, 126, 234, 0.4);
            transform: translateY(-2px);
        }
        
        #gridViewBtn.active {
            background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%) !important;
            box-shadow: 0 4px 16px rgba(156, 39, 176, 0.4) !important;
        }
        
        #freeControlBtn.active {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%) !important;
            box-shadow: 0 4px 16px rgba(244, 67, 54, 0.4) !important;
        }
        
        #previewBtn.active {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%) !important;
            box-shadow: 0 4px 16px rgba(244, 67, 54, 0.4) !important;
        }

        /* Hoodie container */
        .hoodie-container {
            width: 100%;
            max-width: 600px;
            height: 600px;
            position: relative;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(31, 38, 135, 0.2);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .hoodie-container.grid-view {
            max-width: 95%;
            width: 95%;
            height: 80vh;
            max-height: 900px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .hoodie-grid {
            display: none;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            padding: 20px;
            width: 100%;
            min-height: 100%;
        }

        .hoodie-container.grid-view .hoodie-grid {
            display: grid;
        }

        .hoodie-container.grid-view .hoodie-wrapper {
            display: none;
        }

        .grid-item {
            position: relative;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 15px;
            overflow: hidden;
            width: 100%;
            height: 0;
            padding-bottom: 100%;
        }

        .grid-item-content {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .grid-item-label {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            z-index: 100;
            box-shadow: 0 2px 12px rgba(102, 126, 234, 0.3);
        }

        .grid-item-content model-viewer {
            width: 100%;
            height: 100%;
            border-radius: 15px;
            --poster-color: transparent;
        }

        .grid-item-content .logos-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 10;
            border-radius: 15px;
        }

        .grid-item-content .logo-on-hoodie {
            position: absolute;
        }

        .hoodie-wrapper {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .hoodie-wrapper.drag-over::after {
            content: 'أفلت هنا';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 4px dashed #667eea;
            border-radius: 20px;
            pointer-events: none;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(102, 126, 234, 0.1);
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }

        model-viewer {
            width: 100%;
            height: 100%;
            border-radius: 20px;
        }

        .logos-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 10;
            border-radius: 20px;
        }

        body.free-control-active .logo-on-hoodie {
            pointer-events: none !important;
            cursor: default !important;
        }

        body.free-control-active .logo-on-hoodie .delete-btn,
        body.free-control-active .logo-on-hoodie .resize-handle {
            display: none !important;
        }

        body.free-control-active .logo-on-hoodie img {
            border: none !important;
        }

        .logo-on-hoodie {
            position: absolute;
            pointer-events: auto;
            cursor: move;
            user-select: none;
            opacity: 0;
            transition: opacity 0.4s ease-in-out;
            will-change: width, height;
            transform: translateZ(0);
        }

        .logo-on-hoodie.active {
            opacity: 1;
        }

        .logo-on-hoodie.selected {
            outline: 3px solid #667eea;
            outline-offset: 3px;
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.5);
        }

        .logo-on-hoodie img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            pointer-events: none;
            border: 2px dashed transparent;
            transition: border 0.2s;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.3));
        }

        .logo-on-hoodie:hover img,
        .logo-on-hoodie.dragging img {
            border: 2px dashed #667eea;
        }

        .logo-on-hoodie .delete-btn {
            position: absolute;
            top: -12px;
            right: -12px;
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
            color: white;
            border: 3px solid white;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            will-change: opacity;
        }

        @media (min-width: 769px) {
            .logo-on-hoodie:hover .delete-btn {
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .logo-on-hoodie.selected .delete-btn {
                opacity: 1;
            }
            
            .logo-on-hoodie:not(.selected) .delete-btn {
                opacity: 0;
            }
        }

        .logo-on-hoodie .resize-handle {
            position: absolute;
            bottom: -12px;
            right: -12px;
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: 3px solid white;
            border-radius: 50%;
            cursor: nwse-resize;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 10;
            box-shadow: 0 2px 12px rgba(102, 126, 234, 0.4);
            will-change: opacity;
        }

        @media (min-width: 769px) {
            .logo-on-hoodie:hover .resize-handle {
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .logo-on-hoodie.selected .resize-handle {
                opacity: 1;
            }
            
            .logo-on-hoodie:not(.selected) .resize-handle {
                opacity: 0;
            }
        }

        .drag-preview {
            position: fixed;
            width: 80px;
            pointer-events: none;
            z-index: 9999;
            opacity: 0.7;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.5));
        }

        /* Mobile toolbar */
        .logo-toolbar {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 20px;
            padding: 12px 16px;
            display: none;
            gap: 10px;
            z-index: 1000;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.2);
            backdrop-filter: blur(20px);
        }

        .logo-toolbar.active {
            display: flex;
        }

        body.free-control-active .logo-toolbar {
            display: none !important;
        }

        .toolbar-btn {
            background: white;
            color: #667eea;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            width: 55px;
            height: 55px;
            cursor: pointer;
            font-size: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .toolbar-btn:active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
            transform: scale(0.95);
        }

        .toolbar-btn.danger {
            color: #f44336;
            border-color: #ffebee;
        }

        .toolbar-btn.danger:active {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
            color: white;
            border-color: transparent;
        }

        /* Status bar */
        .status-bar {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.95);
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 13px;
            color: #667eea;
            z-index: 50;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(102, 126, 234, 0.2);
            box-shadow: 0 4px 16px rgba(31, 38, 135, 0.1);
        }

        /* Mobile styles */
        @media (max-width: 768px) {
            .sidebar-toggle {
                width: 36px;
                height: 36px;
                font-size: 16px;
                top: 12px;
                right: 10px;
                box-shadow: 0 2px 10px rgba(102, 126, 234, 0.35);
            }

            .sidebar {
                position: fixed;
                right: 0;
                top: 0;
                height: 100vh;
                width: 100%;
                max-width: 100%;
                transform: translateX(100%);
                z-index: 2000;
            }

            .sidebar.open {
                transform: translateX(0);
            }
            
            .sidebar.open::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.7);
                z-index: -1;
            }
            
            .sidebar.open ~ .main .view-controls {
                display: none;
            }

            .sidebar-toggle {
                display: flex;
            }

            .hoodie-container {
                max-width: 100%;
                height: 500px;
                margin-top: 110px;
            }

            .hoodie-container.grid-view {
                height: 90vh;
            }

            .hoodie-grid {
                grid-template-columns: 1fr;
                gap: 15px;
                padding: 15px;
            }

            .grid-item {
                padding-bottom: 100%;
            }

            .grid-item-label {
                font-size: 12px;
                padding: 5px 14px;
            }

            .view-controls {
                top: 55px;
                gap: 5px;
                padding: 8px;
                border-radius: 12px;
                width: 96%;
                max-width: 96%;
                display: grid;
                grid-template-columns: repeat(4, 1fr);
            }

            .view-btn {
                padding: 8px 2px;
                font-size: 9px;
                border-radius: 8px;
                box-shadow: none;
                text-align: center;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .status-bar {
                font-size: 11px;
                padding: 10px 20px;
                max-width: 90%;
            }

            .logo-toolbar {
                bottom: 15px;
                gap: 8px;
                padding: 10px 14px;
            }

            .toolbar-btn {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }

        @media (min-width: 769px) {
            .sidebar-toggle {
                display: none;
            }
        }

        /* ===== SECTIONS STYLES ===== */
        .sections-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 columns = أصغر */
            gap: 8px;
            margin-bottom: 12px;
        }

        .section-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 8px 4px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            cursor: pointer;
            background: white;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .section-item:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(102, 126, 234, 0.2);
        }

        .section-item.active {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));
            box-shadow: 0 3px 12px rgba(102, 126, 234, 0.25);
        }

        /* صورة فقط بدون اسم */
        .section-item img {
            width: 38px;
            height: 38px;
            object-fit: contain;
        }

        /* ===== LOGOS SECTION ===== */
        /* الأقسام + اللوجوهات تتعرض مع بعض */
        .logos-section {
            border-top: 1px solid rgba(102,126,234,0.15);
            padding-top: 12px;
            margin-top: 4px;
        }

        .logos-section-title {
            font-size: 12px;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .logos-section-title span {
            flex: 1;
        }

        /* ===== LOGO GRID - أصغر ===== */
        .logo-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 columns = أصغر */
            gap: 8px;
            margin-bottom: 14px;
        }

        .logo-item {
            width: 100%;
            aspect-ratio: 1;
            cursor: grab;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            padding: 7px;
            background: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            object-fit: contain;
        }

        .logo-item:hover {
            border-color: #667eea;
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 6px 18px rgba(102, 126, 234, 0.25);
        }

        .logo-item:active {
            cursor: grabbing;
        }

        /* Transition للانتقال */
        .slide-in {
            animation: slideIn 0.2s ease forwards;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .no-logos-msg {
            color: #aaa;
            font-size: 11px;
            text-align: center;
            grid-column: 1 / -1;
            padding: 8px 0;
        }

        /* ===== EXPORT BUTTON ===== */
        .export-btn {
            position: absolute;
            bottom: 70px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: #1a4a2e;
            border: none;
            padding: 14px 36px;
            border-radius: 30px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            z-index: 50;
            box-shadow: 0 6px 24px rgba(67, 233, 123, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }
        .export-btn:hover {
            transform: translateX(-50%) translateY(-3px);
            box-shadow: 0 10px 32px rgba(67, 233, 123, 0.5);
        }
        .export-btn:active {
            transform: translateX(-50%) translateY(0px);
        }

        /* ===== ORDER MODAL ===== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(6px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal-overlay.open {
            display: flex;
        }
        .modal-box {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 24px 80px rgba(0,0,0,0.25);
            overflow: hidden;
            animation: modalIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.9) translateY(20px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-header h3 {
            color: white;
            font-size: 17px;
            font-weight: 700;
            margin: 0;
        }
        .modal-close {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.4);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        .modal-close:hover { background: rgba(255,255,255,0.35); }
        .modal-body {
            padding: 20px;
            max-height: 60vh;
            overflow-y: auto;
        }
        .form-group {
            margin-bottom: 14px;
        }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            color: #333;
            background: white;
            transition: border-color 0.2s;
            font-family: inherit;
            outline: none;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        .form-group textarea { resize: none; }
        .modal-footer {
            padding: 16px 20px;
            display: flex;
            gap: 10px;
            border-top: 1px solid #f0f0f0;
        }
        .btn-cancel {
            flex: 1;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            background: white;
            color: #666;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-cancel:hover { background: #f5f5f5; }
        .btn-submit {
            flex: 2;
            padding: 12px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 16px rgba(102,126,234,0.35);
        }
        .btn-submit:hover { box-shadow: 0 6px 20px rgba(102,126,234,0.45); }
        .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }

        /* Success message */
        .success-msg {
            text-align: center;
            padding: 30px 20px;
        }
        .success-msg .success-icon {
            font-size: 60px;
            margin-bottom: 16px;
        }
        .success-msg h4 {
            color: #333;
            font-size: 18px;
            margin-bottom: 8px;
        }
        .success-msg p {
            color: #888;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .export-btn {
                bottom: 80px;
                padding: 12px 28px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <button class="sidebar-toggle" id="sidebarToggle">☰</button>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>🎨 مصمم الهودي</h2>
            <button class="sidebar-close" id="sidebarClose" onclick="document.getElementById('sidebar').classList.remove('open')">✕</button>
        </div>
        <div class="sidebar-content">

            <div class="section-title">اختر القسم</div>
            
            <!-- أقسام اللوجو: صور فقط، 4 في الصف -->
            <div class="sections-grid" id="sectionsGrid">
                @foreach($sections as $section)
                <div class="section-item" data-section-id="{{ $section->id }}" onclick="selectSection(this)">
                    <img src="{{ asset('storage/' . $section->logo) }}" alt="{{ $section->name }}" title="{{ $section->name }}">
                </div>
                @endforeach
            </div>

            <!-- لوجوهات القسم المختار - تظهر تحت الأقسام مباشرة -->
            <div class="logos-section" id="logosSection" style="display:none;">
                <div class="logos-section-title">
                    <span id="selectedSectionName"></span>
                </div>
                <div class="logo-grid" id="logoGrid">
                    <!-- ديناميكي -->
                </div>
            </div>

            <div class="section-title" style="margin-top:10px;">التعليمات</div>
            <div class="instructions-box">
                <p>🎯 اختر منظر من الأزرار أعلاه</p>
                <p>🖱️ اسحب اللوجو وضعه على الهودي</p>
                <p>👆 اضغط على اللوجو لإظهار الأدوات</p>
                <p>🔄 استخدم "تحكم حر" للدوران 360°</p>
                <p>👁️ استخدم "معاينة شاملة" للعرض التلقائي</p>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="view-controls">
            <button class="view-btn active" data-view="front">الوش</button>
            <button class="view-btn" data-view="back">الظهر</button>
            <button class="view-btn" data-view="left">يسار</button>
            <button class="view-btn" data-view="right">يمين</button>
            <button class="view-btn" id="gridViewBtn" style="border-color: #9C27B0;">📋 عرض الكل</button>
            <button class="view-btn" id="freeControlBtn" style="border-color: #2196F3;">🖐️ تحكم حر</button>
            <button class="view-btn" id="previewBtn" style="border-color: #FF9800;">🔄 معاينة شاملة</button>
        </div>

        <div class="hoodie-container" id="hoodieContainer">
            <div class="hoodie-wrapper" id="hoodieWrapper">
                <model-viewer 
                    id="hoodieModel" 
                    src="assets/img/3ds/t_shirt_hoodie_3d_model.glb" 
                    alt="3D Hoodie" 
                    disable-zoom
                    disable-pan
                    touch-action="none"
                    camera-orbit="0deg 75deg 105%"
                    min-camera-orbit="auto 75deg auto"
                    max-camera-orbit="auto 75deg auto"
                    field-of-view="auto"
                    camera-target="auto auto auto"
                    interaction-prompt="none">
                </model-viewer>
                <div class="logos-overlay" id="logosOverlay"></div>
            </div>

            <div class="hoodie-grid" id="hoodieGrid">
                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="grid-item-label">الوش</div>
                        <model-viewer 
                            id="gridModelFront" 
                            src="assets/img/3ds/t_shirt_hoodie_3d_model.glb" 
                            camera-orbit="0deg 75deg 105%"
                            min-camera-orbit="0deg 75deg 105%"
                            max-camera-orbit="0deg 75deg 105%"
                            field-of-view="30deg"
                            camera-target="auto auto auto"
                            disable-zoom disable-pan disable-tap
                            interaction-prompt="none" ar-modes="">
                        </model-viewer>
                        <div class="logos-overlay" id="gridOverlayFront"></div>
                    </div>
                </div>

                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="grid-item-label">الظهر</div>
                        <model-viewer 
                            id="gridModelBack" 
                            src="assets/img/3ds/t_shirt_hoodie_3d_model.glb" 
                            camera-orbit="180deg 75deg 105%"
                            min-camera-orbit="180deg 75deg 105%"
                            max-camera-orbit="180deg 75deg 105%"
                            field-of-view="30deg"
                            camera-target="auto auto auto"
                            disable-zoom disable-pan disable-tap
                            interaction-prompt="none" ar-modes="">
                        </model-viewer>
                        <div class="logos-overlay" id="gridOverlayBack"></div>
                    </div>
                </div>

                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="grid-item-label">يسار</div>
                        <model-viewer 
                            id="gridModelLeft" 
                            src="assets/img/3ds/t_shirt_hoodie_3d_model.glb" 
                            camera-orbit="90deg 75deg 105%"
                            min-camera-orbit="90deg 75deg 105%"
                            max-camera-orbit="90deg 75deg 105%"
                            field-of-view="30deg"
                            camera-target="auto auto auto"
                            disable-zoom disable-pan disable-tap
                            interaction-prompt="none" ar-modes="">
                        </model-viewer>
                        <div class="logos-overlay" id="gridOverlayLeft"></div>
                    </div>
                </div>

                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="grid-item-label">يمين</div>
                        <model-viewer 
                            id="gridModelRight" 
                            src="assets/img/3ds/t_shirt_hoodie_3d_model.glb" 
                            camera-orbit="-90deg 75deg 105%"
                            min-camera-orbit="-90deg 75deg 105%"
                            max-camera-orbit="-90deg 75deg 105%"
                            field-of-view="30deg"
                            camera-target="auto auto auto"
                            disable-zoom disable-pan disable-tap
                            interaction-prompt="none" ar-modes="">
                        </model-viewer>
                        <div class="logos-overlay" id="gridOverlayRight"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="status-bar">
            💡 اضغط على اللوجو لإظهار أدوات التحكم
        </div>

        <button class="export-btn" id="exportBtn" onclick="openOrderModal()">
            🛒 إرسال الطلب
        </button>
    </div>

    <!-- ORDER MODAL -->
    <div class="modal-overlay" id="orderModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3>📋 تفاصيل الطلب</h3>
                <button class="modal-close" onclick="closeOrderModal()">✕</button>
            </div>
            <div class="modal-body">
                <form id="orderForm">
                    @csrf
                    <div class="form-group">
                        <label>الاسم الكامل</label>
                        <input type="text" name="name" id="orderName" placeholder="اكتب اسمك" required>
                    </div>
                    <div class="form-group">
                        <label>رقم الهاتف</label>
                        <input type="tel" name="phone" id="orderPhone" placeholder="01xxxxxxxxx" required>
                    </div>
                    <div class="form-group">
                        <label>العنوان</label>
                        <input type="text" name="address" id="orderAddress" placeholder="المحافظة / المدينة" required>
                    </div>
                    <div class="form-group">
                        <label>المقاس</label>
                        <select name="size" id="orderSize" required>
                            <option value="">اختر المقاس</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ملاحظات (اختياري)</label>
                        <textarea name="notes" id="orderNotes" placeholder="أي ملاحظات إضافية..." rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeOrderModal()">إلغاء</button>
                <button class="btn-submit" id="submitOrderBtn" onclick="submitOrder()">
                    <span id="submitBtnText">✅ تأكيد الطلب</span>
                    <span id="submitBtnLoader" style="display:none;">⏳ جاري الإرسال...</span>
                </button>
            </div>
        </div>
    </div>

    <div class="logo-toolbar" id="logoToolbar">
        <button class="toolbar-btn" id="rotateCCW" title="لف يسار">↶</button>
        <button class="toolbar-btn" id="zoomOut" title="تصغير">−</button>
        <button class="toolbar-btn" id="zoomIn" title="تكبير">+</button>
        <button class="toolbar-btn" id="rotateCW" title="لف يمين">↷</button>
        <button class="toolbar-btn danger" id="deleteLogo" title="حذف">✕</button>
    </div>

    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <script>
        const modelViewer = document.getElementById('hoodieModel');
        const hoodieWrapper = document.getElementById('hoodieWrapper');
        const logosOverlay = document.getElementById('logosOverlay');
        const viewButtons = document.querySelectorAll('.view-btn[data-view]');
        const previewBtn = document.getElementById('previewBtn');
        const freeControlBtn = document.getElementById('freeControlBtn');
        const gridViewBtn = document.getElementById('gridViewBtn');
        const hoodieContainer = document.getElementById('hoodieContainer');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');

        const logoToolbar = document.getElementById('logoToolbar');
        const rotateCCW = document.getElementById('rotateCCW');
        const rotateCW = document.getElementById('rotateCW');
        const zoomIn = document.getElementById('zoomIn');
        const zoomOut = document.getElementById('zoomOut');
        const deleteLogo = document.getElementById('deleteLogo');

        let currentView = 'front';
        let logoCounter = 0;
        let isPreviewMode = false;
        let isFreeControlMode = false;
        let isGridView = false;
        let previewInterval = null;
        let dragPreview = null;
        let isDraggingFromSidebar = false;
        let currentDragSource = null;
        let selectedLogo = null;
        let selectedLogoData = null;
        let logosByView = { front: [], back: [], left: [], right: [] };

        const cameraViews = {
            front: '0deg 75deg 105%',
            back: '180deg 75deg 105%',
            left: '90deg 75deg 105%',
            right: '-90deg 75deg 105%'
        };

        // ===========================
        // SIDEBAR TOGGLE
        // ===========================
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });

        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target) && sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                }
            }
        });

        // ===========================
        // TOOLBAR BUTTONS
        // ===========================
        rotateCCW.addEventListener('click', () => {
            if (!selectedLogoData) return;
            selectedLogoData.rotation = (selectedLogoData.rotation || 0) - 15;
            updateLogoTransform(selectedLogo, selectedLogoData);
        });

        rotateCW.addEventListener('click', () => {
            if (!selectedLogoData) return;
            selectedLogoData.rotation = (selectedLogoData.rotation || 0) + 15;
            updateLogoTransform(selectedLogo, selectedLogoData);
        });

        zoomIn.addEventListener('click', () => {
            if (!selectedLogoData) return;
            const newSize = Math.min(80, selectedLogoData.widthPercent + 5);
            selectedLogoData.widthPercent = newSize;
            selectedLogoData.heightPercent = newSize;
            selectedLogo.style.width = newSize + '%';
            selectedLogo.style.height = newSize + '%';
        });

        zoomOut.addEventListener('click', () => {
            if (!selectedLogoData) return;
            const newSize = Math.max(5, selectedLogoData.widthPercent - 5);
            selectedLogoData.widthPercent = newSize;
            selectedLogoData.heightPercent = newSize;
            selectedLogo.style.width = newSize + '%';
            selectedLogo.style.height = newSize + '%';
        });

        deleteLogo.addEventListener('click', () => {
            if (!selectedLogoData || !selectedLogo) return;
            logosByView[selectedLogoData.view] = logosByView[selectedLogoData.view].filter(l => l.id !== selectedLogoData.id);
            selectedLogo.remove();
            deselectLogo();
        });

        function updateLogoTransform(logo, data) {
            logo.style.transform = `rotate(${data.rotation}deg)`;
        }

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.logo-on-hoodie') && !e.target.closest('.logo-toolbar')) {
                if (selectedLogo) selectedLogo.classList.remove('selected');
                if (window.innerWidth > 768) {
                    selectedLogo = null;
                    selectedLogoData = null;
                    logoToolbar.classList.remove('active');
                }
            }
        });

        function selectLogo(logo, data) {
            const allLogos = logosOverlay.querySelectorAll('.logo-on-hoodie');
            allLogos.forEach(l => l.classList.remove('selected'));
            selectedLogo = logo;
            selectedLogoData = data;
            logo.classList.add('selected');
            logoToolbar.classList.add('active');
        }

        function deselectLogo() {
            if (selectedLogo) selectedLogo.classList.remove('selected');
            selectedLogo = null;
            selectedLogoData = null;
            if (window.innerWidth > 768) logoToolbar.classList.remove('active');
        }

        function checkAndShowToolbar() {
            if (window.innerWidth <= 768) {
                const currentLogoVisible = selectedLogo && selectedLogo.classList.contains('active');
                if (!currentLogoVisible && selectedLogo) deselectLogo();
            }
        }

        // ===========================
        // MODEL VIEWER EVENTS
        // ===========================
        modelViewer.addEventListener('load', () => {
            modelViewer.cameraOrbit = cameraViews.front;
            setTimeout(syncGridCameraSettings, 500);
        });

        function syncGridCameraSettings() {
            const mainTarget = modelViewer.getCameraTarget();
            ['Front','Back','Left','Right'].forEach(v => {
                const m = document.getElementById(`gridModel${v}`);
                if (m) {
                    m.cameraTarget = `${mainTarget.x}m ${mainTarget.y}m ${mainTarget.z}m`;
                    m.fieldOfView = modelViewer.fieldOfView;
                }
            });
        }

        modelViewer.addEventListener('camera-change', () => {
            if (!isFreeControlMode) return;
            const orbit = modelViewer.getCameraOrbit();
            const angleInDegrees = ((orbit.theta * 180 / Math.PI) % 360 + 360) % 360;
            let closestView;
            if (angleInDegrees >= 315 || angleInDegrees < 45) closestView = 'front';
            else if (angleInDegrees >= 45 && angleInDegrees < 135) closestView = 'right';
            else if (angleInDegrees >= 135 && angleInDegrees < 225) closestView = 'back';
            else closestView = 'left';

            if (closestView !== currentView) {
                currentView = closestView;
                updateVisibleLogos();
            }
        });

        // ===========================
        // VIEW BUTTONS
        // ===========================
        viewButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                if (isPreviewMode) stopPreview();
                if (isFreeControlMode) stopFreeControl();
                viewButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentView = this.dataset.view;
                modelViewer.cameraOrbit = cameraViews[currentView];
                updateVisibleLogos();
            });
        });

        freeControlBtn.addEventListener('click', function() {
            isFreeControlMode ? stopFreeControl() : startFreeControl();
        });

        function startFreeControl() {
            if (isPreviewMode) stopPreview();
            deselectLogo();
            isFreeControlMode = true;
            freeControlBtn.textContent = '🔒 قفل الوضع';
            freeControlBtn.classList.add('active');
            document.body.classList.add('free-control-active');
            modelViewer.setAttribute('camera-controls', '');
            modelViewer.setAttribute('touch-action', 'pan-y');
            viewButtons.forEach(b => b.classList.remove('active'));
        }

        function stopFreeControl() {
            isFreeControlMode = false;
            freeControlBtn.textContent = '🖐️ تحكم حر';
            freeControlBtn.classList.remove('active');
            document.body.classList.remove('free-control-active');
            modelViewer.removeAttribute('camera-controls');
            modelViewer.setAttribute('touch-action', 'none');
            modelViewer.cameraOrbit = cameraViews[currentView];
            updateVisibleLogos();
            viewButtons.forEach(b => { if (b.dataset.view === currentView) b.classList.add('active'); });
        }

        previewBtn.addEventListener('click', function() {
            isPreviewMode ? stopPreview() : startPreview();
        });

        if (gridViewBtn) {
            gridViewBtn.addEventListener('click', function() {
                isGridView ? stopGridView() : startGridView();
            });
        }

        function startGridView() {
            if (isPreviewMode) stopPreview();
            if (isFreeControlMode) stopFreeControl();
            deselectLogo();
            isGridView = true;
            gridViewBtn.textContent = '🔙 رجوع للعرض العادي';
            gridViewBtn.classList.add('active');
            hoodieContainer.classList.add('grid-view');
            syncLogosToGrid();
            viewButtons.forEach(b => b.classList.remove('active'));
        }

        function stopGridView() {
            isGridView = false;
            gridViewBtn.textContent = '📋 عرض الكل';
            gridViewBtn.classList.remove('active');
            hoodieContainer.classList.remove('grid-view');
            viewButtons.forEach(b => { if (b.dataset.view === currentView) b.classList.add('active'); });
        }

        function syncLogosToGrid() {
            ['Front','Back','Left','Right'].forEach(v => {
                document.getElementById(`gridOverlay${v}`).innerHTML = '';
            });
            Object.keys(logosByView).forEach(view => {
                const overlay = document.getElementById(`gridOverlay${view.charAt(0).toUpperCase() + view.slice(1)}`);
                logosByView[view].forEach(logoData => {
                    const logo = document.createElement('div');
                    logo.className = 'logo-on-hoodie active';
                    logo.style.left = logoData.xPercent + '%';
                    logo.style.top = logoData.yPercent + '%';
                    logo.style.width = logoData.widthPercent + '%';
                    logo.style.height = logoData.heightPercent + '%';
                    logo.style.transform = `rotate(${logoData.rotation}deg)`;
                    logo.style.pointerEvents = 'none';
                    const img = document.createElement('img');
                    img.src = logoData.src;
                    img.draggable = false;
                    logo.appendChild(img);
                    overlay.appendChild(logo);
                });
            });
        }

        function startPreview() {
            if (isFreeControlMode) stopFreeControl();
            isPreviewMode = true;
            previewBtn.textContent = '⏸️ إيقاف المعاينة';
            previewBtn.classList.add('active');
            const views = ['front', 'right', 'back', 'left'];
            let index = 0;
            previewInterval = setInterval(() => {
                currentView = views[index];
                modelViewer.cameraOrbit = cameraViews[currentView];
                updateVisibleLogos();
                viewButtons.forEach(b => {
                    b.classList.toggle('active', b.dataset.view === currentView);
                });
                index = (index + 1) % views.length;
            }, 1500);
        }

        function stopPreview() {
            isPreviewMode = false;
            previewBtn.textContent = '🔄 معاينة شاملة';
            previewBtn.classList.remove('active');
            if (previewInterval) { clearInterval(previewInterval); previewInterval = null; }
            updateVisibleLogos();
        }

        // ===========================
        // DRAG FROM SIDEBAR
        // ===========================
        document.addEventListener('touchmove', (e) => {
            if (!isDraggingFromSidebar || !dragPreview) return;
            const touch = e.touches[0];
            dragPreview.style.left = touch.clientX - 40 + 'px';
            dragPreview.style.top = touch.clientY - 40 + 'px';
            const rect = hoodieWrapper.getBoundingClientRect();
            if (touch.clientX >= rect.left && touch.clientX <= rect.right &&
                touch.clientY >= rect.top && touch.clientY <= rect.bottom) {
                hoodieWrapper.classList.add('drag-over');
            } else {
                hoodieWrapper.classList.remove('drag-over');
            }
        });

        document.addEventListener('touchend', (e) => {
            if (!isDraggingFromSidebar) return;
            const touch = e.changedTouches[0];
            const rect = hoodieWrapper.getBoundingClientRect();
            if (touch.clientX >= rect.left && touch.clientX <= rect.right &&
                touch.clientY >= rect.top && touch.clientY <= rect.bottom) {
                addLogo(currentDragSource.src, touch.clientX - rect.left, touch.clientY - rect.top);
            }
            if (dragPreview) { dragPreview.remove(); dragPreview = null; }
            isDraggingFromSidebar = false;
            currentDragSource = null;
            hoodieWrapper.classList.remove('drag-over');
        });

        hoodieWrapper.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'copy';
            hoodieWrapper.classList.add('drag-over');
        });

        hoodieWrapper.addEventListener('dragleave', () => {
            hoodieWrapper.classList.remove('drag-over');
        });

        hoodieWrapper.addEventListener('drop', (e) => {
            e.preventDefault();
            hoodieWrapper.classList.remove('drag-over');
            if (!currentDragSource) return;
            const rect = hoodieWrapper.getBoundingClientRect();
            addLogo(currentDragSource.src, e.clientX - rect.left, e.clientY - rect.top);
        });

        // ===========================
        // ADD LOGO
        // ===========================
        function addLogo(src, x, y) {
            logoCounter++;
            const rect = hoodieWrapper.getBoundingClientRect();

            // الحجم الثابت بالبيكسل (نحوله لنسبة مئوية بناءً على الحجم الحالي)
            const logoSizePx = 100;

            // الموضع: مركز اللوجو عند نقطة الإفلات
            // نحسب النسبة المئوية للمركز، مش للركن العلوي الأيسر
            const centerXPercent = (x / rect.width) * 100;
            const centerYPercent = (y / rect.height) * 100;

            // حجم اللوجو كنسبة من الـ container
            const logoWidthPercent = (logoSizePx / rect.width) * 100;
            const logoHeightPercent = (logoSizePx / rect.height) * 100;

            // الـ left/top هو الركن العلوي الأيسر = المركز - نص الحجم
            const xPercent = centerXPercent - (logoWidthPercent / 2);
            const yPercent = centerYPercent - (logoHeightPercent / 2);

            const logoData = {
                id: logoCounter,
                src: src,
                // نخزن موضع المركز كنسبة مئوية (مستقل عن حجم الشاشة)
                centerXPercent: centerXPercent,
                centerYPercent: centerYPercent,
                xPercent: Math.max(0, Math.min(xPercent, 100 - logoWidthPercent)),
                yPercent: Math.max(0, Math.min(yPercent, 100 - logoHeightPercent)),
                widthPercent: logoWidthPercent,
                heightPercent: logoHeightPercent,
                // الحجم الأصلي بالبيكسل للـ responsive recalculation
                logoSizePx: logoSizePx,
                rotation: 0,
                view: currentView
            };
            logosByView[currentView].push(logoData);
            const logoElement = createLogoElement(logoData);
            setTimeout(() => selectLogo(logoElement, logoData), 100);
        }

        // إعادة حساب مواضع اللوجوهات لما تتغير حجم الشاشة
        function recalcLogoPositions() {
            const rect = hoodieWrapper.getBoundingClientRect();
            if (rect.width === 0 || rect.height === 0) return;

            const allLogos = logosOverlay.querySelectorAll('.logo-on-hoodie');
            allLogos.forEach(logoEl => {
                const logoId = parseInt(logoEl.dataset.id);
                const logoView = logoEl.dataset.view;
                const data = logosByView[logoView]?.find(l => l.id === logoId);
                if (!data) return;

                // إعادة حساب الحجم بناءً على الـ container الجديد
                const newWidthPercent = (data.logoSizePx / rect.width) * 100;
                const newHeightPercent = (data.logoSizePx / rect.height) * 100;

                // إعادة حساب الموضع من المركز المخزن
                const newX = data.centerXPercent - (newWidthPercent / 2);
                const newY = data.centerYPercent - (newHeightPercent / 2);

                data.widthPercent = newWidthPercent;
                data.heightPercent = newHeightPercent;
                data.xPercent = Math.max(0, Math.min(newX, 100 - newWidthPercent));
                data.yPercent = Math.max(0, Math.min(newY, 100 - newHeightPercent));

                logoEl.style.width = data.widthPercent + '%';
                logoEl.style.height = data.heightPercent + '%';
                logoEl.style.left = data.xPercent + '%';
                logoEl.style.top = data.yPercent + '%';
            });
        }

        // تحديث المركز لما يتحرك اللوجو
        function updateLogoCenter(data) {
            data.centerXPercent = data.xPercent + (data.widthPercent / 2);
            data.centerYPercent = data.yPercent + (data.heightPercent / 2);
        }

        // Resize observer على الـ wrapper
        const resizeObserver = new ResizeObserver(() => {
            recalcLogoPositions();
        });
        resizeObserver.observe(hoodieWrapper);

        function createLogoElement(data) {
            const logo = document.createElement('div');
            logo.className = 'logo-on-hoodie';
            logo.dataset.id = data.id;
            logo.dataset.view = data.view;
            logo.style.left = data.xPercent + '%';
            logo.style.top = data.yPercent + '%';
            logo.style.width = data.widthPercent + '%';
            logo.style.height = data.heightPercent + '%';
            void logo.offsetWidth;
            updateLogoTransform(logo, data);

            const img = document.createElement('img');
            img.src = data.src;
            img.draggable = false;
            logo.appendChild(img);

            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'delete-btn';
            deleteBtn.innerHTML = '✕';
            deleteBtn.onclick = (e) => {
                e.stopPropagation();
                logosByView[data.view] = logosByView[data.view].filter(l => l.id !== data.id);
                logo.remove();
                deselectLogo();
            };
            logo.appendChild(deleteBtn);

            const resizeHandle = document.createElement('div');
            resizeHandle.className = 'resize-handle';
            logo.appendChild(resizeHandle);

            logo.addEventListener('click', (e) => {
                e.stopPropagation();
                const logoId = parseInt(logo.dataset.id);
                const logoView = logo.dataset.view;
                const currentLogoData = logosByView[logoView].find(l => l.id === logoId);
                if (currentLogoData) selectLogo(logo, currentLogoData);
            });

            let touchStartTime = 0;
            let touchStartPos = { x: 0, y: 0 };
            logo.addEventListener('touchstart', (e) => {
                touchStartTime = Date.now();
                if (e.touches[0]) touchStartPos = { x: e.touches[0].clientX, y: e.touches[0].clientY };
            }, { passive: true, capture: false });

            logo.addEventListener('touchend', (e) => {
                const touchDuration = Date.now() - touchStartTime;
                const touch = e.changedTouches[0];
                if (touch) {
                    const distance = Math.hypot(touch.clientX - touchStartPos.x, touch.clientY - touchStartPos.y);
                    if (touchDuration < 200 && distance < 10) {
                        const logoId = parseInt(logo.dataset.id);
                        const logoView = logo.dataset.view;
                        const currentLogoData = logosByView[logoView].find(l => l.id === logoId);
                        if (currentLogoData) selectLogo(logo, currentLogoData);
                    }
                }
            }, { passive: true, capture: false });

            logosOverlay.appendChild(logo);
            if (data.view === currentView) logo.classList.add('active');

            makeDraggable(logo, data);
            makeResizable(logo, data, resizeHandle);
            return logo;
        }

        function updateVisibleLogos() {
            logosOverlay.querySelectorAll('.logo-on-hoodie').forEach(logo => {
                logo.classList.toggle('active', logo.dataset.view === currentView);
            });
            setTimeout(checkAndShowToolbar, 100);
        }

        function makeDraggable(logo, data) {
            let isDragging = false;
            let startX, startY, startLeft, startTop;

            function startDrag(e) {
                if (isDraggingFromSidebar) return;
                if (e.target.classList.contains('delete-btn') || e.target.classList.contains('resize-handle')) return;
                if (e.touches && e.touches.length > 1) return;
                e.preventDefault();
                e.stopPropagation();
                isDragging = true;
                const touch = e.touches ? e.touches[0] : e;
                startX = touch.clientX;
                startY = touch.clientY;
                startLeft = data.xPercent;
                startTop = data.yPercent;
                logo.classList.add('dragging');
            }

            function drag(e) {
                if (!isDragging) return;
                if (e.touches && e.touches.length > 1) { stopDrag(); return; }
                e.preventDefault();
                const touch = e.touches ? e.touches[0] : e;
                const rect = hoodieWrapper.getBoundingClientRect();
                const deltaX = ((touch.clientX - startX) / rect.width) * 100;
                const deltaY = ((touch.clientY - startY) / rect.height) * 100;
                data.xPercent = Math.max(0, Math.min(startLeft + deltaX, 100 - data.widthPercent));
                data.yPercent = Math.max(0, Math.min(startTop + deltaY, 100 - data.heightPercent));
                logo.style.left = data.xPercent + '%';
                logo.style.top = data.yPercent + '%';
                updateLogoCenter(data);
            }

            function stopDrag() {
                if (isDragging) { isDragging = false; logo.classList.remove('dragging'); }
            }

            logo.addEventListener('mousedown', startDrag);
            document.addEventListener('mousemove', drag);
            document.addEventListener('mouseup', stopDrag);
            logo.addEventListener('touchstart', startDrag, { passive: false });
            logo.addEventListener('touchmove', drag, { passive: false });
            logo.addEventListener('touchend', stopDrag, { passive: false });
        }

        function makeResizable(logo, data, handle) {
            let isResizing = false;
            let startY, startSize, rafId = null;

            function startResize(e) {
                e.stopPropagation();
                e.preventDefault();
                if (e.stopImmediatePropagation) e.stopImmediatePropagation();
                isResizing = true;
                const touch = e.touches ? e.touches[0] : e;
                startY = touch.clientY;
                const containerRect = hoodieWrapper.getBoundingClientRect();
                const logoRect = logo.getBoundingClientRect();
                startSize = (logoRect.width / containerRect.width) * 100;
                data.widthPercent = startSize;
                data.heightPercent = startSize;
            }

            function resize(e) {
                if (!isResizing) return;
                e.preventDefault();
                const touch = e.touches ? e.touches[0] : e;
                const containerRect = hoodieWrapper.getBoundingClientRect();
                const deltaPercent = ((touch.clientY - startY) / containerRect.height) * 100;
                const newSize = Math.max(5, Math.min(80, startSize + deltaPercent));
                if (rafId) cancelAnimationFrame(rafId);
                rafId = requestAnimationFrame(() => {
                    data.widthPercent = newSize;
                    data.heightPercent = newSize;
                    logo.style.width = newSize + '%';
                    logo.style.height = newSize + '%';
                    updateLogoCenter(data);
                });
            }

            function stopResize() {
                if (rafId) { cancelAnimationFrame(rafId); rafId = null; }
                isResizing = false;
            }

            handle.addEventListener('mousedown', startResize, true);
            document.addEventListener('mousemove', resize);
            document.addEventListener('mouseup', stopResize);
            handle.addEventListener('touchstart', startResize, { passive: false, capture: true });
            document.addEventListener('touchmove', resize, { passive: false });
            document.addEventListener('touchend', stopResize);
        }

        // ===========================
        // SECTIONS LOGIC
        // ===========================
        const sectionLogos = {
            @foreach($sections as $section)
            {{ $section->id }}: [
                @foreach($section->logos as $logo)
                "{{ asset('storage/' . $logo->image) }}",
                @endforeach
            ],
            @endforeach
        };

        let currentSectionId = null;

        function selectSection(el) {
            const sectionId = el.dataset.sectionId;

            // لو نفس القسم اتضغط تاني، أخفي اللوجوهات
            if (currentSectionId === sectionId) {
                el.classList.remove('active');
                currentSectionId = null;
                document.getElementById('logosSection').style.display = 'none';
                return;
            }

            // تفعيل القسم
            document.querySelectorAll('.section-item').forEach(s => s.classList.remove('active'));
            el.classList.add('active');
            currentSectionId = sectionId;

            const sectionName = el.querySelector('img').getAttribute('alt') || el.querySelector('img').getAttribute('title') || '';
            const logos = sectionLogos[sectionId] || [];

            // تحديث العنوان
            document.getElementById('selectedSectionName').textContent = sectionName;

            // بناء اللوجوهات
            const logoGrid = document.getElementById('logoGrid');
            logoGrid.innerHTML = '';

            if (logos.length === 0) {
                const p = document.createElement('p');
                p.className = 'no-logos-msg';
                p.textContent = 'لا توجد لوجوهات في هذا القسم';
                logoGrid.appendChild(p);
            } else {
                logos.forEach(src => {
                    const img = document.createElement('img');
                    img.src = src;
                    img.className = 'logo-item';
                    img.alt = 'Logo';
                    img.draggable = true;

                    // Desktop drag
                    img.addEventListener('dragstart', (e) => {
                        currentDragSource = img;
                        e.dataTransfer.effectAllowed = 'copy';
                        e.dataTransfer.setData('text/plain', img.src);
                        if (sidebar.classList.contains('open')) sidebar.classList.remove('open');
                    });
                    img.addEventListener('dragend', () => { currentDragSource = null; });

                    // Mobile touch drag
                    img.addEventListener('touchstart', (e) => {
                        const touch = e.touches[0];
                        isDraggingFromSidebar = true;
                        currentDragSource = img;
                        dragPreview = document.createElement('img');
                        dragPreview.src = img.src;
                        dragPreview.className = 'drag-preview';
                        dragPreview.style.left = touch.clientX - 40 + 'px';
                        dragPreview.style.top = touch.clientY - 40 + 'px';
                        document.body.appendChild(dragPreview);
                        if (sidebar.classList.contains('open')) sidebar.classList.remove('open');
                    }, { passive: true });

                    logoGrid.appendChild(img);
                });
            }

            // إظهار القسم تحت الأقسام (مش بدلها)
            const logosSection = document.getElementById('logosSection');
            logosSection.style.display = 'block';
            logoGrid.classList.add('slide-in');
        }

        // ===========================
        // ORDER MODAL
        // ===========================
        function openOrderModal() {
            const allLogos = [];
            Object.keys(logosByView).forEach(view => {
                logosByView[view].forEach(l => {
                    allLogos.push({
                        src: l.src,
                        view: l.view,
                        xPercent: l.xPercent,
                        yPercent: l.yPercent,
                        widthPercent: l.widthPercent,
                        heightPercent: l.heightPercent,
                        rotation: l.rotation
                    });
                });
            });
            if (allLogos.length === 0) {
                alert('من فضلك ضيف لوجو على الهودي الأول!');
                return;
            }
            // Reset form
            document.getElementById('orderForm').reset();
            document.getElementById('orderModal').querySelector('.modal-body').innerHTML = `
                <form id="orderForm">
                    <div class="form-group">
                        <label>الاسم الكامل</label>
                        <input type="text" name="name" id="orderName" placeholder="اكتب اسمك" required>
                    </div>
                    <div class="form-group">
                        <label>رقم الهاتف</label>
                        <input type="tel" name="phone" id="orderPhone" placeholder="01xxxxxxxxx" required>
                    </div>
                    <div class="form-group">
                        <label>العنوان</label>
                        <input type="text" name="address" id="orderAddress" placeholder="المحافظة / المدينة" required>
                    </div>
                    <div class="form-group">
                        <label>المقاس</label>
                        <select name="size" id="orderSize" required>
                            <option value="">اختر المقاس</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ملاحظات (اختياري)</label>
                        <textarea name="notes" id="orderNotes" placeholder="أي ملاحظات إضافية..." rows="2"></textarea>
                    </div>
                </form>
            `;
            document.getElementById('submitBtnText') && (document.getElementById('submitBtnText').style.display = '');
            document.getElementById('submitBtnLoader') && (document.getElementById('submitBtnLoader').style.display = 'none');
            document.getElementById('orderModal').classList.add('open');
        }

        function closeOrderModal() {
            document.getElementById('orderModal').classList.remove('open');
        }

        // إغلاق لما تضغط بره الـ modal
        document.getElementById('orderModal').addEventListener('click', function(e) {
            if (e.target === this) closeOrderModal();
        });

        function submitOrder() {
            const name = document.getElementById('orderName')?.value?.trim();
            const phone = document.getElementById('orderPhone')?.value?.trim();
            const address = document.getElementById('orderAddress')?.value?.trim();
            const size = document.getElementById('orderSize')?.value;
            const notes = document.getElementById('orderNotes')?.value?.trim();

            if (!name || !phone || !address || !size) {
                alert('من فضلك املأ كل الحقول المطلوبة');
                return;
            }

            // تجميع بيانات اللوجوهات
            const logosData = [];
            Object.keys(logosByView).forEach(view => {
                logosByView[view].forEach(l => {
                    logosData.push({
                        src: l.src,
                        view: l.view,
                        x_percent: parseFloat(l.xPercent.toFixed(2)),
                        y_percent: parseFloat(l.yPercent.toFixed(2)),
                        width_percent: parseFloat(l.widthPercent.toFixed(2)),
                        height_percent: parseFloat(l.heightPercent.toFixed(2)),
                        rotation: l.rotation || 0
                    });
                });
            });

            // تفعيل حالة التحميل
            const submitBtn = document.getElementById('submitOrderBtn');
            const btnText = document.getElementById('submitBtnText');
            const btnLoader = document.getElementById('submitBtnLoader');
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            btnLoader.style.display = 'inline';

            // إرسال للسيرفر
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            if (!csrfToken) {
                alert('خطأ: CSRF token غير موجود، جرب تحدث الصفحة');
                submitBtn.disabled = false;
                btnText.style.display = '';
                btnLoader.style.display = 'none';
                return;
            }

            fetch('/orders', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name, phone, address, size, notes,
                    logos: logosData
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // عرض رسالة النجاح
                    document.getElementById('orderModal').querySelector('.modal-body').innerHTML = `
                        <div class="success-msg">
                            <div class="success-icon">🎉</div>
                            <h4>تم إرسال طلبك بنجاح!</h4>
                            <p>رقم الطلب: <strong>#${data.order_id}</strong></p>
                            <p>هنتواصل معاك على ${phone} قريباً</p>
                        </div>
                    `;
                    document.getElementById('orderModal').querySelector('.modal-footer').innerHTML = `
                        <button class="btn-submit" onclick="closeOrderModal()" style="flex:1">✓ حسناً</button>
                    `;
                } else {
                    alert(data.message || 'حدث خطأ، حاول مرة أخرى');
                    submitBtn.disabled = false;
                    btnText.style.display = '';
                    btnLoader.style.display = 'none';
                }
            })
            .catch(err => {
                console.error(err);
                alert('حدث خطأ في الاتصال، حاول مرة أخرى');
                submitBtn.disabled = false;
                btnText.style.display = '';
                btnLoader.style.display = 'none';
            });
        }
    </script>
</body>
</html>
