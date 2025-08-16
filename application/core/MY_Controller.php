<?php defined('BASEPATH') or exit('No direct script access allowed');

if (class_exists('MX_Controller')) {
    class BaseMX extends MX_Controller {}
} else {
    class BaseMX extends CI_Controller {}
}

class MY_Controller extends BaseMX
{
    public function __construct()
    {
        parent::__construct();
        // Tempatkan logika umum: session, csrf, language, dll (opsional).
    }

    protected function respond_json($data, $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
