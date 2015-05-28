<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rekening extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('rekening_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Master - Rekening'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
			$res_rekening = $this->rekening_models->rekening_content();
            $data['rcrekening']=$res_rekening;
            $this->load->view('page/vw_listrekening', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->rekening_models->getData($this->uri->segment(3));
				foreach ($query as $row) {
					$data = $row;
				}
            } else {
                $data['id_rekening'] = '';
                $data['kd_rekening'] = '';
                $data['nm_rekening'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Master - Rekening';
        }
        if ($this->session->userdata('username')) {
            $this->load->view('form/vw_input_rekening', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function save() {
        
            $data = array(
				'kd_rekening' => $this->input->post('kd_rekening'),
                'nm_rekening' => $this->input->post('nm_rekening'),
                'aktif' => 'true'
            );


            if ($this->input->post('id_rekening')=="") {
                $this->rekening_models->add_record($data);
            } else {
                $this->rekening_models->update_record($data, $this->input->post('id_rekening'));
            }
            redirect(base_url() . "rekening", "location");
        
    }

    public function delete() {
        $data = array(
            'aktif' => 'false'
        );
        $this->load->model('rekening_models');
        $this->rekening_models->update_record($data, $this->uri->segment(3));
        redirect(base_url() . "rekening", "location");
    }

}

?>