<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class purchase_order extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('purchase_order_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Home - Pembelian - Create PO'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
			$this->cart->destroy();
			$this->session->unset_userdata('subject_pr');
			$this->session->unset_userdata('no_pr_po');
			$this->session->unset_userdata('limit_add_cart_po');
			$this->session->unset_userdata('created_date_pr');
            $res_approvedpr = $this->purchase_order_models->approved_pr_content();
            $data['rcapprovedpr']=$res_approvedpr;
            $this->load->view('po/vw_listapprovedpr', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {

				$id['no_pr'] = $this->uri->segment(3);
				$bc['dt_pr_header'] = $this->app_model->getSelectedData("mst.tt_purchase_request",$id);
				foreach($bc['dt_pr_header']->result() as $dph)
				{
					$sess_data1['subject_pr'] = $dph->subject;
					$sess_data2['no_pr_po'] = $dph->no_pr;
					$sess_data3['created_date_pr'] = $dph->created_date;
					$key['no_pr'] = $dph->no_pr;
					$this->session->set_userdata($sess_data1);
					$this->session->set_userdata($sess_data2);
					$this->session->set_userdata($sess_data3);
				}

				$bc['dt_pr_detail'] = $this->app_model->manualQuery("select a.no_pr, a.kd_produk, a.qty, a.thn_reg, b.nama_produk, c.nm_satuan
																	from mst.tt_dtl_purchase_request a 
																	left join mst.tm_produk b on a.kd_produk=b.kd_produk 
																	left join mst.tm_satuan c on b.id_satuan=c.id_satuan
																	where a.no_pr='".$key['no_pr']."'
																	and a.status = 'A'
																	");
				
				$in_cart = array();
				foreach($bc['dt_pr_detail']->result() as $dpd)
				{	
					$thnreg = str_pad($dpd->thn_reg,4,"20",STR_PAD_LEFT);
					$in_cart[] = array(
					'id'         => $dpd->kd_produk,
					'qty'        => $dpd->qty,
					'price'      => 100,
					'name'       => '-',
					'thn_reg'    => $thnreg,
					'namap'		 => $dpd->nama_produk,			
					'satuan'     => $dpd->nm_satuan,
					'options'    => array('status' => '0')
					);
				}
				$this->cart->insert($in_cart);
				
                
                $data['no_pr'] = $this->session->userdata("no_pr_po");
                $data['subject'] = $this->session->userdata("subject_pr");
                $tgltrans = new DateTime($this->session->userdata("created_date_pr"));
				$data['tgltrans'] = $tgltrans->format('d-M-Y');
				$data['masa_berlaku'] = '';

            } else {
                $data['no_pr'] = $this->app_model->getMaxNoPR();
                $data['subject'] = $this->session->userdata("subject_pr");
                $data['tgltrans'] = date('d-M-Y');
				$data['masa_berlaku'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata("username");;
            $data['title'] = $judul;
            $data['location'] = 'Home - Master - Purchase Order';

		}

        if ($this->session->userdata('username')) {
            $this->load->view('po/vw_dtl_pr', $data);
        } else {
             redirect(base_url());
        }
    }

    public function createpo()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
			if($this->session->userdata("limit_add_cart_po")=="")
			{
				
				$no_pr = $this->input->post('no_pr');
				$masa_berlaku = $this->input->post('masa_berlaku');
				$created_date = date('Y-m-d H:i:s');
				$created_by = $this->session->userdata("username");
				
				$totalbrs = $this->input->post('totbaris');
				$kd_produk = $this->input->post('kd_produk');
				$kode_supplier = $this->input->post('kode_supplier');
				$qty_beli = $this->input->post('qty_beli');
				
				for($i=0;$i < $totalbrs;$i++)
				{	
					$dt['jml_po'] = $this->app_model->manualquery("
								select count(*) as jml 
								from mst.tt_purchase_order where kd_supplier = '".$kode_supplier[$i]."' and no_pr = '".$no_pr."'
								");
								
					foreach($dt['jml_po']->result() as $dps)
					{
						$keyjml['jml'] = $dps->jml;
					}
					
					$dt['supp_brg'] = $this->app_model->manualquery("
								select b.pkp, a.disk_persen_supp1, a.disk_persen_supp2, a.disk_persen_supp3, a.disk_persen_supp4,
									a.disk_amt_supp1, a.disk_amt_supp2, a.disk_amt_supp3, a.disk_amt_supp4, a.hrg_supplier, a.dpp, a.waktu_top
								from mst.td_supp_per_brg a
								join mst.tm_supplier b on (b.kd_supplier = a.kd_supplier)
								where a.kd_supplier = '".$kode_supplier[$i]."' 
								and a.kd_produk = '".$kd_produk[$i]."'
								and a.konsinyasi is false
								");
					foreach($dt['supp_brg']->result() as $dpsb)
					{	
						$dtsb['pkp'] = $dpsb->pkp;
						$dtsb['disk_persen_supp1'] = $this->fungsi->nvl($dpsb->disk_persen_supp1,'0');
						$dtsb['disk_persen_supp2'] = $this->fungsi->nvl($dpsb->disk_persen_supp2,'0');
						$dtsb['disk_persen_supp3'] = $this->fungsi->nvl($dpsb->disk_persen_supp3,'0');
						$dtsb['disk_persen_supp4'] = $this->fungsi->nvl($dpsb->disk_persen_supp4,'0');
						$dtsb['disk_amt_supp1'] = $this->fungsi->nvl($dpsb->disk_amt_supp1,'0');
						$dtsb['disk_amt_supp2'] = $this->fungsi->nvl($dpsb->disk_amt_supp2,'0');
						$dtsb['disk_amt_supp3'] = $this->fungsi->nvl($dpsb->disk_amt_supp3,'0');
						$dtsb['disk_amt_supp4'] = $this->fungsi->nvl($dpsb->disk_amt_supp4,'0');
						$dtsb['hrg_supplier'] = $this->fungsi->nvl($dpsb->hrg_supplier,'0');
						$dtsb['dpp'] = $this->fungsi->nvl($dpsb->dpp,'0');
						$dtsb['waktu_top'] = $this->fungsi->nvl($dpsb->waktu_top,'0');
					}
					
					if ( $keyjml['jml'] == '0' ) {
						$no_po = $this->app_model->getMaxNoPO();
						
						$jumlah = $qty_beli[$i];
						$total = $dtsb['hrg_supplier'] * $qty_beli[$i] ;
						
						if ($dtsb['pkp'] == '0') {
							$ppn = 0;
							$grand_total = $total;
						} else {
							$ppn = 10;
							$grand_total = $total + (0.1*$total);
						}
						
						$this->app_model->manualquery("
							insert into mst.tt_purchase_order (
								no_po, no_pr, kd_supplier, dpp, masa_berlaku, jumlah, ppn, grand_total, 
								approval, created_by, created_date
							) VALUES (
								'".$no_po."', '".$no_pr."', '".$kode_supplier[$i]."', '".$dtsb['dpp']."', '".$masa_berlaku."', '".$jumlah."', '".$ppn."', '".$grand_total."',
								'0', '".$created_by."', '".$created_date."'
							)
						");
						
						$this->app_model->manualquery("
							insert into mst.tt_dtl_purchase_order (
								no_po, no_pr, kd_supplier, kd_kategori1, kd_kategori2, kd_kategori3, kd_kategori4, 
								thn_reg, no_urut, kd_produk, qty_beli, disk_persen_supp1, disk_persen_supp2, disk_persen_supp3,
								disk_persen_supp4, disk_amt_supp1, disk_amt_supp2, disk_amt_supp3, disk_amt_supp4, hrg_supplier, 
								dpp, waktu_top, approval, created_by, created_date
							) VALUES (
								'".$no_po."', '".$no_pr."', '".$kode_supplier[$i]."', '".substr($kd_produk[$i],0,2)."', '".substr($kd_produk[$i],2,2)."', '".substr($kd_produk[$i],4,2)."', '".substr($kd_produk[$i],6,2)."',
								'".substr($kd_produk[$i],8,2)."', '".substr($kd_produk[$i],10,3)."', '".$kd_produk[$i]."', '".$qty_beli[$i]."', '".$dtsb['disk_persen_supp1']."', '".$dtsb['disk_persen_supp2']."', '".$dtsb['disk_persen_supp3']."',
								'".$dtsb['disk_persen_supp4']."', '".$dtsb['disk_amt_supp1']."', '".$dtsb['disk_amt_supp2']."', '".$dtsb['disk_amt_supp3']."', '".$dtsb['disk_amt_supp4']."', '".$dtsb['hrg_supplier']."',
								'".$dtsb['dpp']."', '".$dtsb['waktu_top']."', '0', '".$created_by."', '".$created_date."'
							)
						");
					
					} else  {
						
						$dt['dt_po'] = $this->app_model->manualquery("
								select no_po, jumlah, grand_total 
								from mst.tt_purchase_order where kd_supplier = '".$kode_supplier[$i]."' and no_pr = '".$no_pr."'
								");
						foreach($dt['dt_po']->result() as $dppo)
						{
							$keypo['no_po'] = $dppo->no_po;
							$keypo['jumlah'] = $dppo->jumlah;
							$keypo['grand_total'] = $dppo->grand_total;
						}
						
						$this->app_model->manualquery("
							insert into mst.tt_dtl_purchase_order (
								no_po, no_pr, kd_supplier, kd_kategori1, kd_kategori2, kd_kategori3, kd_kategori4, 
								thn_reg, no_urut, kd_produk, qty_beli, disk_persen_supp1, disk_persen_supp2, disk_persen_supp3,
								disk_persen_supp4, disk_amt_supp1, disk_amt_supp2, disk_amt_supp3, disk_amt_supp4, hrg_supplier, 
								dpp, waktu_top, approval, created_by, created_date
							) VALUES (
								'".$keypo['no_po']."', '".$no_pr."', '".$kode_supplier[$i]."', '".substr($kd_produk[$i],0,2)."', '".substr($kd_produk[$i],2,2)."', '".substr($kd_produk[$i],4,2)."', '".substr($kd_produk[$i],6,2)."',
								'".substr($kd_produk[$i],8,2)."', '".substr($kd_produk[$i],10,3)."', '".$kd_produk[$i]."', '".$qty_beli[$i]."', '".$dtsb['disk_persen_supp1']."', '".$dtsb['disk_persen_supp2']."', '".$dtsb['disk_persen_supp3']."',
								'".$dtsb['disk_persen_supp4']."', '".$dtsb['disk_amt_supp1']."', '".$dtsb['disk_amt_supp2']."', '".$dtsb['disk_amt_supp3']."', '".$dtsb['disk_amt_supp4']."', '".$dtsb['hrg_supplier']."',
								'".$dtsb['dpp']."', '".$dtsb['waktu_top']."', '0', '".$created_by."', '".$created_date."'
							)
						");
						
						$jumlah = $keypo['jumlah'] + $qty_beli[$i];
						$total = $dtsb['hrg_supplier'] * $qty_beli[$i] ;
						
						if ($dtsb['pkp'] == '0') {
							$ppn = 0;
							$grand_total = $keypo['grand_total'] + $total;
						} else {
							$ppn = 10;
							$grand_total = $keypo['grand_total'] + ($total + (0.1*$total));
						}
						
						$this->app_model->manualquery("
							update mst.tt_purchase_order
								set jumlah = '".$jumlah."',
									grand_total = '".$grand_total."'
							where no_po = '".$keypo['no_po']."'
						");
					}
					
					
				}
					$statuspr['status']='3';
					$keypr['no_pr']=$this->input->post('no_pr');
					$this->app_model->updateData("mst.tt_purchase_request",$statuspr,$keypr);
				
				$this->cart->destroy();
				$this->session->unset_userdata('subject_pr');
				$this->session->unset_userdata('no_pr_po');
				$this->session->unset_userdata('limit_add_cart_po');
				$this->session->unset_userdata('created_date_pr');
				header('location:'.base_url().'purchase_order');

			}
		} else {
            redirect(base_url());
		}
	}

}

?>