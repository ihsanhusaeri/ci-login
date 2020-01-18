<?php

class Auth_model extends CI_Model
{
    public function login($data)
    {
        $user = $this->db->get_where('user', array(
            'email' => $data['email']
        ))->row_array();
        if ($user) {
            if ($user['is_active'] == 1) {
                if (password_verify($data['password'], $user['password'])) {
                    return $user;
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Wrong password!</div>');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Email has not been activated.</div>');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Email is not registered.</div>');
        }
        return false;
    }

    public function get_user($email)
    {
        return $this->db->get_where('user', ['email' => $email])->row_array();
    }
}
