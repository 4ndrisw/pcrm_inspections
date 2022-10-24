<?php

defined('BASEPATH') or exit('No direct script access allowed');

hooks()->add_action('app_admin_head', 'inspections_head_component');
//hooks()->add_action('app_admin_footer', 'inspections_footer_js__component');
hooks()->add_action('admin_init', 'inspections_settings_tab');

/**
 * Get Inspection short_url
 * @since  Version 2.7.3
 * @param  object $inspection
 * @return string Url
 */
function get_inspection_shortlink($inspection)
{
    $long_url = site_url("inspection/{$inspection->id}/{$inspection->hash}");
    if (!get_option('bitly_access_token')) {
        return $long_url;
    }

    // Check if inspection has short link, if yes return short link
    if (!empty($inspection->short_link)) {
        return $inspection->short_link;
    }

    // Create short link and return the newly created short link
    $short_link = app_generate_short_link([
        'long_url'  => $long_url,
        'title'     => format_inspection_number($inspection->id)
    ]);

    if ($short_link) {
        $CI = &get_instance();
        $CI->db->where('id', $inspection->id);
        $CI->db->update(db_prefix() . 'inspections', [
            'short_link' => $short_link
        ]);
        return $short_link;
    }
    return $long_url;
}

/**
 * Check inspection restrictions - hash, clientid
 * @param  mixed $id   inspection id
 * @param  string $hash inspection hash
 */
function check_inspection_restrictions($id, $hash)
{
    $CI = &get_instance();
    $CI->load->model('inspections_model');
    if (!$hash || !$id) {
        show_404();
    }
    if (!is_client_logged_in() && !is_staff_logged_in()) {
        if (get_option('view_inspection_only_logged_in') == 1) {
            redirect_after_login_to_current_url();
            redirect(site_url('authentication/login'));
        }
    }
    $inspection = $CI->inspections_model->get($id);
    if (!$inspection || ($inspection->hash != $hash)) {
        show_404();
    }
    // Do one more check
    if (!is_staff_logged_in()) {
        if (get_option('view_inspection_only_logged_in') == 1) {
            if ($inspection->clientid != get_client_user_id()) {
                show_404();
            }
        }
    }
}

/**
 * Check if inspection email template for expiry reminders is enabled
 * @return boolean
 */
function is_inspections_email_expiry_reminder_enabled()
{
    return total_rows(db_prefix() . 'emailtemplates', ['slug' => 'inspection-expiry-reminder', 'active' => 1]) > 0;
}

/**
 * Check if there are sources for sending inspection expiry reminders
 * Will be either email or SMS
 * @return boolean
 */
function is_inspections_expiry_reminders_enabled()
{
    return is_inspections_email_expiry_reminder_enabled() || is_sms_trigger_active(SMS_TRIGGER_INSPECTION_EXP_REMINDER);
}

/**
 * Return RGBa inspection status color for PDF documents
 * @param  mixed $status_id current inspection status
 * @return string
 */
function inspection_status_color_pdf($status_id)
{
    if ($status_id == 1) {
        $statusColor = '119, 119, 119';
    } elseif ($status_id == 2) {
        // Sent
        $statusColor = '3, 169, 244';
    } elseif ($status_id == 3) {
        //Declines
        $statusColor = '252, 45, 66';
    } elseif ($status_id == 4) {
        //Accepted
        $statusColor = '0, 191, 54';
    } else {
        // Expired
        $statusColor = '255, 111, 0';
    }

    return hooks()->apply_filters('inspection_status_pdf_color', $statusColor, $status_id);
}

/**
 * Format inspection status
 * @param  integer  $status
 * @param  string  $classes additional classes
 * @param  boolean $label   To include in html label or not
 * @return mixed
 */
function format_inspection_status($status, $classes = '', $label = true)
{
    $id          = $status;
    $label_class = inspection_status_color_class($status);
    $status      = inspection_status_by_id($status);
    if ($label == true) {
        return '<span class="label label-' . $label_class . ' ' . $classes . ' s-status inspection-status-' . $id . ' inspection-status-' . $label_class . '">' . $status . '</span>';
    }

    return $status;
}

