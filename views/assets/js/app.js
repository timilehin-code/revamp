// light mode begings
let lightMode = localStorage.getItem("lightmode");
const modeToggler = document.querySelector(".toggler");

const enableLightMode = () => {
  document.body.classList.add("lightmode");
  localStorage.setItem("lightmode", "enabled");
};

const disableLightMode = () => {
  document.body.classList.remove("lightmode");
  localStorage.setItem("lightmode", "disabled");
};

if (lightMode === "enabled") {
  enableLightMode();
}
if (modeToggler) {
  modeToggler.addEventListener("click", () => {
    lightMode = localStorage.getItem("lightmode");
    if (lightMode !== "enabled") {
      enableLightMode();
      console.log(lightMode);
    } else if (lightMode == "enabled") {
      disableLightMode();
      console.log(lightMode);
    }
  });
}

// email js

function sendEmail() {
  // Get form values
  const name = document.getElementById("name").value;
  const email = document.getElementById("email").value;
  const subject = document.getElementById("subject").value;
  const message = document.getElementById("message").value;
  const statusElement = document.getElementById("status");
  const submitBtn = document.querySelector(".send-btn");

  // Validate form
  if (!name || !email || !subject || !message) {
    statusElement.textContent = "Please fill in all fields";
    statusElement.className = "text-danger text-center mt-4";
    statusElement.style.display = "block";
    setTimeout(() => {
      statusElement.style.display = "none";
    }, 5000);
    return;
  }

  // Disable button to prevent multiple submissions
  submitBtn.disabled = true;
  submitBtn.textContent = "Sending...";
}

// lenis js for smooth scroll

const lenis = new Lenis();
function raf(time) {
  lenis.raf(time);
  requestAnimationFrame(raf);
}

requestAnimationFrame(raf);

// current year in roman numerals
function toRoman(year) {
  const lookup = {
    M: 1000,
    CM: 900,
    D: 500,
    CD: 400,
    C: 100,
    XC: 90,
    L: 50,
    XL: 40,
    X: 10,
    IX: 9,
    V: 5,
    IV: 4,
    I: 1,
  };
  let roman = "";
  for (let key in lookup) {
    while (year >= lookup[key]) {
      roman += key;
      year -= lookup[key];
    }
  }
  return roman;
}
const currentYear = new Date().getFullYear();
// Set the current year in the footer
const year = document.getElementById("currentYear");

if (year) {
  year.textContent = toRoman(currentYear);
}
// spotify widget api
// ========== CONFIG ==========
const clientId = "e7619962a8fa47318570e814246e29f2"; // from Spotify dashboard
const redirectUri = "http://127.0.0.1:5502/"; // must match dashboard exactly
const scopes = "user-read-currently-playing user-read-playback-state";

// ========== HELPERS ==========
function generateRandomString(length) {
  const possible =
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  const values = crypto.getRandomValues(new Uint8Array(length));
  return values.reduce((acc, x) => acc + possible[x % possible.length], "");
}

async function sha256(plain) {
  const encoder = new TextEncoder();
  const data = encoder.encode(plain);
  return window.crypto.subtle.digest("SHA-256", data);
}

function base64encode(input) {
  return btoa(String.fromCharCode(...new Uint8Array(input)))
    .replace(/=/g, "")
    .replace(/\+/g, "-")
    .replace(/\//g, "_");
}

// ========== LOGIN (call this) ==========
async function login() {
  const codeVerifier = generateRandomString(64);
  localStorage.setItem("code_verifier", codeVerifier);

  const hashed = await sha256(codeVerifier);
  const codeChallenge = base64encode(hashed);

  const params = new URLSearchParams({
    client_id: clientId,
    response_type: "code",
    redirect_uri: redirectUri,
    scope: scopes,
    code_challenge_method: "S256",
    code_challenge: codeChallenge,
  });

  window.location = `https://accounts.spotify.com/authorize?${params.toString()}`;
}

// ========== HANDLE THE CALLBACK ==========
async function handleCallback() {
  const params = new URLSearchParams(window.location.search);
  const code = params.get("code");

  if (!code) return; // not coming back from Spotify

  const codeVerifier = localStorage.getItem("code_verifier");
  if (!codeVerifier) {
    console.error("No code_verifier found. Please click Login again.");
    return;
  }

  const body = new URLSearchParams({
    client_id: clientId,
    grant_type: "authorization_code",
    code: code,
    redirect_uri: redirectUri,
    code_verifier: codeVerifier,
  });

  const res = await fetch("https://accounts.spotify.com/api/token", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body,
  });

  const data = await res.json();
  console.log("Token response:", data);

  if (data.access_token) {
    localStorage.setItem("access_token", data.access_token);
    localStorage.setItem("refresh_token", data.refresh_token);
    console.log("✅ Tokens saved successfully!");

    // Clean the URL
    window.history.replaceState({}, document.title, "/");
  } else {
    console.error("Failed to get tokens:", data);
  }
}

