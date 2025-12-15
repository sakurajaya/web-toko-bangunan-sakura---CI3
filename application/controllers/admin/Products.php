<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->database(); // Redundant if loaded in model, but good for safety
        $this->load->library(['session', 'form_validation', 'upload']);
        $this->load->helper(['url', 'text', 'form']);

        // Check authentication
        if (!$this->session->userdata('logged_in')) {
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode(['status' => false, 'message' => 'Session expired']);
                exit;
            }
            redirect('auth');
        }
    }

    public function index()
    {
        $data['categories'] = $this->db->get('categories')->result();
        $this->load->view('admin/layout/header');
        $this->load->view('admin/products/index', $data);
        $this->load->view('admin/layout/footer');
    }

    public function get_json()
    {
        $products = $this->Product_model->get_all();
        // Add action buttons or format data if needed, but raw JSON is fine for now
        // We'll process it in JS
        header('Content-Type: application/json');
        echo json_encode($products);
    }

    public function get_item($id)
    {
        $product = $this->Product_model->get_by_id($id);
        if ($product) {
            header('Content-Type: application/json');
            echo json_encode(['status' => true, 'data' => $product]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Product not found']);
        }
    }

    private function _upload_image()
    {
        if (empty($_FILES['image_file']['name'])) {
            return ['status' => true, 'path' => null];
        }

        $config['upload_path'] = './assets/uploads/products/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
        $config['file_name'] = 'prod_' . time();
        $config['overwrite'] = true;
        $config['max_size'] = 2048; // 2MB

        // Create directory if not exists
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->upload->initialize($config);

        if ($this->upload->do_upload('image_file')) {
            return ['status' => true, 'path' => 'assets/uploads/products/' . $this->upload->data('file_name')];
        } else {
            return ['status' => false, 'error' => $this->upload->display_errors('', '')];
        }
    }

    public function store()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $this->form_validation->set_rules('kode_product', 'Kode Produk', 'required|is_unique[products.kode_product]');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('price', 'Price', 'numeric|required');

        // Manual check for category_ids array
        if (empty($this->input->post('category_ids'))) {
            $this->form_validation->set_rules('category_ids[]', 'Category', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'errors' => validation_errors()]);
            return;
        }

        $upload_result = $this->_upload_image();
        if (!$upload_result['status']) {
            echo json_encode(['status' => false, 'errors' => $upload_result['error']]);
            return;
        }
        $image_path = $upload_result['path'];

        $data = [
            'kode_product' => $this->input->post('kode_product'),
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price'),
            'image' => $image_path ? $image_path : 'assets/products/default.png'
        ];

        $category_ids = $this->input->post('category_ids');

        if ($this->Product_model->insert($data, $category_ids)) {
            echo json_encode(['status' => true, 'message' => 'Product created successfully']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to create product']);
        }
    }

    public function update($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        // Unique check only if code changed
        $input_kode = $this->input->post('kode_product');
        $product = $this->Product_model->get_by_id($id);

        if ($input_kode != $product->kode_product) {
            $this->form_validation->set_rules('kode_product', 'Kode Produk', 'required|is_unique[products.kode_product]');
        } else {
            $this->form_validation->set_rules('kode_product', 'Kode Produk', 'required');
        }

        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('price', 'Price', 'numeric|required');

        if (empty($this->input->post('category_ids'))) {
            $this->form_validation->set_rules('category_ids[]', 'Category', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'errors' => validation_errors()]);
            return;
        }

        $upload_result = $this->_upload_image();
        if (!$upload_result['status']) {
            echo json_encode(['status' => false, 'errors' => $upload_result['error']]);
            return;
        }
        $image_path = $upload_result['path'];

        $data = [
            'kode_product' => $this->input->post('kode_product'),
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price'),
            'status_barang' => $this->input->post('status_barang'),
            'image' => $image_path ? $image_path : $product->image // Keep old image if no new upload
        ];

        $category_ids = $this->input->post('category_ids');

        if ($this->Product_model->update($id, $data, $category_ids)) {
            echo json_encode(['status' => true, 'message' => 'Product updated successfully']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to update product']);
        }
    }

    public function delete($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $product = $this->Product_model->get_by_id($id);
        if ($product && strpos($product->image, 'assets/uploads/') !== false) {
            if (file_exists($product->image)) {
                unlink($product->image);
            }
        }

        if ($this->Product_model->delete($id)) {
            echo json_encode(['status' => true, 'message' => 'Product deleted successfully']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Failed to delete product']);
        }
    }

    public function migrate_db()
    {
        $this->load->dbforge();
        $fields = [
            'kode_product' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
                'after' => 'id'
            ]
        ];
        if ($this->dbforge->add_column('products', $fields)) {
            echo "Column kode_product added successfully!";
        } else {
            echo "Failed to add column or it already exists.";
        }
    }

    public function migrate_multi_category()
    {
        // 1. Create product_categories table
        $sql = "CREATE TABLE IF NOT EXISTS product_categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            category_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
        )";

        if ($this->db->query($sql)) {
            echo "Table product_categories created successfully.<br>";
        } else {
            echo "Failed to create table product_categories.<br>";
            return;
        }

        // 2. Migrate existing data
        // Check if data already exists to avoid duplication
        $count = $this->db->count_all('product_categories');
        if ($count > 0) {
            echo "Data migration skipped: product_categories table already has data.<br>";
        } else {
            $products = $this->db->get('products')->result();
            $migrated_count = 0;
            foreach ($products as $product) {
                if (!empty($product->category_id)) {
                    $data = [
                        'product_id' => $product->id,
                        'category_id' => $product->category_id
                    ];
                    $this->db->insert('product_categories', $data);
                    $migrated_count++;
                }
            }
            echo "Migrated $migrated_count products to pivot table.<br>";
        }
    }
}
