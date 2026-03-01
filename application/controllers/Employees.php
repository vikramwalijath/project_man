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
        // Fetch all active employees with their type
        $this->db->select( 'e.*, et.type_name' );
        $this->db->from( 'employees e' );
        $this->db->join( 'employee_types et', 'et.type_name = e.type', 'left' );
        $this->db->where( 'e.status', 1 );
        $data[ 'employees' ] = $this->db->get()->result();

        $this->load->view( 'common/header_logged_in' );
        $this->load->view( 'employee_list_view', $data );
        $this->load->view( 'common/footer' );
    }

    public function form( $id = '' )
 {
        $data[ 'employee' ] = null;

        // If ID is provided, we are EDITING
        if ( $id ) {
            $data[ 'employee' ] = $this->db->get_where( 'employees', [ 'id' => $id ] )->row();
        }

        // Load employee types for dropdown
        $data[ 'employee_types' ] = $this->db->get( 'employee_types' )->result();

        $this->load->view( 'common/header_logged_in' );
        $this->load->view( 'employee_form_view', $data );
        $this->load->view( 'common/footer' );
    }

    // 3. SAVE or UPDATE

    public function save()
 {
        $id   = $this->input->post( 'id' );
        $data = [
            'name'   => $this->input->post( 'name' ),
            'phone'  => $this->input->post( 'phone' ),
            'type'   => $this->input->post( 'type' ),   // comes from dropdown in form
            'status' => 1
        ];

        if ( $id ) {
            // Update existing employee
            $this->db->where( 'id', $id );
            $this->db->update( 'employees', $data );
            $this->session->set_flashdata( 'success', 'Employee updated successfully!' );
        } else {
            // Insert new employee
            $this->db->insert( 'employees', $data );
            $this->session->set_flashdata( 'success', 'Employee added successfully!' );
        }

        redirect( 'employees' );
    }

    // 4. SOFT DELETE ( Set status to 0 )

    public function delete( $id )
 {
        $employee = $this->db->get_where( 'employees', [ 'id' => $id ] )->row();

        if ( !$employee ) {
            $this->session->set_flashdata( 'error', 'Employee not found.' );
        } else {
            // Soft delete: set status = 0
            $this->db->where( 'id', $id );
            $this->db->update( 'employees', [ 'status' => 0 ] );
            $this->session->set_flashdata( 'success', 'Employee removed successfully!' );
        }

        redirect( 'employees' );
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

    public function payments( $id )
 {
        $employee = $this->db->get_where( 'employees', [ 'id' => $id ] )->row();

        if ( !$employee ) {
            show_404();
        }

        $this->db->select( 'ep.id, ep.amount_paid, ep.payment_date,
                       ep.remarks, ep.file_attachment,
                       u1.name as paid_by_name,
                       u2.name as created_by_name,
                       pr.project_name, pr.customer_name, v.vendor_name' );
        $this->db->from( 'employee_payments ep' );
        $this->db->join( 'project_details pr', 'ep.project_id = pr.id', 'left' );
        $this->db->join( 'vendor_master v', 'pr.vendor_id = v.id', 'left' );
        $this->db->join( 'users u1', 'u1.id = ep.paid_by', 'left' );
        // Paid By
        $this->db->join( 'users u2', 'u2.id = ep.created_by', 'left' );
        // Created By
        $this->db->where( 'ep.employee_id', $id );

        $data[ 'employee' ] = $employee;
        $data[ 'payments' ] = $this->db->get()->result();

        $this->load->view( 'common/header_logged_in' );
        $this->load->view( 'employee_payment_view', $data );
        $this->load->view( 'common/footer' );
    }

}