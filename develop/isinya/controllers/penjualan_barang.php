<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$timezone = "Asia/Jakarta";
if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
//echo date('d-m-Y H:i:s');

$localtime=date('H:i:s');
$localdate=date('Y-m-d');
$today=date('Y-m-d H:i:s');

class Penjualan_barang extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('purchaserequest_models');
        $this->load->model('member_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Home - Penjualan - POS'
        );

        if ($this->session->userdata('username')) {
			$this->cart->destroy();
			$this->session->unset_userdata('subject');
			$this->session->unset_userdata('no_pr');
			$this->session->unset_userdata('limit_add_cart');
			$this->session->unset_userdata('created_date');
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
            $res_pr = $this->purchaserequest_models->purchaserequest_content();
            $data['rcpurchaserequest']=$res_pr;
            $this->load->view('penjualan/poslist', $data);
        } else {
             redirect(base_url());
        }
    }

    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {

				$id['id_pr'] = $this->uri->segment(3);
				$bc['dt_pr_header'] = $this->app_model->getSelectedData("mst.tt_purchase_request",$id);
				foreach($bc['dt_pr_header']->result() as $dph)
				{
					$sess_data1['subject'] = $dph->subject;
					$sess_data2['no_pr'] = $dph->no_pr;
					$sess_data3['created_date'] = $dph->created_date;
					$key['no_pr'] = $dph->no_pr;
					$this->session->set_userdata($sess_data1);
					$this->session->set_userdata($sess_data2);
					$this->session->set_userdata($sess_data3);
				}

				$bc['dt_pr_detail'] = $this->app_model->manualQuery("select a.no_pr, a.kd_produk, a.qty, a.thn_reg, b.nama_produk, c.nm_satuan
																	from mst.tt_dtl_purchase_request a left join mst.tm_produk b 
																	on a.kd_produk=b.kd_produk left join mst.tm_satuan c on b.id_satuan=c.id_satuan
																	where a.no_pr='".$key['no_pr']."'");
				if($this->session->userdata("limit_add_cart")=="")
				{
					$in_cart = array();
					foreach($bc['dt_pr_detail']->result() as $dpd)
					{	
						$thnreg = str_pad($dpd->thn_reg,4,"20",STR_PAD_LEFT);
						$in_cart[] = array(
						'id'         => $dpd->kd_produk,
						'qty'        => $dpd->qty,
						'price'      => 100,
						'name'       => $dpd->nama_produk,
						'thn_reg'    => $thnreg,
						'satuan'     => $dpd->nm_satuan,
						'options'    => array('status' => '0'));
					}
					$this->cart->insert($in_cart);
					$sess_data['limit_add_cart'] = "edit";
					$this->session->set_userdata($sess_data);
				}

                $data['id_pr'] = '';
                $data['no_pr'] = $this->session->userdata("no_pr");
                $data['subject'] = $this->session->userdata("subject");
                $tgltrans = new DateTime($this->session->userdata("created_date"));
				$data['tgltrans'] = $tgltrans->format('d-M-Y');
				$list_status['d']= "Distribusi";
				$list_status['p']= "Project";
				$data['listnama_member'] = $this->member_models->nama_member();
                $data['status_pos'] = '';
				$data['listnama_status'] = $list_status;


            } else {
                $data['id_pr'] = '';
                $data['no_pr'] = $this->app_model->getMaxNoPOS();
                $data['subject'] = $this->session->userdata("subject");
                $data['kd_member'] = '';
				$data['tgltrans'] = date('d-M-Y');
				$list_status['d']= "Distribusi";
				$list_status['p']= "Project";
				$data['listnama_member'] = $this->member_models->nama_member();
                $data['status_pos'] = '';
				$data['listnama_status'] = $list_status;
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata("username");;
            $data['title'] = $judul;
            $data['location'] = 'Home - Master - Purchase Request';

		}

        if ($this->session->userdata('username')) {
            $this->load->view('penjualan/pos_form', $data);
        } else {
             redirect(base_url());
        }
    }

	public function daftar_Produk()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			$bc['jdl'] = "Daftar Produk";
			$bc['tm_produk'] = $this->app_model->manualQuery("select kd_produk,nama_produk from mst.tm_produk limit 200");
			
			$this->load->view('penjualan/daftar_produk',$bc);
		}
		else
		{
            redirect(base_url());
		}
	}

	public function daftar_Produk_onchange()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			$data["nama_produk"] = $_GET["kdproduk"];
			$bc = $this->app_model->manualQuery("select kd_produk,nama_produk from mst.tm_produk where nama_produk like '%'$data'%' limit 2");
			foreach($bc->result() as $rc)
			{
				?>
				<option value="<?php echo $rc['kd_produk']; ?>"><?php echo $rc['nama_produk']; ?></option>
				<?php
			}
			
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
			$q = $this->app_model->manualquery("select a.kd_produk,a.nama_produk,b.nm_satuan from mst.tm_produk a, mst.tm_satuan b where a.id_satuan=b.id_satuan and a.kd_produk='".$data["kd_produk"]."'");
			foreach($q->result() as $d)
			{
			?>
			<table cellpadding="0" cellspacing="0" width="100%" class="tDefault">
			<thead><tr></tr></thead><tbody>
			<td>Kode Produk</td>
			<td><input type="text" id="kode" name="kode" value="<?php echo $d->kd_produk; ?>" style="width:100px;" class="required" readonly="TRUE" /></td>
			</tr><tr>
			<td>Nama Produk</td>
			<td><input type="text" id="nama" name="nama" value="<?php echo $d->nama_produk; ?>" style="width:350px;" class="required" readonly="TRUE" /></td>
			</tr>
<!--			<tr>
			<td>Tahun Reg</td>
			<td><input type="text" id="thn" name="thn" value="<?php echo str_pad($d->thn_reg,4,"20",STR_PAD_LEFT); ?>" style="width:50px;" class="required" readonly="TRUE" /></td>
			</tr>-->
			<tr>
			<td>Qty</td>
			<td><input type="text" id="qty" name="qty" value="1" style="width:50px;" onKeyPress="return onlyNumbers(event);" class="required number" /></td>
			</tr>
			<tr>
			<td>Satuan</td>
			<td><input type="text" id="satuan" name="satuan" value="<?php echo $d->nm_satuan; ?>" style="width:100px;" class="required" readonly="TRUE" /></td>
			</tr>
			<tr>
			<td>Discount</td>
			<td><input type="text" id="disc" name="disc" value="0" style="width:100px;" class="required" readonly="FALSE" /></td>
			</tr>
			<tr>
			<td>Status Kirim</td>
			<td><select>
					<option value="Y">Yes</option>
					<option value="N">No</option>
				</select></td>
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
			'qty'        => $this->input->post('qty'),
			'price'      => 100,
			'name'       => $this->input->post('nama'),
			'thn_reg'    => $this->input->post('thn'),
			'satuan'    => $this->input->post('satuan'),
			'options'    => array('status' => '0'));

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
	
	public function delcart()
	{
			$kode = explode("/",$_GET['kode']);
			if($this->session->userdata("limit_add_cart")=="")
			{
				$data = array(
				'rowid' => $kode[0],
				'qty'   => 0);
				$this->cart->update($data);
			}
			else if($this->session->userdata("limit_add_cart")=="edit")
			{
				$data = array(
				'rowid' => $kode[0],
				'qty'   => 0);
				$this->cart->update($data);
				$hps['no_pr'] = $kode[1];
				$hps['kd_produk'] = $kode[2];
				$this->app_model->deleteData("mst.tt_dtl_purchase_request",$hps);
			}
		redirect(base_url() . "purchaserequest/form", "location");

	}

	public function addsession()
	{
			$data["subject"] = $this->input->post("subject");
			$sess_data['subject'] = $data["subject"];
			$this->session->set_userdata($sess_data);
	}
	
	public function savepr()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			if($this->session->userdata("limit_add_cart")=="")
			{
				$d_header['no_pr'] = $this->app_model->getMaxNoPR();
				$temp = $d_header['no_pr'];
				$d_header['created_date'] = date('Y-m-d H:i:s');
				$d_header['created_date2'] = date('Y-m-d H:i:s');
				$d_header['subject'] = $this->session->userdata("subject");
				$d_header['created_by'] = $this->session->userdata("username");
				$d_header['created_by2'] = $this->session->userdata("username");
				$d_header['status'] = '0';
				$this->app_model->insertData("mst.tt_purchase_request",$d_header);
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
				$this->cart->destroy();
				header('location:'.base_url().'purchaserequest');

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
				header('location:'.base_url().'purchaserequest');
			}
		}
		else
		{
            redirect(base_url());
		}
	}
	

	public function hapuspr()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			$hapus['id_pr'] = $this->uri->segment(3);
			//kembalikan kuantitas barang
			$q = $this->app_model->getSelectedData("mst.tt_purchase_request",$hapus);
			foreach($q->result() as $d)
			{
				//$data['stok'] = $d->qty+$this->app_model->getSisaStok($d->kode_barang);
				$key['no_pr'] = $d->no_pr;
				//$this->app_model->updateData("tbl_barang",$data,$key);
			}
			$this->app_model->deleteData("mst.tt_dtl_purchase_request",$key);
			$this->app_model->deleteData("mst.tt_purchase_request",$key);
			?>
				<script> window.location = "<?php echo base_url(); ?>purchaserequest"; </script>
			<?php
		}
		else
		{
            redirect(base_url());
		}
	}

	public function editpr()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{	
			$id['id_pr'] = $this->uri->segment(3);
			//$cek_faktur = $this->app_model->getSelectedData("tbl_faktur",$id);
			/*if($cek_faktur->num_rows()>0)
			{
				$bc['alert'] = 'return confirm(\' Faktur untuk kode pesanan : '.$id['kode_pesanan'].' telah tersimpan dan akan terhapus otomatis jika anda melakukan perubahan data pesanan. Silahkan menginputkan kembali data faktur untuk kode '.$id['kode_pesanan'].' setelah melakukan perubahan data pesanan.\');';
				$this->session->set_userdata("alert_edit","ok");
			}*/
			
			$bc['dt_pr_header'] = $this->app_model->getSelectedData("mst.tt_purchase_request",$id);
			foreach($bc->result() as $dph)
			{
				$sess_data['subject'] = $dph->subject;
				$key['no_pr'] = $dph->no_pr;
				$this->session->set_userdata($sess_data);
				//$this->app_model->updateData("tbl_barang",$data,$key);
			}

			$bc['dt_pr_detail'] = $this->app_model->manualQuery("select a.no_pr, a.kd_produk, a.qty, a.thn_reg, b.nama_produk 
																from mst.tt_dtl_purchase_request a left join mst.tm_produk b 
																on a.kd_produk=b.kd_produk where a.no_pr='".$key['no_pr']."'");
			
			if($this->session->userdata("limit_add_cart")=="")
			{
				$in_cart = array();
				foreach($bc['dt_pr_detail']->result() as $dpd)
				{
					$in_cart[] = array(
					'id'         => $dpd->kd_produk,
					'qty'        => $dpd->qty,
					'price'      => 100,
					'name'       => $dpd->nama_produk,
					'thn_reg'    => $dpd->thn_reg,
					'options'    => array('status' => '0'));
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

	
}

?>