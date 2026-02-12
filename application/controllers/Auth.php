<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('session');
        $this->load->helper('url'); // Ensure URL helper is loaded for redirects
    }

    public function index() {
        // If user is already logged in, don't show login page, go to dashboard
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }
        $this->load->view('login_view');
    }

    public function login_process() {
        $user = $this->input->post('username');
        $pass = $this->input->post('password');

        $userdata = $this->User_model->check_login($user, $pass);

        if ($userdata) {
            // Set session data
            $session_data = array(
                'user_id'  => $userdata->id,
                'username' => $userdata->username,
                'logged_in' => TRUE
            );
            $this->session->set_userdata($session_data);

            // Redirect to Dashboard (Default after login)
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Invalid Username or Password');
            redirect('auth');
        }
    }

    public function logout() {
        // Clear session and redirect to login
        $this->session->sess_destroy();
        redirect('auth');
    }
}