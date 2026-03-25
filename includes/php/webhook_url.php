<?php

/**
 * Public URL for client-area/webhook.php based on the current HTTP request.
 */
function panel_public_webhook_url() {
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443')
        || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https');
    $protocol = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/client-area/index.php';
    $dir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
    return $protocol . '://' . $host . $dir . '/webhook.php';
}
