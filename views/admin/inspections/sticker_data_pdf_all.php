<?php

defined('BASEPATH') or exit('No direct script access allowed');
$pages = count($inspection->inspection_items);
$page = 0;

foreach ($inspection->inspection_items as $item){
    
    $dimensions    = $pdf->getPageDimensions();

    $pdf->SetMargins('2', '2', '2');
    $pdf->setPrintFooter(false);
    $pdf->SetY(2, true, true);

    $pdf->SetFont('helvetica', '', 10);
    // set auto page breaks
    $pdf->SetAutoPageBreak(false, 0);

    $company_info = '';
    $company_info .= get_option('invoice_company_name') . "\r\n";
    $company_info .= get_option('invoice_company_address') . "\r\n";
    $company_info .= get_option('invoice_company_city') .' ';
    $company_info .= get_option('company_state');

    $pdf->MultiCell(68, 9, ''.$company_info, 0, 'L', 0, 0, '', '', true);

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
    
    $url_data = site_url('inspections/bapr/'. $inspection->id .'/html/'.$item['task_id'].'/'.$inspection->hash) ."\r\n";
    // QRCODE,L : QR-CODE Low error correction
    $pdf->write2DBarcode($url_data, 'QRCODE,L', 65, 2, 20, 20, $style, 'N');

    $pdf->SetFont('helvetica', '', 11);
    $html = '<p style="text-align:center;">';
    // Like heading inspection name
    $html .= '<strong>' . $inspection->formatted_number .'-'. $item['task_id'] .'</strong><br />';
    $html .= ucwords($client_company);
    $html .='</p>';

    $pdf->writeHTML($html, true, false, false, false, '');

    $assigned_path = <<<EOF
            <img src="$inspection->assigned_path">
        EOF;    
    $qrcode_data = $inspection->formatted_number .'-'. $item['task_id'] . "\r\n";
    $qrcode_data .= $item['name'] . "\r\n";
    $qrcode_data .= _d($inspection->date) . "\r\n";
    
    // QRCODE,L : QR-CODE Low error correction
    $pdf->write2DBarcode($qrcode_data, 'QRCODE,M', 0, 30, 33, 33, $style, 'N');

    $right_info = _l('project') .' :' . "\r\n";
    $right_info .= get_project_name_by_id($inspection->project_id) . "\r\n";
    $right_info .= _l('inspection_data_date') . "\r\n";
    $right_info .= _d($inspection->date) . "\r\n";
    $right_info .= _l('inspection_equipment_nama_pesawat') . "\r\n";
    $right_info .= $item['name'] . "\r\n";
    $x = $pdf->getX();
    $y = $pdf->getY();
    //pdf_multi_row($left_info, $right_info,  $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);
    //$pdf->MultiCell(40, 0, ''.$right_info, 0, 'L', 0, 10, '', '', true);
    $pdf->SetY(32, true, true);

    $pdf->SetFont('helvetica', '', 9);
    $pdf->MultiCell(82, 20, ''.$right_info, 0, 'R', 0, 0, '', '', true);


    $page++;
    if($page < $pages){
        // add a page
        $pdf->AddPage();
    }

}
if (ob_get_length() > 0 && ENVIRONMENT == 'production') {
    ob_end_clean();
}

$pdf->SetSubject($inspection->formatted_number .' '. $equipment_name);
$pdf->SetCreator(get_option('invoice_company_name'));
$pdf->SetAuthor(get_option('invoice_company_name'));

// Output PDF to user
$pdf->output('#' . $inspection->formatted_number . '-STICKER-ALL.pdf', 'I');
