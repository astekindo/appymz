<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pembayaran extends CI_Controller {

    
    function __construct() {
        parent::__construct();
        $this->load->model('pembayaran_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Master - Pembayaran'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
			$res_pembayaran = $this->pembayaran_models->pembayaran_content();
            $data['rcpembayaran']=$res_pembayaran;
            $this->load->view('page/vw_listpembayaran', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }
	
    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->pembayaran_models->getData($this->uri->segment(3));
				foreach ($query as $row) {
					$data = $row;
				}
            } else {
                $data['id_pembayaran'] = '';
                $data['nm_pembayaran'] = '';
                $data['charge'] = '';
				$data['jenis'] = '';
				$data['status_aktif'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Master - Pembayaran';
        }
        if ($this->session->userdata('username')) {
            $this->load->view('form/vw_input_pembayaran', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function save() {
        
            $data = array(
				'nm_pembayaran' => $this->input->post('nm_pembayaran'),
                'charge' => $this->input->post('charge'),
				'jenis' => $this->input->post('jenis'),
				'status_aktif' => $this->input->post('status_aktif'),
                'aktif' => 'true'
            );


            if ($this->input->post('id_pembayaran')=="") {
                $this->pembayaran_models->add_record($data);
            } else {
                $this->pembayaran_models->update_record($data, $this->input->post('id_pembayaran'));
            }
            redirect(base_url() . "pembayaran", "location");
        
    }

    public function delete() {
        $data = array(
            'aktif' => 'false'
        );
        $this->load->model('pembayaran_models');
        $this->pembayaran_models->update_record($data, $this->uri->segment(3));
        redirect(base_url() . "pembayaran", "location");
    }

}

?>