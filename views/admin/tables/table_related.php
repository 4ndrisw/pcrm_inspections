<?php

defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();
$path = $CI->uri->segment(3);
$inspection_id = $CI->session->userdata('inspection_id');
$project_id = $CI->session->userdata('project_id');

$aColumns = [
    db_prefix() . 'tasks.name',
    db_prefix() . 'tags.name',
    'flag',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'inspection_items';


$join = [
    'RIGHT JOIN ' . db_prefix() . 'tasks ON ' . db_prefix() . 'inspection_items.task_id = ' . db_prefix() . 'tasks.id',
    'LEFT JOIN ' . db_prefix() . 'projects ON ' . db_prefix() . 'projects.id = ' . db_prefix() . 'tasks.rel_id',
    'LEFT JOIN ' . db_prefix() . 'taggables ON ' . db_prefix() . 'taggables.rel_id = ' . db_prefix() . 'tasks.id',
    'LEFT JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id',
];

$additionalSelect = [db_prefix() . 'inspection_items.id','inspection_id',db_prefix() . 'tasks.id as task_id',db_prefix() . 'taggables.tag_id'];


$where  = [];
array_push($where, 'AND ' . db_prefix() . 'projects.id = "'.$project_id.'"');
array_push($where, 'AND ' . db_prefix() . 'tasks.rel_type = "project"');
array_push($where, 'AND ' . db_prefix() . 'inspection_items.id IS NULL');


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'flag') {
            $_data = '<a class="btn btn-success" title = "'._l('propose_this_item').'" href="#" onclick="inspection_add_inspection_item(' . $inspection_id . ','. $project_id . ',' . $aRow['task_id']. ',' . $aRow['tag_id'] . '); return false;">+</a>';
        } 
        $row[] = $_data;

    }
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
