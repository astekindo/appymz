<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$timezone = "Asia/Jakarta";
if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
//echo date('d-m-Y H:i:s');

class Approval_po extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('approval_po_models');
        
    }
	
	function convertDate($date) {
       // EN-Date to GE-Date
       if (strstr($date, "/") || strstr($date, "/"))   {
               $date = preg_split("/[\/]|[-]+/", $date);
               $date = $date[2]."/".$date[1]."/".$date[0];
               return $date;
       }
       return false;
	}
	
    public function index()
    {
        $judul=$this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Home - Pembelian - Approve PO'
        );
        if($this->session->userdata('username')){
            $res_menu=$this->menu_models->menu_content();            
            $data['menu']=$res_menu;
			$res_approval_po = $this->approval_po_models->approval_po_content();
            $data['rcapproval_po']=$res_approval_po;
            
			$this->cart->destroy();
			$this->session->unset_userdata('limit_add_cart');

			$this->load->view('po/vw_approval_po', $data);
        }else{
            redirect(base_url());
        }

    }
    
	public function daftar_produkpo()
	{
		if($this->uri->segment(3))
		{
				$id['id_po'] = $this->uri->segment(3);
				$bc['dt_po_header'] = $this->app_model->getSelectedData("mst.tt_purchase_order",$id);
				foreach($bc['dt_po_header']->result() as $dph)
				{
					$key['no_po'] = $dph->no_po;
				}
			
				$res_listbarang_po = $this->approval_po_models->listbarang_po_content($key['no_po']);
				$data['rcdetailapproval_po']=$res_listbarang_po;
				$data['no_po']=$key['no_po'];

				$this->load->view('page/vw_detailapproval_po',$data);
		}
		else
		{
            redirect(base_url());
		}
	}

	 
	public function daftar_produkpo_edit()
	{
			
			if ($this->uri->segment(3)) {
				
				$this->cart->destroy();
				$this->session->unset_userdata('limit_add_cart');

				$id['id_po'] = $this->uri->segment(3);
				$bc['dt_po_header'] = $this->app_model->getSelectedData("mst.tt_purchase_order",$id);
				foreach($bc['dt_po_header']->result() as $dph)
				{
					$key['no_po'] = $dph->no_po;
					$key['approval'] = $dph->approval;
				}

				$bc['dt_po_detail'] = $this->app_model->manualQuery("
							select a.no_po, a.no_pr, a.kd_produk, a.kd_supplier, a.thn_reg, a.qty_beli, a.disk_persen_supp1, a.disk_persen_supp2,
							a.disk_persen_supp3, a.disk_persen_supp4, a.disk_amt_supp1, a.disk_amt_supp2, a.disk_amt_supp3, a.disk_amt_supp4, 
							a.hrg_supplier, a.dpp, a.waktu_top, a.approval, d.nm_satuan,b.nama_produk,b.qty_oh, c.approval app
							from mst.tt_dtl_purchase_order a 
							left join mst.tm_produk b on a.kd_produk=b.kd_produk 
							left join mst.tt_purchase_order c on a.no_pr=c.no_po 
							left join mst.tm_satuan d on b.id_satuan=d.id_satuan
				where a.no_po='".$key['no_po']."'");
				if($this->session->userdata("limit_add_cart")=="")
				{
					$in_cart = array();
					foreach($bc['dt_po_detail']->result() as $dpd)
					{	
						$thnreg = str_pad($dpd->thn_reg,4,"20",STR_PAD_LEFT);
						$in_cart[] = array(
						'id'         			=> $dpd->kd_produk,
						'qty'        			=> 1,
						'price'      			=> 1,
						'name'       			=> '-',
						'namap'					=> $dpd->nama_produk,
						'thn_reg'   	 		=> $thnreg,
						'qty_oh'     			=> $dpd->qty_oh,
						'qty_beli'	 			=> $dpd->qty_beli,
						'disk_persen_supp1'		=> $dpd->disk_persen_supp1,
						'disk_persen_supp2'		=> $dpd->disk_persen_supp2,
						'disk_persen_supp3'		=> $dpd->disk_persen_supp3,
						'disk_persen_supp4'		=> $dpd->disk_persen_supp4,
						'disk_amt_supp1'		=> $dpd->disk_amt_supp1,
						'disk_amt_supp2'		=> $dpd->disk_amt_supp2,
						'disk_amt_supp3'		=> $dpd->disk_amt_supp3,
						'disk_amt_supp4'		=> $dpd->disk_amt_supp4,
						'hrg_supplier'			=> $dpd->hrg_supplier,
						'dpp'					=> $dpd->dpp,
						'waktu_top'				=> $dpd->waktu_top,
						'satuan'     			=> $dpd->nm_satuan,
						'approval'   			=> $dpd->approval,
						'options'    			=> array('statusapp' => '0'));
					}
					$this->cart->insert($in_cart);
					$sess_data['limit_add_cart'] = "edit";
					$this->session->set_userdata($sess_data);
				}

                $tgltrans = new DateTime($this->session->userdata("created_date"));
				$data['tgltrans'] = $tgltrans->format('d-M-Y');
				$data['no_po']=$key['no_po'];
				$data['app']=$key['approval'];

				$this->load->view('po/vw_detailapproval_po',$data);
		}
		else
		{
            redirect(base_url());
		}
	}
	
	public function approve()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
				$id['no_po'] = $this->input->post('no_po');
				$d_approval['approval'] = '1';
				
				$this->app_model->updateData("mst.tt_purchase_order",$d_approval,$id);

				//$total = $this->cart->total_items('rowid');
				$totalbrs = $this->input->post('totbaris');
				$item = $this->input->post('rowid');
				$kd_produk = $this->input->post('kd_produk');
				$qty_beli = $this->input->post('qty_beli');
				$approval=$this->input->post('approval');
				
				for($i=0;$i < $totalbrs;$i++)
				{
					if ($approval[$i]=="on")
						{$approval[$i]="A";}
					else
						{$approval[$i]="N";}
					// $data = array(
					// 'rowid' => $item[$i],
					// 'qty'   => $qty[$i],
					// 'status'   => $bstatus[$i],
					// 'options'   => array('statusdtl' => '0')
					//);
					//$this->cart->update_options($data);
					$this->app_model->manualquery(" update mst.tt_dtl_purchase_order set 
													qty_beli = '".$qty_beli[$i]."' , 
													approval = '".$approval[$i]."'
													where no_po='".$id['no_po']."' 
													and kd_produk='".$kd_produk[$i]."'");
					
				}

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
	
	public function notapprove()
	{
		$cek = $this->session->userdata('username');
		if(!empty($cek))
		{
				$id['no_po'] = $this->uri->segment(3);
				$d_approval['approval'] = '4';
				$d_approvaldtl['approval'] = 'N';
				
				$this->app_model->updateData("mst.tt_purchase_order",$d_approval,$id);
				$this->app_model->updateData("mst.tt_dtl_purchase_order",$d_approvaldtl,$id);

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
	
	
	
}

