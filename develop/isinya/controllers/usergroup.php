<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usergroup extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('usergroup_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Master - Satuan'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
			$res_usergroup = $this->usergroup_models->usergroup_content();
            $data['rcusergroup']=$res_usergroup;
            $this->load->view('page/vw_listusergroup', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }
	
    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->usergroup_models->getData($this->uri->segment(3));
				foreach ($query as $row) {
					$data = $row;
				}
            } else {
                $data['id_usergroup'] = '';
                $data['nama_usergroup'] = '';
                $data['deskripsi'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Admin - Usergroup';
        }
        if ($this->session->userdata('username')) {
            $this->load->view('form/vw_input_usergroup', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function save() {
        
            $data = array(
				'nama_usergroup' => $this->input->post('nama_usergroup'),
                'deskripsi' => $this->input->post('deskripsi'),
                'aktif' => 'true'
            );


            if ($this->input->post('id_usergroup')=="") {
                $this->usergroup_models->add_record($data);
            } else {
                $this->usergroup_models->update_record($data, $this->input->post('id_usergroup'));
            }
            redirect(base_url() . "usergroup", "location");
        
    }

    public function delete() {
        $data = array(
            'aktif' => 'false'
        );
        $this->load->model('usergroup_models');
        $this->usergroup_models->update_record($data, $this->uri->segment(3));
        redirect(base_url() . "usergroup", "location");
    }

}

?>