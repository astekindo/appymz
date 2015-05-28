<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$timezone = "Asia/Jakarta";
if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
//echo date('d-m-Y H:i:s');

$localtime=date('H:i:s');
$localdate=date('Y-m-d');
$today=date('Y-m-d H:i:s');

class Retur_order extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('retur_order_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Home - Pembelian - Retur Order'
        );

        if ($this->session->userdata('username')) {
			$this->cart->destroy();
			$this->session->unset_userdata('no_ro_retur');
			$this->session->unset_userdata('kd_supplier_retur');
			$this->session->unset_userdata('nama_supplier_retur');
			$this->session->unset_userdata('limit_add_cart_retur');
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
            $res_retur = $this->retur_order_models->returorder_content();
            $data['rcreturorder']=$res_retur;
            $this->load->view('retur/vw_listreturorder', $data);
        } else {
             redirect(base_url());
        }
    }

    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {

				$id['no_ro'] = $this->uri->segment(3);
				$bc['dt_ro_header'] = $this->app_model->getSelectedData("mst.tt_retur_order",$id);
				$bc['dt_ro_header'] = $this->app_model->manualquery("
					select a.no_ro, a.kd_supplier, b.nama_supplier, a.created_date
					from mst.tt_receive_order a
					join mst.tm_supplier b on (b.kd_supplier = a.kd_supplier) 
					where a.no_ro='".$id['no_ro']."'
				");
				foreach($bc['dt_ro_header']->result() as $dph)
				{
					$sess_data1['no_ro_retur'] = $dph->no_ro;
					$sess_data2['kd_supplier_retur'] = $dph->kd_supplier;
					$sess_data3['nama_supplier_retur'] = $dph->nama_supplier;
					$key['no_ro'] = $dph->no_ro;
				
					$this->session->set_userdata($sess_data1);
					$this->session->set_userdata($sess_data2);
					$this->session->set_userdata($sess_data3);
				}

				$bc['dt_retur_detail'] = $this->app_model->manualQuery("
					SELECT a.no_ro, a.kd_produk, a.thn_reg, b.nama_produk, b.id_satuan, c.nm_satuan, a.qty_terima,
						a.disk_persen_supp1, a.disk_persen_supp2, a.disk_persen_supp3, a.disk_persen_supp4,
						a.disk_amt_supp1, a.disk_amt_supp2, a.disk_amt_supp3, a.disk_amt_supp4, a.hrg_supplier
					FROM mst.tt_dtl_receive_order a 
					LEFT JOIN mst.tm_produk b on a.kd_produk=b.kd_produk 
					LEFT JOIN mst.tm_satuan c on b.id_satuan=c.id_satuan
					where a.no_ro='".$key['no_ro']."'
				");
					$in_cart = array();
					foreach($bc['dt_retur_detail']->result() as $dpd)
					{	
						$thnreg = str_pad($dpd->thn_reg,4,"20",STR_PAD_LEFT);
						$in_cart[] = array(
							'id'         			=> $dpd->kd_produk,
							'qty'        			=> $dpd->qty_terima,
							'price'      			=> 100,
							'name'       			=> '-',
							'thn_reg'    			=> $thnreg,
							'satuan'     			=> $dpd->nm_satuan,
							'namap'      			=> $dpd->nama_produk,
							'disk_persen_supp1'		=> $dpd->disk_persen_supp1,
							'disk_persen_supp2'		=> $dpd->disk_persen_supp2,
							'disk_persen_supp3'		=> $dpd->disk_persen_supp3,
							'disk_persen_supp4'		=> $dpd->disk_persen_supp4,
							'disk_amt_supp1'		=> $dpd->disk_amt_supp1,
							'disk_amt_supp2'		=> $dpd->disk_amt_supp2,
							'disk_amt_supp3'		=> $dpd->disk_amt_supp3,
							'disk_amt_supp4'		=> $dpd->disk_amt_supp4,
							'hrg_supplier'			=> $dpd->hrg_supplier,
							//'dpp'					=> $dpd->dpp,
							'options'    			=> array('status' => '0')
						);
					}
					//$this->cart->insert($in_cart);

                $data['id_retur'] = '';
                $data['no_retur'] = $this->app_model->getMaxNoRetur();
				$data['kd_supplier'] = $this->session->userdata("kd_supplier_retur");
				$data['nama_supplier'] = $this->session->userdata("nama_supplier_retur");
				$data['tt_ro'] = $this->app_model->manualquery("select no_ro from mst.tt_receive_order");
				$data['no_ro'] = $this->session->userdata("no_ro_retur");
                $tgltrans = new DateTime($this->session->userdata("created_date"));
				$data['tgltrans'] = $tgltrans->format('d-M-Y');

            } else {
                $data['id_retur'] = '';
                $data['no_retur'] = $this->app_model->getMaxNoRetur();
				$data['tt_ro'] = $this->app_model->manualquery("select no_ro from mst.tt_receive_order");
				$data['no_ro'] = '';
				$data['kd_supplier'] = '';
				$data['nama_supplier'] = '';
                $data['tgltrans'] = date('d-M-Y');
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata("username");
            $data['title'] = $judul;
            $data['location'] = 'Home - Pembelian - Retur Order';
			$data['listsupplier'] = $this->fungsi->getAllData("mst.tm_supplier");

		}

        if ($this->session->userdata('username')) {
            $this->load->view('retur/vw_input_returorder', $data);
        } else {
             redirect(base_url());
        }
    }

	public function daftar_produk_retur()
	{
		$cek = $this->session->userdata('username');
		
		if(!empty($cek))
		{
			$bc['jdl'] = "Daftar Produk";
			$key['no_ro'] = $this->session->userdata('no_ro_retur');
			$bc['tm_produk_retur'] = $this->app_model->manualQuery("
				select a.no_ro, b.kd_supplier, a.kd_produk, d.nama_produk, a.qty_terima, a.kd_lokasi, a.kd_blok, a.kd_sub_blok, c.waktu_top, c.konsinyasi,
					c.disk_persen_supp1, c.disk_persen_supp2, c.disk_persen_supp3, c.disk_persen_supp4, c.disk_amt_supp1, c.disk_amt_supp2, 
					c.disk_amt_supp3, c.disk_amt_supp4, c.hrg_supplier, c.dpp
				from mst.tt_dtl_receive_order a
				join mst.tt_receive_order b on (b.no_ro = a.no_ro)
				join mst.td_supp_per_brg c on (c.kd_supplier = b.kd_supplier and c.kd_produk = a.kd_produk )
				join mst.tm_produk d on (d.kd_produk = a.kd_produk)
				where a.no_ro = '".$key['no_ro']."'
			");
			
			$this->load->view('retur/daftar_produk_retur',$bc);
		}
		else
		{
            redirect(base_url());
		}
	}

	public function ambil_data_produk()
	{
		$cek = $this->session->userdata('username');
		$no_ro = $this->session->userdata('no_ro_retur');
		if(!empty($cek))
		{
			$data["kd_produk"] = $_GET["kd_produk"];
			$q = $this->app_model->manualquery("
				select a.no_ro, b.kd_supplier, a.kd_produk, d.nama_produk, e.nm_satuan, a.qty_terima, a.kd_lokasi||a.kd_blok||a.kd_sub_blok kode_lokasi, 
					f.nama_lokasi||'-'||g.nama_blok||'-'||h.nama_sub_blok nama_lokasi, c.waktu_top, 
					c.konsinyasi, c.disk_persen_supp1, c.disk_persen_supp2, c.disk_persen_supp3, c.disk_persen_supp4, c.disk_amt_supp1, c.disk_amt_supp2, 
					c.disk_amt_supp3, c.disk_amt_supp4, c.hrg_supplier, c.dpp
				from mst.tt_dtl_receive_order a
				join mst.tt_receive_order b on (b.no_ro = a.no_ro)
				join mst.td_supp_per_brg c on (c.kd_supplier = b.kd_supplier and c.kd_produk = a.kd_produk )
				join mst.tm_produk d on (d.kd_produk = a.kd_produk)
				left join mst.tm_satuan e on (e.id_satuan = d.id_satuan)
				join mst.tm_lokasi f on (f.kd_lokasi = a.kd_lokasi)
				join mst.tm_blok g on (g.kd_blok = a.kd_blok and g.kd_lokasi = f.kd_lokasi)
				join mst.tm_sub_blok h on (h.kd_sub_blok = a.kd_sub_blok and h.kd_blok = g.kd_blok and h.kd_lokasi = g.kd_lokasi)
				where a.no_ro='".$no_ro."'
				and a.kd_produk='".$data["kd_produk"]."'
				");
			foreach($q->result() as $d)
			{
			?>
			<table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
				<thead><tr></tr>
				</thead>
				<tbody>
					<tr>
						<td>Kode Produk</td>
						<td>
							<input type="text" id="kode" name="kode" value="<?php echo $d->kd_produk; ?>" style="width:100px;" class="required" readonly="TRUE" />
						</td>
					</tr>
					<tr>
						<td>Nama Produk</td>
						<td>
							<input type="text" id="nama" name="nama" value="<?php echo $d->nama_produk; ?>" style="width:350px;" class="required" readonly="TRUE" />
						</td>
					</tr>
					<tr>
						<td>Satuan</td>
						<td>
							<input type="text" id="satuan" name="satuan" value="<?php echo $d->nm_satuan; ?>" style="width:100px;" class="required" readonly="TRUE" />
						</td>
					</tr>
					<tr>
						<td>Lokasi</td>
						<td>
							<input type="text" id="kode_lokasi" name="kode_lokasi" value="<?php echo $d->kode_lokasi; ?>" style="width:80px;" class="required" readonly="TRUE" />
							<input type="text" id="nama_lokasi" name="nama_lokasi" value="<?php echo $d->nama_lokasi; ?>" style="width:200px;" class="required" readonly="TRUE" />
						</td>
					</tr>
					<tr>
						<td>Qty</td>
						<td>
							<input type="text" id="qty" name="qty" style="width:50px;" onKeyPress="return onlyNumbers(event);" class="required number" />
						</td>
					</tr>
					<tr>
						<td>Disk % 1</td>
						<td>
							<input type="text" id="disk_persen_supp1" name="disk_persen_supp1" value="<?php echo $d->disk_persen_supp1; ?>" style="width:50px;" class="required" readonly="TRUE" />
						</td>
					</tr>
					<tr>
						<td>Disk % 2</td>
						<td>
							<input type="text" id="disk_persen_supp2" name="disk_persen_supp2" value="<?php echo $d->disk_persen_supp2; ?>" style="width:50px;" class="required" readonly="TRUE" />
						</td>
					</tr>
					<tr>
						<td>Disk % 3</td>
						<td>
							<input type="text" id="disk_persen_supp3" name="disk_persen_supp3" value="<?php echo $d->disk_persen_supp3; ?>" style="width:50px;" class="required" readonly="TRUE" />
						</td>
					</tr>
					<tr>
						<td>Disk % 4</td>
						<td>
							<input type="text" id="disk_persen_supp4" name="disk_persen_supp4" value="<?php echo $d->disk_persen_supp4; ?>" style="width:50px;" class="required" readonly="TRUE" />
						</td>
					</tr>
					<tr>
						<td>Disk Amt 1</td>
						<td>
							<input type="text" id="disk_amt_supp1" name="disk_amt_supp1" value="<?php echo $d->disk_amt_supp1; ?>" style="width:50px;" class="required" readonly="TRUE" />
						</td>
					</tr>
					<tr>
						<td>Disk Amt 2</td>
						<td>
							<input type="text" id="disk_amt_supp2" name="disk_amt_supp2" value="<?php echo $d->disk_amt_supp2; ?>" style="width:50px;" class="required" readonly="TRUE" />
						</td>
					</tr>
					<tr>
						<td>Disk Amt 3</td>
						<td>
							<input type="text" id="disk_amt_supp3" name="disk_amt_supp3" value="<?php echo $d->disk_amt_supp3; ?>" style="width:50px;" class="required" readonly="TRUE" />
						</td>
					</tr>
					<tr>
						<td>Disk Amt 4</td>
						<td>
							<input type="text" id="disk_amt_supp4" name="disk_amt_supp4" value="<?php echo $d->disk_amt_supp4; ?>" style="width:50px;" class="required" readonly="TRUE" />
						</td>
					</tr>
					<tr>
						<td>Harga Supplier</td>
						<td>
							<input type="text" id="hrg_supplier" name="hrg_supplier" value="<?php echo $d->hrg_supplier; ?>" style="width:50px;" class="required" readonly="TRUE" />
						</td>
					</tr>
					<tr>
						<td>DPP</td>
						<td>
							<input type="text" id="dpp" name="dpp" value="<?php echo $d->dpp; ?>" style="width:50px;" class="required" readonly="TRUE" />
						</td>
					</tr>
				</tbody>
			</table>
			<?php
			}
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
			$data = array(
				'id'         			=> $this->input->post('kode'),
				'qty'        			=> $this->input->post('qty'),
				'price'      			=> 100,
				'name'       			=> '-',
				'thn_reg'    			=> $this->input->post('thn'),
				'satuan'     			=> $this->input->post('satuan'),
				'namap'    	 			=> $this->input->post('nama'),
				'kode_lokasi'    	 	=> $this->input->post('kode_lokasi'),
				'nama_lokasi'    	 	=> $this->input->post('nama_lokasi'),
				'disk_persen_supp1'		=> $this->input->post('disk_persen_supp1'),
				'disk_persen_supp2'		=> $this->input->post('disk_persen_supp2'),
				'disk_persen_supp3'		=> $this->input->post('disk_persen_supp3'),
				'disk_persen_supp4'		=> $this->input->post('disk_persen_supp4'),
				'disk_amt_supp1'		=> $this->input->post('disk_amt_supp1'),
				'disk_amt_supp2'		=> $this->input->post('disk_amt_supp2'),
				'disk_amt_supp3'		=> $this->input->post('disk_amt_supp3'),
				'disk_amt_supp4'		=> $this->input->post('disk_amt_supp4'),
				'hrg_supplier'			=> $this->input->post('hrg_supplier'),
				'dpp'					=> $this->input->post('dpp'),
				'options'    => array('status' => '0')
			);

			
			$this->cart->insert($data);
			

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
	
	public function delcart()
	{
			$kode = explode("/",$_GET['kode']);
			if($this->session->userdata("limit_add_cart_retur")=="")
			{
				$data = array(
				'rowid' => $kode[0],
				'qty'   => 0);
				$this->cart->update($data);
			}
			else if($this->session->userdata("limit_add_cart_retur")=="edit")
			{
				$data = array(
				'rowid' => $kode[0],
				'qty'   => 0);
				$this->cart->update($data);
				$hps['no_retur'] = $kode[1];
				$hps['kd_produk'] = $kode[2];
				$this->app_model->deleteData("mst.tt_dtl_retur_order",$hps);
			}
		redirect(base_url() . "retur_order/form", "location");

	}

	public function addsession()
	{
			$data["kd_supplier"] = $this->input->post("kd_supplier");
			$sess_data['kd_supplier_retur'] = $data["kd_supplier"];
			$this->session->set_userdata($sess_data);
	}
	
	public function saveretur()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			if($this->session->userdata("limit_add_cart_retur")=="")
			{
				$d_header['no_retur'] = $this->app_model->getMaxNoRetur();
				$temp = $d_header['no_retur'];
				$d_header['tgl_retur'] = date('Y-m-d H:i:s');
				$d_header['created_date'] = date('Y-m-d H:i:s');
				$d_header['created_by'] = $this->session->userdata("username");
				$d_header['kd_supplier'] = $this->session->userdata("kd_supplier_retur");
				$d_header['no_ro'] = $this->session->userdata("no_ro_retur");
				$d_header['status'] = '1';
				$this->app_model->insertData("mst.tt_retur_order",$d_header);
				foreach($this->cart->contents() as $items)
				{
					$d_detail['no_retur'] = $temp;
					$d_detail['kd_produk'] = $items['id'];
					$d_detail['kd_kategori1'] = substr($items['id'],0,2);
					$d_detail['kd_kategori2'] = substr($items['id'],2,2);
					$d_detail['kd_kategori3'] = substr($items['id'],4,2);
					$d_detail['kd_kategori4'] = substr($items['id'],6,2);
					$d_detail['thn_reg'] = substr($items['id'],8,2);
					$d_detail['no_urut'] = substr($items['id'],10,3);
					$d_detail['kd_lokasi'] = substr($items['kode_lokasi'],0,2);
					$d_detail['kd_blok'] = substr($items['kode_lokasi'],2,2);
					$d_detail['kd_sub_blok'] = substr($items['kode_lokasi'],4,2);
					$d_detail['qty_retur'] = $this->fungsi->nvl($items['qty'],0);
					$d_detail['kd_supplier'] = $d_header['kd_supplier'];
					$d_detail['disk_persen_supp1'] = $this->fungsi->nvl($items['disk_persen_supp1'],0);
					$d_detail['disk_persen_supp2'] = $this->fungsi->nvl($items['disk_persen_supp2'],0);
					$d_detail['disk_persen_supp3'] = $this->fungsi->nvl($items['disk_persen_supp3'],0);
					$d_detail['disk_persen_supp4'] = $this->fungsi->nvl($items['disk_persen_supp4'],0);
					$d_detail['disk_amt_supp1'] = $this->fungsi->nvl($items['disk_amt_supp1'],0);
					$d_detail['disk_amt_supp2'] = $this->fungsi->nvl($items['disk_amt_supp2'],0);
					$d_detail['disk_amt_supp3'] = $this->fungsi->nvl($items['disk_amt_supp3'],0);
					$d_detail['disk_amt_supp4'] = $this->fungsi->nvl($items['disk_amt_supp4'],0);
					$d_detail['hrg_supplier'] = $this->fungsi->nvl($items['hrg_supplier'],0);
					$d_detail['dpp'] = $this->fungsi->nvl($items['dpp'],0);
					$this->app_model->insertData("mst.tt_dtl_retur_order",$d_detail);
					
					$dt['dt_supp'] = $this->app_model->manualquery("
								select pkp 
								from mst.tm_supplier where kd_supplier = '".$d_header['kd_supplier']."'
								");
					foreach($dt['dt_supp']->result() as $dsup)
					{
						$keysupp['pkp'] = $dsup->pkp;
					}
					
					$total = $d_detail['hrg_supplier'] * $d_detail['qty_retur'];
					$jumlah = $d_detail['qty_retur'];
					
					if ($keysupp['pkp'] == '0') {
						$ppn = 0;
						$grand_total = $total;
					} else {
						$ppn = 10;
						$grand_total = ($total + (0.1*$total));
					}
						
					$this->app_model->manualquery("
						update mst.tt_retur_order
						set ppn = '".$ppn."',
							jumlah = jumlah + '".$jumlah."',
							grand_total = grand_total + '".$grand_total."'
						where no_retur = '".$temp."'
							and no_ro = '".$d_header['no_ro']."'
					");
					
					$data['qty_out'] = $items['qty']+$this->app_model->qty_out_tm_produk($items['id']);
					$data['qty_oh'] = $this->app_model->qty_oh_tm_produk($items['id']) - $items['qty'];
					$key['kd_produk']=$items['id'];
					
					$bulan=date('m');
					$tahun=date('Y');					

					$jml['jml'] = $this->app_model->cari_td_lokasi_per_brg($items['id'],substr($items['kode_lokasi'],0,2),substr($items['kode_lokasi'],2,2),substr($items['kode_lokasi'],4,2));
					
					$jml['jml_brg_tahun'] = $this->app_model->cari_td_brg_per_bln_thn($items['id'], $bulan, $tahun);
					
					if ($jml['jml'] == '0')
					{
						$this->app_model->manualquery("
							insert into mst.td_lokasi_per_brg(
								kd_lokasi, kd_blok, kd_sub_blok, kd_produk, kd_kategori1, kd_kategori2, kd_kategori3, kd_kategori4, thn_reg, no_urut, 
								qty_out, qty_oh, created_by, created_date
							) VALUES (
								'".substr($items['kode_lokasi'],0,2)."', '".substr($items['kode_lokasi'],2,2)."', '".substr($items['kode_lokasi'],4,2)."', '".$items['id']."', '".substr($items['id'],0,2)."', '".substr($items['id'],2,2)."', '".substr($items['id'],4,2)."', '".substr($items['id'],6,2)."', '".substr($items['id'],8,2)."', '".substr($items['id'],10,3)."',
								'".$items['qty']."', '".$items['qty']."', '".$d_header['created_by']."', '".$d_header['created_date']."'
							)
						");
					} else {
						$data_out['qty_out'] = $items['qty']+$this->app_model->qty_out_td_lokasi_per_brg($items['id'],substr($items['kode_lokasi'],0,2),substr($items['kode_lokasi'],2,2),substr($items['kode_lokasi'],4,2));

						$data_out['qty_oh'] = $this->app_model->qty_oh_td_lokasi_per_brg($items['id'],substr($items['kode_lokasi'],0,2),substr($items['kode_lokasi'],2,2),substr($items['kode_lokasi'],4,2)) - $items['qty'];
						
						$this->app_model->manualquery("
							UPDATE mst.td_lokasi_per_brg
								SET qty_out='".$data_out['qty_out']."', 
									qty_oh=".$data_out['qty_oh'].", 
									updated_by='".$d_header['created_by']."', 
									updated_date='".$d_header['created_date']."'
								WHERE kd_lokasi='".substr($items['kode_lokasi'],0,2)."' 
									and kd_blok='".substr($items['kode_lokasi'],2,2)."' 
									and kd_sub_blok='".substr($items['kode_lokasi'],4,2)."' 
									and kd_produk='".$items['id']."'
							");				
					}

					if ($jml['jml_brg_tahun'] == '0')
					{
						$this->app_model->manualquery("
							INSERT into mst.td_brg_per_bln_thn(
								bulan, tahun, kd_produk, kd_kategori1, kd_kategori2, kd_kategori3, kd_kategori4, thn_reg, no_urut,
								qty_out, qty_oh, created_by,created_date
							) VALUES (
								'".$bulan."', '".$tahun."', '".$items['id']."', '".substr($items['id'],0,2)."', '".substr($items['id'],2,2)."', '".substr($items['id'],4,2)."', '".substr($items['id'],6,2)."', '".substr($items['id'],8,2)."', '".substr($items['id'],10,3)."',
								'".$items['qty']."', '".$items['qty']."', '".$d_header['created_by']."', '".$d_header['created_date']."'
							)
						");
					}
					else
					{
						$data_out['qty_out_thn_bln'] = $items['qty']+$this->app_model->qty_out_td_brg_per_bln_thn($items['id'],$bulan,$tahun);

						$data_out['qty_oh_thn_bln'] = $this->app_model->qty_oh_td_brg_per_bln_thn($items['id'],$bulan,$tahun) - $items['qty'];
					
						$this->app_model->manualquery("
						UPDATE mst.td_brg_per_bln_thn
							SET qty_out='".$data_out['qty_out_thn_bln']."', 
								qty_oh=".$data_out['qty_oh_thn_bln'].", 
								updated_by='".$d_header['created_by']."', 
								updated_date='".$d_header['created_date']."'
							WHERE kd_produk='".$items['id']."' 
								and bulan='".$bulan."' 
								and tahun='".$tahun."'
						");				
					}
					
					$this->app_model->updateData("mst.tm_produk",$data,$key);
					
				}
				$this->session->unset_userdata('kd_supplier_retur');
				$this->session->unset_userdata('nama_supplier_retur');
				$this->session->unset_userdata('no_ro_retur');
				$this->cart->destroy();
				header('location:'.base_url().'retur_order');

			}else if($this->session->userdata("limit_add_cart_retur")=="edit"){

				$id['no_retur'] = $this->input->post('no_retur');
				$temp = $id['no_retur'];
				//$d_header['tgl_retur'] = $this->session->userdata("created_date");
				$d_header['updated_date'] = date('Y-m-d H:i:s');
				$d_header['updated_by'] = $this->session->userdata("username");
				$d_header['kd_supplier'] = $this->session->userdata("kd_supplier_retur");
				$d_header['status'] = '0';
				
				$this->app_model->updateData("mst.tt_retur_order",$d_header,$id);

				$this->app_model->deleteData("mst.tt_dtl_retur_order",$id);
				foreach($this->cart->contents() as $items)
				{
					$d_detail['no_retur'] = $temp;
					$d_detail['kd_produk'] = $items['id'];
					$d_detail['kd_kategori1'] = substr($items['id'],0,2);
					$d_detail['kd_kategori2'] = substr($items['id'],2,2);
					$d_detail['kd_kategori3'] = substr($items['id'],4,2);
					$d_detail['kd_kategori4'] = substr($items['id'],6,2);
					$d_detail['thn_reg'] = substr($items['id'],8,2);
					$d_detail['no_urut'] = substr($items['id'],10,3);
					$d_detail['kd_lokasi'] = substr($items['kode_lokasi'],0,2);
					$d_detail['kd_blok'] = substr($items['kode_lokasi'],2,2);
					$d_detail['kd_sub_blok'] = substr($items['kode_lokasi'],4,2);
					$d_detail['qty_retur'] = $this->fungsi->nvl($items['qty'],0);
					$d_detail['kd_supplier'] = $d_header['kd_supplier'];
					$d_detail['disk_persen_supp1'] = $this->fungsi->nvl($items['disk_persen_supp1'],0);
					$d_detail['disk_persen_supp2'] = $this->fungsi->nvl($items['disk_persen_supp2'],0);
					$d_detail['disk_persen_supp3'] = $this->fungsi->nvl($items['disk_persen_supp3'],0);
					$d_detail['disk_persen_supp4'] = $this->fungsi->nvl($items['disk_persen_supp4'],0);
					$d_detail['disk_amt_supp1'] = $this->fungsi->nvl($items['disk_amt_supp1'],0);
					$d_detail['disk_amt_supp2'] = $this->fungsi->nvl($items['disk_amt_supp2'],0);
					$d_detail['disk_amt_supp3'] = $this->fungsi->nvl($items['disk_amt_supp3'],0);
					$d_detail['disk_amt_supp4'] = $this->fungsi->nvl($items['disk_amt_supp4'],0);
					$d_detail['hrg_supplier'] = $this->fungsi->nvl($items['hrg_supplier'],0);
					$d_detail['dpp'] = $this->fungsi->nvl($items['dpp'],0);
					$this->app_model->insertData("mst.tt_dtl_purchase_request",$d_detail);
				}
				
				$this->session->unset_userdata('kd_supplier_retur');
				$this->session->unset_userdata('nama_supplier_retur');
				$this->session->unset_userdata('no_ro_retur');
				$this->session->unset_userdata('limit_add_cart_retur');
				$this->cart->destroy();
				header('location:'.base_url().'retur_order');
			}
		}
		else
		{
            redirect(base_url());
		}
	}
	

	public function hapusretur()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			$hapus['id_retur'] = $this->uri->segment(3);
			//kembalikan kuantitas barang
			$q = $this->app_model->getSelectedData("mst.tt_retur_order",$hapus);
			foreach($q->result() as $d)
			{
				//$data['stok'] = $d->qty+$this->app_model->getSisaStok($d->kode_barang);
				$key['no_retur'] = $d->no_retur;
				//$this->app_model->updateData("tbl_barang",$data,$key);
			}
			$this->app_model->deleteData("mst.tt_dtl_retur_order",$key);
			$this->app_model->deleteData("mst.tt_retur_order",$key);
			?>
				<script> window.location = "<?php echo base_url(); ?>retur_order"; </script>
			<?php
		}
		else
		{
            redirect(base_url());
		}
	}

	
}

?>