<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_greetings
 * @copyright   2022 Giovanni <giovanni.scalmati@hospitalitaliano.org.ar>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
require_once($CFG->dirroot. '/local/greetings/lib.php');
require_once($CFG->dirroot. '/local/greetings/message_form.php');

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/greetings/index.php'));
$PAGE->set_pagelayout('standard');

// Con PAGE-> SET_TITLE defino el nombre de la pagina.
$PAGE->set_title("Greetings Students");
$PAGE->set_heading(get_string('pluginname', 'local_greetings'));

// Cuerpo del site.
$messageform = new local_greetings_message_form();

echo $OUTPUT->header();
echo local_greetings_get_greeting($USER);
echo '<hr>';

// Display del form -> $messageform.
$messageform->display();
// Traigo los records de la BD de Local_greetings_msgs
$messages = $DB->get_records('local_greetings_msgs');
// Foreach para imprimirlos
echo $OUTPUT->box_start('card-columns');

foreach ($messages as $m) {
    echo html_writer::start_tag('div', array('class' => 'card'));
    echo html_writer::start_tag('div', array('class' => 'card-body'));
    echo html_writer::tag('p', $m->text, array('class' => 'h3'));
    echo html_writer::start_tag('p', array('class' => 'card-text'));
    echo html_writer::tag('small', userdate($m->timecreated), array('class' => 'text-muted'));
    echo html_writer::end_tag('p');
    echo html_writer::end_tag('div');
    echo html_writer::end_tag('div');
}

echo $OUTPUT->box_end();
// Consulto si se llenó la data.
if ($data = $messageform->get_data()) {
    // Var_dump($data); --> Funciona como verificador estándar para ver qué devuelve el form.

    $message = required_param('message', PARAM_TEXT);

    // echo $OUTPUT->heading($message, 4); - Esto seria para escribirlo en pleno Body
    if (!empty($message)) {
        $record = new stdClass;
        $record->text = $message;
        $record->timecreated = time();

        $DB->insert_record('local_greetings_msgs', $record);
    }
}

// Time() funcion que trae el tiempo del equipo del usuario.
echo '<hr>';
$now = time();
echo userdate($now);
echo '<hr>';

// Format_Float() funcion que le da formato mas normal a las variables double.
/* $grade = 20.00 / 3;
echo format_float($grade, 2);
echo $OUTPUT->footer(); */
