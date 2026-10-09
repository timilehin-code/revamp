<?php
include 'includes/header.php';
include 'includes/navigation.php';
?>
<section>
    <div class="canvas-wrapper inactive">
        <form id="noteForm" action="/revamp/controllers/guests" method="POST" novalidate>
            <button class="close"><i class="fa-solid fa-xmark"></i></button>
            <!-- Name Input Field -->
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

            <div>
                <label for="fullName" class="form-label fw-semibold">
                    Name <span class="text-danger">*</span>
                </label>
                <input type="text" name="fullName" class="form-control form-control-lg" id="fullName" placeholder="e.g. Oluwatimilehin" required>
                <div class="field-error" id="fullNameError">Please enter your name.</div>

            </div>

            <!-- Short Note Input Field -->
            <div>
                <label for="shortNote" class="form-label fw-semibold">
                    short note <span class="text-danger">*</span>
                </label>
                <input type="text" name="shortNote" class="form-control form-control-lg" id="shortNote" placeholder="e.g. I love timi so much" required>
                <div class="field-error" id="shortNoteError">Please enter a short note.</div>
            </div>
            <input type="hidden" name="signature_data" id="signatureData">
            <!-- Drawing Canvas Element -->
            <div>
                <div class="canvas-container" id="canvasContainer">
                    <canvas id="drawingCanvas"></canvas>

                    <div id="canvasPlaceholder" class="canvas-placeholder">
                        <i class="fa-solid fa-pen"></i>
                        <div class="fw-semibold mt-1">Sign or Draw Here</div>
                        <div class="small opacity-75">Mouse & Touch Supported</div>
                    </div>
                </div>

                <!-- Canvas Controls Toolbar -->
                <div class="canvas-tools" style="margin-top: 8px;">
                    <div class="tool-group">
                        <div class="swatch-list">
                            <span class="swatch active" data-color="#8a2be2" style="background-color: #8a2be2;" title="Purple"></span>
                            <span class="swatch" data-color="#0f172a" style="background-color: #0f172a;" title="Dark Slate"></span>
                            <span class="swatch" data-color="#00f0ff" style="background-color: #00f0ff;" title="Neon Blue"></span>
                            <span class="swatch" data-color="#10b981" style="background-color: #10b981;" title="Emerald"></span>
                            <span class="swatch" data-color="#ff4757" style="background-color: #ff4757;" title="Coral"></span>
                        </div>
                        <input type="color" id="customColor" class="custom-color-btn" value="#8a2be2" title="Custom Color">
                    </div>

                    <div class="tool-group">
                        <div class="brush-size-box">
                            <i class="fa-solid fa-circle" style="font-size: 0.4rem;"></i>
                            <input type="range" id="brushSize" min="1" max="20" value="3">
                            <span id="brushSizeVal">3px</span>
                        </div>

                        <button type="button" id="eraserBtn" class="tool-btn" title="Eraser Mode">
                            <i class="fa-solid fa-eraser"></i>
                            <span>Eraser</span>
                        </button>

                        <button type="button" id="clearCanvasBtn" class="tool-btn btn-clear" title="Clear Canvas">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>

                <div class="field-error" id="canvasError">Please draw or sign on the canvas before submitting.</div>
            </div>

            <!-- Action Buttons -->
            <div class="form-actions">
                <button type="button" id="clearFormBtn" class="btn-reset-form">
                    <i class="fa-solid fa-rotate-left"></i> Clear
                </button>
                <button type="submit" id="submitBtn" class="btn-submit">
                    <i class="fa-solid fa-paper-plane"></i> Submit
                </button>
            </div>

        </form>
    </div>
    <div class="container">
        <div class="guest-header">
            <h3>Guests.</h3>
            <p>Every signature here from someone who stopped by.</p>
            <button class="open-btn">Click here to sign</button>
        </div>
        <hr>
        <div class="signatures">
            <div class="signature">
                <div class="sign">
                    <img src="views/assets/img/signature.png" alt="">
                </div>
                <div class="guest">
                    <P>Timi</P>
                    <small>10 oct 2026 14:11</small>
                </div>
            </div>
            <div class="signature">
                <div class="sign">
                    <img src="views/assets/img/signature.png" alt="">
                </div>
                <div class="guest">
                    <P>Timi</P>
                    <small>10 oct 2026 14:11</small>
                </div>
            </div>
            <div class="signature">
                <div class="sign">
                    <img src="views/assets/img/signature.png" alt="">
                </div>
                <div class="guest">
                    <P>Timi</P>
                    <small>10 oct 2026 14:11</small>
                </div>
            </div>
            <div class="signature">
                <div class="sign">
                    <img src="views/assets/img/signature.png" alt="">
                </div>
                <div class="guest">
                    <P>Timi</P>
                    <small>10 oct 2026 14:11</small>
                </div>
            </div>
        </div>

    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Safety Guard Check: Ensure required form elements exist before running
        const form = document.getElementById('noteForm');
        const canvas = document.getElementById('drawingCanvas');
        const fullNameInput = document.getElementById('fullName');
        const shortNoteInput = document.getElementById('shortNote');

        if (!form || !canvas || !fullNameInput || !shortNoteInput) {
            return;
        }

        // 2. DOM Elements
        const ctx = canvas.getContext('2d');
        const signatureDataInput = document.getElementById('signatureData');

        const fullNameError = document.getElementById('fullNameError');
        const shortNoteError = document.getElementById('shortNoteError');
        const canvasError = document.getElementById('canvasError');

        const canvasContainer = document.getElementById('canvasContainer');
        const canvasPlaceholder = document.getElementById('canvasPlaceholder');

        const swatches = document.querySelectorAll('.swatch');
        const customColor = document.getElementById('customColor');
        const brushSize = document.getElementById('brushSize');
        const brushSizeVal = document.getElementById('brushSizeVal');
        const eraserBtn = document.getElementById('eraserBtn');
        const clearCanvasBtn = document.getElementById('clearCanvasBtn');
        const clearFormBtn = document.getElementById('clearFormBtn');

        // Modal Triggers
        const openModalBtn = document.querySelector('.open-btn');
        const closeModalBtn = document.querySelector('.close');
        const canvasWrapper = document.querySelector('.canvas-wrapper');

        // Optional Confirmation Preview Modal
        const confirmationModal = document.getElementById('confirmationModal');
        const modalNameVal = document.getElementById('modalNameVal');
        const modalNoteVal = document.getElementById('modalNoteVal');
        const modalImgVal = document.getElementById('modalImgVal');
        const closeModalCross = document.getElementById('closeModalCross');
        const closeConfirmBtn = document.getElementById('closeModalBtn');
        const downloadBtn = document.getElementById('downloadBtn');

        // State Variables
        let isDrawing = false;
        let hasDrawn = false;
        let isEraserMode = false;
        let activeColor = '#8a2be2';
        let strokeWidth = 3;
        let lastPos = {
            x: 0,
            y: 0
        };

        // Prevent default touch scrolling on canvas
        canvas.style.touchAction = 'none';

        // 3. Canvas Resizing & Resolution Handling
        function resizeCanvas() {
            const container = canvasContainer || canvas.parentElement;
            if (!container) return;

            const rect = container.getBoundingClientRect();
            if (rect.width === 0 || rect.height === 0) return;

            const dpr = window.devicePixelRatio || 1;

            let savedImage = null;
            if (hasDrawn) {
                savedImage = canvas.toDataURL();
            }

            canvas.width = rect.width * dpr;
            canvas.height = rect.height * dpr;

            ctx.scale(dpr, dpr);
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';

            if (savedImage) {
                const img = new Image();
                img.src = savedImage;
                img.onload = () => {
                    ctx.drawImage(img, 0, 0, rect.width, rect.height);
                };
            }
        }

        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        // 4. Modal Open/Close Controls
        if (openModalBtn && canvasWrapper) {
            openModalBtn.addEventListener('click', () => {
                canvasWrapper.classList.remove('inactive');
                setTimeout(resizeCanvas, 50);
            });
        }

        if (closeModalBtn && canvasWrapper) {
            closeModalBtn.addEventListener('click', () => {
                canvasWrapper.classList.add('inactive');
            });
        }

        // 5. Drawing Engine (Pointer Events)
        function getCoordinates(e) {
            const rect = canvas.getBoundingClientRect();
            return {
                x: e.clientX - rect.left,
                y: e.clientY - rect.top
            };
        }

        function startDrawing(e) {
            isDrawing = true;
            lastPos = getCoordinates(e);

            if (!hasDrawn) {
                hasDrawn = true;
                if (canvasPlaceholder) canvasPlaceholder.classList.add('hidden');
                if (canvasError) canvasError.classList.remove('active');
            }
            canvas.setPointerCapture(e.pointerId);
        }

        function draw(e) {
            if (!isDrawing) return;

            const currentPos = getCoordinates(e);

            ctx.beginPath();
            ctx.moveTo(lastPos.x, lastPos.y);
            ctx.lineTo(currentPos.x, currentPos.y);

            if (isEraserMode) {
                ctx.globalCompositeOperation = 'destination-out';
                ctx.lineWidth = strokeWidth * 2.5;
            } else {
                ctx.globalCompositeOperation = 'source-over';
                ctx.strokeStyle = activeColor;
                ctx.lineWidth = strokeWidth;
            }

            ctx.stroke();
            lastPos = currentPos;
        }

        function stopDrawing(e) {
            if (!isDrawing) return;
            isDrawing = false;
            try {
                canvas.releasePointerCapture(e.pointerId);
            } catch (err) {}
        }

        canvas.addEventListener('pointerdown', startDrawing);
        canvas.addEventListener('pointermove', draw);
        window.addEventListener('pointerup', stopDrawing);
        window.addEventListener('pointercancel', stopDrawing);

        // 6. Color & Brush Controls
        swatches.forEach(swatch => {
            swatch.addEventListener('click', () => {
                swatches.forEach(s => s.classList.remove('active'));
                swatch.classList.add('active');
                activeColor = swatch.getAttribute('data-color') || '#8a2be2';
                if (customColor) customColor.value = activeColor;
                setEraser(false);
            });
        });

        if (customColor) {
            customColor.addEventListener('input', (e) => {
                activeColor = e.target.value;
                swatches.forEach(s => s.classList.remove('active'));
                setEraser(false);
            });
        }

        if (brushSize) {
            brushSize.addEventListener('input', (e) => {
                strokeWidth = parseInt(e.target.value, 10) || 3;
                if (brushSizeVal) brushSizeVal.textContent = `${strokeWidth}px`;
            });
        }

        function setEraser(enable) {
            isEraserMode = enable;
            if (eraserBtn) {
                eraserBtn.classList.toggle('active', isEraserMode);
            }
        }

        if (eraserBtn) {
            eraserBtn.addEventListener('click', () => setEraser(!isEraserMode));
        }

        function resetCanvas() {
            const rect = canvas.getBoundingClientRect();
            ctx.clearRect(0, 0, rect.width, rect.height);
            hasDrawn = false;
            if (canvasPlaceholder) canvasPlaceholder.classList.remove('hidden');
        }

        if (clearCanvasBtn) {
            clearCanvasBtn.addEventListener('click', resetCanvas);
        }

        // 7. Form Validation & Error Clearing
        function clearFormErrors() {
            fullNameInput.classList.remove('invalid');
            shortNoteInput.classList.remove('invalid');
            if (fullNameError) fullNameError.classList.remove('active');
            if (shortNoteError) shortNoteError.classList.remove('active');
            if (canvasError) canvasError.classList.remove('active');
        }

        fullNameInput.addEventListener('input', () => {
            if (fullNameInput.value.trim()) {
                fullNameInput.classList.remove('invalid');
                if (fullNameError) fullNameError.classList.remove('active');
            }
        });

        shortNoteInput.addEventListener('input', () => {
            if (shortNoteInput.value.trim()) {
                shortNoteInput.classList.remove('invalid');
                if (shortNoteError) shortNoteError.classList.remove('active');
            }
        });

        if (clearFormBtn) {
            clearFormBtn.addEventListener('click', () => {
                form.reset();
                resetCanvas();
                clearFormErrors();
                setEraser(false);
            });
        }

        // 8. Base64 Export with White Background
        function exportSignatureDataUrl() {
            const exportCanvas = document.createElement('canvas');
            const exportCtx = exportCanvas.getContext('2d');

            exportCanvas.width = canvas.width;
            exportCanvas.height = canvas.height;

            // Draw solid white background to avoid transparent black rendering
            exportCtx.fillStyle = '#ffffff';
            exportCtx.fillRect(0, 0, exportCanvas.width, exportCanvas.height);
            exportCtx.drawImage(canvas, 0, 0);

            return exportCanvas.toDataURL('image/png');
        }

        // 9. Form Submission Handling
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            clearFormErrors();

            const nameVal = fullNameInput.value.trim();
            const noteVal = shortNoteInput.value.trim();
            let valid = true;

            if (!nameVal) {
                fullNameInput.classList.add('invalid');
                if (fullNameError) fullNameError.classList.add('active');
                valid = false;
            }

            if (!noteVal) {
                shortNoteInput.classList.add('invalid');
                if (shortNoteError) shortNoteError.classList.add('active');
                valid = false;
            }

            if (!hasDrawn) {
                if (canvasError) canvasError.classList.add('active');
                valid = false;
            }

            if (!valid) return;

            const dataUrl = exportSignatureDataUrl();

            // Pass Base64 string to hidden input field
            if (signatureDataInput) {
                signatureDataInput.value = dataUrl;
            }

            if (confirmationModal) {
                if (modalNameVal) modalNameVal.textContent = nameVal;
                if (modalNoteVal) modalNoteVal.textContent = noteVal;
                if (modalImgVal) modalImgVal.src = dataUrl;

                confirmationModal.classList.add('active');
            } else {
                form.submit();
            }
        });

        // 10. Confirmation Modal Action Listeners
        if (closeModalCross) {
            closeModalCross.addEventListener('click', () => {
                if (confirmationModal) confirmationModal.classList.remove('active');
            });
        }

        if (closeConfirmBtn) {
            closeConfirmBtn.addEventListener('click', () => {
                if (confirmationModal) confirmationModal.classList.remove('active');
                if (clearFormBtn) clearFormBtn.click();
            });
        }

        if (downloadBtn) {
            downloadBtn.addEventListener('click', () => {
                const name = fullNameInput.value.trim().toLowerCase().replace(/[^a-z0-9]/g, '_') || 'signature';
                const a = document.createElement('a');
                a.download = `${name}_signature.png`;
                a.href = modalImgVal ? modalImgVal.src : exportSignatureDataUrl();
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });
        }
    });
</script>
<?php
include 'includes/footer.php';
include 'includes/script.php';
?>