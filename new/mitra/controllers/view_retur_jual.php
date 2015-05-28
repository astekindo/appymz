<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class View_retur_jual extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('view_retur_jual_model');
    }
    public function get_data_rj($no_retur = ''){
		$data = $this->view_retur_jual_model->get_data_html($no_retur);
                
        $h = $data['header'];
        $d = $data['detail'];
		
		$header = '
			<table width="800" border="0" cellspacing="1" cellpadding="5">
				<tr>
					<td height="28" colspan="2" align="left" valign="middle" bgcolor="#ACD9EB">&nbsp;&nbsp;<b>RETUR JUAL FORM</b></td>
				</tr>
				<tr>
					<td height="28" width="100" align="right" valign="middle" bgcolor="#ACD9EB"><b>No. Retur&nbsp;&nbsp;</b></td>
					<td width="400" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->no_retur.'</td>
				</tr>
				<tr>
					<td height="28" width="100" align="right" valign="middle" bgcolor="#ACD9EB"><b>Tanggal&nbsp;&nbsp;</b></td>
					<td width="400" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->tgl_retur.'</td>
				</tr>
				<tr>
					<td height="28" width="100" align="right" valign="middle" bgcolor="#ACD9EB"><b>No Struk&nbsp;&nbsp;</b></td>
					<td width="400" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->no_so.'</td>
				</tr>
				<tr>
					<td height="28" width="100" align="right" valign="middle" bgcolor="#ACD9EB"><b>Remark&nbsp;&nbsp;</b></td>
					<td width="400" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->remark.'</td>
				</tr>
			</table><br>
			';
		
		echo $header;
		
		$detail = '
			<table width="800"  border="0" >
				<tr>
				<td cellspacing="1" cellpadding="5" height="28" width="30" align="center" bgcolor="#ACD9EB"><b>No.</b></td>
				<td width="200" align="center" bgcolor="#ACD9EB"><b>Kode Produk</b><br><b>(Kode Prod Supp)</b></td>
				<td width="320" align="center" bgcolor="#ACD9EB"><b>Nama Produk</b></td>
                                <td width="80" align="center" bgcolor="#ACD9EB"><b>Lokasi</b></td>
				<td width="60" align="center" bgcolor="#ACD9EB"><b>Qty Retur</b></td>
				<td align="center" bgcolor="#ACD9EB"><b>Harga</b></td>
                                <td align="center" bgcolor="#ACD9EB"><b>Diskon</b></td>
                                <td align="center" bgcolor="#ACD9EB"><b>Ekstra Diskon</b></td>
                                <td align="center" bgcolor="#ACD9EB"><b>Total</b></td>
                                
				</tr>
		';
		
		echo $detail;
		
		$no = 1;
		$qty = 0;
                $total = 0;
		foreach($d as $v){
			echo '
				<tr>
				<td cellspacing="1" cellpadding="5" height="28" width="30" align="center" bgcolor="#f5f5f5">'.$no.'</td>
				<td width="100" align="center" bgcolor="#f5f5f5">'.$v->kd_produk.'<br>('.$v->kd_produk_supp.')</td>
				<td width="320" align="left" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$v->nama_produk.'</td>
                                <td width="300" align="center" bgcolor="#f5f5f5">'.$v->lokasi.'</td>
				<td width="60" align="center" bgcolor="#f5f5f5">'.$v->qty.'</td>
				<td width="100" align="center" bgcolor="#f5f5f5">'.number_format($v->rp_jumlah,0,',','.').'</td>	
                                <td width="100" align="center" bgcolor="#f5f5f5">'.number_format($v->rp_disk,0,',','.').'</td>	
                                <td width="100" align="center" bgcolor="#f5f5f5">'.number_format($v->rp_potongan,0,',','.').'</td>
                                <td width="100" align="center" bgcolor="#f5f5f5">'.number_format($v->rp_total,0,',','.').'</td>
                               
				</tr>
			';
			$qty = $qty + $v->qty;
                        $total = $total + $v->rp_total;
			$no++;
		}
		
		echo '
				<tr>
				<td colspan="3" height="28" align="right" bgcolor="#f5f5f5"><b>Total</b>&nbsp;&nbsp;</td>
                                <td align="center" bgcolor="#f5f5f5"></td>
				<td align="center" bgcolor="#f5f5f5"><b>'.$qty.'</b></td>
				<td align="left" bgcolor="#f5f5f5"></td>
				<td align="center" bgcolor="#f5f5f5"></td>
                                <td align="center" bgcolor="#f5f5f5"></td>
                                <td align="center" bgcolor="#f5f5f5"><b>'.number_format($total,0,',','.').'</b></td>
                                
				</tr>
			';
		echo '</table><br>';
                 echo '<table width="800" border="0" cellspacing="1" cellpadding="5">
			  <tr>
				<td colspan="3" align="left" valign="top">
				<td width="370" valign="middle"><table width="370" border="0" cellspacing="1" cellpadding="5">
				 <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Jumlah Retur</b>&nbsp;&nbsp;</td>
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->rp_jumlah, 0,',','.').'</td>
					</tr>
				  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Diskon Tambahan</b>&nbsp;&nbsp;</td>
					<td valign="middle" align="right" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->pct_potongan, 0,',','.').' %</td>
					<td valign="middle" align="right" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->rp_potongan, 0,',','.').'</td>
					</tr>
				  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Grand Total</b>&nbsp;&nbsp;</td>
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->rp_total, 0,',','.').'</td>
					</tr>
				  
				</table></td>
			  </tr>
			</table>';
	}
        
    public function search_noretur() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->view_retur_jual_model->search_noretur($search, $start, $limit);


        echo $result;
    }

    public function search_produk() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->view_retur_jual_model->search_produk($search, $start, $limit);


        echo $result;
    }

    public function search_member() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->view_retur_jual_model->search_member($search, $start, $limit);


        echo $result;
    }

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';
        $tglAwal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal', TRUE)) : '';
        $tglAkhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir', TRUE)) : '';
        $no_retur = isset($_POST['no_retur']) ? $this->db->escape_str($this->input->post('no_retur', TRUE)) : '';
        $no_so = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so', TRUE)) : '';
        $kd_member = isset($_POST['kd_member']) ? $this->db->escape_str($this->input->post('kd_member', TRUE)) : '';
        if ($tglAwal) {
            $tglAwal = date('Y-m-d', strtotime($tglAwal));
        }
        if ($tglAkhir) {
            $tglAkhir = date('Y-m-d', strtotime($tglAkhir));
        }

        $result = $this->view_retur_jual_model->get_rows($kd_produk, $tglAwal, $tglAkhir, $no_retur, $no_so, $kd_member, $search, $start, $limit);

        echo $result;
    }


}

?>
