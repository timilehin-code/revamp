<?php
$title = "Olúwàtimíléhìn. | Software Engineer.";
include 'includes/header.php';
include 'includes/navigation.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<div class="grain-overlay"></div>

<!-- profile and about myself -->
<section class=" hero container d-flex mt-3 justify-content-center">
    <div class="d-flex flex-column mt-3 justify-content-center">
        <div class="Avatar">
            <img src="views/assets/img/timi.webp" alt="A picture of Timi Oluwatimilehin">
        </div>
        <h1 class="text-center">Olúwàtimíléhìn.</h1>
        <h6 class="text-center">(oluwatimilehin.)</h6>
        <div class="short-note">
            <p class="text-center">
                I'm Timi, a Software Engineer and Computer Science student with a deep obsession with tech, software, and cybersecurity. When I'm not coding, you'll find me listening to music and podcasts, playing chess on <span><img src="views/assets/img/chess.png" alt=""><a href="https://www.chess.com/member/apex_predator01" target="_blank">chess.com</a></span>, keeping up with corporate finance and fintech news, or cheering on Arsenal FC.
            </p>
        </div>
        <div class="easter-egg">
            <p class="text-center">I am unbeatable because my only opponent has always been me.</p>
        </div>
        <div class="resume">
            <a href="views/assets/resume/OLUWATIMILEHINTAWOSE_CV.pdf" download class="text-center"><i class="fa-solid fa-download"></i> Resume</a>
        </div>
    </div>
</section>


<div class="d-flex mt-5 justify-content-center">
    <!-- Spotify Now Playing Widget -->
    <div id="spotify-widget" class="spotify-card p-4 my-3 text-white">
        <div class=" d-flex align-items-center mb-3">
            <!-- Album Art -->
            <a id="spotify-song-url" href="#" target="_blank" class="flex-shrink-0">
                <img id="spotify-album-art" class="spotify-album-art" src="" alt="Album Art">
            </a>

            <!-- Track & Artist Details -->
            <div class="ms-3 overflow-hidden flex-grow-1">
                <div class="d-flex align-items-center mb-1">
                    <a id="spotify-song-title" href="#" target="_blank" class="spotify-song-title text-decoration-none fw-bold text-truncate" style="font-size: 1.15rem; letter-spacing: -0.01em;">Loading...</a>

                    <!-- Animated Equalizer visualizer -->
                    <div id="spotify-equalizer" class="equalizer">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </div>
                </div>
                <div id="spotify-artist" class="text-muted text-truncate" style="font-size: 0.95rem; color: #7f7f7f !important;">Artist</div>
            </div>
        </div>

        <!-- Inset Progress & Timing row -->
        <div class="px-1">
            <div class="spotify-progress-container mb-2">
                <div id="spotify-progress-bar" class="spotify-progress-bar"></div>
            </div>

            <div class="d-flex justify-content-between text-muted" style="font-size: 0.8rem; color: #7f7f7f !important;">
                <span id="spotify-current-time">0:00</span>
                <span id="spotify-duration">0:00</span>
            </div>
        </div>
        <div class="music-platforms d-flex justify-content-center mt-3">
            <a id="platform-spotify" href="#" target="_blank" class="text-decoration-none me-3" title="Listen on Spotify">
                <img src="views/assets/img/spotify.png" alt="Spotify">
            </a>
            <a id="platform-apple" href="#" target="_blank" class="text-decoration-none me-3" title="Listen on Apple Music">
                <img src="views/assets/img/apple-music.png" alt="Apple Music">
            </a>
            <a id="platform-ytmusic" href="#" target="_blank" class="text-decoration-none" title="Listen on YouTube Music">
                <img src="views/assets/img/youtube-music.png" alt="YouTube Music">
            </a>
        </div>
    </div>
