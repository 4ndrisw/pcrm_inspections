<?php

defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();
$path = $CI->uri->segment(3);
$inspection_id = $CI->session->userdata('inspection_id');

$aColumns = [
    db_prefix() . 'tasks.name',
    db_prefix() . 'inspection_items.equipment_name',
    db_prefix() . 'tags.name',
    'flag',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'inspection_items';


$join = [
    'LEFT JOIN ' . db_prefix() . 'tasks ON ' . db_prefix() . 'tasks.id = ' . db_prefix() . 'inspection_items.task_id',
    'LEFT JOIN ' . db_prefix() . 'taggables ON ' . db_prefix() . 'taggables.rel_id = ' . db_prefix() . 'tasks.id',
    'LEFT JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id',
];

$additionalSelect = [db_prefix() . 'inspection_items.id','inspection_id','task_id'];


$where  = [];
array_push($where, 'AND ' . db_prefix() . 'inspection_items.inspection_id = "'.$inspection_id.'"');
array_push($where, 'AND ' . db_prefix() . 'tasks.rel_type = "project"');


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        if ($aColumns[$i] == db_prefix() . 'tasks.name') {
            $_data = '<a href="' . admin_url('inspections/inspection_item/'. $inspection_id . '/' . $aRow['task_id']) . '" target = "_blank">' . $_data . '</a>';
        } elseif ($aColumns[$i] == 'flag') {
            $_data = '<a class="btn btn-danger" title = "'._l('remove_this_item').'" href="#" onclick="inspection_remove_inspection_item(' . $aRow['inspection_id'] . ',' . $aRow['task_id'] . '); return false;">x</a>';
        } 
        $row[] = $_data;

    }
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
