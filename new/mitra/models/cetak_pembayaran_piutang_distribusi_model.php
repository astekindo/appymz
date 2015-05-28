<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cetak_pembayaran_piutang_distribusi_model
 *
 * @author Yakub
 */
class cetak_pembayaran_piutang_distribusi_model extends MY_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    public function getRows($offset, $limit, $tglAwal = "", $tglAkhir = "", $noFaktur = "", $search = "") {
        $sql_search = "";
        if ($tglAwal != "" && $tglAkhir != "") {
            $where .= " AND a.tgl_bayar between '$tglAwal' AND '$tglAkhir' ";
        }
        if ($noFaktur != "") {
            $where .= " AND b.no_faktur = '$noFaktur' ";
        }
        if ($search != "") {

            $sql_search = " AND (lower(a.no_pembayaran_piutang) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search);
        }
        $sql = "select distinct a.* ,b.no_faktur as nomor_faktur from sales.t_piutang_pembayaran a, sales.t_piutang_dist_detail b
                where a.no_pembayaran_piutang = b.no_pembayaran_piutang 
                " . $sql_search . "
		" . $where . "
		limit " . $limit . " offset " . $offset . "";

        $query = $this->db->query($sql);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        $this->db->flush_cache();
        $sql2 = "select count(*) as total from (select distinct a.* from sales.t_piutang_pembayaran a, sales.t_piutang_dist_detail b
                    where a.no_pembayaran_piutang = b.no_pembayaran_piutang
                    " . $sql_search . "
                    " . $where . "
                    ) as tabel limit 1";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        //$results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        $results = array(
            'success' => true,
            'record' => $total,
            'data' => $rows
        );
        return json_encode($results);
        //return var_dump($results);
    }

    public function getRowsDetail($noPembayaran = "") {

        $sql1 = "SELECT a.*, a.no_faktur as  nof ,b.* 
                FROM sales.t_piutang_dist_detail a , sales.t_piutang_pembayaran b
                WHERE a.no_pembayaran_piutang = b.no_pembayaran_piutang AND a.no_pembayaran_piutang='$noPembayaran'";


        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        //$results = '{success:true,record:' . $query->num_rows() . ',data:' . json_encode($rows) . '}';

        $results = array(
            'success' => true,
            'record' => $query->num_rows(),
            'data' => $rows
        );
        return json_encode($results);
    }

    public function getSalesOrderDistRows($offset, $limit, $search = "", $noSo = "") {
        $getDataQuery = "";
        $getTotalQuery = "";
        $sqlSearch = "";
        $result = "";
        $total = 0;
        if (!empty($noSo)) {
            $getDataQuery = "SELECT * FROM sales.t_sales_order_dist a WHERE a.no_so = '$noSo'";
            $getTotalQuery = "SELECT COUNT(*) AS total FROM sales.t_sales_order_dist a WHERE a.no_so = '$noSo'";
        } else {
            if (!empty($search)) {
                $sqlSearch = "WHERE (a.no_so) LIKE '%" . strtolower($search) . "%'";
            }
            $getDataQuery = "SELECT * FROM sales.t_sales_order_dist a $sqlSearch LIMIT $limit OFFSET $offset";
            $getTotalQuery = "SELECT COUNT(*) AS total FROM sales.t_sales_order_dist a $sqlSearch LIMIT $limit OFFSET $offset";
        }
        $queryData = $this->db->query($getDataQuery);
        $queryTotal = $this->db->query($getTotalQuery);

        if ($queryData->num_rows() > 0) {
            $result = $queryData->result();
            $total = $queryTotal->row()->total;
        }

        $results = array(
            'success' => true,
            'total' => $total,
            'data' => $result
        );

        return json_encode($results);
        //return 'true';
    }

    public function getDataPrint($no_bukti = '') {
        $sql = "select 'PEMBAYARAN PIUTANG (DISTRIBUSI)' title,a.*
                        from sales.t_piutang_pembayaran a
                        where no_pembayaran_piutang = '$no_bukti'
                        ";

        $query = $this->db->query($sql);

        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        $sql_detail = " select a.no_pembayaran_piutang,a.tgl_faktur,a.rp_bayar,a.no_faktur,a.rp_faktur,a.rp_potongan,a.rp_dibayar,b.rp_uang_muka,b.cash_diskon,a.rp_piutang,a.rp_bayar, b.rp_kurang_bayar,b.rp_bayar total_bayar
                                from sales.t_piutang_dist_detail a, sales.t_faktur_jual b
                                where a.no_faktur = b.no_faktur
                                and a.no_pembayaran_piutang = '$no_bukti'
                                ";

        $query_detail = $this->db->query($sql_detail);

        $data['detail'] = $query_detail->result();

        $this->db->flush_cache();
        $sql_detail_bayar = "select a.*,b.nm_pembayaran 
                                    from sales.t_piutang_dist_bayar a ,mst.t_jns_pembayaran b
                                    where a.kd_jns_bayar = b.kd_jenis_bayar
                                    and a.no_pembayaran_piutang = '$no_bukti'
                                    ";

        $query_detail_bayar = $this->db->query($sql_detail_bayar);

        $data['detail_bayar'] = $query_detail_bayar->result();

        return $data;
    }

    public function getDataPembayaran($no_bukti = '') {
        $data = $this->getDataPrint($no_bukti);

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
        if (!empty($d)) {
            $no = 1;
            $bayar = 0;
            $total_tagihan = 0;
            foreach ($d as $v) {
                if ($v->tgl_faktur) {
                    $tgl_faktur = date('d-m-Y', strtotime($v->tgl_faktur));
                }
                $sisa_invoice = $v->rp_total - $v->rp_pelunasan_hutang;
                $detail .= '<tr>
								<td align="center" bgcolor="#f5f5f5">' . $no . '</td>
								<td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;' . $v->no_faktur . '<br>&nbsp;&nbsp;</td>
                                                                <td align="center" bgcolor="#f5f5f5">' . $tgl_faktur . '</td>
                                                                <td align="right" bgcolor="#f5f5f5">&nbsp;&nbsp;' . number_format($v->rp_faktur, 0, ',', '.') . '</td>
								<td align="right" bgcolor="#f5f5f5">' . number_format($v->rp_bayar, 0, ',', '.') . '&nbsp;&nbsp;</td>
								<td align="right" bgcolor="#f5f5f5">' . number_format($v->rp_total_bayar, 0, ',', '.') . '&nbsp;&nbsp;</td>
                                                                <td align="right" bgcolor="#f5f5f5">' . number_format($v->rp_kurang_bayar, 0, ',', '.') . '&nbsp;&nbsp;</td>
                                                                											
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
								<td align="right" bgcolor="#f5f5f5">' . number_format($bayar, 0, ',', '.') . '&nbsp;&nbsp;</td>
                                                                <td align="right" bgcolor="#f5f5f5">&nbsp;&nbsp;</td>
                                                                <td align="right" bgcolor="#f5f5f5">&nbsp;&nbsp;</td>
															
							</tr>';
        } else {
            $detail .= '<tr><td>-----</td></tr>';
        }

        $detail .= '</table>';

        if ($h->tgl_bayar) {
            $tanggal = date('d-m-Y', strtotime($h->tgl_bayar));
        }

        if ($h->tgl_faktur_pajak) {
            $tgl_faktur_pajak = date('d-m-Y', strtotime($h->tgl_faktur_pajak));
        }
        if ($h->tgl_jth_tempo) {
            $tgl_jth_tempo = date('d-m-Y', strtotime($h->tgl_jth_tempo));
        }

        $header = '
			<table width="800" border="0" cellspacing="1" cellpadding="5">
			  <tr>
				<td height="28" colspan="4" align="left" valign="middle" bgcolor="#ACD9EB">&nbsp;&nbsp;<b>' . $h->title . '</b></td>
			  </tr>
			  <tr>
				<td height="28" width="40" align="right" valign="middle" bgcolor="#ACD9EB"><b>No.Bukti&nbsp;&nbsp;</b></td>
				<td width="300" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;' . $h->no_pembayaran_piutang . '</td>
				
			  </tr>
			  
                           <tr>
				<td  height="28" width="40"  align="right" valign="middle" bgcolor="#ACD9EB"><b>Tanggal Pelunasan&nbsp;&nbsp;</b></td>
				<td  width="300" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;' . $tanggal . '</b></td>
			  </tr>
                           <tr>
				<td height="28" width="40" align="right" valign="middle" bgcolor="#ACD9EB"><b>Keterangan&nbsp;&nbsp;</b></td>
				<td valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;' . $h->keterangan . '</td>
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
        if (!empty($db)) {
            $no = 1;
            $total_bayar = 0;
            $total_tagihan = 0;
            foreach ($db as $vb) {
                if ($vb->tgl_jth_tempo) {
                    $tgl_jth_tempo = date('d-m-Y', strtotime($vb->tgl_jth_tempo));
                }

                $detailbayar .= '<tr>
								<td align="center" bgcolor="#f5f5f5">' . $no . '</td>
								<td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;' . $vb->nm_pembayaran . '<br>&nbsp;&nbsp;</td>
                                                                <td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;' . number_format($vb->rp_bayar, 0, ',', '.') . '</td>
								<td align="center" bgcolor="#f5f5f5">' . $vb->nomor_bank . '&nbsp;&nbsp;</td>
								<td align="center" bgcolor="#f5f5f5">' . $vb->nomor_ref . '&nbsp;&nbsp;</td>
                                                                <td align="center" bgcolor="#f5f5f5">' . $tgl_jth_tempo . '&nbsp;&nbsp;</td>
                                                                
															
							</tr>
								
				
				';
                $no++;
                $total_bayar = $total_bayar + $vb->rp_bayar;
                $total_tagihan = $total_tagihan + $v->rp_jumlah;
            }

            $detailbayar .= '<tr>
								<td align="center" bgcolor="#f5f5f5"></td>
								<td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;Total Bayar<br>&nbsp;&nbsp;</td>
                                                                <td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;' . number_format($total_bayar, 0, ',', '.') . '</td>
								<td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;</td>
								<td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;</td>
                                                                <td align="center" bgcolor="#f5f5f5">&nbsp;&nbsp;</td>
                                                                
															
							</tr>';
        } else {
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
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;' . number_format($v->rp_total_invoice, 0, ',', '.') . '</td>
					</tr>
                                  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Total Potongan </b>&nbsp;&nbsp;</td>
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;' . number_format($v->rp_total_potongan, 0, ',', '.') . '</td>
					</tr>
                                   
                                  <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Total Pembayaran </b>&nbsp;&nbsp;</td>
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;' . number_format($v->rp_total_dibayar, 0, ',', '.') . '</td>
					</tr>
                                   <tr>
					<td height = "28" align="right" valign="middle" bgcolor="#ACD9EB"><b>Selisih / Sisa </b>&nbsp;&nbsp;</td>
					<td colspan="2" align="right" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;' . number_format($v->rp_total_invoice - $v->rp_total_potongan - $v->rp_total_dibayar, 0, ',', '.') . '</td>
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


        return $html;
    }

    public function printForm($no_bukti = '') {
        $data = $this->getDataPrint($no_bukti);
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/PembayaranPiutangDistribusiPrint.php');
        $pdf = new PembayaranPiutangDistribusiPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail'], $data['detail_bayar']);
        $pdf->Output();
        exit;
    }

}
