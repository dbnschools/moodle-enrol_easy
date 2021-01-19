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
 * PHPUnit Tests for testing discussion retrieval
 *
 * @package   enrol_easy
 * @copyright 2021 Jan Dageförde <jan@dagefor.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace enrol_easy;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/badgeslib.php');

/**
 * PHPUnit Tests for testing easy enrolment preconditions
 *
 * @package   enrol_easy
 * @copyright 2021 Jan Dageförde <jan@dagefor.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class enrol_easy_precondition_testcase extends \advanced_testcase {

    /**
     * Test that a new group with the name of the cohort is created.
     */
    public function test_enrol_requires_badge() {
        global $DB;
        $this->resetAfterTest();

        $now = time();
        $badge = new \stdClass();
        $badge->id = null;
        $badge->name = "Test course badge";
        $badge->description = "Testing course badge";
        $badge->timecreated = $now;
        $badge->timemodified = $now;
        $badge->usercreated = 0;
        $badge->usermodified = 0;
        $badge->issuername = "Test issuer";
        $badge->issuerurl = "http://issuer-url.domain.co.nz";
        $badge->issuercontact = "issuer@example.com";
        $badge->expiredate = null;
        $badge->expireperiod = null;
        $badge->type = BADGE_TYPE_SITE;
        $badge->messagesubject = "Test message subject for course badge";
        $badge->message = "Test message body for course badge";
        $badge->attachment = 1;
        $badge->notification = 0;
        $badge->status = BADGE_STATUS_ACTIVE;
        $badge->version = "Version 1";
        $badge->language = "en";
        $badge->imagecaption = "Image caption";
        $badge->imageauthorname = "Image author's name";
        $badge->imageauthoremail = "author@example.com";
        $badge->imageauthorname = "Image author's name";

        $badgeid = $DB->insert_record('badge', $badge, true);
        $badge1 = new \core_badges\badge($badgeid);

        // Require badge as precondition.
        $plugin = enrol_get_plugin('easy');
        $plugin->set_config('precondition', $badgeid);

        // Create two us, but award the badge only to the first one.
        $user1 = $this->getDataGenerator()->create_user();
        $user2 = $this->getDataGenerator()->create_user();
        $badge1->issue($user1->id, true);

        // First user should fulfil the precondition, and the other should not.
        $this->setUser($user1->id);
        $this->assertTrue($plugin->is_precondition_satisfied());
        $this->setUser($user2->id);
        $this->assertFalse($plugin->is_precondition_satisfied());
    }
}