<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller
{
    public function index()
    {
        // default page for API
        $this->load->view('api/v_api');
    }
}