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
 * Add ics_uid and ics_sequence columns to appointments table.
 *
 * RFC 5545 compliance:
 *  - ics_uid: Stable UUID4 generated at appointment creation. Never changes.
 *    Ensures that calendar clients (Outlook, Google Calendar, Apple Calendar)
 *    recognise updates as belonging to the same event and do not create duplicates.
 *  - ics_sequence: Integer counter, starts at 0, incremented on every update.
 *    Calendar clients use SEQUENCE to determine which version of an event is
 *    the most recent and to correctly apply cancellations.
 */
class Migration_Add_ics_uid_and_sequence_to_appointments extends EA_Migration
{
    /**
     * Generate a UUID4 string.
     *
     * @return string UUID4, e.g. "550e8400-e29b-41d4-a716-446655440000"
     */
    private function generate_uuid4(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0F) | 0x40); // version 4
        $bytes[8] = chr((ord($bytes[8]) & 0x3F) | 0x80); // variant RFC 4122
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }

    /**
     * Upgrade method.
     */
    public function up(): void
    {
        if (!$this->db->field_exists('ics_uid', 'appointments')) {
            $this->dbforge->add_column('appointments', [
                'ics_uid' => [
                    'type' => 'VARCHAR',
                    'constraint' => 36,
                    'null' => true,
                    'after' => 'id',
                ],
            ]);
        }

        if (!$this->db->field_exists('ics_sequence', 'appointments')) {
            $this->dbforge->add_column('appointments', [
                'ics_sequence' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => false,
                    'default' => 0,
                    'after' => 'ics_uid',
                ],
            ]);
        }

        // Backfill stable UIDs for all existing appointments that don't have one.
        // Each appointment gets a unique UUID4 so future updates and cancellations
        // carry the correct UID in their ICS files.
        $appointments = $this->db->select('id')->get('appointments')->result_array();

        foreach ($appointments as $appointment) {
            $this->db->update('appointments', ['ics_uid' => $this->generate_uuid4()], ['id' => $appointment['id']]);
        }
    }

    /**
     * Downgrade method.
     */
    public function down(): void
    {
        if ($this->db->field_exists('ics_sequence', 'appointments')) {
            $this->dbforge->drop_column('appointments', 'ics_sequence');
        }

        if ($this->db->field_exists('ics_uid', 'appointments')) {
            $this->dbforge->drop_column('appointments', 'ics_uid');
        }
    }
}
