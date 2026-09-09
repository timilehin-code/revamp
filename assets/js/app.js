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

  // EmailJS parameters
  const templateParams = {
    name: name,
    email: email,
    subject: subject,
    message: message, // Replace with your email address
  };

  const serviceId = "service_z8chcra";
  const templateId = "template_wivdpyk";
  emailjs
    .send(serviceId, templateId, templateParams)
    .then(() => {
      statusElement.textContent = "Message sent successfully!";
      statusElement.className = " text-success text-center mt-4";
      statusElement.style.display = "block";
      // Clear form
      document.getElementById("name").value = "";
      document.getElementById("email").value = "";
      document.getElementById("subject").value = "";
      document.getElementById("message").value = "";

      setTimeout(() => {
        statusElement.style.display = "none";
      }, 5000);
    })
    .catch((error) => {
      statusElement.textContent = `Failed to send message: ${
        error.text || "Unknown error"
      }. Please try again.`;
      statusElement.className = "text-danger text-center mt-4";
      statusElement.style.display = "block";

      setTimeout(() => {
        statusElement.style.display = "none";
      }, 5000);
    })
    .finally(() => {
      // Re-enable button
      submitBtn.disabled = false;
      submitBtn.textContent = "Send";
    });
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
document.getElementById("currentYear").textContent = toRoman(currentYear);

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

async function updateNowPlaying() {
  const container = document.getElementById("spotify-now-playing");
  if (!container) return;

  const data = await getCurrentlyPlaying();

  if (data && data.item) {
    const song = data.item.name;
    const artists = data.item.artists.map((a) => a.name).join(", ");
    const image = data.item.album.images[0]?.url || "";
    const isPlaying = data.is_playing;

    container.innerHTML = `
      <img src="${image}" alt="Album art">
      <div class="spotify-info">
        <div class="spotify-song">${song}</div>
        <div class="spotify-artist">${artists}</div>
        <div class="spotify-status">${isPlaying ? "▶ Now Playing" : "⏸ Paused"}</div>
      </div>
    `;
  } else {
    container.innerHTML = `
      <div class="spotify-info">
        <div class="spotify-song">Not playing</div>
        <div class="spotify-artist">Spotify</div>
      </div>
    `;
  }
}

// Run once when page loads
updateNowPlaying();

// Optional: auto-refresh every 30 seconds
setInterval(updateNowPlaying, 30000);