/**
 * Return inspection status translated by passed status id
 * @param  mixed $id inspection status id
 * @return string
 */
function inspection_status_by_id($id)
{
    $status = '';
    if ($id == 1) {
        $status = _l('inspection_status_draft');
    } elseif ($id == 2) {
        $status = _l('inspection_status_sent');
    } elseif ($id == 3) {
        $status = _l('inspection_status_declined');
    } elseif ($id == 4) {
        $status = _l('inspection_status_accepted');
    } elseif ($id == 5) {
        // status 5
        $status = _l('inspection_status_expired');
    } else {
        if (!is_numeric($id)) {
            if ($id == 'not_sent') {
                $status = _l('not_sent_indicator');
            }
        }
    }

    return hooks()->apply_filters('inspection_status_label', $status, $id);
}

/**
 * Return inspection status color class based on twitter bootstrap
 * @param  mixed  $id
 * @param  boolean $replace_default_by_muted
 * @return string
 */
function inspection_status_color_class($id, $replace_default_by_muted = false)
{
    $class = '';
    if ($id == 1) {
        $class = 'default';
        if ($replace_default_by_muted == true) {
            $class = 'muted';
        }
    } elseif ($id == 2) {
        $class = 'info';
    } elseif ($id == 3) {
        $class = 'danger';
    } elseif ($id == 4) {
        $class = 'success';
    } elseif ($id == 5) {
        // status 5
        $class = 'warning';
    } else {
        if (!is_numeric($id)) {
            if ($id == 'not_sent') {
                $class = 'default';
                if ($replace_default_by_muted == true) {
                    $class = 'muted';
                }
            }
        }
    }

    return hooks()->apply_filters('inspection_status_color_class', $class, $id);
}

/**
 * Check if the inspection id is last invoice
 * @param  mixed  $id inspectionid
 * @return boolean
 */
function is_last_inspection($id)
{
    $CI = &get_instance();
    $CI->db->select('id')->from(db_prefix() . 'inspections')->order_by('id', 'desc')->limit(1);
    $query            = $CI->db->get();
    $last_inspection_id = $query->row()->id;
    if ($last_inspection_id == $id) {
        return true;
    }

    return false;
}

/**
 * Format inspection number based on description
 * @param  mixed $id
 * @return string
 */
function format_inspection_number($id)
{
    $CI = &get_instance();
    $CI->db->select('date,number,prefix,number_format')->from(db_prefix() . 'inspections')->where('id', $id);
    $inspection = $CI->db->get()->row();

    if (!$inspection) {
        return '';
    }

    $number = inspection_number_format($inspection->number, $inspection->number_format, $inspection->prefix, $inspection->date);

    return hooks()->apply_filters('format_inspection_number', $number, [
        'id'       => $id,
        'inspection' => $inspection,
    ]);
}

/**
 * Format inspection number based on description
 * @param  mixed $id
 * @return string
 */
function format_inspection_item_number($id, $task_id)
{
    $CI = &get_instance();
    $CI->db->select('date,number,prefix,number_format')->from(db_prefix() . 'inspections')->where('id', $id);
    $inspection = $CI->db->get()->row();

    if (!$inspection) {
        return '';
    }

    $number = inspection_number_format($inspection->number, $inspection->number_format, $inspection->prefix, $inspection->date);

    return hooks()->apply_filters('format_inspection_number', $number .'-'. $task_id, [
        'id'       => $id,
        'inspection' => $inspection,
    ]);
}

