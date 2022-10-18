<?php

defined('BASEPATH') or exit('No direct script access allowed');

$dimensions = $pdf->getPageDimensions();

// set auto page breaks
$pdf->SetAutoPageBreak(true, 5);
$pdf->SetFont('dejavusans');

$info_right_column = '';
$info_left_column  = '';

$info_right_column .= '<span style="font-weight:bold;font-size:27px;">' . _l('inspection_pdf_heading') . '</span><br />';
$info_right_column .= '<b style="color:#4e4e4e;"># ' . $inspection_number . '</b>';

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
$pdf->ln(2);
// print a block of text using Write()
$pdf->Write(0, $txt, '', 0, 'L', true, 0, false, false, 0);


// The Table
$pdf->Ln(hooks()->apply_filters('pdf_info_and_table_separator', 2));

$company = get_inspection_company_by_clientid($inspection->clientid);
$address = get_inspection_company_address($inspection->id);
$nama_pesawat = isset($inspection->equipment['nama_pesawat']) ? $inspection->equipment['nama_pesawat'] :'';
$nomor_seri = isset($inspection->equipment['nomor_seri']) ? $inspection->equipment['nomor_seri'] : '';
$nomor_unit = isset($inspection->equipment['nomor_unit']) ? $inspection->equipment['nomor_unit'] : '';
$type_model = isset($inspection->equipment['type_model']) ? $inspection->equipment['type_model'] : '';

$kapasitas = isset($inspection->equipment['kapasitas']) ? $inspection->equipment['kapasitas'] : '';

$inspection_company = _l('inspection_company_name');
$inspection_address = _l('inspection_address');
$inspection_jenis_pesawat = _l('inspection_jenis_pesawat');
$inspection_nama_pesawat = _l('inspection_nama_pesawat');
$inspection_unit_number = _l('inspection_unit_number');
$inspection_type_model = _l('inspection_type_model');
$inspection_serial_number = _l('inspection_serial_number');
$inspection_capacity = _l('inspection_capacity');

$_tblhtml = '';
$_tblhtml .= '
  <tr class="tg-1e15">
    <td width ="130" class="tg-oe15">' . _l('inspection_nama_pesawat') . '</td>
    <td width ="10" class="tg-oe16">:</td>
    <td width ="170" class="tg-oe17">' .$nama_pesawat. '</td>
  </tr>';


$tempat_pembuatan = isset($inspection->equipment['tempat_pembuatan']) ? $inspection->equipment['tempat_pembuatan'] : FALSE;
if($tempat_pembuatan){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_tempat_pembuatan') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$tempat_pembuatan. '</td>
      </tr>';
}
$pabrik_pembuat = isset($inspection->equipment['pabrik_pembuat']) ? $inspection->equipment['pabrik_pembuat'] : FALSE;
if($pabrik_pembuat){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_pabrik_pembuat') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$pabrik_pembuat. '</td>
      </tr>';
}
$nomor_seri = isset($inspection->equipment['nomor_seri']) ? $inspection->equipment['nomor_seri'] : FALSE;
if($nomor_seri){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_nomor_seri') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$nomor_seri. '</td>
      </tr>';
}
$nomor_unit = isset($inspection->equipment['nomor_unit']) ? $inspection->equipment['nomor_unit'] : FALSE;
if($nomor_unit){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_nomor_unit') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$nomor_unit. '</td>
      </tr>';
}
$type_model = isset($inspection->equipment['type_model']) ? $inspection->equipment['type_model'] : FALSE;
if($type_model){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_type_model') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$type_model. '</td>
      </tr>';
}
$kapasitas = isset($inspection->equipment['kapasitas']) ? $inspection->equipment['kapasitas'] : FALSE;
if($kapasitas){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_kapasitas') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$kapasitas. '</td>
      </tr>';
}

