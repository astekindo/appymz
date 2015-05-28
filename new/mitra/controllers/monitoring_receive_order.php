<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Monitoring_receive_order extends MY_Controller {
    /**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('monitoring_ro_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	 
	public function get_data_ro($no_ro = ''){
		$data = $this->monitoring_ro_model->get_data_html($no_ro);
                
        $h = $data['header'];
        $d = $data['detail'];
		foreach($d as $e) {
			$eksp = $e->nama_ekspedisi;
		}
		
		$header = '
			<table width="600" border="0" cellspacing="1" cellpadding="5">
				<tr>
					<td height="28" colspan="4" align="left" valign="middle" bgcolor="#ACD9EB">&nbsp;&nbsp;<b>'.$h->title.'</b></td>
				</tr>
				<tr>
					<td height="28" width="100" align="right" valign="middle" bgcolor="#ACD9EB"><b>No RO&nbsp;&nbsp;</b></td>
					<td width="200" align="left" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->no_do.'</td>
					<td height="28" width="100" align="right" valign="middle" bgcolor="#ACD9EB"><b>No. Bukti&nbsp;&nbsp;</b></td>
					<td width="200" align="left" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->no_bukti_supplier.'</td>
				</tr>
				<tr>
					<td height="28" width="100" align="right" valign="middle" bgcolor="#ACD9EB"><b>No. Referensi&nbsp;&nbsp;</b></td>
					<td width="200" align="left" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->no_bukti_supplier.'</td>
					<td height="28" width="100" align="right" valign="middle" bgcolor="#ACD9EB"><b>Tgl. Terima&nbsp;&nbsp;</b></td>
					<td width="200" align="left" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->tanggal_terima.'</td>
				</tr>
				<tr>
					<td height="28" width="100" align="right" valign="middle" bgcolor="#ACD9EB"><b>Nama Ekspedisi&nbsp;&nbsp;</b></td>
					<td width="200" align="left" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$eksp.'</td>
					<td height="28" width="100" align="right" valign="middle" bgcolor="#ACD9EB"><b>Tgl. Input&nbsp;&nbsp;</b></td>
					<td width="200" align="left" valign="middle" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$h->tanggal.'</td>
				</tr>
			</table><br>
		';
		
		echo $header;
		
		$detail = '
			<table width="970"  border="0" >
				<tr>
				<td valign="center" cellspacing="1" cellpadding="5" height="32" width="30" align="center" bgcolor="#ACD9EB"><b>No.</b></td>
				<td valign="center" width="120" align="center" bgcolor="#ACD9EB"><b>No. PO</b></td>
				<td valign="center" width="120" align="center" bgcolor="#ACD9EB"><b>Kode Barang<br>(Kd Brg Lama)</b></td>
				<td valign="center" width="120" align="center" bgcolor="#ACD9EB"><b>Kode Barang<br>Supplier</b></td>
				<td valign="center" width="280" align="center" bgcolor="#ACD9EB"><b>Nama Barang</b></td>
				<td valign="center" width="60" align="center" bgcolor="#ACD9EB"><b>Qty</b></td>		
				<td valign="center" width="80" align="center" bgcolor="#ACD9EB"><b>Satuan</b></td>	
				<td valign="center" width="280" align="center" bgcolor="#ACD9EB"><b>Gudang</b></td>	
				<td valign="center" width="80" align="center" bgcolor="#ACD9EB"><b>Satuan<br>Ekspedisi</b></td>	
				<td valign="center" width="80" align="center" bgcolor="#ACD9EB"><b>Berat Ekspedisi</b></td>	
				</tr>
		';
		
		echo $detail;
		
		$no = 1;
		$totalQty = 0;
		$totalBerat = 0;
		foreach($d as $v) {
			echo '
				<tr>
				<td valign="center" height="28" width="30" align="center" bgcolor="#f5f5f5">'.$no.'</td>
				<td valign="center" width="120" align="center" bgcolor="#f5f5f5">'.$v->no_po.'</td>
				<td valign="center" width="120" align="center" bgcolor="#f5f5f5">'.$v->kd_produk.'<br>('.$v->kd_produk_lama.')</td>
				<td valign="center" width="120" align="center" bgcolor="#f5f5f5">'.$v->kd_produk_supp.'</td>
				<td valign="center" width="280" align="left" bgcolor="#f5f5f5">&nbsp;&nbsp;'.$v->nama_produk.'</td>
				<td valign="center" width="60" align="center" bgcolor="#f5f5f5">'.$v->qty_terima.'</td>		
				<td valign="center" width="80" align="center" bgcolor="#f5f5f5">'.$v->nm_satuan.'</td>	
				<td valign="center" width="280" align="center" bgcolor="#f5f5f5">'.$v->gudang.'</td>	
				<td valign="center" width="80" align="center" bgcolor="#f5f5f5">'.$v->nm_satuan_ekspedisi.'</td>	
				<td valign="center" width="80" align="center" bgcolor="#f5f5f5">'.$v->berat_ekspedisi.'</td>	
				</tr>
			';
			$no++;
			$totalQty = $totalQty + $v->qty_terima;
			$totalBerat = $totalBerat + $v->berat_ekspedisi;
		}
		
		echo '
				<tr>
				<td valign="center" colspan="5" height="28" align="right" bgcolor="#f5f5f5"><b>Total</b>&nbsp;&nbsp;</td>
				<td valign="center" align="center" bgcolor="#f5f5f5">'.$totalQty.'</td>
				<td valign="center" colspan="3" align="right" bgcolor="#f5f5f5"><b>Total</b>&nbsp;&nbsp;</td>
				<td valign="center" align="center" bgcolor="#f5f5f5">'.$totalBerat.'</td>
				</tr>
				</table></br>
			';
		
	}
	
	public function get_rows(){
            $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
            $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
            $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
            $kdSupplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
            $tglAwal = isset($_POST['tgl_awal']) ? $this->db->escape_str($this->input->post('tgl_awal',TRUE)) : '';
            $tglAkhir = isset($_POST['tgl_akhir']) ? $this->db->escape_str($this->input->post('tgl_akhir',TRUE)) : '';
            $lokasi = isset($_POST['lokasi']) ? $this->db->escape_str($this->input->post('lokasi',TRUE)) : '';
            $bloklokasi = isset($_POST['bloklokasi']) ? $this->db->escape_str($this->input->post('bloklokasi',TRUE)) : '';
            $subbloklokasi = isset($_POST['subbloklokasi']) ? $this->db->escape_str($this->input->post('subbloklokasi',TRUE)) : '';
            $invoice = isset($_POST['invoice']) ? $this->db->escape_str($this->input->post('invoice',TRUE)) : '';
            $bayar = isset($_POST['bayar']) ? $this->db->escape_str($this->input->post('bayar',TRUE)) : '';
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
        $result = $this->monitoring_ro_model->get_rows($kdSupplier, $tglAwal, $tglAkhir, $lokasi, $bloklokasi, $subbloklokasi,$invoice,$bayar,$peruntukan_sup,$peruntukan_dist, $search, $start, $limit);
        
        echo $result;
	}
}
