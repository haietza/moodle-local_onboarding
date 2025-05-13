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
 * Custom html editor class with validation for redirect links.
 * @package   local_onboarding
 * @copyright 2025, Lina Brown <brownli2@appstate.edu>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_onboarding;

/**
 * Custom admin_setting_confightmleditor to add validation to make sure trackable links are correct.
 */
class admin_setting_confightmleditor_with_validation extends \admin_setting_confightmleditor {

    /**
     * Helper function to check that the redirect URL is in the right format if user is hoping to track links.
     * Correct format is http://sitename.com/local/onboarding/redirect.php?id=<linkid>&userid=%userid%
     * @param string $text text in the form
     * @throws Exception
     * Redirect URL can be wrong in the following ways:
     * 1. no id + no userid
     * 2. no id + userid = %userid%
     * 3. no id + userid is not %userid%
     * 4. id + no userid
     * 5. id + userid is not %userid%
     * 6. id is not in table
     */
    public function check_url_syntax($text) {
        global $DB;
        libxml_use_internal_errors(true);
        $dd = new \DOMDocument();
        $dd->loadHTML(mb_convert_encoding($text, 'HTML-ENTITIES', 'UTF-8'));
        // Get link tags - could be tracking clicks for more than one link.
        $links = $dd->getElementsByTagName('a');
        $redirecturl = 'redirect.php';
        foreach ($links as $link) {
            $href = html_entity_decode($link->getAttribute('href'));
            // Start checking to make sure the redirect.php URL has the correct bits.
            $urlstructure = parse_url($href);
            // If not using the redirect.php file do not need to check anything.
            if (empty($urlstructure['path']) || !str_ends_with($urlstructure['path'], $redirecturl)) {
                continue;
            }
            // Check if there are BOTH query parameters.
            if (!isset($urlstructure['query'])) {
                throw new \Exception(get_string('missingbothparamserror', 'local_onboarding'));
                // If we have query params should be id=10&userid=%userid.
            }
            parse_str($urlstructure['query'], $result);
            if (empty($result['id']) || empty($result['userid'])) {
                if (empty($result['id'])) {
                    throw new \Exception (get_string('missingiderror', 'local_onboarding'));
                } else if (empty($result['userid'])) {
                    throw new \Exception (get_string('missinguseriderror', 'local_onboarding'));
                }
            }
            // Check linkid is in redirect links table.
            if (!is_numeric($result['id']) || !$DB->record_exists('local_onboarding_redirect_links', ['id' => $result['id']])) {
                throw new \Exception(get_string('invalidlinkiderror', 'local_onboarding'));
            }
            if ($result['userid'] !== '%userid%') {
                throw new \Exception(get_string('useridparamerror', 'local_onboarding'));
            }
        }
        // Restore libxml error handling to default.
        libxml_clear_errors();
        libxml_use_internal_errors(false);
        return $text;
    }
    /**
     * Custom validation to check for correct URL for link tracking.
     * @param array $data - data submitted to form
     * @return true or string, true if everything is correct or error message
     */
    public function validate($data) {
        $parentresult = parent::validate($data);
        if ($parentresult !== true) {
            return $parentresult;
        }
        try {
            $this->check_url_syntax($data);
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
