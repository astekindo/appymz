<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Satuan extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('satuan_models');
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
			$res_satuan = $this->satuan_models->satuan_content();
            $data['rcsatuan']=$res_satuan;
            $this->load->view('page/vw_listsatuan', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }
	
    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->satuan_models->getData($this->uri->segment(3));
				foreach ($query as $row) {
					$data = $row;
				}
            } else {
                $data['id_satuan'] = '';
                $data['nm_satuan'] = '';
                $data['keterangan'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Master - Satuan';
        }
        if ($this->session->userdata('username')) {
            $this->load->view('form/vw_input_satuan', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function save() {
        
            $data = array(
				'nm_satuan' => $this->input->post('nm_satuan'),
                'keterangan' => $this->input->post('keterangan'),
                'aktif' => 'true'
            );


            if ($this->input->post('id_satuan')=="") {
                $this->satuan_models->add_record($data);
            } else {
                $this->satuan_models->update_record($data, $this->input->post('id_satuan'));
            }
            redirect(base_url() . "satuan", "location");
        
    }

    public function delete() {
        $data = array(
            'aktif' => 'false'
        );
        $this->load->model('satuan_models');
        $this->satuan_models->update_record($data, $this->uri->segment(3));
        redirect(base_url() . "satuan", "location");
    }

}

?>