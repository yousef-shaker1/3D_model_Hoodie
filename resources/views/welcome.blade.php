<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoodie Customizer 3D</title>
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #fff;
            display: flex;
            height: 100vh;
            background: #111;
            overflow: hidden;
        }
        
        .sidebar {
            width: 220px;
            background: #1c1c1c;
            padding: 20px;
            overflow-y: auto;
            z-index: 10;
        }
        
        .sidebar h3 {
            margin-top: 0;
        }
        
        .logo-item {
            width: 120px;
            cursor: grab;
            border: 2px solid transparent;
            transition: all 0.2s;
            margin-bottom: 10px;
            user-select: none;
        }
        
        .logo-item:hover {
            border: 2px solid #4CAF50;
            transform: scale(1.05);
        }
        
        .logo-item:active {
            cursor: grabbing;
            opacity: 0.7;
        }
        
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #605252;
            position: relative;
        }
        
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
        }
        
        .view-btn {
            background: rgba(0,0,0,0.7);
            color: white;
            border: 2px solid #4CAF50;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s;
        }
        
        .view-btn:hover {
            background: #4CAF50;
        }
        
        .view-btn.active {
            background: #4CAF50;
        }
        
        .hoodie-container {
            width: 100%;
            max-width: 500px;
            height: 500px;
            position: relative;
        }
        
        .hoodie-wrapper {
            width: 100%;
            height: 100%;
            position: relative;
        }
        
        .hoodie-wrapper.drag-over::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 3px dashed #4CAF50;
            pointer-events: none;
            z-index: 1000;
        }
        
        model-viewer {
            width: 100%;
            height: 100%;
            pointer-events: auto;
        }
        
        /* Logo overlay layer */
        .logos-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 10;
        }
        
        /* Disable all logo interactions in free control mode */
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
            width: 150px;
            height: 150px;
            pointer-events: auto;
            cursor: move;
            user-select: none;
            opacity: 0;
            /* SMOOTH TRANSITION - هنا التحسين */
            transition: opacity 0.4s ease-in-out, transform 0.3s ease-in-out;
            transform: scale(0.95);
        }
        
        .logo-on-hoodie.active {
            opacity: 1;
            transform: scale(1);
        }
        
        .logo-on-hoodie img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            pointer-events: none;
            border: 2px dashed transparent;
            transition: border 0.2s;
        }
        
        .logo-on-hoodie:hover img,
        .logo-on-hoodie.dragging img {
            border: 2px dashed #4CAF50;
        }
        
        .logo-on-hoodie .delete-btn {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #f44336;
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 10;
        }
        
        /* Show delete button only on desktop */
        @media (min-width: 769px) {
            .logo-on-hoodie:hover .delete-btn {
                opacity: 1;
            }
        }
        
        .logo-on-hoodie .resize-handle {
            position: absolute;
            bottom: -10px;
            right: -10px;
            width: 24px;
            height: 24px;
            background: #4CAF50;
            border-radius: 50%;
            cursor: nwse-resize;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 10;
        }
        
        /* Show resize handle only on desktop */
        @media (min-width: 769px) {
            .logo-on-hoodie:hover .resize-handle {
                opacity: 1;
            }
        }
        
        .drag-preview {
            position: fixed;
            width: 100px;
            pointer-events: none;
            z-index: 9999;
            opacity: 0.7;
        }
        
        .instructions {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.8);
            padding: 15px 20px;
            border-radius: 8px;
            font-size: 12px;
            max-width: 90%;
            text-align: center;
            z-index: 100;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 120px;
                padding: 10px;
            }
            
            .logo-item {
                width: 70px;
            }
            
            .hoodie-container {
                max-width: 100%;
                height: 400px;
            }
            
            .view-controls {
                top: 10px;
                gap: 4px;
                max-width: 95%;
            }
            
            .view-btn {
                padding: 6px 10px;
                font-size: 10px;
                white-space: nowrap;
            }
            
            .instructions {
                font-size: 10px;
                padding: 10px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>اللوجو</h3>
        <img src="{{ asset('assets/images/8.png') }}" class="logo-item" data-logo="logo1" alt="Logo">
        
        <div style="margin-top: 30px; font-size: 11px; opacity: 0.7; line-height: 1.6;">
            <p><strong>التعليمات:</strong></p>
            <p>1️⃣ اختر منظر (وش/ظهر/جنب)</p>
            <p>2️⃣ اسحب اللوجو وحطه على الهودي</p>
            <p>3️⃣ اسحب اللوجو لتحريكه</p>
            <p>4️⃣ موبايل: صباعين على اللوجو للتكبير</p>
            <p>5️⃣ كمبيوتر: اسحب الدائرة الخضراء</p>
            <p>6️⃣ دوستين سريعة على اللوجو للحذف</p>
        </div>
    </div>
    
    <div class="main">
        <div class="view-controls">
            <button class="view-btn active" data-view="front">الوش</button>
            <button class="view-btn" data-view="back">الظهر</button>
            <button class="view-btn" data-view="left">جنب يسار</button>
            <button class="view-btn" data-view="right">جنب يمين</button>
            <button class="view-btn" id="freeControlBtn" style="background: #2196F3; border-color: #2196F3;">🖐️ تحكم حر</button>
            <button class="view-btn" id="previewBtn" style="background: #FF9800; border-color: #FF9800;">🔄 معاينة شاملة</button>
        </div>
        
        <div class="hoodie-container">
            <div class="hoodie-wrapper" id="hoodieWrapper">
                <model-viewer 
                    id="hoodieModel"
                    src="assets/images/t_shirt_hoodie_3d_model.glb" 
                    alt="3D Hoodie"
                    disable-zoom
                    disable-pan
                    touch-action="none"
                    camera-orbit="0deg 75deg 105%"
                    field-of-view="auto"
                    camera-target="auto auto auto"
                    interaction-prompt="none">
                </model-viewer>
                
                <!-- Logo overlay layer -->
                <div class="logos-overlay" id="logosOverlay"></div>
            </div>
        </div>
        
        <div class="instructions">
            📱 **للتجربة من الموبايل:** حط لوجو → حط صباعين على اللوجو → بعّد/قرّب الصوابع = تكبير/تصغير • دوستين بسرعة = حذف
        </div>
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
        
        let currentView = 'front';
        let logoCounter = 0;
        let isPreviewMode = false;
        let isFreeControlMode = false;
        let previewInterval = null;
        let dragPreview = null;
        let isDraggingFromSidebar = false;
        let currentDragSource = null;
        
        // Store logos by view
        let logosByView = {
            front: [],
            back: [],
            left: [],
            right: []
        };
        
        // Camera positions for different views
        const cameraViews = {
            front: '0deg 75deg 105%',
            back: '180deg 75deg 105%',
            left: '90deg 75deg 105%',
            right: '-90deg 75deg 105%'
        };
        
        // Wait for model to load
        modelViewer.addEventListener('load', () => {
            console.log('Model loaded successfully');
            modelViewer.cameraOrbit = cameraViews.front;
            modelViewer.cameraTarget = 'auto auto auto';
        });
        
        // Listen to camera changes in free control mode to update visible logos
        modelViewer.addEventListener('camera-change', () => {
            if (isFreeControlMode) {
                const orbit = modelViewer.getCameraOrbit();
                const theta = orbit.theta;
                
                // Determine which view we're closest to based on camera angle
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
                
                // Update visible logos based on closest view
                if (closestView !== currentView) {
                    currentView = closestView;
                    updateVisibleLogos();
                }
            }
        });
        
        // ===== DRAG AND DROP FROM SIDEBAR =====
        
        logoItems.forEach(item => {
            // Desktop drag
            item.addEventListener('dragstart', (e) => {
                currentDragSource = item;
                e.dataTransfer.effectAllowed = 'copy';
                e.dataTransfer.setData('text/plain', item.src);
            });
            
            item.addEventListener('dragend', () => {
                currentDragSource = null;
            });
            
            // Touch drag
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
        
        // Desktop drop
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
        
        // ===== VIEW CONTROLS =====
        
        viewButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                if (isPreviewMode) {
                    stopPreview();
                }
                if (isFreeControlMode) {
                    stopFreeControl();
                }
                viewButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentView = this.dataset.view;
                
                modelViewer.cameraOrbit = cameraViews[currentView];
                updateVisibleLogos();
            });
        });
        
        previewBtn.addEventListener('click', function() {
            if (isPreviewMode) {
                stopPreview();
            } else {
                startPreview();
            }
        });
        
        freeControlBtn.addEventListener('click', function() {
            if (isFreeControlMode) {
                stopFreeControl();
            } else {
                startFreeControl();
            }
        });
        
        function startFreeControl() {
            if (isPreviewMode) {
                stopPreview();
            }
            
            isFreeControlMode = true;
            freeControlBtn.textContent = '🔒 قفل الوضع';
            freeControlBtn.style.background = '#f44336';
            
            // Add class to body to disable logo interactions
            document.body.classList.add('free-control-active');
            
            // Enable camera controls
            modelViewer.setAttribute('camera-controls', '');
            modelViewer.setAttribute('touch-action', 'pan-y');
            
            // Deactivate view buttons
            viewButtons.forEach(b => b.classList.remove('active'));
        }
        
        function stopFreeControl() {
            isFreeControlMode = false;
            freeControlBtn.textContent = '🖐️ تحكم حر';
            freeControlBtn.style.background = '#2196F3';
            
            // Remove class from body to re-enable logo interactions
            document.body.classList.remove('free-control-active');
            
            // Disable camera controls
            modelViewer.removeAttribute('camera-controls');
            modelViewer.setAttribute('touch-action', 'none');
            
            // Return to current view
            modelViewer.cameraOrbit = cameraViews[currentView];
            updateVisibleLogos();
            
            // Reactivate current view button
            viewButtons.forEach(b => {
                if (b.dataset.view === currentView) {
                    b.classList.add('active');
                }
            });
        }
        
        function startPreview() {
            if (isFreeControlMode) {
                stopFreeControl();
            }
            
            isPreviewMode = true;
            previewBtn.textContent = '⏸️ إيقاف المعاينة';
            previewBtn.style.background = '#f44336';
            
            const views = ['front', 'right', 'back', 'left'];
            let index = 0;
            
            previewInterval = setInterval(() => {
                currentView = views[index];
                modelViewer.cameraOrbit = cameraViews[currentView];
                
                // Update visible logos for current view
                updateVisibleLogos();
                
                viewButtons.forEach(b => {
                    if (b.dataset.view === currentView) {
                        b.classList.add('active');
                    } else {
                        b.classList.remove('active');
                    }
                });
                
                index = (index + 1) % views.length;
            }, 2500);
        }
        
        function stopPreview() {
            isPreviewMode = false;
            previewBtn.textContent = '🔄 معاينة شاملة';
            previewBtn.style.background = '#FF9800';
            if (previewInterval) {
                clearInterval(previewInterval);
                previewInterval = null;
            }
            // Return to showing only current view logos
            updateVisibleLogos();
        }
        
        // ===== ADD LOGO =====
        
        function addLogo(src, x, y) {
            logoCounter++;
            
            const logoData = {
                id: logoCounter,
                src: src,
                x: x - 75,
                y: y - 75,
                width: 150,
                height: 150,
                view: currentView
            };
            
            logosByView[currentView].push(logoData);
            createLogoElement(logoData);
        }
        
        function createLogoElement(data) {
            const logo = document.createElement('div');
            logo.className = 'logo-on-hoodie';
            logo.dataset.id = data.id;
            logo.dataset.view = data.view;
            logo.style.left = data.x + 'px';
            logo.style.top = data.y + 'px';
            logo.style.width = data.width + 'px';
            logo.style.height = data.height + 'px';
            
            const img = document.createElement('img');
            img.src = data.src;
            img.draggable = false;
            logo.appendChild(img);
            
            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'delete-btn';
            deleteBtn.innerHTML = '✕';
            deleteBtn.onclick = () => {
                logosByView[data.view] = logosByView[data.view].filter(l => l.id !== data.id);
                logo.remove();
            };
            logo.appendChild(deleteBtn);
            
            const resizeHandle = document.createElement('div');
            resizeHandle.className = 'resize-handle';
            logo.appendChild(resizeHandle);
            
            logosOverlay.appendChild(logo);
            
            if (data.view === currentView) {
                logo.classList.add('active');
            }
            
            makeDraggable(logo, data);
            makeResizable(logo, data, resizeHandle);
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
        }
        
        // ===== DRAGGING =====
        
        function makeDraggable(logo, data) {
            let isDragging = false;
            let startX, startY;
            let startLeft, startTop;
            
            // Pinch zoom variables
            let isPinching = false;
            let initialDistance = 0;
            let initialSize = 0;
            
            function startDrag(e) {
                if (isDraggingFromSidebar) return;
                if (e.target.classList.contains('delete-btn') || 
                    e.target.classList.contains('resize-handle')) return;
                
                // Check if it's a pinch gesture
                if (e.touches && e.touches.length === 2) {
                    return; // Let pinch handler deal with it
                }
                
                e.preventDefault();
                e.stopPropagation();
                isDragging = true;
                
                const touch = e.touches ? e.touches[0] : e;
                startX = touch.clientX;
                startY = touch.clientY;
                startLeft = data.x;
                startTop = data.y;
                
                logo.classList.add('dragging');
            }
            
            function drag(e) {
                if (!isDragging) return;
                if (isPinching) return; // Don't drag while pinching
                
                e.preventDefault();
                
                const touch = e.touches ? e.touches[0] : e;
                const deltaX = touch.clientX - startX;
                const deltaY = touch.clientY - startY;
                
                data.x = startLeft + deltaX;
                data.y = startTop + deltaY;
                
                // Keep within bounds
                const rect = hoodieWrapper.getBoundingClientRect();
                data.x = Math.max(0, Math.min(data.x, rect.width - data.width));
                data.y = Math.max(0, Math.min(data.y, rect.height - data.height));
                
                logo.style.left = data.x + 'px';
                logo.style.top = data.y + 'px';
            }
            
            function stopDrag() {
                if (isDragging) {
                    isDragging = false;
                    logo.classList.remove('dragging');
                }
            }
            
            // Pinch zoom handlers
            function handlePinchStart(e) {
                if (e.touches.length === 2) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    isPinching = true;
                    isDragging = false; // Stop dragging if it was happening
                    
                    const touch1 = e.touches[0];
                    const touch2 = e.touches[1];
                    initialDistance = Math.hypot(
                        touch2.clientX - touch1.clientX,
                        touch2.clientY - touch1.clientY
                    );
                    initialSize = data.width;
                }
            }
            
            function handlePinchMove(e) {
                if (e.touches.length === 2 && isPinching) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const touch1 = e.touches[0];
                    const touch2 = e.touches[1];
                    const currentDistance = Math.hypot(
                        touch2.clientX - touch1.clientX,
                        touch2.clientY - touch1.clientY
                    );
                    
                    const scale = currentDistance / initialDistance;
                    const newSize = Math.max(50, Math.min(400, initialSize * scale));
                    
                    data.width = newSize;
                    data.height = newSize;
                    logo.style.width = newSize + 'px';
                    logo.style.height = newSize + 'px';
                }
            }
            
            function handlePinchEnd(e) {
                if (isPinching && e.touches.length < 2) {
                    e.preventDefault();
                    isPinching = false;
                }
            }
            
            // Mouse events (desktop)
            logo.addEventListener('mousedown', startDrag);
            document.addEventListener('mousemove', drag);
            document.addEventListener('mouseup', stopDrag);
            
            // Touch events (mobile)
            logo.addEventListener('touchstart', (e) => {
                if (e.touches.length === 2) {
                    handlePinchStart(e);
                } else if (e.touches.length === 1) {
                    startDrag(e);
                }
            }, { passive: false });
            
            logo.addEventListener('touchmove', (e) => {
                if (e.touches.length === 2) {
                    handlePinchMove(e);
                } else if (e.touches.length === 1 && !isPinching) {
                    drag(e);
                }
            }, { passive: false });
            
            logo.addEventListener('touchend', (e) => {
                handlePinchEnd(e);
                if (e.touches.length === 0) {
                    stopDrag();
                }
            }, { passive: false });
            
            // Double tap to delete on mobile
            let lastTapTime = 0;
            logo.addEventListener('touchend', (e) => {
                if (isPinching || isDragging) return; // Don't delete while interacting
                
                const currentTime = new Date().getTime();
                const tapLength = currentTime - lastTapTime;
                
                if (tapLength < 300 && tapLength > 0 && e.touches.length === 0) {
                    // Double tap detected
                    e.preventDefault();
                    logosByView[data.view] = logosByView[data.view].filter(l => l.id !== data.id);
                    logo.remove();
                }
                lastTapTime = currentTime;
            }, { passive: false });
        }
        
        // ===== RESIZING (Desktop only) =====
        
        function makeResizable(logo, data, handle) {
            let isResizing = false;
            let startSize, startY;
            
            function startResize(e) {
                e.stopPropagation();
                e.preventDefault();
                isResizing = true;
                
                startY = e.clientY;
                startSize = data.width;
            }
            
            function resize(e) {
                if (!isResizing) return;
                e.preventDefault();
                
                const delta = e.clientY - startY;
                const newSize = Math.max(50, Math.min(400, startSize + delta));
                
                data.width = newSize;
                data.height = newSize;
                logo.style.width = newSize + 'px';
                logo.style.height = newSize + 'px';
            }
            
            function stopResize() {
                isResizing = false;
            }
            
            // Only mouse events for desktop
            handle.addEventListener('mousedown', startResize);
            document.addEventListener('mousemove', resize);
            document.addEventListener('mouseup', stopResize);
        }
    </script>
</body>
</html>