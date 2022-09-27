<?php

use app\services\AbstractKanban;
use app\services\inspections\InspectionsPipeline;

defined('BASEPATH') or exit('No direct script access allowed');

class Inspections_model extends App_Model
{
    private $statuses;

    private $shipping_fields = ['shipping_street', 'shipping_city', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country'];

    public function __construct()
    {
        parent::__construct();

        $this->statuses = hooks()->apply_filters('before_set_inspection_statuses', [
            1,
            2,
            5,
            3,
            4,
        ]);   
    }
    /**
     * Get unique sale agent for inspections / Used for filters
     * @return array
     */
    public function get_assigneds()
    {
        return $this->db->query("SELECT DISTINCT(assigned) as assigned, CONCAT(firstname, ' ', lastname) as full_name FROM " . db_prefix() . 'inspections JOIN ' . db_prefix() . 'staff on ' . db_prefix() . 'staff.staffid=' . db_prefix() . 'inspections.assigned WHERE assigned != 0')->result_array();
    }

    /**
     * Get inspection/s
     * @param mixed $id inspection id
     * @param array $where perform where
     * @return mixed
     */
    public function get($id = '', $where = [])
    {
//        $this->db->select('*,' . db_prefix() . 'currencies.id as currencyid, ' . db_prefix() . 'inspections.id as id, ' . db_prefix() . 'currencies.name as currency_name');
        $this->db->select('*,' . db_prefix() . 'inspections.id as id');
        $this->db->from(db_prefix() . 'inspections');
        //$this->db->join(db_prefix() . 'currencies', db_prefix() . 'currencies.id = ' . db_prefix() . 'inspections.currency', 'left');
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'inspections.id', $id);
            $inspection = $this->db->get()->row();
            if ($inspection) {
                $inspection->attachments                           = $this->get_attachments($id);
                $inspection->visible_attachments_to_customer_found = false;

                foreach ($inspection->attachments as $attachment) {
                    if ($attachment['visible_to_customer'] == 1) {
                        $inspection->visible_attachments_to_customer_found = true;

                        break;
                    }
                }

                $inspection->client = $this->clients_model->get($inspection->clientid);

                if (!$inspection->client) {
                    $inspection->client          = new stdClass();
                    $inspection->client->company = $inspection->deleted_customer_name;
                }
                $inspection->inspection_items = $this->get_inspection_items($inspection->id, $inspection->project_id);

                $this->load->model('email_schedule_model');
                $inspection->inspectiond_email = $this->email_schedule_model->get($id, 'inspection');
            }

            return $inspection;
        }
        $this->db->order_by('number,YEAR(date)', 'desc');

