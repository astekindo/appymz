<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sub_blok extends CI_Controller {
    
	function __construct() {
        parent::__construct();
        $this->load->model('subblok_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Master - Sub Blok Lokasi'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
			$res_subblok = $this->subblok_models->subblok_content();
            $data['rcsubblok']=$res_subblok;
            $this->load->view('page/vw_listsubblok', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }
	
    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->subblok_models->getData($this->uri->segment(3));
				foreach ($query as $row) {
					$data = $row;
				}
            } else {
				$data['id_sub_blok'] = '';
                $data['kd_lokasi'] = '';
				$data['kd_blok'] = '';
				$data['kd_sub_blok'] = '';
                $data['nama_sub_blok'] = '';
				$data['kapasitas'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Master - Sub Blok Lokasi';
			
			//Lokasi
			$get_lokasi = $this->subblok_models->lokasi_data();
			if(is_array($get_lokasi))
			{
				$listlokasi[0] = '- Pilih Lokasi -';
				foreach ($get_lokasi as $brslokasi)
				{
					$listlokasi[$brslokasi->kd_lokasi] = $brslokasi->nama_lokasi;
				}

				$data['listlokasi'] = $listlokasi;
			}
			else
			{
				$data['listlokasi'] = array('' => 'Tidak ada data');
			}
			
			//blok 
			$data['listblok'] = array('' => '- Pilih Lokasi -');
        }
        if ($this->session->userdata('username')) {
            $this->load->view('form/vw_input_subblok', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }
	
	function get_blok()
	{
		if($_POST)
		{
			$result = $this->subblok_models->blok_data($this->input->post('kd_lokasi'));
			if(is_array($result))
			{
				// jika hasil query array maka looping hasil query
					echo '<option value="">- Pilih Blok -</option>';
				foreach ($result as $row)
				{
					echo '<option value="'.$row->kd_blok.'">'.$row->nama_blok.'</option>';
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
        
            if ($this->input->post('kd_sub_blok')=="") {
				$data = array(
				'kd_sub_blok' => $this->subblok_models->getMaxKode($this->input->post('kd_lokasi'),$this->input->post('kd_blok')),
				'kd_blok' => $this->input->post('kd_blok'),
				'kd_lokasi' => $this->input->post('kd_lokasi'),
				'nama_sub_blok' => $this->input->post('nama_sub_blok'),
				'kapasitas' => $this->fungsi->nvl($this->input->post('kapasitas'),0),
                'aktif' => 'true'
            );
                $this->subblok_models->add_record($data);
            } else {
				$data = array(
				'kd_lokasi' => $this->input->post('kd_lokasi'),
				'kd_blok' => $this->input->post('kd_blok'),
				'nama_sub_blok' => $this->input->post('nama_sub_blok'),
				'kapasitas' => $this->fungsi->nvl($this->input->post('kapasitas'),0),
                'aktif' => 'true'
            );
                $this->subblok_models->update_record($data, $this->input->post('id_sub_blok'));
            }
            redirect(base_url() . "sub_blok", "location");
        
    }

    public function delete() {
        $data = array(
            'aktif' => 'false'
        );
        $this->load->model('subblok_models');
        $this->subblok_models->update_record($data, $this->uri->segment(3));
        redirect(base_url() . "sub_blok", "location");
    }

}
?>
