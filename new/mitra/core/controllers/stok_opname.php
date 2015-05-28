<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stok_opname extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('stok_opname_model');
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_form() {
        $no_op = 'OP' . date('Ym') . '-';
        $sequence = $this->stok_opname_model->get_kode_sequence($no_op, 3);
        echo '{"success":true,
				"data":{
					"no_opname":"' . $no_op . $sequence . '",
					"tanggal_opname":"' . date('d-M-Y') . '"
				}
			}';
    }

    public function update_row() {
        $no_opname = isset($_POST['no_opname']) ? $this->db->escape_str($this->input->post('no_opname', TRUE)) : "";
        $tanggal_opname = isset($_POST['tanggal_opname']) ? $this->db->escape_str($this->input->post('tanggal_opname', TRUE)) : "";
        $kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi', TRUE)) : "";
        $kd_blok = isset($_POST['kd_blok']) ? $this->db->escape_str($this->input->post('kd_blok', TRUE)) : "";
        $kd_sub_blok = isset($_POST['kd_sub_blok']) ? $this->db->escape_str($this->input->post('kd_sub_blok', TRUE)) : "";
        $keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan', TRUE)) : "";

        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();

        $header_result = 0;
        $detail_result = 0;

        if ((count($detail) == 0)) {
            echo '{"success":false,"errMsg":"Proses gagal"}';
            exit;
        }

        if ($tanggal_opname) {
            $tanggal_opname = date('Y-m-d', strtotime($tanggal_opname));
        }

        $this->db->trans_start();

        unset($header_so);
        $header_so['no_opname'] = $no_opname;
        $header_so['tgl_opname'] = $tanggal_opname;
        $header_so['kd_lokasi'] = $kd_lokasi;
        $header_so['kd_blok'] = $kd_blok;
        $header_so['kd_sub_blok'] = $kd_sub_blok;
        $header_so['keterangan'] = $keterangan;
        $header_so['userid'] = $this->session->userdata('username');
        $header_so['created_by'] = $this->session->userdata('username');
        $header_so['created_date'] = date('Y-m-d H:i:s');
        $header_so['updated_by'] = $this->session->userdata('username');
        $header_so['updated_date'] = date('Y-m-d H:i:s');


        if ($this->stok_opname_model->insert_row('inv.t_stok_opname', $header_so)) {
            $header_result++;
        }

        foreach ($detail as $obj) {
            unset($detail_so);

            $detail_so['no_opname'] = $no_opname;
            $detail_so['kd_produk'] = $obj->kd_produk;
            $detail_so['qty'] = $obj->qty_oh;
            $detail_so['qty_adjust'] = $obj->qty_adjust;
            $detail_so['qty_penyesuaian'] = $obj->penyesuaian;

            if ($this->stok_opname_model->insert_row('inv.t_stok_opname_detail', $detail_so)) {
                $detail_result++;
            }
        }
        $this->db->trans_complete();


        if ($header_result > 0 && $detail_result > 0) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    public function get_barang() {
        $kdLokasi = isset($_POST['kdLokasi']) ? $this->db->escape_str($this->input->post('kdLokasi', TRUE)) : "";
        $kdBlok = isset($_POST['kdBlok']) ? $this->db->escape_str($this->input->post('kdBlok', TRUE)) : "";
        $kdSubBlok = isset($_POST['kdSubBlok']) ? $this->db->escape_str($this->input->post('kdSubBlok', TRUE)) : "";

        $result = $this->stok_opname_model->get_barang($kdLokasi, $kdBlok, $kdSubBlok);

        echo $result;
    }

    public function get_barang_entry() {
        $no_opname = isset($_POST['no_opname']) ? $this->db->escape_str($this->input->post('no_opname', TRUE)) : "";

        $result = $this->stok_opname_model->get_barang_entry($no_opname);

        echo $result;
    }

    public function get_initstok() {
        $kdLokasi = isset($_POST['kdLokasi']) ? $this->db->escape_str($this->input->post('kdLokasi', TRUE)) : "";
        $kdBlok = isset($_POST['kdBlok']) ? $this->db->escape_str($this->input->post('kdBlok', TRUE)) : "";
        $kdSubBlok = isset($_POST['kdSubBlok']) ? $this->db->escape_str($this->input->post('kdSubBlok', TRUE)) : "";
        $kdKat1 = isset($_POST['kdKat1']) ? $this->db->escape_str($this->input->post('kdKat1', TRUE)) : "";
        $kdKat2 = isset($_POST['kdKat2']) ? $this->db->escape_str($this->input->post('kdKat2', TRUE)) : "";
        $kdKat3 = isset($_POST['kdKat3']) ? $this->db->escape_str($this->input->post('kdKat3', TRUE)) : "";
        $kdKat4 = isset($_POST['kdKat4']) ? $this->db->escape_str($this->input->post('kdKat4', TRUE)) : "";

        $result = $this->stok_opname_model->get_initstok($kdLokasi, $kdBlok, $kdSubBlok, $kdKat1, $kdKat2, $kdKat3, $kdKat4);

        echo $result;
    }

    public function get_headentrystok() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->stok_opname_model->get_head_entrystok($search, $start, $limit);

        echo $result;
    }

    public function get_headapprovalstok() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->stok_opname_model->get_head_approvalstok($search, $start, $limit);

        echo $result;
    }

    public function update_initialrow() {
        $no_opname = isset($_POST['no_opname']) ? $this->db->escape_str($this->input->post('no_opname', TRUE)) : "";
        $tanggal_opname = isset($_POST['tanggal_opname']) ? $this->db->escape_str($this->input->post('tanggal_opname', TRUE)) : "";
        $kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi', TRUE)) : "";
        $kd_blok = isset($_POST['kd_blok']) ? $this->db->escape_str($this->input->post('kd_blok', TRUE)) : "";
        $kd_sub_blok = isset($_POST['kd_sub_blok']) ? $this->db->escape_str($this->input->post('kd_sub_blok', TRUE)) : "";
        $keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan', TRUE)) : "";

        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();

        $header_result = 0;
        $detail_result = 0;

        if ((count($detail) == 0)) {
            echo '{"success":false,"errMsg":"Proses gagal"}';
            exit;
        }

        if ($tanggal_opname) {
            $tanggal_opname = date('Y-m-d', strtotime($tanggal_opname));
        }

        $this->db->trans_start();

        unset($header_so);
        $header_so['no_opname'] = $no_opname;
        $header_so['tgl_opname'] = $tanggal_opname;
        $header_so['kd_lokasi'] = $kd_lokasi;
        $header_so['kd_blok'] = $kd_blok;
        $header_so['kd_sub_blok'] = $kd_sub_blok;
        $header_so['keterangan'] = $keterangan;
        $header_so['userid'] = $this->session->userdata('username');
        $header_so['created_by'] = $this->session->userdata('username');
        $header_so['created_date'] = date('Y-m-d H:i:s');
        $header_so['updated_by'] = $this->session->userdata('username');
        $header_so['updated_date'] = date('Y-m-d H:i:s');
        $header_so['status'] = 0;



        if ($this->stok_opname_model->insert_row('inv.t_stok_opname', $header_so)) {
            $header_result++;
        }

        foreach ($detail as $obj) {
            unset($detail_so);

            $detail_so['no_opname'] = $no_opname;
            $detail_so['kd_produk'] = $obj->kd_produk;
            $detail_so['qty'] = $obj->qty_oh;
            $detail_so['qty_adjust'] = 0;
            $detail_so['qty_penyesuaian'] = 0;

            if ($this->stok_opname_model->insert_row('inv.t_stok_opname_detail', $detail_so)) {
                $detail_result++;
            }
        }
        $this->db->trans_complete();


        if ($header_result > 0 && $detail_result > 0) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    public function update_entryrow() {
        $no_opname = isset($_POST['no_opname']) ? $this->db->escape_str($this->input->post('no_opname', TRUE)) : "";

        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();

        $header_result = 0;
        $detail_result = 0;

        if ((count($detail) == 0)) {
            echo '{"success":false,"errMsg":"Proses gagal"}';
            exit;
        }


        $this->db->trans_start();

        unset($header_so);
//		$header_so['no_opname'] = $no_opname;			
        $header_so['updated_by'] = $this->session->userdata('username');
        $header_so['updated_date'] = date('Y-m-d H:i:s');
        $header_so['status'] = 1;



        if ($this->stok_opname_model->update_head_entrystok($no_opname, $header_so)) {
            $header_result++;
        }

        foreach ($detail as $obj) {
            unset($detail_so);

//			$detail_so['no_opname'] = $no_opname;
//			$detail_so['kd_produk'] = $obj->kd_produk;
//			$detail_so['qty'] = $obj->qty_oh;
            $detail_so['qty_adjust'] = $obj->qty_adjust;
            $detail_so['qty_penyesuaian'] = $obj->penyesuaian;

            if ($this->stok_opname_model->update_detail_entrystok($no_opname, $obj->kd_produk, $detail_so)) {
                $detail_result++;
            }
        }
        $this->db->trans_complete();


        if ($header_result > 0 && $detail_result > 0) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    public function update_approvalrow() {
        $no_opname = isset($_POST['no_opname']) ? $this->db->escape_str($this->input->post('no_opname', TRUE)) : "";
        $tanggal_opname = isset($_POST['tanggal_opname']) ? $this->db->escape_str($this->input->post('tanggal_opname', TRUE)) : "";
        $kdlokasi = isset($_POST['kdlokasi']) ? $this->db->escape_str($this->input->post('kdlokasi', TRUE)) : "";
        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();

        $header_result = 0;
        $detail_result = 0;

        if ((count($detail) == 0)) {
            echo '{"success":false,"errMsg":"Proses gagal"}';
            exit;
        }
        
        $created_by=$this->session->userdata('username');
        $created_date=date('Y-m-d H:i:s');
        
        if ($tanggal_opname) {
            $tanggal_opname = date('Y-m-d', strtotime($tanggal_opname));
        }
        
        $this->db->trans_start();

        unset($header_so);
//		$header_so['no_opname'] = $no_opname;			
        $header_so['updated_by'] = $this->session->userdata('username');
        $header_so['updated_date'] = date('Y-m-d H:i:s');
        $header_so['status'] = 2;



        if ($this->stok_opname_model->update_head_entrystok($no_opname, $header_so)) {
            $header_result++;
        }

        foreach ($detail as $obj) {
            unset($trxinventory);
            $kd_lokasi = substr($kdlokasi, 0, 2);
            $kd_blok = substr($kdlokasi, 2, 2);
            $kd_sub_blok = substr($kdlokasi, 4, 2);          
            
            
            $trxinventory['kd_produk'] = $obj->kd_produk;
            $trxinventory['no_ref'] = $no_opname;
            $trxinventory['kd_lokasi'] = $kd_lokasi;
            $trxinventory['kd_blok'] = $kd_blok;
            $trxinventory['kd_sub_blok'] = $kd_sub_blok;
            if ($obj->penyesuaian < 0){
                $trxinventory['qty_in'] = 0;
                $trxinventory['qty_out'] = (int) abs($obj->penyesuaian);
            }
            if ($obj->penyesuaian > 0){
                $trxinventory['qty_in'] = (int) $obj->penyesuaian;
                $trxinventory['qty_out'] = 0;
            }
            
            if ($obj->penyesuaian === 0){
                $trxinventory['qty_in'] = (int) $obj->penyesuaian;
                $trxinventory['qty_out'] = 0;
            }
            
            $trxinventory['type'] = '3';
            $trxinventory['created_by'] = $created_by;
            $trxinventory['created_date'] = $created_date;
            $trxinventory['tgl_trx'] = $tanggal_opname;
            
            unset($brg_inventory);
            $stok = 0;
            $stokexists = FALSE;
            $rowstok = $this->stok_opname_model->cek_exists_brg_inv($obj->kd_produk, $kd_lokasi, $kd_blok, $kd_sub_blok);

            if (count($rowstok) > 0) {
                $stokexists = true;
                foreach ($rowstok as $objstok) {
                    $stok = $objstok->qty_oh;
                }
                $brg_inventory['qty_oh'] = $stok + (int) $obj->penyesuaian;
                $brg_inventory['updated_by'] = $created_by;
                $brg_inventory['updated_date'] = $created_date;
            } else {
                $brg_inventory['kd_produk'] = $obj->kd_produk;
                $brg_inventory['kd_lokasi'] = $kd_lokasi;
                $brg_inventory['kd_blok'] = $kd_blok;
                $brg_inventory['kd_sub_blok'] = $kd_sub_blok;
                $brg_inventory['qty_oh'] = $stok + (int) $obj->penyesuaian;
                $brg_inventory['created_by'] = $created_by;
                $brg_inventory['created_date'] = $created_date;
            }
            if ($this->stok_opname_model->insert_row('inv.t_trx_inventory', $trxinventory)) {
                    if(!$stokexists){
                        if ($this->stok_opname_model->insert_row('inv.t_brg_inventory', $brg_inventory)) {
                            $detail_result++;
                        } 
                    }else{
                        if ($this->stok_opname_model->update_brg_inv($obj->kd_produk,$kd_lokasi,$kd_blok,$kd_sub_blok, $brg_inventory)) {
                            $detail_result++;
                        } 
                    }
                                       
                }
//	update stok
//			if($this->stok_opname_model->update_detail_entrystok($no_opname,$obj->kd_produk, $detail_so)){
//            $detail_result++;
//			}
        }
        $this->db->trans_complete();


        if ($header_result > 0 && $detail_result > 0) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    public function get_sub_blok($kdLokasi, $kdBlok) {
        $result = $this->sub_blok_lokasi_model->get_sub_blok($kdLokasi, $kdBlok);
        echo $result;
    }

    public function get_akun_penyesuaian() {

        $result = '{success:true,data:[{"value":"1","display":"akun"},{"value":"2","display":"akun 2"}]}';
        echo $result;
    }

}