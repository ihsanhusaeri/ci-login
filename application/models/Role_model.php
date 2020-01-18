<?php
class Role_model extends CI_Model
{
    public function get_roles()
    {
        $roles = $this->db->get('user_role')->result_array();
        return $roles;
    }
    public function get_role($role_id)
    {
        $role = $this->db->get_where('user_role', ['id' => $role_id])->row_array();
        return $role;
    }
}
