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
class enrol_easy_plugin extends enrol_plugin {

    public function get_form() {
        global $CFG, $OUTPUT, $USER;

        if (!enrol_is_enabled('easy') || !isloggedin()) {
            return '';
        }

        require_once(dirname(__FILE__) . '/locallib.php');

        $enrol_easy_qr = new moodle_url('/enrol/easy/qr.php');
        $enrol_easy_qr = str_replace("http://", "https://", $enrol_easy_qr);

        $data = array(
            'internal' => array(
                'sesskey' => $USER->sesskey
            ),
            'pages' => array(
                'enrol_easy' => new moodle_url('/enrol/easy/index.php'),
                'enrol_easy_qr' => $enrol_easy_qr
            ),
            'component' => array(
                'main_javascript' => new moodle_url('/enrol/easy/js/enrol_easy.js'),
                'jquery' => new moodle_url('/enrol/easy/js/jquery-3.2.0.min.js'),
            ),
            'config' => array(
                'qrenabled' => $this->get_config('qrenabled') && ($this->get_config('showqronmobile') || !isMobile()),
            ),
            'lang' => array(
                'enrolform_course_code' => get_string('enrolform_course_code', 'enrol_easy'),
                'enrolform_submit' => get_string('enrolform_submit', 'enrol_easy')
            ),
        );

        return $OUTPUT->render_from_template('enrol_easy/form', $data);
    }

    public function use_standard_editing_ui() {
        return true;
    }

    public function can_add_instance($courseid) {
        global $DB;

        $context = context_course::instance($courseid, MUST_EXIST);

        if (!has_capability('moodle/course:enrolconfig', $context) or!has_capability('enrol/easy:config', $context)) {
            return false;
        }

        if ($DB->record_exists('enrol', array('courseid' => $courseid, 'enrol' => 'easy'))) {
            return false;
        }

        return true;
    }

    public function can_delete_instance($instance) {
        $context = context_course::instance($instance->courseid);

        return has_capability('enrol/easy:delete', $context);
    }

    public function can_hide_show_instance($instance) {
        $context = context_course::instance($instance->courseid);

        return has_capability('enrol/easy:config', $context);
    }

