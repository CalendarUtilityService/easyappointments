<?php defined('BASEPATH') or exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * Easy!Appointments - Online Appointment Scheduler
 *
 * @package     EasyAppointments
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://easyappointments.org
 * @since       v1.5.0
 * ---------------------------------------------------------------------------- */

/**
 * ScheduCal Configuration
 *
 * Set your ScheduCal API credentials and configuration here.
 * You can also use environment variables for security.
 */

// Enable or disable ScheduCal integration
$config['scheducal_enabled'] = getenv('SCHEDUCAL_ENABLED') ?: TRUE;

// ScheduCal API Credentials
$config['scheducal_api_key'] = getenv('SCHEDUCAL_API_KEY') ?: '82acd8ec-526d-4e37-88e7-4d2d46074115';
$config['scheducal_api_secret'] = getenv('SCHEDUCAL_API_SECRET') ?: '70b7e64b-2279-48be-b1ab-55ef15a99ab6';

// ScheduCal API Endpoint
$config['scheducal_api_url'] = getenv('SCHEDUCAL_API_URL') ?: 'https://api.scheducal.com/api/v1/appointments';

// Base URL for appointment management links sent to ScheduCal
// Falls back to Config::BASE_URL (from config.php) if environment variable not set
$scheducal_base_url = getenv('SCHEDUCAL_BASE_URL') ?: ($_SERVER['SCHEDUCAL_BASE_URL'] ?? ($_ENV['SCHEDUCAL_BASE_URL'] ?? null));
if (empty($scheducal_base_url) && class_exists('Config') && defined('Config::BASE_URL')) {
    $scheducal_base_url = Config::BASE_URL;
}
$config['scheducal_base_url'] = $scheducal_base_url ?: 'http://localhost';
