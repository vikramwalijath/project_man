<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Employees extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library( [ 'session' ] );
        $this->load->helper( 'url' );
        if ( !$this->session->userdata( 'user_id' ) ) {
            redirect( 'auth' );
        }
    }

    // Show all employees

    public function index() {
        $data[ 'carpenters' ]   = $this->db->get_where( 'carpenter_master', [ 'status' => 1 ] )->result();
        $data[ 'painters' ]     = $this->db->get_where( 'painter_master', [ 'status' => 1 ] )->result();
        $data[ 'electricians' ] = $this->db->get_where( 'electrician_master', [ 'status' => 1 ] )->result();

        $this->load->view( 'common/header_logged_in' );
        $this->load->view( 'employee_list_view', $data );
        $this->load->view( 'common/footer' );
    }

    // Add employee payment

    public function add_employee_payment() {
        $project_id = $this->input->post( 'project_id' );

        // Split combined 'type|id'
        $worker_data = explode( '|', $this->input->post( 'worker_info' ) );
        $emp_type = ucfirst( $worker_data[ 0 ] );
        // Carpenter / Painter / Electrician
        $emp_id   = $worker_data[ 1 ];

        // Upload file ( receipt, proof, etc. )
        $file = $this->do_upload( 'attachment' );
        // implement do_upload()

        $data = [
            'project_id'      => $project_id,
            'employee_id'     => $emp_id,
            'employee_type'   => $emp_type,
            'amount_paid'     => $this->input->post( 'amount_paid' ),
            'payment_date'    => $this->input->post( 'payment_date' ),
            'paid_by'         => $this->input->post( 'paid_by' ), // Cash/UPI/Cheque/Bank Transfer
            'remarks'         => $this->input->post( 'remarks' ),
            'file_attachment' => $file,
            'created_by'      => $this->session->userdata( 'user_id' )
        ];

        $this->db->insert( 'employee_payments', $data );
        $this->session->set_flashdata( 'success', 'Employee payment saved!' );
        redirect( 'projects/view/' . $project_id );
    }

    // Show payments for an employee

    public function payments( $type, $id ) {
        $table = $type . '_master';
        $employee = $this->db->get_where( $table, [ 'id' => $id ] )->row();

        if ( !$employee ) {
            show_404();
        }

        $this->db->select( 'ep.id, ep.amount_paid, ep.payment_date, ep.paid_by, ep.remarks, ep.file_attachment, ep.created_by,
                           pr.project_name, pr.customer_name, v.vendor_name' );
        $this->db->from( 'employee_payments ep' );
        $this->db->join( 'project_details pr', 'ep.project_id = pr.id', 'left' );
        $this->db->join( 'vendor_master v', 'pr.vendor_id = v.id', 'left' );
        $this->db->where( 'ep.employee_type', ucfirst( $type ) );
        $this->db->where( 'ep.employee_id', $id );

        $data[ 'employee' ] = $employee;
        $data[ 'payments' ] = $this->db->get()->result();

        $this->load->view( 'common/header_logged_in' );
        $this->load->view( 'employee_payment_view', $data );
        $this->load->view( 'common/footer' );
    }

}