// Run this every time the page loads
handleCallback();

// ========== GET CURRENTLY PLAYING ==========
async function getCurrentlyPlaying() {
  let token = localStorage.getItem("access_token");
  if (!token) {
    console.log("No token – please login first");
    return null;
  }

  let res = await fetch(
    "https://api.spotify.com/v1/me/player/currently-playing",
    {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    },
  );

  // Token expired → try to refresh
  if (res.status === 401) {
    console.log("Token expired, refreshing...");
    token = await refreshAccessToken();
    if (!token) return null;

    res = await fetch(
      "https://api.spotify.com/v1/me/player/currently-playing",
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );
  }

  if (res.status === 204) {
    console.log("Nothing is currently playing");
    return null;
  }

  if (!res.ok) {
    console.error("Error:", await res.text());
    return null;
  }

  return await res.json();
}

// ========== REFRESH TOKEN ==========
async function refreshAccessToken() {
  const refreshToken = localStorage.getItem("refresh_token");
  if (!refreshToken) {
    console.error("No refresh token found");
    return null;
  }

  const body = new URLSearchParams({
    client_id: clientId,
    grant_type: "refresh_token",
    refresh_token: refreshToken,
  });

  const res = await fetch("https://accounts.spotify.com/api/token", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body,
  });

  const data = await res.json();

  if (data.access_token) {
    localStorage.setItem("access_token", data.access_token);
    // Spotify sometimes returns a new refresh token
    if (data.refresh_token) {
      localStorage.setItem("refresh_token", data.refresh_token);
    }
    console.log("Access token refreshed");
    return data.access_token;
  } else {
    console.error("Failed to refresh token:", data);
    return null;
  }
}

getCurrentlyPlaying()
  .then((data) => {
    console.log("=== FULL RESPONSE ===");
    console.log(data);

    if (data && data.item) {
      console.log("✅ Song:", data.item.name);
      console.log(
        "✅ Artists:",
        data.item.artists.map((a) => a.name).join(", "),
      );
      console.log("✅ Album art:", data.item.album.images[0]?.url);
      console.log("✅ Is playing:", data.is_playing);
    } else {
      console.log("ℹ️ Nothing is currently playing (or no active device)");
    }
  })
  .catch((err) => {
    console.error("❌ Error occurred:", err);
  });

function formatTime(ms) {
  const totalSeconds = Math.floor(ms / 1000);
  const minutes = Math.floor(totalSeconds / 60);
  const seconds = totalSeconds % 60;
  return `${minutes}:${seconds.toString().padStart(2, "0")}`;
}

async function updateNowPlaying() {
  const container = document.getElementById("spotify-widget");
  if (!container) return;

  const data = await getCurrentlyPlaying();

  if (!data || !data.item) {
    container.innerHTML = `
      <div class="spotify-top">
        <div class="spotify-info">
          <div class="spotify-title">Not playing</div>
          <div class="spotify-artists">Spotify</div>
        </div>
      </div>
    `;
    return;
  }

  const song = data.item.name;
  const artists = data.item.artists.map((a) => a.name).join(", ");
  const image = data.item.album.images[0]?.url || "";
  const isPlaying = data.is_playing;
  const progress = data.progress_ms || 0;
  const duration = data.item.duration_ms || 0;
  const progressPercent = duration > 0 ? (progress / duration) * 100 : 0;

  container.innerHTML = `
    <div class="spotify-top">
      <img class="spotify-art" src="${image}" alt="Album art">
      <div class="spotify-info">
        <div class="spotify-title-row">
          <div class="spotify-title">${song}</div>
          ${
            isPlaying
              ? `
            <div class="spotify-equalizer">
              <span></span><span></span><span></span><span></span>
            </div>
          `
              : ""
          }
        </div>
        <div class="spotify-artists">${artists}</div>
      </div>
    </div>

    <div class="spotify-progress-container">
      <div class="spotify-progress-bar">
        <div class="spotify-progress-fill" style="width: ${progressPercent}%"></div>
      </div>
      <div class="spotify-times">
        <span>${formatTime(progress)}</span>
        <span>${formatTime(duration)}</span>
      </div>
    </div>
  `;
}
// Initial load + auto refresh
updateNowPlaying();
setInterval(updateNowPlaying, 15000); // every 15 seconds

