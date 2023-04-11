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
 * Plugin internal classes, functions and constants are defined here.
 *
 * @package     local_onboarding
 * @copyright   2023 Michelle Melton <meltonml@appstate.edu>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Handle the user_created event.
 *
 * @param object $event The event object.
 */
function new_user_created($event) {
    global $DB;
    
    $newuser = new stdClass();
    $newuser->userid = $event->objectid;
    $newuser->timecreated = time();
    $DB->insert_record('local_onboarding', $newuser);
    
    return;
}

function send_onboarding_new_message($userid, $message) {
    global $SITE;
    
    $a = new \stdClass();
    $a->sitename = $SITE->fullname;
    
    $messagesubject = get_string('newmessagesubject', 'local_onboarding', $a);
    $messagebody = $message;

    $message = new \core\message\message();
    $message->component = 'local_onboarding';
    $message->name = 'onboardingmessage';
    $message->userfrom = core_user::get_noreply_user();
    $message->userto = $userid;
    $message->subject = $messagesubject;
    $message->fullmessage = html_to_text($messagebody);
    $message->fullmessageformat = FORMAT_HTML;
    $message->fullmessagehtml = $messagebody;
    $message->notification = 1;

    $messageid = message_send($message);
    return $messageid;
}

function send_onboardin_low_use_message($userid, $message) {
    global $SITE;
    
    $a = new \stdClass();
    $a->sitename = $SITE->fullname;
    
    $messagesubject = get_string('lowusemessagesubject', 'local_onboarding', $a);
    $messagebody = $message;

    $message = new \core\message\message();
    $message->component = 'local_onboarding';
    $message->name = 'onboardingmessage';
    $message->userfrom = core_user::get_noreply_user();
    $message->userto = $userid;
    $message->subject = $messagesubject;
    $message->fullmessage = html_to_text($messagebody);
    $message->fullmessageformat = FORMAT_HTML;
    $message->fullmessagehtml = $messagebody;
    $message->notification = 1;

    $messageid = message_send($message);
    return $messageid;
}
