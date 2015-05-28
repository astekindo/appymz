<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Min_stok extends CI_Controller {
    
	function __construct() {
        parent::__construct();
        $this->load->model('min_stok_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Inventori - Informasi Minimum Stok'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
			$res_minstok = $this->min_stok_models->minstok_content();
            $data['rcminstok']=$res_minstok;
            $this->load->view('page/vw_listminstok', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }
	
	public function detail_min_stok()
	{
			
			if ($this->uri->segment(3)) {
				
				$this->cart->destroy();
				$this->session->unset_userdata('limit_add_cart_minstok');
				
				$kd_produk = $this->uri->segment(3);
				
				
				$bc['dt_minstok_header'] = $this->app_model->manualQuery("
					SELECT a.kd_lokasi, a.kd_blok, a.kd_sub_blok, b.nama_lokasi, c.nama_blok, d.nama_sub_blok, a.kd_produk, a.qty_oh 
					FROM mst.td_lokasi_per_brg a
					JOIN mst.tm_lokasi b on (b.kd_lokasi = a.kd_lokasi)
					JOIN mst.tm_blok c on (c.kd_blok = a.kd_blok AND c.kd_lokasi = b.kd_lokasi)
					JOIN mst.tm_sub_blok d on (d.kd_sub_blok = a.kd_sub_blok AND d.kd_blok = c.kd_blok AND d.kd_lokasi = c.kd_lokasi)
					WHERE a.kd_produk = '".$kd_produk."'
				");
				
				foreach($bc['dt_minstok_header']->result() as $dph)
				{
					$key['kd_lokasi'] = $dph->kd_lokasi;
					$key['nama_lokasi'] = $dph->nama_lokasi;
					$key['kd_blok'] = $dph->kd_blok;
					$key['nama_blok'] = $dph->nama_blok;
					$key['kd_sub_blok'] = $dph->kd_sub_blok;
					$key['nama_sub_blok'] = $dph->nama_sub_blok;
					$key['kd_produk'] = $dph->kd_produk;
				}
				
				$bc['dt_minstok_detail'] = $this->app_model->manualQuery("
					SELECT a.kd_lokasi, a.kd_blok, a.kd_sub_blok, b.nama_lokasi, c.nama_blok, d.nama_sub_blok, a.kd_produk, a.qty_oh 
					FROM mst.td_lokasi_per_brg a
					JOIN mst.tm_lokasi b on (b.kd_lokasi = a.kd_lokasi)
					JOIN mst.tm_blok c on (c.kd_blok = a.kd_blok AND c.kd_lokasi = b.kd_lokasi)
					JOIN mst.tm_sub_blok d on (d.kd_sub_blok = a.kd_sub_blok AND d.kd_blok = c.kd_blok AND d.kd_lokasi = c.kd_lokasi)
					WHERE a.kd_produk = '".$key['kd_produk']."'
				");
																	
				if($this->session->userdata("limit_add_cart_minstok")=="")
				{
					$in_cart = array();
					foreach($bc['dt_minstok_detail']->result() as $dpd)
					{	
						//$thnreg = str_pad($dpd->thn_reg,4,"20",STR_PAD_LEFT);
						$in_cart[] = array(
							'id'         		=> 1,
							'qty'        		=> 1,
							'price'      		=> 1,
							'name'       		=> 1,
							'kode_lokasi'		=> $dpd->kd_lokasi.$dpd->kd_blok.$dpd->kd_sub_blok,
							'nama_lokasi'		=> $dpd->nama_lokasi.' - '.$dpd->nama_blok.' - '.$dpd->nama_sub_blok,
							'qty_oh' 			=> $dpd->qty_oh
						);
					}
					$this->cart->insert($in_cart);
					$sess_data['limit_add_cart_minstok'] = "edit";
					$this->session->set_userdata($sess_data);
				}
				
				$data['kd_lokasi']=$key['kd_lokasi'];
				$data['nama_lokasi']=$key['nama_lokasi'];
				$data['kd_blok'] = $key['kd_blok'];
				$data['nama_blok']=$key['nama_blok'];
				$data['kd_sub_blok'] = $key['kd_sub_blok'];
				$data['nama_sub_blok']=$key['nama_sub_blok'];
				$data['kd_produk']=$key['kd_produk'];

				$this->load->view('page/vw_listminstokdetail',$data);
		}
		else
		{
            redirect(base_url());
		}
	}
	
}
?>
