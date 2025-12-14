<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->load->helper('text');
    }

    public function index()
    {
        $category_id = $this->input->get('category');
        $search_query = $this->input->get('q');

        // Fetch Categories for Sidebar
        $data['categories'] = [];
        if ($this->db->table_exists('categories')) {
            $data['categories'] = $this->db->get('categories')->result();
        }

        // We no longer fetch products here for the view, they will be fetched via JS
        // But we pass initial params so JS knows what to load first if needed
        $data['initial_category'] = $category_id;
        $data['initial_search'] = $search_query;

        $this->load->view('layout/header');
        $this->load->view('products/index', $data);
        $this->load->view('layout/footer');
    }

    public function get_json()
    {
        $category_id = $this->input->get('category');
        $search_query = $this->input->get('q');

        $this->db->select('*');
        $this->db->from('products');
        $this->db->where('status_barang', 1);

        if (!empty($category_id)) {
            $this->db->where('category_id', $category_id);
        }

        if (!empty($search_query)) {
            $this->db->group_start();
            $this->db->like('name', $search_query);
            
            $this->db->or_like('description', $search_query);
            $this->db->or_like('kode_product', $search_query); // Add search by code
            $this->db->group_end();
        }

        $products = [];
        if ($this->db->table_exists('products')) {
            $products = $this->db->get()->result();
        }

        // Return JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['data' => $products]));
    }
}
