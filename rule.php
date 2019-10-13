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
 * Implementaton of the quizaccess_fpbiometric plugin.
 *
 * @package   quizaccess_fpbiometric
 * @copyright 2019 victorcatn
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/quiz/accessrule/accessrulebase.php');


/**
 * A rule requiring the use of a fingerprint reader.
 *
 * @copyright  2019 victorcatn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_fpbiometric extends quiz_access_rule_base {

    public function is_preflight_check_required($attemptid) {
        return empty($attemptid);
    }

    public function add_preflight_check_form_fields(mod_quiz_preflight_check_form $quizform,
            MoodleQuickForm $mform, $attemptid) {

	global $PAGE,$USER;
        $mform->addElement('header', 'fingerprintheader',
                get_string('fingerprintheader', 'quizaccess_fpbiometric'));

        $mform->addElement('static', 'fingerprintmessage', '',
                get_string('fingerprintstatement', 'quizaccess_fpbiometric'));

        $mform->addElement('checkbox', 'valid', '','',array('hidden'=>true));

        $PAGE->requires->js_call_amd('quizaccess_fpbiometric/fpbiometric', 'init',
                array($USER->email));
    }

    public function validate_preflight_check($data, $files, $errors, $attemptid) {
        if (empty($data['valid'])) {
            $errors['fpbiometric'] = get_string('notvalid', 'quizaccess_fpbiometric');
        }

        return $errors;
    }

    public static function make(quiz $quizobj, $timenow, $canignoretimelimits) {

        if (empty($quizobj->get_quiz()->fingerprintrequired)) {
            return null;
        }

        return new self($quizobj, $timenow);
    }

    public static function add_settings_form_fields(
            mod_quiz_mod_form $quizform, MoodleQuickForm $mform) {
        $mform->addElement('select', 'fingerprintrequired',
                get_string('fingerprintrequired', 'quizaccess_fpbiometric'),
                array(
                    0 => get_string('notrequired', 'quizaccess_fpbiometric'),
                    1 => get_string('fingerprintrequiredoption', 'quizaccess_fpbiometric'),
                ));
        $mform->addHelpButton('fingerprintrequired',
                'fingerprintrequired', 'quizaccess_fpbiometric');
    }

    public static function save_settings($quiz) {
        global $DB;
        if (empty($quiz->fingerprintrequired)) {
            $DB->delete_records('quizaccess_fpbiometric', array('quizid' => $quiz->id));
        } else {
            if (!$DB->record_exists('quizaccess_fpbiometric', array('quizid' => $quiz->id))) {
                $record = new stdClass();
                $record->quizid = $quiz->id;
                $record->fingerprintrequired = 1;
                $DB->insert_record('quizaccess_fpbiometric', $record);
            }
        }
    }

    public static function delete_settings($quiz) {
        global $DB;
        $DB->delete_records('quizaccess_fpbiometric', array('quizid' => $quiz->id));
    }

    public static function get_settings_sql($quizid) {
        return array(
            'fingerprintrequired',
            'LEFT JOIN {quizaccess_fpbiometric} fpbiometric ON fpbiometric.quizid = quiz.id',
            array());
    }
}
