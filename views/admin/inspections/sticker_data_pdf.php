<?php

defined('BASEPATH') or exit('No direct script access allowed');

$dimensions    = $pdf->getPageDimensions();

$pdf->SetMargins('2', '2', '2');
$pdf->setPrintFooter(false);
$pdf->SetY(2, true, true);

// set auto page breaks
$pdf->SetAutoPageBreak(false, 0);

$company_info = '';
$company_info .= get_option('invoice_company_name') . "\r\n";
$company_info .= get_option('invoice_company_address') . "\r\n";
$company_info .= get_option('invoice_company_city') .' ';
$company_info .= get_option('company_state');

// Multicell test
$pdf->MultiCell(68, 19, ''.$company_info, 0, 'L', 0, 0, '', '', true);

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
$pdf->write2DBarcode($url_data, 'QRCODE,L', 65, 2, 20, 20, $style, 'N');

$pdf->SetFont('helvetica', '', 11);
$html = '<p style="text-align:center;">';
// Like heading inspection name
$html .= '<strong>' . $inspection->formatted_number . '</strong><br />';
$html .= ucwords($client_company);
$html .='</p>';

$pdf->writeHTML($html, true, false, false, false, '');

$assigned_path = <<<EOF
        <img src="$inspection->assigned_path">
    EOF;    
$left_info = '<div style="text-align:center;">';
$left_info .= $assigned_path . '<br />';

if ($inspection->assigned != 0 && get_option('show_assigned_on_inspections') == 1) {
    $left_info .= get_staff_full_name($inspection->assigned);
}
$left_info .= '</div>';

$pdf->SetFont('helvetica', '', 9);
// inspection overview heading
$right_info = '';
/*
$right_info .= _l('project') .' : '. get_project_name_by_id($inspection->project_id) . '<br />';
$right_info .= '<span class="bold">'. _l('inspection_data_date') .' : </span>'. _d($inspection->date) . '<br />';
$right_info .= '<span class="bold">'. _l('inspection_equipment_nama_pesawat') .' : </span>'. $equipment_name . '<br />';
*/

$right_info .= _l('project') .' : ' . '<br />';
$right_info .= get_project_name_by_id($inspection->project_id) . '<br />';
$right_info .= _l('inspection_data_date') . '<br />';
$right_info .= _d($inspection->date) . '<br />';
$right_info .= _l('inspection_equipment_nama_pesawat') . '<br />';
$right_info .= $inspection->equipment_name . '<br />';

pdf_multi_row($left_info, $right_info,  $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

if (ob_get_length() > 0 && ENVIRONMENT == 'production') {
    ob_end_clean();
}

$pdf->SetSubject($inspection->formatted_number .' '. $equipment_name);
$pdf->SetCreator(get_option('invoice_company_name'));
$pdf->SetAuthor(get_option('invoice_company_name'));

// Output PDF to user
$pdf->output('#' . $inspection->formatted_number . '-'.$inspection->task_id.'_' . _d(date('Y-m-d')) . '.pdf', 'I');
