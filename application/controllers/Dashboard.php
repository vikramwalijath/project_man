<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // 1. Load essential libraries and helpers
        $this->load->library('session');
        $this->load->helper('url');

        // 2. Security Check: If user is NOT logged in, redirect to Login page
        if (!$this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Please login to access the dashboard.');
            redirect('auth');
        }
    }

    /**
     * Default Landing Page after Login
     */
    public function index() {
        // Here you can eventually fetch counts for your "Coming Soon" stats
        // Example: $data['total_employees'] = $this->db->count_all('carpenter_master');
        
        $data['username'] = $this->session->userdata('username');

        // Load the view with common Header and Footer
        $this->load->view('common/header_logged_in', $data);
        $this->load->view('dashboard_home', $data);
        $this->load->view('common/footer');
    }

    /**
     * Optional: Create a specific method for "My Projects" if you want 
     * it separate from the home dashboard
     */
    public function projects() {
        $this->load->view('common/header_logged_in');
        // This would be your projects list view
        $this->load->view('projects_list_view'); 
        $this->load->view('common/footer');
    }
}