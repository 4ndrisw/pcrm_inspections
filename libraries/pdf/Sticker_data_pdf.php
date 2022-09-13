<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(LIBSPATH . 'pdf/App_pdf.php');

class Sticker_data_pdf extends App_pdf
{
    protected $inspection_id;

    public function __construct($inspection_id, $task_id)
    {
        parent::__construct();

        $this->inspection_id = $inspection_id;
        $this->task_id = $task_id;

        $this->ci->load->model('clients_model');
    }

    public function prepare()
    {
        $inspection = $this->ci->inspections_model->get($this->inspection_id);
        $inspection_number = format_inspection_item_number($inspection->id, $this->task_id);
        
        $this->SetTitle($inspection->formatted_number.'-'.$this->task_id);
        $members                = $this->ci->inspections_model->get_inspection_members($inspection->id);

        $tags = get_tags_in($this->task_id, 'task');

        $data['jenis_pesawat'] = $tags[0];

        $equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tags[0])));
        $equipment_model = $equipment_type .'_model';
        $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';
        
        include_once($model_path);
        $this->ci->load->model($equipment_model);
        $equipment = $this->ci->{$equipment_model}->get('', ['rel_id' => $inspection->id]);
        $equipment          = reset($equipment);
        
        $inspection->assigned_path = get_inspection_upload_path('inspection').$inspection->id.'/assigned-'.$inspection_number.'.png';
        
        // Add <br /> tag and wrap over div element every image to prevent overlaping over text
        //$inspection->description = preg_replace('/(<img[^>]+>(?:<\/img>)?)/i', '<br><br><div>$1</div><br><br>', $inspection->description);
        $data['client_company']   = $this->ci->clients_model->get($inspection->clientid)->company;
        
        $equipment_name = isset($equipment['nama_pesawat']) ? $equipment['nama_pesawat'] : '';
        
        $inspection->equipment_name = $equipment_name;
        $inspection->task_id = $this->task_id;
        
        $data['inspection']       = $inspection;
        $data['equipment']       = $equipment;

        $data['equipment_name']                          = $equipment_name;
        $data['total_members'] = count($members);
        $data['members'] = $members;

        $url_data = site_url('inspections/sticker/'. $inspection->id .'/html/'.$this->task_id.'/'.$inspection->hash) ."\r\n";
        $data['url_data'] = $url_data;
                           
        $this->set_view_vars($data);

        return $this->build();
    }

    protected function type()
    {
        return 'inspection-data';
    }

    protected function file_path()
    {
        $customPath = module_views_path('inspections', 'admin/inspections/my_sticker_data_pdf.php');
        $actualPath = module_views_path('inspections', 'admin/inspections/sticker_data_pdf.php');

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
