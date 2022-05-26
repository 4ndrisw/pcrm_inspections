<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bucket_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();

     }

    /**
     * Get bucket/s
     * @param mixed $id bucket id
     * @param array $where perform where
     * @return mixed
     */
    public function get($id = '',  $where = [])
    {
        $this->db->select('*,' . db_prefix() . 'bucket.id');
        $this->db->from(db_prefix() . 'bucket');
        $this->db->where($where);
        $results = $this->db->get()->result();
        return reset($results);
    }

    public function create_or_update($data, $rel_id){
        $this->db->select('id');
        $this->db->where('rel_id', $rel_id);
        $exists = $this->db->get(db_prefix() . 'bucket')->result();
        if($exists){
            $this->db->where('rel_id', $rel_id);
            $this->db->update(db_prefix() . 'bucket', $data);
        }else{
            $data['rel_id'] = $rel_id;
            $this->db->insert(db_prefix().'bucket', $data);
        }
    }
}