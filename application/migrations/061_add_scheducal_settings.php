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

class Migration_Add_scheducal_settings extends EA_Migration
{
    /**
     * @var array ScheduCal settings to add
     */
    private array $settings = [
        'scheducal_enabled',
        'scheducal_api_key',
        'scheducal_api_secret',
        'scheducal_api_url',
        'scheducal_base_url',
    ];

    /**
     * Upgrade method.
     */
    public function up(): void
    {
        foreach ($this->settings as $setting_name) {
            if (!$this->db->get_where('settings', ['name' => $setting_name])->num_rows()) {
                $this->db->insert('settings', [
                    'name' => $setting_name,
                    'value' => '',
                ]);
            }
        }
    }

    /**
     * Downgrade method.
     */
    public function down(): void
    {
        foreach ($this->settings as $setting_name) {
            if ($this->db->get_where('settings', ['name' => $setting_name])->num_rows()) {
                $this->db->delete('settings', ['name' => $setting_name]);
            }
        }
    }
}
