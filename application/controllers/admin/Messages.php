<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('text');

        // Check authentication
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    public function index()
    {
        // Get all messages ordered by date (newest first)
        $this->db->order_by('created_at', 'DESC');
        $data['messages'] = $this->db->get('messages')->result();

        $this->load->view('admin/layout/header');
        $this->load->view('admin/messages/index', $data);
        $this->load->view('admin/layout/footer');
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('messages');
        $this->session->set_flashdata('success', 'Pesan berhasil dihapus');
        redirect('admin/messages');
    }
}
