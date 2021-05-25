<?php
require_once(dirname(__FILE__) . '/../../config.php');
require_once(dirname(__FILE__) . '/locallib.php');

require_login();

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/enrol/easy/activate.php'));

$plugin = 'easy';
$manualplugin = enrol_get_plugin($plugin);

$courses = $DB->get_recordset_select('course', 'category > 0', null, '', 'id');

foreach ($courses as $course) {
    $instanceid = null;
    $instances = enrol_get_instances($course->id, true);
    foreach ($instances as $inst) {
        if ($inst->enrol == $plugin) {
            $instanceid = (int)$inst->id;
            break;
        }
    }
    if (empty($instanceid)) {
        $instanceid = $manualplugin->add_default_instance($course);
        if (empty($instanceid)) {
            $instanceid = $manualplugin->add_instance($course);
        }
    }
}
