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

        // Pagination Params
        $page = $this->input->get('page');
        $page = (is_numeric($page) && $page > 0) ? (int) $page : 1;
        $per_page = 12; // default limit
        $offset = ($page - 1) * $per_page;

        // 1. Count Total Rows
        $this->_apply_filters($category_id, $search_query);
        $total_rows = $this->db->count_all_results(); // Note: count_all_results resets query builder

        // 2. Fetch Data
        $products = [];
        if ($total_rows > 0) {
            $this->_apply_filters($category_id, $search_query);
            $this->db->limit($per_page, $offset);
            $products = $this->db->get()->result();
        }

        // Pagination Metadata
        $total_pages = ceil($total_rows / $per_page);

        // Return JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'data' => $products,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $total_pages,
                    'total_rows' => $total_rows,
                    'per_page' => $per_page
                ]
            ]));
    }

    private function _apply_filters($category_id, $search_query)
    {
        $this->db->select('products.*, GROUP_CONCAT(categories.name SEPARATOR ", ") as category_name'); // Select columns explicitly if needed
        $this->db->from('products');
        $this->db->join('product_categories', 'product_categories.product_id = products.id', 'left');
        $this->db->join('categories', 'categories.id = product_categories.category_id', 'left');
        $this->db->where('products.status_barang', 1);

        if (!empty($category_id)) {
            $this->db->where('product_categories.category_id', $category_id);
        }

        if (!empty($search_query)) {
            $this->db->group_start();
            $this->db->like('products.name', $search_query);
            $this->db->or_like('products.description', $search_query);
            $this->db->or_like('products.kode_product', $search_query);
            $this->db->group_end();
        }

        $this->db->group_by('products.id');
        $this->db->order_by('products.created_at', 'DESC');
    }
}
