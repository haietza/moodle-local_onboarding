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
 * Scheduled task for plugin to get low use teachers.
 *
 * @package   local_onboarding
 * @copyright   2023 Michelle Melton <meltonml@appstate.edu>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class send_low_use_messages extends \core\task\scheduled_task {
    /**
     * Get name of scheduled task.
     * {@inheritDoc}
     * @see \core\task\scheduled_task::get_name()
     */
    public function get_name() {
        return get_string('sendonboardinglowusemessages', 'local_onboarding');
    }

    /**
     * Execute scheduled task.
     * {@inheritDoc}
     * @see \core\task\task_base::execute()
     */
    public function execute() {
        global $DB, $CFG, $SITE;

        require_once($CFG->dirroot . '/local/onboarding/locallib.php');
        require_once($CFG->dirroot . '/enrol/externallib.php');
        require_once($CFG->dirroot . '/lib/enrollib.php');

        $config = get_config('onboarding');
        $lowusemessage = $config->lowuseteacher;

        if (strpos($lowusemessage, '%sitename%') !== false) {
            $lowusemessage = preg_replace('/%sitename%/', $SITE->fullname, $lowusemessage);
        }

        // Find course IDs that only have Resource modules and the default News forum (or no modules and/or no default News forum).
        $sql = "SELECT cm.course
                FROM {course_modules} cm
                JOIN {modules} m
                ON cm.module = m.id
                GROUP BY cm.course
                HAVING SUM(CASE WHEN m.name <> 'resource' THEN 1 ELSE 0 END) < 2
                AND SUM(CASE WHEN m.name = 'forum' THEN 1 ELSE 0 END) < 2
                AND cm.course <> 1";

        $records = $DB->get_records_sql($sql);
        $lowusecourses = array_keys($records);

        // Get teacher in low use courses identified above.
        $lowusecourseteachers = [];
        foreach ($lowusecourses as $course) {
            $teachers = \core_enrol_external::get_enrolled_users($course, [['name' => 'withcapability',
                'value' => 'moodle/course:manageactivities']]);
            foreach ($teachers as $teacher) {
                $lowusecourseteachers[] = $teacher['id'];
            }
        }
        // Remove duplicates, if teachers are enroled in more than one low use course.
        $lowusecourseteachers = array_unique($lowusecourseteachers);

        // Remove teachers who are also enroled in a non-low use course.
        $lowuseteachers = [];
        foreach ($lowusecourseteachers as $lowusecourseteacher) {
            // Get rid of teachers who have other courses that are not low use.
            $lowuse = true;
            $othercourses = enrol_get_users_courses($lowusecourseteacher);
            foreach ($othercourses as $othercourse) {
                $context = \context_course::instance($othercourse->id);
                if (has_capability('moodle/course:manageactivities', $context)) {
                    if (!in_array($othercourse->id, $lowusecourses)) {
                        // User is teacher in a course that is not designated as low use.
                        // Break out of loop and do not add teacher for onboarding messages.
                        $lowuse = false;
                        break;
                    }
                }
            }
            if ($lowuse) {
                $lowuseteachers[] = $lowusecourseteacher;
            }
        }

        foreach ($lowuseteachers as $lowuseteacher) {
            $usermessage = $lowusemessage; // Don't want userid to overwrite replace for name.
            if (strpos($lowusemessage, '%userfirstname%') !== false) {
                $firstname = $DB->get_field('user', 'firstname', ['id' => $lowuseteacher]);
                $usermessage = preg_replace('/%userfirstname%/', $firstname, $usermessage);
            }
            if (strpos($lowusemessage, '%userid%') !== false) {
                $usermessage = preg_replace('/%userid%/', $lowuseteacher, $usermessage);
            }

            // No need to store low use teachers, just send them the email now.
            $messageid = send_onboarding_low_use_message($lowuseteacher, $usermessage);
            if ($messageid !== false) {
                mtrace('Sent low use message to user ID ' . $lowuseteacher);
            } else {
                mtrace('Error sending low use message to user ID ' . $lowuseteacher);
            }
        }
    }
}
