<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$timezone = "Asia/Jakarta";
if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
//echo date('d-m-Y H:i:s');

class Approval_pr extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('approval_pr_models');
        
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
            'location' => 'Home - Master - approval_pr'
        );
        if($this->session->userdata('username')){
            $res_menu=$this->menu_models->menu_content();            
            $data['menu']=$res_menu;

			//$res_approval_pr = $this->approval_pr_models->approval_pr_content();
            //$data['rcapproval_pr']=$res_approval_pr;
			$page=$this->uri->segment(3);
			$batas=10;
			if(!$page):
			$offset = 0;
			else:
			$offset = $page;
			endif;

			$data['rcapproval_pr'] = $this->approval_pr_models->approval_pr_data($batas,$offset);
			$tot_hal = $this->approval_pr_models->tot_hal();

			$config = array();
			$config['base_url'] = base_url() . 'approval_pr/index';
       		$config['total_rows'] = $tot_hal->num_rows();
       		$config['per_page'] = $batas;
			$config['uri_segment'] = 3;
    		$config['first_link'] = '<span class="paginate_button">Awal</span>';
			$config['last_link'] = 'Akhir';
			$config['next_link'] = 'Selanjutnya >>';
			$config['prev_link'] = '<< Sebelumnya';

			$this->pagination->initialize($config);
			
			$this->cart->destroy();
			//$this->session->unset_userdata('subject');
			//$this->session->unset_userdata('no_pr');
			$this->session->unset_userdata('limit_add_cart');
			//$this->session->unset_userdata('created_date');

			$this->load->view('page/vw_approved_pr', $data);
        }else{
            redirect(base_url());
        }

    }
    
	public function daftar_produkpr()
	{
		if($this->uri->segment(3))
		{
				$id['id_pr'] = $this->uri->segment(3);
				$bc['dt_pr_header'] = $this->app_model->getSelectedData("mst.tt_purchase_request",$id);
				foreach($bc['dt_pr_header']->result() as $dph)
				{
					$key['no_pr'] = $dph->no_pr;
				}
			
				$res_listbarang_pr = $this->approval_pr_models->listbarang_pr_content($key['no_pr']);
				$data['rcdetailapproval_pr']=$res_listbarang_pr;
				$data['no_pr']=$key['no_pr'];

				$this->load->view('page/vw_detailapproved_pr',$data);
		}
		else
		{
            redirect(base_url());
		}
	}

	 
	public function daftar_produkpr_edit()
	{
			
			if ($this->uri->segment(3)) {
				
				$this->cart->destroy();
				$this->session->unset_userdata('limit_add_cart');

				$id['id_pr'] = $this->uri->segment(3);
				$bc['dt_pr_header'] = $this->app_model->getSelectedData("mst.tt_purchase_request",$id);
				foreach($bc['dt_pr_header']->result() as $dph)
				{
					$key['no_pr'] = $dph->no_pr;
					$key['status'] = $dph->status;
				}

				$bc['dt_pr_detail'] = $this->app_model->manualQuery("select a.no_pr, a.kd_produk, b.qty_oh, a.qty, a.thn_reg, d.nm_satuan,b.nama_produk, a.status, c.status sts from mst.tt_dtl_purchase_request a left join mst.tm_produk b on a.kd_produk=b.kd_produk left join mst.tt_dtl_purchase_request c on a.no_pr=c.no_pr left join mst.tm_satuan d on b.id_satuan=d.id_satuan
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
						'price'      => 1,
						'name'       => '-',
						'thn_reg'    => $thnreg,
						'satuan'     => $dpd->nm_satuan,
						'qty_oh'     => $dpd->qty_oh,
						'status'     => $dpd->status,
						'namap'		 => $dpd->nama_produk,
						'options'    => array('statusapp' => '0'));
					}
					$this->cart->insert($in_cart);
					$sess_data['limit_add_cart'] = "edit";
					$this->session->set_userdata($sess_data);
				}

                $tgltrans = new DateTime($this->session->userdata("created_date"));
				$data['tgltrans'] = $tgltrans->format('d-M-Y');
				$data['no_pr']=$key['no_pr'];
				$data['sts']=$key['status'];

				$this->load->view('page/vw_detailapproved_pr',$data);
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
				$id['no_pr'] = $this->input->post('no_pr');
				$d_status['status'] = '1';
				
				$this->app_model->updateData("mst.tt_purchase_request",$d_status,$id);

				$total = $this->cart->total_items('rowid');
				$totalbrs = $this->input->post('totbaris');
				$item = $this->input->post('rowid');
				$kd_produk = $this->input->post('kd_produk');
				$qty = $this->input->post('qty');
				$status=$this->input->post('status');
				
				for($i=0;$i < $totalbrs;$i++)
				{
					// if ($status[$i]=="on")
						// {$status[$i]="A";}
					// else
						// {$status[$i]="N";}
					// $data = array(
					// 'rowid' => $item[$i],
					// 'qty'   => $qty[$i],
					// 'status'   => $bstatus[$i],
					// 'options'   => array('statusdtl' => '0')
					//);
					//$this->cart->update_options($data);
					$this->app_model->manualquery(" update mst.tt_dtl_purchase_request set 
													qty = '".$qty[$i]."' , 
													status = '".$status[$i]."'
													where no_pr='".$id['no_pr']."' 
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
				$id['no_pr'] = $this->uri->segment(3);
				$d_status['status'] = '4';
				$d_statusdtl['status'] = 'N';
				
				$this->app_model->updateData("mst.tt_purchase_request",$d_status,$id);
				$this->app_model->updateData("mst.tt_dtl_purchase_request",$d_statusdtl,$id);

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