function inspection_number_format($number, $format, $applied_prefix, $date)
{
    $originalNumber = $number;
    $prefixPadding  = get_option('number_padding_prefixes');

    if ($format == 1) {
        // Number based
        $number = $applied_prefix . str_pad($number, $prefixPadding, '0', STR_PAD_LEFT);
    } elseif ($format == 2) {
        // Year based
        $number = $applied_prefix . date('Y', strtotime($date)) . '.' . str_pad($number, $prefixPadding, '0', STR_PAD_LEFT);
    } elseif ($format == 3) {
        // Number-yy based
        $number = $applied_prefix . str_pad($number, $prefixPadding, '0', STR_PAD_LEFT) . '-' . date('y', strtotime($date));
    } elseif ($format == 4) {
        // Number-mm-yyyy based
        $number = $applied_prefix . str_pad($number, $prefixPadding, '0', STR_PAD_LEFT) . '.' . date('m', strtotime($date)) . '.' . date('Y', strtotime($date));
    }

    return hooks()->apply_filters('inspection_number_format', $number, [
        'format'         => $format,
        'date'           => $date,
        'number'         => $originalNumber,
        'prefix_padding' => $prefixPadding,
    ]);
}

/**
 * Calculate inspections percent by status
 * @param  mixed $status          inspection status
 * @return array
 */
function get_inspections_percent_by_status($status, $project_id = null)
{
    $has_permission_view = has_permission('inspections', '', 'view');
    $where               = '';

    if (isset($project_id)) {
        $where .= 'project_id=' . get_instance()->db->escape_str($project_id) . ' AND ';
    }
    if (!$has_permission_view) {
        $where .= get_inspections_where_sql_for_staff(get_staff_user_id());
    }

    $where = trim($where);

    if (endsWith($where, ' AND')) {
        $where = substr_replace($where, '', -3);
    }

    $total_inspections = total_rows(db_prefix() . 'inspections', $where);

    $data            = [];
    $total_by_status = 0;

    if (!is_numeric($status)) {
        if ($status == 'not_sent') {
            $total_by_status = total_rows(db_prefix() . 'inspections', 'sent=0 AND status NOT IN(2,3,4)' . ($where != '' ? ' AND (' . $where . ')' : ''));
        }
    } else {
        $whereByStatus = 'status=' . $status;
        if ($where != '') {
            $whereByStatus .= ' AND (' . $where . ')';
        }
        $total_by_status = total_rows(db_prefix() . 'inspections', $whereByStatus);
    }

    $percent                 = ($total_inspections > 0 ? number_format(($total_by_status * 100) / $total_inspections, 2) : 0);
    $data['total_by_status'] = $total_by_status;
    $data['percent']         = $percent;
    $data['total']           = $total_inspections;

    return $data;
}

function get_inspections_where_sql_for_staff($staff_id)
{
    $CI = &get_instance();
    $has_permission_view_own             = has_permission('inspections', '', 'view_own');
    $allow_staff_view_inspections_assigned = get_option('allow_staff_view_inspections_assigned');
    $whereUser                           = '';
    if ($has_permission_view_own) {
        $whereUser = '((' . db_prefix() . 'inspections.addedfrom=' . $CI->db->escape_str($staff_id) . ' AND ' . db_prefix() . 'inspections.addedfrom IN (SELECT staff_id FROM ' . db_prefix() . 'staff_permissions WHERE feature = "inspections" AND capability="view_own"))';
        if ($allow_staff_view_inspections_assigned == 1) {
            $whereUser .= ' OR assigned=' . $CI->db->escape_str($staff_id);
        }
        $whereUser .= ')';
    } else {
        $whereUser .= 'assigned=' . $CI->db->escape_str($staff_id);
    }

    return $whereUser;
}
/**
 * Check if staff member have assigned inspections / added as sale agent
 * @param  mixed $staff_id staff id to check
 * @return boolean
 */
function staff_has_assigned_inspections($staff_id = '')
{
    $CI       = &get_instance();
    $staff_id = is_numeric($staff_id) ? $staff_id : get_staff_user_id();
    $cache    = $CI->app_object_cache->get('staff-total-assigned-inspections-' . $staff_id);

    if (is_numeric($cache)) {
        $result = $cache;
    } else {
        $result = total_rows(db_prefix() . 'inspections', ['assigned' => $staff_id]);
        $CI->app_object_cache->add('staff-total-assigned-inspections-' . $staff_id, $result);
    }

    return $result > 0 ? true : false;
}
/**
 * Check if staff member can view inspection
 * @param  mixed $id inspection id
 * @param  mixed $staff_id
 * @return boolean
 */
