<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Monitoring_purchase_order extends MY_Controller {
    /**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('monitoring_po_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
         public function get_data_po($no_po = ''){
             
                $data = $this->monitoring_po_model->get_data_html($no_po);
                
                $h = $data['header'];
                $d = $data['detail'];
                 
		$detail = '<table width="1000"  border="0" >';
		$detail .= '<tr>
						<td cellspacing="1" cellpadding="5" height="28" width="30" align="center" bgcolor="#ACD9EB"><b>No.</b></td>
						<td width="100" align="left" bgcolor="#ACD9EB">&nbsp;&nbsp;<b>No. PR</b></td>
						<td width="90" align="left" bgcolor="#ACD9EB">&nbsp;&nbsp;<b>Kode</b></td>
						<td width="300" align="left" bgcolor="#ACD9EB"><b>&nbsp;&nbsp;Nama Barang</b></td>
						<td width="50" align="right" bgcolor="#ACD9EB"><b>Qty&nbsp;&nbsp;</b></td>
						<td width="60" align="center" bgcolor="#ACD9EB"><b>Satuan</b></td>					
						<td width="70" align="right" bgcolor="#ACD9EB"><b>Harga Supplier</b>&nbsp;&nbsp;</td>
						<td width="30" align="center" bgcolor="#ACD9EB"><b>Disk. 1</b></td>
						<td width="30" align="center" bgcolor="#ACD9EB"><b>Disk. 2</b></td>
						<td width="30" align="center" bgcolor="#ACD9EB"><b>Disk. 3</b></td>
						<td width="30" align="center" bgcolor="#ACD9EB"><b>Disk. 4</b></td>
						<td width="30" align="center" bgcolor="#ACD9EB"><b>Disk. 5</b></td>
						<td width="60" align="center" bgcolor="#ACD9EB"><b>Total Diskon</b></td>
						<td width="70" align="right" bgcolor="#ACD9EB"><b>Harga Net</b>&nbsp;&nbsp;</td>
						<td width="70" align="right" bgcolor="#ACD9EB"><b>Harga Nett(Exc.)</b>&nbsp;&nbsp;</td>
						<td width="70" align="right" bgcolor="#ACD9EB"><b>Jumlah</b>&nbsp;&nbsp;</td>
					</tr>';
		if(!empty($d))
		{
			$no = 1;
			$sum_qty = 0;		
			foreach($d as $v)
			{
				$diskon1 = '0%';
				$diskon2 = '0%';
				$diskon3 = '0%';
				$diskon4 = '0%';
				if($v->disk_persen_supp1_po > 0)
				{
					$diskon1 = $v->disk_persen_supp1_po . '%';				
				}	
				else
				{
					//$diskon1 = number_format($v->disk_amt_supp1_po, 0,',','.');
                                    $diskon1 = $v->disk_amt_supp1_po;
				}
				if($v->disk_persen_supp2_po > 0)
				{
					$diskon2 = $v->disk_persen_supp2_po . '%';				
				}	
				else
				{
					$diskon2 = number_format($v->disk_amt_supp2_po, 0,',','.');
				}
				if($v->disk_persen_supp3_po > 0)
				{
					$diskon3 = $v->disk_persen_supp3_po . '%';				
				}	
				else
				{
					$diskon3 = number_format($v->disk_amt_supp3_po, 0,',','.');
				}
				if($v->disk_persen_supp4_po > 0)
				{
					$diskon4 = $v->disk_persen_supp4_po . '%';				
				}	
				else
				{
					$diskon4 = number_format($v->disk_amt_supp4_po, 0,',','.');
				}
					
				$detail .= '<tr>
								<td align="center" bgcolor="#f5f5f5">'.$no.'</td>
								<td align="left" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$v->kd_produk .'<br>&nbsp;&nbsp;('.$v->kd_produk_lama .')</td>
								<td align="left" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$v->kd_produk_supp .'</td>
								<td align="left" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$v->nama_produk.'</td>
								<td align="right" bgcolor="#f5f5f5">'.number_format($v->qty_po, 0,',','.').'&nbsp;&nbsp;</td>
								<td align="center" bgcolor="#f5f5f5">'.$v->nm_satuan.'</td>
								<td align="right" bgcolor="#f5f5f5">'.number_format($v->price_supp_po, 0,',','.').'&nbsp;&nbsp;</td>
								<td align="center" bgcolor="#f5f5f5">'.$diskon1.'</td>
								<td align="center" bgcolor="#f5f5f5">'.$diskon2.'&nbsp;&nbsp;</td>
								<td align="center" bgcolor="#f5f5f5">'.$diskon3.'</td>
								<td align="center" bgcolor="#f5f5f5">'.$diskon4.'</td>
								<td align="center" bgcolor="#f5f5f5">'.number_format($v->disk_amt_supp5_po, 0,',','.').'</td>
								<td align="center" bgcolor="#f5f5f5">'.number_format($v->rp_disk_po, 0,',','.').'</td>
								<td align="right" bgcolor="#f5f5f5">'.number_format($v->net_price_po, 0,',','.').'&nbsp;&nbsp;</td>
								<td align="right" bgcolor="#f5f5f5">'.number_format($v->dpp_po, 0,',','.') .'&nbsp;&nbsp;</td>
								<td align="right" bgcolor="#f5f5f5">'.number_format($v->rp_total_po, 0,',','.') .'&nbsp;&nbsp;</td>								
							</tr>
								
				
				';			
										$no++;
										$sum_qty = $sum_qty + $v->qty_po;
	
			}
			

			
			$detail .= '<tr>
							<td height="28" colspan="3" align="center" bgcolor="#f5f5f5">&nbsp;</td>
							<td align="right" bgcolor="#f5f5f5"><b>Total</b>&nbsp;&nbsp;</td>
							<td align="left" bgcolor="#f5f5f5">&nbsp;&nbsp;<b>'. number_format($sum_qty, 0,',','.') .'</b></td>
							<td colspan="10" align="right" bgcolor="#f5f5f5"><b>Total</b>&nbsp;&nbsp;</td>
							<td align="right" bgcolor="#f5f5f5"><b>'.number_format($h->rp_jumlah_po, 0,',','.').'</b>&nbsp;&nbsp;</td>
							<td align="center" bgcolor="#f5f5f5"></td>
						</tr>';

			
		}
		else
		{
			$detail .= '<tr><td>-----</td></tr>';			
		}
		
		$detail .= '</table>';

		if($h->tanggal_po){
			$tanggal_po = date('d-m-Y', strtotime($h->tanggal_po));
		}

		if($h->tgl_berlaku_po){
			$tgl_berlaku_po = date('d-m-Y', strtotime($h->tgl_berlaku_po));
		}	

		$header = '
			<table width="1000" border="0" cellspacing="1" cellpadding="5">
			  <tr>
				<td height="28" colspan="4" align="left" valign="middle" bgcolor="#ACD9EB">&nbsp;&nbsp;<b>'.$h->title.'</b></td>
			  </tr>
			  <tr>
				<td height="28" width="131" align="right" valign="middle" bgcolor="#ACD9EB"><b>No. PO&nbsp;&nbsp;</b></td>
				<td width="300" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->no_po.'</td>
				<td  width="131" align="right" valign="middle" bgcolor="#ACD9EB"><b>Tanggal PO&nbsp;&nbsp;</b></td>
				<td  width="300" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$tanggal_po.'</b></td>
			  </tr>
			  <tr>
				<td height="28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Nama Supplier&nbsp;&nbsp;</td>
				<td valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->nama_supplier.'</b></td>
				<td align="right" valign="middle" bgcolor="#ACD9EB"><b>Dibuat Oleh&nbsp;&nbsp;</b></td>
				<td valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->order_by_po.'</td>
			  </tr>
			  <tr>
				<td height="28" align="right" valign="middle" bgcolor="#ACD9EB"><b>NPWP&nbsp;&nbsp;</b></td>
				<td valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->npwp.'</td>
				<td align="right" valign="middle" bgcolor="#ACD9EB"><b>TOP&nbsp;&nbsp;</b></td>
				<td valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->top.' Hari</td>
			  </tr>
			  <tr>
				<td height="28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Kepada&nbsp;&nbsp;</b></td>
				<td valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->pic.'</td>
				<td align="right" valign="middle" bgcolor="#ACD9EB"><b>Masa Berlaku&nbsp;&nbsp;</b></td>
				<td valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$tgl_berlaku_po.'</td>
			  </tr>
			  <tr>
				<td height="28" align="right" valign="middle" bgcolor="#ACD9EB"><b>No. Telp&nbsp;&nbsp;</b></td>
				<td valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->telpon.'</td>
				<td align="right" valign="middle" bgcolor="#ACD9EB"><b>Kirim Ke&nbsp;&nbsp;</b></td>
				<td valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->kirim_po.'</td>
			  </tr>
			  <tr>
				<td height="28" align="right" valign="middle" bgcolor="#ACD9EB"><b>No. Fax&nbsp;&nbsp;</b></td>
				<td valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->fax.'</td>
				<td rowspan="2" align="right" valign="top" bgcolor="#ACD9EB"><b>Alamat&nbsp;&nbsp;</b></td>
				<td rowspan="2" valign="top" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->alamat_kirim_po.'</td>
			  </tr>
			  <tr>
				<td height="28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Email&nbsp;&nbsp;</b></td>
				<td valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->email.'</td>
			  </tr>
			</table>
		';
		
		$summary = '
			<table width="1000" border="0" cellspacing="1" cellpadding="5">
			  <tr>
				<td colspan="3" align="left" valign="top">
				<td width="530" valign="middle"><table width="530" border="0" cellspacing="1" cellpadding="5">
				  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Diskon Tambahan</b>&nbsp;&nbsp;</td>
					<td width="122" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->rp_jumlah_po / $h->rp_diskon_po, 0,',','.').' %</td>
					<td width="288" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->rp_diskon_po, 0,',','.').'</td>
					</tr>
				  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Total Tagihan</b>&nbsp;&nbsp;</td>
					<td colspan="2" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->rp_jumlah_po - $h->rp_diskon_po, 0,',','.').'</td>
					</tr>
				  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>PPN</b>&nbsp;&nbsp;</td>
					<td valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->ppn_percent_po, 0,',','.').' %</td>
					<td valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->rp_ppn_po, 0,',','.').'</td>
					</tr>
				  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Grand Total</b>&nbsp;&nbsp;</td>
					<td colspan="2" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->rp_total_po, 0,',','.').'</td>
					</tr>
				  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>DP</b>&nbsp;&nbsp;</td>
					<td colspan="2" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->rp_dp, 0,',','.').'</td>
					</tr>
				  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Sisa Bayar</b>&nbsp;&nbsp;</td>
					<td colspan="2" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.number_format($h->rp_total_po - $h->rp_dp, 0,',','.').'</td>
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
				</table>
				</td>
			</tr>
		</table>';
                
                
		 echo $html;
	}
    
	public function get_rows(){
	$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
	$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
        $kdSupplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
        $tglAwal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal',TRUE)) : '';
        $tglAkhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir',TRUE)) : '';
        $approval_po = isset($_POST['status']) ? $this->db->escape_str($this->input->post('status',TRUE)) : '';
        $close_po = isset($_POST['close_ro']) ? $this->db->escape_str($this->input->post('close_ro',TRUE)) : '';
        $konsinyasi = isset($_POST['konsinyasi']) ? $this->db->escape_str($this->input->post('konsinyasi',TRUE)) : '';
		if($tglAwal){
			$tglAwal = date('Y-m-d', strtotime($tglAwal));
		}
		if($tglAkhir){
			$tglAkhir = date('Y-m-d', strtotime($tglAkhir));
		}
		
        $result = $this->monitoring_po_model->get_rows($kdSupplier, $tglAwal, $tglAkhir, $approval_po, $close_po, $konsinyasi, $search, $start, $limit);
        
        echo $result;
	}
}
