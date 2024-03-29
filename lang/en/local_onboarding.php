<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Lang file.
 *
 * @package   local_onboarding
 * @copyright 2023, Michelle Melton <meltonml@appstate.edu>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Onboarding notifications';
$string['welcometeacher'] = 'New teacher message';
$string['welcometeacher_desc'] = 'Enter the content for the message to be sent to new teachers. The wildcards %sitename% and %userfirstname% can be used.';
$string['welcomestudent'] = 'New student message';
$string['welcomestudent_desc'] = 'Enter the content for the message to be sent to new students. The wildcards %sitename% and %userfirstname% can be used.';
$string['lowuseteacher'] = 'Low use teacher message';
$string['lowuseteacher_desc'] = 'Enter the content for the message to be sent to low use teachers. The wildcards %sitename% and %userfirstname% can be used.';
$string['getroles'] = 'Get stored user roles';
$string['sendonboardingnewmessages'] = 'Send onboarding new messages';
$string['sendonboardinglowusemessages'] = 'Send onboarding low use messages';
$string['cleanup'] = 'Cleanup onboarding table';
$string['newmessagesubject'] = 'Welcome to {$a->sitename}!';
$string['lowusemessagesubject'] = 'Make the most of your {$a->sitename} course!';
$string['messageprovider:onboardingmessage'] = 'Onboarding notifications';
