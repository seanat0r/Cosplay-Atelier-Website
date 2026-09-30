<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\OAuthTokenProvider;

final class MicrosoftOAuthTokenProvider implements OAuthTokenProvider
{
    private ?string $accessToken = null;
    private int $expiresAt = 0;

    public function __construct(
        private readonly string $email,
        private readonly string $tenant,
        private readonly string $clientId,
        private readonly string $clientSecret,
    ) {
    }

    public function getOauth64(): string
    {
        if ($this->accessToken === null || time() >= $this->expiresAt - 60) {
            $this->requestAccessToken();
        }

        return base64_encode("user={$this->email}\x01auth=Bearer {$this->accessToken}\x01\x01");
    }

    private function requestAccessToken(): void
    {
        $url = 'https://login.microsoftonline.com/' . rawurlencode($this->tenant) . '/oauth2/v2.0/token';
        $body = http_build_query([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'scope' => 'https://outlook.office365.com/.default',
            'grant_type' => 'client_credentials',
        ], '', '&', PHP_QUERY_RFC3986);

        $handle = curl_init($url);
        if ($handle === false) {
            throw new RuntimeException('Could not initialize the Microsoft OAuth request.');
        }

        try {
            curl_setopt_array($handle, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 20,
            ]);

            $result = curl_exec($handle);
            $status = curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        } finally {
            curl_close($handle);
        }

        if (!is_string($result) || $status !== 200) {
            throw new RuntimeException('Microsoft OAuth token request failed.');
        }

        $token = json_decode($result, true);
        if (!is_array($token) || !is_string($token['access_token'] ?? null) ||
            $token['access_token'] === '' || !is_numeric($token['expires_in'] ?? null)) {
            throw new RuntimeException('Microsoft OAuth token response is invalid.');
        }

        $this->accessToken = $token['access_token'];
        $this->expiresAt = time() + (int) $token['expires_in'];
    }
}
