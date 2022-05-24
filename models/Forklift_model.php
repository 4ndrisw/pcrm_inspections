<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Forklift_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();

     }

    /**
     * Get forklift/s
     * @param mixed $id forklift id
     * @param array $where perform where
     * @return mixed
     */
    public function get($id = '',  $where = [])
    {
        $this->db->select('*,' . db_prefix() . 'forklift.id');
        $this->db->from(db_prefix() . 'forklift');
        $this->db->where($where);
        $results = $this->db->get()->result();
        return reset($results);
    }

    public function create($data){
        $data['jenis_pesawat'] = 'Chain hoist';
        
        $this->db->insert(db_prefix().'forklift', $data);
        $equipment_id = $this->db->insert_id();

        hooks()->do_action('after_forklift_added', $equipment_id);
        return $equipment_id;
    }

    public function create_or_update($data, $rel_id){
        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $exists = $this->db->get(db_prefix() . 'forklift')->result();
        if($exists){
            $this->db->where('rel_id', $rel_id);
            $this->db->update(db_prefix() . 'forklift', $data);
        }else{
            $data['rel_id'] = $rel_id;
            $this->db->insert(db_prefix().'forklift', $data);
        }
    }

    public function update($data, $rel_id){
        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $this->db->update(db_prefix() . 'forklift', $data);
    }

}