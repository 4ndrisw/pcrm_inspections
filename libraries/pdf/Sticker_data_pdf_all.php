<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(LIBSPATH . 'pdf/App_pdf.php');

class Sticker_data_pdf_all extends App_pdf
{
    protected $inspection_id;

    public function __construct($inspection_id)
    {
        parent::__construct();

        $this->inspection_id = $inspection_id;
        $this->ci->load->model('clients_model');
    }

    public function prepare()
    {
        $inspection = $this->ci->inspections_model->get($this->inspection_id);
        $inspection_number = format_inspection_number($inspection->id);
        $inspection->inspection_items = $this->ci->inspections_model->get_inspection_items($inspection->id, $inspection->project_id);
        $this->SetTitle($inspection->formatted_number .'-STICKER-ALL');
        $data['items'] = $inspection->inspection_items;

/*
        $tags = get_tags_in($this->task_id, 'task');

        $data['jenis_pesawat'] = $tags[0];

        $equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tags[0])));
        $equipment_model = $equipment_type .'_model';
        $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';
        
        include_once($model_path);
        $this->ci->load->model($equipment_model);
        $equipment = $this->ci->{$equipment_model}->get('', ['rel_id' => $inspection->id]);
        $equipment          = reset($equipment);
  */      
        $inspection->assigned_path = get_inspection_upload_path('inspection').$inspection->id.'/assigned-'.$inspection_number.'.png';
        
        // Add <br /> tag and wrap over div element every image to prevent overlaping over text
        //$inspection->description = preg_replace('/(<img[^>]+>(?:<\/img>)?)/i', '<br><br><div>$1</div><br><br>', $inspection->description);
        $data['client_company']   = $this->ci->clients_model->get($inspection->clientid)->company;
        
        $equipment_name = isset($equipment['nama_pesawat']) ? $equipment['nama_pesawat'] : '';
        
        $inspection->equipment_name = $equipment_name;
        //$inspection->task_id = $this->task_id;
        
        $data['inspection']       = $inspection;
        //$data['equipment']       = $equipment;

        $data['equipment_name']                          = $equipment_name;
        //$data['total_members'] = count($members);
        //$data['members'] = $members;

        $this->set_view_vars($data);

        return $this->build();
    }

    protected function type()
    {
        return 'inspection-data';
    }

    protected function file_path()
    {
        $customPath = module_views_path('inspections', 'admin/inspections/my_sticker_data_pdf_all.php');
        $actualPath = module_views_path('inspections', 'admin/inspections/sticker_data_pdf_all.php');

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }

    public function get_format_array()
    {
        return  [
            'orientation' => 'L',
            'format'      => 'B8',
        ];
    }

}
