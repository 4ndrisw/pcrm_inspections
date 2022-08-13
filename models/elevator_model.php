<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Elevator_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();

     }

    /**
     * Get elevator/s
     * @param mixed $id elevator id
     * @param array $where perform where
     * @return mixed
     */
    public function get($id = '',  $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where('staffid', $id);
            $category = $this->db->get(db_prefix() . 'elevator')->row();

            return $category;
        }
        $this->db->select('*,' . db_prefix() . 'elevator.id');
        $this->db->from(db_prefix() . 'elevator');
        $this->db->where($where);
        $results = $this->db->get()->result_array();
        return $results;

    }

    public function create($data){
        $data['regulasi'] = get_option('predefined_regulation_of_paa');
        $this->db->insert(db_prefix().'elevator', $data);
        $equipment_id = $this->db->insert_id();

        hooks()->do_action('after_elevator_added', $equipment_id);
        return $equipment_id;
    }

    public function create_or_update($data, $rel_id, $task_id, $equipment_type){
        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $this->db->where('task_id', $task_id);
        $exists = $this->db->get(db_prefix() . $equipment_type)->result();
        if($exists){
            $this->db->where('rel_id', $rel_id);
            $this->db->where('task_id', $task_id);
            $this->db->update(db_prefix() . $equipment_type, $data);
        }else{
            $data['rel_id'] = $rel_id;
            $this->db->insert(db_prefix(). $equipment_type, $data);
        }
    }

    public function update($data, $rel_id, $task_id){
        $field = $data['field'];
        unset($data['field']);
        //$data_text = htmlspecialchars($data['text'], ENT_QUOTES);
        $data_text = strip_tags($data['text'], '<div><p><br>');
        //$data_text = $data['text'];
        unset($data['text']);
        $data[$field] = $data_text;

        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $this->db->where('task_id', $task_id);
        $this->db->update(db_prefix() . 'elevator', $data);
    }

    public function update_pengujian_data($data){
        $rel_id = $data['rel_id'];
        $task_id = $data['task_id'];
        $jenis_pesawat = strtolower($data['jenis_pesawat']);
        $this->db->where('rel_id', $rel_id);
        $this->db->where('task_id', $task_id);
        $this->db->set($data['pengujian'], $data['value']);

        unset($data['value']);
        unset($data['pengujian']);
        unset($data['jenis_pesawat']);
        unset($data['rel_id']);
        unset($data['task_id']);
        
        $this->db->update(db_prefix() . $jenis_pesawat, $data);
    }
}