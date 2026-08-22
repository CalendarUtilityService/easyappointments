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
 * ScheduCal Settings HTTP client.
 *
 * This module implements the ScheduCal settings related HTTP requests.
 */
App.Http.ScheducalSettings = (function () {
    /**
     * Save ScheduCal settings.
     *
     * @param {Object} scheducalSettings
     *
     * @return {Object}
     */
    function save(scheducalSettings) {
        const url = App.Utils.Url.siteUrl('scheducal_settings/save');

        const data = {
            csrf_token: vars('csrf_token'),
            scheducal_settings: scheducalSettings,
        };

        return $.post(url, data);
    }

    return {
        save,
    };
})();
