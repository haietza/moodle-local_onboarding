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
 * Cron task to send onboarding messages.
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
class send_onboarding_new_messages extends \core\task\scheduled_task {
    /**
     * Get name of scheduled task.
     * {@inheritDoc}
     * @see \core\task\scheduled_task::get_name()
     */
    public function get_name() {
        return get_string('sendonboardingnewmessages', 'local_onboarding');
    }

    /**
     * Execute scheduled task.
     * {@inheritDoc}
     * @see \core\task\task_base::execute()
     */
    public function execute() {
        global $DB, $CFG, $SITE;

        require_once($CFG->dirroot . '/local/onboarding/locallib.php');

        $config = get_config('onboarding');
        $teachermessage = $config->welcometeacher;
        $studentmessage = $config->welcomestudent;

        if (strpos($teachermessage, '%sitename%') !== false ||
                strpos($studentmessage, '%sitename%') !== false) {
            $teachermessage = preg_replace('/%sitename%/', $SITE->fullname, $teachermessage);
            $studentmessage = preg_replace('/%sitename%/', $SITE->fullname, $studentmessage);
        }

        $newuserswithrole = $DB->get_records_select('local_onboarding',
            'roleshortname like "%editingteacher%" OR roleshortname like "%student%"');
        foreach ($newuserswithrole as $user) {
            if (strpos($teachermessage, '%userfirstname%') !== false ||
                    strpos($studentmessage, '%userfirstname%') !== false) {
                $firstname = $DB->get_field('user', 'firstname', array('id' => $user->userid));
                $teacherusermessage = preg_replace('/%userfirstname%/', $firstname, $teachermessage);
                $studentusermessage = preg_replace('/%userfirstname%/', $firstname, $studentmessage);
            }
            // Add replacement to populate userid so we can feed that in as a query parameter.
            if (strpos($teachermessage, '%userid%') !== false ||
                    strpos($studentmessage, '%userid%') !== false) {
                $userid = $user->userid;
                $teacherusermessage = preg_replace('/%userid%/', $userid, $teacherusermessage);
                $studentusermessage = preg_replace('/%userid%/', $userid, $studentusermessage);
            }
            if (strpos($user->roleshortname, 'editingteacher') !== false) {
                // Send teacher message.
                $messageid = send_onboarding_new_message($user->userid, $teacherusermessage);
                if ($messageid !== false) {
                    mtrace('Sent welcome teacher message to user ID ' . $user->userid);
                } else {
                    mtrace('Error sending welcome teacher message to user ID ' . $user->userid);
                }
            }

            if (strpos($user->roleshortname, 'student') !== false) {
                // Send student message.
                $messageid = send_onboarding_new_message($user->userid, $studentusermessage);
                if ($messageid !== false) {
                    mtrace('Sent welcome student message to user ID ' . $user->userid);
                } else {
                    mtrace('Error sending welcome student message to user ID ' . $user->userid);
                }
            }

            $DB->delete_records('local_onboarding', array('userid' => $user->userid));
        }
    }
}
