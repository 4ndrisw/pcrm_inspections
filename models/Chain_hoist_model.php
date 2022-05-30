<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Chain_hoist_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();

     }

    /**
     * Get chain_hoist/s
     * @param mixed $id chain_hoist id
     * @param array $where perform where
     * @return mixed
     */
    public function get($id = '',  $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where('staffid', $id);
            $category = $this->db->get(db_prefix() . 'chain_hoist')->row();

            return $category;
        }
        $this->db->select('*,' . db_prefix() . 'chain_hoist.id');
        $this->db->from(db_prefix() . 'chain_hoist');
        $this->db->where($where);
        $results = $this->db->get()->result_array();
        return $results;

    }

    public function create($data){
        $data['jenis_pesawat'] = 'Chain hoist';
        $data['regulasi'] = get_option('predefined_regulation_of_paa');
        $this->db->insert(db_prefix().'chain_hoist', $data);
        $equipment_id = $this->db->insert_id();

        hooks()->do_action('after_chain_hoist_added', $equipment_id);
        return $equipment_id;
    }

    public function create_or_update($data, $rel_id){
        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $exists = $this->db->get(db_prefix() . 'chain_hoist')->result();
        if($exists){
            $this->db->where('rel_id', $rel_id);
            $this->db->update(db_prefix() . 'chain_hoist', $data);
        }else{
            $data['rel_id'] = $rel_id;
            $this->db->insert(db_prefix().'chain_hoist', $data);
        }
    }

    public function update($data, $rel_id){
        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $this->db->update(db_prefix() . 'chain_hoist', $data);
    }

}