<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Menu_model');
    }
    public function index()
    {
        $data['title'] = 'Menu Management';
        $data['user'] = $this->Auth_model->get_user($this->session->userdata('email'));
        $data['menu'] = $this->Menu_model->get_menus();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('templates/footer');
    }

    public function add()
    {
        $this->form_validation->set_rules('menu', 'Menu', 'required');
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Menu Management';
            $data['user'] = $this->Auth_model->get_user($this->session->userdata('email'));
            $data['menu'] = $this->Menu_model->get_menus();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Menu_model->insert_menu($this->input->post('menu'));
            $this->session->set_flashdata('message', '<div class="alert alert-success">New menu added!</div>');
            redirect('menu');
        }
    }
    public function submenu()
    {
        $data['title'] = 'Submenu Management';
        $data['user'] = $this->Auth_model->get_user($this->session->userdata('email'));
        $data['menu'] = $this->Menu_model->get_menus();
        $data['submenu'] = $this->Menu_model->get_sub_menu();
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'URL', 'required');
        $this->form_validation->set_rules('icon', 'Icon', 'required');
        $this->form_validation->set_rules('is_active', 'Active', 'required');
        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/submenu', $data);
            $this->load->view('templates/footer');
        } else {
            $this->Menu_model->insert_submenu($this->input->post());
            $this->session->set_flashdata('message', '<div class="alert alert-success">New menu added!</div>');
            redirect('menu/submenu');
        }
    }
}
