<?php

defined('BASEPATH') or exit('No direct script access allowed');

//$route['inspections/inspection/(:num)/(:any)'] = 'inspection/index/$1/$2';

/**
 * @since 2.0.0
 */
$route['inspections/list'] = 'myinspection/list';
$route['inspections/sticker/(:num)/(:any)'] = 'myinspection/sticker/$1/$2';
$route['inspections/sticker/pdf/(:num)'] = 'myinspection/sticker_data/$1';
//$route['inspections/bapp/(:num)/(:any)'] = 'myinspection/bapp/$1/$2';
//$route['inspections/bapp/pdf/(:num)'] = 'myinspection/pdf/$1';

$route['inspections/bapr/(:num)/html/(:num)/(:any)'] = 'myinspection/bapr/$1/$2/$3';
$route['inspections/bapr/(:num)/pdf/(:num)'] = 'myinspection/item_pdf/$1/$2';