$daya_terpasang = isset($inspection->equipment['daya_terpasang']) ? $inspection->equipment['daya_terpasang'] : FALSE;
if($daya_terpasang){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_daya_terpasang') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$daya_terpasang. '</td>
      </tr>';
}
$sumber_tenaga = isset($inspection->equipment['sumber_tenaga']) ? $inspection->equipment['sumber_tenaga'] : FALSE;
if($sumber_tenaga){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_sumber_tenaga') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$sumber_tenaga. '</td>
      </tr>';
}
$jenis_arus = isset($inspection->equipment['jenis_arus']) ? $inspection->equipment['jenis_arus'] : FALSE;
if($jenis_arus){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_jenis_arus') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$jenis_arus. '</td>
      </tr>';
}
$instalatir = isset($inspection->equipment['instalatir']) ? $inspection->equipment['instalatir'] : FALSE;
if($instalatir){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_instalatir') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$instalatir. '</td>
      </tr>';
}
$pembumian = isset($inspection->equipment['pembumian']) ? $inspection->equipment['pembumian'] : FALSE;
if($pembumian){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_pembumian') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$pembumian. '</td>
      </tr>';
}
$penerima = isset($inspection->equipment['penerima']) ? $inspection->equipment['penerima'] : FALSE;
if($penerima){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_penerima') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$penerima. '</td>
      </tr>';
}
$tinggi_tiang_penerima = isset($inspection->equipment['tinggi_tiang_penerima']) ? $inspection->equipment['tinggi_tiang_penerima'] : FALSE;
if($tinggi_tiang_penerima){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_tinggi_tiang_penerima') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$tinggi_tiang_penerima. '</td>
      </tr>';
}
$tinggi_bangunan = isset($inspection->equipment['tinggi_bangunan']) ? $inspection->equipment['tinggi_bangunan'] : FALSE;
if($tinggi_bangunan){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_tinggi_bangunan') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$tinggi_bangunan. '</td>
      </tr>';
}
$jumlah_mca = isset($inspection->equipment['jumlah_mca']) ? $inspection->equipment['jumlah_mca'] : FALSE;
if($jumlah_mca){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_jumlah_mca') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$jumlah_mca. '</td>
      </tr>';
}
$jumlah_smoke_detector = isset($inspection->equipment['jumlah_smoke_detector']) ? $inspection->equipment['jumlah_smoke_detector'] : FALSE;
if($jumlah_smoke_detector){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_jumlah_smoke_detector') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$jumlah_smoke_detector. '</td>
      </tr>';
}
$jumlah_alarm_bell = isset($inspection->equipment['jumlah_alarm_bell']) ? $inspection->equipment['jumlah_alarm_bell'] : FALSE;
if($jumlah_alarm_bell){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_jumlah_alarm_bell') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$jumlah_alarm_bell. '</td>
      </tr>';
}
$jumlah_alarm_lamp = isset($inspection->equipment['jumlah_alarm_lamp']) ? $inspection->equipment['jumlah_alarm_lamp'] : FALSE;
if($jumlah_alarm_lamp){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_jumlah_alarm_lamp') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$jumlah_alarm_lamp. '</td>
      </tr>';
}

