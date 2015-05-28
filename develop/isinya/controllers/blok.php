<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blok extends CI_Controller {
    
	function __construct() {
        parent::__construct();
        $this->load->model('blok_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Master - Blok Lokasi'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
			$res_blok = $this->blok_models->blok_content();
            $data['rcblok']=$res_blok;
            $this->load->view('page/vw_listblok', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }
	
    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->blok_models->getData($this->uri->segment(3));
				foreach ($query as $row) {
					$data = $row;
				}
            } else {
				$data['id_blok'] = '';
                $data['kd_lokasi'] = '';
				$data['kd_blok'] = '';
                $data['nama_blok'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Master - Blok Lokasi';
			$data['listlokasi'] = $this->blok_models->lokasi_data();
        }
        if ($this->session->userdata('username')) {
            $this->load->view('form/vw_input_blok', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function save() {
        
            if ($this->input->post('kd_blok')=="") {
				$data = array(
				'kd_blok' => $this->blok_models->getMaxKode($this->input->post('kd_lokasi')),
				'kd_lokasi' => $this->input->post('kd_lokasi'),
				'nama_blok' => $this->input->post('nama_blok'),
                'aktif' => 'true'
            );
                $this->blok_models->add_record($data);
            } else {
				$data = array(
				'kd_lokasi' => $this->input->post('kd_lokasi'),
				'nama_blok' => $this->input->post('nama_blok'),
                'aktif' => 'true'
            );
                $this->blok_models->update_record($data, $this->input->post('id_blok'));
            }
            redirect(base_url() . "blok", "location");
        
    }

    public function delete() {
        $data = array(
            'aktif' => 'false'
        );
        $this->load->model('blok_models');
		$kd['kd_blok'] = $this->blok_models->get_kd_blok($this->uri->segment(3));
		
        $this->blok_models->update_record($data, $this->uri->segment(3));
		$this->app_model->updateData("mst.tm_sub_blok",$data, $kd);
        redirect(base_url() . "blok", "location");
    }

}
?>
