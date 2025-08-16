<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class MX_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        /* Load the MX_Loader */
        $this->load = new MX_Loader;
        $this->load->initialize($this);
    }
}
