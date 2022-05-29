<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Listrik_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();

     }

    /**
     * Get listrik/s
     * @param mixed $id bucket id
     * @param array $where perform where
     * @return mixed
     */
    public function get($id = '',  $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where('staffid', $id);
            $category = $this->db->get(db_prefix() . 'listrik')->row();

            return $category;
        }
        $this->db->select('*,' . db_prefix() . 'listrik.id');
        $this->db->from(db_prefix() . 'listrik');
        $this->db->where($where);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function create_or_update($data, $rel_id){
        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $exists = $this->db->get(db_prefix() . 'listrik')->result();
        if($exists){
            $this->db->where('rel_id', $rel_id);
            $this->db->update(db_prefix() . 'listrik', $data);
        }else{
            $data['rel_id'] = $rel_id;
            $this->db->insert(db_prefix().'listrik', $data);
        }
    }

    public function create($data){
        $data['jenis_pesawat'] = 'listrik';
        $data['regulasi'] = get_option('predefined_regulation_of_iil');

        $this->db->insert(db_prefix().'listrik', $data);
        $equipment_id = $this->db->insert_id();

        hooks()->do_action('after_listrik_added', $equipment_id);
        return $equipment_id;
    }

    public function update($data, $rel_id){
        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $this->db->update(db_prefix() . 'listrik', $data);
    }

}