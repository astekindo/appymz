<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kategori2 extends CI_Controller {

    private $field = array( 'kd_nama_kategori1','kd_nama_kategori2','nama_kategori1','nama_kategori2');

    function __construct() {
        parent::__construct();
        $this->load->model('kategori2_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Home - Master - Kategori 2'
        );
		
        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
            $res_kat2 = $this->kategori2_models->kategori2_content();
            $data['rckategori2']=$res_kat2;
            $this->load->view('page/kategori2', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function getData() {
		$query = "select b.id_kategori2, a.kd_kategori1, b.kd_kategori2, a.nama_kategori1, b.nama_kategori2 from mst.tm_kategori1 a,mst.tm_kategori2 b where
				  a.kd_kategori1=b.kd_kategori1 and b.aktif = true";
        $this->getdata->listtable($this->field, $query, 'kategori2/form', 'kategori2/delete','all');
		}

    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->kategori2_models->getData($this->uri->segment(3));
				foreach ($query as $row) {
					$data = $row;
				}
            } else {
                $data['id_kategori2'] = '';
                $data['kd_kategori1'] = '';
                $data['kd_kategori2'] = '';
                $data['nama_kategori2'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Home - Master - Kategori 2';
			$data['listnama_kategori1'] = $this->kategori2_models->nama_kategori1();
        }
        if ($this->session->userdata('username')) {
            $this->load->view('form/kategori2', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function save() {
			$vkd_kategori1=$this->input->post('kd_kategori1');
			$data = array(
				'kd_kategori2' => str_pad($this->kategori2_models->get_last_records($vkd_kategori1)+1,2,"0",STR_PAD_LEFT),
				'kd_kategori1' => $this->input->post('kd_kategori1'),
				'nama_kategori2' => $this->input->post('nama_kategori2'),
                'aktif' => '1'
            );
            $datau = array(
				'kd_kategori1' => $this->input->post('kd_kategori1'),
				'kd_kategori2' => $this->input->post('kd_kategori2'),
				'nama_kategori2' => $this->input->post('nama_kategori2'),
                'aktif' => '1'
            );


            if ($this->input->post('id_kategori2')=="") {
                $this->kategori2_models->add_record($data);
            } else {
                $this->kategori2_models->update_record($datau, $this->input->post('id_kategori2'));
            }
            redirect(base_url() . "kategori2", "location");
        
    }

    public function delete() {
        $data = array(
            'aktif' => '0'
        );
        $this->load->model('kategori2_models');
        $this->kategori2_models->update_record($data, $this->uri->segment(3));
        redirect(base_url() . "kategori2", "location");

    }

}

?>