$jumlah_kotak_hydrant = isset($inspection->equipment['jumlah_kotak_hydrant']) ? $inspection->equipment['jumlah_kotak_hydrant'] : FALSE;
if($jumlah_kotak_hydrant){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_jumlah_kotak_hydrant') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$jumlah_kotak_hydrant. '</td>
      </tr>';
}
$jumlah_selang_hydrant = isset($inspection->equipment['jumlah_selang_hydrant']) ? $inspection->equipment['jumlah_selang_hydrant'] : FALSE;
if($jumlah_selang_hydrant){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_jumlah_selang_hydrant') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$jumlah_selang_hydrant. '</td>
      </tr>';
}
$jumlah_nozzle = isset($inspection->equipment['jumlah_nozzle']) ? $inspection->equipment['jumlah_nozzle'] : FALSE;
if($jumlah_nozzle){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_jumlah_nozzle') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$jumlah_nozzle. '</td>
      </tr>';
}
$kapasitas_air = isset($inspection->equipment['kapasitas_air']) ? $inspection->equipment['kapasitas_air'] : FALSE;
if($kapasitas_air){
    $_tblhtml .= '
      <tr class="tg-1e15">
        <td width ="130" class="tg-oe15">' . _l('inspection_kapasitas_air') . '</td>
        <td width ="10" class="tg-oe16">:</td>
        <td width ="170" class="tg-oe17">' .$kapasitas_air. '</td>
      </tr>';
}
$tblhtml = <<<EOD
<style type="text/css">
.tg-oe14{width:20%; display:inline-block;background-color:#ddd;}
.tg-1e15{border-bottom: 1px solid black;}
.tg-oe16{width:2%;}
label.field-label{display:inline-block; width:20%;}

</style>
<table class="tg">
<tbody>
  $_tblhtml
</tbody>
</table>
EOD;

$left_info ='';
$tag = $inspection->tag;
$equipment_type = isset($tag) ? $tag : '';
$left_info .= '<div><strong>'. _l('equipment_type') . '</strong></div>';
$left_info .= $equipment_type;

$task_name = isset($inspection->task->name) ? $inspection->task->name : '';
$left_info .= '<div><strong>'. _l('task') . '</strong></div>';
$left_info .= $task_name;

$pdf->SetFont('dejavusans');
pdf_multi_row($left_info, $tblhtml, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm'], true);

$pdf->ln(2);

$_tblhtml = '';

$lengkap = _l('lengkap');
$tidak_lengkap = _l('tidak_lengkap');
$tidak_ada = _l('tidak_ada');
$tidak_dilaksanakan = _l('tidak_dilaksanakan');
$baik = _l('baik');
$tidak_baik = _l('tidak_baik');

$pemeriksaan_dokumen_t = '&#9744;';
$pemeriksaan_dokumen_f = '&#9744;';
$pemeriksaan_dokumen_n = '&#9744;';
if(isset($inspection->equipment["pemeriksaan_dokumen"])){
    if($inspection->equipment["pemeriksaan_dokumen"] == 1){
        $pemeriksaan_dokumen_t = "&#9745;";
    }
    if($inspection->equipment["pemeriksaan_dokumen"] == 2){
        $pemeriksaan_dokumen_f = "&#9745;";
    }
    if($inspection->equipment["pemeriksaan_dokumen"] == 0){
        $pemeriksaan_dokumen_n = "&#9745;";
    }

    $_tblhtml .= '<tr class="tg pemeriksaan_dokumen">
           <td width="34%">'._l('pemeriksaan_dokumen').'</td>
           <td width="1%">:</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pemeriksaan_dokumen_t .'</span>'. $lengkap .'</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pemeriksaan_dokumen_f .'</span>'. $tidak_lengkap .'</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pemeriksaan_dokumen_n .'</span>'. $tidak_ada .'</td>
        </tr>';
}

$pemeriksaan_visual_t = '&#9744;';
$pemeriksaan_visual_f = '&#9744;';
$pemeriksaan_visual_n = '&#9744;';
if(isset($inspection->equipment["pemeriksaan_visual"])){
    if($inspection->equipment["pemeriksaan_visual"] == 1){
        $pemeriksaan_visual_t = "&#9745;";
    }
    if($inspection->equipment["pemeriksaan_visual"] == 2){
        $pemeriksaan_visual_f = "&#9745;";
    }
    if($inspection->equipment["pemeriksaan_visual"] == 0){
        $pemeriksaan_visual_n = "&#9745;";
    }

    $_tblhtml .= '<tr class="tg pemeriksaan_visual">
           <td width="34%">'._l('pemeriksaan_visual').'</td>
           <td width="1%">:</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pemeriksaan_visual_t .'</span>'. $baik .'</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pemeriksaan_visual_f .'</span>'. $tidak_baik .'</td>
           <td width="22%"><span style="font-size:12.5rem;">'. $pemeriksaan_visual_n .'</span>'. $tidak_dilaksanakan .'</td>
        </tr>';
}

$pemeriksaan_pengaman_t = '&#9744;';
$pemeriksaan_pengaman_f = '&#9744;';
$pemeriksaan_pengaman_n = '&#9744;';
if(isset($inspection->equipment["pemeriksaan_pengaman"])){
    if($inspection->equipment["pemeriksaan_pengaman"] == 1){
        $pemeriksaan_pengaman_t = "&#9745;";
    }
    if($inspection->equipment["pemeriksaan_pengaman"] == 2){
        $pemeriksaan_pengaman_f = "&#9745;";
    }
    if($inspection->equipment["pemeriksaan_pengaman"] == 0){
        $pemeriksaan_pengaman_n = "&#9745;";
    }

    $_tblhtml .= '<tr class="tg pemeriksaan_pengaman">
           <td width="34%">'._l('pemeriksaan_pengaman').'</td>
           <td width="1%">:</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pemeriksaan_pengaman_t .'</span>'. $baik .'</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pemeriksaan_pengaman_f .'</span>'. $tidak_baik .'</td>
           <td width="22%"><span style="font-size:12.5rem;">'. $pemeriksaan_pengaman_n .'</span>'. $tidak_dilaksanakan .'</td>
        </tr>';
}

$pengujian_beban_t = '&#9744;';
$pengujian_beban_f = '&#9744;';
$pengujian_beban_n = '&#9744;';
if(isset($inspection->equipment["pengujian_beban"])){
    if($inspection->equipment["pengujian_beban"] == 1){
        $pengujian_beban_t = "&#9745;";
    }
    if($inspection->equipment["pengujian_beban"] == 2){
        $pengujian_beban_f = "&#9745;";
    }
    if($inspection->equipment["pengujian_beban"] == 0){
        $pengujian_beban_n = "&#9745;";
    }

    $_tblhtml .= '<tr class="tg pengujian_beban">
           <td width="34%">'._l('pengujian_beban').'</td>
           <td width="1%">:</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_beban_t .'</span>'. $baik .'</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_beban_f .'</span>'. $tidak_baik .'</td>
           <td width="22%"><span style="font-size:12.5rem;">'. $pengujian_beban_n .'</span>'. $tidak_dilaksanakan .'</td>
        </tr>';
}

$pengujian_penetrant_t = '&#9744;';
$pengujian_penetrant_f = '&#9744;';
$pengujian_penetrant_n = '&#9744;';
if(isset($inspection->equipment["pengujian_penetrant"])){
    if($inspection->equipment["pengujian_penetrant"] == 1){
        $pengujian_penetrant_t = "&#9745;";
    }
    if($inspection->equipment["pengujian_penetrant"] == 2){
        $pengujian_penetrant_f = "&#9745;";
    }
    if($inspection->equipment["pengujian_penetrant"] == 0){
        $pengujian_penetrant_n = "&#9745;";
    }

    $_tblhtml .= '<tr class="tg pengujian_penetrant">
           <td width="34%">'. _l('pengujian_penetrant').'</td>
           <td width="1%">:</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_penetrant_t .'</span>'. _l('baik') .'</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_penetrant_f .'</span>'. _l('tidak_baik') .'</td>
           <td width="22%"><span style="font-size:12.5rem;">'. $pengujian_penetrant_n .'</span>'. _l('tidak_dilaksanakan') .'</td>
        </tr>';
}

$pengujian_thickness_t = '&#9744;';
$pengujian_thickness_f = '&#9744;';
$pengujian_thickness_n = '&#9744;';
if(isset($inspection->equipment["pengujian_thickness"])){
    if($inspection->equipment["pengujian_thickness"] == 1){
        $pengujian_thickness_t = "&#9745;";
    }
    if($inspection->equipment["pengujian_thickness"] == 2){
        $pengujian_thickness_f = "&#9745;";
    }
    if($inspection->equipment["pengujian_thickness"] == 0){
        $pengujian_thickness_n = "&#9745;";
    }

    $_tblhtml .= '<tr class="tg pengujian_thickness">
           <td width="34%">'. _l('pengujian_thickness').'</td>
           <td width="1%">:</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_thickness_t .'</span>'. _l('baik') .'</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_thickness_f .'</span>'. _l('tidak_baik') .'</td>
           <td width="22%"><span style="font-size:12.5rem;">'. $pengujian_thickness_n .'</span>'. _l('tidak_dilaksanakan') .'</td>
        </tr>';
}

$pengujian_hydrotest_t = '&#9744;';
$pengujian_hydrotest_f = '&#9744;';
$pengujian_hydrotest_n = '&#9744;';
if(isset($inspection->equipment["pengujian_hydrotest"])){
    if($inspection->equipment["pengujian_hydrotest"] == 1){
        $pengujian_hydrotest_t = "&#9745;";
    }
    if($inspection->equipment["pengujian_hydrotest"] == 2){
        $pengujian_hydrotest_f = "&#9745;";
    }
    if($inspection->equipment["pengujian_hydrotest"] == 0){
        $pengujian_hydrotest_n = "&#9745;";
    }

    $_tblhtml .= '<tr class="tg pengujian_hydrotest">
           <td width="34%">'. _l('pengujian_hydrotest').'</td>
           <td width="1%">:</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_hydrotest_t .'</span>'. $baik .'</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_hydrotest_f .'</span>'. $tidak_baik .'</td>
           <td width="22%"><span style="font-size:12.5rem;">'. $pengujian_hydrotest_n .'</span>'. $tidak_dilaksanakan .'</td>
        </tr>';
}

$pengujian_grounding_t = '&#9744;';
$pengujian_grounding_f = '&#9744;';
$pengujian_grounding_n = '&#9744;';
if(isset($inspection->equipment["pengujian_grounding"])){
    if($inspection->equipment["pengujian_grounding"] == 1){
        $pengujian_grounding_t = "&#9745;";
    }
    if($inspection->equipment["pengujian_grounding"] == 2){
        $pengujian_grounding_f = "&#9745;";
    }
    if($inspection->equipment["pengujian_grounding"] == 0){
        $pengujian_grounding_n = "&#9745;";
    }

    $_tblhtml .= '<tr class="tg pengujian_grounding">
           <td width="34%">'. _l('pengujian_grounding').'</td>
           <td width="1%">:</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_grounding_t .'</span>'. _l('baik') .'</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_grounding_f .'</span>'. _l('tidak_baik') .'</td>
           <td width="22%"><span style="font-size:12.5rem;">'. $pengujian_grounding_n .'</span>'. _l('tidak_dilaksanakan') .'</td>
        </tr>';
}

$pengujian_thermal_infrared_t = '&#9744;';
$pengujian_thermal_infrared_f = '&#9744;';
$pengujian_thermal_infrared_n = '&#9744;';
if(isset($inspection->equipment["pengujian_thermal_infrared"])){
    if($inspection->equipment["pengujian_thermal_infrared"] == 1){
        $pengujian_thermal_infrared_t = "&#9745;";
    }
    if($inspection->equipment["pengujian_thermal_infrared"] == 2){
        $pengujian_thermal_infrared_f = "&#9745;";
    }
    if($inspection->equipment["pengujian_thermal_infrared"] == 0){
        $pengujian_thermal_infrared_n = "&#9745;";
    }

    $_tblhtml .= '<tr class="tg pengujian_thermal_infrared">
           <td width="34%">'. _l('pengujian_thermal_infrared').'</td>
           <td width="1%">:</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_thermal_infrared_t .'</span>'. _l('baik') .'</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_thermal_infrared_f .'</span>'. _l('tidak_baik') .'</td>
           <td width="22%"><span style="font-size:12.5rem;">'. $pengujian_thermal_infrared_n .'</span>'. _l('tidak_dilaksanakan') .'</td>
        </tr>';
}

$pengujian_kapasitas_hantar_t = '&#9744;';
$pengujian_kapasitas_hantar_f = '&#9744;';
$pengujian_kapasitas_hantar_n = '&#9744;';
if(isset($inspection->equipment["pengujian_kapasitas_hantar"])){
    if($inspection->equipment["pengujian_kapasitas_hantar"] == 1){
        $pengujian_kapasitas_hantar_t = "&#9745;";
    }
    if($inspection->equipment["pengujian_kapasitas_hantar"] == 2){
        $pengujian_kapasitas_hantar_f = "&#9745;";
    }
    if($inspection->equipment["pengujian_kapasitas_hantar"] == 0){
        $pengujian_kapasitas_hantar_n = "&#9745;";
    }

    $_tblhtml .= '<tr class="tg pengujian_kapasitas_hantar">
           <td width="34%">'. _l('pengujian_kapasitas_hantar').'</td>
           <td width="1%">:</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_kapasitas_hantar_t .'</span>'. _l('baik') .'</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_kapasitas_hantar_f .'</span>'. _l('tidak_baik') .'</td>
           <td width="22%"><span style="font-size:12.5rem;">'. $pengujian_kapasitas_hantar_n .'</span>'. _l('tidak_dilaksanakan') .'</td>
        </tr>';
}

$pengujian_pompa_t = '&#9744;';
$pengujian_pompa_f = '&#9744;';
$pengujian_pompa_n = '&#9744;';
if(isset($inspection->equipment["pengujian_pompa"])){
    if($inspection->equipment["pengujian_pompa"] == 1){
        $pengujian_pompa_t = "&#9745;";
    }
    if($inspection->equipment["pengujian_pompa"] == 2){
        $pengujian_pompa_f = "&#9745;";
    }
    if($inspection->equipment["pengujian_pompa"] == 0){
        $pengujian_pompa_n = "&#9745;";
    }

    $_tblhtml .= '<tr class="tg pengujian_pompa">
           <td width="34%">'. _l('pengujian_pompa').'</td>
           <td width="1%">:</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_pompa_t .'</span>'. _l('baik') .'</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_pompa_f .'</span>'. _l('tidak_baik') .'</td>
           <td width="22%"><span style="font-size:12.5rem;">'. $pengujian_pompa_n .'</span>'. _l('tidak_dilaksanakan') .'</td>
        </tr>';
}

$pengujian_tekanan_t = '&#9744;';
$pengujian_tekanan_f = '&#9744;';
$pengujian_tekanan_n = '&#9744;';
if(isset($inspection->equipment["pengujian_tekanan"])){
    if($inspection->equipment["pengujian_tekanan"] == 1){
        $pengujian_tekanan_t = "&#9745;";
    }
    if($inspection->equipment["pengujian_tekanan"] == 2){
        $pengujian_tekanan_f = "&#9745;";
    }
    if($inspection->equipment["pengujian_tekanan"] == 0){
        $pengujian_tekanan_n = "&#9745;";
    }

    $_tblhtml .= '<tr class="tg pengujian_tekanan">
           <td width="34%">'. _l('pengujian_tekanan').'</td>
           <td width="1%">:</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_tekanan_t .'</span>'. _l('baik') .'</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_tekanan_f .'</span>'. _l('tidak_baik') .'</td>
           <td width="22%"><span style="font-size:12.5rem;">'. $pengujian_tekanan_n .'</span>'. _l('tidak_dilaksanakan') .'</td>
        </tr>';
}

$pengujian_daya_pancar_t = '&#9744;';
$pengujian_daya_pancar_f = '&#9744;';
$pengujian_daya_pancar_n = '&#9744;';
if(isset($inspection->equipment["pengujian_daya_pancar"])){
    if($inspection->equipment["pengujian_daya_pancar"] == 1){
        $pengujian_daya_pancar_t = "&#9745;";
    }
    if($inspection->equipment["pengujian_daya_pancar"] == 2){
        $pengujian_daya_pancar_f = "&#9745;";
    }
    if($inspection->equipment["pengujian_daya_pancar"] == 0){
        $pengujian_daya_pancar_n = "&#9745;";
    }

    $_tblhtml .= '<tr class="tg pengujian_daya_pancar">
           <td width="34%">'. _l('pengujian_daya_pancar').'</td>
           <td width="1%">:</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_daya_pancar_t .'</span>'. _l('baik') .'</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_daya_pancar_f .'</span>'. _l('tidak_baik') .'</td>
           <td width="22%"><span style="font-size:12.5rem;">'. $pengujian_daya_pancar_n .'</span>'. _l('tidak_dilaksanakan') .'</td>
        </tr>';
}

$pengujian_operasional_t = '&#9744;';
$pengujian_operasional_f = '&#9744;';
$pengujian_operasional_n = '&#9744;';
if(isset($inspection->equipment["pengujian_operasional"])){
    if($inspection->equipment["pengujian_operasional"] == 1){
        $pengujian_operasional_t = "&#9745;";
    }
    if($inspection->equipment["pengujian_operasional"] == 2){
        $pengujian_operasional_f = "&#9745;";
    }
    if($inspection->equipment["pengujian_operasional"] == 0){
        $pengujian_operasional_n = "&#9745;";
    }

    $_tblhtml .= '<tr class="tg pengujian_operasional">
           <td width="34%">'._l('pengujian_operasional').'</td>
           <td width="1%">:</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_operasional_t .'</span>'. $baik .'</td>
           <td width="20%"><span style="font-size:12.5rem;">'. $pengujian_operasional_f .'</span>'. $tidak_baik .'</td>
           <td width="22%"><span style="font-size:12.5rem;">'. $pengujian_operasional_n .'</span>'. $tidak_dilaksanakan .'</td>
        </tr>';
}



$tblhtml = <<<EOD
<style>
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{border-color:black;border-style:solid;border-width:1px;}
table tr{ line-height: 2;}

</style>
<table class="table table-bordered">
     <tbody>

        $_tblhtml
        
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

// set default font subsetting mode
$pdf->setFontSubsetting(true);

$pdf->SetFont('dejavusans');

$pdf->writeHTML($tblhtml, true, false, false, false, '');

$data = $inspection->client_company. "\r\n";
$data .= $inspection_number .'-'. $inspection->task_id . "\r\n";
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

$default_regulation = get_option('predefined_regulation_of_'.$inspection->categories);
$equipment_regulasi = !empty($inspection->equipment['regulasi']) ? $inspection->equipment['regulasi'] : $default_regulation;
$rline = 4;
$tline = 12;

if (!empty($equipment_regulasi)) {
    $regulasi = explode(' -- ', $equipment_regulasi);
    $equipment_regulasi = '';
    $equipment_regulasi .= '<ol class="regulasi">'; 

    foreach($regulasi as $row){
        $equipment_regulasi .= '<li style="margin-left:70;">' .$row. '</li>'; 
    }
    $equipment_regulasi .= '</ol>';
    $rline = 4;
    $tline = 2;
}
    $pdf->Ln($rline);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('equipment_regulasi'), 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    //$pdf->Ln(1);
    $pdf->writeHTMLCell('', '', '', '', $equipment_regulasi, 0, 1, false, true, 'L', true);

$temuan = '';
$kline = 18;
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
$sline = 18;
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

