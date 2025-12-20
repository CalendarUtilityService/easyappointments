# ScheduCal Integration

This document describes the ScheduCal API integration for Easy!Appointments.

## Overview

The ScheduCal integration sends calendar invitations directly to customers instead of emails with ICS attachments. When appointments are created, updated, or cancelled in Easy!Appointments, ScheduCal sends the appropriate calendar invitation to the customer's email address.

## Configuration

Navigate to **Settings > Integrations > ScheduCal** to configure the integration:

1. **ScheduCal Enabled** - Toggle to enable/disable the integration
2. **API Key** - Your ScheduCal API key for authentication
3. **API Secret** - Your ScheduCal API secret for authentication
4. **API URL** - The ScheduCal API endpoint (default: `https://api.scheducal.com/api/v1/appointments`)

The Base URL for appointment management links is automatically detected from your Easy!Appointments `Config::BASE_URL` setting.

## Features

### Calendar Invitations

- **New Appointments**: When a new appointment is booked, ScheduCal sends a calendar invitation to the customer
- **Updates**: When an appointment is modified, ScheduCal sends an updated invitation
- **Cancellations**: When an appointment is cancelled, ScheduCal sends a cancellation notice

### Customer Email Suppression

When ScheduCal is enabled, the built-in customer email notifications with ICS attachments are suppressed. ScheduCal sends its own calendar invites directly to customers, avoiding duplicate notifications. Provider, admin, and secretary notifications remain unchanged.

### Timezone Handling

The integration correctly handles timezones by:
- Sending appointment times in the provider's local timezone (not UTC)
- Including the `appointmentTimeZone` field to tell ScheduCal which timezone the times are in
- This ensures appointments appear at the correct time in customer calendars

### Appointment Management Links

Calendar invitations include a link back to Easy!Appointments where customers can manage their appointments.

## Technical Details

### Files Created

- `application/config/scheducal.php` - Configuration
- `application/libraries/Scheducal_client.php` - ScheduCal API client library
- `application/controllers/Scheducal_settings.php` - Admin settings controller
- `application/views/pages/scheducal_settings.php` - Settings form view
- `application/migrations/061_add_scheducal_settings.php` - Database migration
- `assets/js/http/scheducal_settings_http_client.js` - AJAX client
- `assets/js/pages/scheducal_settings.js` - Page JavaScript

### Files Modified

- `application/libraries/Notifications.php` - Integrates ScheduCal API calls, suppresses duplicate customer emails
- `application/views/pages/integrations.php` - Adds ScheduCal card
- `application/language/english/translations_lang.php` - Adds language strings

### Database

The integration uses the existing `id_google_calendar` field in the `appointments` table to store the ScheduCal appointment ID. This field was originally designed for Google Calendar integration and is reused here for ScheduCal.

Settings are stored in the `settings` table with keys prefixed by `scheducal_`.

### API Methods

The `Scheducal_client` library implements:

- `is_enabled()`: Check if ScheduCal integration is enabled
- `create_appointment()`: Send a calendar invitation for a new appointment
- `update_appointment()`: Send an updated calendar invitation
- `delete_appointment()`: Send a cancellation notice

## Error Handling

All ScheduCal API errors are logged to the application log file. If ScheduCal is unavailable or returns an error, the appointment will still be saved in Easy!Appointments - the failure won't prevent the appointment from being created locally.

## Disabling the Integration

To disable ScheduCal integration, navigate to **Settings > Integrations > ScheduCal** and toggle off the "ScheduCal Enabled" setting.

## Support

For issues related to the ScheduCal integration, check the application logs for detailed error messages. The integration logs all API requests and responses for debugging purposes.
