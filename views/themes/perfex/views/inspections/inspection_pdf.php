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

$items  = '';
$items .= '<table style="padding:5,10,5,10" border="1" class="table table-bordered table-jobreport-items">';
$items .=    '<thead>';
$items .=        '<tr>';
$items .=            '<th width="50" align="center">No#</th>';
$items .=            '<th width="210" align="center">Nomor BAPR Alat</th>';
$items .=            '<th width="550" align="center">Peralatan</th>';
$items .=        '</tr>';
$items .=    '</thead>';
$items .=    '<tbody>';
        $i=1;
        foreach($inspection->inspection_items as $item){
            //$number = format_inspection_item_number($item['rel_id'], $item['task_id']);
$items .=            '<tr>';
$items .=                '<td width="50" align="right">' .$i.' </td>';
$items .=                '<td width="210">' . $item['formatted_number'].'-'. $item['task_id'] . '</td>';
$items .=                '<td width="550">' .$item['name']. '</td>';
$items .=            '</tr>';
            
             $i++; 
         } 
$items .=    '</tbody>';
$items .= '</table>';

pdf_multi_row($items, '', $pdf, ($dimensions['wk'] / 1) - $dimensions['lm']);

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
$pdf->Ln(2);
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

