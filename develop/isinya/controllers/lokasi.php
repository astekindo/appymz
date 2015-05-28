<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lokasi extends CI_Controller {
    
	function __construct() {
        parent::__construct();
        $this->load->model('lokasi_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Master - Lokasi'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
			$res_lokasi = $this->lokasi_models->lokasi_content();
            $data['rclokasi']=$res_lokasi;
            $this->load->view('page/vw_listlokasi', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->lokasi_models->getData($this->uri->segment(3));
				foreach ($query as $row) {
					$data = $row;
				}
            } else {
				$data['id_lokasi'] = '';
                $data['kd_lokasi'] = '';
                $data['nama_lokasi'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Master - Lokasi';
        }
        if ($this->session->userdata('username')) {
            $this->load->view('form/vw_input_lokasi', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function save() {
        
            if ($this->input->post('kd_lokasi')=="") {
				$data = array(
				'kd_lokasi' => $this->lokasi_models->getMaxKode(),
				'nama_lokasi' => $this->input->post('nama_lokasi'),
                'aktif' => 'true'
            );
                $this->lokasi_models->add_record($data);
            } else {
				$data = array(
				'nama_lokasi' => $this->input->post('nama_lokasi'),
                'aktif' => 'true'
            );
                $this->lokasi_models->update_record($data, $this->input->post('id_lokasi'));
            }
            redirect(base_url() . "lokasi", "location");
        
    }

    public function delete() {
        $data = array(
            'aktif' => 'false'
        );
        $this->load->model('lokasi_models');
		$kd['kd_lokasi'] = $this->lokasi_models->get_kd_lokasi($this->uri->segment(3));
		
        $this->lokasi_models->update_record($data, $this->uri->segment(3));
		$this->app_model->updateData("mst.tm_blok",$data, $kd);
		$this->app_model->updateData("mst.tm_sub_blok",$data, $kd);
        redirect(base_url() . "lokasi", "location");
    }

}
?>
