<?php

defined('BASEPATH') or exit('No direct script access allowed');

$dimensions = $pdf->getPageDimensions();

$info_right_column = '';
$info_left_column  = '';

$info_right_column .= '<span style="font-weight:bold;font-size:27px;">' . _l('inspection_pdf_heading') . '</span><br />';
$info_right_column .= '<b style="color:#4e4e4e;"># ' . $inspection_number . '</b>';

if (get_option('show_status_on_pdf_ei') == 1) {
    $info_right_column .= '<br /><span style="color:rgb(' . inspection_status_color_pdf($status) . ');text-transform:uppercase;">' . format_inspection_status($status, '', false) . '</span>';
}

$pdf->SetFont('dejavusans');
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

$organization_info .= '<p><strong>'. _l('inspection_members') . '</strong></p>';

$CI = &get_instance();
$CI->load->model('inspections_model');
$inspection_members = $CI->inspections_model->get_inspection_members($inspection->id,true);
$i=1;
foreach($inspection_members as $member){
  $organization_info .=  $i.'. ' .$member['firstname'] .' '. $member['lastname']. '<br />';
  $i++;
}

$inspection_info .= '<br />' . _l('inspection_data_date') . ': ' . _d($inspection->date) . '<br />';

if (!empty($inspection->expirydate)) {
    $inspection_info .= _l('inspection_data_expiry_date') . ': ' . _d($inspection->expirydate) . '<br />';
}

if (!empty($inspection->reference_no)) {
    $inspection_info .= _l('reference_no') . ': ' . $inspection->reference_no . '<br />';
}

if ($inspection->project_id != 0 && get_option('show_project_on_inspection') == 1) {
    $inspection_info .= _l('project') . ': ' . get_project_name_by_id($inspection->project_id) . '<br />';
}


$left_info  = $swap == '1' ? $inspection_info : $organization_info;
$right_info = $swap == '1' ? $organization_info : $inspection_info;

$pdf->SetFont('dejavusans');
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
$nomor_seri = isset($inspection->equipment['nomor_seri']) ? $inspection->equipment['nomor_seri'] : '';
$nomor_unit = isset($inspection->equipment['nomor_unit']) ? $inspection->equipment['nomor_unit'] : '';
$nomor_seri = $nomor_seri . ' / ' . $nomor_unit;
$kapasitas = isset($inspection->equipment['kapasitas']) ? $inspection->equipment['kapasitas'] : '';
$satuan_kapasitas = isset($inspection->equipment['satuan_kapasitas']) ? $inspection->equipment['satuan_kapasitas'] : '';
$kapasitas = $kapasitas .' / ' . $satuan_kapasitas;
$inspection_company = _l('inspection_company_name');
$inspection_address = _l('inspection_address');
$inspection_jenis_pesawat = _l('inspection_jenis_pesawat');
$inspection_nama_pesawat = _l('inspection_nama_pesawat');

$inspection_serial_number = _l('inspection_serial_number');
$inspection_unit_number = _l('inspection_unit_number');
$inspection_capacity = _l('inspection_capacity');

$tblhtml = <<<EOD
<style type="text/css">
.tg-1e15{border-bottom: 1px solid black;}
.tg-oe15{width:30%;text-align:left;}
.tg-oe16{width:2%;}
</style>
<table class="tg">
<tbody>
  <tr class="tg-1e15">
    <td class="tg-oe15">$inspection_company</td>
    <td class="tg-oe16">:</td>
    <td class="tg-oe17">$company</td>
  </tr>
  <tr class="tg-1e15">
    <td class="tg-oe15">$inspection_address</td>
    <td class="tg-oe16">:</td>
    <td class="tg-oe17">$address</td>
  </tr>
  <tr class="tg-1e15">
    <td class="tg-oe15">$inspection_nama_pesawat</td>
    <td class="tg-oe16">:</td>
    <td class="tg-oe17">$nama_pesawat</td>
  </tr>
  <tr class="tg-1e15">
    <td class="tg-oe15">$inspection_serial_number / $inspection_unit_number</td>
    <td class="tg-oe16">:</td>
    <td class="tg-oe17">$nomor_seri</td>
  </tr>
  <tr class="tg-1e15">
    <td class="tg-oe15">$inspection_capacity</td>
    <td class="tg-oe16">:</td>
    <td class="tg-oe17">$kapasitas</td>
  </tr>
</tbody>
</table>
EOD;

$pdf->SetFont('dejavusans');
$pdf->writeHTML($tblhtml, true, false, false, false, '');

