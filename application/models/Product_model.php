<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        $this->db->select('products.*, GROUP_CONCAT(categories.name SEPARATOR ", ") as category_name, GROUP_CONCAT(categories.id) as category_ids');
        $this->db->from('products');
        $this->db->join('product_categories', 'product_categories.product_id = products.id', 'left');
        $this->db->join('categories', 'categories.id = product_categories.category_id', 'left');
        $this->db->group_by('products.id');
        $this->db->order_by('products.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        $product = $this->db->get_where('products', ['id' => $id])->row();
        if ($product) {
            $product->categories = $this->db->select('category_id')
                ->from('product_categories')
                ->where('product_id', $id)
                ->get()
                ->result_array();
            $product->category_ids = array_column($product->categories, 'category_id');
        }
        return $product;
    }

    public function insert($data, $categories = [])
    {
        $this->db->trans_start();
        $this->db->insert('products', $data);
        $product_id = $this->db->insert_id();

        if (!empty($categories)) {
            $batch_data = [];
            foreach ($categories as $cat_id) {
                $batch_data[] = [
                    'product_id' => $product_id,
                    'category_id' => $cat_id
                ];
            }
            if (!empty($batch_data)) {
                $this->db->insert_batch('product_categories', $batch_data);
            }
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function update($id, $data, $categories = [])
    {
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->update('products', $data);

        // Update categories if provided
        if ($categories !== null) {
            $this->db->delete('product_categories', ['product_id' => $id]);

            if (!empty($categories)) {
                $batch_data = [];
                foreach ($categories as $cat_id) {
                    $batch_data[] = [
                        'product_id' => $id,
                        'category_id' => $cat_id
                    ];
                }
                if (!empty($batch_data)) {
                    $this->db->insert_batch('product_categories', $batch_data);
                }
            }
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_new_arrivals($limit = 8)
    {
        $this->db->select('products.*, GROUP_CONCAT(categories.name SEPARATOR ", ") as category_name');
        $this->db->from('products');
        $this->db->join('product_categories', 'product_categories.product_id = products.id', 'left');
        $this->db->join('categories', 'categories.id = product_categories.category_id', 'left');
        $this->db->where('products.is_new_arrival', 1);
        $this->db->where('products.status_barang', 1);
        $this->db->group_by('products.id');
        $this->db->order_by('products.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_random_new_arrivals($limit = 10)
    {
        $this->db->select('products.*, GROUP_CONCAT(categories.name SEPARATOR ", ") as category_name');
        $this->db->from('products');
        $this->db->join('product_categories', 'product_categories.product_id = products.id', 'left');
        $this->db->join('categories', 'categories.id = product_categories.category_id', 'left');
        $this->db->where('products.is_new_arrival', 1);
        $this->db->where('products.status_barang', 1);
        $this->db->group_by('products.id');
        $this->db->order_by('RAND()');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function toggle_new_arrival($id)
    {
        $product = $this->get_by_id($id);
        if ($product) {
            $new_status = ($product->is_new_arrival == 1) ? 0 : 1;
            $this->db->where('id', $id);
            $this->db->update('products', ['is_new_arrival' => $new_status]);
            return $new_status;
        }
        return false;
    }

    public function delete($id)
    {
        // Foreign keys with CASCADE should handle pivot table cleanup, 
        // but explicit transaction is safer if FKs are not strict
        $this->db->trans_start();
        $this->db->delete('product_categories', ['product_id' => $id]);
        $this->db->where('id', $id);
        $this->db->delete('products');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
