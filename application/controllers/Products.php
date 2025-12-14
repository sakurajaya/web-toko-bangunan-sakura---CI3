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

        // Build Product Query
        $this->db->select('*');
        $this->db->from('products');

        if (!empty($category_id)) {
            $this->db->where('category_id', $category_id);
        }

        if (!empty($search_query)) {
            $this->db->like('name', $search_query);
            $this->db->or_like('description', $search_query);
        }

        // Execute Query
        if ($this->db->table_exists('products')) {
            $data['products'] = $this->db->get()->result();
        } else {
            $data['products'] = [];
        }

        $data['search_query'] = $search_query;
        $data['selected_category'] = $category_id;

        $this->load->view('layout/header');
        $this->load->view('products/index', $data);
        $this->load->view('layout/footer');
    }
}