$pemeriksaan_dokumen_t = '&#9744;';
if(isset($inspection->equipment['pemeriksaan_dokumen']) && $inspection->equipment['pemeriksaan_dokumen'] == 1){
    $pemeriksaan_dokumen_t = '&#9745;';
}
$pemeriksaan_dokumen_f = '&#9744;';
if(isset($inspection->equipment['pemeriksaan_dokumen']) && $inspection->equipment['pemeriksaan_dokumen'] == 0){
    $pemeriksaan_dokumen_f = '&#9745;';
}
$pemeriksaan_dokumen_n = '&#9744;';
if(isset($inspection->equipment['pemeriksaan_dokumen']) && $inspection->equipment['pemeriksaan_dokumen'] == 2){
    $pemeriksaan_dokumen_n = '&#9745;';
}

$pemeriksaan_visual_t = '&#9744;';
if(isset($inspection->equipment['pemeriksaan_visual']) && $inspection->equipment['pemeriksaan_visual'] == 1){
    $pemeriksaan_visual_t = '&#9745;';
}
$pemeriksaan_visual_f = '&#9744;';
if(isset($inspection->equipment['pemeriksaan_visual']) && $inspection->equipment['pemeriksaan_visual'] == 0){
    $pemeriksaan_visual_f = '&#9745;';
}
$pemeriksaan_visual_n = '&#9744;';
if(isset($inspection->equipment['pemeriksaan_visual']) && $inspection->equipment['pemeriksaan_visual'] == 2){
    $pemeriksaan_visual_n = '&#9745;';
}
$pemeriksaan_pengaman_t = '&#9744;';
if(isset($inspection->equipment['pemeriksaan_pengaman']) && $inspection->equipment['pemeriksaan_pengaman'] == 1){
    $pemeriksaan_pengaman_t = '&#9745;';
}
$pemeriksaan_pengaman_f = '&#9744;';
if(isset($inspection->equipment['pemeriksaan_pengaman']) && $inspection->equipment['pemeriksaan_pengaman'] == 0){
    $pemeriksaan_pengaman_f = '&#9745;';
}
$pemeriksaan_pengaman_n = '&#9744;';
if(isset($inspection->equipment['pemeriksaan_pengaman']) && $inspection->equipment['pemeriksaan_pengaman'] == 2){
    $pemeriksaan_pengaman_n = '&#9745;';
}
$pengujian_penetrant_t = '&#9744;';
if(isset($inspection->equipment['pengujian_penetrant']) && $inspection->equipment['pengujian_penetrant'] == 1){
    $pengujian_penetrant_t = '&#9745;';
}
$pengujian_penetrant_f = '&#9744;';
if(isset($inspection->equipment['pengujian_penetrant']) && $inspection->equipment['pengujian_penetrant'] == 0){
    $pengujian_penetrant_f = '&#9745;';
}
$pengujian_penetrant_n = '&#9744;';
if(isset($inspection->equipment['pengujian_penetrant']) && $inspection->equipment['pengujian_penetrant'] == 2){
    $pengujian_penetrant_n = '&#9745;';
}
$pengujian_operasional_t = '&#9744;';
if(isset($inspection->equipment['pengujian_operasional']) && $inspection->equipment['pengujian_operasional'] == 1){
    $pengujian_operasional_t = '&#9745;';
}
$pengujian_operasional_f = '&#9744;';
if(isset($inspection->equipment['pengujian_operasional']) && $inspection->equipment['pengujian_operasional'] == 0){
    $pengujian_operasional_f = '&#9745;';
}
$pengujian_operasional_n = '&#9744;';
if(isset($inspection->equipment['pengujian_operasional']) && $inspection->equipment['pengujian_operasional'] == 2){
    $pengujian_operasional_n = '&#9745;';
}
$pemeriksan_dokumen = _l('pemeriksan_dokumen');
$pemeriksan_visual = _l('pemeriksan_visual');
$pemeriksan_pengaman = _l('pemeriksan_pengaman');
$pengujian_penetrant = _l('pengujian_penetrant');
$pengujian_operasional = _l('pengujian_operasional');

$lengkap = _l('lengkap');
$tidak_lengkap = _l('tidak_lengkap');
$tidak_ada = _l('tidak_ada');
$baik = _l('baik');
$tidak_baik = _l('tidak_baik');



$tblhtml = <<<EOD
<style>
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{border-color:black;border-style:solid;border-width:1px;}
table tr{ line-height: 2;}

