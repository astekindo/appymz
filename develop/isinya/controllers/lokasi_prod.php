<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lokasi_prod extends CI_Controller {
    
	function __construct() {
        parent::__construct();
        $this->load->model('lokasi_prod_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Inventori - Lokasi Barang'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
			$res_lokasiprod = $this->lokasi_prod_models->lokasiprod_content();
            $data['rclokasiprod']=$res_lokasiprod;
            $this->load->view('page/vw_listlokasiprod', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }
	
	public function detail_produk_lokasi()
	{
			
			if ($this->uri->segment(3)) {
				
				$this->cart->destroy();
				$this->session->unset_userdata('limit_add_cart');
				
				$kode = $this->uri->segment(3);
				
				list($kd_lokasi, $kd_blok, $kd_sub_blok) = explode("%7C", $kode);
				
				//$id['kd_supplier'] = $this->uri->segment(3);
				$bc['dt_lokasiprod_header'] = $this->app_model->manualQuery("SELECT a.id_sub_blok, a.kd_sub_blok, a.kd_blok, a.kd_lokasi, 
																				b.nama_blok, c.nama_lokasi, a.nama_sub_blok, a.kapasitas
																			FROM mst.tm_sub_blok a
																			JOIN mst.tm_blok b ON (b.kd_blok = a.kd_blok AND b.kd_lokasi = a.kd_lokasi)
																			JOIN mst.tm_lokasi c ON (c.kd_lokasi = b.kd_lokasi)
																			WHERE a.kd_lokasi = '".$kd_lokasi."' AND a.kd_blok = '".$kd_blok."' 
																			AND a.kd_sub_blok = '".$kd_sub_blok."'");
				foreach($bc['dt_lokasiprod_header']->result() as $dph)
				{
					$key['kd_lokasi'] = $dph->kd_lokasi;
					$key['nama_lokasi'] = $dph->nama_lokasi;
					$key['kd_blok'] = $dph->kd_blok;
					$key['nama_blok'] = $dph->nama_blok;
					$key['kd_sub_blok'] = $dph->kd_sub_blok;
					$key['nama_sub_blok'] = $dph->nama_sub_blok;
				}
				
				$bc['dt_lokasiprod_detail'] = $this->app_model->manualQuery("select a.kd_lokasi, a.kd_blok, a.kd_sub_blok, a.kd_produk, 
																	a.qty_in, a.qty_oh, a.qty_out, b.nama_produk 
																	from mst.td_lokasi_per_brg a left join mst.tm_produk b 
																	on a.kd_produk=b.kd_produk where a.kd_lokasi = '".$key['kd_lokasi']."'
																	and a.kd_blok = '".$key['kd_blok']."' and a.kd_sub_blok = '".$key['kd_sub_blok']."'
																	");
																	
				if($this->session->userdata("limit_add_cart")=="")
				{
					$in_cart = array();
					foreach($bc['dt_lokasiprod_detail']->result() as $dpd)
					{	
						//$thnreg = str_pad($dpd->thn_reg,4,"20",STR_PAD_LEFT);
						$in_cart[] = array(
							'id'         		=> $dpd->kd_produk,
							'qty'        		=> 1,
							'price'      		=> 1,
							'name'       		=> $dpd->nama_produk,
							'qty_in' => $dpd->qty_in,
							'qty_out' => $dpd->qty_out,
							'qty_oh' => $dpd->qty_oh
						);
					}
					$this->cart->insert($in_cart);
					$sess_data['limit_add_cart'] = "edit";
					$this->session->set_userdata($sess_data);
				}
				
				$data['kd_lokasi']=$key['kd_lokasi'];
				$data['nama_lokasi']=$key['nama_lokasi'];
				$data['kd_blok'] = $key['kd_blok'];
				$data['nama_blok']=$key['nama_blok'];
				$data['kd_sub_blok'] = $key['kd_sub_blok'];
				$data['nama_sub_blok']=$key['nama_sub_blok'];

				$this->load->view('page/vw_listlokasiproddetail',$data);
		}
		else
		{
            redirect(base_url());
		}
	}
	
}
?>
