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
 * Create new DB tables
 * @package   local_onboarding
 * @copyright 2025, Lina Brown <brownli2@appstate.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Create new DB tables
 * @param int  $oldversion version of the plugin that does not have tables.
 * @return boolean true
 */
function xmldb_local_onboarding_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();
    if ($oldversion < 2024020101) {
        $redirectlinktable = new xmldb_table("local_onboarding_redirect_links");
        if (!$dbman->table_exists($redirectlinktable)) {
            $redirectlinktable->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $redirectlinktable->add_field('linkname', XMLDB_TYPE_CHAR, '255', null, null, null, null);
            $redirectlinktable->add_field('fullurl', XMLDB_TYPE_CHAR, '255', null, null, null, null);
            $redirectlinktable->add_field('emailtype', XMLDB_TYPE_CHAR, '255', null, null, null, null);
            $redirectlinktable->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $dbman->create_table($redirectlinktable);
        }
        $linkclicktable = new xmldb_table("local_onboarding_link_clicks");
        if (!$dbman->table_exists($linkclicktable)) {
            $linkclicktable->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
            $linkclicktable->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
            $linkclicktable->add_field('linkid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
            $linkclicktable->add_field('timeclicked', XMLDB_TYPE_INTEGER, '10', XMLDB_NOTNULL);
            $linkclicktable->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $dbman->create_table($linkclicktable);
        }
        upgrade_plugin_savepoint(true, 2024020101, "local", "onboarding");
    }
    return true;
}
