<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(LIBSPATH . 'pdf/App_pdf.php');

class Sticker_data_pdf extends App_pdf
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

        $this->SetTitle($inspection->formatted_number);
        $members                = $this->ci->inspections_model->get_inspection_members($inspection->id);

        $tags = get_tags_in($inspection->id, 'inspection');

        $data['jenis_pesawat'] = $tags[0];

        $equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tags[0])));
        $equipment_model = $equipment_type .'_model';
        $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';

        include_once($model_path);
        $this->load->model($equipment_model);
        $equipment = $this->{$equipment_model}->get('', ['rel_id' => $inspection->id]);

        $inspection->assigned_path = FCPATH . get_inspection_upload_path('inspection').$inspection->id.'/assigned-'.$inspection_number.'.png';
        
        // Add <br /> tag and wrap over div element every image to prevent overlaping over text
        //$inspection->description = preg_replace('/(<img[^>]+>(?:<\/img>)?)/i', '<br><br><div>$1</div><br><br>', $inspection->description);
        $data['client_company']   = $this->ci->clients_model->get($inspection->clientid)->company;

        $data['inspection']       = $inspection;
        $data['equipment']       = $equipment;
        
        $equiptment_name = isset($equiptment->nama_pesawat) ? $equiptment->nama_pesawat : '';

        $data['equiptment_name']                          = $equiptment_name;       
        $data['total_members'] = count($members);
        $data['members'] = $members;

        $url_data = site_url('inspections/show/'. $inspection->id .'/'.$inspection->hash) ."\r\n";
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
