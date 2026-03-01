<?php
defined( 'BASEPATH' ) or exit( 'No direct script access allowed' );

class Projects extends CI_Controller
 {

    public function __construct()
 {
        parent::__construct();
        $this->load->library( [ 'session', 'form_validation' ] );
        if ( !$this->session->userdata( 'user_id' ) ) {
            redirect( 'auth' );
        }
    }

    public function index()
 {
        $this->db->select( 'p.*, c.name as carpenter, pa.name as painter, e.name as electrician' );
        $this->db->from( 'project_details p' );
        $this->db->join( 'carpenter_master c', 'c.id = p.carpenter_id', 'left' );
        $this->db->join( 'painter_master pa', 'pa.id = p.painter_id', 'left' );
        $this->db->join( 'electrician_master e', 'e.id = p.electrician_id', 'left' );
        $this->db->where( 'p.status', 1 );
        $data[ 'projects' ] = $this->db->get()->result();

        $this->load->view( 'common/header_logged_in' );
        $this->load->view( 'project_list_view', $data );
        $this->load->view( 'common/footer' );
    }

    public function form( $id = NULL )
 {
        $data[ 'carpenters' ] = $this->db->get_where( 'carpenter_master', [ 'status' => 1 ] )->result();
        $data[ 'painters' ]   = $this->db->get_where( 'painter_master', [ 'status' => 1 ] )->result();
        $data[ 'electricians' ] = $this->db->get_where( 'electrician_master', [ 'status' => 1 ] )->result();
        $data[ 'vendors' ] = $this->db->get_where( 'vendor_master', [ 'status' => 1 ] )->result();

        $data[ 'project' ] = ( $id ) ? $this->db->get_where( 'project_details', [ 'id' => $id ] )->row() : NULL;

        $this->load->view( 'common/header_logged_in' );
        $this->load->view( 'project_form_view', $data );
        $this->load->view( 'common/footer' );
    }

    public function save()
 {
        $id = $this->input->post( 'id' );
        $current_user = $this->session->userdata( 'user_id' );
        // Ensure 'user_id' matches your session key

        $data = [
            'project_name'        => $this->input->post( 'project_name' ),
            'customer_name'       => $this->input->post( 'customer_name' ),
            'hsn_code'            => $this->input->post( 'hsn_code' ), // Add this line
            'assigned_date'       => $this->input->post( 'assigned_date' ),
            'amount'              => $this->input->post( 'amount' ),
            'tax_percent'         => $this->input->post( 'tax_percent' ),
            'total_amount'        => $this->input->post( 'total_amount' ),
            'address'             => $this->input->post( 'address' ),
            'map_location'        => $this->input->post( 'map_location' ),
            'carpenter_id'        => $this->input->post( 'carpenter_id' ),
            'painter_id'          => $this->input->post( 'painter_id' ),
            'electrician_id'      => $this->input->post( 'electrician_id' ),
            'buyers_order_no'     => $this->input->post( 'buyers_order_no' ),
            'po_date'             => $this->input->post( 'po_date' ),
            'project_external_id' => $this->input->post( 'project_external_id' ),
            'order_id'            => $this->input->post( 'order_id' ),
            'vendor_id' => $this->input->post( 'vendor_id' ),
            'status'              => 1
        ];

        if ( $id ) {
            // Record who is modifying the project
            $data[ 'updated_by' ] = $current_user;

            $this->db->where( 'id', $id )->update( 'project_details', $data );
            $this->session->set_flashdata( 'success', 'Project updated successfully!' );
        } else {
            // Record who created the project
            $data[ 'created_by' ] = $current_user;

            // updated_by is usually null on creation, but some developers set it to creator as well
            $data[ 'updated_by' ] = $current_user;

            $this->db->insert( 'project_details', $data );
            $this->session->set_flashdata( 'success', 'New project created!' );
        }
        redirect( 'projects' );
    }

    public function delete( $id )
 {
        $current_user = $this->session->userdata( 'user_id' );

        // Track who archived the project
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
        $data[ 'all_users' ] = $this->db->get_where( 'users' )->result();
        // 1. Fetch Project Details with Worker Names
        $this->db->select( 'p.*, 
        c.name as carpenter_name, 
        pa.name as painter_name, 
        e.name as electrician_name, 
        c.id as c_id, 
        pa.id as p_id, 
        e.id as e_id' );
        $this->db->from( 'project_details p' );
        $this->db->join( 'carpenter_master c', 'c.id = p.carpenter_id', 'left' );
        $this->db->join( 'painter_master pa', 'pa.id = p.painter_id', 'left' );
        $this->db->join( 'electrician_master e', 'e.id = p.electrician_id', 'left' );
        $this->db->where( 'p.id', $id );
        $data[ 'project' ] = $this->db->get()->row();

        if ( !$data[ 'project' ] ) {
            $this->session->set_flashdata( 'error', 'Project not found.' );
            redirect( 'projects' );
        }

        // 2. Financial Summaries ( Logic updated for 'Received' status )

        // NEW LOGIC: Only sum amounts where payment is actually received ( is_received = 1 )
        $data[ 'received' ] = $this->db->select_sum( 'amount' )
        ->where( 'project_id', $id )
        ->where( 'is_received', 1 ) // Only count true payments
        ->get( 'project_invoices' )
        ->row()->amount ?? 0;

        // Calculate Balance Due ( Total Project Value - Received Amount )
        $data[ 'balance_due' ] = $data[ 'project' ]->total_amount - $data[ 'received' ];

        // Total Material Expenses
        $data[ 'sup_exp' ] = $this->db->select_sum( 'amount_sent' )
        ->where( 'project_id', $id )
        ->get( 'supplier_payments' )
        ->row()->amount_sent ?? 0;

        // Total Labor Expenses
        $data[ 'emp_exp' ] = $this->db->select_sum( 'amount_paid' )
        ->where( 'project_id', $id )
        ->get( 'employee_payments' )
        ->row()->amount_paid ?? 0;

        // 3. Lists for Data Tables

        // NEW: Get all invoices for this project to show in the List
        $data[ 'project_invoices' ] = $this->db->order_by( 'id', 'DESC' )
        ->get_where( 'project_invoices', [ 'project_id' => $id ] )
        ->result();

        // Supplier Payment History
        $data[ 'suppliers' ] = $this->db->get_where( 'supplier_master', [ 'status' => 1 ] )->result();
        $data[ 'sup_payments' ] = $this->db->select( 'sp.*, s.supplier_name, u.name as payer_name' )
        ->from( 'supplier_payments sp' )
        ->join( 'supplier_master s', 's.id = sp.supplier_id' )
        ->join( 'users u', 'u.id = sp.paid_by', 'left' ) // Get the name of the user who paid
        ->where( 'sp.project_id', $id )
        ->order_by( 'sp.payment_date', 'DESC' ) // Most recent bills first
        ->get()->result();

        // 4. Employee Payment History ( Updated with Paid By information )
        $this->db->select( 'ep.*, 
    u.name as payer_name, 
    (CASE 
        WHEN ep.employee_type = "carpenter" THEN c.name 
        WHEN ep.employee_type = "painter" THEN p.name 
        WHEN ep.employee_type = "electrician" THEN e.name 
    END) as worker_name', FALSE );

        // 4. Employee Payment History ( Updated with Paid By & Paid Date )
        $this->db->select( 'ep.*, 
    u.name as payer_name, 
    (CASE 
        WHEN ep.employee_type = "carpenter" THEN c.name 
        WHEN ep.employee_type = "painter" THEN p.name 
        WHEN ep.employee_type = "electrician" THEN e.name 
    END) as worker_name', FALSE );

        $this->db->from( 'employee_payments ep' );
        $this->db->join( 'users u', 'u.id = ep.paid_by', 'left' );
        $this->db->join( 'carpenter_master c', 'c.id = ep.employee_id AND ep.employee_type = "carpenter"', 'left' );
        $this->db->join( 'painter_master p', 'p.id = ep.employee_id AND ep.employee_type = "painter"', 'left' );
        $this->db->join( 'electrician_master e', 'e.id = ep.employee_id AND ep.employee_type = "electrician"', 'left' );

        $this->db->where( 'ep.project_id', $id );
        $this->db->order_by( 'ep.payment_date', 'DESC' );
        // Shows newest payments first
        $data[ 'emp_payments' ] = $this->db->get()->result();

        $data[ 'invoices' ] = $this->db->order_by( 'id', 'DESC' )
        ->get_where( 'project_invoices', [ 'project_id' => $id ] )
        ->result();
        // 5. Load the UI
        $this->load->view( 'common/header_logged_in' );
        $this->load->view( 'project_detail_view', $data );
        $this->load->view( 'common/footer' );
    }

    public function generate_invoice( $id )
 {
        $data[ 'project' ] = $this->db->get_where( 'project_details', [ 'id' => id ] )->row();
        $this->load->view( 'invoice_view', $data );
    }

    public function invoice( $project_id, $invoice_id = NULL )
 {
        // Get Project/Client details
        $data[ 'project' ] = $this->db->get_where( 'project_details', [ 'id' => $project_id ] )->row();

        // Get the specific invoice record if ID is provided
        if ( $invoice_id ) {
            $data[ 'saved_invoice' ] = $this->db->get_where( 'project_invoices', [ 'id' => $invoice_id ] )->row();
        }

        $this->load->view( 'generate_invoice', $data );
    }

    public function mark_as_paid( $invoice_id, $project_id )
 {
        // 1. Get current logged-in user
        $current_user = $this->session->userdata( 'user_id' );

        // 2. Prepare update data
        $update_data = [
            'is_received' => 1,
            'updated_by'  => $current_user,
            // The updated_at column will update automatically via MySQL
        ];

        // 3. Update the database
        $this->db->where( 'id', $invoice_id );
        $this->db->update( 'project_invoices', $update_data );

        // 4. Redirect back to project view ( Verify the route name matches your setup )
        $this->session->set_flashdata( 'success', 'Payment recorded successfully!' );

        // Note: Use 'projects/view/' if that is your method name, or 'projects/details/' as per your code
        redirect( 'projects/view/' . $project_id );

    }

    public function delete_invoice( $invoice_id, $project_id ) 
 {
        // 1. Fetch the invoice to check its status
        $invoice = $this->db->get_where( 'project_invoices', [ 'id' => $invoice_id ] )->row();

        if ( !$invoice ) {
            $this->session->set_flashdata( 'error', 'Invoice not found.' );
        }

        // 2. Safety check: Only delete if amount is NOT received
        elseif ( $invoice->is_received == 1 ) {
            $this->session->set_flashdata( 'error', 'Cannot delete an invoice that has already been paid!' );
        } else {
            // 3. Perform the hard delete
            $this->db->where( 'id', $invoice_id );
            $this->db->delete( 'project_invoices' );
            $this->session->set_flashdata( 'success', 'Invoice deleted successfully.' );
        }

        // Redirect back to the project detail view
        redirect( 'projects/view/' . $project_id );
    }
}