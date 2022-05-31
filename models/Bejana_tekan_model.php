<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bejana_tekan_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();

     }

    /**
     * Get bejana_tekan/s
     * @param mixed $id bejana_tekan id
     * @param array $where perform where
     * @return mixed
     */
    public function get($id = '',  $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where('staffid', $id);
            $category = $this->db->get(db_prefix() . 'bejana_tekan')->row();

            return $category;
        }
        $this->db->select('*,' . db_prefix() . 'bejana_tekan.id');
        $this->db->from(db_prefix() . 'bejana_tekan');
        $this->db->where($where);
        $results = $this->db->get()->result_array();
        return $results;

    }

    public function create($data){
        $data['jenis_pesawat'] = 'Bejana Tekan';
        $data['regulasi'] = get_option('predefined_regulation_of_paa');
        $this->db->insert(db_prefix().'bejana_tekan', $data);
        $equipment_id = $this->db->insert_id();

        hooks()->do_action('after_bejana_tekan_added', $equipment_id);
        return $equipment_id;
    }

    public function create_or_update($data, $rel_id){
        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $exists = $this->db->get(db_prefix() . 'bejana_tekan')->result();
        if($exists){
            $this->db->where('rel_id', $rel_id);
            $this->db->update(db_prefix() . 'bejana_tekan', $data);
        }else{
            $data['rel_id'] = $rel_id;
            $this->db->insert(db_prefix().'bejana_tekan', $data);
        }
    }

    public function update($data, $rel_id){
        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $this->db->update(db_prefix() . 'bejana_tekan', $data);
    }

}