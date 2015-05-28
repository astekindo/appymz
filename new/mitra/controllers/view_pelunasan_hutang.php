<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class View_pelunasan_hutang extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('view_pelunasan_hutang_model');
    }
    public function get_data_pelunasan($no_bukti = ''){
		$data = $this->view_pelunasan_hutang_model->get_data_html($no_bukti);
                
                $h = $data['header'];
                $d = $data['detail'];
                $db = $data['detail_bayar'];
                
		$detail = '<table width="800"  border="0" >';
		$detail .= '<tr>
						<td cellspacing="1" cellpadding="5" height="28" width="30" align="center" bgcolor="#ACD9EB"><b>No.</b></td>
						<td width="100" align="left" bgcolor="#ACD9EB">&nbsp;&nbsp;<b>No Invoice</b></td>
                                                <td width="100" align="center" bgcolor="#ACD9EB"><b>Tanggal Invoice</b></td>
						<td width="100" align="center" bgcolor="#ACD9EB"><b>&nbsp;&nbsp;No Bukti Supplier</b></td>
						<td width="100" align="right" bgcolor="#ACD9EB"><b>Rp Invoice&nbsp;&nbsp;</b></td>
						<td width="100" align="center" bgcolor="#ACD9EB"><b>Potongan</b></td>
						<td width="100" align="center" bgcolor="#ACD9EB"><b>Jumlah Bayar</b></td>
						<td width="100" align="center" bgcolor="#ACD9EB"><b>Total Bayar</b></td>
						<td width="100" align="center" bgcolor="#ACD9EB"><b>Rp Sisa Invoice</b></td>
						
					</tr>';
		if(!empty($d))
		{
			$no = 1;
			$bayar = 0;
                        $total_tagihan = 0;	
			foreach($d as $v)
			{
				if($v->tgl_invoice){
                                        $tgl_invoice = date('d-m-Y', strtotime($v->tgl_invoice));
                                }
                                $sisa_invoice = $v->rp_total - $v->rp_pelunasan_hutang;
				$detail .= '<tr>
								<td align="center" bgcolor="#f5f5f5">'.$no.'</td>
								<td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$v->no_invoice .'<br>&nbsp;&nbsp;</td>
                                                                <td align="center" bgcolor="#f5f5f5">'.$tgl_invoice.'</td>
                                                                <td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$v->no_bukti_supplier .'<br>&nbsp;&nbsp;</td>
								<td align="right" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($v->rp_total, 0,',','.').'</td>
								<td align="right" bgcolor="#f5f5f5">'.number_format($v->potongan, 0,',','.').'&nbsp;&nbsp;</td>
								<td align="right" bgcolor="#f5f5f5">'.number_format($v->rp_bayar, 0,',','.').'&nbsp;&nbsp;</td>
                                                                <td align="right" bgcolor="#f5f5f5">'.number_format($v->rp_pelunasan_hutang, 0,',','.').'&nbsp;&nbsp;</td>
                                                                <td align="right" bgcolor="#f5f5f5">'.number_format($sisa_invoice, 0,',','.').'&nbsp;&nbsp;</td>
															
							</tr>
								
				
				';			
										$no++;
										$bayar = $bayar + $v->rp_bayar;
                                                                                $total_tagihan = $total_tagihan + $v->rp_jumlah;
	
			}
			
                        
			
			$detail .= '<tr>
								<td align="center" bgcolor="#f5f5f5"></td>
								<td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;<br>&nbsp;&nbsp;</td>
                                                                <td align="center" bgcolor="#f5f5f5"></td>
                                                                <td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;<br>&nbsp;&nbsp;</td>
								<td align="right" bgcolor="#f5f5f5">&nbsp;&nbsp;</td>
								<td align="right" bgcolor="#f5f5f5">Total Bayar&nbsp;&nbsp;</td>
								<td align="right" bgcolor="#f5f5f5">'.number_format($bayar, 0,',','.').'&nbsp;&nbsp;</td>
                                                                <td align="right" bgcolor="#f5f5f5">&nbsp;&nbsp;</td>
                                                                <td align="right" bgcolor="#f5f5f5">&nbsp;&nbsp;</td>
															
							</tr>';

			
		}
		else
		{
			$detail .= '<tr><td>-----</td></tr>';			
		}
		
		$detail .= '</table>';

		if($h->tanggal){
			$tanggal = date('d-m-Y', strtotime($h->tanggal));
		}

		if($h->tgl_faktur_pajak){
			$tgl_faktur_pajak = date('d-m-Y', strtotime($h->tgl_faktur_pajak));
		}
                if($h->tgl_jth_tempo){
			$tgl_jth_tempo = date('d-m-Y', strtotime($h->tgl_jth_tempo));
		}

		$header = '
			<table width="800" border="0" cellspacing="1" cellpadding="5">
			  <tr>
				<td height="28" colspan="4" align="left" valign="middle" bgcolor="#ACD9EB">&nbsp;&nbsp;<b>'.$h->title.'</b></td>
			  </tr>
			  <tr>
				<td height="28" width="131" align="right" valign="middle" bgcolor="#ACD9EB"><b>No.Bukti&nbsp;&nbsp;</b></td>
				<td width="300" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->no_bukti.'</td>
				<td  width="131" align="right" valign="middle" bgcolor="#ACD9EB"><b>Tanggal Pelunasan&nbsp;&nbsp;</b></td>
				<td  width="300" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$tanggal.'</b></td>
			  </tr>
			  <tr>
				<td height="28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Nama Supplier&nbsp;&nbsp;</td>
				<td valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->nama_supplier.'</b></td>
				<td align="right" valign="middle" bgcolor="#ACD9EB"><b>Dibuat Oleh&nbsp;&nbsp;</b></td>
				<td valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->created_by.'</td>
			  </tr>
			 
			 
			</table>
		';
		$detailbayar = '<table width="800"  border="0" >';
                 $detailbayar .='<tr>
                                    <td height="28" colspan="4" align="left" valign="middle" bgcolor="#ACD9EB">&nbsp;&nbsp;<b>Detail Pembayaran</b></td>
                                </tr>';
		$detailbayar .= '<tr>
						<td cellspacing="1" cellpadding="5" height="28" width="30" align="center" bgcolor="#ACD9EB"><b>No.</b></td>
						<td width="100" align="left" bgcolor="#ACD9EB">&nbsp;&nbsp;<b>Jenis Pembayaran</b></td>
                                                <td width="100" align="center" bgcolor="#ACD9EB"><b>Jumlah Bayar</b></td>
						<td width="100" align="center" bgcolor="#ACD9EB"><b>&nbsp;&nbsp;No Bank</b></td>
						<td width="100" align="center" bgcolor="#ACD9EB"><b>No Warkat&nbsp;&nbsp;</b></td>
						<td width="100" align="center" bgcolor="#ACD9EB"><b>Tgl Jatuh Tempo</b></td>
						
					</tr>';
		if(!empty($db))
		{
			$no = 1;
			$total_bayar = 0;
                        $total_tagihan = 0;	
			foreach($db as $vb)
			{
				if($vb->tgl_jth_tempo){
                                            $tgl_jth_tempo = date('d-m-Y', strtotime($vb->tgl_jth_tempo));
                                    }
                               
				$detailbayar .= '<tr>
								<td align="center" bgcolor="#f5f5f5">'.$no.'</td>
								<td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$vb->nm_pembayaran  .'<br>&nbsp;&nbsp;</td>
                                                                <td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($vb->rp_bayar, 0,',','.').'</td>
								<td align="center" bgcolor="#f5f5f5">'.$vb->nomor_bank.'&nbsp;&nbsp;</td>
								<td align="center" bgcolor="#f5f5f5">'.$vb->nomor_ref.'&nbsp;&nbsp;</td>
                                                                <td align="center" bgcolor="#f5f5f5">'.$tgl_jth_tempo.'&nbsp;&nbsp;</td>
                                                                
															
							</tr>
								
				
				';			
										$no++;
										$total_bayar = $total_bayar + $vb->rp_bayar;
                                                                                $total_tagihan = $total_tagihan + $v->rp_jumlah;
	
			}
			
                        $detailbayar .= '<tr>
								<td align="center" bgcolor="#f5f5f5"></td>
								<td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;Total Bayar<br>&nbsp;&nbsp;</td>
                                                                <td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($total_bayar, 0,',','.').'</td>
								<td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;</td>
								<td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;</td>
                                                                <td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;</td>
                                                                
															
							</tr>';
			
			

			
		}
		else
		{
			$detailbayar .= '<tr><td>-----</td></tr>';			
		}
		
		$detailbayar .= '</table>';
		$summary = '
			<table width="800" border="0" cellspacing="1" cellpadding="5">
			  <tr>
				<td colspan="3" align="left" valign="top">
				<td width="370" valign="middle"><table width="370" border="0" cellspacing="1" cellpadding="5">
				  
				  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Total Invoice </b>&nbsp;&nbsp;</td>
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($v->rp_total_invoice, 0,',','.').'</td>
					</tr>
                                  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Total Potongan </b>&nbsp;&nbsp;</td>
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($v->rp_total_potongan, 0,',','.').'</td>
					</tr>
                                   
                                  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Total Pembayaran </b>&nbsp;&nbsp;</td>
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($v->rp_total_dibayar, 0,',','.').'</td>
					</tr>
                                   <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Selisih / Sisa </b>&nbsp;&nbsp;</td>
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($v->rp_total_invoice - $v->rp_total_potongan - $v->rp_total_dibayar , 0,',','.').'</td>
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
						<td colspan="2"></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
                <table width="100%" border="0" cellspacing="5" cellpadding="1">			
			<tr>
				<td>
				<table cellspacing="1" style="text-align:left" >					
					<tr>
						<td colspan="2"></td>
					</tr>
					<tr>
						<td colspan="2">' . $detailbayar . '</td>
					</tr>
					<tr>
						<td colspan="2"></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>';
                
                
		 echo $html;
	}
        
    public function search_nobukti() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_peruntukan = $this->session->userdata('user_peruntukan');
        $result = $this->view_pelunasan_hutang_model->search_nobukti($kd_peruntukan,$search, $start, $limit);


        echo $result;
    }

    public function search_produk() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->view_retur_beli_model->search_produk($search, $start, $limit);


        echo $result;
    }

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
       // $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';
        $tglAwal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal', TRUE)) : '';
        $tglAkhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir', TRUE)) : '';
        $no_bukti = isset($_POST['no_bukti']) ? $this->db->escape_str($this->input->post('no_bukti', TRUE)) : '';
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : '';
        
        if ($tglAwal) {
            $tglAwal = date('Y-m-d', strtotime($tglAwal));
        }
        if ($tglAkhir) {
            $tglAkhir = date('Y-m-d', strtotime($tglAkhir));
        }

        $result = $this->view_pelunasan_hutang_model->get_rows($tglAwal, $tglAkhir, $no_bukti, $kd_supplier, $search, $start, $limit);

        echo $result;
    }


}

?>
