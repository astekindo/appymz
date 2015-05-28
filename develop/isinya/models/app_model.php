<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Model extends CI_Model {

	/**
	 * @author : Gede Lumbung
	 * @web : http://gedelumbung.com
	 * @keterangan : Model untuk menangani semua query database aplikasi
	 **/
	 
	//query otomatis dengan active record
	public function getAllData($table)
	{
		return $this->db->get($table);
	}
	
	public function getAllDataLimited($table,$limit,$offset)
	{
		return $this->db->get($table, $limit, $offset);
	}
	
	public function getSelectedDataLimited($table,$data,$limit,$offset)
	{
		return $this->db->get_where($table, $data, $limit, $offset);
	}
	
	public function getSelectedData($table,$data)
	{
		return $this->db->get_where($table, $data);
	}
	
	function updateData($table,$data,$field_key)
	{
		$this->db->update($table,$data,$field_key);
	}
	
	function deleteData($table,$data)
	{
		$this->db->delete($table,$data);
	}
	
	function insertData($table,$data)
	{
		$this->db->insert($table,$data);
	}
	
	function manualQuery($q)
	{
		return $this->db->query($q);
	}
	
	public function getMaxKodeBarang()
	{
		$q = $this->db->query("select MAX(RIGHT(kode_barang,4)) as kd_max from tbl_barang");
		$kd = "";
		if($q->num_rows()>0)
		{
			foreach($q->result() as $k)
			{
				$tmp = ((int)$k->kd_max)+1;
				$kd = sprintf("%04s", $tmp);
			}
		}
		else
		{
			$kd = "0001";
		}	
		return "BR".$kd;
	}
	
	public function getMaxKodePelanggan()
	{
		$q = $this->db->query("select MAX(RIGHT(kode_pelanggan,5)) as kd_max from tbl_pelanggan");
		$kd = "";
		if($q->num_rows()>0)
		{
			foreach($q->result() as $k)
			{
				$tmp = ((int)$k->kd_max)+1;
				$kd = sprintf("%05s", $tmp);
			}
		}
		else
		{
			$kd = "00001";
		}	
		return "PL".$kd;
	}
	
	public function getMaxNoPR()
	{
		$query = $this->db->query("SELECT max(to_number(substr(no_pr,12,4),'9999')) no_pr FROM mst.tt_purchase_request");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = 'PR'.date('Ymd').'-'.str_pad($row->no_pr + 1,4,"0",STR_PAD_LEFT);
                }
                return $return_value;
	}
	
	public function getMaxNoPO()
	{
		$query = $this->db->query("SELECT max(to_number(substr(no_po,12,4),'9999')) no_po FROM mst.tt_purchase_order");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = 'PO'.date('Ymd').'-'.str_pad($row->no_po + 1,4,"0",STR_PAD_LEFT);
                }
                return $return_value;
	}

	public function getMaxNoRO()
	{
		$query = $this->db->query("SELECT max(to_number(substr(no_ro,12,4),'9999')) no_ro FROM mst.tt_receive_order");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = 'RO'.date('Ymd').'-'.str_pad($row->no_ro + 1,4,"0",STR_PAD_LEFT);
                }
                return $return_value;
	}
	
	public function getMaxNoRetur()
	{
		$query = $this->db->query("SELECT max(to_number(substr(no_retur,12,4),'9999')) no_retur FROM mst.tt_retur_order");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = 'RET'.date('Ymd').'-'.str_pad($row->no_retur + 1,4,"0",STR_PAD_LEFT);
                }
                return $return_value;
	}
	
	public function getMaxNoPOS()
	{
		$query = $this->db->query("SELECT max(to_number(substr(no_pos,12,4),'9999')) no_pos FROM mst.tt_penjualan_barang");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = 'PS'.date('Ymd').'-'.str_pad($row->no_pos + 1,4,"0",STR_PAD_LEFT);
                }
                return $return_value;
	}

	public function getMaxNoMS()
	{
		$query = $this->db->query("SELECT max(to_number(substr(no_mutasi,12,4),'9999')) no_ms FROM mst.tt_mst_mutasi_barang");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = 'MS'.date('Ymd').'-'.str_pad($row->no_ms + 1,4,"0",STR_PAD_LEFT);
                }
                return $return_value;
	}

	public function getMaxNoPRKons()
	{
		$query = $this->db->query("SELECT max(to_number(substr(no_pr,12,4),'9999')) no_pr FROM mst.tt_purchase_request_kons");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = 'PR'.date('Ymd').'-'.str_pad($row->no_pr + 1,4,"0",STR_PAD_LEFT);
                }
                return $return_value;
	}
	
	public function getMaxNoSKB()
	{
		$query = $this->db->query("SELECT max(to_number(substr(no_skb,12,4),'9999')) no_po FROM mst.tt_skb_kons");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = 'SKB'.date('Ymd').'-'.str_pad($row->no_skb + 1,4,"0",STR_PAD_LEFT);
                }
                return $return_value;
	}
	
	public function getMaxNoROKons()
	{
		$query = $this->db->query("SELECT max(to_number(substr(no_ro,12,4),'9999')) no_ro FROM mst.tt_receive_order_kons");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = 'RO'.date('Ymd').'-'.str_pad($row->no_ro + 1,4,"0",STR_PAD_LEFT);
                }
                return $return_value;
	}
	
	public function getMaxNoReturKons()
	{
		$query = $this->db->query("SELECT max(to_number(substr(no_retur,12,4),'9999')) no_retur FROM mst.tt_retur_order_kons");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = 'RET'.date('Ymd').'-'.str_pad($row->no_retur + 1,4,"0",STR_PAD_LEFT);
                }
                return $return_value;
	}
	
	public function getMaxKodeFaktur()
	{
		$q = $this->db->query("select MAX(RIGHT(kode_faktur,8)) as kd_max from tbl_faktur");
		$kd = "";
		if($q->num_rows()>0)
		{
			foreach($q->result() as $k)
			{
				$tmp = ((int)$k->kd_max)+1;
				$kd = sprintf("%08s", $tmp);
			}
		}
		else
		{
			$kd = "00000001";
		}	
		return "FK".$kd;
	}
	
	public function getMaxKodeSuratJalan()
	{
		$q = $this->db->query("select MAX(RIGHT(kode_surat_jalan,8)) as kd_max from tbl_surat_jalan");
		$kd = "";
		if($q->num_rows()>0)
		{
			foreach($q->result() as $k)
			{
				$tmp = ((int)$k->kd_max)+1;
				$kd = sprintf("%08s", $tmp);
			}
		}
		else
		{
			$kd = "00000001";
		}	
		return "SJ".$kd;
	}
	
	public function qty_in_tm_produk($kd_produk)
	{
		$q = $this->db->query("select qty_in from mst.tm_produk where kd_produk='".$kd_produk."'");
		$qty_in = "";
		foreach($q->result() as $d)
		{
			if (!$d->qty_in)
			{$qty_in = 0;} else {$qty_in = $d->qty_in;}
			
		}
		return $qty_in;
	}
	
	public function qty_out_tm_produk($kd_produk)
	{
		$q = $this->db->query("select qty_out from mst.tm_produk where kd_produk='".$kd_produk."'");
		$qty_out = "";
		foreach($q->result() as $d)
		{
			if (!$d->qty_out)
			{$qty_out = 0;} else {$qty_out = $d->qty_out;}
			
		}
		return $qty_out;
	}

	public function qty_oh_tm_produk($kd_produk)
	{
		$q = $this->db->query("select qty_oh from mst.tm_produk where kd_produk='".$kd_produk."'");
		$qty_oh = "";
		foreach($q->result() as $d)
		{
			if (!$d->qty_oh)
			{$qty_oh = 0;} else {$qty_oh = $d->qty_oh;}
		}
		return $qty_oh;
	}

	public function cari_td_lokasi_per_brg($kd_produk,$kd_lokasi,$kd_blok,$kd_sub_blok)
	{
		$q = $this->db->query("select count(*) as jml from mst.td_lokasi_per_brg where kd_produk='".$kd_produk."' and kd_lokasi='".$kd_lokasi."' and kd_blok='".$kd_blok."' and kd_sub_blok='".$kd_sub_blok."'");
		$jml = "";
		foreach($q->result() as $d)
		{
			if (!$d->jml)
			{$jml = 0;} else {$jml = $d->jml;}
		}
		return $jml;
	}

	public function qty_in_td_lokasi_per_brg($kd_produk,$kd_lokasi,$kd_blok,$kd_sub_blok)
	{
		$q = $this->db->query("select qty_in from mst.td_lokasi_per_brg where kd_produk='".$kd_produk."' and kd_lokasi='".$kd_lokasi."' and kd_blok='".$kd_blok."' and kd_sub_blok='".$kd_sub_blok."'");
		$qty_in = "";
		foreach($q->result() as $d)
		{
			if (!$d->qty_in)
			{$qty_in = 0;} else {$qty_in = $d->qty_in;}
		}
		return $qty_in;
	}
	
	public function qty_out_td_lokasi_per_brg($kd_produk,$kd_lokasi,$kd_blok,$kd_sub_blok)
	{
		$q = $this->db->query("select qty_out from mst.td_lokasi_per_brg where kd_produk='".$kd_produk."' and kd_lokasi='".$kd_lokasi."' and kd_blok='".$kd_blok."' and kd_sub_blok='".$kd_sub_blok."'");
		$qty_out = "";
		foreach($q->result() as $d)
		{
			if (!$d->qty_out)
			{$qty_out = 0;} else {$qty_out = $d->qty_out;}
		}
		return $qty_out;
	}

	public function qty_oh_td_lokasi_per_brg($kd_produk,$kd_lokasi,$kd_blok,$kd_sub_blok)
	{
		$q = $this->db->query("select qty_oh from mst.td_lokasi_per_brg where kd_produk='".$kd_produk."' and kd_lokasi='".$kd_lokasi."' and kd_blok='".$kd_blok."' and kd_sub_blok='".$kd_sub_blok."'");
		$qty_oh = "";
		foreach($q->result() as $d)
		{
			if (!$d->qty_oh)
			{$qty_oh = 0;} else {$qty_oh = $d->qty_oh;}
		}
		return $qty_oh;
	}

	public function cari_td_brg_per_bln_thn($kd_produk,$bulan,$tahun)
	{
		$q = $this->db->query("select count(*) as jml from mst.td_brg_per_bln_thn where kd_produk='".$kd_produk."' and bulan='".$bulan."' and tahun='".$tahun."'");
		$jml = "";
		foreach($q->result() as $d)
		{
			if (!$d->jml)
			{$jml = 0;} else {$jml = $d->jml;}
		}
		return $jml;
	}

	public function qty_in_td_brg_per_bln_thn($kd_produk,$bulan,$tahun)
	{
		$q = $this->db->query("select qty_in from mst.td_brg_per_bln_thn where kd_produk='".$kd_produk."' and bulan='".$bulan."' and tahun='".$tahun."'");
		$qty_in = "";
		foreach($q->result() as $d)
		{
			if (!$d->qty_in)
			{$qty_in = 0;} else {$qty_in = $d->qty_in;}
		}
		return $qty_in;
	}
	
	public function qty_out_td_brg_per_bln_thn($kd_produk,$bulan,$tahun)
	{
		$q = $this->db->query("select qty_out from mst.td_brg_per_bln_thn where kd_produk='".$kd_produk."' and bulan='".$bulan."' and tahun='".$tahun."'");
		$qty_out = "";
		foreach($q->result() as $d)
		{
			if (!$d->qty_out)
			{$qty_out = 0;} else {$qty_out = $d->qty_out;}
		}
		return $qty_out;
	}

	public function qty_oh_td_brg_per_bln_thn($kd_produk,$bulan,$tahun)
	{
		$q = $this->db->query("select qty_oh from mst.td_brg_per_bln_thn where kd_produk='".$kd_produk."' and bulan='".$bulan."' and tahun='".$tahun."'");
		$qty_oh = "";
		foreach($q->result() as $d)
		{
			if (!$d->qty_oh)
			{$qty_oh = 0;} else {$qty_oh = $d->qty_oh;}
		}
		return $qty_oh;
	}
	
	public function qty_mutasi_in_td_lokasi_per_brg($kd_produk,$kd_lokasi,$kd_blok,$kd_sub_blok)
	{
		$q = $this->db->query("select qty_mutasi_in from mst.td_lokasi_per_brg where kd_produk='".$kd_produk."' and kd_lokasi='".$kd_lokasi."' and kd_blok='".$kd_blok."' and kd_sub_blok='".$kd_sub_blok."'");
		$qty_mutasi_in = "";
		foreach($q->result() as $d)
		{
			if (!$d->qty_mutasi_in)
			{$qty_mutasi_in = 0;} else {$qty_mutasi_in = $d->qty_mutasi_in;}
		}
		return $qty_mutasi_in;
	}

	public function qty_mutasi_out_td_lokasi_per_brg($kd_produk,$kd_lokasi,$kd_blok,$kd_sub_blok)
	{
		$q = $this->db->query("select qty_mutasi_out from mst.td_lokasi_per_brg where kd_produk='".$kd_produk."' and kd_lokasi='".$kd_lokasi."' and kd_blok='".$kd_blok."' and kd_sub_blok='".$kd_sub_blok."'");
		$qty_mutasi_out = "";
		foreach($q->result() as $d)
		{
			if (!$d->qty_mutasi_out)
			{$qty_mutasi_out = 0;} else {$qty_mutasi_out = $d->qty_mutasi_out;}
		}
		return $qty_mutasi_out;
	}

	public function qty_mutasi_oh_td_lokasi_per_brg($kd_produk,$kd_lokasi,$kd_blok,$kd_sub_blok)
	{
		$q = $this->db->query("select qty_oh from mst.td_lokasi_per_brg where kd_produk='".$kd_produk."' and kd_lokasi='".$kd_lokasi."' and kd_blok='".$kd_blok."' and kd_sub_blok='".$kd_sub_blok."'");
		$qty_mutasi_oh = "";
		foreach($q->result() as $d)
		{
			if (!$d->qty_oh)
			{$qty_mutasi_oh = 0;} else {$qty_mutasi_oh = $d->qty_oh;}
		}
		return $qty_mutasi_oh;
	}

	public function getBalancedStok($kd_produk,$kurangi)
	{
		$q = $this->db->query("select qty_oh from mst.tm_produk where kd_produk='".$kd_produk."'");
		$qty_oh = "";
		foreach($q->result() as $d)
		{
			$qty_oh = $d->qty_oh-$kurangi;
		}
		return $qty_oh;
	}
	
	
	//query login
	public function getLoginData($usr,$psw)
	{
		$u = mysql_real_escape_string($usr);
		$p = md5(mysql_real_escape_string($psw.'appFakturDlmbg32'));
		$q_cek_login = $this->db->get_where('tbl_login', array('username' => $u, 'password' => $p));
		if(count($q_cek_login->result())>0)
		{
			foreach($q_cek_login->result() as $qck)
			{
				if($qck->stts=='admin')
				{
					foreach($q_cek_login->result() as $qad)
					{
						$sess_data['logged_in'] = 'yesGetMeLogin';
						$sess_data['username'] = $qad->username;
						$sess_data['nama_pengguna'] = $qad->nama_pengguna;
						$sess_data['stts'] = $qad->stts;
						$this->session->set_userdata($sess_data);
					}
					header('location:'.base_url().'pemesanan/pending');
				}
			}
		}
		else
		{
			$this->session->set_flashdata('result_login', 'Username atau Password yang anda masukkan salah.');
			header('location:'.base_url().'front');
		}
	}
}

/* End of file app_model.php */
/* Location: ./application/models/app_model.php */