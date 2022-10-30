<?php defined('BASEPATH') or exit('No direct script access allowed');

class Myinspection extends ClientsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('inspections_model');
        $this->load->model('clients_model');
        $this->load->model('tasks_model');
    }

    /* Get all inspections in case user go on index page */
    public function list($id = '')
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(module_views_path('inspections', 'admin/tables/table'));
        }
        $contact_id = get_contact_user_id();
        $user_id = get_user_id_by_contact_id($contact_id);
        $client = $this->clients_model->get($user_id);
        $data['inspections'] = $this->inspections_model->get_client_inspections($client);
        $data['inspectionid']            = $id;
        $data['title']                 = _l('inspections_tracking');

        $data['bodyclass'] = 'inspections';
        $this->data($data);
        $this->view('themes/'. active_clients_theme() .'/views/inspections/inspections');
        $this->layout();
    }

    public function sticker($id, $task_id, $hash)
    {
        check_inspection_restrictions($id, $hash);
        $inspection = $this->inspections_model->get($id);

        if (!is_client_logged_in()) {
            load_client_language($inspection->clientid);
        }

        $identity_confirmation_enabled = get_option('inspection_accept_identity_confirmation');

        if ($this->input->post('inspection_action')) {
            $action = $this->input->post('inspection_action');

            // Only decline and accept allowed
            if ($action == 4 || $action == 3) {
                $success = $this->inspections_model->mark_action_status($action, $id, true);

                $redURL   = $this->uri->uri_string();
                $accepted = false;

                if (is_array($success)) {
                    if ($action == 4) {
                        $accepted = true;
                        set_alert('success', _l('clients_inspection_accepted_not_invoiced'));
                    } else {
                        set_alert('success', _l('clients_inspection_declined'));
                    }
                } else {
                    set_alert('warning', _l('clients_inspection_failed_action'));
                }
                if ($action == 4 && $accepted = true) {
                    process_digital_signature_image($this->input->post('signature', false), INSPECTION_ATTACHMENTS_FOLDER . $id);

                    $this->db->where('id', $id);
                    $this->db->update(db_prefix() . 'inspections', get_acceptance_info_array());
                }
            }
            redirect($redURL);
        }
        // Handle Inspection PDF generator

        $inspection_number = format_inspection_item_number($inspection->id, $task_id);
      /*
        $tags = get_tags_in($inspection->id, 'inspection');

        $equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tags[0])));
        $equipment_model = $equipment_type .'_model';
        $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';

        include_once($model_path);
        $this->load->model($equipment_model);
        $equipment = $this->{$equipment_model}->get('', ['rel_id' => $inspection->id]);
        $data['equipment'] = $equipment[0];
        $equipment_name = isset($equipment[0]['nama_pesawat']) ? $equipment[0]['nama_pesawat'] : '';
        */

        $tags = get_tags_in($task_id, 'task');
        
        $equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tags[0])));
        $inspection->equipment_type = $equipment_type;
        $inspection->tag_id = get_tag_by_name($tags[0])->id;

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
        $inspection->task_id = $task_id;

        $data['inspection']          = $inspection;
        $data['equipment']          = reset($equipment);
        $data['equipment_name']          = $data['equipment']['nama_pesawat'];
        $data['title'] = $inspection_number;
        $data['inspection_number']              = $inspection_number;
        $data['hash']                          = $hash;
        $data['can_be_accepted']               = false;
        $data['inspection']                     = hooks()->apply_filters('inspection_html_pdf_data', $inspection);
        $data['bodyclass']                     = 'viewinspection';
        $data['client_company']                = $this->clients_model->get($inspection->clientid)->company;
        
        //$data['equipment_name']                          = $equipment_name;
        $setSize = get_option('inspection_qrcode_size');

        $data['identity_confirmation_enabled'] = $identity_confirmation_enabled;
        if ($identity_confirmation_enabled == '1') {
            $data['bodyclass'] .= ' identity-confirmation';
        }
        $data['inspection_members']  = $this->inspections_model->get_inspection_members($inspection->id,true);
        $qrcode_data  = '';
        $qrcode_data .= _l('inspection_number') . ' : ' . $inspection_number ."\r\n";
        $qrcode_data .= _l('inspection_date') . ' : ' . $inspection->date ."\r\n";
        $qrcode_data .= _l('inspection_equipment_nama_pesawat') . ' : ' . $data['equipment']['nama_pesawat'] ."\r\n";
        $qrcode_data .= _l('inspection_assigned_string') . ' : ' . get_staff_full_name($inspection->assigned) ."\r\n";
        //$qrcode_data .= _l('inspection_company') . ' : ' . get_option('invoice_company_name') ."\r\n";
        

        $inspection_path = get_upload_path_by_type('inspections') . $inspection->id . '/';
        _maybe_create_upload_path('uploads/inspections');
        _maybe_create_upload_path('uploads/inspections/'.$inspection_path);
        
        $params['data'] = $qrcode_data;
        $params['writer'] = 'png';
        $params['setSize'] = isset($setSize) ? $setSize : 160;
        
        $params['encoding'] = 'UTF-8';
        $params['setMargin'] = 0;
        $params['setForegroundColor'] = ['r'=>0,'g'=>0,'b'=>0];
        $params['setBackgroundColor'] = ['r'=>255,'g'=>255,'b'=>255];

        $params['crateLogo'] = false;
        $params['logo'] = './uploads/company/favicon.png';
        $params['setResizeToWidth'] = 60;

        $params['crateLabel'] = false;
        $params['label'] = $inspection_number;
        $params['setTextColor'] = ['r'=>255,'g'=>0,'b'=>0];
        $params['ErrorCorrectionLevel'] = 'medium';

        $params['saveToFile'] = FCPATH.'uploads/inspections/'.$inspection_path .'assigned-'.$inspection_number.'.'.$params['writer'];

        $this->load->library('endroid_qrcode');
        $this->endroid_qrcode->generate($params);


        $this->data($data);
        $this->disableNavigation();
        $this->disableSubMenu();
        $this->app_scripts->theme('sticky-js', 'assets/plugins/sticky/sticky.js');
        $this->view('themes/'. active_clients_theme() .'/views/inspections/inspection_sticker_html');
        add_views_tracking('inspection', $id);
        hooks()->do_action('inspection_html_viewed', $id);
        no_index_customers_area();
        $this->layout();
    }

    public function bapr($id, $hash)
    {
        $task_id = NULL;
        check_inspection_restrictions($id, $hash);
        $inspection = $this->inspections_model->get($id);
        $inspection->task_id = $task_id;
        $task = $this->tasks_model->get($task_id);

        if (!is_client_logged_in()) {
            load_client_language($inspection->clientid);
        }

        $identity_confirmation_enabled = get_option('inspection_accept_identity_confirmation');

        if ($this->input->post('inspection_action')) {
            $action = $this->input->post('inspection_action');

            // Only decline and accept allowed
            if ($action == 4 || $action == 3) {
                $success = $this->inspections_model->mark_action_status($action, $id, true);

                $redURL   = $this->uri->uri_string();
                $accepted = false;

                if (is_array($success)) {
                    if ($action == 4) {
                        $accepted = true;
                        set_alert('success', _l('clients_inspection_accepted_not_invoiced'));
                    } else {
                        set_alert('success', _l('clients_inspection_declined'));
                    }
                } else {
                    set_alert('warning', _l('clients_inspection_failed_action'));
                }
                if ($action == 4 && $accepted = true) {
                    process_digital_signature_image($this->input->post('signature', false), INSPECTION_ATTACHMENTS_FOLDER . $id);

                    $this->db->where('id', $id);
                    $this->db->update(db_prefix() . 'inspections', get_acceptance_info_array());
                }
            }
            redirect($redURL);
        }
        // Handle Inspection PDF generator

        //$inspection_number = format_inspection_number($inspection->id);
        $inspection_number = format_inspection_number($id, $task_id);


        $data['title'] = $inspection_number;
        $this->disableNavigation();
        $this->disableSubMenu();

        $data['inspection_number']              = $inspection_number;
        $data['hash']                          = $hash;
        $data['can_be_accepted']               = false;

        //$tags = get_tags_in($task_id, 'task');
        //$equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tags[0])));
        //$inspection->equipment_type = $equipment_type;
            
        $data['inspection']                     = hooks()->apply_filters('inspection_html_pdf_data', $inspection);
        $data['task']                           = $task;

        $data['inspection_items'] = $this->inspections_model->get_inspection_items($inspection->id, $inspection->project_id, $task_id);

        //get_option('tag_id_'.$tag['tag_id']);
        $tag_id = get_available_tags($task_id);        
        $data['_tag_id'] = get_option('tag_id_'.$tag_id['0']['tag_id']);
        $data['bodyclass']                     = 'viewinspection';
        $data['client_company']                = $this->clients_model->get($inspection->clientid)->company;
        $setSize = get_option('inspection_qrcode_size');

        $data['identity_confirmation_enabled'] = $identity_confirmation_enabled;
        if ($identity_confirmation_enabled == '1') {
            $data['bodyclass'] .= ' identity-confirmation';
        }
        $data['inspection_members']  = $this->inspections_model->get_inspection_members($inspection->id,true);
        $tag_id = get_available_tags($task_id);
        $inspection->categories = get_option('tag_id_'.$tag_id['0']['tag_id']);
        $inspection->assigned_name = get_staff_full_name($inspection->assigned);

        $qrcode_data  = '';
        $qrcode_data .= _l('inspection_number') . ' : ' . $inspection_number ."\r\n";
        $qrcode_data .= _l('inspection_date') . ' : ' . $inspection->date ."\r\n";
        $qrcode_data .= _l('inspection_operation_manager') . ' : ' . $inspection->assigned_name ."\r\n";
        
        $inspection_path = get_upload_path_by_type('inspections') . $inspection->id . '/';
        _maybe_create_upload_path('uploads/inspections');
        _maybe_create_upload_path('uploads/inspections/'.$inspection_path);

        $params['data'] = $qrcode_data;
        $params['writer'] = 'png';
        $params['setSize'] = isset($setSize) ? $setSize : 160;
        $params['encoding'] = 'UTF-8';
        $params['setMargin'] = 0;
        $params['setForegroundColor'] = ['r'=>0,'g'=>0,'b'=>0];
        $params['setBackgroundColor'] = ['r'=>255,'g'=>255,'b'=>255];

        $params['crateLogo'] = true;
        $params['logo'] = './uploads/company/favicon.png';
        $params['setResizeToWidth'] = 60;

        $params['crateLabel'] = false;
        $params['label'] = $inspection_number;
        $params['setTextColor'] = ['r'=>255,'g'=>0,'b'=>0];
        $params['ErrorCorrectionLevel'] = 'hight';

        $params['saveToFile'] = FCPATH.'uploads/inspections/'.$inspection_path .'assigned-'.$inspection_number.'.'.$params['writer'];

        $this->load->library('endroid_qrcode');
        $this->endroid_qrcode->generate($params);

        $this->data($data);
        $this->app_scripts->theme('sticky-js', 'assets/plugins/sticky/sticky.js');
        $this->view('themes/'. active_clients_theme() .'/views/inspections/inspection_bapr_html');
        add_views_tracking('inspection', $id);
        hooks()->do_action('inspection_html_viewed', $id);
        no_index_customers_area();
        $this->layout();
    }

    public function bapr_item($id, $task_id, $hash)
    {
        //check_inspection_restrictions($id, $hash);
        $inspection = $this->inspections_model->get($id);
        $inspection->task_id = $task_id;
        $task = $this->tasks_model->get($task_id);

        if (!is_client_logged_in()) {
            load_client_language($inspection->clientid);
        }


        $identity_confirmation_enabled = get_option('inspection_accept_identity_confirmation');

        if ($this->input->post('inspection_action')) {
            $action = $this->input->post('inspection_action');

            // Only decline and accept allowed
            if ($action == 4 || $action == 3) {
                $success = $this->inspections_model->mark_action_status($action, $id, true);

                $redURL   = $this->uri->uri_string();
                $accepted = false;

                if (is_array($success)) {
                    if ($action == 4) {
                        $accepted = true;
                        set_alert('success', _l('clients_inspection_accepted_not_invoiced'));
                    } else {
                        set_alert('success', _l('clients_inspection_declined'));
                    }
                } else {
                    set_alert('warning', _l('clients_inspection_failed_action'));
                }
                if ($action == 4 && $accepted = true) {
                    process_digital_signature_image($this->input->post('signature', false), INSPECTION_ATTACHMENTS_FOLDER . $id);

                    $this->db->where('id', $id);
                    $this->db->update(db_prefix() . 'inspections', get_acceptance_info_array());
                }
            }
            redirect($redURL);
        }
        // Handle Inspection PDF generator

        //$inspection_number = format_inspection_number($inspection->id);
        $inspection_item_number = format_inspection_item_number($id, $task_id);


        $data['title'] = $inspection_item_number;
        $this->disableNavigation();
        $this->disableSubMenu();

        $data['inspection_number']              = $inspection_item_number;
        $data['hash']                          = $hash;
        $data['can_be_accepted']               = false;

        //$tags = get_tags_in($task_id, 'task');
        //$equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tags[0])));
        //$inspection->equipment_type = $equipment_type;
        
        $tags = get_tags_in($inspection->task_id,'task');
        $data['jenis_pesawat'] = $tags[0];

        $equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tags[0])));
        $inspection->equipment_type = $equipment_type;
            
        $equipment_model = $equipment_type .'_model';
        $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';

        include_once($model_path);
        $this->load->model($equipment_model);
        $equipment = $this->{$equipment_model}->get('', ['rel_id' => $inspection->id, 'task_id' =>$task_id]);
        
        if(empty($equipment && $inspection->status == '1')){
            set_alert('danger', _l('file_not_found ;', $equipment_model));
            log_activity('Data '. $equipment_model . ' empty, and status draft');
            redirect(admin_url('inspections/inspection_item/'.$id.'/'.$task_id));
        }

        $inspection->equipment = $equipment;
        $data['equipment'] = $equipment[0];
        var_dump($equipment);
        
        $data['equipment_name']          = $data['equipment']['nama_pesawat'];
        
        $data['inspection']                     = hooks()->apply_filters('inspection_html_pdf_data', $inspection);
        $data['task']                           = $task;
        //get_option('tag_id_'.$tag['tag_id']);
        $tag_id = get_available_tags($task_id);        
        $data['_tag_id'] = get_option('tag_id_'.$tag_id['0']['tag_id']);
        $data['bodyclass']                     = 'viewinspection';
        $data['client_company']                = $this->clients_model->get($inspection->clientid)->company;
        $setSize = get_option('inspection_qrcode_size');

        $data['identity_confirmation_enabled'] = $identity_confirmation_enabled;
        if ($identity_confirmation_enabled == '1') {
            $data['bodyclass'] .= ' identity-confirmation';
        }
        $data['inspection_members']  = $this->inspections_model->get_inspection_members($inspection->id,true);
        $tag_id = get_available_tags($task_id);
        $inspection->categories = get_option('tag_id_'.$tag_id['0']['tag_id']);
        $inspection->assigned_item = get_staff_full_name(get_option('default_inspection_assigned_'.$inspection->categories));


        $qrcode_data  = '';
        $qrcode_data .= _l('inspection_number') . ' : ' . $inspection_item_number ."\r\n";
        $qrcode_data .= _l('inspection_date') . ' : ' . $inspection->date ."\r\n";
        $qrcode_data .= _l('inspection_assigned_string') . ' : ' . $inspection->assigned_item ."\r\n";
        
        $inspection_path = get_upload_path_by_type('inspections') . $inspection->id . '/';
        _maybe_create_upload_path('uploads/inspections');
        _maybe_create_upload_path('uploads/inspections/'.$inspection_path);

        $params['data'] = $qrcode_data;
        $params['writer'] = 'png';
        $params['setSize'] = isset($setSize) ? $setSize : 160;
        $params['encoding'] = 'UTF-8';
        $params['setMargin'] = 0;
        $params['setForegroundColor'] = ['r'=>0,'g'=>0,'b'=>0];
        $params['setBackgroundColor'] = ['r'=>255,'g'=>255,'b'=>255];

        $params['crateLogo'] = true;
        $params['logo'] = './uploads/company/favicon.png';
        $params['setResizeToWidth'] = 60;

        $params['crateLabel'] = false;
        $params['label'] = $inspection_item_number;
        $params['setTextColor'] = ['r'=>255,'g'=>0,'b'=>0];
        $params['ErrorCorrectionLevel'] = 'hight';

        $params['saveToFile'] = FCPATH.'uploads/inspections/'.$inspection_path .'assigned-'.$inspection_item_number.'.'.$params['writer'];

        $this->load->library('endroid_qrcode');
        $this->endroid_qrcode->generate($params);

        $this->data($data);
        $this->app_scripts->theme('sticky-js', 'assets/plugins/sticky/sticky.js');
        

        if (is_client_logged_in() || is_staff_logged_in() || is_admin()) {
            $this->view('themes/'. active_clients_theme() .'/views/inspections/inspection_bapr_item_html');
        }else{
            $this->view('themes/'. active_clients_theme() .'/views/inspections/inspection_bapr_item_anonymouse_html');
        }

        add_views_tracking('inspection', $id);
        hooks()->do_action('inspection_html_viewed', $id);
        no_index_customers_area();
        $this->layout();
    }

    /* Generates inspection PDF and senting to email  */
    public function pdf($id)
    {
        $canView = user_can_view_inspection($id);
        if (!$canView) {
            access_denied('Inspections');
        } else {
            if (!has_permission('inspections', '', 'view') && !has_permission('inspections', '', 'view_own') && $canView == false) {
                access_denied('Inspections');
            }
        }
        if (!$id) {
            redirect(admin_url('inspections'));
        }
        $inspection        = $this->inspections_model->get($id);
        $inspection_number = format_inspection_number($inspection->id);
        
        $inspection->assigned_path = get_inspection_upload_path('inspection').$inspection->id.'/assigned-'.$inspection_number.'.png';
        $inspection->acceptance_path = get_inspection_upload_path('inspection').$inspection->id .'/'.$inspection->signature;
        
        $inspection->client_company = $this->clients_model->get($inspection->clientid)->company;
        $inspection->acceptance_date_string = _dt($inspection->acceptance_date);

        $inspection->inspection_items = $this->inspections_model->get_inspection_items($inspection->id, $inspection->project_id);

        try {
            $pdf = inspection_pdf($inspection);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $type = 'D';

        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }

        if ($this->input->get('print')) {
            $type = 'I';
        }

        $fileNameHookData = hooks()->apply_filters('inspection_file_name_admin_area', [
                            'file_name' => mb_strtoupper(slug_it($inspection_number)) . '.pdf',
                            'inspection'  => $inspection,
                        ]);

        $pdf->Output($fileNameHookData['file_name'], $type);
    }


    /* Generates inspection PDF and senting to email  */
    public function pdf_all($id)
    {
        $canView = user_can_view_inspection($id);
        if (!$canView) {
            access_denied('Inspections');
        } else {
            if (!has_permission('inspections', '', 'view') && !has_permission('inspections', '', 'view_own') && $canView == false) {
                access_denied('Inspections');
            }
        }
        if (!$id) {
            redirect(admin_url('inspections'));
        }
        $inspection        = $this->inspections_model->get($id);
        $inspection_number = format_inspection_number($inspection->id);
        
        $inspection->assigned_path = get_inspection_upload_path('inspection').$inspection->id.'/assigned-'.$inspection_number.'.png';
        $inspection->acceptance_path = get_inspection_upload_path('inspection').$inspection->id .'/'.$inspection->signature;
        
        $inspection->client_company = $this->clients_model->get($inspection->clientid)->company;
        $inspection->acceptance_date_string = _dt($inspection->acceptance_date);

        $inspection->inspection_items = $this->inspections_model->get_inspection_items($inspection->id, $inspection->project_id);
        
        try {
            $pdf = inspection_pdf_all($inspection);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $type = 'D';

        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }

        if ($this->input->get('print')) {
            $type = 'I';
        }

        $fileNameHookData = hooks()->apply_filters('inspection_file_name_admin_area', [
                            'file_name' => mb_strtoupper(slug_it($inspection_number)) . '-ALL-BAPR.pdf',
                            'inspection'  => $inspection,
                        ]);

        $pdf->Output($fileNameHookData['file_name'], $type);
    }


    public function item_pdf($id, $task_id)
    {
        $canView = user_can_view_inspection($id);
        if (!$canView) {
            access_denied('Inspections');
        } else {
            if (!has_permission('inspections', '', 'view') && !has_permission('inspections', '', 'view_own') && $canView == false) {
                access_denied('Inspections');
            }
        }

        $inspection        = $this->inspections_model->get($id);
        $inspection_item_number = format_inspection_item_number($id, $task_id);

        $inspection->inspection_item_number = $inspection_item_number;
        $inspection->task_id = $task_id;
        $task = $this->tasks_model->get($task_id);
        $inspection->task = $task;

        $inspection->assigned_path = get_inspection_upload_path('inspection').$inspection->id.'/assigned-'.$inspection_item_number.'.png';
        $inspection->acceptance_path = get_inspection_upload_path('inspection').$inspection->id .'/'.$inspection->signature;
        
        $inspection->client_company = $this->clients_model->get($inspection->clientid)->company;
        $inspection->acceptance_date_string = _dt($inspection->acceptance_date);

        $tags = get_tags_in($task_id, 'task');

        $data['jenis_pesawat'] = $tags[0];
        $inspection->tag = $tags[0];

        $tag_id = get_available_tags($task_id);
        $inspection->categories = get_option('tag_id_'.$tag_id['0']['tag_id']);
        $inspection->assigned_item = get_staff_full_name(get_option('default_inspection_assigned_'.$inspection->categories));
        $equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tags[0])));
        $inspection->equipment_type = $equipment_type;
       
        $equipment_model = $equipment_type .'_model';
        $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';
        
        include_once($model_path);
        $this->load->model($equipment_model);
        $equipment = $this->{$equipment_model}->get('', ['rel_id' => $id, 'task_id' => $task_id]);
        
        if (!$equipment) {
            set_alert('danger', _l('record not found ;', $equipment_model));
            redirect(admin_url('inspections/inspection_item/'.$id.'/'.$task_id));
        }
        $inspection->equipment = $equipment[0];
        try {
            $pdf = inspection_item_pdf($inspection);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $type = 'D';

        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }

        if ($this->input->get('print')) {
            $type = 'I';
        }

        $fileNameHookData = hooks()->apply_filters('inspection_file_name_admin_area', [
                            'file_name' => mb_strtoupper(slug_it($inspection_item_number)) . '.pdf',
                            'inspection'  => $inspection,
                        ]);

        $pdf->Output($fileNameHookData['file_name'], $type);
    }

    public function raw_pdf($id, $task_id)
    {
        $canView = user_can_view_inspection($id);
        if (!$canView) {
            access_denied('Inspections');
        } else {
            if (!has_permission('inspections', '', 'view') && !has_permission('inspections', '', 'view_own') && $canView == false) {
                access_denied('Inspections');
            }
        }
        if (!$id) {
            redirect(admin_url('inspections'));
        }
        $inspection        = $this->inspections_model->get($id);
        $inspection_item_number = format_inspection_item_number($id, $task_id);

        $inspection->task_id = $task_id;
        $task = $this->tasks_model->get($task_id);
        $inspection->task = $task;

        $tag_id = get_available_tags($task_id);
        $inspection->categories = get_option('tag_id_'.$tag_id['0']['tag_id']);
        $inspection->assigned_item = get_staff_full_name(get_option('default_inspection_assigned_'.$inspection->categories));
        
        $inspection->assigned_path = get_inspection_upload_path('inspection').$inspection->id.'/assigned-'.$inspection_item_number.'.png';
        $inspection->acceptance_path = get_inspection_upload_path('inspection').$inspection->id .'/'.$inspection->signature;
        
        $inspection->client_company = $this->clients_model->get($inspection->clientid)->company;
        $inspection->acceptance_date_string = _dt($inspection->acceptance_date);

        $tags = get_tags_in($task_id, 'task');

        $data['jenis_pesawat'] = $tags[0];
        $inspection->tag = $tags[0];

        $equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tags[0])));
        $inspection->equipment_type = $equipment_type;
        $inspection->tag_id = get_tag_by_name($tags[0])->id;
       
        $equipment_model = $equipment_type .'_model';
        $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';
        
        include_once($model_path);
        $this->load->model($equipment_model);
        $equipment = $this->{$equipment_model}->get('', ['rel_id' => $inspection->id]);
        
        if (!$equipment) {
            set_alert('danger', _l('record not found ;', $equipment_model));
            
            redirect(admin_url('inspections/inspection_item/'.$id.'/'.$task_id));
        }
        
        $inspection->equipment = $equipment[0];
        
        try {
            $pdf = inspection_raw_pdf($inspection);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $type = 'D';

        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }

        if ($this->input->get('print')) {
            $type = 'I';
        }

        $fileNameHookData = hooks()->apply_filters('inspection_file_name_admin_area', [
                            'file_name' => mb_strtoupper(slug_it($inspection_item_number)) . '.pdf',
                            'inspection'  => $inspection,
                        ]);

        $pdf->Output($fileNameHookData['file_name'], $type);
    }



    public function sticker_data($id, $task_id)
    {
        $canView = user_can_view_inspection($id);
        if (!$canView) {
            access_denied('Inspections');
        } else {
            if (!has_permission('inspections', '', 'view') && !has_permission('inspections', '', 'view_own') && $canView == false) {
                access_denied('Inspections');
            }
        }
        if (!$id) {
            redirect(admin_url('inspections'));
        }

        app_pdf('sticker-data', module_libs_path(INSPECTIONS_MODULE_NAME) . 'pdf/Sticker_data_pdf', $id, $task_id);
    }

    public function sticker_data_all($id)
    {
        $canView = user_can_view_inspection($id);
        if (!$canView) {
            access_denied('Inspections');
        } else {
            if (!has_permission('inspections', '', 'view') && !has_permission('inspections', '', 'view_own') && $canView == false) {
                access_denied('Inspections');
            }
        }
        if (!$id) {
            redirect(admin_url('inspections'));
        }

        app_pdf('sticker-data-all', module_libs_path(INSPECTIONS_MODULE_NAME) . 'pdf/Sticker_data_pdf_all', $id);
    }
    
}
