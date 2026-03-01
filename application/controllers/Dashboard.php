<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // 1. Load essential libraries and helpers
        $this->load->library( 'session' );
        $this->load->helper( 'url' );

        // 2. Security Check: If user is NOT logged in, redirect to Login page
        if ( !$this->session->userdata( 'user_id' ) ) {
            $this->session->set_flashdata( 'error', 'Please login to access the dashboard.' );
            redirect( 'auth' );
        }
    }

    /**
    * Default Landing Page after Login
    */

    public function index() {
        // 1. User Info
        $data[ 'user_full_name' ] = $this->session->userdata( 'name' ) ?? 'Admin';

        // 2. Project & Invoice Totals
        $data[ 'total_projects' ]  = $this->db->count_all( 'project_details' );
        $data[ 'total_revenue' ]   = $this->db->select_sum( 'total_amount' )->get( 'project_details' )->row()->total_amount ?? 0;
        $data[ 'total_invoiced' ]  = $this->db->select_sum( 'amount' )->get( 'project_invoices' )->row()->amount ?? 0;

        // 3. Detailed Expense Sums
        $data[ 'total_carp_paid' ] = $this->db->where( 'employee_type', 'carpenter' )->select_sum( 'amount_paid' )->get( 'employee_payments' )->row()->amount_paid ?? 0;
        $data[ 'total_paint_paid' ] = $this->db->where( 'employee_type', 'painter' )->select_sum( 'amount_paid' )->get( 'employee_payments' )->row()->amount_paid ?? 0;
        $data[ 'total_elec_paid' ] = $this->db->where( 'employee_type', 'electrician' )->select_sum( 'amount_paid' )->get( 'employee_payments' )->row()->amount_paid ?? 0;
        $data[ 'total_sup_paid' ]  = $this->db->select_sum( 'amount_sent' )->get( 'supplier_payments' )->row()->amount_sent ?? 0;

        // 4. Counts
        $data[ 'total_suppliers' ] = $this->db->count_all( 'supplier_master' );
        $data[ 'total_staff' ]     = $this->db->count_all( 'carpenter_master' ) + $this->db->count_all( 'painter_master' ) + $this->db->count_all( 'electrician_master' );

        // 5. Logic Calculations
        $data[ 'total_expenses' ]  = $data[ 'total_carp_paid' ] + $data[ 'total_paint_paid' ] + $data[ 'total_elec_paid' ] + $data[ 'total_sup_paid' ];
        $data[ 'pending_bill' ]    = $data[ 'total_revenue' ] - $data[ 'total_invoiced' ];
        $data[ 'net_profit' ]      = $data[ 'total_invoiced' ] - $data[ 'total_expenses' ];

        $this->load->view( 'common/header_logged_in', $data );
        $this->load->view( 'dashboard_home', $data );
        $this->load->view( 'common/footer' );
    }

    /**
    * Optional: Create a specific method for 'My Projects' if you want
    * it separate from the home dashboard
    */

    public function projects() {
        $this->load->view( 'common/header_logged_in' );
        // This would be your projects list view
        $this->load->view( 'projects_list_view' );

        $this->load->view( 'common/footer' );
    }
}