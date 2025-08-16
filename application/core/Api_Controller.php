<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Controller.php'; // pastikan MY_Controller tersedia

/**
 * Khusus controller API.
 * - Set JSON header
 * - Wajib JWT (kecuali class Auth)
 */
class Api_Controller extends MY_Controller
{
    protected $authUser = null; // payload JWT terverifikasi

    public function __construct()
    {
        parent::__construct();

        // Semua response API default JSON
        header('Content-Type: application/json; charset=utf-8');

        // Wajib JWT untuk semua controller API kecuali Auth
        if (strtolower($this->router->class) !== 'auth') {
            $this->authUser = $this->require_jwt();
        }
    }

    protected function respond($data, $code = 200)
    {
        return $this->respond_json($data, $code);
    }

    protected function get_bearer_token()
    {
        $auth = $this->input->get_request_header('Authorization', TRUE);
        if (!$auth || !preg_match('/Bearer\s(\S+)/', $auth, $m)) return null;
        return $m[1];
    }

    protected function require_jwt()
    {
        $this->load->helper('jwt'); // pastikan Jwt_helper.php ada
        $token = $this->get_bearer_token();
        if (!$token) $this->respond(['error' => 'Unauthorized: missing token'], 401);
        try {
            $payload = jwt_decode($token); // stdClass
            return $payload;
        } catch (Exception $e) {
            $this->respond(['error' => 'Unauthorized: invalid token', 'detail' => $e->getMessage()], 401);
        }
    }
}
