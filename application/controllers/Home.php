<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Data for the view
        $data = [];

        // Attempt to fetch data from database if tables exist
        // This makes the app robust if SQL hasn't been imported yet

        if ($this->db->table_exists('categories')) {
            $data['categories'] = $this->db->get('categories')->result();
        } else {
            $data['categories'] = [];
        }

        if ($this->db->table_exists('products')) {
            // For now, getting all products, or we could limit
            $data['products'] = $this->db->get('products')->result();
        } else {
            $data['products'] = [];
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
}
