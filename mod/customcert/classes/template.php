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
 * Class represents a customcert template.
 *
 * @package    mod_customcert
 * @copyright  2016 Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_customcert;


/**
 * Class represents a customcert template.
 *
 * @package    mod_customcert
 * @copyright  2016 Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class template
{

    /**
     * @var int $id The id of the template.
     */
    protected $id;

    /**
     * @var string $name The name of this template
     */
    protected $name;

    /**
     * @var int $contextid The context id of this template
     */
    protected $contextid;

    /**
     * The constructor.
     *
     * @param \stdClass $template
     */
    public function __construct($template)
    {
        $this->id = $template->id;
        $this->name = $template->name;
        $this->contextid = $template->contextid;
    }

    /**
     * Handles saving data.
     *
     * @param \stdClass $data the template data
     */
    public function save($data)
    {
        global $DB;

        $savedata = new \stdClass();
        $savedata->id = $this->id;
        $savedata->name = $data->name;
        $savedata->timemodified = time();

        $DB->update_record('customcert_templates', $savedata);

        // Only trigger event if the name has changed.
        if ($this->get_name() != $data->name) {
            \mod_customcert\event\template_updated::create_from_template($this)->trigger();
        }
    }

    /**
     * Handles adding another page to the template.
     *
     * @param bool $triggertemplateupdatedevent
     * @return int the id of the page
     */
    public function add_page(bool $triggertemplateupdatedevent = true)
    {
        global $DB;

        // Set the page number to 1 to begin with.
        $sequence = 1;
        // Get the max page number.
        $sql = "SELECT MAX(sequence) as maxpage
                  FROM {customcert_pages} cp
                 WHERE cp.templateid = :templateid";
        if ($maxpage = $DB->get_record_sql($sql, ['templateid' => $this->id])) {
            $sequence = $maxpage->maxpage + 1;
        }

        // New page creation.
        $page = new \stdClass();
        $page->templateid = $this->id;
        $page->width = '210';
        $page->height = '297';
        $page->sequence = $sequence;
        $page->timecreated = time();
        $page->timemodified = $page->timecreated;

        // Insert the page.
        $pageid = $DB->insert_record('customcert_pages', $page);

        $page->id = $pageid;

        \mod_customcert\event\page_created::create_from_page($page, $this)->trigger();

        if ($triggertemplateupdatedevent) {
            \mod_customcert\event\template_updated::create_from_template($this)->trigger();
        }

        return $page->id;
    }

    /**
     * Handles saving page data.
     *
     * @param \stdClass $data the template data
     */
    public function save_page($data)
    {
        global $DB;

        // Set the time to a variable.
        $time = time();

        // Get the existing pages and save the page data.
        if ($pages = $DB->get_records('customcert_pages', ['templateid' => $data->tid])) {
            // Loop through existing pages.
            foreach ($pages as $page) {
                // Only update if there is a difference.
                if ($this->has_page_been_updated($page, $data)) {
                    $width = 'pagewidth_' . $page->id;
                    $height = 'pageheight_' . $page->id;
                    $leftmargin = 'pageleftmargin_' . $page->id;
                    $rightmargin = 'pagerightmargin_' . $page->id;

                    $p = new \stdClass();
                    $p->id = $page->id;
                    $p->width = $data->$width;
                    $p->height = $data->$height;
                    $p->leftmargin = $data->$leftmargin;
                    $p->rightmargin = $data->$rightmargin;
                    $p->timemodified = $time;

                    // Update the page.
                    $DB->update_record('customcert_pages', $p);

                    // Calling code is expected to trigger template_updated
                    // after this method.
                    \mod_customcert\event\page_updated::create_from_page($p, $this)->trigger();
                }
            }
        }
    }

    /**
     * Handles deleting the template.
     *
     * @return bool return true if the deletion was successful, false otherwise
     */
    public function delete()
    {
        global $DB;

        // Delete the pages.
        if ($pages = $DB->get_records('customcert_pages', ['templateid' => $this->id])) {
            foreach ($pages as $page) {
                $this->delete_page($page->id, false);
            }
        }

        // Now, finally delete the actual template.
        if (!$DB->delete_records('customcert_templates', ['id' => $this->id])) {
            return false;
        }

        \mod_customcert\event\template_deleted::create_from_template($this)->trigger();

        return true;
    }

    /**
     * Handles deleting a page from the template.
     *
     * @param int $pageid the template page
     * @param bool $triggertemplateupdatedevent False if page is being deleted
     * during deletion of template.
     */
    public function delete_page(int $pageid, bool $triggertemplateupdatedevent = true): void
    {
        global $DB;

        // Get the page.
        $page = $DB->get_record('customcert_pages', ['id' => $pageid], '*', MUST_EXIST);

        // The element may have some extra tasks it needs to complete to completely delete itself.
        if ($elements = $DB->get_records('customcert_elements', ['pageid' => $page->id])) {
            foreach ($elements as $element) {
                // Get an instance of the element class.
                if ($e = \mod_customcert\element_factory::get_element_instance($element)) {
                    $e->delete();
                } else {
                    // The plugin files are missing, so just remove the entry from the DB.
                    $DB->delete_records('customcert_elements', ['id' => $element->id]);
                }
            }
        }

        // Delete this page.
        $DB->delete_records('customcert_pages', ['id' => $page->id]);

        \mod_customcert\event\page_deleted::create_from_page($page, $this)->trigger();

        // Now we want to decrease the page number values of
        // the pages that are greater than the page we deleted.
        $sql = "UPDATE {customcert_pages}
                   SET sequence = sequence - 1
                 WHERE templateid = :templateid
                   AND sequence > :sequence";
        $DB->execute($sql, ['templateid' => $this->id, 'sequence' => $page->sequence]);

        if ($triggertemplateupdatedevent) {
            \mod_customcert\event\template_updated::create_from_template($this)->trigger();
        }
    }

    /**
     * Handles deleting an element from the template.
     *
     * @param int $elementid the template page
     */
    public function delete_element($elementid)
    {
        global $DB;

        // Ensure element exists and delete it.
        $element = $DB->get_record('customcert_elements', ['id' => $elementid], '*', MUST_EXIST);

        // Get an instance of the element class.
        if ($e = \mod_customcert\element_factory::get_element_instance($element)) {
            $e->delete();
        } else {
            // The plugin files are missing, so just remove the entry from the DB.
            $DB->delete_records('customcert_elements', ['id' => $elementid]);
        }

        // Now we want to decrease the sequence numbers of the elements
        // that are greater than the element we deleted.
        $sql = "UPDATE {customcert_elements}
                   SET sequence = sequence - 1
                 WHERE pageid = :pageid
                   AND sequence > :sequence";
        $DB->execute($sql, ['pageid' => $element->pageid, 'sequence' => $element->sequence]);

        \mod_customcert\event\template_updated::create_from_template($this)->trigger();
    }



    /**
     * Handles copying this template into another.
     *
     * @param object $copytotemplate The template instance to copy to
     */
    public function copy_to_template($copytotemplate)
    {
        global $DB;

        $copytotemplateid = $copytotemplate->get_id();

        // Get the pages for the template, there should always be at least one page for each template.
        if ($templatepages = $DB->get_records('customcert_pages', ['templateid' => $this->id])) {
            // Loop through the pages.
            foreach ($templatepages as $templatepage) {
                $page = clone ($templatepage);
                $page->templateid = $copytotemplateid;
                $page->timecreated = time();
                $page->timemodified = $page->timecreated;
                // Insert into the database.
                $page->id = $DB->insert_record('customcert_pages', $page);
                \mod_customcert\event\page_created::create_from_page($page, $copytotemplate)->trigger();
                // Now go through the elements we want to load.
                if ($templateelements = $DB->get_records('customcert_elements', ['pageid' => $templatepage->id])) {
                    foreach ($templateelements as $templateelement) {
                        $element = clone ($templateelement);
                        $element->pageid = $page->id;
                        $element->timecreated = time();
                        $element->timemodified = $element->timecreated;
                        // Ok, now we want to insert this into the database.
                        $element->id = $DB->insert_record('customcert_elements', $element);
                        // Load any other information the element may need to for the template.
                        if ($e = \mod_customcert\element_factory::get_element_instance($element)) {
                            if (!$e->copy_element($templateelement)) {
                                // Failed to copy - delete the element.
                                $e->delete();
                            } else {
                                \mod_customcert\event\element_created::create_from_element($e)->trigger();
                            }
                        }
                    }
                }
            }

            // Trigger event if loading a template in a course module instance.
            // (No event triggered if copying a system-wide template as
            // create() triggers this).
            if ($copytotemplate->get_context() != \context_system::instance()) {
                \mod_customcert\event\template_updated::create_from_template($copytotemplate)->trigger();
            }
        }
    }

    /**
     * Handles moving an item on a template.
     *
     * @param string $itemname the item we are moving
     * @param int $itemid the id of the item
     * @param string $direction the direction
     */
    public function move_item($itemname, $itemid, $direction)
    {
        global $DB;

        $table = 'customcert_';
        if ($itemname == 'page') {
            $table .= 'pages';
        } else { // Must be an element.
            $table .= 'elements';
        }

        if ($moveitem = $DB->get_record($table, ['id' => $itemid])) {
            // Check which direction we are going.
            if ($direction == 'up') {
                $sequence = $moveitem->sequence - 1;
            } else { // Must be down.
                $sequence = $moveitem->sequence + 1;
            }

            // Get the item we will be swapping with. Make sure it is related to the same template (if it's
            // a page) or the same page (if it's an element).
            if ($itemname == 'page') {
                $params = ['templateid' => $moveitem->templateid];
            } else { // Must be an element.
                $params = ['pageid' => $moveitem->pageid];
            }
            $swapitem = $DB->get_record($table, $params + ['sequence' => $sequence]);
        }

        // Check that there is an item to move, and an item to swap it with.
        if ($moveitem && !empty($swapitem)) {
            $DB->set_field($table, 'sequence', $swapitem->sequence, ['id' => $moveitem->id]);
            $DB->set_field($table, 'sequence', $moveitem->sequence, ['id' => $swapitem->id]);

            \mod_customcert\event\template_updated::create_from_template($this)->trigger();
        }
    }

    /**
     * Returns the id of the template.
     *
     * @return int the id of the template
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * Returns the name of the template.
     *
     * @return string the name of the template
     */
    public function get_name()
    {
        return $this->name;
    }

    /**
     * Returns the context id.
     *
     * @return int the context id
     */
    public function get_contextid()
    {
        return $this->contextid;
    }

    /**
     * Returns the context id.
     *
     * @return \context the context
     */
    public function get_context()
    {
        return \context::instance_by_id($this->contextid);
    }

    /**
     * Returns the context id.
     *
     * @return \context_module|null the context module, null if there is none
     */
    public function get_cm()
    {
        $context = $this->get_context();
        if ($context->contextlevel === CONTEXT_MODULE) {
            return get_coursemodule_from_id('customcert', $context->instanceid, 0, false, MUST_EXIST);
        }

        return null;
    }

    /**
     * Ensures the user has the proper capabilities to manage this template.
     *
     * @throws \required_capability_exception if the user does not have the necessary capabilities (ie. Fred)
     */
    public function require_manage()
    {
        require_capability('mod/customcert:manage', $this->get_context());
    }

    /**
     * Creates a template.
     *
     * @param string $templatename the name of the template
     * @param int $contextid the context id
     * @return \mod_customcert\template the template object
     */
    public static function create($templatename, $contextid)
    {
        global $DB;

        $template = new \stdClass();
        $template->name = $templatename;
        $template->contextid = $contextid;
        $template->timecreated = time();
        $template->timemodified = $template->timecreated;
        $template->id = $DB->insert_record('customcert_templates', $template);

        $template = new \mod_customcert\template($template);

        \mod_customcert\event\template_created::create_from_template($template)->trigger();

        return $template;
    }

    /**
     * Checks if a page has been updated given form information
     *
     * @param \stdClass $page
     * @param \stdClass $formdata
     * @return bool
     */
    private function has_page_been_updated($page, $formdata): bool
    {
        $width = 'pagewidth_' . $page->id;
        $height = 'pageheight_' . $page->id;
        $leftmargin = 'pageleftmargin_' . $page->id;
        $rightmargin = 'pagerightmargin_' . $page->id;

        if ($page->width != $formdata->$width) {
            return true;
        }

        if ($page->height != $formdata->$height) {
            return true;
        }

        if ($page->leftmargin != $formdata->$leftmargin) {
            return true;
        }

        if ($page->rightmargin != $formdata->$rightmargin) {
            return true;
        }

        return false;
    }

    /**
     * Generate the PDF for the template.
     *
     * @param bool $preview true if it is a preview, false otherwise
     * @param int|null $userid the id of the user whose certificate we want to view
     * @param bool $return Do we want to return the contents of the PDF?
     * @return string|void Can return the PDF in string format if specified.
     */
    public function generate_pdf(bool $preview = false, ?int $userid = null, bool $return = false)
    {
        global $CFG, $DB, $USER;

        if (empty($userid)) {
            $user = $USER;
        } else {
            $user = \core_user::get_user($userid);
        }

        require_once($CFG->libdir . '/pdflib.php');
        require_once($CFG->dirroot . '/mod/customcert/lib.php');

        // Get the pages for the template, there should always be at least one page for each template.
        if ($pages = $DB->get_records('customcert_pages', ['templateid' => $this->id], 'sequence ASC')) {
            // Create the pdf object.
            $pdf = new \pdf();

            $customcert = $DB->get_record('customcert', ['templateid' => $this->id]);

            // I want to have my digital diplomas without having to change my preferred language.
            $userlang = $USER->lang ?? current_language();

            // Check the $customcert exists as it is false when previewing from mod/customcert/manage_templates.php.
            if ($customcert) {
                $forcelang = mod_customcert_force_current_language($customcert->language);
                if (!empty($forcelang)) {
                    // This is a failsafe -- if an exception triggers during the template rendering, this should still execute.
                    // Preventing a user from getting trapped with the wrong language.
                    \core_shutdown_manager::register_function('force_current_language', [$userlang]);
                }
            }

            // If the template belongs to a certificate then we need to check what permissions we set for it.
            if (!empty($customcert->protection)) {
                $protection = explode(', ', $customcert->protection);
                $pdf->SetProtection($protection);
            }

            if (empty($customcert->deliveryoption)) {
                $deliveryoption = certificate::DELIVERY_OPTION_INLINE;
            } else {
                $deliveryoption = $customcert->deliveryoption;
            }

            // Remove full-stop at the end, if it exists, to avoid "..pdf" being created and being filtered by clean_filename.
            $filename = rtrim(format_string($this->name, true, ['context' => $this->get_context()]), '.');

            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetTitle($filename);
            $pdf->SetAutoPageBreak(true, 0);

            // This is the logic the TCPDF library uses when processing the name. This makes names
            // such as 'الشهادة' become empty, so set a default name in these cases.
            $filename = preg_replace('/[\s]+/', '_', $filename);
            $filename = preg_replace('/[^a-zA-Z0-9_\.-]/', '', $filename);

            if (empty($filename)) {
                $filename = get_string('certificate', 'customcert');
            }

            $filename = clean_filename($filename . '.pdf');


            // Loop through the pages and display their content.
            foreach ($pages as $page) {

                // Add the page to the PDF.
                if ($page->width > $page->height) {
                    $orientation = 'L';
                } else {
                    $orientation = 'P';
                }

                $pdf->AddPage($orientation, [$page->width, $page->height]);

                $pdf->SetMargins($page->leftmargin, 0, $page->rightmargin);
                // Get the elements for the page.
                if ($elements = $DB->get_records('customcert_elements', ['pageid' => $page->id], 'sequence ASC')) {
                    // Loop through and display.
                    foreach ($elements as $element) {
                        if ($element->name == "FirmaQR" || $element->name == "FirmaCadena" || $element->name == "FirmaNombre" || $element->name == "FirmaFecha") {
                            continue;
                        }

                        // Get an instance of the element class.
                        if ($e = \mod_customcert\element_factory::get_element_instance($element)) {
                            $e->render($pdf, $preview, $user);
                        }
                    }
                }
            }

            // Check the $customcert exists as it is false when previewing from mod/customcert/manage_templates.php.
            if ($customcert) {
                // We restore original language.
                if ($userlang != $customcert->language) {
                    mod_customcert_force_current_language($userlang);
                }
            }

            // Genera el PDF original
            $pdf_content = $pdf->Output('', 'S');


            if (!$preview) {
                /*DIGITAL SIGNATURE Initialization*/
                //Obtenemos el issue para saber si ya tiene hash
                $hashFlag = false;
                $issue = $DB->get_record('customcert_issues', ['userid' => $user->id, 'customcertid' => $customcert->id], '*', MUST_EXIST);
                if (!empty($issue->hash)) {
                    $hash = $issue->hash; 
                    //get hash from db
                    $hashFlag = false;
                } else {
                    $hash = $this->firmar_pdf_con_web_service($pdf_content); //get hash from wb
                    //var_dump($issue);
                    //exit();
                    
                    if (!empty($hash)) {
                        $issue->hash = $hash;
                        $DB->update_record('customcert_issues', $issue); //update hash in db
                        $hashFlag = true;
                    } else {
                        throw new \Exception("Failed to generate hash from the web service.");
                    }
                }

                if (!empty($hash)) {
                    // Obtener el  .xml usando el hash
                    $folio = '';
                    $firma_dig = '';
                    $firma_firmante = '';
                    $firma_fecha = '';


                    if (!$hashFlag && !empty($issue->folio_id)) {
                        //get data from db
                        $folio = $issue->folio_id;
                        $firma_dig = $issue->firma_hex;
                        $firma_firmante = $issue->nombre_firmante;
                        $firma_fecha = $issue->firma_fecha;
                    } else {
                        $evidencia_xml = $this->obtener_xml($hash); //get xml from wb
                        if (empty($evidencia_xml)) {
                            throw new \Exception("Failed to generate xml from the web service.");
                        }
                        if ($evidencia_xml['contenido']) {
                            $xml_content = $evidencia_xml['contenido'];
                            $xml_name = $evidencia_xml['nombre'];

                            $signing_info = $this->interpretar_xml($xml_content);
                            if ($signing_info) {

                                $signing_info = json_decode($signing_info, true);
                                $folio = $signing_info['folio_id']; //get folio
                                $firma_dig = base64_encode($signing_info['firma_hex']); //get firma
                                $firma_firmante = $signing_info['nombre_firmante'];
                                $firma_fecha = $signing_info['fecha_firma'];

                                $issue->folio_id = $folio;
                                $issue->firma_hex = $firma_dig;
                                $issue->nombre_firmante = $firma_firmante;
                                $issue->firma_fecha = $firma_fecha;
                                $DB->update_record('customcert_issues', $issue); //update issue in db

                                //save the xml file
                                $fs = get_file_storage();

                                //Elimina un XML anterior si ya existía.
                                if ($existingfile = $fs->get_file($this->get_context()->id, 'mod_customcert', 'signedxmls', $issue->id, '/', $xml_name)) {
                                    $existingfile->delete();
                                }

                                // Guarda el XML como archivo en Moodle.
                                $file_record = [
                                    'contextid' => $this->get_context()->id,
                                    'component' => 'mod_customcert',
                                    'filearea'  => 'signedxmls',
                                    'itemid'    => $issue->folio_id,
                                    'filepath'  => '/',
                                    'filename'  => $xml_name,
                                ];

                                $storedfile = $fs->create_file_from_string($file_record, $xml_content);
                            }
                        }
                    }
                }
                //generar el pdf con la firma digital
                if (!$preview && $hash) {
                    $pdf_download = new \pdf();
                    $pdf_download->setPrintHeader(false);
                    $pdf_download->setPrintFooter(false);
                    $pdf_download->SetAutoPageBreak(true, 0);

                    foreach ($pages as $page) {
                        $orientation = ($page->width > $page->height) ? 'L' : 'P';
                        $pdf_download->AddPage($orientation, [$page->width, $page->height]);
                        $pdf_download->SetMargins($page->leftmargin, 0, $page->rightmargin);

                        if ($elements = $DB->get_records('customcert_elements', ['pageid' => $page->id], 'sequence ASC')) {
                            foreach ($elements as $element) {
                               if ($element->name == "FirmaCadena") {
                                $element->data = $firma_dig;
                                } else if ($element->name == "FirmaNombre") {
                                    $element->data =$element->data. ' '. $firma_firmante;
                                } else if ($element->name == "FirmaFecha") {
                                    $element->data = $element->data. ' '. $firma_fecha;
                                }
                                //$DB->update_record('customcert_elements', $element); //update issue in db

                                if ($e = \mod_customcert\element_factory::get_element_instance($element)) {
                                    $e->render($pdf_download, $preview, $user);
                                }
                            }
                        }
                    }
                }
                /*DIGITAL SIGNATURE End*/
            }

            //Imprimir pdf con QR de firma digital

            if (!$preview) {
                if ($return) {
                    return $pdf_download->Output('', 'S');
                }
                $pdf_download->Output($filename, $deliveryoption);
            } else {
                if ($return) {
                    return $pdf->Output('', 'S');
                }
                $pdf->Output($filename, $deliveryoption);
            }
        }
    }

    //Conexion a webservice
    public static function getSoapClient()
    {

        global $CFG;

        $wsdl_url = $CFG->webservice_url;

        $options = array(
            'login' => $CFG->webservice_username,
            'password' => $CFG->webservice_password,
            'trace' => 1,
            'exception' => 1,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'stream_context' => stream_context_create([
                'http' => [
                    'header' => 'Authorization: Basic ' . base64_encode($CFG->webservice_username . ':' . $CFG->webservice_password)
                ]
            ])
        );

        try {
            return new \SoapClient($wsdl_url, $options);
        } catch (\SoapFault $e) {
            error_log("Error al crear cliente SOAP: " . $e->getMessage());
            echo "Error en conexión SOAP: Verifique que el Web Service esté activo o que las credenciales y la URL WSDL esten correctas." . $e->getMessage();
            throw $e;

            die();
        }
    }

    // Método para obtener el hash
    public static function firmar_pdf_con_web_service($pdf_content)
    {
        global $CFG;

        try {
            $client = self::getSoapClient();

            // var_dump($client);

            $solicitud = [
                'asunto' => "prueba",
                'documento' => [
                    'nombre' => "Certificado.pdf",
                    'contenido' => base64_encode($pdf_content)
                ],
                'listaFirmantes' => [
                    'item' => [$CFG->firmante, $CFG->firmante_pw]
                ],
                'listaOrdenada' => true,
                'tipoDocumento' => 1
            ];

            $response = $client->nuevaSolicitudBatch(['solicitud' => $solicitud]);

            if (isset($response->return)) {

                return $response->return; // Devolvemos el hash
            } else {
                error_log("La respuesta no devolvió el hash: " . print_r($response, true));
                return  false;
            }
        } catch (\SoapFault $e) {
            error_log("Error en SOAP al obtener hash: " . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            error_log("Error general al obtener hash: " . $e->getMessage());
            return false;
        }
    }

    // Método para obtener el .xml usando el hash
    public static function obtener_xml($hash)
    {
        try {
            $client = self::getSoapClient();

            $params = ['hashSolicitud' => $hash];

            $response = $client->obtenerEvidenciaXml($params); // Get .xml

            if (!isset($response->return)) {
                throw new \Exception("La respuesta no contiene el objeto return");
            }

            $documento = $response->return;

            // Validamos que haya contenido
            if (empty($documento->contenido)) {
                throw new \Exception("El documento no contiene datos");
            }

            if (empty($documento->nombre)) {
                throw new \Exception("El documento no contiene nombre válido");
            }
            
            return [
                'contenido' => $documento->contenido,
                'nombre' => $documento->nombre
            ];
        } catch (\SoapFault $e) {
            error_log("Error SOAP en obtener_documento_firmado: " . $e->getMessage());
            return  false;
        } catch (\Exception $e) {
            error_log("Error en obtener_documento_firmado: " . $e->getMessage());
            return false;
        }
    }

    //Método para leer el xml
    public static function interpretar_xml($xml_content)
    {
        try {
            $xml = simplexml_load_string($xml_content);
            if ($xml === false) {
                throw new \Exception("XML inválido");
            }

            $info = [
                'folio_id' => (string)$xml->document['id'],
                'firma_hex' => (string)$xml->ca->signatory->signature,
                'nombre_firmante' => (string)$xml->ca->signatory['name'],
                'fecha_firma' => (string)$xml->ca->signatory->signature['date'],

            ];

            $info['firma_hex'] = str_replace(' ', '', $info['firma_hex']);

            return json_encode($info, JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            error_log("Error procesando XML: " . $e->getMessage());
            return false;
        }
    }
}
