<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Gallery_model');
        $this->load->library(['session', 'form_validation', 'upload']);
        $this->load->helper(['url', 'form']);

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
        $this->load->view('admin/layout/header');
        $this->load->view('admin/gallery/index');
        $this->load->view('admin/layout/footer');
    }

    public function get_json()
    {
        $data = $this->Gallery_model->get_all();
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function get_item($id)
    {
        $item = $this->Gallery_model->get_by_id($id);
        if ($item) {
            echo json_encode(['status' => true, 'data' => $item]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => false, 'message' => 'Item not found']);
        }
    }

    private function _upload_image()
    {
        if (empty($_FILES['image_file']['name'])) {
            return ['status' => true, 'path' => null];
        }

        $config['upload_path'] = './assets/uploads/gallery/';
        $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
        $config['file_name'] = 'gallery_' . time();
        $config['overwrite'] = true;
        $config['max_size'] = 2048; // 2MB

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->upload->initialize($config);

        if ($this->upload->do_upload('image_file')) {
            return ['status' => true, 'path' => 'assets/uploads/gallery/' . $this->upload->data('file_name')];
        } else {
            return ['status' => false, 'error' => $this->upload->display_errors('', '')];
        }
    }

    public function store()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $this->form_validation->set_rules('title', 'Title', 'required');

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
            'title' => $this->input->post('title'),
            'description' => $this->input->post('description'),
            'image' => $image_path ? $image_path : 'https://placehold.co/600x400?text=No+Image'
        ];

        if ($this->Gallery_model->insert($data)) {
            echo json_encode(['status' => true, 'message' => 'Foto berhasil ditambahkan']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menambahkan foto']);
        }
    }

    public function update($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $this->form_validation->set_rules('title', 'Title', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => false, 'errors' => validation_errors()]);
            return;
        }

        $item = $this->Gallery_model->get_by_id($id);

        $upload_result = $this->_upload_image();
        if (!$upload_result['status']) {
            echo json_encode(['status' => false, 'errors' => $upload_result['error']]);
            return;
        }
        $image_path = $upload_result['path'];

        $data = [
            'title' => $this->input->post('title'),
            'description' => $this->input->post('description'),
            'image' => $image_path ? $image_path : $item->image
        ];

        if ($this->Gallery_model->update($id, $data)) {
            echo json_encode(['status' => true, 'message' => 'Foto berhasil diupdate']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal mengupdate foto']);
        }
    }

    public function delete($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $item = $this->Gallery_model->get_by_id($id);
        if ($item && strpos($item->image, 'assets/uploads/') !== false) {
            if (file_exists($item->image)) {
                unlink($item->image);
            }
        }

        if ($this->Gallery_model->delete($id)) {
            echo json_encode(['status' => true, 'message' => 'Foto berhasil dihapus']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal menghapus foto']);
        }
    }
}
