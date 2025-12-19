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
 * Configuration priority:
 * 1. Database setting (via setting() helper) - if not empty
 * 2. Environment variable (via getenv())
 * 3. $_SERVER / $_ENV fallbacks
 * 4. Config::BASE_URL for base_url only
 * 5. Hardcoded default
 */

/**
 * Get a ScheduCal config value with fallback chain.
 *
 * @param string $name Setting name (without scheducal_ prefix for env vars)
 * @param mixed $default Default value if all sources are empty
 * @return mixed
 */
function scheducal_config_value(string $name, $default = null)
{
    $db_key = 'scheducal_' . $name;
    $env_key = 'SCHEDUCAL_' . strtoupper($name);

    // Try database first (if setting() helper is available)
    if (function_exists('setting')) {
        $db_value = setting($db_key);
        if (!empty($db_value) || $db_value === '0') {
            return $db_value;
        }
    }

    // Try environment variables
    $env_value = getenv($env_key);
    if ($env_value !== false && $env_value !== '') {
        return $env_value;
    }

    // Try $_SERVER and $_ENV
    if (!empty($_SERVER[$env_key])) {
        return $_SERVER[$env_key];
    }
    if (!empty($_ENV[$env_key])) {
        return $_ENV[$env_key];
    }

    return $default;
}

// Enable or disable ScheduCal integration
$config['scheducal_enabled'] = scheducal_config_value('enabled', false);

// ScheduCal API Credentials
$config['scheducal_api_key'] = scheducal_config_value('api_key', '');
$config['scheducal_api_secret'] = scheducal_config_value('api_secret', '');

// ScheduCal API Endpoint
$config['scheducal_api_url'] = scheducal_config_value('api_url', 'https://api.scheducal.com/api/v1/appointments');

// Base URL for appointment management links sent to ScheduCal
// Falls back to Config::BASE_URL (from config.php) if not set anywhere
$scheducal_base_url = scheducal_config_value('base_url', null);
if (empty($scheducal_base_url) && class_exists('Config') && defined('Config::BASE_URL')) {
    $scheducal_base_url = Config::BASE_URL;
}
$config['scheducal_base_url'] = $scheducal_base_url ?: 'http://localhost';
