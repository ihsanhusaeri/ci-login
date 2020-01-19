<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Auth_model');
    }
    public function index()
    {
        $data['title'] = 'My Profile';
        $data['user'] = $this->Auth_model->get_user($this->session->userdata('email'));
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }

    public function edit()
    {
        $data['title'] = 'Edit Profile';
        $data['user'] = $this->Auth_model->get_user($this->session->userdata('email'));
        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $old_image = $data['user']['image'];
            $this->Auth_model->update_user($this->input->post(), $old_image);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Your profile has been updated!</div>');
            redirect('user');
        }
    }
    public function changepassword()
    {
        $data['title'] = 'Change Password';
        $data['user'] = $this->Auth_model->get_user($this->session->userdata('email'));

        $this->form_validation->set_rules('current_password', 'Current Password', 'required|trim');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|trim|min_length[3]');
        $this->form_validation->set_rules('repeat_new_password', 'Confirm New Password', 'matches[new_password]');
        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/changepassword', $data);
            $this->load->view('templates/footer');
        } else {
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password');
            if (password_verify($current_password, $data['user']['password'])) {
                if ($current_password == $new_password) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    New password cannot be same as current password!</div>');
                } else {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $this->Auth_model->update_password($hashed_password, $data['user']['email']);
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                    Password changed!</div>');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Wrong current password!</div>');
            }
            redirect('user/changepassword');
        }
    }
}
