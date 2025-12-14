<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller
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
        // Get all categories
        $data['categories'] = [];
        if ($this->db->table_exists('categories')) {
            $data['categories'] = $this->db->get('categories')->result();
        }

        $this->load->view('admin/layout/header');
        $this->load->view('admin/categories/index', $data);
        $this->load->view('admin/layout/footer');
    }

    private function _upload_image()
    {
        $config['upload_path'] = './assets/uploads/categories/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
        $config['file_name'] = 'cat_' . time();
        $config['overwrite'] = true;
        $config['max_size'] = 2048; // 2MB

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('image_file')) {
            return 'assets/uploads/categories/' . $this->upload->data('file_name');
        }

        // If error, you might want to log it or handle it. 
        // For now returning null so text input can be used as fallback or ignored.
        return null;
    }

    public function add()
    {
        // Process Form
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $image_path = $this->_upload_image();

            // Fallback to text input if upload failed or empty, 
            // OR prefer text input if provided? 
            // Logic: If upload exists, use it. Else use text input.
            if (!$image_path) {
                $image_path = $this->input->post('image');
            }

            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'image' => $image_path ? $image_path : 'assets/categories/default.jpg'
            ];

            $this->db->insert('categories', $data);
            $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan');
            redirect('admin/categories');
        }

        $this->load->view('admin/layout/header');
        $this->load->view('admin/categories/form');
        $this->load->view('admin/layout/footer');
    }

    public function edit($id)
    {
        $category = $this->db->get_where('categories', ['id' => $id])->row();

        if (!$category) {
            show_404();
        }

        // Process Form
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $image_path = $this->_upload_image();

            if (!$image_path) {
                // Keep old image if no new upload
                // Also check if user manually updated text input (optional, but good for flexibility)
                $text_input = $this->input->post('image');
                $image_path = !empty($text_input) ? $text_input : $category->image;
            }

            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'image' => $image_path
            ];

            $this->db->where('id', $id);
            $this->db->update('categories', $data);
            $this->session->set_flashdata('success', 'Kategori berhasil diperbarui');
            redirect('admin/categories');
        }

        $data['category'] = $category;

        $this->load->view('admin/layout/header');
        $this->load->view('admin/categories/form', $data);
        $this->load->view('admin/layout/footer');
    }

    public function delete($id)
    {
        $category = $this->db->get_where('categories', ['id' => $id])->row();

        // Optional: Delete physical file if it exists and is in upload folder
        if ($category && strpos($category->image, 'assets/uploads/categories/') !== false) {
            if (file_exists($category->image)) {
                unlink($category->image);
            }
        }

        $this->db->where('id', $id);
        $this->db->delete('categories');
        $this->session->set_flashdata('success', 'Kategori berhasil dihapus');
        redirect('admin/categories');
    }
}