function user_can_view_inspection($id, $staff_id = false)
{
    $CI = &get_instance();

    $staff_id = $staff_id ? $staff_id : get_staff_user_id();

    if (has_permission('inspections', $staff_id, 'view')) {
        return true;
    }

    if(is_client_logged_in()){

        $CI = &get_instance();
        $CI->load->model('inspections_model');
       
        $inspection = $CI->inspections_model->get($id);
        if (!$inspection) {
            show_404();
        }
        // Do one more check
        if (get_option('view_inspectiont_only_logged_in') == 1) {
            if ($inspection->clientid != get_client_user_id()) {
                show_404();
            }
        }
    
        return true;
    }
    
    $CI->db->select('id, addedfrom, assigned');
    $CI->db->from(db_prefix() . 'inspections');
    $CI->db->where('id', $id);
    $inspection = $CI->db->get()->row();

    if ((has_permission('inspections', $staff_id, 'view_own') && $inspection->addedfrom == $staff_id)
        || ($inspection->assigned == $staff_id && get_option('allow_staff_view_inspections_assigned') == '1')
    ) {
        return true;
    }

    return false;
}


/**
 * Prepare general inspection pdf
 * @since  Version 1.0.2
 * @param  object $inspection inspection as object with all necessary fields
 * @param  string $tag tag for bulk pdf exporter
 * @return mixed object
 */
function inspection_pdf($inspection, $tag = '')
{
    return app_pdf('inspection',  module_libs_path(INSPECTIONS_MODULE_NAME) . 'pdf/Inspection_pdf', $inspection, $tag);
}


/**
 * Prepare general inspection pdf
 * @since  Version 1.0.2
 * @param  object $inspection inspection as object with all necessary fields
 * @param  string $tag tag for bulk pdf exporter
 * @return mixed object
 */
function inspection_pdf_all($inspection, $tag = '')
{
    return app_pdf('inspection',  module_libs_path(INSPECTIONS_MODULE_NAME) . 'pdf/Inspection_pdf_all', $inspection, $tag);
}


/**
 * Prepare general inspection pdf
 * @since  Version 1.0.2
 * @param  object $inspection inspection as object with all necessary fields
 * @param  string $tag tag for bulk pdf exporter
 * @return mixed object
 */
function inspection_item_pdf($inspection, $tag = '')
{
    return app_pdf('inspection',  module_libs_path(INSPECTIONS_MODULE_NAME) . 'pdf/Inspection_item_pdf', $inspection, $tag);
}


/**
 * Prepare general inspection pdf
 * @since  Version 1.0.2
 * @param  object $inspection inspection as object with all necessary fields
 * @param  string $tag tag for bulk pdf exporter
 * @return mixed object
 */
function inspection_raw_pdf($inspection, $tag = '')
{
    return app_pdf('inspection',  module_libs_path(INSPECTIONS_MODULE_NAME) . 'pdf/Inspection_raw_pdf', $inspection, $tag);
}


/**
 * Add new item do database, used for proposals,estimates,credit notes,invoices
 * This is repetitive action, that's why this function exists
 * @param array $item     item from $_POST
 * @param mixed $rel_id   relation id eq. invoice id
 * @param string $rel_type relation type eq invoice
 */
function add_new_inspection_item_post($item, $rel_id, $rel_type)
{

    $CI = &get_instance();

    $CI->db->insert(db_prefix() . 'itemable', [
                    'description'      => $item['description'],
                    'long_description' => nl2br($item['long_description']),
                    'qty'              => $item['qty'],
                    'rel_id'           => $rel_id,
                    'rel_type'         => $rel_type,
                    'item_order'       => $item['order'],
                    'unit'             => isset($item['unit']) ? $item['unit'] : 'unit',
                ]);

    $id = $CI->db->insert_id();

    return $id;
}

