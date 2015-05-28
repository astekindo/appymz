<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$timezone = "Asia/Jakarta";
if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
//echo date('d-m-Y H:i:s');

$localtime=date('H:i:s');
$localdate=date('Y-m-d');
$today=date('Y-m-d H:i:s');


class Receiveorder extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('receiveorder_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Home - Master - Delivery Order'
        );

        if ($this->session->userdata('username')) {
			$this->cart->destroy();
			$this->session->unset_userdata('no_po');
			$this->session->unset_userdata('no_pr');
			$this->session->unset_userdata('kd_supplier');
			$this->session->unset_userdata('nama_supplier');
			$this->session->unset_userdata('limit_add_cart');
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
            $res_ro = $this->receiveorder_models->receiveorder_content();
            $data['rcreceiveorder']=$res_ro;
            $this->load->view('ro/vwreceiveorder', $data);
        } else {
             redirect(base_url());
        }
    }

    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {

				$id['no_po'] = $this->uri->segment(3);
				$bc['dt_po_header'] = $this->app_model->manualquery("select a.no_po,a.no_pr,a.kd_supplier,b.nama_supplier from mst.tt_purchase_order a, mst.tm_supplier b where a.kd_supplier=b.kd_supplier and a.no_po='".$id['no_po']."' and a.approval='1'");
				foreach($bc['dt_po_header']->result() as $dph)
				{
					$sess_data1['no_po'] = $dph->no_po;
					$sess_data2['no_pr'] = $dph->no_pr;
					$sess_data3['kd_supplier'] = $dph->kd_supplier;
					$sess_data4['nama_supplier'] = $dph->nama_supplier;
					$key['no_po'] = $dph->no_po;
					$this->session->set_userdata($sess_data1);
					$this->session->set_userdata($sess_data2);
					$this->session->set_userdata($sess_data3);
					$this->session->set_userdata($sess_data4);
				}

				$dtpo['dt_po'] = $this->app_model->manualQuery("select a.no_po, a.kd_produk, a.qty_beli, a.thn_reg, c.nm_satuan, b.nama_produk from mst.tt_dtl_purchase_order a left join mst.tm_produk b on a.kd_produk=b.kd_produk 
				left join mst.tm_satuan c on b.id_satuan=c.id_satuan where a.no_po='".$key['no_po']."'");

					$in_cart = array();
					foreach($dtpo['dt_po']->result() as $dpd)
					{	
						$thnreg = str_pad($dpd->thn_reg,4,"20",STR_PAD_LEFT);
						$in_cart[] = array(
						'id'          => $dpd->kd_produk,
						'qty'         => $dpd->qty_beli,
						'price'       => 1,
						'name'        => '-',
						'namap'        => $dpd->nama_produk,
						'thn_reg'     => $thnreg,
						'satuan'      => $dpd->nm_satuan,
						'keterangan' => '-',
						'options'     => array('statusapp' => '0'));
					}
					$this->cart->insert($in_cart);				
				
				
				$data['listlokasi']= $this->app_model->manualquery("select c.kd_lokasi||b.kd_blok||a.kd_sub_blok kode,c.nama_lokasi||' '||b.nama_blok||'
																	'||a.nama_sub_blok nama_lokasi from mst.tm_sub_blok a, mst.tm_blok b, mst.tm_lokasi c where a.kd_lokasi=c.kd_lokasi and b.kd_lokasi=c.kd_lokasi and a.kd_blok = b.kd_blok and a.aktif is true order by nama_lokasi asc");
                $data['id_ro'] = '';
                $data['no_ro'] = $this->app_model->getMaxNoRO();
                $data['tanggal'] = date('d-M-Y');
                $data['tt_po'] = $this->app_model->manualquery("select no_po from mst.tt_purchase_order where approval='1'");
                $data['no_po'] = $this->session->userdata("no_po");
                $data['no_pr'] = $this->session->userdata("no_pr");
                $data['kd_supplier'] = $this->session->userdata("kd_supplier");
                $data['nama_supplier'] = $this->session->userdata("nama_supplier");

            } else {
                $data['id_ro'] = '';
                $data['no_ro'] = $this->app_model->getMaxNoRO();
                $data['tanggal'] = date('d-M-Y');
                $data['tt_po'] = $this->app_model->manualquery("select no_po from mst.tt_purchase_order where approval='1'");
                $data['no_po'] = '';
                $data['no_pr'] = '';
                $data['kd_supplier'] = '';
                $data['nama_supplier'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata("username");;
            $data['title'] = $judul;
            $data['location'] = 'Home - Master - Purchase Request';

		}

        if ($this->session->userdata('username')) {
            $this->load->view('ro/receiveorder', $data);
        } else {
             redirect(base_url());
        }
    }

	public function addsession()
	{
			$data["subject"] = $this->input->post("subject");
			$sess_data['subject'] = $data["subject"];
			$this->session->set_userdata($sess_data);
	}
	
	public function savero()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			if($this->session->userdata("limit_add_cart")=="")
			{
				$d_header['no_ro'] = $this->app_model->getMaxNoRO();
				$temp = $d_header['no_ro'];
				$d_header['no_pr'] = $this->input->post('no_pr');
				$d_header['no_po'] = $this->input->post('no_po');
				$d_header['kd_supplier'] = $this->input->post('kd_supplier');
				$d_header['created_date'] = date('Y-m-d H:i:s');
				$d_header['created_date2'] = date('Y-m-d H:i:s');
				$d_header['created_by'] = $this->session->userdata("username");
				$d_header['created_by2'] = $this->session->userdata("username");
				$d_header['status'] = '1';
				$this->app_model->insertData("mst.tt_receive_order",$d_header);

				
				$totalbrs = $this->input->post('totbaris');
				$kd_produk = $this->input->post('kd_produk');
				$kodelokasi = $this->input->post('kodelokasi');
				$qty_beli = $this->input->post('qty_beli');
				$qty_terima = $this->input->post('qty_terima');
				$keterangan=$this->input->post('keterangan');
				
				for($i=0;$i < $totalbrs;$i++)
				{
					// if ($status[$i]=="on")
						// {$status[$i]="A";}
					// else
						// {$status[$i]="N";}

					$this->app_model->manualquery("insert into mst.tt_dtl_receive_order(no_ro,kd_produk,kd_kategori1,kd_kategori2,kd_kategori3,kd_kategori4,thn_reg,no_urut,qty_beli,qty_terima,kd_lokasi,kd_blok,kd_sub_blok,keterangan,status) VALUES ('".$temp."','".$kd_produk[$i]."','".substr($kd_produk[$i],0,2)."','".substr($kd_produk[$i],2,2)."','".substr($kd_produk[$i],4,2)."', '".substr($kd_produk[$i],6,2)."','".substr($kd_produk[$i],8,2)."','".substr($kd_produk[$i],10,3)."','".$qty_beli[$i]."','".$qty_terima[$i]."','".substr($kodelokasi[$i],0,2)."','".substr($kodelokasi[$i],2,2)."','".substr($kodelokasi[$i],4,2)."','".$keterangan[$i]."','TRUE')");
					
					$data['qty_in'] = $qty_terima[$i]+$this->app_model->qty_in_tm_produk($kd_produk[$i]);
					$data['qty_oh'] = $qty_terima[$i]+$this->app_model->qty_oh_tm_produk($kd_produk[$i]);
					$key['kd_produk']=$kd_produk[$i];
					
					$bulan=date('m');
					$tahun=date('Y');					

					$jml['jml'] = $this->app_model->cari_td_lokasi_per_brg($kd_produk[$i],substr($kodelokasi[$i],0,2),substr($kodelokasi[$i],2,2),substr($kodelokasi[$i],4,2));
					
					$jml['jml_brg_tahun'] = $this->app_model->cari_td_brg_per_bln_thn($kd_produk[$i],$bulan,$tahun);
					
					if ($jml['jml'] == '0')
					{
					$this->app_model->manualquery("insert into mst.td_lokasi_per_brg(kd_lokasi,kd_blok,kd_sub_blok,kd_produk,kd_kategori1,kd_kategori2,kd_kategori3,kd_kategori4,thn_reg,no_urut,qty_in,qty_oh,created_by,created_date) VALUES ('".substr($kodelokasi[$i],0,2)."','".substr($kodelokasi[$i],2,2)."','".substr($kodelokasi[$i],4,2)."','".$kd_produk[$i]."','".substr($kd_produk[$i],0,2)."','".substr($kd_produk[$i],2,2)."','".substr($kd_produk[$i],4,2)."', '".substr($kd_produk[$i],6,2)."','".substr($kd_produk[$i],8,2)."','".substr($kd_produk[$i],10,3)."','".$qty_terima[$i]."','".$qty_terima[$i]."','".$d_header['created_by']."','".$d_header['created_date']."')");
					}
					else
					{
					$data_in['qty_in'] = $qty_terima[$i]+$this->app_model->qty_in_td_lokasi_per_brg($kd_produk[$i],substr($kodelokasi[$i],0,2),substr($kodelokasi[$i],2,2),substr($kodelokasi[$i],4,2));

					$data_in['qty_oh'] = $qty_terima[$i]+$this->app_model->qty_oh_td_lokasi_per_brg($kd_produk[$i],substr($kodelokasi[$i],0,2),substr($kodelokasi[$i],2,2),substr($kodelokasi[$i],4,2));
					
					$this->app_model->manualquery("UPDATE mst.td_lokasi_per_brg
												   SET qty_in='".$data_in['qty_in']."', qty_oh=".$data_in['qty_oh'].", updated_by='".$d_header['created_by']."', updated_date='".$d_header['created_date']."'
												   WHERE kd_lokasi='".substr($kodelokasi[$i],0,2)."' and kd_blok='".substr($kodelokasi[$i],2,2)."' and kd_sub_blok='".substr($kodelokasi[$i],4,2)."' and kd_produk='".$kd_produk[$i]."'");				
					}

					if ($jml['jml_brg_tahun'] == '0')
					{
					$this->app_model->manualquery("insert into mst.td_brg_per_bln_thn(bulan,tahun,kd_produk,kd_kategori1,kd_kategori2,kd_kategori3,kd_kategori4,thn_reg,no_urut,qty_in,qty_oh,created_by,created_date) VALUES ('".$bulan."','".$tahun."','".$kd_produk[$i]."','".substr($kd_produk[$i],0,2)."','".substr($kd_produk[$i],2,2)."','".substr($kd_produk[$i],4,2)."', '".substr($kd_produk[$i],6,2)."','".substr($kd_produk[$i],8,2)."','".substr($kd_produk[$i],10,3)."','".$qty_terima[$i]."','".$qty_terima[$i]."','".$d_header['created_by']."','".$d_header['created_date']."')");
					}
					else
					{
					$data_in['qty_in_thn_bln'] = $qty_terima[$i]+$this->app_model->qty_in_td_brg_per_bln_thn($kd_produk[$i],$bulan,$tahun);

					$data_in['qty_oh_thn_bln'] = $qty_terima[$i]+$this->app_model->qty_oh_td_brg_per_bln_thn($kd_produk[$i],$bulan,$tahun);
					
					$this->app_model->manualquery("UPDATE mst.td_brg_per_bln_thn
												   SET qty_in='".$data_in['qty_in_thn_bln']."', qty_oh=".$data_in['qty_oh_thn_bln'].", updated_by='".$d_header['created_by']."', updated_date='".$d_header['created_date']."'
												   WHERE kd_produk='".$kd_produk[$i]."' and bulan='".$bulan."' and tahun='".$tahun."'");				
					}
					
					$this->app_model->updateData("mst.tm_produk",$data,$key);
					
				}
					$statuspo['approval']='2';
					$keypo['no_po']=$this->input->post('no_po');
					$this->app_model->updateData("mst.tt_purchase_order",$statuspo,$keypo);
				
				$this->cart->destroy();
				$this->session->unset_userdata('no_po');
				$this->session->unset_userdata('no_pr');
				$this->session->unset_userdata('kd_supplier');
				$this->session->unset_userdata('nama_supplier');
				header('location:'.base_url().'receiveorder');

			}else if($this->session->userdata("limit_add_cart")=="edit"){

				$id['no_pr'] = $this->input->post('no_pr');
				$temp = $id['no_pr'];
				$d_header['subject'] = $this->session->userdata("subject");
				$d_header['created_date'] = $this->session->userdata("created_date");
				$d_header['created_date2'] = $this->session->userdata("created_date");
				$d_header['created_by'] = $this->session->userdata("username");
				$d_header['created_by2'] = $this->session->userdata("username");
				$d_header['status'] = '0';
				
				$this->app_model->updateData("mst.tt_purchase_request",$d_header,$id);

				$this->app_model->deleteData("mst.tt_dtl_purchase_request",$id);
				foreach($this->cart->contents() as $items)
				{
					$d_detail['no_pr'] = $temp;
					$d_detail['kd_produk'] = $items['id'];
					$d_detail['kd_kategori1'] = substr($items['id'],0,2);
					$d_detail['kd_kategori2'] = substr($items['id'],2,2);
					$d_detail['kd_kategori3'] = substr($items['id'],4,2);
					$d_detail['kd_kategori4'] = substr($items['id'],6,2);
					$d_detail['thn_reg'] = substr($items['id'],8,2);
					$d_detail['no_urut'] = substr($items['id'],10,3);
					$d_detail['qty'] = $items['qty'];
					$this->app_model->insertData("mst.tt_dtl_purchase_request",$d_detail);
				}

				$this->session->unset_userdata('subject');
				$this->session->unset_userdata('no_pr');
				$this->session->unset_userdata('created_date');
				$this->session->unset_userdata('limit_add_cart');
				$this->cart->destroy();
				header('location:'.base_url().'receiveorder');
			}
		}
		else
		{
            redirect(base_url());
		}
	}
	

	function get_blok()
	{
		if($_POST)
		{
			$result = $this->app_model->manualquery("select * from mst.tm_blok where kd_lokasi = '".$this->input->post('kd_lokasi')."'");
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

	function get_subblok()
	{
		if($_POST)
		{
			$result = $this->app_model->manualquery("select * from mst.tm_subblok where kd_blok = '".$this->input->post('kd_blok')."'");
			if(is_array($result))
			{
				// jika hasil query array maka looping hasil query
					echo '<option value="">- Pilih Blok -</option>';
				foreach ($result as $row)
				{
					echo '<option value="'.$row->kd_subblok.'">'.$row->nama_sub_blok.'</option>';
				}
			}
			else
			{
				// tampilkan jika data hasil query kosong
				echo '<option value="">Tidak ada data</option>';
			}
		}
	}
	
}

?>