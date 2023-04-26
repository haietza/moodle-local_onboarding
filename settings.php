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
 * Admin settings.
 *
 * @package   local_onboarding
 * @category  admin
 * @copyright 2023, Michelle Melton <meltonml@appstate.edu>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// Ensure the configurations for this site are set.
if ($hassiteconfig) {
    $settings = new admin_settingpage('local_onboarding_settings', get_string('pluginname', 'local_onboarding'));

    if ($ADMIN->fulltree) {
        $settings->add(new admin_setting_confightmleditor('onboarding/welcometeacher',
            get_string('welcometeacher', 'local_onboarding'), get_string('welcometeacher_desc', 'local_onboarding'), '', PARAM_RAW));
        $settings->add(new admin_setting_confightmleditor('onboarding/welcomestudent', get_string('welcomestudent', 'local_onboarding'),
            get_string('welcomestudent_desc', 'local_onboarding'), '', PARAM_RAW));
        $settings->add(new admin_setting_confightmleditor('onboarding/lowuseteacher',
            get_string('lowuseteacher', 'local_onboarding'), get_string('lowuseteacher_desc', 'local_onboarding'), '', PARAM_RAW));
    }

    $ADMIN->add('localplugins', $settings);
}
