<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Equipment_category_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();

     }

    /**
     * Get equipment_category/s
     * @param mixed $id bucket id
     * @param array $where perform where
     * @return mixed
     */
    public function get($id = '',  $where = [])
    {

        if (is_numeric($id)) {
            $this->db->where('staffid', $id);
            $category = $this->db->get(db_prefix() . 'equipment_category')->row();

            return $category;
        }
        $this->db->select('*,' . db_prefix() . 'equipment_category.code');
        $this->db->from(db_prefix() . 'equipment_category');
        $this->db->where($where);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function create_or_update($data, $rel_id){
        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $exists = $this->db->get(db_prefix() . 'equipment_category')->result();
        if($exists){
            $this->db->where('rel_id', $rel_id);
            $this->db->update(db_prefix() . 'equipment_category', $data);
        }else{
            $data['rel_id'] = $rel_id;
            $this->db->insert(db_prefix().'equipment_category', $data);
        }
    }
}