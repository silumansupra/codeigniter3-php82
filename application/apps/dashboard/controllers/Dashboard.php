<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = array(
            'title'   => 'Dashboard',
            'content' => 'v_dashboard',
        );

        $this->load->view('mainview', $data);
    }
}
