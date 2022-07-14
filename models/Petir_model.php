<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Petir_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();

     }

    /**
     * Get petir/s
     * @param mixed $id petir id
     * @param array $where perform where
     * @return mixed
     */
    public function get($id = '',  $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where('staffid', $id);
            $category = $this->db->get(db_prefix() . 'petir')->row();

            return $category;
        }
        $this->db->select('*,' . db_prefix() . 'petir.id');
        $this->db->from(db_prefix() . 'petir');
        $this->db->where($where);
        $results = $this->db->get()->result_array();
        return $results;

    }

    public function create($data){
        $data['jenis_pesawat'] = 'Bejana Tekan';
        $data['regulasi'] = get_option('predefined_regulation_of_paa');
        $this->db->insert(db_prefix().'petir', $data);
        $equipment_id = $this->db->insert_id();

        hooks()->do_action('after_petir_added', $equipment_id);
        return $equipment_id;
    }

    public function create_or_update($data, $rel_id){
        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $exists = $this->db->get(db_prefix() . 'petir')->result();
        if($exists){
            $this->db->where('rel_id', $rel_id);
            $this->db->update(db_prefix() . 'petir', $data);
        }else{
            $data['rel_id'] = $rel_id;
            $this->db->insert(db_prefix().'petir', $data);
        }
    }

    public function update($data, $rel_id, $task_id){
        $field_nama_pesawat = $data['field'];
        unset($data['field']);
        $data_nama_pesawat = htmlspecialchars($data['text'], ENT_QUOTES);
        unset($data['text']);
        $data[$field_nama_pesawat] = $data_nama_pesawat;

        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $this->db->where('task_id', $task_id);
        $this->db->update(db_prefix() . 'petir', $data);
    }

}