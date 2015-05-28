<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kategori4 extends CI_Controller {

    private $field = array('kd_kategori1','kd_kategori2','kd_kategori3','kd_kategori4','name_kategori1','name_kategori2','name_kategori3','name_kategori4');

    function __construct() {
        parent::__construct();
        $this->load->model('kategori4_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Home - Master - Kategori 4'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
            $res_kat4 = $this->kategori4_models->kategori4_content();
            $data['rckategori4']=$res_kat4;
            $this->load->view('page/kategori4', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function getData() {
		$query = "select d.id_kategori4, a.kd_kategori1, a.nama_kategori1, b.kd_kategori2, b.nama_kategori2, c.kd_kategori3,c.nama_kategori3, d.kd_kategori4,d.nama_kategori4
					from mst.tm_kategori1 a, mst.tm_kategori2 b, mst.tm_kategori3 c, mst.tm_kategori4 d
					where d.kd_kategori3=c.kd_kategori3 and d.kd_kategori2=c.kd_kategori2 and d.kd_kategori1=c.kd_kategori1 and d.kd_kategori1=a.kd_kategori1
					and c.kd_kategori2=b.kd_kategori2 and c.kd_kategori1=a.kd_kategori1 and b.kd_kategori1=a.kd_kategori1 and d.aktif = true";
        $this->getdata->listtable($this->field, $query, 'kategori4/form', 'kategori4/delete','all');
		}

    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->kategori4_models->getData($this->uri->segment(3));
				foreach ($query as $row) {
					$data = $row;
				}
            } else {
                $data['id_kategori4'] = '';
                $data['kd_kategori1'] = '';
                $data['kd_kategori2'] = '';
                $data['kd_kategori3'] = '';
                $data['kd_kategori4'] = '';
                $data['nama_kategori4'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Home - Master - Kategori 4';

			//Kategori 1
			$ambil_kategori1 = $this->kategori4_models->get_kategori1();
			if(is_array($ambil_kategori1))
			{
				$listkategori1[0] = 'Pilih Kategori';
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
			$data['listkategori2'] = array('' => 'Pilih kategori 1');
			
			//Kategori 3
			$data['listkategori3'] = array('' => 'Pilih kategori 2');
		}

        if ($this->session->userdata('username')) {
            $this->load->view('form/kategori4', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

	function grab_kategori2()
	{
		if($_POST)
		{
			$result = $this->kategori4_models->get_kategori2($this->input->post('kd_kategori1'));
			if(is_array($result))
			{
				// jika hasil query array maka looping hasil query
					echo '<option value="">Pilih Kategori</option>';
				foreach ($result as $row)
				{
					echo '<option value="'.$row->kd_kat2.'">'.$row->nama_kategori2.'</option>';
				}
			}
			else
			{
				// tampilkan jika data hasil query kosong
				echo '<option value="">Tidak ada data</option>';
			}
		}
	}

	function grab_kategori3()
	{
		if($_POST)
		{
			$result = $this->kategori4_models->get_kategori3($this->input->post('kd_kategori2'));
			if(is_array($result))
			{
				// jika hasil query array maka looping hasil query
					echo '<option value="">Pilih Kategori</option>';
				foreach ($result as $row)
				{
					echo '<option value="'.$row->kd_kategori3.'">'.$row->nama_kategori3.'</option>';
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
			$vkd_kategori2= str_pad(substr($this->input->post('kd_kategori2'),3,2),2,"0",STR_PAD_LEFT);
			$vkd_kategori3=$this->input->post('kd_kategori3');

            $data = array(
				'kd_kategori1' => $this->input->post('kd_kategori1'),
				'kd_kategori2' => $vkd_kategori2,
				'kd_kategori3' => $this->input->post('kd_kategori3'),
				'kd_kategori4' => str_pad($this->kategori4_models->get_last_records($vkd_kategori1, $vkd_kategori2, $vkd_kategori3)+1,2,"0",STR_PAD_LEFT),
				'nama_kategori4' => $this->input->post('nama_kategori4'),
                'aktif' => '1'
            );
            $datau = array(
				'kd_kategori1' => $this->input->post('kd_kategori1'),
				'kd_kategori2' => $this->input->post('kd_kategori2'),
				'kd_kategori3' => $this->input->post('kd_kategori3'),
				'kd_kategori4' => $this->input->post('kd_kategori4'),
				'nama_kategori4' => $this->input->post('nama_kategori4'),
                'aktif' => '1'
            );

            if ($this->input->post('id_kategori4')=="") {
                $this->kategori4_models->add_record($data);
            } else {
                $this->kategori4_models->update_record($datau, $this->input->post('id_kategori4'));
            }
            redirect(base_url() . "kategori4", "location");
        
    }

    public function delete() {
        $data = array(
            'aktif' => '0'
        );
        $this->load->model('kategori4_models');
        $this->kategori4_models->update_record($data, $this->uri->segment(3));
        redirect(base_url() . "kategori4", "location");
    }

}

?>