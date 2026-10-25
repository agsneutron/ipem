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
 * Configurable Reports a Moodle block for creating customizable reports
 *
 * @copyright  2020 Juan Leyva <juan@moodle.com>
 * @package    block_configurable_reports
 * @author     Juan leyva <http://www.twitter.com/jleyvadelgado>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Export report
 *
 * @param object $report
 * @return void
 */
function export_report($report) {
    $table = $report->table;
    $filename = 'report_' . (time()) . '.xml';
    $headers = $table->head;

    $dom = new DOMDocument('1.0', 'UTF-8');
    $top = $dom->createElement('records');
    $dom->appendChild($top);
    foreach($table->data as $key=>$data){
        $row_ = $dom->createElement("row_".++$key);
        $top->appendChild($row_);
        foreach($data as $index => $value){
            $header = remueveAcentos(trim($headers[$index]));
            $header = remueveSpecialChar(trim($header));
            $header = str_replace(" ", "_", trim($header));
             $row = $dom->createElement($header,$value);
             $row_->appendChild($row);
    }
    }

    $downloadfilename = clean_filename($filename);
    header('Content-type: text/xml');
    header('Content-disposition: attachment; filename=' . $downloadfilename);
    echo $dom->saveXML();
    exit;
}

function remueveSpecialChar($str){

    $res = preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$str);
    return $res;
}

function remueveAcentos($str){
    
    $to_replace = array(
        array(
            array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â'),
		    array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a')
		),
		array(
		    array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		    array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e')
		),
		array(
		    array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		    array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		),
		array(
		    array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		    array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		),
		array(
		    array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		    array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		),
    );
    
    foreach($to_replace as $from_to){
        $str = str_replace($from_to[0], $from_to[1], $str);
    }
    
    return $str;

}