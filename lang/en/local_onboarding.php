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
$string['welcometeacher_desc'] = 'Enter the content for the message to be sent to new teachers. The wildcards %sitename%, %userfirstname% and %userid% can be used. Links provided in emails can be tracked by changing the link url to https://asulearn.appstate.edu/local/onboarding/redirect.php?id=<linkid>&userid=%userid% where <linkid> is the id of the onboarding link the the local_onboarding_redirect_links table.';
$string['welcomestudent'] = 'New student message';
$string['welcomestudent_desc'] = 'Enter the content for the message to be sent to new students. The wildcards %sitename% and %userfirstname% can be used. Links provided in emails can be tracked by changing the link url to https://asulearn.appstate.edu/local/onboarding/redirect.php?id=<linkid>&userid=%userid% where <linkid> is the id of the onboarding link the the local_onboarding_redirect_links table.';
$string['lowuseteacher'] = 'Low use teacher message';
$string['lowuseteacher_desc'] = 'Enter the content for the message to be sent to low use teachers. The wildcards %sitename% and %userfirstname% can be used. Links provided in emails can be tracked by changing the link url to https://asulearn.appstate.edu/local/onboarding/redirect.php?id=<linkid>&userid=%userid% where <linkid> is the id of the onboarding link the the local_onboarding_redirect_links table.';
$string['getroles'] = 'Get stored user roles';
$string['sendonboardingnewmessages'] = 'Send onboarding new messages';
$string['sendonboardinglowusemessages'] = 'Send onboarding low use messages';
$string['cleanup'] = 'Cleanup onboarding table';
$string['newmessagesubject'] = 'Welcome to {$a->sitename}!';
$string['lowusemessagesubject'] = 'Make the most of your {$a->sitename} course!';
$string['messageprovider:onboardingmessage'] = 'Onboarding notifications';
$string['missingbothparamserror'] = 'The redirect.php url is missing both url paramters. It must have the id and userid parameters.';
$string['missingiderror'] = 'The redirect.php url must have the id parameter.';
$string['missinguseriderror'] = 'The redirect.php url must have the userid parameter.';
$string['invalidlinkiderror'] = 'The id in the redirect url does not correspond to an entry in the database. Please check the local mdl_local_onboarding_redirect_links table for valid link ids.';
$string['useridparamerror'] = 'The userid parameter must be set to %userid%';
