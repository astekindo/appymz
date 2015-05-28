<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cetak_faktur_penjualan extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('cetak_faktur_penjualan_model', 'cfp_model');
        $this->load->model('faktur_penjualan_model', 'fj_model');
        }
    public function search_salesorder() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->cfp_model->search_salesorder($search, $start, $limit);


        echo $result;
    }
    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $tgl_awal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal', TRUE)) : '';
        $tgl_akhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir', TRUE)) : '';
        $no_so = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so', TRUE)) : '';
        $kd_pelanggan = isset($_POST['kd_pelanggan']) ? $this->db->escape_str($this->input->post('kd_pelanggan', TRUE)) : '';
        if ($tgl_awal) {
            $tglAwal = date('Y-m-d', strtotime($tgl_awal));
        }
        if ($tgl_akhir) {
            $tglAkhir = date('Y-m-d', strtotime($tgl_akhir));
        }
        $result = $this->cfp_model->get_rows($tglAwal,$tglAkhir,$no_so,$kd_pelanggan, $search, $start, $limit);

        echo $result;
    }
    public function get_rows_detail($no_faktur = '') {
        $hasil = $this->cfp_model->get_rows_detail($no_faktur);
        echo '{success:true,data:'.json_encode($hasil).'}';
        //echo $result;
    }
    public function get_data_faktur($no_faktur = ''){
		$data = $this->fj_model->get_data_print($no_faktur);
                
                $h = $data['header'];
                $d = $data['detail'];
                $db = $data['detail_bayar'];
                
                $detail = '<table width="920"  border="0" >';
		$detail .= '<tr>
						<td cellspacing="1" cellpadding="5" height="28" width="30" align="center" bgcolor="#ACD9EB"><b>No.</b></td>
						<td width="130" align="center" bgcolor="#ACD9EB">&nbsp;&nbsp;<b>No SJ</b></td>
                                                <td width="200" align="center" bgcolor="#ACD9EB"><b>Nama Barang</b></td>
						<td width="80" align="center" bgcolor="#ACD9EB"><b>&nbsp;&nbsp;Qty</b></td>
						<td width="80" align="center" bgcolor="#ACD9EB"><b>Satuan&nbsp;&nbsp;</b></td>
						<td width="100" align="center" bgcolor="#ACD9EB"><b>Harga Jual</b></td>
						<td width="100" align="center" bgcolor="#ACD9EB"><b>Total Diskon</b></td>
						<td width="100" align="center" bgcolor="#ACD9EB"><b>Harga Jual Net</b></td>
                                                <td width="100" align="center" bgcolor="#ACD9EB"><b>Total</b></td>
					</tr>';
		if(!empty($d))
		{
			$no = 1;
			$bayar = 0;
                        $total_tagihan = 0;	
			foreach($d as $v)
			{
				if($v->tgl_faktur){
                                        $tgl_faktur = date('d-m-Y', strtotime($v->tgl_faktur));
                                }
                                $harga_jual_net = $v->rp_harga_jual - $v->rp_total_diskon;
                                $sisa_invoice = $v->rp_total - $v->rp_pelunasan_hutang;
                                $total_tagihan = $h->rp_faktur - $h->rp_uang_muka - $h->cash_diskon;
                                $dpp = ($h->rp_faktur - $h->rp_uang_muka) / 1.1;
				$detail .= '<tr>
								<td align="center" bgcolor="#f5f5f5">'.$no.'</td>
								<td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$v->no_sj .'<br>&nbsp;&nbsp;</td>
                                                                <td align="center" bgcolor="#f5f5f5">'.$v->nama_produk.'</td>
                                                                <td align="center" bgcolor="#f5f5f5">'.$v->qty.'</td>
                                                                <td align="center" bgcolor="#f5f5f5">'.$v->nm_satuan.'</td>
                                                                <td align="right" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($v->rp_harga_jual, 0,',','.').'</td>
								<td align="right" bgcolor="#f5f5f5">'.number_format($v->rp_total_diskon, 0,',','.').'&nbsp;&nbsp;</td>
								<td align="right" bgcolor="#f5f5f5">'.number_format($harga_jual_net, 0,',','.').'&nbsp;&nbsp;</td>
                                                                <td align="right" bgcolor="#f5f5f5">'.number_format($v->rp_jumlah, 0,',','.').'&nbsp;&nbsp;</td>
                                                                											
							</tr>
								
				
				';			
										$no++;
										$bayar = $bayar + $v->rp_bayar;
                                                                                
	
			}
			
			
		}
		else
		{
			$detail .= '<tr><td>-----</td></tr>';			
		}
		
		$detail .= '</table>';

		if($h->tgl_faktur){
			$tanggal = date('d-m-Y', strtotime($h->tgl_faktur));
		}

		if($h->tgl_faktur_pajak){
			$tgl_faktur_pajak = date('d-m-Y', strtotime($h->tgl_faktur_pajak));
		}
                if($h->tgl_jatuh_tempo){
			$tgl_jth_tempo = date('d-m-Y', strtotime($h->tgl_jatuh_tempo));
		}
                
		$header = '
			<table width="920" border="0" cellspacing="1" cellpadding="5">
			  <tr>
				<td height="28" colspan="4" align="left" valign="middle" bgcolor="#ACD9EB">&nbsp;&nbsp;<b>'.$h->title.'</b></td>
			  </tr>
			  <tr>
				<td height="28" width="40" align="right" valign="middle" bgcolor="#ACD9EB"><b>No.Faktur&nbsp;&nbsp;</b></td>
				<td width="300" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->no_faktur.'</td>
				
			  </tr>
			  <tr>
				<td height="28" width="40" align="right" valign="middle" bgcolor="#ACD9EB"><b>No.SO&nbsp;&nbsp;</b></td>
				<td width="300" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->no_so.'</td>
				
			  </tr>
                           <tr>
				<td  height="28" width="40"  align="right" valign="middle" bgcolor="#ACD9EB"><b>Jatuh Tempo&nbsp;&nbsp;</b></td>
				<td  width="300" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$tgl_jth_tempo.'</b></td>
			  </tr>
                           <tr>
				<td  height="28" width="40"  align="right" valign="middle" bgcolor="#ACD9EB"><b>Termin Pembayaran&nbsp;&nbsp;</b></td>
				<td  width="300" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->top.' Hari</b></td>
			  </tr>
			 <tr>
				<td  height="28" width="40"  align="right" valign="middle" bgcolor="#ACD9EB"><b>Nama Salesman&nbsp;&nbsp;</b></td>
				<td  width="300" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->nama_sales.'</b></td>
			  </tr>
			 
			</table>
		';
		
		$summary = '
			<table width="920" border="0" cellspacing="1" cellpadding="5">
			  <tr>
				<td colspan="3" align="left" valign="top">
				<td width="370" valign="middle"><table width="370" border="0" cellspacing="1" cellpadding="5">
				  
				  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Total </b>&nbsp;&nbsp;</td>
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->rp_faktur, 0,',','.').'</td>
					</tr>
                                  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Uang Muka </b>&nbsp;&nbsp;</td>
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->rp_uang_muka, 0,',','.').'</td>
					</tr>
                                   
                                  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Total Net </b>&nbsp;&nbsp;</td>
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->rp_faktur - $h->rp_uang_muka, 0,',','.').'</td>
					</tr>
                                   <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Cash Diskon </b>&nbsp;&nbsp;</td>
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->cash_diskon, 0,',','.').'</td>
					</tr>
				  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Total Tagihan </b>&nbsp;&nbsp;</td>
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($total_tagihan, 0,',','.').'</td>
					</tr>
				  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>DPP </b>&nbsp;&nbsp;</td>
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($dpp, 0,',','.').'</td>
					</tr>
                                   <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>PPN </b>&nbsp;&nbsp;</td>
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->rp_ppn, 0,',','.').'</td>
					</tr>
				</table></td>
			  </tr>
			</table>
		';
		
		$html = '
		<table width="100%" border="0" cellspacing="5" cellpadding="1">			
			<tr>
				<td>
				<table cellspacing="1" style="text-align:left" >					
					<tr>
						<td colspan="2">' . $header . '</td>
					</tr>
					<tr>
						<td colspan="2">' . $detail . '</td>
					</tr>
                                        <tr>
						<td colspan="2">' . $summary . '</td>
					</tr>
					<tr>
						<td colspan="2"></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
               ';
                
                
		 echo $html;
	}
}