    public function edit_instance_form($instance, MoodleQuickForm $mform, $context) {
        global $COURSE, $CFG, $DB, $OUTPUT;

        require_once(dirname(__FILE__) . '/locallib.php');

        $mform->updateAttributes(array('id' => 'enrol_easy_settings'));

        $options = $this->get_status_options();
        $mform->addElement('select', 'status', get_string('status', 'enrol_easy'), $options);
        $mform->setType('status', PARAM_NOTAGS);
        $mform->setDefault('status', $this->get_config('status', 'enrol_easy'));
        $mform->addHelpButton('status', 'status', 'enrol_easy');

        $options = array('optional' => true, 'defaultunit' => 86400);
        $mform->addElement('duration', 'enrolperiod', get_string('enrolperiod', 'enrol_self'), $options);
        $mform->addHelpButton('enrolperiod', 'enrolperiod', 'enrol_self');

        $options = $this->get_expirynotify_options();
        $mform->addElement('select', 'expirynotify', get_string('expirynotify', 'core_enrol'), $options);
        $mform->addHelpButton('expirynotify', 'expirynotify', 'core_enrol');

        $options = array('optional' => false, 'defaultunit' => 86400);
        $mform->addElement('duration', 'expirythreshold', get_string('expirythreshold', 'core_enrol'), $options);
        $mform->addHelpButton('expirythreshold', 'expirythreshold', 'core_enrol');
        $mform->disabledIf('expirythreshold', 'expirynotify', 'eq', 0);

        $options = array('optional' => true);
        $mform->addElement('date_time_selector', 'enrolstartdate', get_string('enrolstartdate', 'enrol_easy'), $options);
        $mform->setType('enrolstartdate', PARAM_NOTAGS);
        $mform->setDefault('enrolstartdate', 0);
        $mform->addHelpButton('enrolstartdate', 'enrolstartdate', 'enrol_easy');

        $options = array('optional' => true);
        $mform->addElement('date_time_selector', 'enrolenddate', get_string('enrolenddate', 'enrol_easy'), $options);
        $mform->setType('enrolenddate', PARAM_NOTAGS);
        $mform->setDefault('enrolenddate', 0);
        $mform->addHelpButton('enrolenddate', 'enrolenddate', 'enrol_easy');

        $mform->addElement('select', 'customint4', get_string('sendcoursewelcomemessage', 'enrol_self'),
        enrol_send_welcome_email_options());
        $mform->addHelpButton('customint4', 'sendcoursewelcomemessage', 'enrol_self');
        
        $options = array('cols' => '60', 'rows' => '8');
        $mform->addElement('textarea', 'customtext1', get_string('customwelcomemessage', 'enrol_easy'), $options);
        $mform->addHelpButton('customtext1', 'customwelcomemessage', 'enrol_easy');

        $mform->addElement('header', 'nameforyourheaderelement', get_string('header_coursecodes', 'enrol_easy'));

        $allcodesobj = $DB->get_records('enrol_easy');
        $allcodes = array();

        foreach ($allcodesobj as $c) {
            $allcodes[] = $c;
        }

        $code = $DB->get_records('enrol_easy', array('course_id' => $COURSE->id, 'group_id' => null));

        if ($code && (count($code) > 1)) {
            $DB->delete_records('enrol_easy', array('course_id' => $COURSE->id, 'group_id' => null));
            $code = NULL;
        } else {
            $code = array_pop($code);
        }

        if (!$code) {
            $code = randomstring(6);

            while (array_key_exists($code, $allcodes)) {
                $code = randomstring(6);
            }

            $dataobj = new stdClass();
            $dataobj->course_id = $COURSE->id;
            $dataobj->enrolmentcode = $code;
            $DB->insert_record('enrol_easy', $dataobj);

            $allcodes[] = $code;
        } else {
            $code = $code->enrolmentcode;
        }

        $coursetext = get_string('coursetext', 'enrol_easy');

        $codetext = $mform->addElement('text', 'course_' . $COURSE->id, $coursetext . $COURSE->fullname, array('readonly' => ''));
        $mform->setType('course_' . $COURSE->id, PARAM_NOTAGS);
        $mform->setDefault('course_' . $COURSE->id, $code);
        $mform->updateElementAttr('course_' . $COURSE->id, array('data-type' => 'enroleasycode')); // For whatever reason it refuses to set a class, so data attr it is.
        $mform->updateElementAttr('course_' . $COURSE->id, array('data-coursename' => $COURSE->fullname));

        $groups = $DB->get_records('groups', array('courseid' => $COURSE->id));

        foreach ($groups as $group) {

            $code = $DB->get_records('enrol_easy', array('group_id' => $group->id));

            if ($code && (count($code) > 1)) {
                $DB->delete_records('enrol_easy', array('course_id' => $COURSE->id, 'group_id' => $group->id));
                $code = NULL;
            } else {
                $code = array_pop($code);
            }

            if ($code && $code->course_id != $COURSE->id) {
                $DB->delete_records('enrol_easy', array('enrolmentcode' => $code->enrolmentcode));
                $code = NULL;
            }

            if (!$code) {
                $code = randomstring(6);

                while (array_key_exists($code, $allcodes)) {
                    $code = randomstring(6);
                }

                $dataobj = new stdClass();
                $dataobj->course_id = $COURSE->id;
                $dataobj->group_id = $group->id;
                $dataobj->enrolmentcode = $code;
                $DB->insert_record('enrol_easy', $dataobj);
                $allcodes[] = $code;
            } else {
                $code = $code->enrolmentcode;
            }

            $grouptext = get_string('grouptext', 'enrol_easy');

            $codetext = $mform->addElement('text', 'group_' . $group->id, $grouptext . $group->name, array('readonly' => '', 'value' => $code));
            $mform->setType('group_' . $group->id, PARAM_NOTAGS);
            $mform->setDefault('group_' . $group->id, $code);
            $mform->updateElementAttr('group_' . $group->id, array('data-type' => 'enroleasycode')); // For whatever reason it refuses to set a class, so data attr it is.
            $mform->updateElementAttr('group_' . $group->id, array('data-coursename' => $COURSE->fullname));
            $mform->updateElementAttr('group_' . $group->id, array('data-groupname' => $group->name));
        }

        $mform->addElement('checkbox', 'regenerate_codes', get_string('regenerate_codes', 'enrol_easy'));
        $mform->setType('regenerate_codes', PARAM_NOTAGS);
        $mform->setDefault('regenerate_codes', $this->get_config('regenerate_codes'));
        $mform->addHelpButton('regenerate_codes', 'regenerate_codes', 'enrol_easy');


        if ($this->get_config('qrenabled')) {

            $jquery_url = new moodle_url('/enrol/easy/js/jquery-3.2.0.min.js');
            $qrcode_url = new moodle_url('/enrol/easy/js/jquery.qrcode.min.js');
            $js_url = new moodle_url('/enrol/easy/js/enrol_easy.js');

            $mform->addElement('html', '<script src="' . $jquery_url . '"></script>');
            $mform->addElement('html', '<script src="' . $qrcode_url . '"></script>');
            $mform->addElement('html', '<script src="' . $js_url . '"></script>');
        }
    }