/**
 * Update inspection item from $_POST 
 * @param  mixed $item_id item id to update
 * @param  array $data    item $_POST data
 * @param  string $field   field is require to be passed for long_description,rate,item_order to do some additional checkings
 * @return boolean
 */
function update_inspection_item_post($item_id, $data, $field = '')
{
    $update = [];
    if ($field !== '') {
        if ($field == 'long_description') {
            $update[$field] = nl2br($data[$field]);
        } elseif ($field == 'rate') {
            $update[$field] = number_format($data[$field], get_decimal_places(), '.', '');
        } elseif ($field == 'item_order') {
            $update[$field] = $data['order'];
        } else {
            $update[$field] = $data[$field];
        }
    } else {
        $update = [
            'item_order'       => $data['order'],
            'description'      => $data['description'],
            'long_description' => nl2br($data['long_description']),
            'qty'              => $data['qty'],
            'unit'             => $data['unit'],
        ];
    }

    $CI = &get_instance();
    $CI->db->where('id', $item_id);
    $CI->db->update(db_prefix() . 'itemable', $update);

    return $CI->db->affected_rows() > 0 ? true : false;
}


/**
 * Prepares email template preview $data for the view
 * @param  string $template    template class name
 * @param  mixed $customer_id_or_email customer ID to fetch the primary contact email or email
 * @return array
 */
function inspection_mail_preview_data($template, $customer_id_or_email, $mailClassParams = [])
{
    $CI = &get_instance();

    if (is_numeric($customer_id_or_email)) {
        $contact = $CI->clients_model->get_contact(get_primary_contact_user_id($customer_id_or_email));
        $email   = $contact ? $contact->email : '';
    } else {
        $email = $customer_id_or_email;
    }

    $CI->load->model('emails_model');

    $data['template'] = $CI->app_mail_template->prepare($email, $template);
    $slug             = $CI->app_mail_template->get_default_property_value('slug', $template, $mailClassParams);

    $data['template_name'] = $slug;

    $template_result = $CI->emails_model->get(['slug' => $slug, 'language' => 'english'], 'row');

    $data['template_system_name'] = $template_result->name;
    $data['template_id']          = $template_result->emailtemplateid;

    $data['template_disabled'] = $template_result->active == 0;

    return $data;
}


/**
 * Function that return full path for upload based on passed type
 * @param  string $type
 * @return string
 */
function get_inspection_upload_path($type=NULL)
{
   $type = 'inspection';
   $path = INSPECTION_ATTACHMENTS_FOLDER;
   
    return hooks()->apply_filters('get_upload_path_by_type', $path, $type);
}




/**
 * Injects theme CSS
 * @return null
 */
function inspections_head_component()
{
    $CI = &get_instance();
    if (($CI->uri->segment(1) == 'admin' && $CI->uri->segment(2) == 'inspections') ||
        $CI->uri->segment(1) == 'inspections'){
        echo '<link href="' . base_url('modules/inspections/assets/css/inspections.css') . '"  rel="stylesheet" type="text/css" >';
    }
}


/**
 * Remove and format some common used data for the inspection feature eq invoice,inspections etc..
 * @param  array $data $_POST data
 * @return array
 */
function _format_data_inspection_feature($data)
{
    foreach (_get_inspection_feature_unused_names() as $u) {
        if (isset($data['data'][$u])) {
            unset($data['data'][$u]);
        }
    }

    if (isset($data['data']['date'])) {
        $data['data']['date'] = to_sql_date($data['data']['date']);
    }

    if (isset($data['data']['open_till'])) {
        $data['data']['open_till'] = to_sql_date($data['data']['open_till']);
    }

    if (isset($data['data']['expirydate'])) {
        $data['data']['expirydate'] = to_sql_date($data['data']['expirydate']);
    }

    if (isset($data['data']['duedate'])) {
        $data['data']['duedate'] = to_sql_date($data['data']['duedate']);
    }

    if (isset($data['data']['clientnote'])) {
        $data['data']['clientnote'] = nl2br_save_html($data['data']['clientnote']);
    }

    if (isset($data['data']['terms'])) {
        $data['data']['terms'] = nl2br_save_html($data['data']['terms']);
    }

    if (isset($data['data']['adminnote'])) {
        $data['data']['adminnote'] = nl2br($data['data']['adminnote']);
    }

    foreach (['country', 'billing_country', 'shipping_country', 'project_id', 'assigned'] as $should_be_zero) {
        if (isset($data['data'][$should_be_zero]) && $data['data'][$should_be_zero] == '') {
            $data['data'][$should_be_zero] = 0;
        }
    }

    return $data;
}


