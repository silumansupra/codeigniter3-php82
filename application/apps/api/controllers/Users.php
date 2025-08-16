<?php defined('BASEPATH') or exit('No direct script access allowed');

class Users extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/User_model', 'users');
        $this->load->library('form_validation');
    }

    // GET /api/v1/users?limit=&offset=&q=
    public function index()
    {
        $limit  = (int)$this->input->get('limit') ?: 20;
        $offset = (int)$this->input->get('offset') ?: 0;
        $q      = $this->input->get('q');

        $items = $this->users->get_all($limit, $offset, $q);
        $total = $this->users->count_all($q);

        return $this->respond([
            'data' => $items,
            'meta' => ['total' => $total, 'limit' => $limit, 'offset' => $offset],
            'me'  => $this->authUser // payload jwt
        ]);
    }

    // GET /api/v1/users/{id}
    public function show($id)
    {
        $row = $this->users->find($id);
        if (!$row) return $this->respond(['error' => 'Not found'], 404);
        return $this->respond(['data' => $row]);
    }

    // POST /api/v1/users
    public function store()
    {
        $input = json_decode($this->input->raw_input_stream, true);
        if (!$input) $input = $this->input->post(NULL, true);

        $this->form_validation->set_data($input);
        $this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');

        if (!$this->form_validation->run()) {
            return $this->respond(['errors' => $this->form_validation->error_array()], 422);
        }

        $id = $this->users->create([
            'name'          => $input['name'],
            'email'         => $input['email'],
            'password_hash' => password_hash($input['password'], PASSWORD_BCRYPT),
            'role'          => $input['role'] ?? 'user'
        ]);

        return $this->respond(['message' => 'Created', 'id' => $id], 201);
    }

    // PUT /api/v1/users/{id}
    public function update($id)
    {
        $input = json_decode($this->input->raw_input_stream, true);
        if (!$input) return $this->respond(['error' => 'Invalid JSON'], 400);

        $data = array_filter([
            'name'  => $input['name']  ?? null,
            'email' => $input['email'] ?? null,
            'role'  => $input['role']  ?? null,
        ], fn ($v) => $v !== null);

        if (isset($input['password']) && $input['password'] !== '') {
            $data['password_hash'] = password_hash($input['password'], PASSWORD_BCRYPT);
        }

        if (!$this->users->find($id)) return $this->respond(['error' => 'Not found'], 404);

        $this->users->update_by_id($id, $data);
        return $this->respond(['message' => 'Updated']);
    }

    // DELETE /api/v1/users/{id}
    public function destroy($id)
    {
        if (!$this->users->find($id)) return $this->respond(['error' => 'Not found'], 404);
        $this->users->delete_by_id($id);
        return $this->respond(['message' => 'Deleted']);
    }
}
