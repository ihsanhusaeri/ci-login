<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Auth_model');
        $this->load->model('Role_model', 'role');
        $this->load->model('Menu_model', 'menu');
    }
    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->Auth_model->get_user($this->session->userdata('email'));
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/footer');
    }
    public function role()
    {
        $data['title'] = 'Role';
        $data['user'] = $this->Auth_model->get_user($this->session->userdata('email'));
        $data['role'] = $this->role->get_roles();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role', $data);
        $this->load->view('templates/footer');
    }
    public function roleaccess($role_id)
    {
        $data['title'] = 'Role Access';
        $data['user'] = $this->Auth_model->get_user($this->session->userdata('email'));
        $data['role'] = $this->role->get_role($role_id);
        $data['menu'] = $this->menu->get_menus();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('templates/footer');
    }

    public function change_access()
    {
        $role_id = $this->input->post('roleId');
        $menu_id = $this->input->post('menuId');

        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $access = $this->db->get_where('user_access_menu', $data);
        if ($access->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Access Changed!</div>');
    }
}
