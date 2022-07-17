<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Overhead_crane_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();

     }

    /**
     * Get overhead_crane/s
     * @param mixed $id overhead_crane id
     * @param array $where perform where
     * @return mixed
     */
    public function get($id = '',  $where = [])
    {
        $this->db->select('*,' . db_prefix() . 'overhead_crane.id');
        $this->db->from(db_prefix() . 'overhead_crane');
        $this->db->where($where);
        $results = $this->db->get()->result();
        return reset($results);
    }

    public function create($data){        
        $this->db->insert(db_prefix().'overhead_crane', $data);
        $equipment_id = $this->db->insert_id();

        hooks()->do_action('after_overhead_crane_added', $equipment_id);
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
        $this->db->update(db_prefix() . 'overhead_crane', $data);
    }

}