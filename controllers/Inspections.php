<?php

use app\services\inspections\InspectionsPipeline;

defined('BASEPATH') or exit('No direct script access allowed');

class Inspections extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('inspections_model');
        $this->load->model('clients_model');
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
    public function inspection($id)
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

        $data['activity']          = $this->inspections_model->get_inspection_activity($id);
        $data['inspection']          = $inspection;
        $data['members']           = $this->staff_model->get('', ['active' => 1]);
        $data['inspection_statuses'] = $this->inspections_model->get_statuses();
        $data['totalNotes']        = total_rows(db_prefix() . 'notes', ['rel_id' => $id, 'rel_type' => 'inspection']);




        $tags = get_tags_in($inspection->id, 'inspection');

        $equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tags[0])));
        $equipment_model = $equipment_type .'_model';
        $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';

        include_once($model_path);
        $this->load->model($equipment_model);
        $equipment = $this->{$equipment_model}->get('', ['rel_id' => $inspection->id]);
        $data['equipment'] = $equipment;

        $data['send_later'] = false;
        if ($this->session->has_userdata('send_later')) {
            $data['send_later'] = true;
            $this->session->unset_userdata('send_later');
        }

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('inspections', 'admin/tables/small_table'));
        }

        $this->load->view('admin/inspections/inspection_preview', $data);
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

            $tags = get_tags_in($inspection->id, 'inspection');

            $data['jenis_pesawat'] = $tags[0];

            $equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tags[0])));
            $equipment_model = $equipment_type .'_model';
            $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';

            include_once($model_path);
            $this->load->model($equipment_model);
            $equipment = $this->{$equipment_model}->get('', ['rel_id' => $inspection->id]);
            $data['equipment'] = $equipment;

            $data['edit']     = true;
            $title            = _l('edit', _l('inspection_lowercase'));
       

        if ($this->input->get('customer_id')) {
            $data['customer_id'] = $this->input->get('customer_id');
        }


        $data['inspection_members']  = $this->inspections_model->get_inspection_members($id);
        
        $data['staff']             = $this->staff_model->get('', ['active' => 1]);
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

}