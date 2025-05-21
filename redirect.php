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

require_once("../../config.php");

global $DB;
$cache = cache::make('local_onboarding', 'onboardinglinkdata');

$id = required_param('id', PARAM_INT); // Link id from the table.
$userid = required_param('userid', PARAM_INT); // Userid wildcard.
$userclicktime = time();

// Check if we have already logged a click on this link for this user.
$existingclick = $DB->record_exists('local_onboarding_link_clicks', [
    'linkid' => $id,
    'userid' => $userid,
]);
$validuser = $DB->record_exists('user', ['id' => $userid]);
// Get record so we can get full url to redirect.

// Check cache for redirectlink before we try to look it up directly.
$key = $id;
$data = $cache->get($key);

if ($data === false) {
    $redirectlinkrecord = $DB->get_record('local_onboarding_redirect_links', ['id'  => $id]);
    // Just to be safe, don't want to cache empty record, should not get to this point bc of form validation but just to be extra safe.
    if ($redirectlinkrecord) {
        $data = $redirectlinkrecord;
        $cache->set($key, $data);
    } else {
        throw new \Exception(get_string('invalidlinkrecord', 'local_onboarding'));
    }
} else {
    $redirectlinkrecord = $data;
}

// Only log the click if the user hasn't already clicked it and userid is valid.
if (!$existingclick && $validuser) {
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
