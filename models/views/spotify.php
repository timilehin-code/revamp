<?php

declare(strict_types=1);

namespace Models\Views;

class Spotify
{
    private string $clientId;
    private string $clientSecret;
    private string $refreshToken;

    public function __construct()
    {
        $this->clientId     = $_ENV['SPOTIFY_CLIENT_ID'] ?? getenv('SPOTIFY_CLIENT_ID') ?: '';
        $this->clientSecret = $_ENV['SPOTIFY_CLIENT_SECRET'] ?? getenv('SPOTIFY_CLIENT_SECRET') ?: '';
        $this->refreshToken = $_ENV['SPOTIFY_REFRESH_TOKEN'] ?? getenv('SPOTIFY_REFRESH_TOKEN') ?: '';
    }

    /**
     * Request a fresh access token using the refresh token.
     */
    private function getAccessToken(): ?string
    {
        // 1. Check if any required environment variable is missing
        if (empty($this->clientId) || empty($this->clientSecret) || empty($this->refreshToken)) {
            if (function_exists('logProjectError')) {
                logProjectError("Spotify Error: Missing client_id, client_secret, or refresh_token in .env");
            }
            return null;
        }

        $url = 'https://accounts.spotify.com/api/token';
        $credentials = base64_encode($this->clientId . ':' . $this->clientSecret);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false, // Bypasses local XAMPP SSL cert validation error
            CURLOPT_HTTPHEADER     => [
                'Authorization: Basic ' . $credentials,
                'Content-Type: application/x-www-form-urlencoded',
            ],
            CURLOPT_POSTFIELDS     => http_build_query([
                'grant_type'    => 'refresh_token',
                'refresh_token' => $this->refreshToken,
            ]),
        ]);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            if (function_exists('logProjectError')) {
                logProjectError("Spotify cURL Failure: " . $curlError);
            }
            return null;
        }

        $data = json_decode($response, true);

        // If Spotify returned an explicit API error (e.g. invalid_grant or invalid_client)
        if (isset($data['error'])) {
            if (function_exists('logProjectError')) {
                logProjectError("Spotify API Token Error: " . $data['error'] . " - " . ($data['error_description'] ?? ''));
            }
            return null;
        }

        return $data['access_token'] ?? null;
    }

    /**
     * Fetch the user's currently playing track from Spotify API.
     */
    public function getCurrentlyPlaying(): array
    {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return ['is_playing' => false, 'message' => 'Failed to obtain access token'];
        }

        $ch = curl_init('https://api.spotify.com/v1/me/player/currently-playing');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $accessToken,
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // HTTP 204 or empty body means nothing is playing
        if ($httpCode === 204 || empty($response)) {
            return ['is_playing' => false];
        }

        $data = json_decode($response, true);

        if (!isset($data['item'])) {
            return ['is_playing' => false];
        }

        $artists = array_map(fn($artist) => $artist['name'], $data['item']['artists']);

        return [
            'is_playing' => $data['is_playing'] ?? false,
            'title'      => $data['item']['name'],
            'artist'     => implode(', ', $artists),
            'album'      => $data['item']['album']['name'],
            'album_art'  => $data['item']['album']['images'][0]['url'] ?? 'views/assets/img/Spotify.png',
            'song_url'   => $data['item']['external_urls']['spotify'] ?? '#',
            'progress_ms' => $data['progress_ms'] ?? 0,
            'duration_ms' => $data['item']['duration_ms'] ?? 0,
        ];
    }
}