</div>
<!-- profile and about my self ends -->
<!-- tech stacks  -->
<section id="about">
    <div class="container mt-5">
        <div class="stacks">
            <h4 class=" text-center">Languages and stacks I use.</h4>
            <div class="overflow-hidden d-flex stacks-logo  m-auto align-items-center justify-content-around">
                <div class="d-flex logo">
                    <img src="views/assets/img/php_logo.png" alt="">
                    <img src="views/assets/img/Laravel-logo.png" alt="">
                    <img src="views/assets/img/mysql_logo.png" alt="">
                    <img src="views/assets/img/javascript_logo.png" alt="">
                    <img src="views/assets/img/html_logo.png" alt="">
                    <img src="views/assets/img/css_logo.png" alt="">
                    <img src="views/assets/img/github_logo.png" alt="">
                    <img src="views/assets/img/git_logo.png" alt="">
                    <img src="views/assets/img/bootstrap_logo.png" alt="">
                    <img src="views/assets/img/tailwind_logo.png" alt="">
                    <img src="views/assets/img/jquery_logo.png" alt="">
                    <img src="views/assets/img/wordpress_logo.png" alt="">
                    <img src="views/assets/img/python_logo.png" alt="">
                </div>
                <div class="d-flex logo">
                    <img src="views/assets/img/php_logo.png" alt="">
                    <img src="views/assets/img/Laravel-logo.png" alt="">
                    <img src="views/assets/img/mysql_logo.png" alt="">
                    <img src="views/assets/img/javascript_logo.png" alt="">
                    <img src="views/assets/img/html_logo.png" alt="">
                    <img src="views/assets/img/css_logo.png" alt="">
                    <img src="views/assets/img/github_logo.png" alt="">
                    <img src="views/assets/img/git_logo.png" alt="">
                    <img src="views/assets/img/bootstrap_logo.png" alt="">
                    <img src="views/assets/img/tailwind_logo.png" alt="">
                    <img src="views/assets/img/jquery_logo.png" alt="">
                    <img src="views/assets/img/wordpress_logo.png" alt="">
                    <img src="views/assets/img/python_logo.png" alt="">
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- tech stacks ends  -->
    <!--  My projects and works i have done-->
    <div class="container mt-5 projects">
        <h1 class=" text-center">My projects</h1>
        <div class="row mt-3">
            <div class="col-md-4 mt-4">
                <a href="">
                    <div class="work-card">
                        <div class="work-img">
                            <img src="views/assets/img/portfolio.jpg" alt="">
                        </div>
                        <h4 class=" mt-2">Portfolio</h4>
                        <p class=" text-justify mt-3">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Nobis deserunt officiis similique reprehenderit eius odit tenetur nesciunt ipsa dolorem,
                            itaque
                            pariatur magni eligendi cumque fuga unde amet dolores doloremque eum rem nisi architecto
                            hic?
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mt-4">
                <a href="">
                    <div class="work-card">
                        <div class="work-img">
                            <img src="views/assets/img/med_record_1.jpg" alt="">
                        </div>
                        <h4 class=" mt-2">Portfolio</h4>
                        <p class=" text-justify mt-3">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Nobis deserunt officiis similique reprehenderit eius odit tenetur nesciunt ipsa dolorem,
                            itaque
                            pariatur magni eligendi cumque fuga unde amet dolores doloremque eum rem nisi architecto
                            hic?
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mt-4">
                <a href="">
                    <div class="work-card">
                        <div class="work-img">
                            <img src="views/assets/img/portfolio.jpg" alt="">
                        </div>
                        <h4 class="mt-2">Portfolio</h4>
                        <p class="text-justify mt-3">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Nobis deserunt officiis similique reprehenderit eius odit tenetur nesciunt ipsa dolorem,
                            itaque
                            pariatur magni eligendi cumque fuga unde amet dolores doloremque eum rem nisi architecto
                            hic?
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mt-4">
                <a href="">
                    <div class="work-card">
                        <div class="work-img">
                            <img src="views/assets/img/portfolio.jpg" alt="">
                        </div>
                        <h4 class=" mt-2">Portfolio</h4>
                        <p class=" text-justify mt-3">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Nobis deserunt officiis similique reprehenderit eius odit tenetur nesciunt ipsa dolorem,
                            itaque
                            pariatur magni eligendi cumque fuga unde amet dolores doloremque eum rem nisi architecto
                            hic?
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mt-4">
                <a href="">
                    <div class="work-card">
                        <div class="work-img">
                            <img src="views/assets/img/med_record_1.jpg" alt="">
                        </div>
                        <h4 class=" mt-2">Portfolio</h4>
                        <p class=" text-justify mt-3">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Nobis deserunt officiis similique reprehenderit eius odit tenetur nesciunt ipsa dolorem,
                            itaque
                            pariatur magni eligendi cumque fuga unde amet dolores doloremque eum rem nisi architecto
                            hic?
                        </p>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mt-4">
                <a href="">
                    <div class="work-card">
                        <div class="work-img">
                            <img src="views/assets/img/portfolio.jpg" alt="">
                        </div>
                        <h4 class=" mt-2">Portfolio</h4>
                        <p class=" text-justify mt-3">Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            Nobis deserunt officiis similique reprehenderit eius odit tenetur nesciunt ipsa dolorem,
                            itaque
                            pariatur magni eligendi cumque fuga unde amet dolores doloremque eum rem nisi architecto
                            hic?
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
<!-- my projects and works i have done ends -->
<!-- contact me begings  -->
<div class="container mt-5 contact" id="contact">

    <h4 class="text-center ">Contact</h4>
    <h2 class="text-center ">Get In Touch</h2>

    <div class="row mt-3 contact-cards">
        <div class="col-md-4 mt-2">
            <div class="contact-item">
                <div class="text-center">
                    <i class="fas fa-phone text-center"></i>
                </div>
                <h5 class="text-center ">Phone</h5>
                <p class="text-center">+234-908-347-0210</p>
            </div>
        </div>
        <div class="col-md-4 mt-2">
            <div class="contact-item">
                <div class="text-center">
                    <i class="fas fa-envelope text-center"></i>
                </div>
                <h5 class="text-center">Email</h5>
                <p class="text-center">oluwatimilehintawose2005@gmail.com</p>
            </div>
        </div>
        <div class="col-md-4 mt-2">
            <div class="contact-item">
                <div class="text-center">
                    <i class="fas fa-map-marker-alt text-center"></i>
                </div>
                <h5 class="text-center">Location</h5>
                <p class="text-center">Lagos, Nigeria.</p>
            </div>
        </div>
    </div>
    <form action="/revamp/mail" class="mt-4" method="POST" id="contact-form">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        <div class="row">
            <div class="col-md-6 mt-2">
                <input type="text" name="name" class="w-100" id="name" placeholder="Name">
            </div>
            <div class="col-md-6 mt-2">
                <input type="email" name="email" class="w-100" id="email" placeholder="Email">
            </div>
            <div class="col-md-6 mt-3">
                <input type="text" name="subject" class="w-100" id="subject" placeholder="Subject">
            </div>
            <div class="col-md-6 mt-3">
                <input type="text" name="message" class="w-100" id="message" placeholder="Message">
            </div>
            <div class="text-center mt-4">
                <button type="submit" class=" send-btn"> Send</button>
            </div>
        </div>
    </form>
    <?php
    if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])) {
        echo '<p class="error-message text-danger mt-5 text-center">' . htmlspecialchars($_SESSION['error_message']) . '</p>';
        unset($_SESSION['error_message']);
    }
    if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) {
        echo '<p class="success-message text-success mt-5 text-center">' . htmlspecialchars($_SESSION['success_message']) . '</p>';
        unset($_SESSION['success_message']);
    }
    ?>