/**
 * Unsed $_POST request names, mostly they are used as helper inputs in the form
 * The top function will check all of them and unset from the $data
 * @return array
 */
function _get_inspection_feature_unused_names()
{
    return [
        'taxname', 'description',
        'currency_symbol', 'price',
        'isedit', 'taxid',
        'long_description', 'unit',
        'rate', 'quantity',
        'item_select', 'tax',
        'billed_tasks', 'billed_expenses',
        'task_select', 'task_id',
        'expense_id', 'repeat_every_custom',
        'repeat_type_custom', 'bill_expenses',
        'save_and_send', 'merge_current_invoice',
        'cancel_merged_invoices', 'invoices_to_merge',
        'tags', 's_prefix', 'save_and_record_payment',
    ];
}

/**
 * When item is removed eq from invoice will be stored in removed_items in $_POST
 * With foreach loop this function will remove the item from database and it's taxes
 * @param  mixed $id       item id to remove
 * @param  string $rel_type item relation eq. invoice, estimate
 * @return boolena
 */
function handle_removed_inspection_item_post($id, $rel_type)
{
    $CI = &get_instance();

    $CI->db->where('id', $id);
    $CI->db->where('rel_type', $rel_type);
    $CI->db->delete(db_prefix() . 'itemable');
    if ($CI->db->affected_rows() > 0) {
        return true;
    }

    return false;
}


/**
 * Injects theme CSS
 * @return null
 */
function inspection_head_component()
{
}

$CI = &get_instance();
// Check if inspection is excecuted
if ($CI->uri->segment(1)=='inspections') {
    hooks()->add_action('app_customers_head', 'inspection_app_client_includes');
}

/**
 * Theme clients footer includes
 * @return stylesheet
 */
function inspection_app_client_includes()
{
    echo '<link href="' . base_url('modules/' .INSPECTIONS_MODULE_NAME. '/assets/css/inspections.css') . '"  rel="stylesheet" type="text/css" >';
    echo '<script src="' . module_dir_url('' .INSPECTIONS_MODULE_NAME. '', 'assets/js/inspections.js') . '"></script>';
}


function inspection_create_assigned_qrcode_hook($id){
     
     log_activity( 'Hello, world!' );

}

function inspection_status_changed_hook($data){

    log_activity('inspection_status_changed');

}

function get_inspection_company_name($id){
    $CI = &get_instance();

    $CI->load->model('inspections_model');   
    $inspection = $CI->inspections_model->get($id);

    $CI->load->model('clients_model');   
    $client = $CI->clients_model->get($inspection->clientid);
    return $client->company;
}

function get_inspection_company_by_clientid($id){
    $CI = &get_instance();
 
    $CI->load->model('clients_model');   
    $client = $CI->clients_model->get($id);
    return $client->company;
}

function get_inspection_company_address($id){
    $CI = &get_instance();
    $CI->db->select('billing_street, billing_city, billing_state','billing_zip');
    $CI->db->from(db_prefix() . 'inspections');
    $CI->db->where('id', $id);
    $inspection = $CI->db->get()->row();

    $address  = '';
    $address .= isset($inspection->billing_street) ? $inspection->billing_street .' ' : '' ;
    $address .= isset($inspection->billing_city) ? $inspection->billing_city .' ' : '' ;
    $address .= isset($inspection->billing_state) ? $inspection->billing_state .' ' : '' ;
    $address .= isset($inspection->billing_zip) ? $inspection->billing_zip : '' ;
    
    return $address;
}

