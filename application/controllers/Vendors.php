<?php
defined( 'BASEPATH' ) or exit( 'No direct script access allowed' );

class Vendors extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library( [ 'session', 'form_validation' ] );
        if ( !$this->session->userdata( 'user_id' ) ) {
            redirect( 'auth' );
        }
    }

    public function index() {
        $data[ 'vendors' ] = $this->db->get_where( 'vendor_master', [ 'status' => 1 ] )->result();
        $this->load->view( 'common/header_logged_in' );
        $this->load->view( 'vendor_list_view', $data );
        $this->load->view( 'common/footer' );
    }

    public function form( $id = NULL ) {
        $data[ 'vendor' ] = ( $id ) ? $this->db->get_where( 'vendor_master', [ 'id' => $id ] )->row() : NULL;
        $this->load->view( 'common/header_logged_in' );
        $this->load->view( 'vendor_form_view', $data );
        $this->load->view( 'common/footer' );
    }

    public function save() {
        $id = $this->input->post( 'id' );

        // Validation rules
        $this->form_validation->set_rules( 'vendor_name', 'Vendor Name', 'required|min_length[3]|max_length[100]' );
        $this->form_validation->set_rules( 'shop_address', 'Shop Address', 'required|min_length[5]' );

        if ( $this->form_validation->run() == FALSE ) {
            // Reload form with errors
            $data[ 'vendor' ] = ( $id ) ? $this->db->get_where( 'vendor_master', [ 'id' => $id ] )->row() : NULL;
            $this->load->view( 'common/header_logged_in' );
            $this->load->view( 'vendor_form_view', $data );
            $this->load->view( 'common/footer' );
        } else {
            // Valid input
            $data = [
                'vendor_name' => $this->input->post( 'vendor_name' ),
                'shop_address' => $this->input->post( 'shop_address' ),
                'status' => 1
            ];

            if ( $id ) {
                $this->db->where( 'id', $id )->update( 'vendor_master', $data );
                $this->session->set_flashdata( 'success', 'Vendor updated successfully!' );
            } else {
                $this->db->insert( 'vendor_master', $data );
                $this->session->set_flashdata( 'success', 'New vendor added!' );
            }
            redirect( 'vendors' );
        }
    }

    public function delete( $id ) {
        $this->db->where( 'id', $id )->update( 'vendor_master', [ 'status' => 0 ] );
        $this->session->set_flashdata( 'success', 'Vendor archived.' );
        redirect( 'vendors' );
    }
}