<?php
/**
 * Allows course enrolment via a simple text code.
 *
 * @package   enrol_easy
 * @copyright 2017 Dearborn Public Schools
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("$CFG->libdir/formslib.php");
require_once(dirname(__FILE__) . '/../../config.php');

function randomstring($length = 10) {
    //$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $characters = '23456789abcdefghijkmnpqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
/*
 * https://stackoverflow.com/questions/4117555/simplest-way-to-detect-a-mobile-device
 */
function isMobile() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

class enrolform extends moodleform {
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        $mform->addElement('text', 'enrolform_course_code', get_string('enrolform_course_code', 'enrol_easy'));
        $mform->setType('enrolform_course_code', PARAM_NOTAGS);

        $mform->addElement('submit', 'enrolform_submit', get_string('enrolform_submit', 'enrol_easy'));
    }
    function validation($data, $files) {
        return array();
    }
}
