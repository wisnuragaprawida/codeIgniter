<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }


    // login
    public function login()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }
        // rules login
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        // validation login
        if ($this->form_validation->run() == false) {

            $data['title'] = 'Login';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            $this->_login();
        }
    }

    private function _login()
    {

        $email = $this->input->post('email');
        $password = $this->input->post('password');

        // query db
        $user = $this->db->get_where('user', ['email' => $email])->row_array();
        // jika usernya ada
        if ($user) {
            // jika user aktiv
            if ($user['is_active'] == 1) {
                // cek pasword
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id']
                    ];
                    $this->session->set_userdata($data);
                    if ($user['role_id'] == 2) {
                        redirect('user/home');
                    } else {
                        redirect('admin');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    password does not match!!</div>');
                    redirect('auth/login');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Email not activated!</div>');
                redirect('auth/login');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Email not registered!</div>');
            redirect('auth/login');
        }
    }


    // registrasi


    public function registration()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }
        // rules registrasi
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'this email alredy regitered!!'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[6]|matches[password2]', [
            'matches' => 'password does not match!',
            'min_length' => 'password too short!'

        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');


        //validation registration
        if ($this->form_validation->run() == false) {
            $data['title'] = 'register';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
        } else {
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 1,
                'date_create' => time()
            ];
            $this->db->insert('user', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
           create acount succsess!!, please login.</div>');
            redirect('auth/login');
        }
    }
    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
           you have been logout!!</div>');
        redirect('auth/login');
    }

    public function blocked()
    {
        echo " access denied";
    }
}
