<?php

defined('BASEPATH') or exit('No direct script access allowed');

$route['inspections/inspection/(:num)/(:any)'] = 'inspection/index/$1/$2';

/**
 * @since 2.0.0
 */
$route['inspections/list'] = 'myinspection/list';
$route['inspections/show/(:num)/(:any)'] = 'myinspection/show/$1/$2';
$route['inspections/pdf/(:num)'] = 'myinspection/pdf/$1';
