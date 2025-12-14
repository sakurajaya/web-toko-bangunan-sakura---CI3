<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ResetUser extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        // 1. Check if table users exists
        if (!$this->db->table_exists('users')) {
            echo "<h1>Error: Tabel 'users' tidak ditemukan!</h1>";
            echo "<p>Silakan import file <code>users_table.sql</code> ke database Anda terlebih dahulu.</p>";
            return;
        }

        // 2. Check if admin user exists
        $user = $this->db->get_where('users', ['username' => 'admin'])->row();

        $new_password = 'admin'; // We'll set it to just 'admin' for simplicity first
        $hash = password_hash($new_password, PASSWORD_BCRYPT);

        if ($user) {
            // Update
            $this->db->where('id', $user->id);
            $this->db->update('users', ['password' => $hash]);
            echo "<h1>Sukses!</h1>";
            echo "<p>Password untuk user <strong>admin</strong> telah direset menjadi: <strong>$new_password</strong></p>";
        } else {
            // Create
            $data = [
                'username' => 'admin',
                'password' => $hash
            ];
            $this->db->insert('users', $data);
            echo "<h1>Sukses!</h1>";
            echo "<p>User <strong>admin</strong> telah dibuat dengan password: <strong>$new_password</strong></p>";
        }

        echo "<p><a href='" . base_url('auth') . "'>Login sekarang</a></p>";
    }
}
