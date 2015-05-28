<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kategori3 extends CI_Controller {

    private $field = array('kd_kategori1','kd_kategori2','kd_kategori3','name_kategori1','name_kategori2','name_kategori3');

    function __construct() {
        parent::__construct();
        $this->load->model('kategori3_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Home - Master - Kategori 3'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
            $res_kat3 = $this->kategori3_models->kategori3_content();
            $data['rckategori3']=$res_kat3;
            $this->load->view('page/kategori3', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function getData() {
			$query = "select c.id_kategori3, a.kd_kategori1, a.nama_kategori1, b.kd_kategori2, b.nama_kategori2, c.kd_kategori3,c.nama_kategori3
						from mst.tm_kategori1 a, mat.tm_kategori2 b, mst.tm_kategori3 c 
					  where b.kd_kategori1=a.kd_kategori1 and c.kd_kategori2=b.kd_kategori2 and c.kd_kategori1=a.kd_kategori1 and c.aktif = true";
        $this->getdata->listtable($this->field, $query, 'kategori3/form', 'kategori3/delete','all');
		}

    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->kategori3_models->getData($this->uri->segment(3));
				foreach ($query as $row) {
					$data = $row;
				}
            } else {
                $data['id_kategori3'] = '';
                $data['kd_kategori1'] = '';
                $data['kd_kategori2'] = '';
                $data['kd_kategori3'] = '';
                $data['nama_kategori3'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Home - Master - Kategori 3';

			//Kategori 1
			$ambil_kategori1 = $this->kategori3_models->get_kategori1();
			if(is_array($ambil_kategori1))
			{
				$listkategori1[0] = '- Pilih Kategori -';
				foreach ($ambil_kategori1 as $bariskategori1)
				{
					$listkategori1[$bariskategori1->kd_kategori1] = $bariskategori1->nama_kategori1;
				}

				$data['listkategori1'] = $listkategori1;
			}
			else
			{
				$data['listkategori1'] = array('' => 'Tidak ada data');
			}
			
			//Kategori 2
			$data['listkategori2'] = array('' => '- Pilih kategori1 -');
		}

        if ($this->session->userdata('username')) {
            $this->load->view('form/kategori3', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

	function grab_kategori2()
	{
		if($_POST)
		{
			$result = $this->kategori3_models->get_kategori2($this->input->post('kd_kategori1'));
			if(is_array($result))
			{
				// jika hasil query array maka looping hasil query
					echo '<option value="">Pilih Kategori</option>';
				foreach ($result as $row)
				{
					echo '<option value="'.$row->kd_kategori2.'">'.$row->nama_kategori2.'</option>';
				}
			}
			else
			{
				// tampilkan jika data hasil query kosong
				echo '<option value="">Tidak ada data</option>';
			}
		}
	}
	
    public function save() {
			$vkd_kategori1=$this->input->post('kd_kategori1');
			$vkd_kategori2=$this->input->post('kd_kategori2');
			$data = array(
				'kd_kategori1' => $this->input->post('kd_kategori1'),
				'kd_kategori2' => $this->input->post('kd_kategori2'),
				'kd_kategori3' => str_pad($this->kategori3_models->get_last_records($vkd_kategori1,$vkd_kategori2)+1,2,"0",STR_PAD_LEFT),
				'nama_kategori3' => $this->input->post('nama_kategori3'),
                'aktif' => '1'
            );
            $datau = array(
				'kd_kategori1' => $this->input->post('kd_kategori1'),
				'kd_kategori2' => $this->input->post('kd_kategori2'),
				'kd_kategori3' => $this->input->post('kd_kategori3'),
				'nama_kategori3' => $this->input->post('nama_kategori3'),
                'aktif' => '1'
            );

            if ($this->input->post('kd_kategori3')=="") {
                $this->kategori3_models->add_record($data);
            } else {
                $this->kategori3_models->update_record($datau, $this->input->post('id_kategori3'));
            }
            redirect(base_url() . "kategori3", "location");
        
    }

    public function delete() {
        $data = array(
            'aktif' => '0'
        );
        $this->load->model('kategori3_models');
        $this->kategori3_models->update_record($data, $this->uri->segment(3));
        redirect(base_url() . "kategori3", "location");
    }

}

?>