<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
	
$timezone = "Asia/Jakarta";
if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
//echo date('d-m-Y H:i:s');

$localtime=date('H:i:s');
$localdate=date('Y-m-d');
$today=date('Y-m-d H:i:s');

class Supp_prod extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('supp_prod_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Home - Master - Supplier per Barang'
        );

        if ($this->session->userdata('username')) {
			$this->cart->destroy();
			$this->session->unset_userdata('kd_supplier');
			$this->session->unset_userdata('limit_add_cart');
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
            $res_supp_prod = $this->supp_prod_models->supp_prod_content();
            $data['rcsuppprod']=$res_supp_prod;
            $this->load->view('page/vw_listsuppprod', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $id['kd_supplier'] = $this->uri->segment(3);
				$bc['dt_supp_header'] = $this->app_model->getSelectedData("mst.tm_supplier",$id);
				foreach($bc['dt_supp_header']->result() as $dph)
				{
					$sess_data1['kd_supplier'] = $dph->kd_supplier;
					$this->session->set_userdata($sess_data1);
				}

				$bc['dt_supp_detail'] = $this->app_model->manualQuery("select a.kd_supplier, a.kd_produk, a.konsinyasi, a.disk_persen_supp1, a.disk_persen_supp2,
																	a.disk_persen_supp3, a.disk_persen_supp4, a.disk_amt_supp1, a.disk_amt_supp2, a.disk_amt_supp3,
																	a.disk_amt_supp4, a.hrg_supplier, a.dpp, a.waktu_top, a.konsinyasi, b.nama_produk 
																	from mst.td_supp_per_brg a left join mst.tm_produk b 
																	on a.kd_produk=b.kd_produk where a.kd_supplier='".$sess_data1['kd_supplier']."'");
				if($this->session->userdata("limit_add_cart")=="")
				{
					$in_cart = array();
					foreach($bc['dt_supp_detail']->result() as $dpd)
					{	
						$in_cart[] = array(
						'id'         		=> $dpd->kd_produk,
						'qty'        		=> 1,
						'price'      		=> 1,
						'name'       		=> $dpd->nama_produk,
						'disk_persen_supp1' => $dpd->disk_persen_supp1,
						'disk_persen_supp2' => $dpd->disk_persen_supp2,
						'disk_persen_supp3' => $dpd->disk_persen_supp3,
						'disk_persen_supp4' => $dpd->disk_persen_supp4,
						'disk_amt_supp1' 	=> $dpd->disk_amt_supp1,
						'disk_amt_supp2' 	=> $dpd->disk_amt_supp2,
						'disk_amt_supp3' 	=> $dpd->disk_amt_supp3,
						'disk_amt_supp4' 	=> $dpd->disk_amt_supp4,
						'hrg_supplier' 		=> $dpd->hrg_supplier,
						'dpp' 				=> $dpd->dpp,
						'waktu_top' 				=> $dpd->waktu_top,
						'konsinyasi' 				=> $dpd->konsinyasi);
					}
					$this->cart->insert($in_cart);
					$sess_data['limit_add_cart'] = "edit";
					$this->session->set_userdata($sess_data);
				}

                
                $data['kd_supplier'] = $this->session->userdata("kd_supplier");
				
            } else {
                $data['kd_supplier'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Home - Master - Supplier per Barang';
			$data['listsupplier'] = $this->fungsi->getAllData("mst.tm_supplier");
        }
        
        if ($this->session->userdata('username')) {
            $this->load->view('form/vw_input_supprod', $data);
        } else {
            redirect(base_url());
        }
    }
	
	public function ambil_data_supplier_ajax()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			$data["kd_supplier"] = $_GET["kd_supplier"];
			$q = $this->fungsi->getSelectedData("mst.tm_supplier",$data);
			foreach($q->result() as $d)
			{
			?>
			<table cellpadding="5" cellspacing="0" border="0">
				<div class="formRow">
					<div class="grid3"><label>Kode Supplier:</label></div>
					<div class="grid9">
						<input type="text" id="kode_supp" name="kode_supp" value="<?php echo $d->kd_supplier; ?>" class="input-read-only" readonly="true" style="width:200px;" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label>Alamat Supplier:</label></div>
					<div class="grid9">
						<input type="text" value="<?php echo $d->alamat; ?>" class="input-read-only" readonly="true" style="width:200px;" />
					</div>
					<div class="clear"></div>
				</div>
			</table>
			<?php
			}
		}
		else
		{
			redirect(base_url());
		}
	}
	
	public function ambil_data_supplier_session()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			if($this->session->userdata("kd_supplier")!=NULL)
			{
				$data["kd_supplier"] = $this->session->userdata("kd_supplier");
				$q = $this->fungsi->getSelectedData("mst.tm_supplier",$data);
				foreach($q->result() as $d)
				{
					$kd_supplier = $d->kd_supplier;
					$alamat = $d->alamat;
				}
			}
			else
			{
				$kd_supplier = "";
				$alamat = "";

			}
			
			?>
			<table cellpadding="5" cellspacing="0" border="0">
				<div class="formRow">
					<div class="grid3"><label>Kode Supplier:</label></div>
					<div class="grid9">
						<input type="text" id="kode_supp" name="kode_supp" value="<?php echo $kd_supplier; ?>" class="input-read-only" readonly="true" style="width:200px;" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label>Alamat Supplier:</label></div>
					<div class="grid9">
						<input type="text" value="<?php echo $alamat; ?>" class="input-read-only" readonly="true" style="width:200px;" />
					</div>
					<div class="clear"></div>
				</div>
			</table>
			<?php
		}
		else
		{
			redirect(base_url());
		}
	}
	
	public function list_produk()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			$bc['jdl'] = "Daftar Produk";
			$bc['tm_produk'] = $this->app_model->getAllData("mst.tm_produk");
			
			$this->load->view('form/vw_input_sp_prod',$bc);
		}
		else
		{
            redirect(base_url());
		}
	}
	
	public function ambil_data_produk() {
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			$data["kd_produk"] = $_GET["kd_produk"];
			$q = $this->app_model->getSelectedData("mst.tm_produk",$data);
			foreach($q->result() as $d)
			{
			?>
			<table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
				<thead>
					<tr></tr>
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
						<td>Disk % 1</td>
						<td>
							<input type="text" id="disk_persen_supp1" name="disk_persen_supp1" style="width:50px;" onKeyPress="return onlyNumbers(event);" class="required number" />
						</td>
					</tr>
					<tr>
						<td>Disk % 2</td>
						<td><input type="text" id="disk_persen_supp2" name="disk_persen_supp2" style="width:50px;" onKeyPress="return onlyNumbers(event);" class="required number" />
						</td>
					</tr>
					<tr>
						<td>Disk % 3</td>
						<td><input type="text" id="disk_persen_supp3" name="disk_persen_supp3" style="width:50px;" onKeyPress="return onlyNumbers(event);" class="required number" />
						</td>
					</tr>
					<tr>
						<td>Disk % 4</td>
						<td><input type="text" id="disk_persen_supp4" name="disk_persen_supp4" style="width:50px;" onKeyPress="return onlyNumbers(event);" class="required number" />
						</td>
					</tr>
					<tr>
						<td>Disk Amt 1</td>
						<td>
							<input type="text" id="disk_amt_supp1" name="disk_amt_supp1" style="width:50px;" onKeyPress="return onlyNumbers(event);" class="required number" />
						</td>
					</tr>
					<tr>
						<td>Disk Amt 2</td>
						<td>
							<input type="text" id="disk_amt_supp2" name="disk_amt_supp2" style="width:50px;" onKeyPress="return onlyNumbers(event);" class="required number" />
						</td>
					</tr>
					<tr>
						<td>Disk Amt 3</td>
						<td>
							<input type="text" id="disk_amt_supp3" name="disk_amt_supp3" style="width:50px;" onKeyPress="return onlyNumbers(event);" class="required number" />
						</td>
					</tr>
					<tr>
						<td>Disk Amt 4</td>
						<td>
							<input type="text" id="disk_amt_supp4" name="disk_amt_supp4" style="width:50px;" onKeyPress="return onlyNumbers(event);" class="required number" />
						</td>
					</tr>
					<tr>
						<td>Waktu Top</td>
						<td>
							<input type="text" id="waktu_top" name="waktu_top" style="width:50px;" onKeyPress="return onlyNumbers(event);" class="required number" />
						</td>
					</tr>
					<tr>
						<td>Konsinyasi</td>
						<td>
							<input type="text" id="konsinyasi" name="konsinyasi" style="width:50px;" onKeyPress="return onlyNumbers(event);" class="required number" />
						</td>
					</tr>
					<tr>
						<td>Harga</td>
						<td>
							<input type="text" id="hrg_supplier" name="hrg_supplier" style="width:50px;" onKeyPress="return onlyNumbers(event);" class="required number" />
						</td>
					</tr>
					<tr>
						<td>DPP</td>
						<td>
							<input type="text" id="dpp" name="dpp" style="width:50px;" onKeyPress="return onlyNumbers(event);" class="required number" />
						</td>
					</tr>
				</tbody>
			</table>
			<?php
			}
		}
		else
		{
            $this->load->view('utama', $data);
		}
	}
	
	public function addcart()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			$data = array(
			'id'         => $this->input->post('kode'),
			'qty'        => 1,
			'price'      => 1,
			'name'       => $this->input->post('nama'),
			'disk_persen_supp1' => $this->input->post('disk_persen_supp1'),
			'disk_persen_supp2' => $this->input->post('disk_persen_supp2'),
			'disk_persen_supp3' => $this->input->post('disk_persen_supp3'),
			'disk_persen_supp4' => $this->input->post('disk_persen_supp4'),
			'disk_amt_supp1' => $this->input->post('disk_amt_supp1'),
			'disk_amt_supp2' => $this->input->post('disk_amt_supp2'),
			'disk_amt_supp3' => $this->input->post('disk_amt_supp3'),
			'disk_amt_supp4' => $this->input->post('disk_amt_supp4'),
			'waktu_top' => $this->input->post('waktu_top'),
			'konsinyasi' => $this->input->post('konsinyasi'),
			'hrg_supplier' => $this->input->post('hrg_supplier'),
			'dpp' => $this->input->post('dpp')
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
			if($this->session->userdata("limit_add_cart")=="")
			{
				$data = array(
				'rowid' => $kode[0]
				);
				$this->cart->update($data);
			}
			else if($this->session->userdata("limit_add_cart")=="edit")
			{
				$data = array(
				'rowid' => $kode[0]
				);
				$this->cart->update($data);
				$hps['kd_supplier'] = $kode[1];
				$hps['kd_produk'] = $kode[2];
				$this->app_model->deleteData("mst.td_supp_per_brg",$hps);
			}
		redirect(base_url() . "purchaserequest/form", "location");

	}

	public function addsession()
	{
			$data["subject"] = $this->input->post("subject");
			$sess_data['subject'] = $data["subject"];
			$this->session->set_userdata($sess_data);
	}
	
	public function savesuppprod()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			if($this->session->userdata("limit_add_cart")=="")
			{
				$d_header['kd_supplier'] = $this->session->userdata("kd_supplier");
				$id['kd_supplier'] = $this->input->post('kode_supp');
				$temp = $id['kd_supplier'];
				/*$d_header['created_date'] = date('Y-m-d H:i:s');
				$d_header['subject'] = $this->session->userdata("subject");
				$d_header['created_by'] = $this->session->userdata("username");
				$d_header['status'] = '1';
				$this->app_model->insertData("mst.tt_purchase_request",$d_header);*/
				foreach($this->cart->contents() as $items)
				{
					$d_detail['kd_supplier'] = $temp;
					$d_detail['kd_produk'] = $items['id'];
					$d_detail['kd_kategori1'] = substr($items['id'],0,2);
					$d_detail['kd_kategori2'] = substr($items['id'],2,2);
					$d_detail['kd_kategori3'] = substr($items['id'],4,2);
					$d_detail['kd_kategori4'] = substr($items['id'],6,2);
					$d_detail['thn_reg'] = substr($items['id'],8,2);
					$d_detail['no_urut'] = substr($items['id'],10,3);
					$d_detail['disk_persen_supp1'] = $items['disk_persen_supp1'];
					$d_detail['disk_persen_supp2'] = $items['disk_persen_supp2'];
					$d_detail['disk_persen_supp3'] = $items['disk_persen_supp3'];
					$d_detail['disk_persen_supp4'] = $items['disk_persen_supp4'];
					$d_detail['disk_amt_supp1'] = $items['disk_amt_supp1'];
					$d_detail['disk_amt_supp2'] = $items['disk_amt_supp2'];
					$d_detail['disk_amt_supp3'] = $items['disk_amt_supp3'];
					$d_detail['disk_amt_supp4'] = $items['disk_amt_supp4'];
					$d_detail['waktu_top'] = $items['waktu_top'];
					$d_detail['konsinyasi'] = $items['konsinyasi'];
					$d_detail['hrg_supplier'] = $items['hrg_supplier'];
					$d_detail['dpp'] = $items['dpp'];
					$this->app_model->insertData("mst.td_supp_per_brg",$d_detail);
				}
				$this->session->unset_userdata('kd_supplier');
				$this->cart->destroy();
				header('location:'.base_url().'supp_prod');

			}
			else if($this->session->userdata("limit_add_cart")=="edit") 
			{

				$id['kd_supplier'] = $this->input->post('kd_supplier');
				/*$temp = $id['kd_supplier'];
				$d_header['subject'] = $this->session->userdata("subject");
				$d_header['created_date'] = $this->session->userdata("created_date");
				$d_header['created_date2'] = $this->session->userdata("created_date");
				$d_header['created_by'] = $this->session->userdata("username");
				$d_header['created_by2'] = $this->session->userdata("username");
				$d_header['status'] = '0';
				
				$this->app_model->updateData("mst.tt_purchase_request",$d_header,$id);*/

				$this->app_model->deleteData("mst.td_supp_per_brg",$id);
				foreach($this->cart->contents() as $items)
				{
					$d_detail['kd_supplier'] = $temp;
					$d_detail['kd_produk'] = $items['id'];
					$d_detail['kd_kategori1'] = substr($items['id'],0,2);
					$d_detail['kd_kategori2'] = substr($items['id'],2,2);
					$d_detail['kd_kategori3'] = substr($items['id'],4,2);
					$d_detail['kd_kategori4'] = substr($items['id'],6,2);
					$d_detail['thn_reg'] = substr($items['id'],8,2);
					$d_detail['no_urut'] = substr($items['id'],10,3);
					$d_detail['disk_persen_supp1'] = $items['disk_persen_supp1'];
					$d_detail['disk_persen_supp2'] = $items['disk_persen_supp2'];
					$d_detail['disk_persen_supp3'] = $items['disk_persen_supp3'];
					$d_detail['disk_persen_supp4'] = $items['disk_persen_supp4'];
					$d_detail['disk_amt_supp1'] = $items['disk_amt_supp1'];
					$d_detail['disk_amt_supp2'] = $items['disk_amt_supp2'];
					$d_detail['disk_amt_supp3'] = $items['disk_amt_supp3'];
					$d_detail['disk_amt_supp4'] = $items['disk_amt_supp4'];
					$d_detail['waktu_top'] = $items['waktu_top'];
					$d_detail['konsinyasi'] = $items['konsinyasi'];
					$d_detail['hrg_supplier'] = $items['hrg_supplier'];
					$d_detail['dpp'] = $items['dpp'];
					$this->app_model->insertData("mst.td_supp_per_brg",$d_detail);
				}

				$this->session->unset_userdata('kd_supplier');
				$this->session->unset_userdata('limit_add_cart');
				$this->cart->destroy();
				header('location:'.base_url().'supp_prod');
			}
		}
		else
		{
            redirect(base_url());
		}
	}
	
	public function hapussuppprod()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			$hapus['kd_supplier'] = $this->uri->segment(3);
			//kembalikan kuantitas barang
			$q = $this->app_model->getSelectedData("mst.td_supp_per_brg",$hapus);
			foreach($q->result() as $d)
			{
				$key['kd_supplier'] = $d->kd_supplier;
			}
			$this->app_model->deleteData("mst.td_supp_per_brg",$key);
			?>
				<script> window.location = "<?php echo base_url(); ?>supp_prod"; </script>
			<?php
		}
		else
		{
            redirect(base_url());
		}
	}
	
	public function editsuppprod()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{	
			$id['kd_supplier'] = $this->uri->segment(3);
			
			$bc['dt_pr_header'] = $this->app_model->getSelectedData("mst.tm_supplier",$id);
			foreach($bc->result() as $dph)
			{
				$sess_data['kd_supplier'] = $dph->kd_supplier;
				$key['kd_supplier'] = $dph->no_pr;
				$this->session->set_userdata($sess_data);
			}

			$bc['dt_supp_detail'] = $this->app_model->manualQuery("select a.kd_supplier, a.kd_produk, a.konsinyasi, a.disk_persen_supp1, a.disk_persen_supp2,
																	a.disk_persen_supp3, a.disk_persen_supp4, a.disk_amt_supp1, a.disk_amt_supp2, a.disk_amt_supp3,
																	a.disk_amt_supp4, a.hrg_supplier, a.dpp, a.waktu_top, a.konsinyasi, b.nama_produk 
																	from mst.td_supp_per_brg a left join mst.tm_produk b 
																	on a.kd_produk=b.kd_produk where a.kd_supplier='".$key['kd_supplier']."'");
			
			if($this->session->userdata("limit_add_cart")=="")
			{
				$in_cart = array();
				foreach($bc['dt_supp_detail']->result() as $dpd)
				{
					$in_cart[] = array(
						'id'         		=> $dpd->kd_produk,
						'qty'        		=> 1,
						'price'      		=> 1,
						'name'       		=> $dpd->nama_produk,
						'disk_persen_supp1' => $dpd->disk_persen_supp1,
						'disk_persen_supp2' => $dpd->disk_persen_supp2,
						'disk_persen_supp3' => $dpd->disk_persen_supp3,
						'disk_persen_supp4' => $dpd->disk_persen_supp4,
						'disk_amt_supp1' 	=> $dpd->disk_amt_supp1,
						'disk_amt_supp2' 	=> $dpd->disk_amt_supp2,
						'disk_amt_supp3' 	=> $dpd->disk_amt_supp3,
						'disk_amt_supp4' 	=> $dpd->disk_amt_supp4,
						'hrg_supplier' 		=> $dpd->hrg_supplier,
						'dpp' 				=> $dpd->dpp,
						'waktu_top' 		=> $dpd->waktu_top,
						'konsinyasi' 		=> $dpd->konsinyasi);
				}
				$this->cart->insert($in_cart);
				$sess_data['limit_add_cart'] = "edit";
				$this->session->set_userdata($sess_data);
			}
			
		}
		else
		{
            redirect(base_url());
		}
	}
	
	public function detail_produk_supp()
	{
			
			if ($this->uri->segment(3)) {
				
				$this->cart->destroy();
				$this->session->unset_userdata('limit_add_cart');

				$id['kd_supplier'] = $this->uri->segment(3);
				$bc['dt_suppprod_header'] = $this->app_model->getSelectedData("mst.tm_supplier",$id);
				foreach($bc['dt_suppprod_header']->result() as $dph)
				{
					$key['kd_supplier'] = $dph->kd_supplier;
					$key['nama_supplier'] = $dph->nama_supplier;
				}
				
				$bc['dt_suppprod_detail'] = $this->app_model->manualQuery("select a.kd_supplier, a.kd_produk, a.konsinyasi, a.disk_persen_supp1, a.disk_persen_supp2,
																	a.disk_persen_supp3, a.disk_persen_supp4, a.disk_amt_supp1, a.disk_amt_supp2, a.disk_amt_supp3,
																	a.disk_amt_supp4, a.hrg_supplier, a.dpp, a.waktu_top, a.konsinyasi, b.nama_produk 
																	from mst.td_supp_per_brg a left join mst.tm_produk b 
																	on a.kd_produk=b.kd_produk where a.kd_supplier='".$key['kd_supplier']."'");
																	
				if($this->session->userdata("limit_add_cart")=="")
				{
					$in_cart = array();
					foreach($bc['dt_suppprod_detail']->result() as $dpd)
					{	
						//$thnreg = str_pad($dpd->thn_reg,4,"20",STR_PAD_LEFT);
						$in_cart[] = array(
							'id'         		=> $dpd->kd_produk,
							'qty'        		=> 1,
							'price'      		=> 1,
							'name'       		=> $dpd->nama_produk,
							'disk_persen_supp1' => $dpd->disk_persen_supp1,
							'disk_persen_supp2' => $dpd->disk_persen_supp2,
							'disk_persen_supp3' => $dpd->disk_persen_supp3,
							'disk_persen_supp4' => $dpd->disk_persen_supp4,
							'disk_amt_supp1' 	=> $dpd->disk_amt_supp1,
							'disk_amt_supp2' 	=> $dpd->disk_amt_supp2,
							'disk_amt_supp3' 	=> $dpd->disk_amt_supp3,
							'disk_amt_supp4' 	=> $dpd->disk_amt_supp4,
							'hrg_supplier' 		=> $dpd->hrg_supplier,
							'dpp' 				=> $dpd->dpp,
							'waktu_top' 		=> $dpd->waktu_top,
							'konsinyasi' 		=> $dpd->konsinyasi
						);
					}
					$this->cart->insert($in_cart);
					$sess_data['limit_add_cart'] = "edit";
					$this->session->set_userdata($sess_data);
				}
				
				$data['kd_supplier']=$key['kd_supplier'];
				$data['nama_supplier'] = $key['nama_supplier'];

				$this->load->view('page/vw_listsuppproddetail',$data);
		}
		else
		{
            redirect(base_url());
		}
	}

}

?>