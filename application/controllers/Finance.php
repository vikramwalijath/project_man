<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        if (!$this->session->userdata('user_id')) { redirect('auth'); }
    }

   

    public function get_next_invoice_no() {
    // 1. Get the last invoice created
    $last_invoice = $this->db->select('invoice_no')
                             ->order_by('id', 'DESC')
                             ->limit(1)
                             ->get('project_invoices')
                             ->row();

    if ($last_invoice) {
        // 2. Extract the number from 'SK/26/001'
        // We split by '/' and get the last part
        $parts = explode('/', $last_invoice->invoice_no);
        $last_num = (int) end($parts);
        $new_num = $last_num + 1;
    } else {
        // Start with 1 if no invoices exist
        $new_num = 1;
    }

    // 3. Format it back to SK/26/001 (padding with 3 zeros)
    $current_year = date('y');
    return "SK/" . $current_year . "/" . str_pad($new_num, 3, '0', STR_PAD_LEFT);
}

  public function add_invoice() {
    $project_id = $this->input->post('project_id');
    
    $data = [
        'project_id'   => $project_id,
        'invoice_no'   => $this->get_next_invoice_no(),
        'amount'       => $this->input->post('amount'),
        'invoice_date' => $this->input->post('date'),
        'description'  => $this->input->post('description'),
        'hsn_code'     => $this->input->post('hsn_code')
    ];

    // Save to your existing table
    $this->db->insert('project_invoices', $data);
    $invoice_id = $this->db->insert_id();

    // Redirect to the generator
    redirect('projects/invoice/' . $project_id . '/' . $invoice_id);
}

   // Standard upload configuration
    private function do_upload($field_name) {
        if (!empty($_FILES[$field_name]['name'])) {
            $config['upload_path']   = './uploads/payments/';
            $config['allowed_types'] = 'jpg|jpeg|png|pdf|docx';
            $config['max_size']      = 2048; // 2MB
            $config['file_name']     = time() . '_' . $_FILES[$field_name]['name'];

            // Create directory if not exists
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }

            $this->upload->initialize($config);

            if ($this->upload->do_upload($field_name)) {
                return $this->upload->data('file_name');
            }
        }
        return NULL;
    }

    public function add_supplier_payment() {
        $project_id = $this->input->post('project_id');
        $file = $this->do_upload('attachment');

        $data = [
            'project_id'      => $project_id,
            'supplier_id'     => $this->input->post('supplier_id'),
            'invoice_name'    => $this->input->post('invoice_name'),
            'amount_sent'     => $this->input->post('amount_sent'),
            'payment_date'    => $this->input->post('payment_date'),
            'file_attachment' => $file
        ];

        $this->db->insert('supplier_payments', $data);
        $this->session->set_flashdata('success', 'Supplier payment saved!');
        redirect('projects/view/' . $project_id);
    }

    public function add_employee_payment() {
        $project_id = $this->input->post('project_id');
        $file = $this->do_upload('attachment');

        $data = [
            'project_id'      => $project_id,
            'employee_id'     => $this->input->post('employee_id'),
            'employee_type'   => $this->input->post('employee_type'),
            'amount_paid'     => $this->input->post('amount_paid'),
            'payment_date'    => $this->input->post('payment_date'),
            'remarks'         => $this->input->post('remarks'),
            'file_attachment' => $file
        ];

        $this->db->insert('employee_payments', $data);
        $this->session->set_flashdata('success', 'Employee payment saved!');
        redirect('projects/view/' . $project_id);
    }
}