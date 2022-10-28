<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(LIBSPATH . 'pdf/App_pdf.php');

class Inspection_item_pdf extends App_pdf
{
    protected $inspection;

    private $inspection_number;

    public function __construct($inspection, $tag = '')
    {
        $this->load_language($inspection->clientid);

        $inspection                = hooks()->apply_filters('inspection_html_pdf_data', $inspection);
        $GLOBALS['inspection_pdf'] = $inspection;

        parent::__construct();

        $this->tag             = $tag;
        $this->inspection        = $inspection;
        $this->equipment        = $inspection->equipment;
        $this->inspection_number = format_inspection_item_number($this->inspection->id, $this->inspection->task_id);

        $this->SetTitle($this->inspection_number);
    }

    public function prepare()
    {

        $this->set_view_vars([
            'status'          => $this->inspection->status,
            'inspection_number' => $this->inspection_number,
            'inspection'        => $this->inspection,
            'equipment'        => $this->equipment,
        ]);

        return $this->build();
    }

    protected function type()
    {
        return 'inspection';
    }

    protected function file_path()
    {
        $filePath = 'my_inspectionpdf.php';
        $inspection_equipment_type = isset($this->inspection->equipment_type) ? strtolower($this->inspection->equipment_type) : FALSE;

        if(isset($this->inspection->categories)){
            $filePath = 'inspection_'. $this->inspection->categories .'_pdf.php';
        }

        if($inspection_equipment_type){
            $equipment_type = 'inspection_'. $inspection_equipment_type .'_pdf.php';
            $equipment_type_filePath = module_views_path('inspections','themes/' . active_clients_theme() . '/views/inspections/' . $equipment_type);
            if (file_exists($equipment_type_filePath)) {
                $filePath = $equipment_type;
            }
        }

        $customPath = module_views_path('inspections','themes/' . active_clients_theme() . '/views/inspections/' . $filePath);
        $actualPath = module_views_path('inspections','themes/' . active_clients_theme() . '/views/inspections/inspection_item_pdf.php');

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }
}