        return $this->db->get()->result_array();
    }

    /**
     * Get inspection statuses
     * @return array
     */
    public function get_statuses()
    {
        return $this->statuses;
    }


    /**
     * Get inspection statuses
     * @return array
     */
    public function get_status($status,$id)
    {
        $this->db->where('status', $status);
        $this->db->where('id', $id);
        $inspection = $this->db->get(db_prefix() . 'inspections')->row();

        return $this->status;
    }

    public function clear_signature($id)
    {
        $this->db->select('signed','signature','status');
        $this->db->where('id', $id);
        $inspection = $this->db->get(db_prefix() . 'inspections')->row();

        if ($inspection) {
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'inspections', ['signed'=>0,'signature' => null, 'status'=>2]);

            if (!empty($inspection->signature)) {
                unlink(get_upload_path_by_type('inspection') . $id . '/' . $inspection->signature);
            }

            return true;
        }

        return false;
    }


    /**
     * Convert inspection to jobreport
     * @param mixed $id inspection id
     * @return mixed     New jobreport ID
     */
    public function convert_to_jobreport($id, $client = false, $draft_jobreport = false)
    {
        // Recurring jobreport date is okey lets convert it to new jobreport
        $_inspection = $this->get($id);
        $new_jobreport_data = [];
        if ($draft_jobreport == true) {
            $new_jobreport_data['save_as_draft'] = true;
        }
        $new_jobreport_data['clientid']   = $_inspection->clientid;
        $new_jobreport_data['project_id']   = $_inspection->project_id;
        $new_jobreport_data['inspection_id'] = $_inspection->id;
        $new_jobreport_data['number']     = get_option('next_jobreport_number');
        $new_jobreport_data['date']       = _d(date('Y-m-d'));

        $new_jobreport_data['show_quantity_as'] = $_inspection->show_quantity_as;
        //$new_jobreport_data['assigned']       = get_option('default_jobreport_assigned');
        // Since version 1.0.6
        $new_jobreport_data['billing_street']   = clear_textarea_breaks($_inspection->billing_street);
        $new_jobreport_data['billing_city']     = $_inspection->billing_city;
        $new_jobreport_data['billing_state']    = $_inspection->billing_state;
        $new_jobreport_data['billing_zip']      = $_inspection->billing_zip;
        $new_jobreport_data['billing_country']  = $_inspection->billing_country;
        $new_jobreport_data['shipping_street']  = clear_textarea_breaks($_inspection->shipping_street);
        $new_jobreport_data['shipping_city']    = $_inspection->shipping_city;
        $new_jobreport_data['shipping_state']   = $_inspection->shipping_state;
        $new_jobreport_data['shipping_zip']     = $_inspection->shipping_zip;
        $new_jobreport_data['shipping_country'] = $_inspection->shipping_country;

        if ($_inspection->include_shipping == 1) {
            $new_jobreport_data['include_shipping'] = 1;
        }

        $number = get_option('next_jobreport_number');
        $format = get_option('jobreport_number_format');
        $prefix = get_option('jobreport_prefix');
        $date = date('Y-m-d');
        
        $new_jobreport_data['formatted_number'] = jobreport_number_format($number, $format, $prefix, $date);



        $new_jobreport_data['show_shipping_on_jobreport'] = $_inspection->show_shipping_on_inspection;
        $new_jobreport_data['terms']                    = get_option('predefined_terms_jobreport');
        $new_jobreport_data['clientnote']               = get_option('predefined_clientnote_jobreport');
        // Set to unpaid status automatically
        $new_jobreport_data['status']    = 1;
        $new_jobreport_data['adminnote'] = '';

        $custom_fields_items                       = get_custom_fields('items');
       
        include_once(FCPATH . 'modules/jobreports/models/Jobreports_model.php');

        $this->load->model('jobreports_model');

        $id = $this->jobreports_model->add($new_jobreport_data);
        if ($id) {
            // Customer accepted the inspection and is auto converted to jobreport
            if (!is_staff_logged_in()) {
                $this->db->where('rel_type', 'jobreport');
                $this->db->where('rel_id', $id);
                $this->db->delete(db_prefix() . 'inspection_activity');
                $this->jobreports_model->log_jobreport_activity($id, 'jobreport_activity_auto_converted_from_inspection', true, serialize([
                    '<a href="' . admin_url('inspections/inspection/' . $_inspection->id) . '">' . format_inspection_number($_inspection->id) . '</a>',
                ]));
            }
            // For all cases update addefrom and sale agent from the jobreport
            // May happen staff is not logged in and these values to be 0
            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'jobreports', [
                'addedfrom'  => $_inspection->addedfrom,
                'assigned' => get_option('default_jobreport_assigned'),
            ]);

            // Update inspection with the new jobreport data and set to status accepted
            $this->db->where('id', $_inspection->id);
            $this->db->update(db_prefix() . 'inspections', [
                'jobreport_date' => date('Y-m-d H:i:s'),
                'jobreportid'     => $id,
                'status'        => 5,
            ]);


            if ($client == false) {
                $this->log_inspection_activity($_inspection->id, 'inspection_activity_converted', false, serialize([
                    '<a href="' . admin_url('jobreports/jobreport/' . $id) . '">' . format_jobreport_number($id) . '</a>',
                ]));
            }

            hooks()->do_action('inspection_converted_to_jobreport', ['jobreport_id' => $id, 'inspection_id' => $_inspection->id]);
        }

        return $id;
    }

    /**
     * Copy inspection
     * @param mixed $id inspection id to copy
     * @return mixed
     */
    public function copy($id)
    {
        $_inspection                       = $this->get($id);
        $new_inspection_data               = [];
        $new_inspection_data['clientid']   = $_inspection->clientid;
        $new_inspection_data['project_id'] = $_inspection->project_id;
        $new_inspection_data['number']     = get_option('next_inspection_number');
        
        $number = get_option('next_inspection_number');
        $format = get_option('inspection_number_format');
        $prefix = get_option('inspection_prefix');
        $date = date('Y-m-d');
                
        $new_inspection_data['formatted_number'] = inspection_number_format($number, $format, $prefix, $date);


        $new_inspection_data['date']       = _d(date('Y-m-d'));
        $new_inspection_data['expirydate'] = null;

        if ($_inspection->expirydate && get_option('inspection_due_after') != 0) {
            $new_inspection_data['expirydate'] = _d(date('Y-m-d', strtotime('+' . get_option('inspection_due_after') . ' DAY', strtotime(date('Y-m-d')))));
        }

        $new_inspection_data['show_quantity_as'] = $_inspection->show_quantity_as;
    
        $new_inspection_data['terms']            = $_inspection->terms;
        $new_inspection_data['assigned']       = $_inspection->assigned;
        $new_inspection_data['reference_no']     = $_inspection->reference_no;
        // Since version 1.0.6
        $new_inspection_data['billing_street']   = clear_textarea_breaks($_inspection->billing_street);
        $new_inspection_data['billing_city']     = $_inspection->billing_city;
        $new_inspection_data['billing_state']    = $_inspection->billing_state;
        $new_inspection_data['billing_zip']      = $_inspection->billing_zip;
        $new_inspection_data['billing_country']  = $_inspection->billing_country;
        $new_inspection_data['shipping_street']  = clear_textarea_breaks($_inspection->shipping_street);
        $new_inspection_data['shipping_city']    = $_inspection->shipping_city;
        $new_inspection_data['shipping_state']   = $_inspection->shipping_state;
        $new_inspection_data['shipping_zip']     = $_inspection->shipping_zip;
        $new_inspection_data['shipping_country'] = $_inspection->shipping_country;
        if ($_inspection->include_shipping == 1) {
            $new_inspection_data['include_shipping'] = $_inspection->include_shipping;
        }
        $new_inspection_data['show_shipping_on_inspection'] = $_inspection->show_shipping_on_inspection;
        // Set to unpaid status automatically
        $new_inspection_data['status']     = 1;
        $new_inspection_data['clientnote'] = $_inspection->clientnote;
        $new_inspection_data['adminnote']  = '';

        $custom_fields_items             = get_custom_fields('items');
        $id = $this->add($new_inspection_data);
        if ($id) {
            $custom_fields = get_custom_fields('inspection');
            foreach ($custom_fields as $field) {
                $value = get_custom_field_value($_inspection->id, $field['id'], 'inspection', false);
                if ($value == '') {
                    continue;
                }

                $this->db->insert(db_prefix() . 'customfieldsvalues', [
                    'relid'   => $id,
                    'fieldid' => $field['id'],
                    'fieldto' => 'inspection',
                    'value'   => $value,
                ]);
            }

            $tags = get_tags_in($_inspection->id, 'inspection');
            handle_tags_save($tags, $id, 'inspection');

            $this->log_inspection_activity('Copied Inspection ' . format_inspection_number($_inspection->id));

            return $id;
        }

        return false;
    }
    

    /**
     * Performs inspections totals status
     * @param array $data
     * @return array
     */
    public function get_inspections_total($data)
    {
        $statuses            = $this->get_statuses();
        $has_permission_view = has_permission('inspections', '', 'view');
        $this->load->model('currencies_model');
        if (isset($data['currency'])) {
            $currencyid = $data['currency'];
        } elseif (isset($data['customer_id']) && $data['customer_id'] != '') {
            $currencyid = $this->clients_model->get_customer_default_currency($data['customer_id']);
            if ($currencyid == 0) {
                $currencyid = $this->currencies_model->get_base_currency()->id;
            }
        } elseif (isset($data['inspection_id']) && $data['inspection_id'] != '') {
            $this->load->model('inspections_model');
            $currencyid = $this->inspections_model->get_currency($data['inspection_id'])->id;
        } else {
            $currencyid = $this->currencies_model->get_base_currency()->id;
        }

        $currency = get_currency($currencyid);
        $where    = '';
        if (isset($data['customer_id']) && $data['customer_id'] != '') {
            $where = ' AND clientid=' . $data['customer_id'];
        }

        if (isset($data['inspection_id']) && $data['inspection_id'] != '') {
            $where .= ' AND inspection_id=' . $data['inspection_id'];
        }

        if (!$has_permission_view) {
            $where .= ' AND ' . get_inspections_where_sql_for_staff(get_staff_user_id());
        }

        $sql = 'SELECT';
        foreach ($statuses as $inspection_status) {
            $sql .= '(SELECT SUM(total) FROM ' . db_prefix() . 'inspections WHERE status=' . $inspection_status;
            $sql .= ' AND currency =' . $this->db->escape_str($currencyid);
            if (isset($data['years']) && count($data['years']) > 0) {
                $sql .= ' AND YEAR(date) IN (' . implode(', ', array_map(function ($year) {
                    return get_instance()->db->escape_str($year);
                }, $data['years'])) . ')';
            } else {
                $sql .= ' AND YEAR(date) = ' . date('Y');
            }
            $sql .= $where;
            $sql .= ') as "' . $inspection_status . '",';
        }

        $sql     = substr($sql, 0, -1);
        $result  = $this->db->query($sql)->result_array();
        $_result = [];
        $i       = 1;
        foreach ($result as $key => $val) {
            foreach ($val as $status => $total) {
                $_result[$i]['total']         = $total;
                $_result[$i]['symbol']        = $currency->symbol;
                $_result[$i]['currency_name'] = $currency->name;
                $_result[$i]['status']        = $status;
                $i++;
            }
        }
        $_result['currencyid'] = $currencyid;

        return $_result;
    }

    /**
     * Insert new inspection to database
     * @param array $data invoiec data
     * @return mixed - false if not insert, inspection ID if succes
     */
    public function add($data)
    {
        $affectedRows = 0;
        
        $data['datecreated'] = date('Y-m-d H:i:s');

        $data['addedfrom'] = get_staff_user_id();

        $data['prefix'] = get_option('inspection_prefix');

        $data['number_format'] = get_option('inspection_number_format');

        $save_and_send = isset($data['save_and_send']);


        $data['hash'] = app_generate_hash();
        $tags         = isset($data['tags']) ? $data['tags'] : '';
        $equipment    = isset($data['equipment']) ? $data['equipment'] : '';

        $inspection_members = [];
        if (isset($data['inspection_members'])) {
            $inspection_members = $data['inspection_members'];
            unset($data['inspection_members']);
        }

        $data = $this->map_shipping_columns($data);

        $data['billing_street'] = trim($data['billing_street']);
        $data['billing_street'] = nl2br($data['billing_street']);

        if (isset($data['shipping_street'])) {
            $data['shipping_street'] = trim($data['shipping_street']);
            $data['shipping_street'] = nl2br($data['shipping_street']);
        }

        $hook = hooks()->apply_filters('before_inspection_added', [
            'data'  => $data,
            'equipment' => $equipment,
        ]);

        $data  = $hook['data'];
        $equipment = $hook['equipment'];

        unset($data['tags']);
        unset($data['equipment']);
        unset($data['allowed_payment_modes']);
        unset($data['save_as_draft']);
        unset($data['inspection_id']);
        unset($data['duedate']);


        try {
            $this->db->insert(db_prefix() . 'inspections', $data);
        } catch (Exception $e) {
            $message = $e->getMessage();
            log_activity('Insert ERROR ' . $message);
        }

        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            // Update next inspection number in settings
            $this->db->where('name', 'next_inspection_number');
            $this->db->set('value', 'value+1', false);
            $this->db->update(db_prefix() . 'options');

            handle_tags_save($tags, $insert_id, 'inspection');
            /*
            //$tags = $data['tags'];
            $tags = empty($tags) ? [] : explode(',', $tags);
            $tag = $tags[0];
            $equipment = ucfirst(strtolower(str_replace(' ', '_', $tag)));
            $equipment_model = $equipment .'_model';
            include_once(__DIR__ . '/' . $equipment_model .'.php');
            $this->load->model($equipment_model);

            $equipment_data['rel_id'] = $insert_id;
            //$equipment_data['task_id'] = $task_id;
            $this->{$equipment_model}->create($equipment_data);
            */

            $_sm = [];
            if (isset($inspection_members)) {
                $_sm['inspection_members'] = $inspection_members;
            }

            $this->add_edit_inspection_members($_sm, $insert_id);

            hooks()->do_action('after_inspection_added', $insert_id);

            if ($save_and_send === true) {
                $this->send_inspection_to_client($insert_id, '', true, '', true);
            }

            return $insert_id;
        }

        return false;
    }

    /**
     * Update inspection data
     * @param array $data inspection data
     * @param mixed $id inspectionid
     * @return boolean
     */
    public function update($data, $id)
    {
        $affectedRows = 0;

        $data['number'] = trim($data['number']);

        $original_inspection = $this->get($id);

        $original_status = $original_inspection->status;

        $original_number = $original_inspection->number;

        $original_number_formatted = format_inspection_number($id);

        $save_and_send = isset($data['save_and_send']);

        $inspection_members = [];
        if (isset($data['inspection_members'])) {
            $inspection_members = $data['inspection_members'];
            unset($data['inspection_members']);
        }
        
        if (isset($data['tags'])) {
            if (handle_tags_save($data['tags'], $id, 'inspection')) {
                $affectedRows++;
            }
        }
        //$equipment_data = $data['equipment'];
        //unset($data['equipment']);

        $data['billing_street'] = trim($data['billing_street']);
        $data['billing_street'] = nl2br($data['billing_street']);

        $data['shipping_street'] = trim($data['shipping_street']);
        $data['shipping_street'] = nl2br($data['shipping_street']);

        $data = $this->map_shipping_columns($data);

        $hook = hooks()->apply_filters('before_inspection_updated', [
            'data'             => $data,
            'inspection_members' => $inspection_members,
            'removed_items'    => isset($data['removed_items']) ? $data['removed_items'] : [],
        ], $id);

        $data                  = $hook['data'];
        $inspection_members      = $hook['inspection_members'];
        $data['removed_items'] = $hook['removed_items'];

        // Delete items checked to be removed from database
        foreach ($data['removed_items'] as $remove_item_id) {
            $original_item = $this->get_inspection_item($remove_item_id);
            if (handle_removed_inspection_item_post($remove_item_id, 'inspection')) {
                $affectedRows++;
                $this->log_inspection_activity($id, 'inspection_activity_removed_item', false, serialize([
                    $original_item->description,
                ]));
            }
        }

        unset($data['removed_items']);


        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'inspections', $data);

        if ($this->db->affected_rows() > 0) {
            // Check for status change
            if ($original_status != $data['status']) {
                $this->log_inspection_activity($original_inspection->id, 'not_inspection_status_updated', false, serialize([
                    '<original_status>' . $original_status . '</original_status>',
                    '<new_status>' . $data['status'] . '</new_status>',
                ]));
                if ($data['status'] == 2) {
                    $this->db->where('id', $id);
                    $this->db->update(db_prefix() . 'inspections', ['sent' => 1, 'datesend' => date('Y-m-d H:i:s')]);
                }
            }
            if ($original_number != $data['number']) {
                $this->log_inspection_activity($original_inspection->id, 'inspection_activity_number_changed', false, serialize([
                    $original_number_formatted,
                    format_inspection_number($original_inspection->id),
                ]));
            }

            $affectedRows++;
        }
        
        $_sm = [];
        if (isset($inspection_members)) {
            $_sm['inspection_members'] = $inspection_members;
        }
        if ($this->add_edit_inspection_members($_sm, $id)) {
            $affectedRows++;
        }

        
        if ($save_and_send === true) {
            $this->send_inspection_to_client($id, '', true, '', true);
        }

        if ($affectedRows > 0) {
            hooks()->do_action('after_inspection_updated', $id);
            return true;
        }

        return false;
    }

    public function mark_action_status($action, $id, $client = false)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'inspections', [
            'status' => $action,
            'signed' => ($action == 4) ? 1 : 0,
        ]);

        $notifiedUsers = [];

        if ($this->db->affected_rows() > 0) {
            $inspection = $this->get($id);
            if ($client == true) {
                $this->db->where('staffid', $inspection->addedfrom);
                $this->db->or_where('staffid', $inspection->assigned);
                $staff_inspection = $this->db->get(db_prefix() . 'staff')->result_array();

                $invoiceid = false;
                $invoiced  = false;

                $contact_id = !is_client_logged_in()
                    ? get_primary_contact_user_id($inspection->clientid)
                    : get_contact_user_id();

                if ($action == 4) {
                    $this->log_inspection_activity($id, 'inspection_activity_client_accepted', true);

                    // Send thank you email to all contacts with permission inspections
                    $contacts = $this->clients_model->get_contacts($inspection->clientid, ['active' => 1, 'project_emails' => 1]);

                    foreach ($contacts as $contact) {
                        // (To fix merge field) send_mail_template('inspection_accepted_to_customer','inspections', $inspection, $contact);
                    }

                    foreach ($staff_inspection as $member) {
                        $notified = add_notification([
                            'fromcompany'     => true,
                            'touserid'        => $member['staffid'],
                            'description'     => 'inspection_customer_accepted',
                            'link'            => 'inspections/inspection/' . $id,
                            'additional_data' => serialize([
                                format_inspection_number($inspection->id),
                            ]),
                        ]);

                        if ($notified) {
                            array_push($notifiedUsers, $member['staffid']);
                        }

                        // (To fix merge field) send_mail_template('inspection_accepted_to_staff','inspections', $inspection, $member['email'], $contact_id);
                    }

                    pusher_trigger_notification($notifiedUsers);
                    hooks()->do_action('inspection_accepted', $inspection);

                    return true;
                } elseif ($action == 3) {
                    foreach ($staff_inspection as $member) {
                        $notified = add_notification([
                            'fromcompany'     => true,
                            'touserid'        => $member['staffid'],
                            'description'     => 'inspection_customer_declined',
                            'link'            => 'inspections/inspection/' . $id,
                            'additional_data' => serialize([
                                format_inspection_number($inspection->id),
                            ]),
                        ]);

                        if ($notified) {
                            array_push($notifiedUsers, $member['staffid']);
                        }
                        // Send staff email notification that customer declined inspection
                        // (To fix merge field) send_mail_template('inspection_declined_to_staff', 'inspections',$inspection, $member['email'], $contact_id);
                    }
                    pusher_trigger_notification($notifiedUsers);
                    $this->log_inspection_activity($id, 'inspection_activity_client_declined', true);
                    hooks()->do_action('inspection_declined', $inspection);

                    return true;
                }
            } else {
                if ($action == 2) {
                    $this->db->where('id', $id);
                    $this->db->update(db_prefix() . 'inspections', ['sent' => 1, 'datesend' => date('Y-m-d H:i:s')]);
                
                    $this->db->where('active', 1);
                    $staff_inspection = $this->db->get(db_prefix() . 'staff')->result_array();
                    $contacts = $this->clients_model->get_contacts($inspection->clientid, ['active' => 1, 'project_emails' => 1]);
                    
                        foreach ($staff_inspection as $member) {
                            $notified = add_notification([
                                'fromcompany'     => true,
                                'touserid'        => $member['staffid'],
                                'description'     => 'inspection_send_to_customer_already_sent_2',
                                'link'            => 'inspections/inspection/' . $id,
                                'additional_data' => serialize([
                                    format_inspection_number($inspection->id),
                                ]),
                            ]);
                    
                            if ($notified) {
                                array_push($notifiedUsers, $member['staffid']);
                            }
                            // Send staff email notification that customer declined inspection
                            // (To fix merge field) send_mail_template('inspection_declined_to_staff', 'inspections',$inspection, $member['email'], $contact_id);
                        }

                    // Admin marked inspection
                    $this->log_inspection_activity($id, 'inspection_activity_marked', false, serialize([
                        '<status>' . $action . '</status>',
                    ]));

                    /*
                     *
                     * add equipment here
                     * get_inspection_items($inspection_id, $project_id)
                     *
                     *
                     */
                    $inspection_items = $this->get_inspection_items($inspection->id, $inspection->project_id);
                    foreach($inspection_items as $item){
                        $equipment = ucfirst(strtolower(str_replace(' ', '_', $item['tag_name'])));
                        $equipment_model = $equipment .'_model';
                        
                        $tags = get_tags_in($item['task_id'], 'task');
                        $equipment_type = strtolower(str_replace(' ', '_', $tags[0]));

                        include_once(__DIR__ . '/' . $equipment_model .'.php');
                        $this->load->model($equipment_model);

                        $equipment_data['rel_id'] = $inspection->id;
                        $equipment_data['task_id'] = $item['task_id'];
                        $equipment_data['jenis_pesawat'] = $tags[0];
                        $this->{$equipment_model}->create_or_update($equipment_data,$inspection->id, $item['task_id'], $equipment_type);
                    }


                    pusher_trigger_notification($notifiedUsers);
                    hooks()->do_action('inspection_send_to_customer_already_sent', $inspection);
                    


                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get inspection attachments
     * @param mixed $inspection_id
     * @param string $id attachment id
     * @return mixed
     */
    public function get_attachments($inspection_id, $id = '')
    {
        // If is passed id get return only 1 attachment
        if (is_numeric($id)) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('rel_id', $inspection_id);
        }
        $this->db->where('rel_type', 'inspection');
        $result = $this->db->get(db_prefix() . 'files');
        if (is_numeric($id)) {
            return $result->row();
        }

        return $result->result_array();
    }

    /**
     *  Delete inspection attachment
     * @param mixed $id attachmentid
     * @return  boolean
     */
    public function delete_attachment($id)
    {
        $attachment = $this->get_attachments('', $id);
        $deleted    = false;
        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_upload_path_by_type('inspection') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete(db_prefix() . 'files');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                log_inspection_activity('Inspection Attachment Deleted [InspectionID: ' . $attachment->rel_id . ']');
            }

            if (is_dir(get_upload_path_by_type('inspection') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('inspection') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type('inspection') . $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    /**
     * Delete inspection items and all connections
     * @param mixed $id inspectionid
     * @return boolean
     */
    public function delete($id, $simpleDelete = false)
    {
        if (get_option('delete_only_on_last_inspection') == 1 && $simpleDelete == false) {
            if (!is_last_inspection($id)) {
                return false;
            }
        }
        $inspection = $this->get($id);
      
        hooks()->do_action('before_inspection_deleted', $id);

        $number = format_inspection_number($id);

        $this->clear_signature($id);

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'inspections');

        if ($this->db->affected_rows() > 0) {
            if (!is_null($inspection->short_link)) {
                app_archive_short_link($inspection->short_link);
            }

            if (get_option('inspection_number_decrement_on_delete') == 1 && $simpleDelete == false) {
                $current_next_inspection_number = get_option('next_inspection_number');
                if ($current_next_inspection_number > 1) {
                    // Decrement next inspection number to
                    $this->db->where('name', 'next_inspection_number');
                    $this->db->set('value', 'value-1', false);
                    $this->db->update(db_prefix() . 'options');
                }
            }

            delete_tracked_emails($id, 'inspection');

            // Delete the items values
            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'inspection');
            $this->db->delete(db_prefix() . 'notes');

            $this->db->where('rel_type', 'inspection');
            $this->db->where('rel_id', $id);
            $this->db->delete(db_prefix() . 'views_tracking');
            /*
            $tags = get_tags_in($id, 'inspection');          
            $equipment_type = strtolower(str_replace(' ', '_', $tags[0]));
            
            // Delete the record from related equipment table
            $this->db->where('rel_id', $id);
            $this->db->delete(db_prefix() . $equipment_type);
            */

            $this->db->where('inspection_id', $id);
            $this->db->delete(db_prefix() . 'inspection_items');

            $this->db->where('rel_type', 'inspection');
            $this->db->where('rel_id', $id);
            $this->db->delete(db_prefix() . 'taggables');

            $this->db->where('rel_type', 'inspection');
            $this->db->where('rel_id', $id);
            $this->db->delete(db_prefix() . 'reminders');

            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'inspection');
            $this->db->delete(db_prefix() . 'inspection_activity');

            $attachments = $this->get_attachments($id);
            foreach ($attachments as $attachment) {
                $this->delete_attachment($attachment['id']);
            }

            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'inspection');
            $this->db->delete('scheduled_emails');

            // Get related tasks
            $this->db->where('rel_type', 'inspection');
            $this->db->where('rel_id', $id);
            $tasks = $this->db->get(db_prefix() . 'tasks')->result_array();
            foreach ($tasks as $task) {
                $this->tasks_model->delete_task($task['id']);
            }
            if ($simpleDelete == false) {
                $this->log_inspection_activity('Inspections Deleted [Number: ' . $number . ']');
            }

            return true;
        }

        return false;
    }

    /**
     * Set inspection to sent when email is successfuly sended to client
     * @param mixed $id inspectionid
     */
    public function set_inspection_sent($id, $emails_sent = [])
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'inspections', [
            'sent'     => 1,
            'datesend' => date('Y-m-d H:i:s'),
        ]);

        $this->log_inspection_activity($id, 'inspection_activity_sent_to_client', false, serialize([
            '<custom_data>' . implode(', ', $emails_sent) . '</custom_data>',
        ]));

        // Update inspection status to sent
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'inspections', [
            'status' => 2,
        ]);

        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'inspection');
        $this->db->delete('inspectiond_emails');
    }

    /**
     * Send expiration reminder to customer
     * @param mixed $id inspection id
     * @return boolean
     */
    public function send_expiry_reminder($id)
    {
        $inspection        = $this->get($id);
        $inspection_number = format_inspection_number($inspection->id);
        set_mailing_constant();
        $pdf              = inspection_pdf($inspection);
        $attach           = $pdf->Output($inspection_number . '.pdf', 'S');
        $emails_sent      = [];
        $sms_sent         = false;
        $sms_reminder_log = [];

        // For all cases update this to prevent sending multiple reminders eq on fail
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'inspections', [
            'is_expiry_notified' => 1,
        ]);

        $contacts = $this->clients_model->get_contacts($inspection->clientid, ['active' => 1, 'project_emails' => 1]);

        foreach ($contacts as $contact) {
            $template = mail_template('inspection_expiration_reminder', $inspection, $contact);

            $merge_fields = $template->get_merge_fields();

            $template->add_attachment([
                'attachment' => $attach,
                'filename'   => str_replace('/', '-', $inspection_number . '.pdf'),
                'type'       => 'application/pdf',
            ]);

            if ($template->send()) {
                array_push($emails_sent, $contact['email']);
            }

            if (can_send_sms_based_on_creation_date($inspection->datecreated)
                && $this->app_sms->trigger(SMS_TRIGGER_INSPECTION_EXP_REMINDER, $contact['phonenumber'], $merge_fields)) {
                $sms_sent = true;
                array_push($sms_reminder_log, $contact['firstname'] . ' (' . $contact['phonenumber'] . ')');
            }
        }

        if (count($emails_sent) > 0 || $sms_sent) {
            if (count($emails_sent) > 0) {
                $this->log_inspection_activity($id, 'not_expiry_reminder_sent', false, serialize([
                    '<custom_data>' . implode(', ', $emails_sent) . '</custom_data>',
                ]));
            }

            if ($sms_sent) {
                $this->log_inspection_activity($id, 'sms_reminder_sent_to', false, serialize([
                    implode(', ', $sms_reminder_log),
                ]));
            }

            return true;
        }

        return false;
    }

    /**
     * Send inspection to client
     * @param mixed $id inspectionid
     * @param string $template email template to sent
     * @param boolean $attachpdf attach inspection pdf or not
     * @return boolean
     */
    public function send_inspection_to_client($id, $template_name = '', $attachpdf = true, $cc = '', $manually = false)
    {
        $inspection = $this->get($id);

        if ($template_name == '') {
            $template_name = $inspection->sent == 0 ?
                'inspection_send_to_customer' :
                'inspection_send_to_customer_already_sent';
        }

        $inspection_number = format_inspection_number($inspection->id);

        $emails_sent = [];
        $send_to     = [];

        // Manually is used when sending the inspection via add/edit area button Save & Send
        if (!DEFINED('CRON') && $manually === false) {
            $send_to = $this->input->post('sent_to');
        } elseif (isset($GLOBALS['inspectiond_email_contacts'])) {
            $send_to = $GLOBALS['inspectiond_email_contacts'];
        } else {
            $contacts = $this->clients_model->get_contacts(
                $inspection->clientid,
                ['active' => 1, 'project_emails' => 1]
            );

            foreach ($contacts as $contact) {
                array_push($send_to, $contact['id']);
            }
        }

        $status_auto_updated = false;
        $status_now          = $inspection->status;

        if (is_array($send_to) && count($send_to) > 0) {
            $i = 0;

            // Auto update status to sent in case when user sends the inspection is with status draft
            if ($status_now == 1) {
                $this->db->where('id', $inspection->id);
                $this->db->update(db_prefix() . 'inspections', [
                    'status' => 2,
                ]);
                $status_auto_updated = true;
            }

            if ($attachpdf) {
                $_pdf_inspection = $this->get($inspection->id);
                set_mailing_constant();
                $pdf = inspection_pdf($_pdf_inspection);

                $attach = $pdf->Output($inspection_number . '.pdf', 'S');
            }

            foreach ($send_to as $contact_id) {
                if ($contact_id != '') {
                    // Send cc only for the first contact
                    if (!empty($cc) && $i > 0) {
                        $cc = '';
                    }

                    $contact = $this->clients_model->get_contact($contact_id);

                    if (!$contact) {
                        continue;
                    }

                    $template = mail_template($template_name, $inspection, $contact, $cc);

                    if ($attachpdf) {
                        $hook = hooks()->apply_filters('send_inspection_to_customer_file_name', [
                            'file_name' => str_replace('/', '-', $inspection_number . '.pdf'),
                            'inspection'  => $_pdf_inspection,
                        ]);

                        $template->add_attachment([
                            'attachment' => $attach,
                            'filename'   => $hook['file_name'],
                            'type'       => 'application/pdf',
                        ]);
                    }

                    if ($template->send()) {
                        array_push($emails_sent, $contact->email);
                    }
                }
                $i++;
            }
        } else {
            return false;
        }

        if (count($emails_sent) > 0) {
            $this->set_inspection_sent($id, $emails_sent);
            hooks()->do_action('inspection_sent', $id);

            return true;
        }

        if ($status_auto_updated) {
            // Inspection not send to customer but the status was previously updated to sent now we need to revert back to draft
            $this->db->where('id', $inspection->id);
            $this->db->update(db_prefix() . 'inspections', [
                'status' => 1,
            ]);
        }

        return false;
    }

    /**
     * All inspection activity
     * @param mixed $id inspectionid
     * @return array
     */
    public function get_inspection_activity($id)
    {
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'inspection');
        $this->db->order_by('date', 'desc');

        return $this->db->get(db_prefix() . 'inspection_activity')->result_array();
    }

    /**
     * Log inspection activity to database
     * @param mixed $id inspectionid
     * @param string $description activity description
     */
    public function log_inspection_activity($id, $description = '', $client = false, $additional_data = '')
    {
        $staffid   = get_staff_user_id();
        $full_name = get_staff_full_name(get_staff_user_id());
        if (DEFINED('CRON')) {
            $staffid   = '[CRON]';
            $full_name = '[CRON]';
        } elseif ($client == true) {
            $staffid   = null;
            $full_name = '';
        }

        $this->db->insert(db_prefix() . 'inspection_activity', [
            'description'     => $description,
            'date'            => date('Y-m-d H:i:s'),
            'rel_id'          => $id,
            'rel_type'        => 'inspection',
            'staffid'         => $staffid,
            'full_name'       => $full_name,
            'additional_data' => $additional_data,
        ]);
    }

    /**
     * Updates pipeline order when drag and drop
     * @param mixe $data $_POST data
     * @return void
     */
    public function update_pipeline($data)
    {
        $this->mark_action_status($data['status'], $data['inspectionid']);
        AbstractKanban::updateOrder($data['order'], 'pipeline_order', 'inspections', $data['status']);
    }

    /**
     * Get inspection unique year for filtering
     * @return array
     */
    public function get_inspections_years()
    {
        return $this->db->query('SELECT DISTINCT(YEAR(date)) as year FROM ' . db_prefix() . 'inspections ORDER BY year DESC')->result_array();
    }

    private function map_shipping_columns($data)
    {
        if (!isset($data['include_shipping'])) {
            foreach ($this->shipping_fields as $_s_field) {
                if (isset($data[$_s_field])) {
                    $data[$_s_field] = null;
                }
            }
            $data['show_shipping_on_inspection'] = 1;
            $data['include_shipping']          = 0;
        } else {
            $data['include_shipping'] = 1;
            // set by default for the next time to be checked
            if (isset($data['show_shipping_on_inspection']) && ($data['show_shipping_on_inspection'] == 1 || $data['show_shipping_on_inspection'] == 'on')) {
                $data['show_shipping_on_inspection'] = 1;
            } else {
                $data['show_shipping_on_inspection'] = 0;
            }
        }

        return $data;
    }

    public function do_kanban_query($status, $search = '', $page = 1, $sort = [], $count = false)
    {
        _deprecated_function('Inspections_model::do_kanban_query', '2.9.2', 'InspectionsPipeline class');

        $kanBan = (new InspectionsPipeline($status))
            ->search($search)
            ->page($page)
            ->sortBy($sort['sort'] ?? null, $sort['sort_by'] ?? null);

        if ($count) {
            return $kanBan->countAll();
        }

        return $kanBan->get();
    }


    public function get_inspection_members($id, $with_name = false)
    {
        if ($with_name) {
            $this->db->select('firstname,lastname,email,inspection_id,staff_id');
        } else {
            $this->db->select('email,inspection_id,staff_id');
        }
        $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid=' . db_prefix() . 'inspection_members.staff_id');
        $this->db->where('inspection_id', $id);

        return $this->db->get(db_prefix() . 'inspection_members')->result_array();
    }

    public function add_edit_inspection_members($data, $id)
    {
        $affectedRows = 0;
        if (isset($data['inspection_members'])) {
            $inspection_members = $data['inspection_members'];
        }

        $new_inspection_members_to_receive_email = [];
        $this->db->select('id,number,clientid,project_id');
        $this->db->where('id', $id);
        $inspection      = $this->db->get(db_prefix() . 'inspections')->row();
        $inspection_number = format_inspection_number($id);
        $inspection_id    = $id;
        $client_id    = $inspection->clientid;
        $project_id    = $inspection->project_id;

        $inspection_members_in = $this->get_inspection_members($id);
        if (sizeof($inspection_members_in) > 0) {
            foreach ($inspection_members_in as $inspection_member) {
                if (isset($inspection_members)) {
                    if (!in_array($inspection_member['staff_id'], $inspection_members)) {
                        $this->db->where('inspection_id', $id);
                        $this->db->where('staff_id', $inspection_member['staff_id']);
                        $this->db->delete(db_prefix() . 'inspection_members');
                        if ($this->db->affected_rows() > 0) {
                            $this->db->where('staff_id', $inspection_member['staff_id']);
                            $this->db->where('inspection_id', $id);
                            //$this->db->delete(db_prefix() . 'pinned_inspections');

                            $this->log_inspection_activity($id, 'inspection_activity_removed_team_member', get_staff_full_name($inspection_member['staff_id']));
                            $affectedRows++;
                        }
                    }
                } else {
                    $this->db->where('inspection_id', $id);
                    $this->db->delete(db_prefix() . 'inspection_members');
                    if ($this->db->affected_rows() > 0) {
                        $affectedRows++;
                    }
                }
            }
            if (isset($inspection_members)) {
                $notifiedUsers = [];
                foreach ($inspection_members as $staff_id) {
                    $this->db->where('inspection_id', $id);
                    $this->db->where('staff_id', $staff_id);
                    $_exists = $this->db->get(db_prefix() . 'inspection_members')->row();
                    if (!$_exists) {
                        if (empty($staff_id)) {
                            continue;
                        }
                        $this->db->insert(db_prefix() . 'inspection_members', [
                            'inspection_id' => $id,
                            'staff_id'   => $staff_id,
                        ]);
                        if ($this->db->affected_rows() > 0) {
                            if ($staff_id != get_staff_user_id()) {
                                $notified = add_notification([
                                    'fromuserid'      => get_staff_user_id(),
                                    'description'     => 'not_staff_added_as_inspection_member',
                                    'link'            => 'inspections/inspection/' . $id,
                                    'touserid'        => $staff_id,
                                    'additional_data' => serialize([
                                        $inspection_number,
                                    ]),
                                ]);
                                array_push($new_inspection_members_to_receive_email, $staff_id);
                                if ($notified) {
                                    array_push($notifiedUsers, $staff_id);
                                }
                            }

                            $this->log_inspection_activity($id, 'inspection_activity_added_team_member', get_staff_full_name($staff_id));
                            $affectedRows++;
                        }
                    }
                }
                pusher_trigger_notification($notifiedUsers);
            }
        } else {
            if (isset($inspection_members)) {
                $notifiedUsers = [];
                foreach ($inspection_members as $staff_id) {
                    if (empty($staff_id)) {
                        continue;
                    }
                    $this->db->insert(db_prefix() . 'inspection_members', [
                        'inspection_id' => $id,
                        'staff_id'   => $staff_id,
                    ]);
                    if ($this->db->affected_rows() > 0) {
                        if ($staff_id != get_staff_user_id()) {
                            $notified = add_notification([
                                'fromuserid'      => get_staff_user_id(),
                                'description'     => 'not_staff_added_as_inspection_member',
                                'link'            => 'inspections/inspection/' . $id,
                                'touserid'        => $staff_id,
                                'additional_data' => serialize([
                                    $inspection_number,
                                ]),
                            ]);
                            array_push($new_inspection_members_to_receive_email, $staff_id);
                            if ($notifiedUsers) {
                                array_push($notifiedUsers, $staff_id);
                            }
                        }
                        $this->log_inspection_activity($id, 'inspection_activity_added_team_member', get_staff_full_name($staff_id));
                        $affectedRows++;
                    }
                }
                pusher_trigger_notification($notifiedUsers);
            }
        }

        if (count($new_inspection_members_to_receive_email) > 0) {
            $all_members = $this->get_inspection_members($id);
            foreach ($all_members as $data) {
                if (in_array($data['staff_id'], $new_inspection_members_to_receive_email)) {

                try {
                    // init bootstrapping phase
                 
                    //$send = // (To fix merge field) send_mail_template('inspection_staff_added_as_member', $data, $id, $client_id);
                    $this->log_inspection_activity($id, 'inspection_activity_added_team_member', get_staff_full_name($staff_id));
                    $this->log_inspection_activity($id, 'inspection_activity_added_team_member', $client_id);
                    $send = 1;
                    if (!$send)
                    {
                      throw new Exception("Mail not send.");
                    }
                  
                    // continue execution of the bootstrapping phase
                } catch (Exception $e) {
                    echo $e->getMessage();
                        $this->log_inspection_activity($id, 'inspection_activity_added_team_member', get_staff_full_name($staff_id));
                        $this->log_inspection_activity($id, 'inspection_activity_added_team_member', $client_id);
                }

                }
            }
        }
        if ($affectedRows > 0) {
            return true;
        }

        return false;
    }

    /**
     * Update canban inspection status when drag and drop
     * @param  array $data inspection data
     * @return boolean
     */
    public function update_inspection_status($data)
    {
        $this->db->select('status');
        $this->db->where('id', $data['inspectionid']);
        $_old = $this->db->get(db_prefix() . 'inspections')->row();

        $old_status = '';

        if ($_old) {
            $old_status = format_inspection_status($_old->status);
        }

        $affectedRows   = 0;
        $current_status = format_inspection_status($data['status']);


        $this->db->where('id', $data['inspectionid']);
        $this->db->update(db_prefix() . 'inspections', [
            'status' => $data['status'],
        ]);

        $_log_message = '';

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            if ($current_status != $old_status && $old_status != '') {
                $_log_message    = 'not_inspection_activity_status_updated';
                $additional_data = serialize([
                    get_staff_full_name(),
                    $old_status,
                    $current_status,
                ]);

                hooks()->do_action('inspection_status_changed', [
                    'inspection_id'    => $data['inspectionid'],
                    'old_status' => $old_status,
                    'new_status' => $current_status,
                ]);
            }
            $this->db->where('id', $data['inspectionid']);
            $this->db->update(db_prefix() . 'inspections', [
                'last_status_change' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($affectedRows > 0) {
            if ($_log_message == '') {
                return true;
            }
            $this->log_inspection_activity($data['inspectionid'], $_log_message, false, $additional_data);

            return true;
        }

        return false;
    }


    /**
     * Get the inspections about to expired in the given days
     *
     * @param  integer|null $staffId
     * @param  integer $days
     *
     * @return array
     */
    public function get_inspections_this_week($staffId = null, $days = 7)
    {
        $diff1 = date('Y-m-d', strtotime('-' . 0 . ' days'));
        $diff2 = date('Y-m-d', strtotime('+' . $days . ' days'));

        if ($staffId && ! staff_can('view', 'inspections', $staffId)) {
            $this->db->where(db_prefix() . 'inspections.addedfrom', $staffId);
        }

        $this->db->select(db_prefix() . 'inspections.id,' . db_prefix() . 'inspections.number,' . db_prefix() . 'clients.userid,' . db_prefix() . 'clients.company,' . db_prefix() . 'projects.name,' . db_prefix() . 'inspections.date');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.userid = ' . db_prefix() . 'inspections.clientid', 'left');
        $this->db->join(db_prefix() . 'projects', db_prefix() . 'projects.id = ' . db_prefix() . 'inspections.project_id', 'left');
        $this->db->where('date IS NOT NULL');
        $this->db->where('date >=', $diff1);
        $this->db->where('date <=', $diff2);

        return $this->db->get(db_prefix() . 'inspections')->result_array();
    }

    /**
     * Get the inspections for the client given
     *
     * @param  integer|null $staffId
     * @param  integer $days
     *
     * @return array
     */
    public function get_client_inspections($client = null)
    {
        /*
        if ($staffId && ! staff_can('view', 'inspections', $staffId)) {
            $this->db->where('addedfrom', $staffId);
        }
        */

        $this->db->select(db_prefix() . 'inspections.id,' . db_prefix() . 'inspections.number,' . db_prefix() . 'inspections.status,' . db_prefix() . 'clients.userid,' . db_prefix() . 'inspections.hash,' . db_prefix() . 'projects.name,' . db_prefix() . 'inspections.date');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.userid = ' . db_prefix() . 'inspections.clientid', 'left');
        $this->db->join(db_prefix() . 'projects', db_prefix() . 'projects.id = ' . db_prefix() . 'inspections.project_id', 'left');
        $this->db->where('date IS NOT NULL');
        $this->db->where(db_prefix() . 'inspections.status > ',1);
        $this->db->where(db_prefix() . 'inspections.clientid =', $client->userid);

        return $this->db->get(db_prefix() . 'inspections')->result_array();
    }


    /**
     * Get the inspections about to expired in the given days
     *
     * @param  integer|null $staffId
     * @param  integer $days
     *
     * @return array
     */
    public function get_inspections_between($staffId = null, $days = 7)
    {
        $diff1 = date('Y-m-d', strtotime('-' . $days . ' days'));
        $diff2 = date('Y-m-d', strtotime('+' . $days . ' days'));

        if ($staffId && ! staff_can('view', 'inspections', $staffId)) {
            $this->db->where('addedfrom', $staffId);
        }

        $this->db->select(db_prefix() . 'inspections.id,' . db_prefix() . 'inspections.number,' . db_prefix() . 'clients.userid,' . db_prefix() . 'clients.company,' . db_prefix() . 'projects.name,' . db_prefix() . 'inspections.date');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.userid = ' . db_prefix() . 'inspections.clientid', 'left');
        $this->db->join(db_prefix() . 'projects', db_prefix() . 'projects.id = ' . db_prefix() . 'inspections.project_id', 'left');
        $this->db->where('expirydate IS NOT NULL');
        $this->db->where('expirydate >=', $diff1);
        $this->db->where('expirydate <=', $diff2);

        return $this->db->get_compiled_select(db_prefix() . 'inspections');
//        return $this->db->get(db_prefix() . 'inspections')->get_compiled_select();
//        return $this->db->get(db_prefix() . 'inspections')->result_array();
    }

    public function get_available_tasks($inspection_id, $project_id){

        $this->db->select([db_prefix() . 'projects.id AS project_id', db_prefix() . 'tasks.id AS task_id']);

        $this->db->join(db_prefix() . 'projects', db_prefix() . 'tasks.rel_id = ' . db_prefix() . 'projects.id', 'left');
        $this->db->join(db_prefix() . 'inspection_items', db_prefix() . 'tasks.id = ' . db_prefix() . 'inspection_items.task_id', 'left');

        $this->db->where(db_prefix() . 'tasks.rel_id =' . $project_id);
        $this->db->where(db_prefix() . 'tasks.rel_type = ' . "'project'");
        $this->db->where(db_prefix() . 'inspection_items.task_id IS NULL');

        //return $this->db->get_compiled_select(db_prefix() . 'tasks');
        return $this->db->get(db_prefix() . 'tasks')->result_array();
    }

    public function get_inspection_items($inspection_id, $project_id){
        $this->db->select([db_prefix() . 'tasks.id AS task_id',db_prefix() . 'tasks.name', db_prefix() . 'tasks.rel_id', db_prefix() . 'tasks.dateadded', db_prefix() . 'tags.name AS tag_name']);
        $this->db->where(db_prefix() . 'tasks.rel_id =' . $project_id);
        
        $this->db->join(db_prefix() . 'inspection_items', db_prefix() . 'inspection_items.task_id = ' . db_prefix() . 'tasks.id', 'left');
        $this->db->join(db_prefix() . 'taggables', db_prefix() . 'taggables.rel_id = ' . db_prefix() . 'tasks.id', 'left');
        $this->db->join(db_prefix() . 'tags', db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id', 'left');

        $this->db->where(db_prefix() . 'tasks.rel_type = ' . "'project'");
        $this->db->where(db_prefix() . 'inspection_items.inspection_id = ' . $inspection_id);
        $this->db->where(db_prefix() . 'inspection_items.project_id = ' . $project_id);

        //return $this->db->get_compiled_select(db_prefix() . 'tasks');
        return $this->db->get(db_prefix() . 'tasks')->result_array();

    }


    public function update_equipment_name($data, $rel_id, $task_id){
        $field = $data['field'];
        unset($data['field']);
        //$data_text = htmlspecialchars($data['text'], ENT_QUOTES);
        $data_text = strip_tags($data['text'], '<div><p><br>');
        //$data_text = $data['text'];
        unset($data['text']);
        $data[$field] = $data_text;
        $rel_id = $data['rel_id'];
        unset($data['rel_id']);
        
        $jenis_pesawat = $data['jenis_pesawat'];
        unset($data['jenis_pesawat']);

        $data['equipment_name'] = $data['nama_pesawat'];
        unset($data['nama_pesawat']);

        $this->db->where('inspection_id', $rel_id);
        $this->db->where('task_id', $task_id);
        $this->db->update(db_prefix() . 'inspection_items', $data);
    }

    public function inspection_add_inspection_item($data){
        
        $this->db->insert(db_prefix() . 'inspection_items', [
                'inspection_id'      => $data['inspection_id'],
                'project_id' => $data['project_id'],
                'task_id'              => $data['task_id']]);
    }

    public function inspection_remove_inspection_item($data)
    {

        $affectedRows   = 0;

        //$this->db->where('inspection_id', $data['inspection_id']);
        //$this->db->where('task_id', $data['task_id']);
        //$id = $this->db->get(db_prefix() . 'inspection_items')->row();
        //if(isset($id)){
            $this->db->delete(db_prefix() . 'inspection_items', [
                'inspection_id' => $data['inspection_id'],
                'task_id' => $data['task_id'],
            ]);            
        //}

        $_log_message = '';

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
                $_log_message    = 'not_inspection_remove_inspection_item';
                $additional_data = serialize([
                    get_staff_full_name(),
                    $data['inspection_id'],
                    $data['task_id'],
                ]);

                hooks()->do_action('inspection_remove_inspection_item', [
                    'inspection_id' => $data['inspection_id'],
                    'task_id' => $data['task_id'],
                ]);
            
        }

        if ($affectedRows > 0) {
            if ($_log_message == '') {
                return true;
            }
            $this->log_inspection_activity($data['inspection_id'], $_log_message, false, $additional_data);

            return true;
        }

        return false;
    }

    public function get_available_tags($task_id=NULL){

        $this->db->select([db_prefix() . 'tags.id AS tag_id', db_prefix() . 'tags.name AS tag_name']);
        $this->db->select(['COUNT('.db_prefix() . 'tasks.id) AS count_task']);
        
        $this->db->join(db_prefix() . 'taggables', db_prefix() . 'taggables.rel_id = ' . db_prefix() . 'tasks.id', 'left');
        $this->db->join(db_prefix() . 'tags', db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id', 'left');
        $this->db->group_by(db_prefix() . 'tags.id');
        $this->db->where(db_prefix() . 'tasks.rel_type = ' . "'project'");
        if(is_numeric($task_id)){
            $this->db->where(db_prefix() . 'tasks.id = ' . $task_id);
        }
        $this->db->where(db_prefix() . 'tags.id is NOT NULL', NULL, true);

        //return $this->db->get_compiled_select(db_prefix() . 'tasks');
        return $this->db->get(db_prefix() . 'tasks')->result_array();

    }

    public function add_files_to_database($rel_id, $task_id, $attachment, $external = false)
    {
        $data['dateadded'] = date('Y-m-d H:i:s');
        $data['rel_id']    = $rel_id;
        $data['task_id']    = $task_id;
        if (!isset($attachment[0]['staffid'])) {
            $data['staffid'] = get_staff_user_id();
        } else {
            $data['staffid'] = $attachment[0]['staffid'];
        }

        if (isset($attachment[0]['task_comment_id'])) {
            $data['task_comment_id'] = $attachment[0]['task_comment_id'];
        }

        //$data['rel_type'] = $rel_type;

        if (isset($attachment[0]['contact_id'])) {
            $data['contact_id']          = $attachment[0]['contact_id'];
            $data['visible_to_customer'] = 1;
            if (isset($data['staffid'])) {
                unset($data['staffid']);
            }
        }

        $data['attachment_key'] = app_generate_hash();

        if ($external == false) {
            $data['file_name'] = $attachment[0]['file_name'];
            $data['filetype']  = $attachment[0]['filetype'];
        } else {
            $path_parts            = pathinfo($attachment[0]['name']);
            $data['file_name']     = $attachment[0]['name'];
            $data['external_link'] = $attachment[0]['link'];
            $data['filetype']      = !isset($attachment[0]['mime']) ? get_mime_by_extension('.' . $path_parts['extension']) : $attachment[0]['mime'];
            $data['external']      = $external;
            if (isset($attachment[0]['thumbnailLink'])) {
                $data['thumbnail_link'] = $attachment[0]['thumbnailLink'];
            }
        }

        $this->db->insert(db_prefix() . 'inspection_files', $data);
        $insert_id = $this->db->insert_id();

        return $insert_id;
    }


    /**
     * Add uploaded attachments to database
     * @since  Version 1.0.1
     * @param mixed $taskid     task id
     * @param array $attachment attachment data
     */
    public function add_attachment_to_database($rel_id, $task_id, $attachment, $external = false, $notification = true)
    {
        $file_id = $this->add_files_to_database($rel_id, $task_id, $attachment, $external);
        if ($file_id) {
            /*
            $this->db->select('rel_type,rel_id,name,visible_to_client');
            $this->db->where('id', $rel_id);
            $task = $this->db->get(db_prefix() . 'tasks')->row();

            if ($task->rel_type == 'project') {
                $this->projects_model->log_activity($task->rel_id, 'project_activity_new_task_attachment', $task->name, $task->visible_to_client);
            }

            if ($notification == true) {
                $description = 'not_task_new_attachment';
                $this->_send_task_responsible_users_notification($description, $rel_id, false, 'task_new_attachment_to_staff');
                $this->_send_customer_contacts_notification($rel_id, 'task_new_attachment_to_customer');
            }

            $task_attachment_as_comment = hooks()->apply_filters('add_task_attachment_as_comment', 'true');

            if ($task_attachment_as_comment == 'true') {
                $file = $this->misc_model->get_file($file_id);
                $this->db->insert(db_prefix() . 'task_comments', [
                    'content'    => '[task_attachment]',
                    'taskid'     => $rel_id,
                    'staffid'    => $file->staffid,
                    'contact_id' => $file->contact_id,
                    'file_id'    => $file_id,
                    'dateadded'  => date('Y-m-d H:i:s'),
                ]);
            }
            */

            return true;
        }

        return false;
    }


    /**
     * Get all inspection attachments
     * @param  mixed $inspection_id inspection_id
     * @return array
     */
    public function get_inspection_documentation($inspection_id, $task_id, $where = [])
    {
        //$this->db->select(implode(', ', prefixed_table_fields_array(db_prefix() . 'files')) . ', ' . db_prefix() . 'inspection_comments.id as comment_file_id');
        $this->db->select(db_prefix() . 'inspection_files.*');
        $this->db->where(db_prefix() . 'inspection_files.rel_id', $inspection_id);
        $this->db->where(db_prefix() . 'inspection_files.task_id', $task_id);

        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $this->db->where($where);
        }

        $this->db->join(db_prefix() . 'inspections', db_prefix() . 'inspections.id = ' . db_prefix() . 'inspection_files.rel_id');
        $this->db->order_by(db_prefix() . 'inspection_files.dateadded', 'desc');

        return $this->db->get(db_prefix() . 'inspection_files')->result_array();
    }


    /**
     * Remove inspection attachment from server and database
     * @param  mixed $id attachmentid
     * @return boolean
     */
    public function remove_inspection_attachment($id)
    {
        $comment_removed = false;
        $deleted         = false;
        // Get the attachment
        $this->db->where('id', $id);
        $attachment = $this->db->get(db_prefix() . 'inspection_files')->row();

        if ($attachment) {
            if (empty($attachment->external)) {
                $relPath  = get_upload_path_by_type('inspection') . $attachment->rel_id . '/';
                $fullPath = $relPath . $attachment->file_name;
                unlink($fullPath);
                $fname     = pathinfo($fullPath, PATHINFO_FILENAME);
                $fext      = pathinfo($fullPath, PATHINFO_EXTENSION);
                $thumbPath = $relPath . $fname . '_thumb.' . $fext;
                if (file_exists($thumbPath)) {
                    unlink($thumbPath);
                }
            }

            $this->db->where('id', $attachment->id);
            $this->db->delete(db_prefix() . 'inspection_files');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                log_activity('inspection Attachment Deleted [inspectionID: ' . $attachment->rel_id . ']');
            }

            if (is_dir(get_upload_path_by_type('inspection') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('inspection') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type('inspection') . $attachment->rel_id);
                }
            }
        }

        return ['success' => $deleted];
    }

    /**
     * Get the inspections about to expired in the given days
     *
     * @param  integer|null $staffId
     * @param  integer $days
     *
     * @return array
     */
    public function get_project_not_inspected($staffId = null)
    {
        $days = get_option('inspection_number_of_date');
        $diff1 = date('Y-m-d', strtotime('-' . $days . ' days'));
        $diff2 = date('Y-m-d', strtotime('+' . $days . ' days'));
        $start = date(get_option('inspection_start_date'));

        if ($staffId && ! staff_can('view', 'inspections', $staffId)) {
            $this->db->where(db_prefix() . 'inspections.addedfrom', $staffId);
        }

        $this->db->select(db_prefix() . 'inspections.id,' . db_prefix() . 'inspections.formatted_number,' . db_prefix() . 'clients.userid,' . db_prefix() . 'clients.company,' . db_prefix() . 'projects.id,' . db_prefix() . 'projects.name,' . db_prefix() . 'projects.start_date');
        $this->db->join(db_prefix() . 'projects', db_prefix() . 'projects.id = ' . db_prefix() . 'inspections.project_id', 'right');
        $this->db->join(db_prefix() . 'schedules', db_prefix() . 'schedules.project_id = ' . db_prefix() . 'inspections.project_id', 'left');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.userid = ' . db_prefix() . 'projects.clientid', 'left');
        
        $this->db->where(db_prefix() . 'inspections.project_id', null);
        $this->db->where(db_prefix() . 'schedules.project_id IS NOT NULL');
        if($days !='0'){
            $this->db->where(db_prefix() . 'projects.start_date >=', $diff1);        
            $this->db->where(db_prefix() . 'projects.start_date <=', $diff2);            
        }

        $this->db->where(db_prefix() . 'projects.start_date >=', $start);

        //return $this->db->get_compiled_select(db_prefix() . 'inspections');

        return $this->db->get(db_prefix() . 'inspections')->result_array();
    }


  public function get_inpections_items($inspection_id, $project_id){

        $this->db->select([db_prefix() . 'tasks.id AS task_id', db_prefix() . 'tasks.name AS task_name']);
        $this->db->select([db_prefix() . 'inspection_items.inspection_id', db_prefix() . 'projects.id AS project_id', db_prefix() . 'tags.name AS tags_name', 'COUNT('.db_prefix() . 'tasks.id) AS count']);

        $this->db->join(db_prefix() . 'tasks', db_prefix() . 'tasks.id = ' . db_prefix() . 'inspection_items.task_id', 'left');
        $this->db->join(db_prefix() . 'projects', db_prefix() . 'tasks.rel_id = ' . db_prefix() . 'projects.id', 'left');
        $this->db->join(db_prefix() . 'taggables', db_prefix() . 'taggables.rel_id = ' . db_prefix() . 'tasks.id', 'left');
        $this->db->join(db_prefix() . 'tags', db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id', 'left');

        $this->db->group_by(db_prefix() . 'inspection_items.inspection_id');
        $this->db->group_by(db_prefix() . 'inspection_items.project_id');
        $this->db->group_by(db_prefix() . 'inspection_items.task_id');
        $this->db->group_by(db_prefix() . 'tasks.name');
        $this->db->group_by(db_prefix() . 'tags.name');

        $this->db->where(db_prefix() . 'tasks.rel_id =' . $project_id);
        $this->db->where(db_prefix() . 'tasks.rel_type = ' . "'project'");
        $this->db->where(db_prefix() . 'inspection_items.inspection_id=' . $inspection_id);

        //return $this->db->get_compiled_select(db_prefix() . 'inspection_items');
        return $this->db->get(db_prefix() . 'inspection_items')->result_array();
    }

}
