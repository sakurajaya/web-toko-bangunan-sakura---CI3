<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model'); // Load model
    }

    public function index()
    {
        // Data for the view
        $data = [];

        // Attempt to fetch data from database if tables exist
        if ($this->db->table_exists('categories')) {
            $this->db->order_by('order_num', 'ASC');
            $data['categories'] = $this->db->get('categories')->result();
        } else {
            $data['categories'] = [];
        }

        // Fetch New Arrivals
        if ($this->db->table_exists('products')) {
            // Using model method
            $data['new_arrivals'] = $this->Product_model->get_random_new_arrivals(10); // Limit 10 and random for carousel
        } else {
            $data['new_arrivals'] = [];
        }

        if ($this->db->table_exists('gallery')) {
            $data['gallery'] = $this->db->get('gallery')->result();
        } else {
            $data['gallery'] = [];
        }

        $this->load->view('layout/header');
        $this->load->view('home/index', $data);
        $this->load->view('layout/footer');
    }

    public function send_message()
    {
        // Simple validation
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $message = $this->input->post('message');

        if (!empty($name) && !empty($message)) {
            $data = [
                'name' => $name,
                'email' => $email,
                'message' => $message
            ];

            if ($this->db->insert('messages', $data)) {
                $this->session->set_flashdata('success', 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.');
            } else {
                $this->session->set_flashdata('error', 'Maaf, terjadi kesalahan saat mengirim pesan.');
            }
        } else {
            $this->session->set_flashdata('error', 'Nama dan Pesan wajib diisi.');
        }

        redirect('home#contact');
    }
}
