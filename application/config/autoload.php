<?php

defined('BASEPATH') or exit('No direct script access allowed');

$autoload['packages']  = array();
$autoload['libraries'] = array(
	'database',
	'session',
);
$autoload['drivers']   = array();
$autoload['helper']    = array(
	'Jwt',
	'url',
	'form',
	'global',
	'template',
);
$autoload['config']    = array();
$autoload['language']  = array();
$autoload['model']     = array();
