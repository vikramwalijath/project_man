<?php
defined( 'BASEPATH' ) or exit( 'No direct script access allowed' );

class Invoices extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library( [ 'session', 'form_validation' ] );
        if ( !$this->session->userdata( 'user_id' ) ) {
            redirect( 'auth' );
        }
    }

    public function index() {
        // Filters
        $month = $this->input->get( 'month' );
        $year = $this->input->get( 'year' );
        $fy   = $this->input->get( 'fy' );
        // e.g. 2025-2026
        $vendor_id = $this->input->get( 'vendor_id' );

        $this->db->select( 'pi.*, pd.project_name, pd.customer_name, v.vendor_name' );
        $this->db->from( 'project_invoices pi' );
        $this->db->join( 'project_details pd', 'pi.project_id = pd.id', 'left' );
        $this->db->join( 'vendor_master v', 'pd.vendor_id = v.id', 'left' );

        if ( $month ) {
            $this->db->where( 'MONTH(pi.invoice_date)', $month );
        }
        if ( $year ) {
            $this->db->where( 'YEAR(pi.invoice_date)', $year );
        }
        if ( $fy ) {
            // Financial year filter ( April–March )
            $fy_parts = explode( '-', $fy );
            $start = $fy_parts[ 0 ] . '-04-01';
            $end   = $fy_parts[ 1 ] . '-03-31';
            $this->db->where( "pi.invoice_date BETWEEN '$start' AND '$end'" );
        }
        if ( $vendor_id ) {
            $this->db->where( 'pd.vendor_id', $vendor_id );
        }

        $data[ 'invoices' ] = $this->db->get()->result();
        $data[ 'vendors' ]  = $this->db->get_where( 'vendor_master', [ 'status' => 1 ] )->result();

        $this->load->view( 'common/header_logged_in' );
        $this->load->view( 'invoice_list_view', $data );
        $this->load->view( 'common/footer' );
    }

    public function download( $id ) {
        $invoice = $this->db->get_where( 'project_invoices', [ 'id' => $id ] )->row();
        if ( $invoice && $invoice->file_attachment ) {
            redirect( base_url( 'uploads/invoices/'.$invoice->file_attachment ) );
        } else {
            $this->session->set_flashdata( 'error', 'Invoice file not found.' );
            redirect( 'invoices' );
        }
    }
}