    /**
     * Return an array of valid options for the expirynotify property.
     *
     * @return array
     */
    protected function get_expirynotify_options() {
        $options = array(0 => get_string('no'),
            1 => get_string('expirynotifyenroller', 'enrol_self'),
            2 => get_string('expirynotifyall', 'enrol_self'));
        return $options;
    }

    public function get_instance_defaults() {
        $fields = array();

        return $fields;
    }

    public function edit_instance_validation($data, $files, $instance, $context) {

        $errors = array();

        return $errors;
    }

    public function update_instance($instance, $data) {
        global $DB;

        $enrolmentcodes = $DB->get_records('enrol_easy', array('course_id' => $instance->courseid));

        $allcodesobj = $DB->get_records('enrol_easy');
        $allcodes = array();

        foreach ($allcodesobj as $code) {
            $allcodes[] = $code;
        }

        if ($data->regenerate_codes) {

            foreach ($enrolmentcodes as $enrolmentcode) {

                $code = randomstring(6);

                while (array_key_exists($code, $allcodes)) {
                    $code = randomstring(6);
                }

                $dataobj = new stdClass();
                $dataobj->id = $enrolmentcode->id;
                $dataobj->enrolmentcode = $code;

                $allcodes[] = $code;
                $DB->update_record('enrol_easy', $dataobj);
            }
        }
        parent::update_instance($instance, $data);
        header('Location: ' . $data->returnurl);
        exit;
    }

    public function add_instance($course, array $fields = null) {
        return parent::add_instance($course, $fields);
    }

    protected function get_status_options() {
        $options = array(ENROL_INSTANCE_ENABLED => get_string('yes'),
            ENROL_INSTANCE_DISABLED => get_string('no'));
        return $options;
    }

    public function allow_unenrol(stdClass $instance) {
        return true;
    }

    public function allow_unenrol_user(stdClass $instance, stdClass $ue) {
        return true;
    }

    public function allow_manage(stdClass $instance) {
        return true;
    }

    public function get_user_enrolment_actions(course_enrolment_manager $manager, $ue) {
        $actions = array();
        $context = $manager->get_context();
        $instance = $ue->enrolmentinstance;
        $params = $manager->get_moodlepage()->url->params();
        $params['ue'] = $ue->id;
        if ($this->allow_unenrol_user($instance, $ue) && has_capability("enrol/easy:unenrol", $context)) {
            $url = new moodle_url('/enrol/unenroluser.php', $params);
            $actions[] = new user_enrolment_action(new pix_icon('t/delete', ''), get_string('unenrol', 'enrol'), $url, array('class' => 'unenrollink', 'rel' => $ue->id));
        }
        if ($this->allow_manage($instance) && has_capability("enrol/easy:manage", $context)) {
            $url = new moodle_url('/enrol/editenrolment.php', $params);
            $actions[] = new user_enrolment_action(new pix_icon('t/edit', ''), get_string('edit'), $url, array('class' => 'editenrollink', 'rel' => $ue->id));
        }
        return $actions;
    }

