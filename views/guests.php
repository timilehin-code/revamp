<?php
include 'includes/header.php';
include 'includes/navigation.php';
include 'controllers/views/guest.php';
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
            <?php foreach ($allGuests as $guest) { ?>

                <div class="sign">
                    <div class="note">
                        <p><?= htmlspecialchars($guest['note']) ?></p>
                    </div>
                    <div class="signature">
                        <img src="<?= htmlspecialchars($guest['signature']) ?>" alt="<?= htmlspecialchars($guest['name']) ?>'s signature">
                    </div>
                    <div class="guest">
                        <p><?= htmlspecialchars($guest['name']) ?></p>
                        <small><?= date('d M Y H:i', strtotime($guest['created_at'])) ?></small>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
</section>
<script>

</script>
<?php
include 'includes/footer.php';
include 'includes/script.php';
?>