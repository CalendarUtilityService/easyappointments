# ScheduCal Integration Patches

These patches add ScheduCal integration to Easy!Appointments.

## Quick Install (Complete Patch)

Apply all changes at once:

```bash
cd /path/to/easyappointments
git apply patches/scheducal-integration-complete.patch
```

## Individual Patches

Apply patches one by one for more control:

| File | Description |
|------|-------------|
| `01-scheducal-config.patch` | ScheduCal configuration with database/env fallback |
| `02-scheducal-sync-library.patch` | API client library for ScheduCal |
| `03-notifications-integration.patch` | Integration with Notifications system + email suppression |
| `04-scheducal-settings-controller.patch` | Admin settings controller |
| `05-scheducal-settings-view.patch` | Admin settings form view |
| `06-integrations-page.patch` | Add ScheduCal card to integrations page |
| `07-migration.patch` | Database migration for settings |
| `08-translations.patch` | English language strings |
| `09-js-http-client.patch` | JavaScript AJAX client |
| `10-js-page.patch` | Settings page JavaScript |
| `11-documentation.patch` | SCHEDUCAL_INTEGRATION.md documentation |

## Apply Individual Patches

```bash
git apply patches/01-scheducal-config.patch
git apply patches/02-scheducal-sync-library.patch
# ... etc
```

## After Applying

1. Run database migrations (visit the app or run migration command)
2. Navigate to Settings > Integrations > ScheduCal to configure
3. Enter your ScheduCal API credentials

## Requirements

- Easy!Appointments version compatible with upstream/main
- ScheduCal API credentials

## Troubleshooting

If patches fail to apply:

```bash
# Check what would happen without applying
git apply --check patches/scheducal-integration-complete.patch

# Apply with 3-way merge for conflicts
git apply --3way patches/scheducal-integration-complete.patch
```
