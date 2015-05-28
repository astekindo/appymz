<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
    
	function __construct() {
        parent::__construct();
        $this->load->model('user_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Admin - User'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
			$res_user = $this->user_models->user_content();
            $data['rcuser']=$res_user;
            $this->load->view('page/vw_listuser', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }
	
    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->user_models->getData($this->uri->segment(3));
				foreach ($query as $row) {
					$data = $row;
				}
            } else {
				$data['id_user'] = '';
                $data['username'] = '';
				$data['passwd'] = '';
				$data['email'] = '';
                $data['id_usergroup'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Admin - User';
			$data['listusergroup'] = $this->user_models->usergroup_data();
        }
        if ($this->session->userdata('username')) {
            $this->load->view('form/vw_input_user', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function save() {
        
        $data = array(
			'username' => $this->input->post('username'),
			'passwd' => md5($this->input->post('passwd')),
			'email' => $this->input->post('email'),
			'id_usergroup' => $this->input->post('id_usergroup'),
			'aktif' => 'true'
		);


		if ($this->input->post('id_user')=="") {
			$this->user_models->add_record($data);
		} else {
			$this->user_models->update_record($data, $this->input->post('id_user'));
		}
		redirect(base_url() . "user", "location");
        
    }

    public function delete() {
        $data = array(
            'aktif' => 'false'
        );
        $this->load->model('user_models');
        $this->user_models->update_record($data, $this->uri->segment(3));
        redirect(base_url() . "user", "location");
    }

}
?>
