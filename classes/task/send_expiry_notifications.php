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
 * Send expiry notifications task.
 *
 * @package   enrol_easy
 * @author    Lukas Celinak <lukascelinak@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace enrol_easy\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Send expiry notifications task.
 *
 * @package   enrol_easy
 * @author    Lukas Celinak <lukascelinak@gmail.com>
 * @copyright 2021 Lukas Celinak
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class send_expiry_notifications extends \core\task\scheduled_task {

    /**
     * Name for this task.
     *
     * @return string
     */
    public function get_name() {
        return get_string('sendexpirynotificationstask', 'enrol_easy');
    }

    /**
     * Run task for sending expiry notifications.
     */
    public function execute() {
        $enrol = enrol_get_plugin('easy');
        $trace = new \text_progress_trace();
        $enrol->send_expiry_notifications($trace);
    }

}
