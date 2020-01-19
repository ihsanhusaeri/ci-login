<?php
class Menu_model extends CI_Model
{
    public function get_menus()
    {
        $this->db->where('id !=', 1);
        $menus = $this->db->get('user_menu')->result_array();
        return $menus;
    }
    public function insert_menu($menu)
    {
        $this->db->insert('user_menu', ['menu' => $menu]);
    }
    public function get_sub_menu()
    {
        $query = "SELECT `user_sub_menu`.*, `user_menu`.`menu`
                 FROM `user_sub_menu` JOIN `user_menu`
                 ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
                ";
        $submenu = $this->db->query($query)->result_array();
        return $submenu;
    }
    public function insert_submenu($submenu)
    {
        $data = [
            'title' => $submenu['title'],
            'menu_id' => $submenu['menu_id'],
            'url' => $submenu['url'],
            'icon' => $submenu['icon'],
            'is_active' => $submenu['is_active']
        ];
        $this->db->insert('user_sub_menu', $data);
    }
}
