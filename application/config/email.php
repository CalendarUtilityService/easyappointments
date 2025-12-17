<?php defined('BASEPATH') or exit('No direct script access allowed');

// Add custom values by settings them to the $config array.
// Example: $config['smtp_host'] = 'smtp.gmail.com';
// @link https://codeigniter.com/user_guide/libraries/email.html

$config['useragent'] = 'Easy!Appointments';
$config['mailtype'] = 'html'; // or 'text'

// Check for SMTP environment variables (Azure App Service)
$smtp_host = getenv('SMTP_HOST');
if ($smtp_host) {
    $config['protocol'] = 'smtp';
    $config['smtp_host'] = $smtp_host;
    $config['smtp_user'] = getenv('SMTP_USER') ?: '';
    $config['smtp_pass'] = getenv('SMTP_PASSWORD') ?: '';
    $config['smtp_crypto'] = getenv('SMTP_CRYPTO') ?: 'tls';
    $config['smtp_port'] = getenv('SMTP_PORT') ?: 587;
    $config['smtp_auth'] = !empty($config['smtp_user']);
} else {
    $config['protocol'] = 'mail'; // Fallback to PHP mail()
}

// $config['smtp_debug'] = '0'; // or '1'
// $config['from_name'] = '';
// $config['from_address'] = '';
// $config['reply_to'] = '';
$config['crlf'] = "\r\n";
$config['newline'] = "\r\n";
