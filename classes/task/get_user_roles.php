<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Cron task to get roles for stored users.
 *
 * @package     local_onboarding
 * @copyright   2023 Michelle Melton <meltonml@appstate.edu>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_onboarding\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Scheduled task for plugin to get user roles.
 *
 * @package   local_onboarding
 * @copyright   2023 Michelle Melton <meltonml@appstate.edu>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class get_user_roles extends \core\task\scheduled_task {
    /**
     * Get name of scheduled task.
     * {@inheritDoc}
     * @see \core\task\scheduled_task::get_name()
     */
    public function get_name() {
        return get_string('getroles', 'local_onboarding');
    }

    /**
     * Execute scheduled task.
     * {@inheritDoc}
     * @see \core\task\task_base::execute()
     */
    public function execute() {
        global $DB;
        
        $storedusers = $DB->get_records_select('local_onboarding', 'roleshortname IS NULL AND type = "new"');
        $teacher = false;
        $student = false;
        foreach ($storedusers as $storeduser) {
            $roleids = $DB->get_records('role_assignments', array('userid' => $storeduser->userid), '', 'id, roleid');
            foreach ($roleids as $roleid) {
                $roleshortname = $DB->get_field('role', 'shortname', array('id' => $roleid->roleid));
                if ($roleshortname === 'editingteacher') {
                    $teacher = true;
                }
                if ($roleshortname === 'student') {
                    $student = true;
                }
            }
            
            if ($teacher && !$student) {
                $storeduser->roleshortname = 'editingteacher';
            } elseif (!$teacher && $student) {
                $storeduser->roleshortname = 'student';
            } elseif ($teacher && $student) {
                $storeduser->roleshortname = 'editingteacher, student';
            }
            
            try {
                $DB->update_record('local_onboarding', $storeduser);
            } catch (\dml_exception $e) {
                mtrace('DML exception: ' . $e->getMessage());
            }
        }
    }
}
