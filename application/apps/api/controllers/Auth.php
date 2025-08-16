<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends BaseMX
{ // TIDAK pakai Api_Controller (supaya bisa login tanpa token)
    public function __construct()
    {
        parent::__construct();
        header('Content-Type: application/json; charset=utf-8');
        $this->load->model('api/M_user', 'users');
        $this->load->helper('jwt');
    }

    private function respond($data, $code = 200)
    {
        http_response_code($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // POST /api/v1/auth/login
    public function login()
    {

        $input           = json_decode($this->input->raw_input_stream, true);
        if (!$input) $input = $this->input->post(NULL, true);

        $email = trim($input['email'] ?? '');
        $pass  = (string)($input['password'] ?? '');

        if (!$email || !$pass) {
            return $this->respond(['error' => 'Email/password required'], 422);
        }

        $user = $this->users->find_by_email($email);

        if (!$user || !password_verify($pass, $user->password_hash)) {
            return $this->respond(['error' => 'Invalid credentials'], 401);
        }

        $token = jwt_encode([
            'sub'   => (int)$user->id,
            'email' => $user->email,
            'name'  => $user->name,
            'role'  => $user->role ?? 'user'
        ], 60 * 60 * 4); // 4 jam

        return $this->respond([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => 60 * 60 * 4
        ], 200);
    }
}
