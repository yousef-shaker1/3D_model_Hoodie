<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        }

        .sidebar-header h2 {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
            color: white;
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

        .logo-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .logo-item {
            width: 100%;
            aspect-ratio: 1;
            cursor: grab;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            padding: 10px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .logo-item:hover {
            border-color: #667eea;
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.25);
        }

        .logo-item:active {
            cursor: grabbing;
            transform: translateY(-2px) scale(0.98);
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
        
        /* Special buttons - keep their own colors when active */
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

        /* Grid view mode */
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

        /* Logo overlay */
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
            /* Prevent layout shift on resize */
            will-change: width, height;
            /* Force GPU acceleration */
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
            /* Prevent position shift */
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
            /* Prevent position shift */
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
            width: 100px;
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
            
            /* Overlay backdrop when sidebar is open */
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
            
            /* Hide view controls when sidebar is open */
            .sidebar.open ~ .main .view-controls {
                display: none;
            }

            .sidebar-toggle {
                display: flex;
            }

            .hoodie-container {
                max-width: 100%;
                height: 500px;
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
                top: 80px;
                gap: 6px;
                padding: 10px;
            }

            .view-btn {
                padding: 8px 12px;
                font-size: 11px;
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
    </style>
</head>
<body>
    <button class="sidebar-toggle" id="sidebarToggle">☰</button>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>🎨 مصمم الهودي</h2>
        </div>
        <div class="sidebar-content">
            <div class="section-title">اختر اللوجو</div>
            <div class="logo-grid">
                <img src="{{ asset('assets/img/logos/8.png') }}" class="logo-item" alt="Logo">
            </div>
            <div class="section-title">التعليمات</div>
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
                            src="assets/images/t_shirt_hoodie_3d_model.glb" 
                            camera-orbit="0deg 75deg 105%"
                            min-camera-orbit="0deg 75deg 105%"
                            max-camera-orbit="0deg 75deg 105%"
                            field-of-view="30deg"
                            camera-target="auto auto auto"
                            disable-zoom
                            disable-pan
                            disable-tap
                            interaction-prompt="none"
                            ar-modes="">
                        </model-viewer>
                        <div class="logos-overlay" id="gridOverlayFront"></div>
                    </div>
                </div>

                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="grid-item-label">الظهر</div>
                        <model-viewer 
                            id="gridModelBack" 
                            src="assets/images/t_shirt_hoodie_3d_model.glb" 
                            camera-orbit="180deg 75deg 105%"
                            min-camera-orbit="180deg 75deg 105%"
                            max-camera-orbit="180deg 75deg 105%"
                            field-of-view="30deg"
                            camera-target="auto auto auto"
                            disable-zoom
                            disable-pan
                            disable-tap
                            interaction-prompt="none"
                            ar-modes="">
                        </model-viewer>
                        <div class="logos-overlay" id="gridOverlayBack"></div>
                    </div>
                </div>

                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="grid-item-label">يسار</div>
                        <model-viewer 
                            id="gridModelLeft" 
                            src="assets/images/t_shirt_hoodie_3d_model.glb" 
                            camera-orbit="90deg 75deg 105%"
                            min-camera-orbit="90deg 75deg 105%"
                            max-camera-orbit="90deg 75deg 105%"
                            field-of-view="30deg"
                            camera-target="auto auto auto"
                            disable-zoom
                            disable-pan
                            disable-tap
                            interaction-prompt="none"
                            ar-modes="">
                        </model-viewer>
                        <div class="logos-overlay" id="gridOverlayLeft"></div>
                    </div>
                </div>

                <div class="grid-item">
                    <div class="grid-item-content">
                        <div class="grid-item-label">يمين</div>
                        <model-viewer 
                            id="gridModelRight" 
                            src="assets/images/t_shirt_hoodie_3d_model.glb" 
                            camera-orbit="-90deg 75deg 105%"
                            min-camera-orbit="-90deg 75deg 105%"
                            max-camera-orbit="-90deg 75deg 105%"
                            field-of-view="30deg"
                            camera-target="auto auto auto"
                            disable-zoom
                            disable-pan
                            disable-tap
                            interaction-prompt="none"
                            ar-modes="">
                        </model-viewer>
                        <div class="logos-overlay" id="gridOverlayRight"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="status-bar">
            💡 اضغط على اللوجو لإظهار أدوات التحكم
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
        const logoItems = document.querySelectorAll('.logo-item');
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
        let logosByView = {
            front: [],
            back: [],
            left: [],
            right: []
        };

        const cameraViews = {
            front: '0deg 75deg 105%',
            back: '180deg 75deg 105%',
            left: '90deg 75deg 105%',
            right: '-90deg 75deg 105%'
        };

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
            const newSize = Math.max(10, selectedLogoData.widthPercent - 5);
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
            setTimeout(() => checkAndShowToolbar(), 100);
        });

        function updateLogoTransform(logo, data) {
            logo.style.transform = `rotate(${data.rotation}deg)`;
        }

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.logo-on-hoodie') && !e.target.closest('.logo-toolbar')) {
                if (selectedLogo) {
                    selectedLogo.classList.remove('selected');
                }
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
            if (selectedLogo) {
                selectedLogo.classList.remove('selected');
            }
            selectedLogo = null;
            selectedLogoData = null;
            if (window.innerWidth > 768) {
                logoToolbar.classList.remove('active');
            }
        }
        
        function checkAndShowToolbar() {
            // On mobile, DON'T auto-select logos
            // Let user manually tap to select
            // Just hide toolbar if no logo is selected
            if (window.innerWidth <= 768) {
                const currentLogoVisible = selectedLogo && selectedLogo.classList.contains('active');
                
                // If selected logo is not in current view, deselect it
                if (!currentLogoVisible && selectedLogo) {
                    deselectLogo();
                }
            }
        }

        modelViewer.addEventListener('load', () => {
            modelViewer.cameraOrbit = cameraViews.front;
            setTimeout(() => {
                syncGridCameraSettings();
            }, 500);
        });

        function syncGridCameraSettings() {
            const mainTarget = modelViewer.getCameraTarget();
            const gridModels = [
                document.getElementById('gridModelFront'),
                document.getElementById('gridModelBack'),
                document.getElementById('gridModelLeft'),
                document.getElementById('gridModelRight')
            ];

            gridModels.forEach(model => {
                if (model) {
                    model.cameraTarget = `${mainTarget.x}m ${mainTarget.y}m ${mainTarget.z}m`;
                    model.fieldOfView = modelViewer.fieldOfView;
                }
            });
        }

        modelViewer.addEventListener('camera-change', () => {
            if (isFreeControlMode) {
                const orbit = modelViewer.getCameraOrbit();
                const theta = orbit.theta;
                const angleInDegrees = (theta * 180 / Math.PI) % 360;
                let normalizedAngle = angleInDegrees < 0 ? angleInDegrees + 360 : angleInDegrees;

                let closestView;
                if (normalizedAngle >= 315 || normalizedAngle < 45) {
                    closestView = 'front';
                } else if (normalizedAngle >= 45 && normalizedAngle < 135) {
                    closestView = 'right';
                } else if (normalizedAngle >= 135 && normalizedAngle < 225) {
                    closestView = 'back';
                } else {
                    closestView = 'left';
                }

                if (closestView !== currentView) {
                    currentView = closestView;
                    updateVisibleLogos();
                }
            }
        });

        logoItems.forEach(item => {
            item.addEventListener('dragstart', (e) => {
                currentDragSource = item;
                e.dataTransfer.effectAllowed = 'copy';
                e.dataTransfer.setData('text/plain', item.src);
                if (sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                }
            });

            item.addEventListener('dragend', () => {
                currentDragSource = null;
            });

            item.addEventListener('touchstart', (e) => {
                const touch = e.touches[0];
                isDraggingFromSidebar = true;
                currentDragSource = item;

                dragPreview = document.createElement('img');
                dragPreview.src = item.src;
                dragPreview.className = 'drag-preview';
                dragPreview.style.left = touch.clientX - 50 + 'px';
                dragPreview.style.top = touch.clientY - 50 + 'px';
                document.body.appendChild(dragPreview);

                if (sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                }
            }, { passive: true });
        });

        document.addEventListener('touchmove', (e) => {
            if (!isDraggingFromSidebar || !dragPreview) return;

            const touch = e.touches[0];
            dragPreview.style.left = touch.clientX - 50 + 'px';
            dragPreview.style.top = touch.clientY - 50 + 'px';

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
                const x = touch.clientX - rect.left;
                const y = touch.clientY - rect.top;
                addLogo(currentDragSource.src, x, y);
            }

            if (dragPreview) {
                dragPreview.remove();
                dragPreview = null;
            }

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
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            addLogo(currentDragSource.src, x, y);
        });

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
            if (isFreeControlMode) {
                stopFreeControl();
            } else {
                startFreeControl();
            }
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

            viewButtons.forEach(b => {
                if (b.dataset.view === currentView) {
                    b.classList.add('active');
                }
            });
        }

        previewBtn.addEventListener('click', function() {
            if (isPreviewMode) {
                stopPreview();
            } else {
                startPreview();
            }
        });

        gridViewBtn.addEventListener('click', function() {
            if (isGridView) {
                stopGridView();
            } else {
                startGridView();
            }
        });

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

            viewButtons.forEach(b => {
                if (b.dataset.view === currentView) {
                    b.classList.add('active');
                }
            });
        }

        function syncLogosToGrid() {
            ['Front', 'Back', 'Left', 'Right'].forEach(view => {
                const overlay = document.getElementById(`gridOverlay${view}`);
                overlay.innerHTML = '';
            });

            Object.keys(logosByView).forEach(view => {
                const viewCapitalized = view.charAt(0).toUpperCase() + view.slice(1);
                const overlay = document.getElementById(`gridOverlay${viewCapitalized}`);

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
                    if (b.dataset.view === currentView) {
                        b.classList.add('active');
                    } else {
                        b.classList.remove('active');
                    }
                });

                index = (index + 1) % views.length;
            }, 1500);
        }

        function stopPreview() {
            isPreviewMode = false;
            previewBtn.textContent = '🔄 معاينة شاملة';
            previewBtn.classList.remove('active');

            if (previewInterval) {
                clearInterval(previewInterval);
                previewInterval = null;
            }

            updateVisibleLogos();
        }

        function addLogo(src, x, y) {
            logoCounter++;

            const rect = hoodieWrapper.getBoundingClientRect();

            const logoData = {
                id: logoCounter,
                src: src,
                xPercent: ((x - 75) / rect.width) * 100,
                yPercent: ((y - 75) / rect.height) * 100,
                widthPercent: (150 / rect.width) * 100,
                heightPercent: (150 / rect.height) * 100,
                rotation: 0,
                view: currentView
            };

            logosByView[currentView].push(logoData);
            const logoElement = createLogoElement(logoData);
            
            // AUTO-SELECT: Select the logo immediately after adding it
            setTimeout(() => {
                selectLogo(logoElement, logoData);
            }, 100);
        }

        function createLogoElement(data) {
            const logo = document.createElement('div');
            logo.className = 'logo-on-hoodie';
            logo.dataset.id = data.id;
            logo.dataset.view = data.view;

            // CRITICAL: Set dimensions explicitly AND force reflow
            logo.style.left = data.xPercent + '%';
            logo.style.top = data.yPercent + '%';
            logo.style.width = data.widthPercent + '%';
            logo.style.height = data.heightPercent + '%';
            
            // Force immediate layout calculation
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
                
                // CRITICAL FIX: ALWAYS get FRESH data from logosByView, never use closure reference
                const logoId = parseInt(logo.dataset.id);
                const logoView = logo.dataset.view;
                
                console.log('=== LOGO CLICKED ===');
                console.log('Clicked logo ID:', logoId, 'View:', logoView);
                console.log('Available logos in this view:', logosByView[logoView]);
                
                const currentLogoData = logosByView[logoView].find(l => l.id === logoId);
                
                console.log('Found logo data:', currentLogoData);
                
                if (currentLogoData) {
                    selectLogo(logo, currentLogoData);
                } else {
                    console.error('ERROR: Could not find logo data for ID', logoId);
                }
            });
            
            // MOBILE FIX: Add touch tap handler for selection
            let touchStartTime = 0;
            let touchStartPos = { x: 0, y: 0 };
            
            logo.addEventListener('touchstart', (e) => {
                touchStartTime = Date.now();
                if (e.touches[0]) {
                    touchStartPos = {
                        x: e.touches[0].clientX,
                        y: e.touches[0].clientY
                    };
                }
            }, { passive: true, capture: false });
            
            logo.addEventListener('touchend', (e) => {
                const touchDuration = Date.now() - touchStartTime;
                const touch = e.changedTouches[0];
                
                if (touch) {
                    const distance = Math.hypot(
                        touch.clientX - touchStartPos.x,
                        touch.clientY - touchStartPos.y
                    );
                    
                    // If it was a tap (short duration, small movement)
                    if (touchDuration < 200 && distance < 10) {
                        const logoId = parseInt(logo.dataset.id);
                        const logoView = logo.dataset.view;
                        const currentLogoData = logosByView[logoView].find(l => l.id === logoId);
                        
                        console.log('=== LOGO TAPPED (MOBILE) ===');
                        console.log('Tapped logo ID:', logoId);
                        
                        if (currentLogoData) {
                            selectLogo(logo, currentLogoData);
                        }
                    }
                }
            }, { passive: true, capture: false });

            logosOverlay.appendChild(logo);

            if (data.view === currentView) {
                logo.classList.add('active');
            }

            makeDraggable(logo, data);
            makeResizable(logo, data, resizeHandle);
            
            // Return the logo element so it can be selected
            return logo;
        }

        function updateVisibleLogos() {
            const allLogos = logosOverlay.querySelectorAll('.logo-on-hoodie');
            allLogos.forEach(logo => {
                if (logo.dataset.view === currentView) {
                    logo.classList.add('active');
                } else {
                    logo.classList.remove('active');
                }
            });
            
            setTimeout(() => checkAndShowToolbar(), 100);
        }

        function makeDraggable(logo, data) {
            let isDragging = false;
            let startX, startY;
            let startLeft, startTop;

            function startDrag(e) {
                if (isDraggingFromSidebar) return;
                if (e.target.classList.contains('delete-btn') || 
                    e.target.classList.contains('resize-handle')) return;

                if (e.touches && e.touches.length > 1) {
                    return;
                }

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
                
                if (e.touches && e.touches.length > 1) {
                    stopDrag();
                    return;
                }

                e.preventDefault();
                const touch = e.touches ? e.touches[0] : e;
                const rect = hoodieWrapper.getBoundingClientRect();

                const deltaX = ((touch.clientX - startX) / rect.width) * 100;
                const deltaY = ((touch.clientY - startY) / rect.height) * 100;

                data.xPercent = startLeft + deltaX;
                data.yPercent = startTop + deltaY;

                data.xPercent = Math.max(0, Math.min(data.xPercent, 100 - data.widthPercent));
                data.yPercent = Math.max(0, Math.min(data.yPercent, 100 - data.heightPercent));

                logo.style.left = data.xPercent + '%';
                logo.style.top = data.yPercent + '%';
            }

            function stopDrag() {
                if (isDragging) {
                    isDragging = false;
                    logo.classList.remove('dragging');
                }
            }

            logo.addEventListener('mousedown', startDrag);
            document.addEventListener('mousemove', drag);
            document.addEventListener('mouseup', stopDrag);

            logo.addEventListener('touchstart', startDrag, { passive: false });
            logo.addEventListener('touchmove', drag, { passive: false });
            logo.addEventListener('touchend', stopDrag, { passive: false });
        }

        // FIXED: Simple resize that maintains square
        function makeResizable(logo, data, handle) {
            let isResizing = false;
            let startY;
            let startSize;
            let rafId = null;

            function startResize(e) {
                e.stopPropagation();
                e.preventDefault();
                
                // CRITICAL: Stop the event from reaching logo's touchstart
                if (e.stopImmediatePropagation) {
                    e.stopImmediatePropagation();
                }
                
                isResizing = true;
                
                const touch = e.touches ? e.touches[0] : e;
                startY = touch.clientY;
                
                // CRITICAL FIX: Read ACTUAL computed size from DOM, not from data
                // This prevents the "jump" on first resize
                const containerRect = hoodieWrapper.getBoundingClientRect();
                const logoRect = logo.getBoundingClientRect();
                startSize = (logoRect.width / containerRect.width) * 100;
                
                // Update data to match actual size
                data.widthPercent = startSize;
                data.heightPercent = startSize;
            }

            function resize(e) {
                if (!isResizing) return;
                e.preventDefault();

                const touch = e.touches ? e.touches[0] : e;
                const containerRect = hoodieWrapper.getBoundingClientRect();
                
                // Simple: measure vertical movement
                const deltaY = touch.clientY - startY;
                const deltaPercent = (deltaY / containerRect.height) * 100;
                
                const newSize = Math.max(10, Math.min(80, startSize + deltaPercent));

                // Use RAF to prevent layout thrashing
                if (rafId) {
                    cancelAnimationFrame(rafId);
                }
                
                rafId = requestAnimationFrame(() => {
                    // Update BOTH to keep square
                    data.widthPercent = newSize;
                    data.heightPercent = newSize;
                    logo.style.width = newSize + '%';
                    logo.style.height = newSize + '%';
                });
            }

            function stopResize() {
                if (rafId) {
                    cancelAnimationFrame(rafId);
                    rafId = null;
                }
                isResizing = false;
            }

            // CRITICAL: Use capture phase to catch events BEFORE logo
            handle.addEventListener('mousedown', startResize, true);
            document.addEventListener('mousemove', resize);
            document.addEventListener('mouseup', stopResize);

            handle.addEventListener('touchstart', startResize, { passive: false, capture: true });
            document.addEventListener('touchmove', resize, { passive: false });
            document.addEventListener('touchend', stopResize);
        }
    </script>
</body>
</html>