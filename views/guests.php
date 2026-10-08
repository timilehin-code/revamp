<?php
include 'includes/header.php';
include 'includes/navigation.php';
?>
<section>
    <div class="canvas-wrapper inactive">
        <form id="noteForm" action="" novalidate>
            <button class="close"><i class="fa-solid fa-xmark"></i></button>
            <!-- Name Input Field -->
            <div>
                <label for="fullName" class="form-label fw-semibold">
                    Name <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control form-control-lg" id="fullName" placeholder="e.g. Oluwatimilehin" required>
                <div class="field-error" id="fullNameError">Please enter your name.</div>
            </div>

            <!-- Short Note Input Field -->
            <div>
                <label for="shortNote" class="form-label fw-semibold">
                    short note <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control form-control-lg" id="shortNote" placeholder="e.g. I love timi so much" required>
                <div class="field-error" id="shortNoteError">Please enter a short note.</div>
            </div>

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
    const openModal = document.querySelector(".open-btn");
    const CloseModal = document.querySelector(".close");
    const CanvasModal = document.querySelector(".canvas-wrapper");
    openModal.addEventListener("click", () => {
        CanvasModal.classList.remove("inactive")
    })
    CloseModal.addEventListener("click", () => {
        CanvasModal.classList.add("inactive")
    })
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('noteForm');
        const fullNameInput = document.getElementById('fullName');
        const shortNoteInput = document.getElementById('shortNote');

        const fullNameError = document.getElementById('fullNameError');
        const shortNoteError = document.getElementById('shortNoteError');
        const canvasError = document.getElementById('canvasError');

        const canvas = document.getElementById('drawingCanvas');
        const ctx = canvas.getContext('2d');
        const canvasPlaceholder = document.getElementById('canvasPlaceholder');

        const swatches = document.querySelectorAll('.swatch');
        const customColor = document.getElementById('customColor');
        const brushSize = document.getElementById('brushSize');
        const brushSizeVal = document.getElementById('brushSizeVal');
        const eraserBtn = document.getElementById('eraserBtn');
        const clearCanvasBtn = document.getElementById('clearCanvasBtn');
        const clearFormBtn = document.getElementById('clearFormBtn');

        const modal = document.getElementById('confirmationModal');
        const modalNameVal = document.getElementById('modalNameVal');
        const modalNoteVal = document.getElementById('modalNoteVal');
        const modalImgVal = document.getElementById('modalImgVal');
        const closeModalCross = document.getElementById('closeModalCross');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const downloadBtn = document.getElementById('downloadBtn');

        let isDrawing = false;
        let hasDrawn = false;
        let isEraserMode = false;
        let activeColor = '#8a2be2';
        let strokeWidth = 3;
        let lastPos = {
            x: 0,
            y: 0
        };

        function resizeCanvas() {
            const container = canvas.parentElement;
            const rect = container.getBoundingClientRect();
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

        function getCoordinates(e) {
            const rect = canvas.getBoundingClientRect();
            let clientX = e.clientX;
            let clientY = e.clientY;

            if (e.touches && e.touches.length > 0) {
                clientX = e.touches[0].clientX;
                clientY = e.touches[0].clientY;
            }

            return {
                x: clientX - rect.left,
                y: clientY - rect.top
            };
        }

        function startDrawing(e) {
            isDrawing = true;
            lastPos = getCoordinates(e);

            if (!hasDrawn) {
                hasDrawn = true;
                canvasPlaceholder.classList.add('hidden');
                canvasError.classList.remove('active');
            }
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

        function stopDrawing() {
            isDrawing = false;
        }

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        window.addEventListener('mouseup', stopDrawing);

        canvas.addEventListener('touchstart', (e) => {
            e.preventDefault();
            startDrawing(e);
        }, {
            passive: false
        });

        canvas.addEventListener('touchmove', (e) => {
            e.preventDefault();
            draw(e);
        }, {
            passive: false
        });

        window.addEventListener('touchend', stopDrawing);

        swatches.forEach(swatch => {
            swatch.addEventListener('click', () => {
                swatches.forEach(s => s.classList.remove('active'));
                swatch.classList.add('active');
                activeColor = swatch.getAttribute('data-color');
                customColor.value = activeColor;
                setEraser(false);
            });
        });

        customColor.addEventListener('input', (e) => {
            activeColor = e.target.value;
            swatches.forEach(s => s.classList.remove('active'));
            setEraser(false);
        });

        brushSize.addEventListener('input', (e) => {
            strokeWidth = parseInt(e.target.value, 10);
            brushSizeVal.textContent = `${strokeWidth}px`;
        });

        function setEraser(enable) {
            isEraserMode = enable;
            if (isEraserMode) {
                eraserBtn.classList.add('active');
            } else {
                eraserBtn.classList.remove('active');
            }
        }

        eraserBtn.addEventListener('click', () => {
            setEraser(!isEraserMode);
        });

        function resetCanvas() {
            const rect = canvas.getBoundingClientRect();
            ctx.clearRect(0, 0, rect.width, rect.height);
            hasDrawn = false;
            canvasPlaceholder.classList.remove('hidden');
        }

        clearCanvasBtn.addEventListener('click', resetCanvas);

        function clearFormErrors() {
            fullNameInput.classList.remove('invalid');
            shortNoteInput.classList.remove('invalid');
            fullNameError.classList.remove('active');
            shortNoteError.classList.remove('active');
            canvasError.classList.remove('active');
        }

        fullNameInput.addEventListener('input', () => {
            if (fullNameInput.value.trim()) {
                fullNameInput.classList.remove('invalid');
                fullNameError.classList.remove('active');
            }
        });

        shortNoteInput.addEventListener('input', () => {
            if (shortNoteInput.value.trim()) {
                shortNoteInput.classList.remove('invalid');
                shortNoteError.classList.remove('active');
            }
        });

        clearFormBtn.addEventListener('click', () => {
            form.reset();
            resetCanvas();
            clearFormErrors();
            setEraser(false);
        });

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            clearFormErrors();

            const nameVal = fullNameInput.value.trim();
            const noteVal = shortNoteInput.value.trim();
            let valid = true;

            if (!nameVal) {
                fullNameInput.classList.add('invalid');
                fullNameError.classList.add('active');
                valid = false;
            }

            if (!noteVal) {
                shortNoteInput.classList.add('invalid');
                shortNoteError.classList.add('active');
                valid = false;
            }

            if (!hasDrawn) {
                canvasError.classList.add('active');
                valid = false;
            }

            if (!valid) return;

            // Export canvas content with clean white background
            const exportCanvas = document.createElement('canvas');
            const exportCtx = exportCanvas.getContext('2d');
            exportCanvas.width = canvas.width;
            exportCanvas.height = canvas.height;

            exportCtx.fillStyle = '#ffffff';
            exportCtx.fillRect(0, 0, exportCanvas.width, exportCanvas.height);
            exportCtx.drawImage(canvas, 0, 0);

            const dataUrl = exportCanvas.toDataURL('image/png');

            modalNameVal.textContent = nameVal;
            modalNoteVal.textContent = noteVal;
            modalImgVal.src = dataUrl;

            modal.classList.add('active');
        });

        function hideModal() {
            modal.classList.remove('active');
        }

        closeModalCross.addEventListener('click', hideModal);
        closeModalBtn.addEventListener('click', () => {
            hideModal();
            clearFormBtn.click();
        });

        downloadBtn.addEventListener('click', () => {
            const name = fullNameInput.value.trim().toLowerCase().replace(/[^a-z0-9]/g, '_') || 'drawing';
            const a = document.createElement('a');
            a.download = `${name}_signature.png`;
            a.href = modalImgVal.src;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        });



    });
</script>
<?php
include 'includes/footer.php';
include 'includes/script.php';
?>