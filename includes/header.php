<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $metaTitle = $title ?? 'Olúwàtimíléhìn. | Software Engineer.';
    $metaDescription = $description ?? 'Portfolio and blog of Olúwàtimíléhìn, a software engineer and computer science student.';
    $metaType = $ogType ?? 'website';
    $requestScheme = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    $siteOrigin = $requestScheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    $pageUrl = $siteOrigin . ($_SERVER['REQUEST_URI'] ?? '/revamp/');
    $imagePath = $ogImage ?? '/revamp/views/assets/img/profile.png';
    $imageUrl = preg_match('/^https?:\\/\\//i', $imagePath)
        ? $imagePath
        : $siteOrigin . '/' . ltrim($imagePath, '/');
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="<?= htmlspecialchars($metaRobots ?? 'index, follow', ENT_QUOTES, 'UTF-8') ?>">
    <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="author" content="Olúwàtimíléhìn">
    <meta name="canonical" content="<?= htmlspecialchars($canonicalUrl ?? $pageUrl, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl ?? $pageUrl, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:title" content="<?= htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:type" content="<?= htmlspecialchars($metaType, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($pageUrl, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:image" content="<?= htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:site_name" content="Olúwàtimíléhìn.">
    <meta property="og:locale" content="en_US">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') ?>">
    <!-- bootstrap css  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- bootstrap css ends -->
    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mochiy+Pop+One&display=swap" rel="stylesheet">
    <!-- google fonts ends  -->
    <!-- font-awesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- font awesome css -->
    <!-- favicon icon -->
    <link rel="shortcut icon" href="views/assets/img/profile.png" type="image/x-icon">
    <link rel="shortcut icon" href="../views/assets/img/profile.png" type="image/x-icon">
    <!-- favicon ends -->
    <!-- custom css -->
    <link rel="stylesheet" href="views/assets/css/style.css">
    <link rel="stylesheet" href="views/assets/css/mediaquery.css">
    <link rel="stylesheet" href="../views/assets/css/style.css">
    <link rel="stylesheet" href="../views/assets/css/mediaquery.css">
    <!-- custom css ends  -->
    <!-- Web App Manifest -->
    <link rel="manifest" href="/revamp/manifest.json">

    <!-- Mobile Address Bar Color (matches dark UI theme) -->
    <meta name="theme-color" content="#000000">

    <!-- iOS Safari Support -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="/revamp/views/assets/img/profile.png">
    <!-- EasyMDE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <!--  Highlight.js Theme CSS (e.g. Atom One Dark) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <title><?= htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8') ?></title>

</head>

<body>
    <!-- the navigation -->