const blogTitle = document.querySelector("#p-title");
const blogSlug = document.querySelector("#p-slug");
if (blogTitle && blogSlug) {
  // const blogTitleValue = blogTitle.value;
  blogTitle.addEventListener("input", () => {
    let SlugValue = blogTitle.value;
    let symbol = "-";
    blogSlug.value = SlugValue.toLowerCase()
      .trim()
      .replace(/[^a-z0-9\s-]/g, "")
      .replace(/\s+/g, symbol)
      .replace(/-+/g, symbol);
  });
}

document.addEventListener("DOMContentLoaded", () => {
  // 1. Safety Guard Check: Ensure required form elements exist before running
  const form = document.getElementById("noteForm");
  const canvas = document.getElementById("drawingCanvas");
  const fullNameInput = document.getElementById("fullName");
  const shortNoteInput = document.getElementById("shortNote");

  if (!form || !canvas || !fullNameInput || !shortNoteInput) {
    return;
  }

  // 2. DOM Elements
  const ctx = canvas.getContext("2d");
  const signatureDataInput = document.getElementById("signatureData");

  const fullNameError = document.getElementById("fullNameError");
  const shortNoteError = document.getElementById("shortNoteError");
  const canvasError = document.getElementById("canvasError");

  const canvasContainer = document.getElementById("canvasContainer");
  const canvasPlaceholder = document.getElementById("canvasPlaceholder");

  const swatches = document.querySelectorAll(".swatch");
  const customColor = document.getElementById("customColor");
  const brushSize = document.getElementById("brushSize");
  const brushSizeVal = document.getElementById("brushSizeVal");
  const eraserBtn = document.getElementById("eraserBtn");
  const clearCanvasBtn = document.getElementById("clearCanvasBtn");
  const clearFormBtn = document.getElementById("clearFormBtn");

  // Modal Triggers
  const openModalBtn = document.querySelector(".open-btn");
  const closeModalBtn = document.querySelector(".close");
  const canvasWrapper = document.querySelector(".canvas-wrapper");

  // Optional Confirmation Preview Modal
  const confirmationModal = document.getElementById("confirmationModal");
  const modalNameVal = document.getElementById("modalNameVal");
  const modalNoteVal = document.getElementById("modalNoteVal");
  const modalImgVal = document.getElementById("modalImgVal");
  const closeModalCross = document.getElementById("closeModalCross");
  const closeConfirmBtn = document.getElementById("closeModalBtn");
  const downloadBtn = document.getElementById("downloadBtn");

  // State Variables
  let isDrawing = false;
  let hasDrawn = false;
  let isEraserMode = false;
  let activeColor = "#8a2be2";
  let strokeWidth = 3;
  let lastPos = {
    x: 0,
    y: 0,
  };

  // Prevent default touch scrolling on canvas
  canvas.style.touchAction = "none";

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
    ctx.lineCap = "round";
    ctx.lineJoin = "round";

    if (savedImage) {
      const img = new Image();
      img.src = savedImage;
      img.onload = () => {
        ctx.drawImage(img, 0, 0, rect.width, rect.height);
      };
    }
  }

  window.addEventListener("resize", resizeCanvas);
  resizeCanvas();

  // 4. Modal Open/Close Controls
  if (openModalBtn && canvasWrapper) {
    openModalBtn.addEventListener("click", () => {
      canvasWrapper.classList.remove("inactive");
      setTimeout(resizeCanvas, 50);
    });
  }

  if (closeModalBtn && canvasWrapper) {
    closeModalBtn.addEventListener("click", () => {
      canvasWrapper.classList.add("inactive");
    });
  }

  // 5. Drawing Engine (Pointer Events)
  function getCoordinates(e) {
    const rect = canvas.getBoundingClientRect();
    return {
      x: e.clientX - rect.left,
      y: e.clientY - rect.top,
    };
  }

  function startDrawing(e) {
    isDrawing = true;
    lastPos = getCoordinates(e);

    if (!hasDrawn) {
      hasDrawn = true;
      if (canvasPlaceholder) canvasPlaceholder.classList.add("hidden");
      if (canvasError) canvasError.classList.remove("active");
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
      ctx.globalCompositeOperation = "destination-out";
      ctx.lineWidth = strokeWidth * 2.5;
    } else {
      ctx.globalCompositeOperation = "source-over";
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

  canvas.addEventListener("pointerdown", startDrawing);
  canvas.addEventListener("pointermove", draw);
  window.addEventListener("pointerup", stopDrawing);
  window.addEventListener("pointercancel", stopDrawing);

  // 6. Color & Brush Controls
  swatches.forEach((swatch) => {
    swatch.addEventListener("click", () => {
      swatches.forEach((s) => s.classList.remove("active"));
      swatch.classList.add("active");
      activeColor = swatch.getAttribute("data-color") || "#8a2be2";
      if (customColor) customColor.value = activeColor;
      setEraser(false);
    });
  });

  if (customColor) {
    customColor.addEventListener("input", (e) => {
      activeColor = e.target.value;
      swatches.forEach((s) => s.classList.remove("active"));
      setEraser(false);
    });
  }

  if (brushSize) {
    brushSize.addEventListener("input", (e) => {
      strokeWidth = parseInt(e.target.value, 10) || 3;
      if (brushSizeVal) brushSizeVal.textContent = `${strokeWidth}px`;
    });
  }

  function setEraser(enable) {
    isEraserMode = enable;
    if (eraserBtn) {
      eraserBtn.classList.toggle("active", isEraserMode);
    }
  }

  if (eraserBtn) {
    eraserBtn.addEventListener("click", () => setEraser(!isEraserMode));
  }

  function resetCanvas() {
    const rect = canvas.getBoundingClientRect();
    ctx.clearRect(0, 0, rect.width, rect.height);
    hasDrawn = false;
    if (canvasPlaceholder) canvasPlaceholder.classList.remove("hidden");
  }

  if (clearCanvasBtn) {
    clearCanvasBtn.addEventListener("click", resetCanvas);
  }

  // 7. Form Validation & Error Clearing
  function clearFormErrors() {
    fullNameInput.classList.remove("invalid");
    shortNoteInput.classList.remove("invalid");
    if (fullNameError) fullNameError.classList.remove("active");
    if (shortNoteError) shortNoteError.classList.remove("active");
    if (canvasError) canvasError.classList.remove("active");
  }

  fullNameInput.addEventListener("input", () => {
    if (fullNameInput.value.trim()) {
      fullNameInput.classList.remove("invalid");
      if (fullNameError) fullNameError.classList.remove("active");
    }
  });

  shortNoteInput.addEventListener("input", () => {
    if (shortNoteInput.value.trim()) {
      shortNoteInput.classList.remove("invalid");
      if (shortNoteError) shortNoteError.classList.remove("active");
    }
  });

  if (clearFormBtn) {
    clearFormBtn.addEventListener("click", () => {
      form.reset();
      resetCanvas();
      clearFormErrors();
      setEraser(false);
    });
  }

  // 8. Base64 Export with White Background
  function exportSignatureDataUrl() {
    const exportCanvas = document.createElement("canvas");
    const exportCtx = exportCanvas.getContext("2d");

    exportCanvas.width = canvas.width;
    exportCanvas.height = canvas.height;

    // Draw solid white background to avoid transparent black rendering
    // exportCtx.fillStyle = "#ffffff";
    // exportCtx.fillRect(0, 0, exportCanvas.width, exportCanvas.height);
    exportCtx.drawImage(canvas, 0, 0);

    return exportCanvas.toDataURL("image/png");
  }

  // 9. Form Submission Handling
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    clearFormErrors();

    const nameVal = fullNameInput.value.trim();
    const noteVal = shortNoteInput.value.trim();
    let valid = true;

    if (!nameVal) {
      fullNameInput.classList.add("invalid");
      if (fullNameError) fullNameError.classList.add("active");
      valid = false;
    }

    if (!noteVal) {
      shortNoteInput.classList.add("invalid");
      if (shortNoteError) shortNoteError.classList.add("active");
      valid = false;
    }

    if (!hasDrawn) {
      if (canvasError) canvasError.classList.add("active");
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

      confirmationModal.classList.add("active");
    } else {
      form.submit();
    }
  });

  // 10. Confirmation Modal Action Listeners
  if (closeModalCross) {
    closeModalCross.addEventListener("click", () => {
      if (confirmationModal) confirmationModal.classList.remove("active");
    });
  }

  if (closeConfirmBtn) {
    closeConfirmBtn.addEventListener("click", () => {
      if (confirmationModal) confirmationModal.classList.remove("active");
      if (clearFormBtn) clearFormBtn.click();
    });
  }

  if (downloadBtn) {
    downloadBtn.addEventListener("click", () => {
      const name =
        fullNameInput.value
          .trim()
          .toLowerCase()
          .replace(/[^a-z0-9]/g, "_") || "signature";
      const a = document.createElement("a");
      a.download = `${name}_signature.png`;
      a.href = modalImgVal ? modalImgVal.src : exportSignatureDataUrl();
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    });
  }
});
