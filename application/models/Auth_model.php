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
    public function update_user($user, $old_image)
    {
        $new_image = $_FILES['image'];
        if ($new_image) {
            $config['upload_path'] = './assets/img/profile/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size']     = '2048';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                $new_image_filename = $this->upload->data('file_name');
                $this->db->set('image', $new_image_filename);
            } else {
                echo $this->upload->display_errors();
            }

            if ($old_image != "default.jpg") {
                unlink(FCPATH . "assets/img/profile/" . $old_image);
            }
        }
        $name = $user['name'];
        $email = $user['email'];

        $this->db->set('name', $name);
        $this->db->where('email', $email);
        $this->db->update('user');
    }

    public function update_password($hashed_password, $email)
    {
        $this->db->set('password', $hashed_password);
        $this->db->where('email', $email);
        $this->db->update('user');
    }
}
