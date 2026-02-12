<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(['session']);
        $this->load->helper('url');
        if (!$this->session->userdata('user_id')) { redirect('auth'); }
    }

    // 1. LIST ALL (Only showing active status = 1)
    public function index() {
        $data['carpenters'] = $this->db->get_where('carpenter_master', ['status' => 1])->result();
        $data['painters'] = $this->db->get_where('painter_master', ['status' => 1])->result();
        $data['electricians'] = $this->db->get_where('electrician_master', ['status' => 1])->result();

        $this->load->view('common/header_logged_in');
        $this->load->view('employee_list_view', $data);
        $this->load->view('common/footer');
    }

    // 2. ADD / EDIT VIEW
    public function form($type = '', $id = '') {
        $data['type'] = $type;
        $data['employee'] = null;

        // If ID is provided, we are EDITING
        if ($id) {
            $table = $type . '_master';
            $data['employee'] = $this->db->get_where($table, ['id' => $id])->row();
        }

        $this->load->view('common/header_logged_in');
        $this->load->view('employee_form_view', $data);
        $this->load->view('common/footer');
    }

    // 3. SAVE or UPDATE
    public function save() {
        $type = $this->input->post('emp_type');
        $id = $this->input->post('id');
        $table = $type . '_master';

        $data = [
            'name' => $this->input->post('name'),
            'phone' => $this->input->post('phone'),
            'status' => 1
        ];

        if ($id) {
            // Update existing
            $this->db->where('id', $id);
            $this->db->update($table, $data);
            $this->session->set_flashdata('success', 'Updated successfully!');
        } else {
            // Insert new
            $this->db->insert($table, $data);
            $this->session->set_flashdata('success', 'Added successfully!');
        }
        redirect('employees');
    }

    // 4. SOFT DELETE (Set status to 0)
    public function delete($type, $id) {
        $table = $type . '_master';
        $this->db->where('id', $id);
        $this->db->update($table, ['status' => 0]);
        
        $this->session->set_flashdata('success', 'Employee removed successfully!');
        redirect('employees');
    }
}