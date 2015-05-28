<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$timezone = "Asia/Jakarta";
if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
//echo date('d-m-Y H:i:s');

$localtime=date('H:i:s');
$localdate=date('Y-m-d');
$today=date('Y-m-d H:i:s');

class Mutasibarang extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mutasibarang_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Home - Master - Mutasi Barang'
        );

        if ($this->session->userdata('username')) {

			$this->session->unset_userdata('no_mutasi');
			$this->session->unset_userdata('keterangan');
			$this->session->unset_userdata('limit_add_cart');

            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
            $mutasibarang = $this->mutasibarang_models->mutasibarang_content();
            $data['rcmutasibarang']=$mutasibarang;
            $this->load->view('mutasi/vwmutasibarang', $data);

			$this->cart->destroy();
			
		} else {
			$this->cart->destroy();
            redirect(base_url());
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
                $query = $this->mutasibarang_models->getData($this->uri->segment(3));
				foreach ($query as $row) {
					$data = $row;
				}
            } else {
				$data['listlokasi']= $this->app_model->manualquery("select d.kode,d.nama_lokasi,d.kapasitas,e.qty_terisi from
																	(select c.kd_lokasi||b.kd_blok||a.kd_sub_blok kode,c.nama_lokasi||' '||b.nama_blok||' '||a.nama_sub_blok nama_lokasi, kapasitas from mst.tm_sub_blok a, mst.tm_blok b, mst.tm_lokasi c where a.kd_lokasi=c.kd_lokasi and a.kd_blok=b.kd_blok and b.kd_lokasi = c.kd_lokasi and a.aktif is true order by nama_lokasi asc) d 
																	left outer join 
																	(select kd_lokasi||kd_blok||kd_sub_blok kode,sum(qty_oh) qty_terisi from mst.td_lokasi_per_brg group by kd_lokasi,kd_blok,kd_sub_blok) e on d.kode=e.kode");
				$data['id_mutasi'] = '';
                $data['no_ms'] = $this->app_model->getMaxNoMS();
                $data['tgltrans'] = date('d-M-Y');
                $data['keterangan'] = $this->session->userdata("keterangan");
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Home - Master - Mutasi Barang';

			/*Kategori 1
			$ambil_lokasi = $this->mutasibarang_models->get_lokasi();
			if(is_array($ambil_lokasi))
			{
				$listlokasi[0] = 'Pilih Lokasi';
				foreach ($ambil_lokasi as $barislokasi)
				{
					$listlokasi[$barislokasi->kd_lokasi] = $barislokasi->nama_lokasi;
				}

				$data['listlokasi'] = $listlokasi;
			}
			else
			{
				$data['listlokasi'] = array('' => 'Tidak ada data');
			}
			
			//Blok
			$data['listblok'] = array('' => 'Pilih Blok');
			//Sub Blok
			$data['listsubblok'] = array('' => 'Pilih Sub Blok');*/
		}

        if ($this->session->userdata('username')) {
            $this->load->view('mutasi/formmutasibarang', $data);
        } else {
            redirect(base_url());
        }
    }

	function grab_blok()
	{
		if($_POST)
		{
			$result = $this->mutasibarang_models->get_blok($this->input->post('kd_lokasi'));
			if(is_array($result))
			{
				// jika hasil query array maka looping hasil query
					echo '<option value="">Pilih Blok</option>';
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

	function grab_sub_blok()
	{
		if($_POST)
		{
			$result = $this->mutasibarang_models->get_sub_blok($this->input->post('kd_blok'));
			if(is_array($result))
			{
				// jika hasil query array maka looping hasil query
					echo '<option value="">Pilih Sub Blok</option>';
				foreach ($result as $row)
				{
					echo '<option value="'.$row->kd_sub_blok.'">'.$row->nama_sub_blok.'</option>';
				}
			}
			else
			{
				// tampilkan jika data hasil query kosong
				echo '<option value="">Tidak ada data</option>';
			}
		}
	}
	
	public function addsessionketerangan()
	{
			$data["keterangan"] = $this->input->post("keterangan");
			$sess_data['keterangan'] = $data["keterangan"];
			$this->session->set_userdata($sess_data);
	}
	
	public function daftar_Produk()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			$bc['jdl'] = "Daftar Produk";
			$bc['tm_produk'] = '';
			
			$this->load->view('mutasi/daftar_produk',$bc);
		}
		else
		{
            redirect(base_url());
		}
	}

	
	public function ambil_data_produk()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			$data["kd_produk"] = $_GET["kd_produk"];
			$q = $this->app_model->manualquery("select a.kd_produk,nama_produk,qty_oh_produk,kodelokasi lokasilama,nama_lokasi nama_lokasilama,qty_terisi from 
						(select kd_produk,nama_produk,qty_oh qty_oh_produk
						from mst.tm_produk) a
						inner join 
						(select kd_produk,kd_lokasi||kd_blok||kd_sub_blok kodelokasi,sum(qty_oh) qty_terisi from mst.td_lokasi_per_brg
						group by kd_produk,kd_lokasi,kd_blok,kd_sub_blok) b
						on a.kd_produk=b.kd_produk
						inner join
						(select c.kd_lokasi||b.kd_blok||a.kd_sub_blok kode,c.nama_lokasi||' '||b.nama_blok||' '||a.nama_sub_blok nama_lokasi, kapasitas
						from mst.tm_sub_blok a, mst.tm_blok b, mst.tm_lokasi c 
						where a.kd_lokasi=c.kd_lokasi and a.kd_blok=b.kd_blok and b.kd_lokasi = c.kd_lokasi and a.aktif is true) c
						on c.kode=b.kodelokasi
						where a.kd_produk like '%".$data["kd_produk"]."%' or a.nama_produk like '%".$data["kd_produk"]."%'");
			$no=0;
			?>
<!--			<div class="widget">
				<div class="whead"><h6>Tabel Mutasi Barang</h6><div class="clear"></div></div>
				<div class="shownpars">
						<table cellpadding="0" cellspacing="0" border="0" class="tDefault">
						<thead>
							<tr>
								<th><b>NO</b></th>
								<th><b>KODE PRODUK</b></th>
								<th align="center"><b>NAMA PRODUK</b></th>
								<th align="center"><b>LOKASI</b></th>
								<th align="center"><b>QTY</b></th>
								<th><b>ACTION</b></th>
							</tr>
							</thead> 
							<tbody> -->
							<?  foreach ($q->result() as $row) 
								{ $no=$no+1;	?>
							<form action="<?=base_url();?>mutasibarang/addcart" method="post" class="form colours">
							<tr class="gradeX">
									<td align="center"><?echo $no; ?></td>
									<td align="center"><? echo $row->kd_produk; ?><input type="hidden" name="kd_produk[]" value="<?php echo $row->kd_produk; ?>" /></td>
									<td align="left"><? echo $row->nama_produk; ?></td>
									<td align="left"><? echo $row->nama_lokasilama; ?><input type="hidden" name="lokasilama[]" value="<?php echo $row->lokasilama; ?>" /></td>
									<td align="right"><? echo $row->qty_terisi; ?></td>
									<td align="center">
									<!--<a href="mutasibarang/addcart/'.<?php echo $row->kd_produk;?>.'/'.<?php echo $row->lokasilama;?>.'" title="Add" class="bDefault tipS">-->
									<input type="button" onclick="javascript:window.location.href='<?php echo base_url(); ?>mutasibarang/addcart/<?php echo $row->kd_produk; ?>/<?php echo $row->lokasilama;?>'" name="add" id="add" value="Add" class="buttonM bBlue" />
									</td>
								</tr>
							</form>
								<?};?>
<!--							</tbody> 
					</table> 
				</div>
			</div> -->
			<?
		}
		else
		{
            redirect(base_url());
		}
	}
	
	public function addcart()
	{
		$cek = $this->session->userdata('username');
		
		if(!empty($cek))
		{
			    $query = $this->app_model->manualquery("select a.kd_produk,nama_produk,qty_oh_produk,kodelokasi lokasilama,nama_lokasi nama_lokasilama, 
						kapasitas,qty_terisi  from 
						(select kd_produk,nama_produk,qty_oh qty_oh_produk
						from mst.tm_produk) a
						inner join 
						(select kd_produk,kd_lokasi||kd_blok||kd_sub_blok kodelokasi,sum(qty_oh) qty_terisi from mst.td_lokasi_per_brg
						group by kd_produk,kd_lokasi,kd_blok,kd_sub_blok) b
						on a.kd_produk=b.kd_produk
						inner join
						(select c.kd_lokasi||b.kd_blok||a.kd_sub_blok kode,c.nama_lokasi||' '||b.nama_blok||' '||a.nama_sub_blok nama_lokasi, kapasitas
						from mst.tm_sub_blok a, mst.tm_blok b, mst.tm_lokasi c 
						where a.kd_lokasi=c.kd_lokasi and a.kd_blok=b.kd_blok and b.kd_lokasi = c.kd_lokasi and a.aktif is true) c
						on c.kode=b.kodelokasi
						where a.kd_produk = '".$this->uri->segment(3)."' and kodelokasi = '".$this->uri->segment(4)."'");
					$localtime=date('His');
					foreach ($query->result() as $row) {
						$data = array(
						'id'         => $localtime,
						'qty'        => 1,
						'price'      => 1,
						'name'       => '-',
						'kdproduk'   => $row->kd_produk,
						'namap'    	 => $row->nama_produk,
						'kodelokasilama' => $row->lokasilama,
						'lokasilama' => $row->nama_lokasilama,
						'kapasitas'  => $row->kapasitas,
						'qtyoh'      => $row->qty_terisi,
						'qtymutasi'  => $row->qty_terisi,
						'keterangan' => '',
						'options'    => array('status' => '0'));
				}
			/*$datatemp = array(
				'id_pr' => 1,
				'no_pr' => 001,
				'kd_produk' => $this->input->post('kd_produk'),
				'kd_kategori1' => substr($this->input->post('kd_produk'),0,2),
				'kd_kategori2' => substr($this->input->post('kd_produk'),2,2),
				'kd_kategori3' => substr($this->input->post('kd_produk'),4,2),
				'kd_kategori4' => substr($this->input->post('kd_produk'),6,2),
				'no_urut' => substr($this->input->post('kd_produk'),10,3),
				'qty' => $this->input->post('qty'),
				'thn_reg' => substr($this->input->post('kd_produk'),8, 2)
            );*/
			$this->cart->insert($data);
			//$this->purchaserequest_models->add_record($datatemp);
			//header('location:'.base_url().'purchaserequest/form');

			?>
				<script>
					window.parent.location.reload(true);
				</script>
			<?php
		}
		else
		{
            redirect(base_url());
		}
	}
	
    public function save() {
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{

				$d_header['created_date'] = date('Y-m-d H:i:s');
				$d_header['created_by'] = $this->session->userdata("username");
		
				$total = $this->cart->total_items('rowid');
				$item = $this->input->post('rowid');
				$kd_produk = $this->input->post('kd_produk');
				$lokasilama = $this->input->post('lokasilama');
				$lokasitujuan = $this->input->post('lokasitujuan');
				$qty_mutasi = $this->input->post('qty_mutasi');
				$qty_oh = $this->input->post('qty_oh');
				$kapasitas = $this->input->post('kapasitas');
				
				$cnt_err1=0;$cnt_err2=0;$cnt_err3=0;
				for($i=0; $i < $total; $i++)
				{
					if ($lokasitujuan[$i]=='-')
					{
						$cnt_err1=$cnt_err1+1;
					}else if ($qty_mutasi[$i]=='')
					{
						$cnt_err2=$cnt_err2+1;
					}else if ($qty_mutasi[$i] > $qty_oh[$i])
					{
						$cnt_err3=$cnt_err3+1;
					}
				}
				
				if ($cnt_err1 > 0)
				{
				?><script>window.alert('Lokasi tujuan harus di isi'); window.history.go(-1);</script><?php
				}else if ($cnt_err2 > 0)
				{
				?><script>window.alert('Qty mutasi harus di isi'); window.history.go(-1);</script><?php
				}else if ($cnt_err3 > 0)
				{
				?><script>window.alert('Qty mutasi tidak boleh lebih besar dari qty oh'); window.history.go(-1);</script><?php
				}
				else
				{

					$today=date('Y-m-d H:i:s');
					$this->app_model->manualquery("insert into mst.tt_mst_mutasi_barang(no_mutasi,created_by,created_date,keterangan)
												  values('".$this->input->post('no_ms')."','".$this->session->userdata('username')."',
														 '".$today."','".$this->session->userdata('keterangan')."')");

					for($i=0; $i < $total; $i++)
					{
					$data['qty_mutasi_in'] = $qty_mutasi[$i]+$this->app_model->qty_mutasi_in_td_lokasi_per_brg($kd_produk[$i],substr($lokasitujuan[$i],0,2),substr($lokasitujuan[$i],2,2),substr($lokasitujuan[$i],4,2));
					
					$data['qty_mutasi_out'] = $qty_mutasi[$i]+$this->app_model->qty_mutasi_out_td_lokasi_per_brg($kd_produk[$i],substr($lokasilama[$i],0,2),substr($lokasilama[$i],2,2),substr($lokasilama[$i],4,2));

					$data['qty_oh_out'] = $this->app_model->qty_mutasi_oh_td_lokasi_per_brg($kd_produk[$i],substr($lokasilama[$i],0,2),substr($lokasilama[$i],2,2),substr($lokasilama[$i],4,2))-$qty_mutasi[$i];

					$data['qty_oh_in'] = $qty_mutasi[$i]+$this->app_model->qty_mutasi_oh_td_lokasi_per_brg($kd_produk[$i],substr($lokasitujuan[$i],0,2),substr($lokasitujuan[$i],2,2),substr($lokasitujuan[$i],4,2));
					
					$this->app_model->manualquery(" insert into
													mst.tt_dtl_mutasi_barang(no_mutasi,kd_produk,kd_kategori1,kd_kategori2,kd_kategori3,kd_kategori4,thn_reg, no_urut,kd_lokasi_lama,kd_blok_lama,kd_sub_blok_lama,kd_lokasi_baru,kd_blok_baru,kd_sub_blok_baru,qty_pindah)
													values(
													'".$this->input->post('no_ms')."','".$kd_produk[$i]."',
													'".substr($kd_produk[$i],0,2)."','".substr($kd_produk[$i],2,2)."','".substr($kd_produk[$i],4,2)."',
													'".substr($kd_produk[$i],6,2)."','".substr($kd_produk[$i],8,2)."','".substr($kd_produk[$i],10,3)."',
													'".substr($lokasilama[$i],0,2)."','".substr($lokasilama[$i],2,2)."','".substr($lokasilama[$i],4,2)."',
													'".substr($lokasitujuan[$i],0,2)."','".substr($lokasitujuan[$i],2,2)."','".substr($lokasitujuan[$i],4,2)."',
													'".$qty_mutasi[$i]."') ");
					
					$this->app_model->manualquery("UPDATE mst.td_lokasi_per_brg
							   SET qty_mutasi_out='".$data['qty_mutasi_out']."', qty_oh=".$data['qty_oh_out']."
							   WHERE kd_lokasi='".substr($lokasilama[$i],0,2)."' and kd_blok='".substr($lokasilama[$i],2,2)."' and kd_sub_blok='".substr($lokasilama[$i],4,2)."' and kd_produk='".$kd_produk[$i]."'");
					
					$data['select']=$this->app_model->manualquery("select count(1) from mst.td_lokasi_per_brg where kd_lokasi='".substr($lokasitujuan[$i],0,2)."' and kd_blok='".substr($lokasitujuan[$i],2,2)."' and kd_sub_blok='".substr($lokasitujuan[$i],4,2)."' and kd_produk='".$kd_produk[$i]."'");
					if ($data['select'] != '0')
						{
						$this->app_model->manualquery("UPDATE mst.td_lokasi_per_brg
								   SET qty_mutasi_in='".$data['qty_mutasi_in']."', qty_oh=".$data['qty_oh_in']."
								   WHERE kd_lokasi='".substr($lokasitujuan[$i],0,2)."' and kd_blok='".substr($lokasitujuan[$i],2,2)."' and kd_sub_blok='".substr($lokasitujuan[$i],4,2)."' and kd_produk='".$kd_produk[$i]."'");
						}else{
						$this->app_model->manualquery("insert into mst.td_lokasi_per_brg(kd_lokasi,kd_blok,kd_sub_blok,kd_produk,kd_kategori1,kd_kategori2,kd_kategori3,kd_kategori4,thn_reg,no_urut,qty_oh,qty_mutasi_in,created_by,created_date) VALUES ('".substr($lokasitujuan[$i],0,2)."','".substr($lokasitujuan[$i],2,2)."','".substr($lokasitujuan[$i],4,2)."','".$kd_produk[$i]."','".substr($kd_produk[$i],0,2)."','".substr($kd_produk[$i],2,2)."','".substr($kd_produk[$i],4,2)."', '".substr($kd_produk[$i],6,2)."','".substr($kd_produk[$i],8,2)."','".substr($kd_produk[$i],10,3)."','".$qty_mutasi[$i]."','".$qty_mutasi[$i]."','".$d_header['created_by']."','".$d_header['created_date']."')");
						}
							   
					}
					?>
						<script>
							window.alert('Data mutasi tersimpan'); window.location=('<? echo base_url();?>mutasibarang');
						</script>
						
					<?php

			}

		}
		else
		{
            redirect(base_url());
		}
    }

	public function detail_mutasibarang()
	{
			
			if ($this->uri->segment(3)) {

				$this->cart->destroy();

				$id['no_mutasi'] = $this->uri->segment(3);
				$bc['mutasi_header'] = $this->app_model->getSelectedData("mst.tt_mst_mutasi_barang",$id);
				foreach($bc['mutasi_header']->result() as $dph)
				{
					$key['no_mutasi'] = $dph->no_mutasi;
					$key['keterangan'] = $dph->keterangan;
				}

				$data['mutasi_detail'] = $this->app_model->manualQuery("select a.no_mutasi,b.kd_produk,c.nama_produk,d.nama_lokasi_lama,e.nama_lokasi_baru, b.qty_pindah qty_mutasi,a.created_date from (select * from mst.tt_mst_mutasi_barang) a
				inner join (select * from mst.tt_dtl_mutasi_barang) b on a.no_mutasi=b.no_mutasi
				inner join (select kd_produk,nama_produk from mst.tm_produk) c on b.kd_produk=c.kd_produk
				inner join (select c.kd_lokasi||b.kd_blok||a.kd_sub_blok kode,c.nama_lokasi||' '||b.nama_blok||' '||a.nama_sub_blok nama_lokasi_lama, kapasitas
				from mst.tm_sub_blok a, mst.tm_blok b, mst.tm_lokasi c 
				where a.kd_lokasi=c.kd_lokasi and a.kd_blok=b.kd_blok and b.kd_lokasi = c.kd_lokasi and a.aktif is true) d
				on b.kd_lokasi_lama||b.kd_blok_lama||b.kd_sub_blok_lama=d.kode
				inner join
				(select c.kd_lokasi||b.kd_blok||a.kd_sub_blok kode,c.nama_lokasi||' '||b.nama_blok||' '||a.nama_sub_blok nama_lokasi_baru, kapasitas
				from mst.tm_sub_blok a, mst.tm_blok b, mst.tm_lokasi c 
				where a.kd_lokasi=c.kd_lokasi and a.kd_blok=b.kd_blok and b.kd_lokasi = c.kd_lokasi and a.aktif is true) e
				on b.kd_lokasi_baru||b.kd_blok_baru||b.kd_sub_blok_baru=e.kode
				where a.no_mutasi='".$key['no_mutasi']."'
				order by a.no_mutasi asc");

					
					$data['no_mutasi']=$key['no_mutasi'];
					$data['keterangan']=$key['keterangan'];

					$this->load->view('mutasi/vw_detail_mutasi',$data);
		}
		else
		{
            redirect(base_url());
		}
	}

}

?>