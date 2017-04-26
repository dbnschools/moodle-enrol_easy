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
 * Allows course enrolment via a simple text code.
 *
 * @package   enrol_easy
 * @copyright 2017 Dearborn Public Schools
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../config.php');
require_once(dirname(__FILE__) . '/locallib.php');

require_login();

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/enrol/easy/index.php'));
$PAGE->set_title(get_string('enrolform_pagetitle', 'enrol_easy'));
$PAGE->set_heading(get_string('enrolform_heading', 'enrol_easy'));

if (!enrol_is_enabled('easy')) {
    die(get_string('error_disabled_global', 'enrol_easy'));
}

$mform = new enrolform();

if ($mform->get_data()) {

    $roles = get_user_roles($PAGE->context, $USER->id, false);

    $enrolment_code = required_param('enrolform_course_code', PARAM_TEXT);

    $course = $DB->get_record('enrol_easy', array('enrolmentcode' => $enrolment_code), '*');

    if ($course && $course->course_id) {

        //$context = context_course::instance($course->instance_id);
        $studentrole = $DB->get_record('role', array('shortname'=>'student'));
        $instance = $DB->get_record('enrol', array('courseid'=>$course->course_id, 'enrol'=>'easy'), '*', MUST_EXIST);

        if ($course) {

            $plugin = enrol_get_plugin('easy');

            if ($instance->status == 1) {
                echo $OUTPUT->header();
                echo get_string('error_disabled_course', 'enrol_easy');
                $mform->display();
                echo $OUTPUT->footer();
                exit;
            }

            $now = time();

            if ($instance->enrolstartdate != 0 && $instance->enrolstartdate > $now) {
                echo $OUTPUT->header();
                echo get_string('error_enrolstartdate', 'enrol_easy');
                $mform->display();
                echo $OUTPUT->footer();
                exit;
            }

            if ($instance->enrolenddate != 0 && $instance->enrolenddate < $now) {
                echo $OUTPUT->header();
                echo get_string('error_enrolenddate', 'enrol_easy');
                $mform->display();
                echo $OUTPUT->footer();
                exit;
            }

            $plugin->enrol_user($instance, $USER->id, $studentrole->id);

            if ($course->group_id) {
                require_once($CFG->dirroot . '/group/lib.php');
                groups_add_member($course->group_id, $USER->id);
            }

            redirect(new moodle_url('/course/view.php', array('id'=>$course->course_id)));
            exit;

        }
    }
    else {
        echo $OUTPUT->header();
        echo get_string('error_invalid_code', 'enrol_easy');
        $mform->display();
        echo $OUTPUT->footer();
        exit;
    }

} else {

    echo $OUTPUT->header();
    $mform->display();
    echo $OUTPUT->footer();

}

