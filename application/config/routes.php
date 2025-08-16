<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// API
$route['api/info'] = 'api/info/index';

$route['api/v1/auth/login']             = 'api/auth/login';
$route['api/v1/users']['GET']           = 'api/users/index';
$route['api/v1/users']['POST']          = 'api/users/store';
$route['api/v1/users/(:num)']['GET']    = 'api/users/show/$1';
$route['api/v1/users/(:num)']['PUT']    = 'api/users/update/$1';
$route['api/v1/users/(:num)']['DELETE'] = 'api/users/destroy/$1';