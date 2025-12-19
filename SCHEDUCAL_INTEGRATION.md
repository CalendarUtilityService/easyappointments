# ScheduCal Integration

This document describes the ScheduCal API integration for Easy!Appointments.

## Overview

The ScheduCal integration synchronizes appointments between Easy!Appointments and the ScheduCal calendar service. When appointments are created, updated, or deleted in Easy!Appointments, they are automatically synced to ScheduCal.

## Configuration

### Admin UI (Recommended)

Navigate to **Settings > Integrations > ScheduCal** to configure the integration:

1. **ScheduCal Enabled** - Toggle to enable/disable the integration
2. **API Key** - Your ScheduCal API key for authentication
3. **API Secret** - Your ScheduCal API secret for authentication
4. **API URL** - The ScheduCal API endpoint (default: `https://api.scheducal.com/api/v1/appointments`)
5. **Base URL** - The base URL of your Easy!Appointments installation (used in appointment management links)

### Environment Variables (Alternative)

For deployments where database configuration isn't practical, you can use environment variables as a fallback:

```bash
SCHEDUCAL_ENABLED=true
SCHEDUCAL_API_KEY=your-api-key-here
SCHEDUCAL_API_SECRET=your-api-secret-here
SCHEDUCAL_API_URL=https://api.scheducal.com/api/v1/appointments
SCHEDUCAL_BASE_URL=https://your-domain.com
```

**Configuration Priority:** Database settings take precedence over environment variables.

## Features

### Automatic Synchronization

- **Create**: When a new appointment is booked, it's automatically created in ScheduCal
- **Update**: When an appointment is modified, the changes are synced to ScheduCal
- **Delete**: When an appointment is cancelled, it's deleted from ScheduCal

### Customer Email Suppression

When ScheduCal is enabled, the built-in customer email notifications with ICS attachments are suppressed. ScheduCal sends its own calendar invites directly to customers, avoiding duplicate notifications. Provider, admin, and secretary notifications remain unchanged.

### Timezone Handling

The integration correctly handles timezones by:
- Sending appointment times in the provider's local timezone (not UTC)
- Including the `appointmentTimeZone` field to tell ScheduCal which timezone the times are in
- This ensures appointments appear at the correct time in ScheduCal calendars

### Appointment Management Links

When ScheduCal is configured with a base URL, appointment notifications include a link back to Easy!Appointments where customers can manage their appointments.

## Technical Details

### Files Created

- `application/config/scheducal.php` - Configuration with database-first fallback
- `application/libraries/Scheducal_sync.php` - ScheduCal API client library
- `application/controllers/Scheducal_settings.php` - Admin settings controller
- `application/views/pages/scheducal_settings.php` - Settings form view
- `application/migrations/061_add_scheducal_settings.php` - Database migration
- `assets/js/http/scheducal_settings_http_client.js` - AJAX client
- `assets/js/pages/scheducal_settings.js` - Page JavaScript

### Files Modified

- `application/libraries/Notifications.php` - Integrates ScheduCal sync calls, suppresses duplicate customer emails
- `application/views/pages/integrations.php` - Adds ScheduCal card
- `application/language/english/translations_lang.php` - Adds language strings

### Database

The integration uses the existing `id_google_calendar` field in the `appointments` table to store the ScheduCal appointment ID. This field was originally designed for Google Calendar integration and is reused here for ScheduCal.

Settings are stored in the `settings` table with keys prefixed by `scheducal_`.

### API Methods

The `Scheducal_sync` library implements:

- `is_enabled()`: Check if ScheduCal integration is enabled
- `create_appointment()`: Create a new appointment in ScheduCal
- `update_appointment()`: Update an existing appointment in ScheduCal
- `delete_appointment()`: Delete an appointment from ScheduCal

## Error Handling

All ScheduCal API errors are logged to the application log file. If ScheduCal is unavailable or returns an error, the appointment will still be saved in Easy!Appointments - the sync failure won't prevent the appointment from being created locally.

## Disabling the Integration

To disable ScheduCal integration, navigate to **Settings > Integrations > ScheduCal** and toggle off the "ScheduCal Enabled" setting.

## Support

For issues related to the ScheduCal integration, check the application logs for detailed error messages. The integration logs all API requests and responses for debugging purposes.
