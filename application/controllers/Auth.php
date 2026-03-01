<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model( 'User_model' );
        $this->load->library( 'session' );
        $this->load->helper( 'url' );
        // Ensure URL helper is loaded for redirects
    }

    public function index() {
        // If user is already logged in, don't show login page, go to dashboard
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }
        $this->load->view('login_view');
    }

   public function login_process() {
    $login_identity = $this->input->post('username'); // This field takes Mobile or Email
    $password = $this->input->post('password');

    // Query to check both mobile and email fields
    $this->db->group_start()
             ->where('mobile', $login_identity)
             ->or_where('email', $login_identity)
             ->group_end();
    
    $this->db->where('password', $password); // Use password_verify() in production
    $this->db->where('status', 1); // Only active users
    
    $user = $this->db->get('users')->row();

    if ($user) {
        $this->session->set_userdata([
            'user_id'  => $user->id,
            'username' => $user->username,
            'name'     => $user->name,
            'logged_in'=> TRUE
        ]);
        redirect('dashboard');
    } else {
        $this->session->set_flashdata('error', 'Invalid Mobile/Email or Password');
        redirect('auth');
    }
}       

    public function logout() {
        // Clear session and redirect to login
        $this->session->sess_destroy();
        redirect('auth' );
    }
}