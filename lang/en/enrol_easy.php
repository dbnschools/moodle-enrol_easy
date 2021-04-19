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


$string['pluginname'] = 'Easy enrollments';
$string['pluginname_desc'] = 'Allows easy enrollment via a text code.';

$string['enrolform_course_code'] = 'Enrollment Code';
$string['enrolform_submit'] = 'Enroll';
$string['enrolform_heading'] = 'Enroll in a Course';
$string['enrolform_pagetitle'] = 'Enroll in a Course';

$string['header_coursecodes'] = 'Enrollment codes';

$string['status'] = 'Enabled';
$string['status_help'] = 'Set to "Yes" to enabled enrollment. Set to "No" to disable enrollment.';
$string['enrolstartdate'] = 'Enrollment Begins';
$string['enrolstartdate_help'] = 'Students will be unable to enroll prior to this date.';

$string['enrolenddate'] = 'Enrollment Ends';
$string['enrolenddate_help'] = 'Students will be unable to enroll after this date.';

$string['regenerate_codes'] = 'Regenerate Codes';
$string['regenerate_codes_help'] = 'Check this and click "Save changes" to re-create all above enrollment codes.';

$string['qrenabled'] = 'Enable Enrol via QR Codes';
$string['qrenableddesc'] = 'Enable Enrol via QR Codes';

$string['showqronmobile'] = 'Enable QR Code Reader on Mobile';
$string['showqronmobiledesc'] = 'Enable Enrol via QR Codes on mobile devices. May not work on all mobile browsers.  Preferred use of QR codes is in the Chrome browser and on a desktop, laptop, or Chromebook.';

$string['easy:unenrolself'] = 'Unenroll from course';
$string['easy:config'] = 'Configure Easy Enrollment instances';
$string['easy:delete'] = 'Delete Easy Enrollment instances';
$string['easy:manage'] = 'Manage Easy Enrollment instances';
$string['easy:unenrol'] = 'Unenrol from Easy Enrollment instances';
$string['unenrolselfconfirm'] = 'Do you really want to unenrol yourself from course "{$a}"?';

$string['error_disabled_global'] = 'Easy enrollment is disabled site-wide.';
$string['error_disabled_global'] = 'Easy enrollment is disabled for this course.';
$string['error_enrolstartdate'] = 'Enrollment has not begin for this course yet.';
$string['error_enrolenddate'] = 'Enrollment for this course has ended.';
$string['error_invalid_code'] = 'Invalid enrollment code.';

$string['coursetext'] = 'Course:  ';
$string['grouptext'] = 'Group:  ';

$string['sendexpirynotificationstask'] = 'Easy enrollment expiration notification task';

$string['expirymessageenrollersubject'] = 'Easy enrolment expiry notification';
$string['expirymessageenrollerbody'] = 'Easy enrolment in the course \'{$a->course}\' will expire within the next {$a->threshold} for the following users:

{$a->users}

To extend their enrolment, go to {$a->extendurl}';
$string['expirymessageenrolledsubject'] = 'Easy enrolment expiry notification';
$string['expirymessageenrolledbody'] = 'Dear {$a->user},

This is a notification that your enrolment in the course \'{$a->course}\' is due to expire on {$a->timeend}.

If you need help, please contact {$a->enroller}.';

$string['customwelcomemessage'] = 'Custom welcome message';
$string['customwelcomemessage_help'] = 'A custom welcome message may be added as plain text or Moodle-auto format, including HTML tags and multi-lang tags.

The following placeholders may be included in the message:

* Course name {$a->coursename}
* Link to user\'s profile page {$a->profileurl}
* User email {$a->email}
* User fullname {$a->fullname}';