# ScheduCal Integration

This document describes the ScheduCal API integration for Easy!Appointments.

## Overview

The ScheduCal integration synchronizes appointments between Easy!Appointments and the ScheduCal calendar service. When appointments are created, updated, or deleted in Easy!Appointments, they are automatically synced to ScheduCal.

## Configuration

### 1. Environment Variables (Recommended)

Create a `.env` file in the root directory (you can copy `.env.example` as a template):

```bash
# Enable ScheduCal integration
SCHEDUCAL_ENABLED=true

# Your ScheduCal API credentials
SCHEDUCAL_API_KEY=your-api-key-here
SCHEDUCAL_API_SECRET=your-api-secret-here

# ScheduCal API endpoint (default: https://api.scheducal.com/api/v1/appointments)
SCHEDUCAL_API_URL=https://api.scheducal.com/api/v1/appointments

# Base URL for your EasyAppointments installation
SCHEDUCAL_BASE_URL=https://your-domain.com/easyappointments
```

**Important:** Never commit the `.env` file to version control! It's already included in `.gitignore`.

### 2. Configuration File

Alternatively, you can edit `application/config/scheducal.php` directly, but environment variables are more secure.

## Features

### Automatic Synchronization

- **Create**: When a new appointment is booked, it's automatically created in ScheduCal
- **Update**: When an appointment is modified, the changes are synced to ScheduCal
- **Delete**: When an appointment is cancelled, it's deleted from ScheduCal

### Timezone Handling

The integration correctly handles timezones by:
- Sending appointment times in the provider's local timezone (not UTC)
- Including the `appointmentTimeZone` field to tell ScheduCal which timezone the times are in
- This ensures appointments appear at the correct time in ScheduCal calendars

### Appointment Management Links

When ScheduCal is configured with a `SCHEDUCAL_BASE_URL`, appointment notifications include a link back to Easy!Appointments where customers can manage their appointments.

## Technical Details

### Files Modified/Created

- `application/config/scheducal.php` - Configuration file
- `application/libraries/Scheducal_sync.php` - ScheduCal API client library
- `application/libraries/Notifications.php` - Modified to include ScheduCal sync calls
- `.env.example` - Template for environment configuration
- `.gitignore` - Updated to exclude `.env`

### Database

The integration uses the existing `id_google_calendar` field in the `appointments` table to store the ScheduCal appointment ID. This field was originally designed for Google Calendar integration and is reused here for ScheduCal.

### API Methods

The `Scheducal_sync` library implements:

- `is_enabled()`: Check if ScheduCal integration is enabled
- `create_appointment()`: Create a new appointment in ScheduCal
- `update_appointment()`: Update an existing appointment in ScheduCal
- `delete_appointment()`: Delete an appointment from ScheduCal

## Error Handling

All ScheduCal API errors are logged to the application log file. If ScheduCal is unavailable or returns an error, the appointment will still be saved in Easy!Appointments - the sync failure won't prevent the appointment from being created locally.

## Disabling the Integration

To disable ScheduCal integration, set `SCHEDUCAL_ENABLED=false` in your `.env` file or update the configuration in `application/config/scheducal.php`.

## Future Enhancements

Potential improvements that could be added:

- Bi-directional sync (import appointments from ScheduCal)
- Retry mechanism for failed API calls
- Bulk sync of existing appointments
- Admin UI for configuring ScheduCal settings

## Support

For issues related to the ScheduCal integration, check the application logs for detailed error messages. The integration logs all API requests and responses for debugging purposes.
