<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Sends mail through Microsoft Graph instead of SMTP.
 *
 * The tenant's "Block legacy authentication" Conditional Access policy rejects
 * SMTP AUTH with `535 5.7.139 ... the user credentials were incorrect`, which
 * reads like a bad password but is a policy block. Graph uses modern auth
 * (client credentials) and is unaffected.
 *
 * PHPMailer still composes the message, so HTML/plain alternatives and the
 * ICS invitation attachment are preserved byte for byte; this only replaces
 * the transport. Graph accepts a base64 MIME message directly.
 *
 * Configured via GRAPH_MAIL_CLIENT_ID / _CLIENT_SECRET / _TENANT_ID / _SENDER.
 * When those are absent it falls back to PHPMailer's own SMTP transport, so
 * local development is unaffected.
 */
class Graph_mailer
{
    private static ?string $cached_token = null;
    private static int $cached_token_expires = 0;

    /**
     * True when all four Graph settings are present.
     */
    public function is_configured(): bool
    {
        return getenv('GRAPH_MAIL_CLIENT_ID') &&
            getenv('GRAPH_MAIL_CLIENT_SECRET') &&
            getenv('GRAPH_MAIL_TENANT_ID') &&
            getenv('GRAPH_MAIL_SENDER');
    }

    /**
     * The mailbox Graph sends as. The MIME From header must match it.
     */
    public function sender(): string
    {
        return (string) getenv('GRAPH_MAIL_SENDER');
    }

    /**
     * Acquires an app-only token, cached until shortly before it expires.
     */
    private function access_token(): ?string
    {
        if (self::$cached_token !== null && time() < self::$cached_token_expires) {
            return self::$cached_token;
        }

        $tenant = getenv('GRAPH_MAIL_TENANT_ID');
        $url = "https://login.microsoftonline.com/{$tenant}/oauth2/v2.0/token";

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POSTFIELDS => http_build_query([
                'grant_type' => 'client_credentials',
                'client_id' => getenv('GRAPH_MAIL_CLIENT_ID'),
                'client_secret' => getenv('GRAPH_MAIL_CLIENT_SECRET'),
                'scope' => 'https://graph.microsoft.com/.default',
            ]),
        ]);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false || $status !== 200) {
            log_message('error', 'Graph mail: token request failed (' . $status . ') ' . $error . ' ' . (string) $response);
            return null;
        }

        $data = json_decode($response, true);

        if (empty($data['access_token'])) {
            log_message('error', 'Graph mail: token response missing access_token');
            return null;
        }

        self::$cached_token = $data['access_token'];
        self::$cached_token_expires = time() + max(0, (int) ($data['expires_in'] ?? 3600) - 60);

        return self::$cached_token;
    }

    /**
     * Sends an already-composed MIME message. Returns true on success.
     *
     * Never throws - callers decide how a delivery failure should surface.
     */
    public function send_mime(string $mime): bool
    {
        $token = $this->access_token();

        if (!$token) {
            return false;
        }

        $sender = rawurlencode($this->sender());
        $url = "https://graph.microsoft.com/v1.0/users/{$sender}/sendMail";

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                // Graph accepts a base64-encoded MIME message under text/plain.
                'Content-Type: text/plain',
            ],
            CURLOPT_POSTFIELDS => base64_encode($mime),
        ]);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // sendMail returns 202 Accepted with an empty body on success.
        if ($status !== 202) {
            log_message('error', 'Graph mail: sendMail failed (' . $status . ') ' . $error . ' ' . (string) $response);
            return false;
        }

        return true;
    }
}
