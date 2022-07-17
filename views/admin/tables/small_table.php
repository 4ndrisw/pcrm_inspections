<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'formatted_number',
    'company',
    'date',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'inspections';


$join = [
    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'inspections.clientid',
    //'LEFT JOIN ' . db_prefix() . 'projects ON ' . db_prefix() . 'projects.id = ' . db_prefix() . 'inspections.project_id',
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], ['id']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'formatted_number') {
            $_data = '<a href="' . admin_url('inspections/inspection/' . $aRow['id']) . '">' . $_data . '</a>';
            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('inspections/update/' . $aRow['id']) . '">' . _l('edit') . '</a>';

            if (has_permission('inspections', '', 'delete')) {
                $_data .= ' | <a href="' . admin_url('inspections/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }
            $_data .= '</div>';
        }
        elseif ($aColumns[$i] == 'equipment_type') {
            $tag = get_tags_in($aRow['id'],'inspection')[0];
            $_data = isset($tag) ? $tag : '';
            $_data = $_data;
        } elseif ($aColumns[$i] == 'date') {
            $_data = _d($_data);
        } 
        $row[] = $_data;

    }
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
