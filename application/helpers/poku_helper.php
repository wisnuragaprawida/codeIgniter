<?php

function rules()
{
    $poku = get_instance();
    if (!$poku->session->userdata('email')) {
        redirect('auth/login');
    } else {
        $role_id = $poku->session->userdata('role_id');
        $menu = $poku->uri->segment(1);


        $qry_menu = $poku->db->get_where('user_menu', ['menu' => $menu])->row_array();

        $menu_id = $qry_menu['id'];

        $qry_menu_id = $poku->db->get_where('user_access', [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ]);

        if ($qry_menu_id->num_rows() < 1) {
            redirect('auth/blocked');
        }
    }
}
