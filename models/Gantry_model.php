<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Gantry_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();

     }

    /**
     * Get gantry/s
     * @param mixed $id gantry id
     * @param array $where perform where
     * @return mixed
     */
    public function get($id = '',  $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where('staffid', $id);
            $category = $this->db->get(db_prefix() . 'gantry')->row();

            return $category;
        }
        $this->db->select('*,' . db_prefix() . 'gantry.id');
        $this->db->from(db_prefix() . 'gantry');
        $this->db->where($where);
        $results = $this->db->get()->result_array();
        return $results;

    }

    public function create($data){
        $data['regulasi'] = get_option('predefined_regulation_of_paa');
        $this->db->insert(db_prefix().'gantry', $data);
        $equipment_id = $this->db->insert_id();

        hooks()->do_action('after_gantry_added', $equipment_id);
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
            $this->db->insert(db_prefix(). $equipment_type, $data);
        }
    }

    public function update($data, $rel_id){
        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $this->db->update(db_prefix() . 'gantry', $data);
    }

}