</div>
<!-- contact me ends -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let progressMs = 0;
        let durationMs = 0;
        let isPlaying = false;
        let tickInterval = null;

        // Helper function to format milliseconds into MM:SS
        function formatTime(ms) {
            if (isNaN(ms) || ms < 0) return '0:00';
            const totalSeconds = Math.floor(ms / 1000);
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        }

        // Smooth client-side progress bar ticker
        function startTicking() {
            if (tickInterval) clearInterval(tickInterval);

            tickInterval = setInterval(() => {
                if (!isPlaying) return;

                progressMs += 1000;
                if (progressMs > durationMs) progressMs = durationMs;

                const percentage = durationMs > 0 ? (progressMs / durationMs) * 100 : 0;
                const bar = document.getElementById('spotify-progress-bar');
                const timeDisplay = document.getElementById('spotify-current-time');

                if (bar) bar.style.width = `${percentage}%`;
                if (timeDisplay) timeDisplay.innerText = formatTime(progressMs);
            }, 1000);
        }

        // Main function to fetch playing status and update DOM
        async function updateSpotifyWidget() {
            try {
                const res = await fetch('/revamp/api/spotify/now-playing');
                const data = await res.json();

                // Select widget elements
                const widget = document.getElementById('spotify-widget');
                const art = document.getElementById('spotify-album-art');
                const songUrl = document.getElementById('spotify-song-url');
                const title = document.getElementById('spotify-song-title');
                const artist = document.getElementById('spotify-artist');
                const equalizer = document.getElementById('spotify-equalizer');
                const durationDisplay = document.getElementById('spotify-duration');
                const progressDisplay = document.getElementById('spotify-current-time');
                const bar = document.getElementById('spotify-progress-bar');

                // Select music platform link elements
                const spotifyBtn = document.getElementById('platform-spotify');
                const appleBtn = document.getElementById('platform-apple');
                const ytMusicBtn = document.getElementById('platform-ytmusic');

                // Safeguard: Ensure core elements exist before proceeding
                if (!widget || !art || !title || !artist || !equalizer) return;

                if (data.is_playing) {
                    // Display the widget card
                    widget.style.display = 'block';

                    // Populate track details
                    art.src = data.album_art;
                    title.innerText = data.title;
                    title.href = data.song_url;
                    songUrl.href = data.song_url;
                    artist.innerText = data.artist;
                    equalizer.style.display = 'inline-flex';

                    // Save progress & duration
                    progressMs = data.progress_ms || 0;
                    durationMs = data.duration_ms || 0;
                    isPlaying = true;

                    if (durationDisplay) durationDisplay.innerText = formatTime(durationMs);
                    if (progressDisplay) progressDisplay.innerText = formatTime(progressMs);
                    if (bar) bar.style.width = `${(progressMs / durationMs) * 100}%`;

                    // Generate dynamic music platform search links
                    const searchQuery = encodeURIComponent(`${data.artist} ${data.title}`);

                    if (spotifyBtn) spotifyBtn.href = data.song_url;
                    if (appleBtn) appleBtn.href = `https://music.apple.com/us/search?term=${searchQuery}`;
                    if (ytMusicBtn) ytMusicBtn.href = `https://music.youtube.com/search?q=${searchQuery}`;

                    // Start local timeline ticker
                    startTicking();
                } else {
                    // Hide widget when offline
                    widget.style.display = 'none';
                    isPlaying = false;
                    if (tickInterval) clearInterval(tickInterval);

                    // Reset platform links
                    if (spotifyBtn) spotifyBtn.removeAttribute('href');
                    if (appleBtn) appleBtn.removeAttribute('href');
                    if (ytMusicBtn) ytMusicBtn.removeAttribute('href');
                }
            } catch (err) {
                console.error('Error fetching Spotify status:', err);
            }
        }

        // Execute immediately on page load & poll every 15 seconds
        updateSpotifyWidget();
        setInterval(updateSpotifyWidget, 15000);
    });
</script>
<?php
include 'includes/footer.php';
include 'includes/script.php';
?>