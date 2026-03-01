<?php
defined( 'BASEPATH' ) or exit( 'No direct script access allowed' );

class Projects extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library( [ 'session', 'form_validation' ] );
        if ( !$this->session->userdata( 'user_id' ) ) {
            redirect( 'auth' );
        }
    }

    public function index() {
        $this->db->select( 'p.*, v.vendor_name, GROUP_CONCAT(e.name SEPARATOR ", ") as team' );
        $this->db->from( 'project_details p' );
        $this->db->join( 'vendor_master v', 'v.id = p.vendor_id', 'left' );
        $this->db->join( 'project_team_assignments pta', 'pta.project_id = p.id', 'left' );
        $this->db->join( 'employees e', 'e.id = pta.employee_id', 'left' );
        $this->db->where( 'p.status', 1 );
        $this->db->group_by( 'p.id' );
        $data[ 'projects' ] = $this->db->get()->result();

        $this->load->view( 'common/header_logged_in' );
        $this->load->view( 'project_list_view', $data );
        $this->load->view( 'common/footer' );
    }

    public function form( $id = NULL ) {
        $data[ 'vendors' ] = $this->db->get_where( 'vendor_master', [ 'status' => 1 ] )->result();
        $data[ 'employee_types' ] = $this->db->get( 'employee_types' )->result();
        $data[ 'employees' ] = $this->db->get_where( 'employees', [ 'status' => 1 ] )->result();

        $data[ 'project' ] = ( $id ) ? $this->db->get_where( 'project_details', [ 'id' => $id ] )->row() : NULL;

        // Assigned team
        if ( $id ) {
            $this->db->select( 'pta.id, e.name, e.type' );
            $this->db->from( 'project_team_assignments pta' );
            $this->db->join( 'employees e', 'e.id = pta.employee_id', 'left' );
            $this->db->where( 'pta.project_id', $id );
            $data[ 'assigned_team' ] = $this->db->get()->result();

        } else {
            $data[ 'assigned_team' ] = [];
        }

        $this->load->view( 'common/header_logged_in' );
        $this->load->view( 'project_form_view', $data );
        $this->load->view( 'common/footer' );
    }

    public function save() {
        $id = $this->input->post( 'id' );
        $current_user = $this->session->userdata( 'user_id' );

        $data = [
            'project_name'        => $this->input->post( 'project_name' ),
            'customer_name'       => $this->input->post( 'customer_name' ),
            'hsn_code'            => $this->input->post( 'hsn_code' ),
            'assigned_date'       => $this->input->post( 'assigned_date' ),
            'amount'              => $this->input->post( 'amount' ),
            'tax_percent'         => $this->input->post( 'tax_percent' ),
            'total_amount'        => $this->input->post( 'total_amount' ),
            'address'             => $this->input->post( 'address' ),
            'map_location'        => $this->input->post( 'map_location' ),
            'buyers_order_no'     => $this->input->post( 'buyers_order_no' ),
            'po_date'             => $this->input->post( 'po_date' ),
            'project_external_id' => $this->input->post( 'project_external_id' ),
            'order_id'            => $this->input->post( 'order_id' ),
            'vendor_id'           => $this->input->post( 'vendor_id' ),
            'status'              => 1
        ];

        if ( $id ) {
            $data[ 'updated_by' ] = $current_user;
            $this->db->where( 'id', $id )->update( 'project_details', $data );
            $this->session->set_flashdata( 'success', 'Project updated successfully!' );
        } else {
            $data[ 'created_by' ] = $current_user;
            $data[ 'updated_by' ] = $current_user;
            $this->db->insert( 'project_details', $data );
            $this->session->set_flashdata( 'success', 'New project created!' );
        }
        redirect( 'projects' );
    }

    // Archive project

    public function delete( $id )
 {
        $current_user = $this->session->userdata( 'user_id' );

        $update_data = [
            'status'     => 0,
            'updated_by' => $current_user
        ];

        $this->db->where( 'id', $id )->update( 'project_details', $update_data );
        $this->session->set_flashdata( 'success', 'Project archived.' );
        redirect( 'projects' );
    }

    // Detailed Project Dashboard

    public function view( $id )
 {
        $data[ 'all_users' ] = $this->db->get( 'users' )->result();

        // 1. Fetch project details
        $data[ 'project' ] = $this->db->get_where( 'project_details', [ 'id' => $id ] )->row();

        if ( !$data[ 'project' ] ) {
            $this->session->set_flashdata( 'error', 'Project not found.' );
            redirect( 'projects' );
        }

        // 2. Assigned Team Members ( employees + employee_types )
        $this->db->select( 'pta.id, e.name as employee_name, et.type_name as employee_type' );
        $this->db->from( 'project_team_assignments pta' );
        $this->db->join( 'employees e', 'e.id = pta.employee_id', 'left' );
        $this->db->join( 'employee_types et', 'et.type_name = e.type', 'left' );

        $this->db->where( 'pta.project_id', $id );
        $data[ 'assigned_team' ] = $this->db->get()->result();

        // 3. Financial summaries
        $data[ 'received' ] = $this->db->select_sum( 'amount' )
        ->where( 'project_id', $id )
        ->where( 'is_received', 1 )
        ->get( 'project_invoices' )
        ->row()->amount ?? 0;

        $data[ 'balance_due' ] = $data[ 'project' ]->total_amount - $data[ 'received' ];

        $data[ 'sup_exp' ] = $this->db->select_sum( 'amount_sent' )
        ->where( 'project_id', $id )
        ->get( 'supplier_payments' )
        ->row()->amount_sent ?? 0;

        $data[ 'emp_exp' ] = $this->db->select_sum( 'amount_paid' )
        ->where( 'project_id', $id )
        ->get( 'employee_payments' )
        ->row()->amount_paid ?? 0;

        // 4. Lists
        $data[ 'project_invoices' ] = $this->db->order_by( 'id', 'DESC' )
        ->get_where( 'project_invoices', [ 'project_id' => $id ] )
        ->result();

        $data[ 'suppliers' ] = $this->db->get_where( 'supplier_master', [ 'status' => 1 ] )->result();
        $data[ 'sup_payments' ] = $this->db->select( 'sp.*, s.supplier_name, u.name as payer_name' )
        ->from( 'supplier_payments sp' )
        ->join( 'supplier_master s', 's.id = sp.supplier_id' )
        ->join( 'users u', 'u.id = sp.paid_by', 'left' )
        ->where( 'sp.project_id', $id )
        ->order_by( 'sp.payment_date', 'DESC' )
        ->get()->result();

        // 5. Employee Payment History ( using employees + employee_types )
        $this->db->select( 'ep.*, u.name as payer_name, e.name as worker_name, e.type as worker_type' );
        $this->db->from( 'employee_payments ep' );
        $this->db->join( 'users u', 'u.id = ep.paid_by', 'left' );
        $this->db->join( 'employees e', 'e.id = ep.employee_id', 'left' );
        $this->db->where( 'ep.project_id', $id );
        $this->db->order_by( 'ep.payment_date', 'DESC' );
        $data[ 'emp_payments' ] = $this->db->get()->result();

        $data[ 'invoices' ] = $this->db->order_by( 'id', 'DESC' )
        ->get_where( 'project_invoices', [ 'project_id' => $id ] )
        ->result();

        // 6. Load view
        $this->load->view( 'common/header_logged_in' );
        $this->load->view( 'project_detail_view', $data );
        $this->load->view( 'common/footer' );
    }

    // Generate invoice view

    public function generate_invoice( $id )
 {
        $data[ 'project' ] = $this->db->get_where( 'project_details', [ 'id' => $id ] )->row();
        $this->load->view( 'invoice_view', $data );
    }

    // Invoice creation/edit

    public function invoice( $project_id, $invoice_id = NULL )
 {
        $data[ 'project' ] = $this->db->get_where( 'project_details', [ 'id' => $project_id ] )->row();

        if ( $invoice_id ) {
            $data[ 'saved_invoice' ] = $this->db->get_where( 'project_invoices', [ 'id' => $invoice_id ] )->row();
        }

        $this->load->view( 'generate_invoice', $data );
    }

    // Mark invoice as paid

    public function mark_as_paid( $invoice_id, $project_id )
 {
        $current_user = $this->session->userdata( 'user_id' );
        $update_data = [
            'is_received' => 1,
            'updated_by'  => $current_user,
        ];
        $this->db->where( 'id', $invoice_id )->update( 'project_invoices', $update_data );
        $this->session->set_flashdata( 'success', 'Payment recorded successfully!' );
        redirect( 'projects/view/' . $project_id );
    }

    // Delete invoice

    public function delete_invoice( $invoice_id, $project_id )
 {
        $invoice = $this->db->get_where( 'project_invoices', [ 'id' => $invoice_id ] )->row();

        if ( !$invoice ) {
            $this->session->set_flashdata( 'error', 'Invoice not found.' );
        } elseif ( $invoice->is_received == 1 ) {
            $this->session->set_flashdata( 'error', 'Cannot delete an invoice that has already been paid!' );
        } else {
            $this->db->where( 'id', $invoice_id )->delete( 'project_invoices' );
            $this->session->set_flashdata( 'success', 'Invoice deleted successfully.' );
        }

        redirect( 'projects/view/' . $project_id );
    }

    public function save_team_assignment() {
        $data = [
            'project_id'    => $this->input->post( 'project_id' ),
            'employee_id'   => $this->input->post( 'employee_id' ),
            'assigned_by'   => $this->session->userdata( 'user_id' ),
            'assigned_date' => date( 'Y-m-d' )
        ];
        $this->db->insert( 'project_team_assignments', $data );
        $this->session->set_flashdata( 'success', 'Team member assigned!' );
        redirect( 'projects/form/'.$this->input->post( 'project_id' ) );
    }

    public function remove_team_assignment( $id, $project_id ) {
        $this->db->delete( 'project_team_assignments', [ 'id' => $id ] );
        $this->session->set_flashdata( 'success', 'Team member removed!' );
        redirect( 'projects/form/'.$project_id );
    }

    public function delete_employee_payment( $payment_id, $project_id )
 {
        // Fetch the payment record
        $payment = $this->db->get_where( 'employee_payments', [ 'id' => $payment_id ] )->row();

        if ( !$payment ) {
            $this->session->set_flashdata( 'error', 'Payment record not found.' );
        } else {
            // Perform delete
            $this->db->where( 'id', $payment_id )->delete( 'employee_payments' );
            $this->session->set_flashdata( 'success', 'Employee payment deleted successfully.' );
        }

        // Redirect back to project detail view
        redirect( 'projects/view/' . $project_id );
    }

    public function delete_supplier_payment( $payment_id, $project_id )
 {
        // Fetch the payment record
        $payment = $this->db->get_where( 'supplier_payments', [ 'id' => $payment_id ] )->row();

        if ( !$payment ) {
            $this->session->set_flashdata( 'error', 'Supplier payment record not found.' );
        } else {
            // Perform delete
            $this->db->where( 'id', $payment_id )->delete( 'supplier_payments' );
            $this->session->set_flashdata( 'success', 'Supplier payment deleted successfully.' );
        }

        // Redirect back to project detail view
        redirect( 'projects/view/' . $project_id );
    }

}