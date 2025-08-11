<?php

defined('BASEPATH') or exit('No direct script access allowed');

$autoload['packages']  = array();
$autoload['libraries'] = array(
	'database',
	'session',
);
$autoload['drivers']   = array('session');
$autoload['helper']    = array(
	'url',
	'form',
);
$autoload['config']    = array();
$autoload['language']  = array();
$autoload['model']     = array();
