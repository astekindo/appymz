<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Monitoring_purchase_request extends MY_Controller {
    /**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('monitoring_pr_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	
	public function get_data_pr($no_pr = ''){
		$data = $this->monitoring_pr_model->get_data_html($no_pr);
                
        $h = $data['header'];
        $d = $data['detail'];
		
		$header = '
			<table width="500" border="0" cellspacing="1" cellpadding="5">
				<tr>
					<td height="28" colspan="2" align="left" valign="middle" bgcolor="#ACD9EB">&nbsp;&nbsp;<b>PURCHASE REQUEST FORM</b></td>
				</tr>
				<tr>
					<td height="28" width="100" align="right" valign="middle" bgcolor="#ACD9EB"><b>No. PR&nbsp;&nbsp;</b></td>
					<td width="400" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->no_ro.'</td>
				</tr>
				<tr>
					<td height="28" width="100" align="right" valign="middle" bgcolor="#ACD9EB"><b>Tanggal&nbsp;&nbsp;</b></td>
					<td width="400" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->tgl_ro.'</td>
				</tr>
				<tr>
					<td height="28" width="100" align="right" valign="middle" bgcolor="#ACD9EB"><b>Supplier&nbsp;&nbsp;</b></td>
					<td width="400" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->nama_supplier.'</td>
				</tr>
				<tr>
					<td height="28" width="100" align="right" valign="middle" bgcolor="#ACD9EB"><b>Alamat&nbsp;&nbsp;</b></td>
					<td width="400" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->alamat.'</td>
				</tr>
			</table><br>
			';
		
		echo $header;
		
		$detail = '
			<table width="800"  border="0" >
				<tr>
				<td cellspacing="1" cellpadding="5" height="28" width="30" align="center" bgcolor="#ACD9EB"><b>No.</b></td>
				<td width="80" align="center" bgcolor="#ACD9EB"><b>Kode Barang</b></td>
				<td width="320" align="center" bgcolor="#ACD9EB"><b>Nama Barang</b></td>
				<td width="60" align="center" bgcolor="#ACD9EB"><b>Qty</b></td>
				<td width="80" align="center" bgcolor="#ACD9EB"><b>Satuan</b></td>
				<td align="center" bgcolor="#ACD9EB"><b>Keterangan</b></td>		
				</tr>
		';
		
		echo $detail;
		
		$no = 1;
		$total = 0;
		foreach($d as $v){
                    $keterangan = '';
                    if($v->keterangan2 === ''){
                        $keterangan = $v->keterangan1;
                    }else{
                        $keterangan = $v->keterangan2;
                    }
                    
			echo '
				<tr>
				<td cellspacing="1" cellpadding="5" height="28" width="30" align="center" bgcolor="#f5f5f5">'.$no.'</td>
				<td width="100" align="center" bgcolor="#f5f5f5">'.$v->kd_produk.'</td>
				<td width="320" align="left" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$v->nama_produk.'</td>
				<td width="60" align="center" bgcolor="#f5f5f5">'.$v->qty_adj.'</td>
				<td width="80" align="center" bgcolor="#f5f5f5">'.$v->nm_satuan.'</td>
				<td align="center" bgcolor="#f5f5f5">'.$keterangan.'</td>	
				</tr>
			';
			$total = $total + $v->qty_adj;
			$no++;
		}
		
		echo '
				<tr>
				<td colspan="3" height="28" align="right" bgcolor="#f5f5f5"><b>Total</b>&nbsp;&nbsp;</td>
				<td align="center" bgcolor="#f5f5f5"><b>'.$total.'</b></td>
				<td align="left" bgcolor="#f5f5f5"></td>
				<td align="center" bgcolor="#f5f5f5"></td>
				<td align="center" bgcolor="#f5f5f5"></td>
	
				</tr>
			';
		echo '</table><br>';
	}
	
	public function get_rows(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
            $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
            $kdSupplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
            $tglAwal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal',TRUE)) : '';
            $tglAkhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir',TRUE)) : '';
            $status = isset($_POST['status']) ? $this->db->escape_str($this->input->post('status',TRUE)) : '';
            $close_pr = isset($_POST['close_pr']) ? $this->db->escape_str($this->input->post('close_pr',TRUE)) : '';
            $konsinyasi = isset($_POST['konsinyasi']) ? $this->db->escape_str($this->input->post('konsinyasi',TRUE)) : '';
            $peruntukan_sup = isset($_POST['peruntukan_sup']) ? $this->db->escape_str($this->input->post('peruntukan_sup',TRUE)) : '';
            $peruntukan_dist = isset($_POST['peruntukan_dist']) ? $this->db->escape_str($this->input->post('peruntukan_dist',TRUE)) : '';
		if($tglAwal){
			$tglAwal = date('Y-m-d', strtotime($tglAwal));
		}
		if($tglAkhir){
			$tglAkhir = date('Y-m-d', strtotime($tglAkhir));
		}
                if($peruntukan_sup == 'true'){
                    $peruntukan_sup = '0';
                }else{
                    $peruntukan_sup = '';
                }
                if($peruntukan_dist == 'true'){
                    $peruntukan_dist = '1';
                }else{
                    $peruntukan_dist = '';
                }
		
        $result = $this->monitoring_pr_model->get_rows($kdSupplier, $tglAwal, $tglAkhir, $status, $close_pr, $konsinyasi,$peruntukan_sup,$peruntukan_dist, $search, $start, $limit);
        
        echo $result;
	}
}
