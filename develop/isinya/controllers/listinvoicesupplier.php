<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$timezone = "Asia/Jakarta";
if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
//echo date('d-m-Y H:i:s');

$localtime=date('H:i:s');
$localdate=date('Y-m-d');
$today=date('Y-m-d H:i:s');


class Listinvoicesupplier extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('invoicesupplier_models');
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
            $res_ro = $this->invoicesupplier_models->invoice_content();
            $data['rcreceiveorder']=$res_ro;
            $this->load->view('listinvoicesuppliercreate', $data);
        } else {
             redirect(base_url());
        }
    }

    public function form() {
        $judul = $this->config->item('judul');
        if ($this->uri->segment(3)) {

            $id['no_ro'] = $this->uri->segment(3);
            $sql = "select a.no_ro,a.no_po,a.no_pr,a.kd_supplier,b.nama_supplier 
                        from mst.tt_receive_order a, mst.tm_supplier b 
                        where a.kd_supplier=b.kd_supplier and a.no_ro='".$id['no_ro']."'";
            #exit($sql);
            $bc['dt_ro_header'] = $this->app_model->manualquery($sql);
            foreach($bc['dt_ro_header']->result() as $dph)
            {
                $sess_data1['no_ro'] = $dph->no_ro;
                $sess_data2['no_po'] = $dph->no_po;
                $sess_data3['no_pr'] = $dph->no_pr;
                $sess_data4['kd_supplier'] = $dph->kd_supplier;
                $sess_data5['nama_supplier'] = $dph->nama_supplier;
                $key['no_ro'] = $dph->no_ro;
                $this->session->set_userdata($sess_data1);
                $this->session->set_userdata($sess_data2);
                $this->session->set_userdata($sess_data3);
                $this->session->set_userdata($sess_data4);
                $this->session->set_userdata($sess_data5);
            }
            $sqlDet="select a.no_ro, a.kd_produk, a.qty_beli, a.thn_reg, 
                            c.nm_satuan, b.nama_produk, a.kd_lokasi, a.hrg_supplier, 
                            a.disk_persen_supp1 as disk_persen_supp1, a.disk_persen_supp2, a.disk_persen_supp3,  a.disk_persen_supp4,
                            a.disk_amt_supp1, a.disk_amt_supp2, a.disk_amt_supp3,  a.disk_amt_supp4
                    from mst.tt_dtl_receive_order a 
                    left join mst.tm_produk b on a.kd_produk=b.kd_produk 
                    left join mst.tm_satuan c on b.id_satuan=c.id_satuan 
                where a.no_ro='".$key['no_ro']."'";
                #echo $sqlDet;

            $dtro['dt_ro'] = $this->app_model->manualQuery($sqlDet);

                $in_cart = array();
                foreach($dtro['dt_ro']->result() as $dpd)
                {   
                    $thnreg = str_pad($dpd->thn_reg,4,"20",STR_PAD_LEFT);
                    $in_cart[] = array(
                    'id'          => $dpd->kd_produk,
                    'qty'         => $dpd->qty_beli,
                    'disk_persen_supp1' => $dpd->disk_persen_supp1,
                    'disk_persen_supp2' => $dpd->disk_persen_supp2,
                    'disk_persen_supp3' => $dpd->disk_persen_supp3,
                    'disk_persen_supp4' => $dpd->disk_persen_supp4,
                    'disk_amt_supp1' => $dpd->disk_amt_supp1,
                    'disk_amt_supp2' => $dpd->disk_amt_supp2,
                    'disk_amt_supp3' => $dpd->disk_amt_supp3,
                    'disk_amt_supp4' => $dpd->disk_amt_supp4,
                    'hrg_supplier' => $dpd->hrg_supplier,
                    'price'       => 1,
                    'name'        => $dpd->nama_produk,
                    'thn_reg'     => $thnreg,
                    'satuan'      => $dpd->nm_satuan,
                    'kd_lokasi'   => $dpd->kd_lokasi,
                    'kd_blok'     => '-',
                    'kd_sub_blok' => '-',
                    'keterangan' => '-',
                    'options'     => array('statusapp' => '0'));
                }
                $this->cart->insert($in_cart);              
            
            
            $data['listlokasi']= $this->app_model->manualquery("select c.kd_lokasi||b.kd_blok||a.kd_sub_blok kode,c.nama_lokasi||' '||b.nama_blok||'
                                                                '||a.nama_sub_blok nama_lokasi from mst.tm_sub_blok a, mst.tm_blok b, mst.tm_lokasi c where a.kd_lokasi=c.kd_lokasi and b.kd_lokasi=c.kd_lokasi and a.kd_blok = b.kd_blok and a.aktif is true order by nama_lokasi asc");
            $data['id_ro'] = '';
            $data['no_ro'] = $this->session->userdata("no_ro");
            $data['tanggal'] = date('d-M-Y');
            $data['tt_po'] = $this->app_model->manualquery("select no_po from mst.tt_purchase_order where approval='1'");
            $data['no_po'] = $this->session->userdata("no_po");
            $data['no_pr'] = $this->session->userdata("no_pr");
            $data['kd_supplier'] = $this->session->userdata("kd_supplier");
            $data['nama_supplier'] = $this->session->userdata("nama_supplier");
            
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata("username");;
            $data['title'] = $judul;
            $data['location'] = 'Home - Master - Purchase Request';

        }

        if ($this->session->userdata('username')) {
            $this->load->view('invoicesupplierform', $data);
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
	
	public function save()
	{
       $this->receiveorder_models->receiveorder_update_invoice($this->input->post('no_ro'));
        header('location:'.base_url().'invoicesupplier');
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