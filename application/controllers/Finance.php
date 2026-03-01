<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Finance extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ( !$this->session->userdata( 'user_id' ) ) {
            redirect( 'auth' );
        }
    }

    public function get_next_invoice_no() {
        // 1. Get the last invoice created
        $last_invoice = $this->db->select( 'invoice_no' )
        ->order_by( 'id', 'DESC' )
        ->limit( 1 )
        ->get( 'project_invoices' )
        ->row();

        if ( $last_invoice ) {
            // 2. Extract the number from 'SK/26/001'
            // We split by '/' and get the last part
            $parts = explode( '/', $last_invoice->invoice_no );
            $last_num = ( int ) end( $parts );
            $new_num = $last_num + 1;
        } else {
            // Start with 1 if no invoices exist
            $new_num = 1;
        }

        // 3. Format it back to SK/26/001 ( padding with 3 zeros )
        $current_year = date( 'y' );
        return 'SK/' . $current_year . '/' . str_pad( $new_num, 3, '0', STR_PAD_LEFT );
    }

    public function add_invoice() {
        $project_id = $this->input->post( 'project_id' );
        $posted_amount = $this->input->post( 'amount' );

        // 1. VALIDATION: Amount must be greater than 0
        if ( $posted_amount <= 0 || empty( $posted_amount ) ) {
            $this->session->set_flashdata( 'error', 'Invoice amount must be greater than 0!' );
            redirect( 'projects/view/' . $project_id );
            return;
            // Stop execution
        }

        $data = [
            'project_id'   => $project_id,
            'invoice_no'   => $this->get_next_invoice_no(),
            'amount'       => $posted_amount,
            'invoice_date' => $this->input->post( 'date' ),
            'created_by'   => $this->session->userdata( 'user_id' ),
        ];

        // 2. Save to database
        if ( $this->db->insert( 'project_invoices', $data ) ) {
            $invoice_id = $this->db->insert_id();
            $this->session->set_flashdata( 'success', 'Invoice generated successfully!' );
            redirect( 'projects/view/' . $project_id );
        } else {
            $this->session->set_flashdata( 'error', 'Database error: Could not save invoice.' );
            redirect( 'projects/view/' . $project_id );
        }
    }

    // Standard upload configuration

    private function do_upload( $field_name ) {
        if ( !empty( $_FILES[ $field_name ][ 'name' ] ) ) {
            $config[ 'upload_path' ]   = './uploads/payments/';
            $config[ 'allowed_types' ] = 'jpg|jpeg|png|pdf|docx';
            $config[ 'max_size' ]      = 2048;
            // 2MB
            $config[ 'encrypt_name' ]  = TRUE;
            // Recommended: prevents issues with special characters/spaces

            // Create directory if it doesn't exist
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        // --- THE FIX: Load the library before initializing ---
        $this->load->library('upload'); 
        $this->upload->initialize($config);
        // -----------------------------------------------------

        if ($this->upload->do_upload($field_name)) {
            return $this->upload->data('file_name');
        } else {
            // Optional: Log errors for debugging
            // log_message('error', $this->upload->display_errors());
            return NULL;
        }
    }
    return NULL;
}

    public function add_supplier_payment() {
        $project_id = $this->input->post( 'project_id' );
        $file = $this->do_upload( 'attachment' );

        $data = [
            'project_id'      => $project_id,
            'supplier_id'     => $this->input->post( 'supplier_id' ),
            'invoice_name'    => $this->input->post( 'invoice_name' ),
            'amount_sent'     => $this->input->post( 'amount_sent' ),
            'payment_date'    => $this->input->post( 'payment_date' ),
            'paid_by'         => $this->input->post( 'supplier_paid_by' ), // New field

            'file_attachment' => $file
        ];

        $this->db->insert( 'supplier_payments', $data );
        $this->session->set_flashdata( 'success', 'Supplier payment saved!' );
        redirect( 'projects/view/' . $project_id );
    }

    public function add_employee_payment() {
        $project_id = $this->input->post( 'project_id' );

        // 1. Split the combined 'type|id' value
        $worker_data = explode( '|', $this->input->post( 'worker_info' ) );
        $emp_type = $worker_data[ 0 ];
        // e.g., carpenter
        $emp_id   = $worker_data[ 1 ];
        // e.g., 5

        $file = $this->do_upload( 'attachment' );

        $data = [
            'project_id'      => $project_id,
            'employee_id'     => $emp_id,
            'employee_type'   => $emp_type,
            'amount_paid'     => $this->input->post( 'amount_paid' ),
            'payment_date'    => $this->input->post( 'payment_date' ),
            'remarks'         => $this->input->post( 'remarks' ),
            'paid_by'         => $this->input->post( 'paid_by' ), // New field
            'file_attachment' => $file,
            'created_by'      => $this->session->userdata( 'user_id' )
        ];

        $this->db->insert( 'employee_payments', $data );
        $this->session->set_flashdata( 'success', 'Employee payment saved!' );
        redirect( 'projects/view/' . $project_id );
        }

    }