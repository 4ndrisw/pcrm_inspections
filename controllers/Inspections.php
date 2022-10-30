<?php

use app\services\inspections\InspectionsPipeline;

defined('BASEPATH') or exit('No direct script access allowed');

class Inspections extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('inspections_model');
        //$this->load->model('equipment_category_model');
        $this->load->model('clients_model');
        $this->load->model('tasks_model');
        //$this->categories = $this->equipment_category_model->get();

    }

    /* Get all inspections in case user go on index page */
    public function index($id = '')
    {
        if (!has_permission('inspections', '', 'view')) {
            access_denied('inspections');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('inspections', 'admin/tables/table'));
        }
        $data['inspectionid']            = $id;
        $data['title']                 = _l('inspections_tracking');
        $this->load->view('admin/inspections/manage', $data);
    }


    /* Add new inspection or update existing */
    public function inspection($id='')
    {

        $inspection = $this->inspections_model->get($id);

        if (!$inspection || !user_can_view_inspection($id)) {
            blank_page(_l('inspection_not_found'));
        }

        $data['inspection'] = $inspection;
        $data['edit']     = false;
        $title            = _l('preview_inspection');


        if ($this->input->get('customer_id')) {
            $data['customer_id'] = $this->input->get('customer_id');
        }

        $data['staff']             = $this->staff_model->get('', ['active' => 1]);
        $data['inspection_statuses'] = $this->inspections_model->get_statuses();
        $data['title']             = $title;

        $inspection->date       = _d($inspection->date);

        if ($inspection->project_id !== null) {
            $this->load->model('projects_model');
            $inspection->project_data = $this->projects_model->get($inspection->project_id);
        }

        //$data = inspection_mail_preview_data($template_name, $inspection->clientid);

        $data['inspection_members'] = $this->inspections_model->get_inspection_members($id,true);
        $data['inspection_items'] = $this->inspections_model->get_inspection_items($inspection->id, $inspection->project_id);

        $data['activity']          = $this->inspections_model->get_inspection_activity($id);
        $data['inspection']          = $inspection;
        $data['members']           = $this->staff_model->get('', ['active' => 1]);
        $data['inspection_statuses'] = $this->inspections_model->get_statuses();
        $data['totalNotes']        = total_rows(db_prefix() . 'notes', ['rel_id' => $id, 'rel_type' => 'inspection']);
        /*
        $tags = get_tags_in($inspection->id, 'inspection');

        $equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tags[0])));
        $equipment_model = $equipment_type .'_model';
        $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';

        include_once($model_path);
        $this->load->model($equipment_model);
        $equipment = $this->{$equipment_model}->get('', ['rel_id' => $inspection->id]);
        $data['equipment'] = $equipment[0];
        */

        $data['send_later'] = false;
        if ($this->session->has_userdata('send_later')) {
            $data['send_later'] = true;
            $this->session->unset_userdata('send_later');
        }
        $this->session->set_userdata('inspection_id', $id);
        $this->session->set_userdata('project_id', $inspection->project_id);

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('inspections', 'admin/tables/small_table'));
        }

        $this->load->view('admin/inspections/inspection_preview', $data);
    }


    /* Add new inspection or update existing */
    public function inspection_item($id, $task_id)
    {

        $inspection = $this->inspections_model->get($id);
        $task = $this->tasks_model->get($task_id);

        if (!$inspection || !user_can_view_inspection($id)) {
            blank_page(_l('inspection_not_found'));
        }
        $inspection->task_id       = $task_id;
        $inspection->documentations = $this->inspections_model->get_inspection_documentation($id,$task_id);

        $_inspection_item = $this->inspections_model->get_inpection_item($id,'',$task_id);
        $inspection_item = $_inspection_item[0];

        $inspection->inspection_item = $inspection_item;

        //$data['inspection'] = $inspection;
        $data['edit']     = false;
        $title            = _l('preview_inspection');


        if ($this->input->get('customer_id')) {
            $data['customer_id'] = $this->input->get('customer_id');
        }

        $data['staff']             = $this->staff_model->get('', ['active' => 1]);
        $data['inspection_statuses'] = $this->inspections_model->get_statuses();
        $data['title']             = $title;

        $inspection->date       = _d($inspection->date);

        if ($inspection->project_id !== null) {
            $this->load->model('projects_model');
            $inspection->project_data = $this->projects_model->get($inspection->project_id);
        }

        //$data = inspection_mail_preview_data($template_name, $inspection->clientid);

        $data['inspection_members'] = $this->inspections_model->get_inspection_members($id,true);

        $data['activity']          = $this->inspections_model->get_inspection_activity($id);
        $data['task']              = $task;

        $data['members']           = $this->staff_model->get('', ['active' => 1]);
        $data['inspection_statuses'] = $this->inspections_model->get_statuses();
        $data['totalNotes']        = total_rows(db_prefix() . 'notes', ['rel_id' => $id, 'rel_type' => 'inspection']);

        $data['editable_class']          = 'not_editable';
        $data['editableText_class']          = 'noteditableText';
        if($inspection->status == 2 ){
            $data['editable_class']          = 'editable';
            $data['editableText_class']          = 'editableText';

        }

        $tanggal_inspeksi_raw = isset($inspection->date) ? _d($inspection->date) : '1970-01-01';
        $tahun = getYear($tanggal_inspeksi_raw);
        $bulan = getMonth($tanggal_inspeksi_raw);
        $tanggal = getDay($tanggal_inspeksi_raw);
        $data['tanggal_inspeksi'] = $tanggal.' '.$bulan.' '.$tahun;

        $tags = get_tags_in($task_id, 'task');

        $equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tags[0])));
        $inspection->equipment_type = $equipment_type;
        //$inspection->tag_id = get_tag_by_name($tags[0])->id;
        
        $tag_id = $inspection_item->tag_id;
        //$inspection->categories = $inspection_item->category;;
        $equipment_model = $equipment_type .'_model';
        $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';

        if (!file_exists($model_path)) {
            set_alert('danger', _l('file_not_found ;', $equipment_model));
            log_activity('File '. $equipment_model . ' not_found');
            redirect(admin_url('inspections/inspection/'.$id));
        }

        include_once($model_path);
        $this->load->model($equipment_model);
        $equipment = $this->{$equipment_model}->get('', ['rel_id' => $inspection->id, 'task_id' =>$task_id]);
        $inspection->equipment = $equipment;

        $data['inspection']          = $inspection;
        $data['equipment']          = reset($equipment);

        $data['send_later'] = false;
        if ($this->session->has_userdata('send_later')) {
            $data['send_later'] = true;
            $this->session->unset_userdata('send_later');
        }
        $this->session->set_userdata('inspection_id', $id);
        $this->session->set_userdata('project_id', $inspection->project_id);

        /*
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('inspections', 'admin/tables/small_table'));
        }
        */

        $this->load->view('admin/inspections/inspection_item_preview', $data);
    }


    /* Add new inspection or update existing */
    public function inspection_report($id, $task_id)
    {
        $inspection = $this->inspections_model->get($id);
        //$task = $this->tasks_model->get($task_id);
        if (!$inspection || !user_can_view_inspection($id)) {
            blank_page(_l('inspection_not_found'));
        }
        $_inspection_item = $this->inspections_model->get_inspection_items($id, $inspection->project_id, $task_id);
        $inspection_item = $_inspection_item[0];
        $tags = get_tags_in($task_id, 'task');

        $equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tags[0])));
        $inspection->equipment_type = $equipment_type;
        //$tag_id = get_available_tags($task_id);

        //$tag_id = $inspection_item->tag_id;
        //$inspection->tag_id = get_tag_by_name($tags[0])->id;

        //$inspection->categories = get_option('tag_id_'.$tag_id);

        $inspection->inspection_item = $inspection_item;
        $equipment_model = $equipment_type .'_model';
        $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';

        if (!file_exists($model_path)) {
            set_alert('danger', _l('file_not_found ;', $equipment_model));
            log_activity('File '. $equipment_model . ' not_found');
            redirect(admin_url('inspections/inspection/'.$id));
        }

        include_once($model_path);
        $this->load->model($equipment_model);
        $equipments = $this->{$equipment_model}->get('', ['rel_id' => $inspection->id, 'task_id' =>$task_id]);
        $equipment = reset($equipments);

        $inspection->equipment = $equipment;

        $data = inspection_data($inspection, $task_id);
        /*
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die();
        */

        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $file = str_replace(' ', '_', 'laporan_' . $equipment['jenis_pesawat']);
        $file = strtolower($file);

        $template = FCPATH .'modules/'. INSPECTIONS_MODULE_NAME . '/assets/resources/'. $file .'.docx';

        $templateProcessor = $phpWord->loadTemplate($template);

        $templateProcessor->setValues($data);

        //$templateProcessor->setImageValue('CompanyLogo', 'path/to/company/logo.png');
        $perusahaan = str_replace(' ', '_', $inspection->client->company);
        $temp_filename = strtoupper($perusahaan .'-'. $equipment['jenis_pesawat']) .'-'. $inspection->formatted_number .'-'.$task_id. '.docx';
        $templateProcessor->saveAs($temp_filename);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$temp_filename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($temp_filename));
        flush();
        readfile($temp_filename);
        unlink($temp_filename);
        exit;

    }
    /* Add new inspection */
    public function create()
    {
        if ($this->input->post()) {

            $inspection_data = $this->input->post();

            $save_and_send_later = false;
            if (isset($inspection_data['save_and_send_later'])) {
                unset($inspection_data['save_and_send_later']);
                $save_and_send_later = true;
            }

            if (!has_permission('inspections', '', 'create')) {
                access_denied('inspections');
            }

            $next_inspection_number = get_option('next_inspection_number');
            $_format = get_option('inspection_number_format');
            $_prefix = get_option('inspection_prefix');

            $prefix  = isset($inspection->prefix) ? $inspection->prefix : $_prefix;
            $format  = isset($inspection->number_format) ? $inspection->number_format : $_format;
            $number  = isset($inspection->number) ? $inspection->number : $next_inspection_number;

            $date = date('Y-m-d');

            $inspection_data['formatted_number'] = inspection_number_format($number, $format, $prefix, $date);

            $id = $this->inspections_model->add($inspection_data);

            if ($id) {
                set_alert('success', _l('added_successfully', _l('inspection')));

                $redUrl = admin_url('inspections/inspection/' . $id);

                if ($save_and_send_later) {
                    $this->session->set_userdata('send_later', true);
                    // die(redirect($redUrl));
                }

                redirect(
                    !$this->set_inspection_pipeline_autoload($id) ? $redUrl : admin_url('inspections/inspection/')
                );
            }
        }
        $title = _l('create_new_inspection');

        if ($this->input->get('customer_id')) {
            $data['customer_id'] = $this->input->get('customer_id');
        }

        $data['staff']             = $this->staff_model->get('', ['active' => 1]);
        //$data['categories']        = $this->equipment_category_model->get();
        $data['inspection_statuses'] = $this->inspections_model->get_statuses();
        $data['title']             = $title;

        $this->load->view('admin/inspections/inspection_create', $data);
    }

    /* update inspection */
    public function update($id)
    {
        if ($this->input->post()) {
            $inspection_data = $this->input->post();

            $save_and_send_later = false;
            if (isset($inspection_data['save_and_send_later'])) {
                unset($inspection_data['save_and_send_later']);
                $save_and_send_later = true;
            }

            if (!has_permission('inspections', '', 'edit')) {
                access_denied('inspections');
            }

            $next_inspection_number = get_option('next_inspection_number');
            $format = get_option('inspection_number_format');
            $_prefix = get_option('inspection_prefix');

            $number_settings = $this->get_number_settings($id);

            $prefix = isset($number_settings->prefix) ? $number_settings->prefix : $_prefix;

            $number  = isset($inspection_data['number']) ? $inspection_data['number'] : $next_inspection_number;

            $date = date('Y-m-d');

            $inspection_data['formatted_number'] = inspection_number_format($number, $format, $prefix, $date);

            $success = $this->inspections_model->update($inspection_data, $id);
            if ($success) {
                set_alert('success', _l('updated_successfully', _l('inspection')));
            }

            if ($this->set_inspection_pipeline_autoload($id)) {
                redirect(admin_url('inspections/'));
            } else {
                redirect(admin_url('inspections/inspection/' . $id));
            }
        }

            $inspection = $this->inspections_model->get($id);

            if (!$inspection || !user_can_view_inspection($id)) {
                blank_page(_l('inspection_not_found'));
            }

            $data['inspection'] = $inspection;
            /*
            $tags = get_tags_in($inspection->id, 'inspection');

            $data['jenis_pesawat'] = $tags[0];

            $equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tags[0])));
            $data['equipment_type'] = $equipment_type;
            $equipment_model = $equipment_type .'_model';
            $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';

            include_once($model_path);
            $this->load->model($equipment_model);
            $equipment = $this->{$equipment_model}->get('', ['rel_id' => $inspection->id]);
            */
            //$data['equipment'] = $equipment[0];

            $data['edit']     = true;
            $title            = _l('edit', _l('inspection_lowercase'));


        if ($this->input->get('customer_id')) {
            $data['customer_id'] = $this->input->get('customer_id');
        }


        $data['inspection_members']  = $this->inspections_model->get_inspection_members($id);

        $data['staff']             = $this->staff_model->get('', ['active' => 1]);
        //$data['categories']        = $this->equipment_category_model->get();
        $data['inspection_statuses'] = $this->inspections_model->get_statuses();
        $data['title']             = $title;
        $this->load->view('admin/inspections/inspection_update', $data);
    }

    public function get_number_settings($id){
        $this->db->select('prefix');
        $this->db->where('id', $id);
        return $this->db->get(db_prefix() . 'inspections')->row();

    }
    public function update_number_settings($id)
    {
        $response = [
            'success' => false,
            'message' => '',
        ];
        if (has_permission('inspections', '', 'edit')) {
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'inspections', [
                'prefix' => $this->input->post('prefix'),
            ]);
            if ($this->db->affected_rows() > 0) {
                $response['success'] = true;
                $response['message'] = _l('updated_successfully', _l('inspection'));
            }
        }

        echo json_encode($response);
        die;
    }

    public function validate_inspection_number()
    {
        $isedit          = $this->input->post('isedit');
        $number          = $this->input->post('number');
        $date            = $this->input->post('date');
        $original_number = $this->input->post('original_number');
        $number          = trim($number);
        $number          = ltrim($number, '0');

        if ($isedit == 'true') {
            if ($number == $original_number) {
                echo json_encode(true);
                die;
            }
        }

        if (total_rows(db_prefix() . 'inspections', [
            'YEAR(date)' => date('Y', strtotime(to_sql_date($date))),
            'number' => $number,
        ]) > 0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }


    public function add_note($rel_id)
    {
        if ($this->input->post() && user_can_view_inspection($rel_id)) {
            $this->misc_model->add_note($this->input->post(), 'inspection', $rel_id);
            echo $rel_id;
            header("Refresh:0");
        }
    }

    public function get_notes($id)
    {
        if (user_can_view_inspection($id)) {
            $data['notes'] = $this->misc_model->get_notes($id, 'inspection');
            $this->load->view('admin/includes/sales_notes_template', $data);
        }
    }

    public function mark_action_status($status, $id)
    {
        if (!has_permission('inspections', '', 'edit')) {
            access_denied('inspections');
        }
        $success = $this->inspections_model->mark_action_status($status, $id);
        if ($success) {
            set_alert('success', _l('inspection_status_changed_success'));
        } else {
            set_alert('danger', _l('inspection_status_changed_fail'));
        }
        if ($this->set_inspection_pipeline_autoload($id)) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(admin_url('inspections/inspection/' . $id));
        }
    }


    public function set_inspection_pipeline_autoload($id)
    {
        if ($id == '') {
            return false;
        }

        if ($this->session->has_userdata('inspection_pipeline')
                && $this->session->userdata('inspection_pipeline') == 'true') {
            $this->session->set_flashdata('inspectionid', $id);

            return true;
        }

        return false;
    }

    /* Convert inspection to jobreport */
    public function convert_to_jobreport($id)
    {
        if (!has_permission('jobreports', '', 'create')) {
            access_denied('jobreports');
        }
        if (!$id) {
            die('No inspection found');
        }
        $draft_jobreport = false;
        if ($this->input->get('save_as_draft')) {
            $draft_jobreport = true;
        }
        $jobreportid = $this->inspections_model->convert_to_jobreport($id, false, $draft_jobreport);
        if ($jobreportid) {
            set_alert('success', _l('inspection_convert_to_jobreport_successfully'));
            redirect(admin_url('jobreports/jobreport/' . $jobreportid));
        } else {
            if ($this->session->has_userdata('inspection_pipeline') && $this->session->userdata('inspection_pipeline') == 'true') {
                $this->session->set_flashdata('inspectionid', $id);
            }
            if ($this->set_inspection_pipeline_autoload($id)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect(admin_url('inspections/inspection/' . $id));
            }
        }
    }

    public function copy($id)
    {
        if (!has_permission('inspections', '', 'create')) {
            access_denied('inspections');
        }
        if (!$id) {
            die('No inspection found');
        }
        $new_id = $this->inspections_model->copy($id);
        if ($new_id) {
            set_alert('success', _l('inspection_copied_successfully'));
            if ($this->set_inspection_pipeline_autoload($new_id)) {
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                redirect(admin_url('inspections/inspection/' . $new_id));
            }
        }
        set_alert('danger', _l('inspection_copied_fail'));
        if ($this->set_inspection_pipeline_autoload($id)) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(admin_url('inspections/inspection/' . $id));
        }
    }

    /* Delete inspection */
    public function delete($id)
    {
        if (!has_permission('inspections', '', 'delete')) {
            access_denied('inspections');
        }
        if (!$id) {
            redirect(admin_url('inspections'));
        }
        $success = $this->inspections_model->delete($id);
        if (is_array($success)) {
            set_alert('warning', _l('is_invoiced_inspection_delete_error'));
        } elseif ($success == true) {
            set_alert('success', _l('deleted', _l('inspection')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('inspection_lowercase')));
        }
        redirect(admin_url('inspections'));
    }

    /* Used in kanban when dragging and mark as */
    public function update_inspection_status()
    {
        if ($this->input->post() && $this->input->is_ajax_request()) {
            $this->inspections_model->update_inspection_status($this->input->post());
        }
    }

    public function clear_signature($id)
    {
        if (has_permission('inspections', '', 'delete')) {
            $this->inspections_model->clear_signature($id);
        }

        redirect(admin_url('inspections/inspection/' . $id));
    }

    public function table_items()
    {
        $this->app->get_table_data(module_views_path('inspections', 'admin/tables/table_items'));
    }

    public function table_items_submitted()
    {
        $this->app->get_table_data(module_views_path('inspections', 'admin/tables/table_items_submitted'));
    }

    public function table_related($inspection_id='')
    {
        $this->app->get_table_data(module_views_path('inspections', 'admin/tables/table_related'));
    }

    public function add_inspection_item()
    {
        if ($this->input->post() && $this->input->is_ajax_request()) {
            $this->inspections_model->inspection_add_inspection_item($this->input->post());
        }
    }


    public function remove_inspection_item()
    {
        if ($this->input->post() && $this->input->is_ajax_request()) {
            $this->inspections_model->inspection_remove_inspection_item($this->input->post());
        }
    }


    public function update_inspection_data(){
        if ($this->input->post() && $this->input->is_ajax_request()) {

            $jenis_pesawat = $this->input->post('jenis_pesawat');
            $task_id = $this->input->post('task_id');
            $rel_id = $this->input->post('rel_id');
            $field = $this->input->post('field');

            //log_activity(json_encode($this->input->post()));

            $equipment_type = ucfirst(strtolower(str_replace(' ', '_', $jenis_pesawat)));
            $equipment_model = $equipment_type .'_model';
            $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';

            include_once($model_path);
            $this->load->model($equipment_model);
            $this->{$equipment_model}->update($this->input->post(), $rel_id, $task_id);
            if($field == 'nama_pesawat'){
                $this->inspections_model->update_equipment_name($this->input->post(), $rel_id, $task_id);
            }
        }
    }

    public function change_inspection_status(){

        //if ($this->input->post() && $this->input->is_ajax_request()) {
            $input = $this->input->post();
            //log_activity(json_encode($input));

            //$this->inspections_model->inspection_remove_inspection_item($this->input->post());
        //}
    }


    /* Change client status / active / inactive */
    public function inspection_status_change($params, $status)
    {

        list($data['rel_id'], $data['task_id'], $data['jenis_pesawat'], $data['pengujian']) = explode("-",$params);

        //log_activity(json_encode($params));

        $equipment_model = $data['jenis_pesawat'] .'_model';
        $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';

        include_once($model_path);
        $this->load->model($equipment_model);

        //log_activity($status);

        //if ($this->input->is_ajax_request()) {
            $this->{$equipment_model}->update_pengujian_status($data, $status);
        //}
    }


    public function item_pengujian_data(){
        $jenis_pesawat = $this->input->post('jenis_pesawat');
        $task_id = $this->input->post('task_id');
        $rel_id = $this->input->post('rel_id');

        $equipment_type = ucfirst(strtolower(str_replace(' ', '_', $jenis_pesawat)));
        $equipment_model = $equipment_type .'_model';
        $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';

        include_once($model_path);
        $this->load->model($equipment_model);

        if ($this->input->post() && $this->input->is_ajax_request()) {
            $this->{$equipment_model}->update_pengujian_data($this->input->post());
        }
    }

    /**
     * Upload task attachment
     * @since  Version 1.0.1
     */
    public function upload_file()
    {
        if ($this->input->post()) {
            $task_id  = $this->input->post('task_id');
            $rel_id  = $this->input->post('rel_id');

            $files   = handle_inspection_attachments_array($rel_id, 'file');
            $success = false;

            if ($files) {
                $i   = 0;
                $len = count($files);
                foreach ($files as $file) {
                    $success = $this->inspections_model->add_attachment_to_database($rel_id, $task_id, [$file], false, ($i == $len - 1 ? true : false));
                    $i++;
                }
            }

            echo json_encode([
                'success'  => $success,
                //'taskHtml' => $this->get_task_data($task_id, true),
            ]);
        }
    }

    /**
     * Remove inspection attachment
     * @since  Version 1.0.1
     * @param  mixed $id attachment it
     * @return json
     */
    public function remove_inspection_attachment($id)
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->inspections_model->remove_inspection_attachment($id));
        }
    }


    public function download_files($inspection_id, $task_id, $comment_id = null)
    {
        $inspectionWhere = 'external IS NULL';

        if ($comment_id) {
            $inspectionWhere .= ' AND inspection_comment_id=' . $this->db->escape_str($comment_id);
        }

        if (!has_permission('inspections', '', 'view')) {
            $inspectionWhere .= ' AND ' . get_inspections_where_string(false);
        }

        $files = $this->inspections_model->get_inspection_documentation($inspection_id, $task_id, $inspectionWhere);

        if (count($files) == 0) {
            redirect($_SERVER['HTTP_REFERER']);
        }

        $path = INSPECTION_ATTACHMENTS_FOLDER . get_upload_path_by_type('inspection') . $inspection_id;

        $this->load->library('zip');

        foreach ($files as $file) {
            $this->zip->read_file($path . '/' . $file['file_name']);
        }

        $this->zip->download('inspection-'. $inspection_id .'-'.$task_id .'.zip');
        $this->zip->clear_data();
    }


}
