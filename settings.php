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

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_heading('enrol_easy_settings', '', get_string('pluginname_desc', 'enrol_easy')));

    //--- enrol instance defaults ----------------------------------------------------------------------------
    $settings->add(new admin_setting_heading('enrol_manual_defaults',
        get_string('enrolinstancedefaults', 'admin'), get_string('enrolinstancedefaults_desc', 'admin')));

    $settings->add(new admin_setting_configcheckbox('enrol_easy/defaultenrol',
        get_string('defaultenrol', 'enrol'), get_string('defaultenrol_desc', 'enrol'), 1));

    $settings->add(new admin_setting_configcheckbox('enrol_easy/qrenabled',
        get_string('qrenabled', 'enrol_easy'), null, 1));

    $settings->add(new admin_setting_configcheckbox('enrol_easy/showqronmobile',
        get_string('showqronmobile', 'enrol_easy'), get_string('showqronmobiledesc', 'enrol_easy'), 0));

    $preconditionchoices = ['' => get_string('no_precondition', 'enrol_easy')];
    $records = $DB->get_records_sql('SELECT b.id, b.name FROM {badge} b ORDER BY b.id ASC');
    foreach ($records as $r) {
        $preconditionchoices[$r->id] = $r->name;
    }

    $settings->add(new admin_setting_configselect('enrol_easy/precondition',
        get_string('precondition', 'enrol_easy'), get_string('preconditiondesc', 'enrol_easy'), '', $preconditionchoices));

}
