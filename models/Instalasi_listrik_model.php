<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Instalasi_listrik_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();

     }

    /**
     * Get instalasi_listrik/s
     * @param mixed $id bucket id
     * @param array $where perform where
     * @return mixed
     */
    public function get($id = '',  $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where('staffid', $id);
            $category = $this->db->get(db_prefix() . 'instalasi_listrik')->row();

            return $category;
        }
        $this->db->select('*,' . db_prefix() . 'instalasi_listrik.id');
        $this->db->from(db_prefix() . 'instalasi_listrik');
        $this->db->where($where);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function create_or_update($data, $rel_id){
        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $exists = $this->db->get(db_prefix() . 'instalasi_listrik')->result();
        if($exists){
            $this->db->where('rel_id', $rel_id);
            $this->db->update(db_prefix() . 'instalasi_listrik', $data);
        }else{
            $data['rel_id'] = $rel_id;
            $this->db->insert(db_prefix().'instalasi_listrik', $data);
        }
    }

    public function create($data){
        $data['jenis_pesawat'] = 'instalasi_listrik';
        $data['regulasi'] = get_option('predefined_regulation_of_iil');

        $this->db->insert(db_prefix().'instalasi_listrik', $data);
        $equipment_id = $this->db->insert_id();

        hooks()->do_action('after_instalasi_listrik_added', $equipment_id);
        return $equipment_id;
    }

    public function update($data, $rel_id){
        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $this->db->update(db_prefix() . 'instalasi_listrik', $data);
    }

}