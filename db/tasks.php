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
 * Scheduled tasks for plugin.
 *
 * @package     local_onboarding
 * @copyright   2023 Michelle Melton <meltonml@appstate.edu>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$tasks = array(
    array(
        'classname' => 'local_onboarding\task\get_user_roles',
        'blocking' => 0,
        'minute' => '5',
        'hour' => '3',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    ),
    array(
        'classname' => 'local_onboarding\task\send_onboarding_new_messages',
        'blocking' => 0,
        'minute' => '5',
        'hour' => '4',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    ),
    array(
        'classname' => 'local_onboarding\task\send_low_use_messages',
        'blocking' => 0,
        'minute' => '5',
        'hour' => '4',
        'day' => '15',
        'dayofweek' => '*',
        'month' => '1,5,8'
    ),
    array(
        'classname' => 'local_onboarding\task\cleanup',
        'blocking' => 0,
        'minute' => '5',
        'hour' => '3',
        'day' => '1',
        'dayofweek' => '*',
        'month' => '*'
    )
);
