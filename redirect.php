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
 * Redirect URL to capture link clicks
 *
 * @package   local_onboarding
 * @copyright 2025, Lina Brown <brownli2@appstate.edu>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
global $DB;

$id = required_param('id', PARAM_ALPHANUM); // Link id from the table.
$userid = required_param('userid', PARAM_INT); // Userid wildcard.
$userclicktime = time();

// Check if we have already logged a click on this link for this user.
$existingclick = $DB->record_exists('local_onboarding_link_clicks', [
    'linkid' => $id,
    'userid' => $userid,
]);

// Get record so we can get full url to redirect.
$redirectlinkrecord = $DB->get_record('local_onboarding_redirect_links', ['id'  => $id]);

// Only log the click if the user hasn't already clicked it.
if (!$existingclick) {
    // Create an object to log the link click.
    $linkclickrecord = new stdClass();
    $linkclickrecord->linkid = $redirectlinkrecord->id;
    $linkclickrecord->userid = $userid;
    $linkclickrecord->timeclicked = $userclicktime;

    // Log the click.
    $DB->insert_record('local_onboarding_link_clicks', $linkclickrecord);
}

// Redirect user to site.
header('Location: '.$redirectlinkrecord->fullurl);
die();