</style>
<table class="table table-bordered">
     <tbody>
        <tr class="tg pemeriksaan_dokumen">
           <td width="36%">$pemeriksan_dokumen</td>
           <td width="1%">:</td>
           <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_t</span> $lengkap</td>
           <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_f</span> $tidak_lengkap</td>
           <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_dokumen_n</span> $tidak_ada</td>
        </tr>
        <tr class="tg pemeriksaan_visual">
           <td width="36%">$pemeriksan_visual</td>
           <td width="1%">:</td>
           <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_t</span> $baik</td>
           <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_f</span> $tidak_baik</td>
           <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_visual_n</span> $tidak_ada</td>
        </tr>
        <tr class="tg pemeriksaan_pengaman">
           <td width="36%">$pemeriksan_pengaman</td>
           <td width="1%">:</td>
           <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_t</span> $baik</td>
           <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_f</span> $tidak_baik</td>
           <td width="20%"><span style='font-size:2.5rem;'>$pemeriksaan_pengaman_n</span> $tidak_ada</td>
        </tr>
        <tr class="tg pengujian_penetrant">
           <td width="36%">$pengujian_penetrant</td>
           <td width="1%">:</td>
           <td width="20%"><span style='font-size:2.5rem;'>$pengujian_penetrant_t</span> $baik</td>
           <td width="20%"><span style='font-size:2.5rem;'>$pengujian_penetrant_f</span> $tidak_baik</td>
           <td width="20%"><span style='font-size:2.5rem;'>$pengujian_penetrant_n</span> $tidak_ada</td>
        </tr>
        <tr class="tg pengujian_operasional">
           <td width="36%">$pengujian_operasional</td>
           <td width="1%">:</td>
           <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_t</span> $baik</td>
           <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_f</span> $tidak_baik</td>
           <td width="20%"><span style='font-size:2.5rem;'>$pengujian_operasional_n</span> $tidak_ada</td>
        </tr>
        <tr class="tg">
           <td width="36%"></td>
           <td width="1%"></td>
           <td width="20%"></td>
           <td width="20%"></td>
           <td width="20%"></td>
        </tr>
     </tbody>
  </table>
EOD;

// set default font subsetting mode
$pdf->setFontSubsetting(true);

$pdf->SetFont('dejavusans');

$pdf->writeHTML($tblhtml, true, false, false, false, '');

$assigned_path = <<<EOF
        <img width="150" height="150" src="$inspection->assigned_path">
    EOF;    
$assigned_info = '<div style="text-align:center;">';
    $assigned_info .= get_option('invoice_company_name') . '<br />';
    $assigned_info .= $assigned_path . '<br />';

if ($inspection->assigned != 0 && get_option('show_assigned_on_inspections') == 1) {
    $assigned_info .= get_staff_full_name($inspection->assigned);
}
$assigned_info .= '</div>';

$acceptance_path = <<<EOF
    <img src="$inspection->acceptance_path">
EOF;
$client_info = '<div style="text-align:center;">';
    $client_info .= $inspection->client_company .'<br />';


if ($inspection->signed != 0) {
    $client_info .= _l('inspection_signed_by') . ": {$inspection->acceptance_firstname} {$inspection->acceptance_lastname}" . '<br />';
    $client_info .= _l('inspection_signed_date') . ': ' . _dt($inspection->acceptance_date_string) . '<br />';
    $client_info .= _l('inspection_signed_ip') . ": {$inspection->acceptance_ip}" . '<br />';

    $client_info .= $acceptance_path;
    $client_info .= '<br />';
}
$client_info .= '</div>';


$left_info  = $swap == '1' ? $client_info : $assigned_info;
$right_info = $swap == '1' ? $assigned_info : $client_info;
pdf_multi_row($left_info, $right_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

if (!empty($inspection->equipment['temuan'])) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('equipment_temuan'), 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(1);
    $pdf->writeHTMLCell('', '', '', '', $inspection->equipment['temuan'], 0, 1, false, true, 'L', true);
}

if (!empty($inspection->equipment['kesimpulan'])) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('equipment_kesimpulan'), 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(1);
    $pdf->writeHTMLCell('', '', '', '', $inspection->equipment['kesimpulan'], 0, 1, false, true, 'L', true);
}

if (!empty($inspection->terms)) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('terms_and_conditions') . ":", 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(1);
    $pdf->writeHTMLCell('', '', '', '', $inspection->terms, 0, 1, false, true, 'L', true);
}

