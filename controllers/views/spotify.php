<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';

// Safe-load .env variables
if (class_exists('Dotenv\Dotenv')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->safeLoad();
}

require_once __DIR__ . '/../../Models/views/Spotify.php';

use Models\Views\Spotify;

function nowPlaying()
{
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');

    $spotify = new Spotify();
    $trackData = $spotify->getCurrentlyPlaying();

    echo json_encode($trackData);
    exit;
}