function get_inspection_jenis_pesawat(){
    $CI = &get_instance();
    $CI->load->model('clients_model');   
    $client = $CI->clients_model->get($id);
    return $client->company;
}


 /**
 * Format company info/address format
 * @return string
 */
function format_company_info()
{
    $format = get_option('company_info_format');

/*
    $format = _info_format_replace('company_name', '<b style="color:black" class="company-name-formatted">' . get_option('invoice_company_name') . '</b>', $format);
    $format = _info_format_replace('address', get_option('invoice_company_address'), $format);
    $format = _info_format_replace('city', get_option('invoice_company_city'), $format);
    $format = _info_format_replace('state', get_option('company_state'), $format);
    $format = _info_format_replace('zip_code', '', $format);

    $format = _info_format_replace('vat_number_with_label', '', $format);
    $format = _maybe_remove_first_and_last_br_tag($format);
*/ 
    $format = '';
    $format .= get_option('invoice_company_name') . "\r\n";
    $format .= get_option('invoice_company_address');
    $format .= get_option('invoice_company_city');
    $format .= get_option('company_state');
    
    // Remove multiple white spaces
    $format = preg_replace('/\s+/', ' ', $format);
    $format = trim($format);

    return $format;
}

 /**
 * Format company info/address format
 * @return string
 */
function format_unorderedText($text)
{
    $lists = explode('*', trim($text));
    $output = '';
    foreach($lists as $li){
        if(!empty($li)){
            $output .= '<li class="text-item">' . $li . '</li>';            
        }
    }

    return '<ul class="text-items">'.$output.'</ul>';
}


function add_inspection_related($insert_id){

    $CI = &get_instance();
    $CI->load->model('inspections_model');
    
    $inspection = $CI->inspections_model->get($insert_id);

    $items = $CI->inspections_model->get_available_tasks($inspection->id, $inspection->project_id);
    
    foreach($items as $item){
        $item['inspection_id']=$insert_id;
        
        $CI->db->insert(db_prefix().'inspection_items', $item);
        
    }

}


/**
 * Task attachments upload array
 * Multiple task attachments can be upload if input type is array or dropzone plugin is used
 * @param  mixed $taskid     task id
 * @param  string $index_name attachments index, in different forms different index name is used
 * @return mixed
 */
function handle_inspection_attachments_array($rel_id, $index_name = 'attachments')
{
    $uploaded_files = [];
    $path           = INSPECTION_ATTACHMENTS_FOLDER . get_upload_path_by_type('inspections') . $rel_id . '/';
    $CI             = &get_instance();
    
    if (isset($_FILES[$index_name]['name'])
        && ($_FILES[$index_name]['name'] != '' || is_array($_FILES[$index_name]['name']) && count($_FILES[$index_name]['name']) > 0)) {
        if (!is_array($_FILES[$index_name]['name'])) {
            $_FILES[$index_name]['name']     = [$_FILES[$index_name]['name']];
            $_FILES[$index_name]['type']     = [$_FILES[$index_name]['type']];
            $_FILES[$index_name]['tmp_name'] = [$_FILES[$index_name]['tmp_name']];
            $_FILES[$index_name]['error']    = [$_FILES[$index_name]['error']];
            $_FILES[$index_name]['size']     = [$_FILES[$index_name]['size']];
        }

        _file_attachments_index_fix($index_name);
        for ($i = 0; $i < count($_FILES[$index_name]['name']); $i++) {
            // Get the temp file path
            $tmpFilePath = $_FILES[$index_name]['tmp_name'][$i];

            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                if (_perfex_upload_error($_FILES[$index_name]['error'][$i])
                    || !_upload_extension_allowed($_FILES[$index_name]['name'][$i])) {
                    continue;
                }

                _maybe_create_upload_path($path);
                $filename    = unique_filename($path, $_FILES[$index_name]['name'][$i]);
                $newFilePath = $path . $filename;

                // Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    array_push($uploaded_files, [
                        'file_name' => $filename,
                        'filetype'  => $_FILES[$index_name]['type'][$i],
                    ]);

                    if (is_image($newFilePath)) {
                        create_img_thumb($path, $filename);
                    }
                }
            }
        }
    }

    if (count($uploaded_files) > 0) {
        return $uploaded_files;
    }

    return false;
}

