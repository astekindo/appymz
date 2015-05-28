<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penjualan_retur extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('penjualan_retur_model');
        $this->load->model('pembelian_retur_model', 'pret_model');
        $this->load->model('pembelian_receive_order_model', 'pro_model');
    }

    //RBYYYYMM-001
    public function get_form() {
       // $no_ret = 'RJ' . date('Ym') . '-';
        //$sequence = $this->penjualan_retur_model->get_kode_sequence($no_ret, 3);
        echo '{"success":true,
				"data":{
					"no_retur":"' . $no_ret . $sequence . '",
					"tgl_retur":"' . date('d-M-Y') . '"
				}
			}';
    }

    public function search_salesorder() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->penjualan_retur_model->search_salesorder($search, $start, $limit);


        echo $result;
    }
    public function search_do() {
        $no_so = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so', TRUE)) : "";
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : "";
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : "";
        $result = $this->penjualan_retur_model->search_do($no_so,$kd_produk, $search, $this->session->userdata('user_peruntukan'));
        
        echo $result;
    }

    public function search_produk_by_salesorder($no_so = '') {
        $detail = $this->penjualan_retur_model->search_produk_by_salesorder($no_so);
        echo '{success:true,data:' . json_encode($detail) . '}';
    }
    public function search_produk_bonus_by_so($no_so = '') {
        $detail = $this->penjualan_retur_model->search_produk_bonus_by_so($no_so);
        echo '{success:true,data:' . json_encode($detail) . '}';
    }
    public function get_rows_lokasi(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
                $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';
		
        $result = $this->penjualan_retur_model->get_rows_lokasi($kd_produk, $search, $start, $limit);
        
        echo $result;
	}
    public function get_sub_blok(){
		$result = '{"success" : true, record : 0, "data" : ""} ';
				
        
        echo $result;
	}
    public function get_all_faktur(){
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : "";
        $result = $this->penjualan_retur_model->get_all_faktur($search, $start, $limit);
        
        echo $result;
	}
        
    public function update_row() {
        $tgl_retur = isset($_POST['tgl_retur']) ? $this->db->escape_str($this->input->post('tgl_retur', TRUE)) : FALSE;
        $current_date = date('Ym', strtotime($tgl_retur));
        $no_ret = 'RJ' . $current_date . '-';
        $sequence = $this->penjualan_retur_model->get_kode_sequence($no_ret, 3);
        $no_retur = $no_ret . $sequence;
        //$no_retur = isset($_POST['no_retur']) ? $this->db->escape_str($this->input->post('no_retur', TRUE)) : FALSE;
        
        $no_so = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so', TRUE)) : FALSE;
        $remark = isset($_POST['_remark']) ? $this->db->escape_str($this->input->post('_remark', TRUE)) : FALSE;
        $rp_diskon = isset($_POST['_diskon_returjual']) ? $this->db->escape_str($this->input->post('_diskon_returjual', TRUE)) : FALSE;
        $rp_total = isset($_POST['_jumlah_returjual']) ? $this->db->escape_str($this->input->post('_jumlah_returjual', TRUE)) : FALSE;
        //$kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi', TRUE)) : FALSE;
        $ppn = isset($_POST['ppn']) ? $this->db->escape_str($this->input->post('ppn', TRUE)) : FALSE;
        $dpp = isset($_POST['dpp']) ? $this->db->escape_str($this->input->post('dpp', TRUE)) : FALSE;
        $diskon_ekstra = isset($_POST['_diskon_ekstra_returjual']) ? $this->db->escape_str($this->input->post('_diskon_ekstra_returjual', TRUE)) : FALSE;
        $jumlah_returjual= isset($_POST['_jumlah_returjual']) ? $this->db->escape_str($this->input->post('_jumlah_returjual', TRUE)) : FALSE;
        $grand_total = isset($_POST['_grandtotal_returjual']) ? $this->db->escape_str($this->input->post('_grandtotal_returjual', TRUE)) : FALSE;
        $pct_diskon_tammbahan= isset($_POST['_pct_diskon_tammbahan']) ? $this->db->escape_str($this->input->post('_pct_diskon_tammbahan', TRUE)) : FALSE;
        $rp_diskon_tambahan = isset($_POST['_rp_diskon_tambahan']) ? $this->db->escape_str($this->input->post('_rp_diskon_tambahan', TRUE)) : FALSE;
        $nilai_retur = isset($_POST['nilai_retur']) ? $this->db->escape_str($this->input->post('nilai_retur', TRUE)) : FALSE;
        $status = '1';
        $created_by = $this->session->userdata('username');
        $created_date = date('Y-m-d');
        // $is_konsinyasi = isset($_POST['pis_konsinyasi']) ? $this->db->escape_str($this->input->post('pis_konsinyasi', TRUE)) : FALSE;

        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();

        $header_result = 0;
        $detail_result = 0;
        if ($tgl_retur) {
            $tgl_retur = date('Y-m-d', strtotime($tgl_retur));
        }

        if (count($detail) == 0) {
            echo '{"success":false,"errMsg":"Proses gagal"}';
        }

        $this->db->trans_begin();
        //$rp_total = $rp_total - $rp_diskon - $diskon_ekstra;

        $header_ret['no_retur'] = $no_retur;
        $header_ret['tgl_retur'] = $tgl_retur;
        $header_ret['no_so'] = $no_so;
        $header_ret['pct_potongan'] = $pct_diskon_tammbahan;
        $header_ret['rp_potongan'] = $rp_diskon_tambahan;
        $header_ret['rp_jumlah'] = $jumlah_returjual;
        $header_ret['rp_total'] = $nilai_retur;
        $header_ret['remark'] = $remark;
        $header_ret['status'] = $status;
        $header_ret['created_by'] = $created_by;
        $header_ret['created_date'] = $created_date;
        $header_ret['no_so_retur'] = '';
        $header_ret['dpp'] = $dpp;
        $header_ret['ppn'] = $ppn;
        $header_ret['rp_grand_total'] = $grand_total;
       
        $header_result = $this->penjualan_retur_model->insert_row('sales.t_retur_sales', $header_ret);

        $sql = "UPDATE sales.t_sales_order SET rp_retur = rp_retur + " . $jumlah_returjual . 
            " WHERE no_so = '".$no_so."'";
        $this->penjualan_retur_model->query_update($sql);
       
        foreach ($detail as $obj) {
         if($obj->edited == 'Y'){
            $kd_lokasi = substr($obj->sub, 0, 2);
            $kd_blok = substr($obj->sub, 2, 2);
            $kd_sub_blok = substr($obj->sub, 4, 2);
            $kd_produk = $obj->kd_produk;  
            $maks_retur = (int) $obj->retur_so + (int) $obj->qty_retur + (int) $obj->qty_retur_so + (int) $obj->qty_retur_do + (int) $obj->qty_input;
            $qty_struk = (int) $obj->qty;
//            if ($maks_retur > $qty_struk){
//                echo '{"success":false,"errMsg":"Retur SO + Retur + Qty Retur SO + Qty Retur DO + Qty Retur Tidak Boleh Lebih Dari Qty SO!"}';
//                $this->db->trans_rollback();
//                exit;
//            }
                     
            unset($detail_pr);
            $detail_pr['kd_produk'] = $obj->kd_produk;
            $detail_pr['no_retur'] = $no_retur;
            $detail_pr['qty'] = (int) $obj->qty_input;
            $detail_pr['qty_retur_so'] = (int) $obj->qty_retur_so;
            $detail_pr['qty_retur_do'] = (int) $obj->qty_retur_do;
            $detail_pr['rp_disk'] = (int) $obj->rp_diskon;
            $detail_pr['rp_jumlah'] = (int) $obj->rp_harga;
            $detail_pr['rp_potongan'] = (int) $obj->ekstra_diskon;
            $detail_pr['rp_total'] = (int) $obj->rp_total1;
            $detail_pr['no_do'] = $obj->no_do;
            $detail_pr['approval'] = 1;
            $detail_pr['kd_lokasi'] = $kd_lokasi;
            $detail_pr['kd_blok'] = $kd_blok;
            $detail_pr['kd_sub_blok'] = $kd_sub_blok;
            $detail_pr['created_by'] = $this->session->userdata('username');
            $detail_pr['created_date'] = date('Y-m-d H:i:s');
            //print_r($detail_pr);
            $detailresult = $this->penjualan_retur_model->insert_row('sales.t_retur_sales_detail', $detail_pr);
            if ($detailresult) {
                $sql = "UPDATE sales.t_sales_order_detail SET qty_retur = qty_retur + " . $obj->qty_input . ", 
                    qty_retur_so = qty_retur_so + " . $obj->qty_retur_so . ",qty_retur_do = qty_retur_do + " . $obj->qty_retur_do . " WHERE kd_produk = '" . $obj->kd_produk . "' AND no_so = '".$no_so."'";
		$this->penjualan_retur_model->query_update($sql);


                
                $sql_do = "UPDATE sales.t_sales_delivery_order_detail SET qty_retur_do = qty_retur_do + " . $obj->qty_retur_do . " WHERE kd_barang = '" . $obj->kd_produk . "' AND no_do = '". $obj->no_do."'";
		$this->penjualan_retur_model->query_update($sql_do);
                
                if((int)$obj->qty_input > 0){
                $lokasi = $this->penjualan_retur_model->search_lokasi($kd_lokasi,$kd_blok,$kd_sub_blok,$kd_produk);
                if ($lokasi){
                        $sql = "UPDATE inv.t_brg_inventory SET qty_oh = qty_oh + " . $obj->qty_input . " WHERE kd_produk = '" . $obj->kd_produk . "' AND kd_lokasi = '".$kd_lokasi."' AND kd_blok = '".$kd_blok."' AND kd_sub_blok = '".$kd_sub_blok."'";
                        $this->penjualan_retur_model->query_update($sql);
                     }
                else { 
                    unset($brg_inventory);
						$brg_inventory['kd_produk'] = $obj->kd_produk;
						$brg_inventory['kd_lokasi'] = $kd_lokasi;
						$brg_inventory['kd_blok'] = $kd_blok;
						$brg_inventory['kd_sub_blok'] = $kd_sub_blok;
						$brg_inventory['qty_oh'] = $obj->qty_input;
						$brg_inventory['created_by'] = $this->session->userdata('username');
						$brg_inventory['created_date'] = date('Y-m-d H:i:s');
                    $this->penjualan_retur_model->insert_row("inv.t_brg_inventory",$brg_inventory);
                       // $sql = "INSERT INTO inv.t_brg_inventory (kd_produk,kd_lokasi,kd_blok,kd_sub_blok,qty_oh) values ('" . $kd_produk . "','" . $kd_lokasi . "' ,'" . $kd_blok . "','" . $kd_sub_blok . "','" . $obj->qty_input . "')";
                        //->penjualan_retur_model->query_insert($sql);
                     }
                }
                $detail_result++;
                
            }
        }
      }
        $this->db->trans_commit();
        if ($header_result && $detailresult) {
            $result = '{"success":true,"errMsg":"Data Berhasil Disimpan","printUrl":"' . site_url("penjualan_retur/print_form/" . $no_retur) . '"}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function print_form($no_retur = '') {
        $data = $this->penjualan_retur_model->get_data_print($no_retur);
        if (!$data)
            show_404('page');

//         print_r($data['header']);
//        print_r($data['detail']);exit;
        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/ReturPenjualanPrint.php');
        $pdf = new ReturPenjualanPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, 'LETTER_MBS', true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail']);
        $pdf->Output();
        exit;
    }

}

?>
