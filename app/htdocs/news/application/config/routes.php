<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller'] = "index";
$route['404_override'] = '';

$route['(:num)'] = 'detail/index/$1';
