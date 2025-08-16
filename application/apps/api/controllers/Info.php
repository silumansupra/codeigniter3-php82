<?php defined('BASEPATH') or exit('No direct script access allowed');

class Info extends MX_Controller
{
    public function index()
    {
        echo json_encode([
            'status' => 'ok',
            'php'    => PHP_VERSION,
            'ci'     => CI_VERSION,
            'hmvc'   => class_exists('MX_Controller') ? 'ON' : 'OFF',
            'time'   => date('c')
        ]);
    }
}