    public function add_default_instance($course) {

        $fields = $this->get_instance_defaults();

        return $this->add_instance($course, $fields);
    }

    public function enrol_course_delete($course) {

        $enrolmentcodes = $DB->delete_records('enrol_easy', array('course_id' => $course->id));

        parent::enrol_course_delete($course);
    }

        /**
     * Send welcome email to specified user.
     *
     * @param stdClass $instance
     * @param stdClass $user user record
     * @return void
     */
    public function email_welcome_message($instance, $user) {
        global $CFG, $DB;

        $course = $DB->get_record('course', array('id'=>$instance->courseid), '*', MUST_EXIST);
        $context = context_course::instance($course->id);

        $a = new stdClass();
        $a->coursename = format_string($course->fullname, true, array('context'=>$context));
        $a->profileurl = "$CFG->wwwroot/user/view.php?id=$user->id&course=$course->id";

        if (trim($instance->customtext1) !== '') {
            $message = $instance->customtext1;
            $key = array('{$a->coursename}', '{$a->profileurl}', '{$a->fullname}', '{$a->email}');
            $value = array($a->coursename, $a->profileurl, fullname($user), $user->email);
            $message = str_replace($key, $value, $message);
            if (strpos($message, '<') === false) {
                // Plain text only.
                $messagetext = $message;
                $messagehtml = text_to_html($messagetext, null, false, true);
            } else {
                // This is most probably the tag/newline soup known as FORMAT_MOODLE.
                $messagehtml = format_text($message, FORMAT_MOODLE, array('context'=>$context, 'para'=>false, 'newlines'=>true, 'filter'=>true));
                $messagetext = html_to_text($messagehtml);
            }
        } else {
            $messagetext = get_string('welcometocoursetext', 'enrol_self', $a);
            $messagehtml = text_to_html($messagetext, null, false, true);
        }

        $subject = get_string('welcometocourse', 'enrol_self', format_string($course->fullname, true, array('context'=>$context)));

        $sendoption = $instance->customint4;
        $contact = $this->get_welcome_email_contact($sendoption, $context);

        // Directly emailing welcome message rather than using messaging.
        email_to_user($user, $contact, $subject, $messagetext, $messagehtml);
    }
    
        /**
     * Get the "from" contact which the email will be sent from.
     *
     * @param int $sendoption send email from constant ENROL_SEND_EMAIL_FROM_*
     * @param $context context where the user will be fetched
     * @return mixed|stdClass the contact user object.
     */
    public function get_welcome_email_contact($sendoption, $context) {
        global $CFG;

        $contact = null;
        // Send as the first user assigned as the course contact.
        if ($sendoption == ENROL_SEND_EMAIL_FROM_COURSE_CONTACT) {
            $rusers = array();
            if (!empty($CFG->coursecontact)) {
                $croles = explode(',', $CFG->coursecontact);
                list($sort, $sortparams) = users_order_by_sql('u');
                // We only use the first user.
                $i = 0;
                do {
                    $allnames = get_all_user_name_fields(true, 'u');
                    $rusers = get_role_users($croles[$i], $context, true, 'u.id,  u.confirmed, u.username, '. $allnames . ',
                    u.email, r.sortorder, ra.id', 'r.sortorder, ra.id ASC, ' . $sort, null, '', '', '', '', $sortparams);
                    $i++;
                } while (empty($rusers) && !empty($croles[$i]));
            }
            if ($rusers) {
                $contact = array_values($rusers)[0];
            }
        } else if ($sendoption == ENROL_SEND_EMAIL_FROM_KEY_HOLDER) {
            // Send as the first user with enrol/self:holdkey capability assigned in the course.
            list($sort) = users_order_by_sql('u');
            $keyholders = get_users_by_capability($context, 'enrol/self:holdkey', 'u.*', $sort);
            if (!empty($keyholders)) {
                $contact = array_values($keyholders)[0];
            }
        }

        // If send welcome email option is set to no reply or if none of the previous options have
        // returned a contact send welcome message as noreplyuser.
        if ($sendoption == ENROL_SEND_EMAIL_FROM_NOREPLY || empty($contact)) {
            $contact = core_user::get_noreply_user();
        }

        return $contact;
    }
}
