<?php

defined('BASEPATH') or exit('No direct script access allowed');
$pages = count($inspection->inspection_items);
$page = 0;

foreach($inspection->inspection_items as $item){


$dimensions = $pdf->getPageDimensions();

// set auto page breaks
$pdf->SetAutoPageBreak(true, 5);
$pdf->SetFont('dejavusans');

$info_right_column = '';
$info_left_column  = '';

$info_right_column .= '<span style="font-weight:bold;font-size:27px;">' . _l('inspection_pdf_heading') . '</span><br />';
$info_right_column .= '<b style="color:#4e4e4e;"># ' . $inspection_number .'-'. $item['task_id'] .'</b>';

if (get_option('show_status_on_pdf_ei') == 1) {
    $info_right_column .= '<br /><span style="color:rgb(' . inspection_status_color_pdf($status) . ');text-transform:uppercase;">' . format_inspection_status($status, '', false) . '</span>';
}

// Add logo
$info_left_column .= pdf_logo_url();
// Write top left logo and right column info/text
pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->ln(5);

$organization_info = '<div style="color:#424242;">';
    $organization_info .= format_organization_info();
$organization_info .= '</div>';

// Inspection to
$inspection_info = '<b>' . _l('inspection_to') . '</b>';
$inspection_info .= '<div style="color:#424242;">';
$inspection_info .= format_customer_info($inspection, 'inspection', 'billing');
$inspection_info .= '</div>';

$CI = &get_instance();
$CI->load->model('inspections_model');
$inspection_members = $CI->inspections_model->get_inspection_members($inspection->id,true);

if ($inspection->project_id != 0 && get_option('show_project_on_inspection') == 1) {
    $inspection_info .= _l('project') . ': ' . get_project_name_by_id($inspection->project_id) . '<br />';
}


$left_info  = $swap == '1' ? $inspection_info : $organization_info;
$right_info = $swap == '1' ? $organization_info : $inspection_info;

pdf_multi_row($left_info, $right_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);
$date = $inspection->date;
$inspection_declare = _l('inspection_declare');
$getDayName = getDayName($date);
$getDay = getDay($date);
$getMonth = getMonth($date);
$getYear = getYear($date);

$inspection_result = _l('inspection_result');
$txt = <<<EOD
$inspection_declare $getDayName $getDay $getMonth $getYear, $inspection_result
EOD;

// print a block of text using Write()
$pdf->Write(0, $txt, '', 0, 'L', true, 0, false, false, 0);


// The Table
$pdf->Ln(hooks()->apply_filters('pdf_info_and_table_separator', 2));

$company = get_inspection_company_by_clientid($inspection->clientid);
$address = get_inspection_company_address($inspection->id);
$nama_pesawat = isset($inspection->equipment['nama_pesawat']) ? $inspection->equipment['nama_pesawat'] :'';
//$nomor_seri = isset($inspection->equipment['nomor_seri']) ? $inspection->equipment['nomor_seri'] : '';
//$nomor_unit = isset($inspection->equipment['nomor_unit']) ? $inspection->equipment['nomor_unit'] : '';
//$type_model = isset($inspection->equipment['type_model']) ? $inspection->equipment['type_model'] : '';

//$kapasitas = isset($inspection->equipment['kapasitas']) ? $inspection->equipment['kapasitas'] : '';

$inspection_company = _l('inspection_company_name');
$inspection_address = _l('inspection_address');
$inspection_jenis_pesawat = _l('inspection_jenis_pesawat');
$inspection_nama_pesawat = _l('inspection_nama_pesawat');
$inspection_unit_number = _l('inspection_unit_number');
$inspection_type_model = _l('inspection_type_model');
$inspection_serial_number = _l('inspection_serial_number');
$inspection_capacity = _l('inspection_capacity');
$inspection_jumlah_kotak_hydrant = _l('inspection_jumlah_kotak_hydrant');
$inspection_jumlah_selang_hydrant = _l('inspection_jumlah_selang_hydrant');
$inspection_jumlah_nozzle = _l('inspection_jumlah_nozzle');
$inspection_kapasitas_air = _l('inspection_kapasitas_air');

$tag = $inspection->inspection_items[$page]['tag_name'];

$equipment = isset($tag) ? $tag : '';
$left_info .= '<div><strong>'. _l('equipment_type') . '</strong></div>';
$left_info .= $equipment;
$equipment_type = ucfirst(strtolower(str_replace(' ', '_', $tag)));
$equipment_model = $equipment_type .'_model';
$model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $equipment_model .'.php';

include_once($model_path);
$this->ci->load->model($equipment_model);
$task_id = $inspection->inspection_items[$page]['task_id'];
$equipment = $this->ci->{$equipment_model}->get('', ['rel_id' => $inspection->id, 'task_id' => $task_id]);

if (!$equipment) {
    set_alert('danger', _l('record not found ;', $equipment_model));
    
    redirect(admin_url('inspections/inspection_item/'.$id.'/'.$task_id));
}

$inspection->equipment = isset($equipment[$page]) ? $equipment[$page] : [];

$tag_id = $this->ci->inspections_model->get_available_tags($task_id);

$inspection->categories = get_option('tag_id_'.$tag_id['0']['tag_id']);

$inspection_sumber_tenaga = _l('inspection_sumber_tenaga');
$inspection_daya_terpasang = _l('inspection_daya_terpasang');
$inspection_jenis_arus = _l('inspection_jenis_arus');

$instalatir = _l('inspection_instalatir');
$inspection_jumlah_mca = _l('inspection_jumlah_mca');
$inspection_jumlah_smoke_detector = _l('inspection_jumlah_smoke_detector');
$inspection_jumlah_alarm_bell = _l('inspection_jumlah_alarm_bell');
$inspection_jumlah_alarm_lamp = _l('inspection_jumlah_alarm_lamp');
$inspection_task_name = 'Tugas Inspeksi';

$task__name = $inspection->inspection_items[$page]['name'];
$task_name = isset($task__name) ? $task__name : 'kosong';

$_tblhtml = '';

switch ($inspection->categories) {
    case 'pubt':
        $tblhtml = <<<EOD
        <style type="text/css">
        .tg-oe14{width:20%; display:inline-block;background-color:#ddd;}
        .tg-1e15{border-bottom: 1px solid black;}
        .tg-oe16{width:2%;}
        label.field-label{display:inline-block; width:20%;}

        </style>
        <table class="tg">
        <tbody>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_task_name</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17">$task_name</td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_nama_pesawat</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_serial_number</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_unit_number</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_type_model</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_capacity</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
        </tbody>
        </table>
        EOD;
    break;
    case 'paa':
        $tblhtml = <<<EOD
        <style type="text/css">
        .tg-oe14{width:20%; display:inline-block;background-color:#ddd;}
        .tg-1e15{border-bottom: 1px solid black;}
        .tg-oe16{width:2%;}
        label.field-label{display:inline-block; width:20%;}

        </style>
        <table class="tg">
        <tbody>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_task_name</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17">$task_name</td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_nama_pesawat</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_serial_number</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_unit_number</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_type_model</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_capacity</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
        </tbody>
        </table>
        EOD;
    break;
    case 'iil':
        $tblhtml = <<<EOD
        <style type="text/css">
        .tg-oe14{width:20%; display:inline-block;background-color:#ddd;}
        .tg-1e15{border-bottom: 1px solid black;}
        .tg-oe16{width:2%;}
        label.field-label{display:inline-block; width:20%;}

        </style>
        <table class="tg">
        <tbody>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_task_name</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17">$task_name</td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_nama_pesawat</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_daya_terpasang</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_sumber_tenaga</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_jenis_arus</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
        </tbody>
        </table>
        EOD;
    break;
    case 'ipp':
        $inspection_instalatir = _l('inspection_instalatir');
        $inspection_penerima = _l('inspection_penerima');
        $inspection_tinggi_tiang_penerima = _l('inspection_tinggi_tiang_penerima');
        $inspection_tinggi_bangunan = _l('inspection_tinggi_bangunan');
        $tblhtml = <<<EOD
            <style type="text/css">
            .tg-oe14{width:20%; display:inline-block;background-color:#ddd;}
            .tg-1e15{border-bottom: 1px solid black;}
            .tg-oe16{width:2%;}
            label.field-label{display:inline-block; width:20%;}

            </style>
            <table class="tg">
                <tbody>
                  <tr class="tg-1e15">
                    <td width ="130" class="tg-oe15">$inspection_task_name</td>
                    <td width ="10" class="tg-oe16">:</td>
                    <td width ="250" class="tg-oe17">$task_name</td>
                  </tr>
                  <tr class="tg-1e15">
                    <td width ="130" class="tg-oe15">$inspection_nama_pesawat</td>
                    <td width ="10" class="tg-oe16">:</td>
                    <td width ="250" class="tg-oe17"></td>
                  </tr>
                  <tr class="tg-1e15">
                    <td width ="130" class="tg-oe15">$inspection_instalatir</td>
                    <td width ="10" class="tg-oe16">:</td>
                    <td width ="250" class="tg-oe17"></td>
                  </tr>
                  <tr class="tg-1e15">
                    <td width ="130" class="tg-oe15">$inspection_penerima</td>
                    <td width ="10" class="tg-oe16">:</td>
                    <td width ="250" class="tg-oe17"></td>
                  </tr>
                  <tr class="tg-1e15">
                    <td width ="130" class="tg-oe15">$inspection_tinggi_tiang_penerima</td>
                    <td width ="10" class="tg-oe16">:</td>
                    <td width ="250" class="tg-oe17"></td>
                  </tr>
                  <tr class="tg-1e15">
                    <td width ="130" class="tg-oe15">$inspection_tinggi_bangunan</td>
                    <td width ="10" class="tg-oe16">:</td>
                    <td width ="250" class="tg-oe17"></td>
                  </tr>
                </tbody>
            </table>
        EOD;
    break;
    case 'lie':
        $tblhtml = <<<EOD
        <style type="text/css">
        .tg-oe14{width:20%; display:inline-block;background-color:#ddd;}
        .tg-1e15{border-bottom: 1px solid black;}
        .tg-oe16{width:2%;}
        label.field-label{display:inline-block; width:20%;}

        </style>
        <table class="tg">
        <tbody>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_task_name</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17">$task_name</td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_nama_pesawat</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_serial_number</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_unit_number</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_type_model</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_capacity</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
        </tbody>
        </table>
        EOD;


    break;
    case 'ptp':
        $tblhtml = <<<EOD
        <style type="text/css">
        .tg-oe14{width:20%; display:inline-block;background-color:#ddd;}
        .tg-1e15{border-bottom: 1px solid black;}
        .tg-oe16{width:2%;}
        label.field-label{display:inline-block; width:20%;}

        </style>
        <table class="tg">
        <tbody>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_task_name</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17">$task_name</td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_nama_pesawat</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_serial_number</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_unit_number</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_type_model</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_capacity</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
        </tbody>
        </table>
        EOD;
    break;
    case 'ipkh':
        $tblhtml = <<<EOD
        <style type="text/css">
        .tg-oe14{width:20%; display:inline-block;background-color:#ddd;}
        .tg-1e15{border-bottom: 1px solid black;}
        .tg-oe16{width:2%;}
        label.field-label{display:inline-block; width:20%;}

        </style>
        <table class="tg">
        <tbody>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_task_name</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17">$task_name</td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_nama_pesawat</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_jumlah_nozzle</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_jumlah_kotak_hydrant</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_jumlah_selang_hydrant</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_kapasitas_air</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
        </tbody>
        </table>
        EOD;
    break;
    case 'ipka':
        $tblhtml = <<<EOD
        <style type="text/css">
        .tg-oe14{width:20%; display:inline-block;background-color:#ddd;}
        .tg-1e15{border-bottom: 1px solid black;}
        .tg-oe16{width:2%;}
        label.field-label{display:inline-block; width:20%;}

        </style>
        <table class="tg">
        <tbody>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_task_name</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17">$task_name</td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_nama_pesawat</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$instalatir</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_jumlah_mca</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_jumlah_smoke_detector</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_jumlah_alarm_bell</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
          <tr class="tg-1e15">
            <td width ="130" class="tg-oe15">$inspection_jumlah_alarm_lamp</td>
            <td width ="10" class="tg-oe16">:</td>
            <td width ="250" class="tg-oe17"></td>
          </tr>
        </tbody>
        </table>
        EOD;

    break;
    case 'pu--bt':

    break;

    default:
        $tblhtml = <<<EOD
        EOD;
    break;
}


$i=1;
$left_info = '<div><strong>'. _l('inspection_members') . '</strong></div>';
foreach($inspection_members as $member){
  $left_info .=  $i.'. ' .$member['firstname'] .' '. $member['lastname']. '<br />';
  $i++;
}

$inspection->assigned_item = get_staff_full_name(get_option('default_inspection_assigned_'.$inspection->categories));

$pdf->SetFont('dejavusans');
pdf_multi_row($left_info, $tblhtml, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm'], true);

$tblhtml = '';

$pemeriksaan_dokumen_t = '&#9744;';
$pemeriksaan_dokumen_f = '&#9744;';
$pemeriksaan_dokumen_n = '&#9744;';


$pemeriksaan_visual_t = '&#9744;';
$pemeriksaan_visual_f = '&#9744;';
$pemeriksaan_visual_n = '&#9744;';

$pemeriksaan_pengaman_t = '&#9744;';
$pemeriksaan_pengaman_f = '&#9744;';
$pemeriksaan_pengaman_n = '&#9744;';

$pengujian_penetrant_t = '&#9744;';
$pengujian_penetrant_f = '&#9744;';
$pengujian_penetrant_n = '&#9744;';

$pengujian_thickness_t = '&#9744;';
$pengujian_thickness_f = '&#9744;';
$pengujian_thickness_n = '&#9744;';

$pengujian_operasional_t = '&#9744;';
$pengujian_operasional_f = '&#9744;';
$pengujian_operasional_n = '&#9744;';

$pengujian_beban_t = '&#9744;';
$pengujian_beban_f = '&#9744;';
$pengujian_beban_n = '&#9744;';
$pengujian_beban = _l('pengujian_beban');

$pengujian_grounding_t = '&#9744;';
$pengujian_grounding_f = '&#9744;';
$pengujian_grounding_n = '&#9744;';
$pengujian_grounding = _l('pengujian_grounding');


$pengujian_pompa_t = '&#9744;';
$pengujian_pompa_f = '&#9744;';
$pengujian_pompa_n = '&#9744;';

$pengujian_tekanan_t = '&#9744;';
$pengujian_tekanan_f = '&#9744;';
$pengujian_tekanan_n = '&#9744;';

$pengujian_daya_pancar_t = '&#9744;';
$pengujian_daya_pancar_f = '&#9744;';
$pengujian_daya_pancar_n = '&#9744;';

$pengujian_pompa = _l('pengujian_pompa');
$pengujian_tekanan = _l('pengujian_tekanan');
$pengujian_daya_pancar = _l('pengujian_daya_pancar');

$pemeriksaan_dokumen = _l('pemeriksaan_dokumen');
$pemeriksaan_visual = _l('pemeriksaan_visual');
$pemeriksaan_pengaman = _l('pemeriksaan_pengaman');
$pengujian_penetrant = _l('pengujian_penetrant');
$pengujian_thickness = _l('pengujian_thickness');
$pengujian_operasional = _l('pengujian_operasional');

$lengkap = _l('lengkap');
$tidak_lengkap = _l('tidak_lengkap');
$tidak_ada = _l('tidak_ada');
$baik = _l('baik');
$tidak_baik = _l('tidak_baik');
$tidak_dilaksanakan = _l('tidak_dilaksanakan');

switch ($inspection->categories) {
    case 'pubt':
        $tblhtml = <<<EOD
        <style>
        .tg  {border-collapse:collapse;border-spacing:0;}
        .tg td{border-color:black;border-style:solid;border-width:1px;}
        table tr{ line-height: 2;}

        </style>
        <table class="table table-bordered">
             <tbody>
                <tr class="tg pemeriksaan_dokumen">
                   <td width="34%">$pemeriksaan_dokumen</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_t</span> $lengkap</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_f</span> $tidak_lengkap</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_n</span> $tidak_ada</td>
                </tr>
                <tr class="tg pemeriksaan_visual">
                   <td width="34%">$pemeriksaan_visual</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pemeriksaan_pengaman">
                   <td width="34%">$pemeriksaan_pengaman</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pengujian_penetrant">
                   <td width="34%">$pengujian_penetrant</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_penetrant_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_penetrant_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_penetrant_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pengujian_thickness">
                   <td width="34%">$pengujian_thickness</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_thickness_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_thickness_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_thickness_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr style='border-bottom:1px solid black;' class="tg pengujian_operasional">
                   <td width="34%">$pengujian_operasional</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_operasional_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg">
                   <td width="34%"></td>
                   <td width="1%"></td>
                   <td width="20%"></td>
                   <td width="20%"></td>
                   <td width="22%"></td>
                </tr>
             </tbody>
        </table>
        EOD;
        break;
    
    case 'paa':

        $tblhtml = <<<EOD
        <style>
        .tg  {border-collapse:collapse;border-spacing:0;}
        .tg td{border-color:black;border-style:solid;border-width:1px;}
        table tr{ line-height: 2;}

        </style>
        <table class="table table-bordered">
             <tbody>
                <tr class="tg pemeriksaan_dokumen">
                   <td width="34%">$pemeriksaan_dokumen</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_t</span> $lengkap</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_f</span> $tidak_lengkap</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_n</span> $tidak_ada</td>
                </tr>
                <tr class="tg pemeriksaan_visual">
                   <td width="34%">$pemeriksaan_visual</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pemeriksaan_pengaman">
                   <td width="34%">$pemeriksaan_pengaman</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pengujian_beban">
                   <td width="34%">$pengujian_beban</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_beban_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_beban_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_beban_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pengujian_penetrant">
                   <td width="34%">$pengujian_penetrant</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_penetrant_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_penetrant_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_penetrant_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr style='border-bottom:1px solid black;' class="tg pengujian_operasional">
                   <td width="34%">$pengujian_operasional</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_operasional_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg">
                   <td width="34%"></td>
                   <td width="1%"></td>
                   <td width="20%"></td>
                   <td width="20%"></td>
                   <td width="22%"></td>
                </tr>
             </tbody>
          </table>
        EOD;
        break;

    case 'ptp':
        $tblhtml = <<<EOD
        <style>
        .tg  {border-collapse:collapse;border-spacing:0;}
        .tg td{border-color:black;border-style:solid;border-width:1px;}
        table tr{ line-height: 2;}

        </style>
        <table class="table table-bordered">
             <tbody>
                <tr class="tg pemeriksaan_dokumen">
                   <td width="34%">$pemeriksaan_dokumen</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_t</span> $lengkap</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_f</span> $tidak_lengkap</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_n</span> $tidak_ada</td>
                </tr>
                <tr class="tg pemeriksaan_visual">
                   <td width="34%">$pemeriksaan_visual</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pemeriksaan_pengaman">
                   <td width="34%">$pemeriksaan_pengaman</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pengujian_grounding">
                   <td width="34%">$pengujian_grounding</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_grounding_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_grounding_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_grounding_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pengujian_operasional">
                   <td width="34%">$pengujian_operasional</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_operasional_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg">
                   <td width="34%"></td>
                   <td width="1%"></td>
                   <td width="20%"></td>
                   <td width="20%"></td>
                   <td width="22%"></td>
                </tr>
             </tbody>
          </table>
        EOD;
        break;

    case 'iil':
        $pengujian_grounding_t = '&#9744;';
        $pengujian_grounding_f = '&#9744;';
        $pengujian_grounding_n = '&#9744;';
        $pengujian_grounding = _l('pengujian_grounding');

        $pengujian_thermal_infrared_t = '&#9744;';
        $pengujian_thermal_infrared_f = '&#9744;';
        $pengujian_thermal_infrared_n = '&#9744;';
        $pengujian_thermal_infrared = _l('pengujian_thermal_infrared');

        $tblhtml = <<<EOD
        <style>
        .tg  {border-collapse:collapse;border-spacing:0;}
        .tg td{border-color:black;border-style:solid;border-width:1px;}
        table tr{ line-height: 2;}

        </style>
        <table class="table table-bordered">
             <tbody>
                <tr class="tg pemeriksaan_dokumen">
                   <td width="34%">$pemeriksaan_dokumen</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_t</span> $lengkap</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_f</span> $tidak_lengkap</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_n</span> $tidak_ada</td>
                </tr>
                <tr class="tg pemeriksaan_visual">
                   <td width="34%">$pemeriksaan_visual</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pemeriksaan_pengaman">
                   <td width="34%">$pemeriksaan_pengaman</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pengujian_grounding">
                   <td width="34%">$pengujian_grounding</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_grounding_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_grounding_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_grounding_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pengujian_thermal_infrared">
                   <td width="34%">$pengujian_thermal_infrared</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_thermal_infrared_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_thermal_infrared_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_thermal_infrared_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pengujian_operasional">
                   <td width="34%">$pengujian_operasional</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_operasional_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg">
                   <td width="34%"></td>
                   <td width="1%"></td>
                   <td width="20%"></td>
                   <td width="20%"></td>
                   <td width="22%"></td>
                </tr>
             </tbody>
          </table>
        EOD;
        break;

    case 'ipp':
        $tblhtml = <<<EOD
        <style>
        .tg  {border-collapse:collapse;border-spacing:0;}
        .tg td{border-color:black;border-style:solid;border-width:1px;}
        table tr{ line-height: 2;}

        </style>
        <table class="table table-bordered">
             <tbody>
                <tr class="tg pemeriksaan_dokumen">
                   <td width="34%">$pemeriksaan_dokumen</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_t</span> $lengkap</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_f</span> $tidak_lengkap</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_n</span> $tidak_ada</td>
                </tr>
                <tr class="tg pemeriksaan_visual">
                   <td width="34%">$pemeriksaan_visual</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pemeriksaan_pengaman">
                   <td width="34%">$pemeriksaan_pengaman</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pengujian_grounding">
                   <td width="34%">$pengujian_grounding</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_grounding_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_grounding_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_grounding_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg">
                   <td width="34%"></td>
                   <td width="1%"></td>
                   <td width="20%"></td>
                   <td width="20%"></td>
                   <td width="22%"></td>
                </tr>
             </tbody>
          </table>
        EOD;
        break;
    case 'lie':
        $tblhtml = <<<EOD
        <style>
        .tg  {border-collapse:collapse;border-spacing:0;}
        .tg td{border-color:black;border-style:solid;border-width:1px;}
        table tr{ line-height: 2;}

        </style>
        <table class="table table-bordered">
             <tbody>
                <tr class="tg pemeriksaan_dokumen">
                   <td width="34%">$pemeriksaan_dokumen</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_t</span> $lengkap</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_f</span> $tidak_lengkap</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_n</span> $tidak_ada</td>
                </tr>
                <tr class="tg pemeriksaan_visual">
                   <td width="34%">$pemeriksaan_visual</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_n</span> $tidak_ada</td>
                </tr>
                <tr class="tg pemeriksaan_pengaman">
                   <td width="34%">$pemeriksaan_pengaman</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_n</span> $tidak_ada</td>
                </tr>
                <tr class="tg pengujian_beban">
                   <td width="34%">$pengujian_beban</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_beban_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_beban_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_beban_n</span> $tidak_ada</td>
                </tr>
                <tr class="tg pengujian_penetrant">
                   <td width="34%">$pengujian_penetrant</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_penetrant_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_penetrant_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_penetrant_n</span> $tidak_ada</td>
                </tr>
                <tr class="tg pengujian_operasional">
                   <td width="34%">$pengujian_operasional</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_operasional_n</span> $tidak_ada</td>
                </tr>
                <tr class="tg">
                   <td width="34%"></td>
                   <td width="1%"></td>
                   <td width="20%"></td>
                   <td width="20%"></td>
                   <td width="22%"></td>
                </tr>
             </tbody>
          </table>
        EOD;
        break;
    
    case 'ipkh':
        $tblhtml = <<<EOD
        <style>
        .tg  {border-collapse:collapse;border-spacing:0;}
        .tg td{border-color:black;border-style:solid;border-width:1px;}
        table tr{ line-height: 2;}

        </style>
        <table class="table table-bordered">
             <tbody>
                <tr class="tg pemeriksaan_dokumen">
                   <td width="34%">$pemeriksaan_dokumen</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_t</span> $lengkap</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_f</span> $tidak_lengkap</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_n</span> $tidak_ada</td>
                </tr>
                <tr class="tg pemeriksaan_visual">
                   <td width="34%">$pemeriksaan_visual</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pemeriksaan_pengaman">
                   <td width="34%">$pemeriksaan_pengaman</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pengujian_pompa">
                   <td width="34%">$pengujian_pompa</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_pompa_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_pompa_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_pompa_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pengujian_tekanan">
                   <td width="34%">$pengujian_tekanan</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_tekanan_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_tekanan_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_tekanan_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pengujian_daya_pancar">
                   <td width="34%">$pengujian_daya_pancar</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_daya_pancar_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_daya_pancar_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_daya_pancar_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pengujian_operasional">
                   <td width="34%">$pengujian_operasional</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_operasional_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg">
                   <td width="34%"></td>
                   <td width="1%"></td>
                   <td width="20%"></td>
                   <td width="20%"></td>
                   <td width="22%"></td>
                </tr>
             </tbody>
          </table>
        EOD;

    break;

    case 'ipka':
        $tblhtml = <<<EOD
        <style>
        .tg  {border-collapse:collapse;border-spacing:0;}
        .tg td{border-color:black;border-style:solid;border-width:1px;}
        table tr{ line-height: 2;}

        </style>
        <table class="table table-bordered">
             <tbody>
                <tr class="tg pemeriksaan_dokumen">
                   <td width="34%">$pemeriksaan_dokumen</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_t</span> $lengkap</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_f</span> $tidak_lengkap</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_n</span> $tidak_ada</td>
                </tr>
                <tr class="tg pemeriksaan_visual">
                   <td width="34%">$pemeriksaan_visual</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pemeriksaan_pengaman">
                   <td width="34%">$pemeriksaan_pengaman</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg pengujian_operasional">
                   <td width="34%">$pengujian_operasional</td>
                   <td width="1%">:</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_t</span> $baik</td>
                   <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_f</span> $tidak_baik</td>
                   <td width="22%"><span style='font-size:2.5rem;'>$pengujian_operasional_n</span> $tidak_dilaksanakan</td>
                </tr>
                <tr class="tg">
                   <td width="34%"></td>
                   <td width="1%"></td>
                   <td width="20%"></td>
                   <td width="20%"></td>
                   <td width="22%"></td>
                </tr>
             </tbody>
          </table>
        EOD;
    
    break;

    case '_ptp':
        break;

    default:
        // code...
        break;
}


// set default font subsetting mode
$pdf->setFontSubsetting(true);

$pdf->SetFont('dejavusans');

$pdf->writeHTML($tblhtml, true, false, false, false, '');

$assigned_info = '<div style="text-align:center;">';
$assigned_info .= get_option('invoice_company_name');
$assigned_info .= '</div>';

$acceptance_path = <<<EOF
    <img src="$inspection->acceptance_path">
EOF;
$client_info = '<div style="text-align:center;">';
$client_info .= $inspection->client_company;


if ($inspection->signed != 0) {
    $client_info .= _l('inspection_signed_by') . ": {$inspection->acceptance_firstname} {$inspection->acceptance_lastname}" . '<br />';
    $client_info .= _l('inspection_signed_date') . ': ' . _dt($inspection->acceptance_date_string) . '<br />';
    $client_info .= _l('inspection_signed_ip') . ": {$inspection->acceptance_ip}" . '<br />';

    $client_info .= $acceptance_path;
}
$client_info .= '</div>';

$left_info  = $swap == '1' ? $client_info : $assigned_info;
$right_info = $swap == '1' ? $assigned_info : $client_info;
pdf_multi_row($left_info, $right_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$data = $inspection->client_company. "\r\n";
$data .= $inspection_number .'-'. $item['task_id']. "\r\n";
$data .= $task_name ."\r\n";

// define barcode style// set style for barcode
$style = array(
    'border' => 0,
    'vpadding' => 'auto',
    'hpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255)
    'module_width' => 1, // width of a single module in points
    'module_height' => 1 // height of a single module in points
);

// QRCODE,L : QR-CODE Low error correction
$pdf->write2DBarcode($data, 'QRCODE,M', 37, $pdf->GetY(), 40, 40, $style, 'N');


$assigned_info = '<div style="text-align:center;">';
if ($inspection->assigned_item != '' && get_option('show_assigned_on_inspections') == 1) {
    $assigned_info .= $inspection->assigned_item;
}
$assigned_info .= '</div>';

pdf_multi_row($assigned_info, '', $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$regulasi = '';
$rline = 2;
$tline = 8;
if (!empty($inspection->equipment['regulasi'])) {
    $regulasi = $inspection->equipment['regulasi'];
    $rline = 2;
    $tline = 2;
}
    $pdf->Ln($rline);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('equipment_regulasi'), 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    //$pdf->Ln(1);
    $pdf->writeHTMLCell('', '', '', '', format_unorderedText($regulasi), 0, 1, false, true, 'L', true);

$temuan = '';
$kline = 6;
if (!empty($inspection->equipment['temuan'])) {
    $temuan = $inspection->equipment['temuan'];
    $tline = 2;
    $kline = 2;
}
    $pdf->Ln($tline);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('equipment_temuan'), 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->writeHTMLCell('', '', '', '', format_unorderedText($temuan), 0, 1, false, true, 'L', true);

$kesimpulan = '';
$sline = 6;
if (!empty($inspection->equipment['kesimpulan'])) {
    $kesimpulan = $inspection->equipment['kesimpulan'];
    $kline = 4;
    $sline = 2;
}
    $pdf->Ln($kline);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('equipment_kesimpulan'), 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->writeHTMLCell('', '', '', '', format_unorderedText($kesimpulan), 0, 1, false, true, 'L', true);

    $pdf->Ln($sline);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('terms_and_conditions') . ":", 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    
if (!empty($inspection->terms) && ($inspection->status != 1)) {
    $pdf->Ln(1);
    $pdf->writeHTMLCell('', '', '', '', format_unorderedText($inspection->terms), 0, 1, false, true, 'L', true);
}



    $page++;
    if($page < $pages){
        // add a page
        $pdf->AddPage();
    }
}