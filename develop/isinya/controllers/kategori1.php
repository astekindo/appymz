<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kategori1 extends CI_Controller {

    private $field = array('kd_kategori1,kategori1');

    function __construct() {
        parent::__construct();
        $this->load->model('kategori1_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Home - Master - Kategori 1'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
            $res_kat1 = $this->kategori1_models->kategori1_content();
            $data['rckategori1']=$res_kat1;
            $this->load->view('page/kategori1', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function getData() {
		$query = "select id_kategori1,kd_kategori1,nama_kategori1 from mst.tm_kategori1 where aktif = true";
        $this->getdata->listtable($this->field, $query, 'kategori1/form', 'kategori1/delete','all');
		}

    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->kategori1_models->getData($this->uri->segment(3));
				foreach ($query as $row) {
					$data = $row;
				}
            } else {
                $data['id_kategori1'] = '';
                $data['kd_kategori1'] = '';
                $data['nama_kategori1'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Home - Master - Kategori 1';
        }
        if ($this->session->userdata('username')) {
            $this->load->view('form/kategori1', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function save() {
        
            $data = array(
				'kd_kategori1' => str_pad($this->kategori1_models->get_last_records()+1,2,"0",STR_PAD_LEFT),
                'nama_kategori1' => $this->input->post('nama_kategori1'),
                'aktif' => '1'
            );

            $datau = array(
				'nama_kategori1' => $this->input->post('nama_kategori1'),
                'aktif' => '1'
            );


            if ($this->input->post('id_kategori1')=="") {
                $this->kategori1_models->add_record($data);
            } else {
                $this->kategori1_models->update_record($datau, $this->input->post('id_kategori1'));
            }
            redirect(base_url() . "kategori1", "location");
        
    }

    public function delete() {
        $datau = array(
            'aktif' => '0'
        );
        $this->load->model('kategori1_models');
        $this->kategori1_models->update_record($datau, $this->uri->segment(3));
        $this->index();
    }

}

?>