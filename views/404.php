<?php
include 'includes/header.php';
// include 'includes/navigation.php';
?>
<style>
    :root {
        --to-purple: #49378f;
        --to-purple-transparent: #49378fc1;
        --to-black: #111111;
        --to-white: #f1f1f1;
        --to-black-grey: #1f1f1f;
        --to-black-grey-faded: #1f1f1f26;
        --to-black-grey-500: #343232;
        --to-grey: #8e8e8e;
        --to-light-grey: #a1a1a1;
        --to-white-900: #ffffff;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html,
    body {
        height: 100%;
    }

    body {
        background: var(--to-black);
        color: var(--to-white);
        font-family: -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        min-height: 100vh;
        min-height: 100dvh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: clamp(1.25rem, 6vw, 3rem);
        padding-top: calc(clamp(1.25rem, 6vw, 3rem) + env(safe-area-inset-top, 0px));
        padding-bottom: calc(clamp(1.25rem, 6vw, 3rem) + env(safe-area-inset-bottom, 0px));
    }

    .wrap {
        max-width: 620px;
        width: 100%;
        text-align: center;
    }

    .ghost {
        width: 120px;
        height: auto;
        margin: 0 auto 1.5rem;
        display: block;
    }

    .code {
        font-size: clamp(4rem, 16vw, 7rem);
        font-weight: 800;
        line-height: 1;
        letter-spacing: -0.02em;
        color: var(--to-purple);
        -webkit-text-stroke: 2px var(--to-white-900);
    }

    h1 {
        margin-top: 0.75rem;
        font-size: clamp(1.5rem, 4vw, 2.1rem);
        font-weight: 700;
    }

    .lede {
        margin-top: 1rem;
        color: var(--to-light-grey);
        font-size: clamp(0.98rem, 2vw, 1.08rem);
        line-height: 1.6;
    }

    .reasons {
        margin: 2rem auto 0;
        max-width: 460px;
        text-align: left;
        background: var(--to-black-grey);
        border: 1px solid var(--to-black-grey-500);
        border-radius: 14px;
        padding: 1.25rem 1.4rem;
    }

    .reasons p.label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--to-grey);
        margin-bottom: 0.8rem;
    }

    .reasons ul {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 0.7rem;
    }

    .reasons li {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        font-size: 0.94rem;
        color: var(--to-white);
    }

    .reasons li .box {
        width: 16px;
        height: 16px;
        margin-top: 0.25rem;
        border: 1.5px solid var(--to-white);
        border-radius: 4px;
        flex-shrink: 0;
    }

    .actions {
        margin-top: 2.25rem;
        display: flex;
        gap: 0.85rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.8rem 1.6rem;
        border-radius: 999px;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border: 1px solid transparent;
    }

    .btn-primary {
        background: var(--to-purple);
        color: var(--to-white-900);
    }

    .btn-primary:hover {
        background: var(--to-purple-transparent);
    }

    .btn-ghost {
        background: transparent;
        color: var(--to-light-grey);
        border-color: var(--to-black-grey-500);
    }

    .btn-ghost:hover {
        color: var(--to-white);
        border-color: var(--to-grey);
    }

    .fine-print {
        margin-top: 2rem;
        font-size: 0.78rem;
        color: var(--to-grey);
    }

    @media (max-width: 480px) {
        .actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .ghost {
            width: 90px;
            margin-bottom: 1rem;
        }

        .reasons {
            padding: 1rem 1.1rem;
        }

        .reasons li {
            font-size: 0.88rem;
        }

        .fine-print {
            padding: 0 0.5rem;
        }
    }

    @media (max-height: 560px) and (orientation: landscape) {
        body {
            align-items: flex-start;
        }

        .ghost {
            width: 64px;
            margin-bottom: 0.75rem;
        }

        .code {
            font-size: clamp(2.5rem, 10vw, 4rem);
        }

        .reasons {
            margin-top: 1.25rem;
            padding: 1rem 1.2rem;
        }

        .actions {
            margin-top: 1.5rem;
        }

        .fine-print {
            margin-top: 1.25rem;
        }
    }
</style>

<div class="wrap">

    <svg class="ghost" viewBox="0 0 100 120" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M20 60c0-22.1 13.4-40 30-40s30 17.9 30 40v46l-10-8-10 8-10-8-10 8-10-8-10 8V60z" fill="var(--to-black-grey)" stroke="var(--to-purple)" stroke-width="3" />
        <circle cx="38" cy="58" r="4.5" fill="var(--to-white-900)" />
        <circle cx="62" cy="58" r="4.5" fill="var(--to-white-900)" />
        <path d="M40 74c4 4 16 4 20 0" stroke="var(--to-white-900)" stroke-width="3" stroke-linecap="round" fill="none" />
    </svg>

    <div class="code">404</div>
    <h1>Congrats, you broke it.</h1>
    <p class="lede">Or the link broke. Or the page ghosted us. We may never know, but statistically, it's probably you. You clicked something sketchy, didn't you.</p>

    <div class="reasons">
        <p class="label">Let's assign blame, shall we</p>
        <ul>
            <li><span class="box"></span> You typed this URL from memory, at 2am, confidently.</li>
            <li><span class="box"></span> You bookmarked this in 2019 and never questioned it since.</li>
            <li><span class="box"></span> This page saw your search history and left on principle.</li>
            <li><span class="box"></span> Our "senior developer" (me, alone, at midnight) broke a link.</li>
            <li><span class="box"></span> You're the reason we can't have nice URLs.</li>
        </ul>
    </div>

    <div class="actions">
        <a href="blog" class="btn btn-primary">Take me somewhere that works</a>
        <a href="home" class="btn btn-ghost">Retreat in shame</a>
    </div>

    <p class="fine-print">Error 404 — rarer than my consistent sleep schedule, more common than my finished side projects.</p>

</div>
<?php
// include 'includes/footer.php';
include 'includes/script.php';
?>