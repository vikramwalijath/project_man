<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(['session', 'form_validation']);
        if (!$this->session->userdata('user_id')) { redirect('auth'); }
    }

    public function index() {
        $this->db->select('p.*, c.name as carpenter, pa.name as painter, e.name as electrician');
        $this->db->from('project_details p');
        $this->db->join('carpenter_master c', 'c.id = p.carpenter_id', 'left');
        $this->db->join('painter_master pa', 'pa.id = p.painter_id', 'left');
        $this->db->join('electrician_master e', 'e.id = p.electrician_id', 'left');
        $this->db->where('p.status', 1);
        $data['projects'] = $this->db->get()->result();

        $this->load->view('common/header_logged_in');
        $this->load->view('project_list_view', $data);
        $this->load->view('common/footer');
    }

    public function form($id = NULL) {
        $data['carpenters'] = $this->db->get_where('carpenter_master', ['status' => 1])->result();
        $data['painters']   = $this->db->get_where('painter_master', ['status' => 1])->result();
        $data['electricians'] = $this->db->get_where('electrician_master', ['status' => 1])->result();
        
        $data['project'] = ($id) ? $this->db->get_where('project_details', ['id' => $id])->row() : NULL;

        $this->load->view('common/header_logged_in');
        $this->load->view('project_form_view', $data);
        $this->load->view('common/footer');
    }

    public function save() {
        $id = $this->input->post('id');
        $data = [
            'project_name'   => $this->input->post('project_name'),
            'customer_name'  => $this->input->post('customer_name'),
            'assigned_date'  => $this->input->post('assigned_date'),
            'amount'         => $this->input->post('amount'),
            'tax_percent'    => $this->input->post('tax_percent'),
            'total_amount'   => $this->input->post('total_amount'),
            'address'        => $this->input->post('address'),
            'map_location'   => $this->input->post('map_location'),
            'carpenter_id'   => $this->input->post('carpenter_id'),
            'painter_id'     => $this->input->post('painter_id'),
            'electrician_id' => $this->input->post('electrician_id'),
            'customer_name'       => $this->input->post('customer_name'),
    'buyers_order_no'     => $this->input->post('buyers_order_no'),
    'po_date'             => $this->input->post('po_date'),
    'project_external_id' => $this->input->post('project_external_id'),
    'order_id'            => $this->input->post('order_id'),
            'status'         => 1
        ];

        if ($id) {
            $this->db->where('id', $id)->update('project_details', $data);
            $this->session->set_flashdata('success', 'Project updated successfully!');
            } else {
            $this->db->insert('project_details', $data);
            $this->session->set_flashdata('success', 'New project created!');
        }
        redirect('projects');
    }

    public function delete($id) {
        $this->db->where('id', $id)->update('project_details', ['status' => 0]);
        $this->session->set_flashdata('success', 'Project archived.');
        redirect('projects');
    }
// Detailed Project Dashboard
   public function view($id) {
    // 1. Fetch Project Details with Worker Names
    // We use aliases (as carpenter_name) to avoid property conflicts in the View
    $this->db->select('p.*, 
        c.name as carpenter_name, 
        pa.name as painter_name, 
        e.name as electrician_name, 
        c.id as c_id, 
        pa.id as p_id, 
        e.id as e_id');
    $this->db->from('project_details p');
    $this->db->join('carpenter_master c', 'c.id = p.carpenter_id', 'left');
    $this->db->join('painter_master pa', 'pa.id = p.painter_id', 'left');
    $this->db->join('electrician_master e', 'e.id = p.electrician_id', 'left');
    $this->db->where('p.id', $id);
    $data['project'] = $this->db->get()->row();

    // Check if project exists to prevent errors
    if (!$data['project']) {
        $this->session->set_flashdata('error', 'Project not found.');
        redirect('projects');
    }

    // 2. Financial Summaries
    // Total Income Received from Client
    $data['received'] = $this->db->select_sum('amount')
                                ->where('project_id', $id)
                                ->get('project_invoices')
                                ->row()->amount ?? 0;

    // Total Material Expenses (Suppliers)
    $data['sup_exp'] = $this->db->select_sum('amount_sent')
                               ->where('project_id', $id)
                               ->get('supplier_payments')
                               ->row()->amount_sent ?? 0;

    // Total Labor Expenses (Employees)
    $data['emp_exp'] = $this->db->select_sum('amount_paid')
                               ->where('project_id', $id)
                               ->get('employee_payments')
                               ->row()->amount_paid ?? 0;

    // 3. Lists for Data Tables
    // Supplier Payment History
    $data['suppliers'] = $this->db->get_where('supplier_master', ['status' => 1])->result();
    $data['sup_payments'] = $this->db->select('sp.*, s.supplier_name')
                                     ->from('supplier_payments sp')
                                     ->join('supplier_master s', 's.id = sp.supplier_id')
                                     ->where('sp.project_id', $id)
                                     ->get()->result();

    // 4. Employee Payment History (Corrected CASE syntax with FALSE parameter)
    $this->db->select('ep.*, 
        (CASE 
            WHEN ep.employee_type = "carpenter" THEN c.name 
            WHEN ep.employee_type = "painter" THEN p.name 
            WHEN ep.employee_type = "electrician" THEN e.name 
        END) as worker_name', FALSE); 
    $this->db->from('employee_payments ep');
    $this->db->join('carpenter_master c', 'c.id = ep.employee_id AND ep.employee_type = "carpenter"', 'left');
    $this->db->join('painter_master p', 'p.id = ep.employee_id AND ep.employee_type = "painter"', 'left');
    $this->db->join('electrician_master e', 'e.id = ep.employee_id AND ep.employee_type = "electrician"', 'left');
    $this->db->where('ep.project_id', $id);
    $data['emp_payments'] = $this->db->get()->result();

    // 5. Load the UI
    $this->load->view('common/header_logged_in');
    $this->load->view('project_detail_view', $data);
    $this->load->view('common/footer');
}
public function generate_invoice($id) {
    $data['project'] = $this->db->get_where('project_details', ['id' => id])->row();
    $this->load->view('invoice_view', $data);
}
public function invoice($project_id, $invoice_id = NULL) {
    // Get Project/Client details
    $data['project'] = $this->db->get_where('project_details', ['id' => $project_id])->row();
    
    // Get the specific invoice record if ID is provided
    if($invoice_id) {
        $data['saved_invoice'] = $this->db->get_where('project_invoices', ['id' => $invoice_id])->row();
    }

    $this->load->view('generate_invoice', $data);
}
}
?>