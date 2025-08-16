<?php defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    protected $table = 'testing_api_users';

    public function find_by_email($email)
    {
        return $this->db->get_where($this->table, ['email' => $email])->row();
    }
    public function find($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }
    public function get_all($limit = 20, $offset = 0, $q = null)
    {
        if ($q) {
            $this->db->group_start()
                ->like('name', $q)
                ->or_like('email', $q)
                ->group_end();
        }
        return $this->db->limit($limit, $offset)->get($this->table)->result();
    }
    public function count_all($q = null)
    {
        if ($q) {
            $this->db->group_start()
                ->like('name', $q)
                ->or_like('email', $q)
                ->group_end();
        }
        return $this->db->count_all_results($this->table);
    }
    public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    public function update_by_id($id, $data)
    {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }
    public function delete_by_id($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }
}
