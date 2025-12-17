<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Oath2 extends CI_Controller
{

 
    private $client_id;
    private $client_secret;
    private $redirect_url;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');

        $this->client_id = $_ENV['GOOGLE_CLIENT_ID'] ?? getenv('GOOGLE_CLIENT_ID');
        $this->client_secret = $_ENV['GOOGLE_CLIENT_SECRET'] ?? getenv('GOOGLE_CLIENT_SECRET');
        $this->redirect_url = $_ENV['GOOGLE_REDIRECT_URL'] ?? getenv('GOOGLE_REDIRECT_URL');

        // Include old Google API PHP Client
        include_once FCPATH . 'assets/google-client/Google_Client.php';
        include_once FCPATH . 'assets/google-client/contrib/Google_Oauth2Service.php';
    }

    public function index()
    {
        $gclient = new Google_Client();
        $gclient->setClientId($this->client_id);
        $gclient->setClientSecret($this->client_secret);
        $gclient->setRedirectUri($this->redirect_url);

        $google_oauthv2 = new Google_Oauth2Service($gclient);

        // Handle Google callback
        if (isset($_GET['code'])) {
            try {
                $gclient->authenticate($_GET['code']);
                $token = $gclient->getAccessToken();

                if ($token && is_string($token) && json_decode($token) !== null) {
                    $this->session->set_userdata('token', $token);
                    redirect($this->redirect_url);
                } else {
                    log_message('error', 'Google OAuth: Invalid token received after authentication.');
                    $this->session->set_flashdata('message', 'Gagal login dengan Google. Token tidak valid.');
                    redirect('auth');
                }
            } catch (Exception $e) {
                log_message('error', 'Google OAuth Exception: ' . $e->getMessage());
                $this->session->set_flashdata('message', 'Autentikasi Google gagal.');
                redirect('auth');
            }
        }

        // Token from session
        if ($this->session->userdata('token')) {
            $token = $this->session->userdata('token');

            // Check token format before setting
            if ($token && is_string($token) && json_decode($token) !== null) {
                try {
                    $gclient->setAccessToken($token);

                    if ($gclient->isAccessTokenExpired()) {
                        $this->session->unset_userdata('token');
                        redirect($this->redirect_url);
                    }
                } catch (Exception $e) {
                    log_message('error', 'Token error: ' . $e->getMessage());
                    $this->session->unset_userdata('token');
                    redirect($this->redirect_url);
                }
            } else {
                log_message('error', 'Invalid or corrupted token found in session.');
                $this->session->unset_userdata('token');
                redirect($this->redirect_url);
            }
        }

        // If token is valid and access granted
        if ($gclient->getAccessToken()) {
            try {
                $gpuserprofile = $google_oauthv2->userinfo->get();
                $fullname = $gpuserprofile['given_name'] . " " . $gpuserprofile['family_name'];
                $email = $gpuserprofile['email'];
                

                // $query = $this->db->get_where('user', ['email' => $email]);
                // $user = $query->row_array();

                $db_main = $this->load->database('db_main', TRUE);

                $query = $db_main->get_where('user', ['email' => $email]);
                $user = $query->row_array();


                if (!empty($user) && $user['is_active'] == 1) {

                    // $this->load->model('m_login');

                    // $username = $user['username'];
                    // $token = $this->getToken(10);
                    // $row = $this->m_login->getallacount($username)->row();

                    // if ($row->allcount > 0) {
                    //     $this->m_login->update_token($token, $username);
                    // } else {
                    //     $this->m_login->insert_token($token, $username);
                    // }

                    $data = [
                        'user_id' => $user['id'],
                        'username' => $user['username'],
                        'status' => 'login',
                        'role_id' => $user['role_id'],
                        'area_id' => $user['area_id'],
                        'token' => $token,
                        'logged_in' => TRUE,
                    ];
                    $this->session->set_userdata($data);

                    // $this->db->insert('visitor_logins', [
                    //     'ip_address' => $this->input->ip_address(),
                    //     'email' => $email,
                    // ]);

                    // $redirect_url = $this->session->userdata('redirect_after_login');
                    // if (!empty($redirect_url)) {
                    //     redirect($redirect_url);
                    // }

                    // if ($user['role_id'] == 5) {
                    //     redirect('admin/categories');
                    // } else {
                    //     redirect('admin/categories');
                    // }
                    redirect('admin/categories');

                } else {
                    $this->session->set_flashdata('message', 'Maaf, email tidak terdaftar.');
                    redirect('auth');
                }
            } catch (Exception $e) {
                log_message('error', 'Error retrieving user info: ' . $e->getMessage());
                $this->session->set_flashdata('message', 'Gagal mengambil data Google.');
                redirect('auth');
            }
        } else {
            // Start Google login
            $authUrl = $gclient->createAuthUrl();
            redirect($authUrl);
        }
    }

    private function getToken($length)
    {
        $token = '';
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $max = strlen($characters);

        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[random_int(0, $max - 1)];
        }

        return $token;
    }
}
