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
class send_onboarding_messages extends \core\task\scheduled_task {
    /**
     * Get name of scheduled task.
     * {@inheritDoc}
     * @see \core\task\scheduled_task::get_name()
     */
    public function get_name() {
        return get_string('sendonboardingmessages', 'local_onboarding');
    }

    /**
     * Execute scheduled task.
     * {@inheritDoc}
     * @see \core\task\task_base::execute()
     */
    public function execute() {
        global $DB;
        
        $config = get_config('onboarding');
        
        $newuserswithrole = $DB->get_records_select('local_onboarding', 'roleshortname like "%editingteacher%" OR roleshortname like "%student%" AND type = "new"');
        foreach ($newuserswithrole as $user) {
            if (strpos($user->roleshortname, 'editingteacher') !== false) {
                // Send teacher message.
                send_onboarding_message($user->userid, $config->welcometeacher);
                
            }
            if (strpos($user->roleshortname, 'student') !== false) {
                // Send student message.
                send_onboarding_message($user->userid, $config->welcomestudent);
            }
            
            $DB->delete_records('local_onboarding', array('userid' => $user->userid, 'type' => 'new'));
        }
    }
}
