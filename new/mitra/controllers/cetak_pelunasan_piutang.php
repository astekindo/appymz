<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cetak_pelunasan_piutang extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('cetak_pelunasan_piutang_model', 'cpp_model');
        $this->load->model('penjualan_pelunasan_piutang_model', 'ppp_model');
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $tgl_awal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal', TRUE)) : '';
        $tgl_akhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir', TRUE)) : '';
         $no_so = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so', TRUE)) : '';
        if ($tgl_awal) {
            $tglAwal = date('Y-m-d', strtotime($tgl_awal));
        }
        if ($tgl_akhir) {
            $tglAkhir = date('Y-m-d', strtotime($tgl_akhir));
        }
        $result = $this->cpp_model->get_rows($tglAwal,$tglAkhir,$no_so, $search, $start, $limit);

        echo $result;
    }

    public function get_rows_detail($no_bukti = '') {
        $hasil = $this->cpp_model->get_rows_detail($no_bukti);
        $results = array();
		foreach($hasil as $result){
			//hitung diskon
			$diskon = 0;
                        $diskon1 = '0%';
			$diskon2 = '0%';
			$diskon3 = '0%';
			$diskon4 = '0%';
                        if($result->disk_amt_supp1 > 0)
				{
                                    $diskon1 = number_format($result->disk_amt_supp1, 0,',','.');
				}	
				else
				{
					//$diskon1 = number_format($v->disk_amt_supp1_po, 0,',','.');
                                    $diskon1 = $result->disk_persen_supp1 . '%';
				}
				if($result->disk_amt_supp2 > 0)
				{
                                    $diskon2 = number_format($result->disk_amt_supp2, 0,',','.');
                                }	
				else
				{
					$diskon2 = $result->disk_persen_supp2 . '%';	
				}
				if($result->disk_amt_supp3 > 0)
				{
                                    $diskon3 = number_format($result->disk_amt_supp3, 0,',','.');
                                    			
				}	
				else
				{
					$diskon3 = $result->disk_persen_supp3 . '%';	
				}
				if($result->disk_amt_supp4 > 0)
				{
                                    $diskon4 = number_format($result->disk_amt_supp4, 0,',','.');
                                    			
				}	
				else
				{
					$diskon4 = $result->disk_persen_supp4 . '%';	
				}
			$diskon5 = number_format($result->disk_amt_supp5, 0,',','.');
			//diskon Rp
			$result->disk_grid_supp1 = $diskon1;
			$result->disk_grid_supp2 = $diskon2;
			$result->disk_grid_supp3 = $diskon3;
			$result->disk_grid_supp4 = $diskon4;
			$result->disk_grid_supp5 = $diskon5;
			
			
			$dpp_po = ($result->dpp_po) * $result->qty_terima;
			$rp_total_po = $dpp_po;
			//($result->dpp_po) - $diskon;
			$harga_net = $result->pricelist - $result->rp_disk_po;
                        $result->harga_net= $harga_net;
                        $harga_net_ect = $harga_net / 1.1;
                        $result->harga_net_ect= $harga_net_ect;
			$result->dpp_po = $dpp_po;
			$result->rp_total_po = $rp_total_po;
			$results[] = $result;
                        //print_r($results[]);
		}
		echo '{success:true,data:'.json_encode($results).'}';
        //echo $result;
    }
    public function print_form($no_bukti = ''){
		$data = $this->ppp_model->get_data_print($no_bukti);
                if (!$data)
                    show_404('page');

                $this->output->set_content_type("application/pdf");
                require_once(APPPATH . 'libraries/PelunasanPiutangPrint.php');
                $pdf = new PelunasanPiutangPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->setKertas();
                $pdf->privateData($data['header'], $data['detail'], $data['detail_bayar']);
                $pdf->Output();
                exit;
    }
    
    public function get_data_pembayaran($no_bukti = ''){
		$data = $this->ppp_model->get_data_print($no_bukti);
                
                $h = $data['header'];
                $d = $data['detail'];
                $db = $data['detail_bayar'];
                
                $detail = '<table width="800"  border="0" >';
		$detail .= '<tr>
						<td cellspacing="1" cellpadding="5" height="28" width="30" align="center" bgcolor="#ACD9EB"><b>No.</b></td>
						<td width="130" align="left" bgcolor="#ACD9EB">&nbsp;&nbsp;<b>No Faktur</b></td>
                                                <td width="100" align="center" bgcolor="#ACD9EB"><b>Tanggal Faktur</b></td>
						<td width="100" align="center" bgcolor="#ACD9EB"><b>&nbsp;&nbsp;Jumlah Faktur</b></td>
						<td width="100" align="right" bgcolor="#ACD9EB"><b>Jumlah Bayar&nbsp;&nbsp;</b></td>
						<td width="100" align="center" bgcolor="#ACD9EB"><b>Total Bayar</b></td>
						<td width="100" align="center" bgcolor="#ACD9EB"><b>Rp Sisa Bayar</b></td>
												
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
                                $sisa_invoice = $v->rp_total - $v->rp_pelunasan_hutang;
				$detail .= '<tr>
								<td align="center" bgcolor="#f5f5f5">'.$no.'</td>
								<td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$v->no_faktur .'<br>&nbsp;&nbsp;</td>
                                                                <td align="center" bgcolor="#f5f5f5">'.$tgl_faktur.'</td>
                                                                <td align="right" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($v->rp_faktur, 0,',','.').'</td>
								<td align="right" bgcolor="#f5f5f5">'.number_format($v->rp_bayar, 0,',','.').'&nbsp;&nbsp;</td>
								<td align="right" bgcolor="#f5f5f5">'.number_format($v->rp_total_bayar, 0,',','.').'&nbsp;&nbsp;</td>
                                                                <td align="right" bgcolor="#f5f5f5">'.number_format($v->rp_kurang_bayar, 0,',','.').'&nbsp;&nbsp;</td>
                                                                											
							</tr>
								
				
				';			
										$no++;
										$bayar = $bayar + $v->rp_bayar;
                                                                                $total_tagihan = $total_tagihan + $v->rp_jumlah;
	
			}
			
                        
			
			$detail .= '<tr>
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
				<td height="28" width="40" align="right" valign="middle" bgcolor="#ACD9EB"><b>No.Bukti&nbsp;&nbsp;</b></td>
				<td width="300" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->no_pelunasan_piutang.'</td>
				
			  </tr>
			  
                           <tr>
				<td  height="28" width="40"  align="right" valign="middle" bgcolor="#ACD9EB"><b>Tanggal Pelunasan&nbsp;&nbsp;</b></td>
				<td  width="300" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$tanggal.'</b></td>
			  </tr>
                           <tr>
				<td height="28" width="40" align="right" valign="middle" bgcolor="#ACD9EB"><b>Keterangan&nbsp;&nbsp;</b></td>
				<td valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->keterangan.'</td>
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
    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
}
