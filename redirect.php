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
 * Redirect URL.
 *
 * @package   local_onboarding
 * @category  admin
 * @copyright 2025, Lina Brown <brownli2@appstate.edu>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
global $DB;

echo 'This is the redirect page!';

$id = required_param('id', PARAM_ALPHANUM); // This will the the id from the URL in the email
$useripaddress = $_SERVER['REMOTE_ADDR'];
$userbrowser = $_SERVER['HTTP_USER_AGENT'];
$userclicktime = time();

// Get the record for the link.
$redirectlinkrecord = $DB->get_record('local_onboarding_redirect_links', ['shortname'  => $id]);

// Create an object to log the link click.
$linkclickrecord = new stdClass();
$linkclickrecord->linkid = $redirectlinkrecord->id;
$linkclickrecord->useripaddress = $useripaddress;
$linkclickrecord->userbrowser = $userbrowser;
$linkclickrecord->userclicktime = $userclicktime;

// Log the click.
$DB->insert_record('local_onboarding_link_clicks', $linkclickrecord);

//redirect user to Confluence
header('Location: '.$redirectlinkrecord->fullurl);
die();