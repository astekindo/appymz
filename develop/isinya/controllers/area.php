<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Area extends CI_Controller {

    private $field = array('nama_area', 'alamat', 'keterangan');

    function __construct() {
        parent::__construct();
        $this->load->model('area_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Home - Setting - Area'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
            $res_area = $this->area_models->area_content();
            $data['rcarea']=$res_area;
            $this->load->view('page/area', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function getData() {
		$query = "select id_area,nama_area,alamat,keterangan from mst.tm_area where aktif = true";
        $this->getdata->listtable($this->field, $query, 'area/form', 'area/delete','all');
		}

    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->area_models->getData($this->uri->segment(3));
				foreach ($query as $row) {
					$data = $row;
				}
            } else {
                $data['id_area'] = '';
                $data['nama_area'] = '';
                $data['alamat'] = '';
                $data['keterangan'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Home - Setting - Area';
        }
        if ($this->session->userdata('username')) {
            $this->load->view('form/area', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function save() {
        
            $data = array(
				'nama_area' => $this->input->post('nama_area'),
                'alamat' => $this->input->post('alamat'),
                'keterangan' => $this->input->post('keterangan'),
                'aktif' => '1'
            );


            if ($this->input->post('id_area')=="") {
                $this->area_models->add_record($data);
            } else {
                $this->area_models->update_record($data, $this->input->post('id_area'));
            }
            redirect(base_url() . "area", "location");
        
    }

    public function delete() {
        $data = array(
            'aktif' => '0'
        );
        $this->load->model('area_models');
        $this->area_models->update_record($data, $this->uri->segment(3));
        $this->index();
    }

}

?>