function tanggal_pemeriksaan($date){
    $tanggal_inspeksi_raw = isset($date) ? _d($date) : '1970-01-01';
    $tahun = getYear($tanggal_inspeksi_raw);
    $bulan = getMonth($tanggal_inspeksi_raw);
    $tanggal = getDay($tanggal_inspeksi_raw);
    $tanggal_pemeriksaan = $tanggal.' '.$bulan.' '.$tahun;
    return $tanggal_pemeriksaan;
}


function get_available_tags($task_id=NULL){
    $CI = &get_instance();

    $CI->db->select([db_prefix() . 'tags.id AS tag_id', db_prefix() . 'tags.name AS tag_name']);
    $CI->db->select(['COUNT('.db_prefix() . 'tasks.id) AS count_task']);
    
    $CI->db->join(db_prefix() . 'taggables', db_prefix() . 'taggables.rel_id = ' . db_prefix() . 'tasks.id', 'left');
    $CI->db->join(db_prefix() . 'tags', db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id', 'left');
    $CI->db->group_by(db_prefix() . 'tags.id');
    $CI->db->where(db_prefix() . 'tasks.rel_type = ' . "'project'");
    if(is_numeric($task_id)){
        $CI->db->where(db_prefix() . 'tasks.id = ' . $task_id);
    }
    $CI->db->where(db_prefix() . 'tags.id is NOT NULL', NULL, true);

    //return $this->db->get_compiled_select(db_prefix() . 'tasks');
    return $CI->db->get(db_prefix() . 'tasks')->result_array();

}


function inspection_data($inspection, $task_id){
    $_data = [];

    foreach ($inspection->equipment as $key => $value) {
        $_data[$key] = $value;
    }

    $data = isset($_data[0]) ? $_data[0] : $_data;

    //$licence = get_licence_id_from_spection_item($task_id);

    //$data['licence_id'] = $licence_id;
    $data['pjk3'] = get_option('invoice_company_name');
    $data['nama_perusahaan'] = isset($inspection->client) ? $inspection->client->company : $inspection['client']->company;
    $data['alamat_perusahaan'] = $inspection->billing_street .' '. $inspection->billing_city .' '. $inspection->billing_state .' '. $inspection->billing_zip;
    $data['tanggal_pemeriksaan'] = tanggal_pemeriksaan($inspection->date);
    $data['kelompok_pemeriksaan'] = $inspection->categories;
    $data['nomor_inspeksi'] = $inspection->formatted_number;
    $data['nomor_inspeksi_alat'] = format_inspection_item_number($inspection->id, $task_id);

    unset($data['id'],$data['rel_id'],$data['pemeriksaan_dokumen'],$data['pemeriksaan_visual'],$data['pemeriksaan_pengaman'],
          $data['pengujian_beban'] ,$data['pengujian_penetrant'],$data['pengujian_operasional'], $data['pengujian_thickness'] ,$data['kesimpulan'],$data['temuan']
    );

    $default_regulation = get_option('predefined_regulation_of_'.$inspection->categories);
    $equipment_regulasi = !empty($data['regulasi']) ? $data['regulasi'] : $default_regulation;

    if (!empty($equipment_regulasi)) {
        $regulasi = explode(' -- ', $equipment_regulasi);
        $equipment_regulasi = '';
        $i = 1;
        foreach($regulasi as $row){
            $equipment_regulasi .= $i .'. ' .$row. "<br />"; 
            $i++;
        }
    }

    $data['regulasi'] = $equipment_regulasi;

    return $data;
}
