<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Produk extends CI_Controller {

    
    function __construct() {
        parent::__construct();
        $this->load->model('produk_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Master - Barang'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
			$res_produk = $this->produk_models->produk_content();
            $data['rcproduk']=$res_produk;
            $this->load->view('page/vw_listproduk', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->produk_models->getData($this->uri->segment(3));
				foreach ($query as $row) {
					$data = $row;
				}
            } else {
                $data['id_produk'] = '';
				$data['kd_kategori1'] = '';
				$data['kd_kategori2'] = '';
				$data['kd_kategori3'] = '';
				$data['kd_kategori4'] = '';
				$data['thn_reg'] = '';
				$data['no_urut'] = '';
				$data['nama_produk'] = '';
				$data['kd_produk'] = '';
				$data['kd_produk_lama'] = '';
				$data['kd_produk_supp'] = '';
				$data['id_satuan'] = '';
				$data['kd_peruntukkan'] = '';
				$data['qty_in'] = '';
				$data['qty_out'] = '';
				$data['qty_oh'] = '';
				$data['qty_do'] = '';
				$data['qty_siap_jual'] = '';
				$data['min_stok'] = '';
				$data['max_stok'] = '';
				$data['min_order'] = '';
				$data['hrg_supplier'] = '';
				$data['hrg_hpp'] = '';
				$data['hrg_jual'] = '';
				$data['disk_persen_kons1'] = '';
				$data['disk_persen_kons2'] = '';
				$data['disk_persen_kons3'] = '';
				$data['disk_persen_kons4'] = '';
				$data['disk_amt_kons1'] = '';
				$data['disk_amt_kons2'] = '';
				$data['disk_amt_kons3'] = '';
				$data['disk_amt_kons4'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Master - Barang';
			$data['listsatuan'] = $this->produk_models->satuan_data();
			$ambil_kategori1 = $this->produk_models->get_kategori1();
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
			
			//Kategori 4
			$data['listkategori4'] = array('' => 'Pilih kategori 3');
        }
        if ($this->session->userdata('username')) {
            $this->load->view('form/vw_input_produk', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }
	
	function grab_kategori2()
	{
		if($_POST)
		{
			$result = $this->produk_models->get_kategori2($this->input->post('kd_kategori1'));
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
			$result = $this->produk_models->get_kategori3($this->input->post('kd_kategori2'));
			if(is_array($result))
			{
				// jika hasil query array maka looping hasil query
					echo '<option value="">Pilih Kategori</option>';
				foreach ($result as $row)
				{
					echo '<option value="'.$row->kd_kat3.'">'.$row->nama_kategori3.'</option>';
				}
			}
			else
			{
				// tampilkan jika data hasil query kosong
				echo '<option value="">Tidak ada data</option>';
			}
		}
	}
	
	function grab_kategori4()
	{
		if($_POST)
		{
			$result = $this->produk_models->get_kategori4($this->input->post('kd_kategori3'));
			if(is_array($result))
			{
				// jika hasil query array maka looping hasil query
					echo '<option value="">Pilih Kategori</option>';
				foreach ($result as $row)
				{
					echo '<option value="'.$row->kd_kat4.'">'.$row->nama_kategori4.'</option>';
				}
			}
			else
			{
				// tampilkan jika data hasil query kosong
				echo '<option value="">Tidak ada data</option>';
			}
		}
	}
	
	function get_product_code()
	{	
		$kd_kategori1 = $this->input->post('kd_kategori1');
		$kd_kategori2 = $this->input->post('kd_kategori2');
		$kd_kategori3 = $this->input->post('kd_kategori3');
		$kd_kategori4 = $this->input->post('kd_kategori4');
		$this_year = date("y");
		if($_POST)
		{
			$result = $this->produk_models->getMaxKode($this->input->post('kd_kategori4'));
			if($result != null)
			{
				//echo $kd_kategori1.$kd_kategori2.$kd_kategori3.$kd_kategori4.$this_year.$result;
				echo $this->input->post('kd_kategori4').$this_year.$result;
				
			}
			else
			{
				echo '';
			}
		}
	}

    public function save() {
			
			$vkdp=$this->input->post('kd_peruntukkan');
			if ($vkdp=='on')
			{$vkdp='1';}
			else
			{$vkdp='0';};

            if ($this->input->post('id_produk')=="") {
				
				$data = array(
					'kd_kategori1' => $this->input->post('kd_kategori1'),
					'kd_kategori2' => substr($this->input->post('kd_kategori4'),2,2),
					'kd_kategori3' => substr($this->input->post('kd_kategori4'),4,2),
					'kd_kategori4' => substr($this->input->post('kd_kategori4'),6,2),
					'thn_reg' => date("y"),
					'no_urut' => $this->produk_models->getMaxKode($this->input->post('kd_kategori4')),
					'nama_produk' => $this->input->post('nama_produk'),
					'kd_produk' => $this->input->post('kd_produk'),
					'kd_produk_lama' => $this->input->post('kd_produk_lama'),
					'kd_produk_supp' => $this->input->post('kd_produk_supp'),
					'id_satuan' => $this->input->post('id_satuan'),
					'kd_peruntukkan' => $vkdp,
					'qty_in' => $this->fungsi->nvl($this->input->post('qty_in'),'0'),
					'qty_out' => $this->fungsi->nvl($this->input->post('qty_out'),'0'),
					'qty_oh' => $this->fungsi->nvl($this->input->post('qty_oh'),'0'),
					'qty_do' => $this->fungsi->nvl($this->input->post('qty_do'),'0'),
					'qty_siap_jual' => $this->fungsi->nvl($this->input->post('qty_siap_jual'),'0'),
					'min_stok' => $this->fungsi->nvl($this->input->post('min_stok'),'0'),
					'max_stok' => $this->fungsi->nvl($this->input->post('max_stok'),'0'),
					'min_order' => $this->fungsi->nvl($this->input->post('min_order'),'0'),
					'hrg_supplier' => $this->fungsi->nvl($this->input->post('hrg_supplier'),'0'),
					'hrg_hpp' => $this->fungsi->nvl($this->input->post('hrg_hpp'),'0'),
					'hrg_jual' => $this->fungsi->nvl($this->input->post('hrg_jual'),'0'),
					'disk_persen_kons1' => $this->fungsi->nvl($this->input->post('disk_persen_kons1'),'0'),
					'disk_persen_kons2' => $this->fungsi->nvl($this->input->post('disk_persen_kons2'),'0'),
					'disk_persen_kons3' => $this->fungsi->nvl($this->input->post('disk_persen_kons3'),'0'),
					'disk_persen_kons4' => $this->fungsi->nvl($this->input->post('disk_persen_kons4'),'0'),
					'disk_amt_kons1' => $this->fungsi->nvl($this->input->post('disk_amt_kons1'),'0'),
					'disk_amt_kons2' => $this->fungsi->nvl($this->input->post('disk_amt_kons2'),'0'),
					'disk_amt_kons3' => $this->fungsi->nvl($this->input->post('disk_amt_kons3'),'0'),
					'disk_amt_kons4' => $this->fungsi->nvl($this->input->post('disk_amt_kons4'),'0'),
					'aktif' => 'true'
				);
			
                $this->produk_models->add_record($data);
            } else {
				
				$data = array(
					'nama_produk' => $this->input->post('nama_produk'),
					'kd_produk_lama' => $this->input->post('kd_produk_lama'),
					'kd_produk_supp' => $this->input->post('kd_produk_supp'),
					'id_satuan' => $this->input->post('id_satuan'),
					'kd_peruntukkan' => $vkdp,
					'qty_in' => $this->fungsi->nvl($this->input->post('qty_in'),'0'),
					'qty_out' => $this->fungsi->nvl($this->input->post('qty_out'),'0'),
					'qty_oh' => $this->fungsi->nvl($this->input->post('qty_oh'),'0'),
					'qty_do' => $this->fungsi->nvl($this->input->post('qty_do'),'0'),
					'qty_siap_jual' => $this->fungsi->nvl($this->input->post('qty_siap_jual'),'0'),
					'min_stok' => $this->fungsi->nvl($this->input->post('min_stok'),'0'),
					'max_stok' => $this->fungsi->nvl($this->input->post('max_stok'),'0'),
					'min_order' => $this->fungsi->nvl($this->input->post('min_order'),'0'),
					'hrg_supplier' => $this->fungsi->nvl($this->input->post('hrg_supplier'),'0'),
					'hrg_hpp' => $this->fungsi->nvl($this->input->post('hrg_hpp'),'0'),
					'hrg_jual' => $this->fungsi->nvl($this->input->post('hrg_jual'),'0'),
					'disk_persen_kons1' => $this->fungsi->nvl($this->input->post('disk_persen_kons1'),'0'),
					'disk_persen_kons2' => $this->fungsi->nvl($this->input->post('disk_persen_kons2'),'0'),
					'disk_persen_kons3' => $this->fungsi->nvl($this->input->post('disk_persen_kons3'),'0'),
					'disk_persen_kons4' => $this->fungsi->nvl($this->input->post('disk_persen_kons4'),'0'),
					'disk_amt_kons1' => $this->fungsi->nvl($this->input->post('disk_amt_kons1'),'0'),
					'disk_amt_kons2' => $this->fungsi->nvl($this->input->post('disk_amt_kons2'),'0'),
					'disk_amt_kons3' => $this->fungsi->nvl($this->input->post('disk_amt_kons3'),'0'),
					'disk_amt_kons4' => $this->fungsi->nvl($this->input->post('disk_amt_kons4'),'0'),
					'aktif' => 'true'
				);
				
                $this->produk_models->update_record($data, $this->input->post('id_produk'));
            }
            redirect(base_url() . "produk", "location");
        
    }

    public function delete() {
        $data = array(
            'aktif' => 'false'
        );
        $this->load->model('produk_models');
        $this->produk_models->update_record($data, $this->uri->segment(3));
        redirect(base_url() . "produk", "location");
    }

}

?>