<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mutasi_barang extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('mutasi_barang_model');
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_form() {
        $no_mu = 'MS' . date('Ymd') . '-';
        $sequence = $this->mutasi_barang_model->get_kode_sequence($no_mu, 3);
        echo '{"success":true,
				"data":{
					"no_mutasi_stok":"' . $no_mu . $sequence . '",
					"tgl_mutasi":"' . date('d-M-Y') . '"
				}
			}';
    }
    
    
    public function get_form_out() {
        $no_mu = 'ML' . date('Ymd') . '-';
        $sequence = $this->mutasi_barang_model->get_kode_sequence($no_mu, 3);
        echo '{"success":true,
				"data":{
					"no_mutasi_stok":"' . $no_mu . $sequence . '",
					"tgl_mutasi":"' . date('d-M-Y') . '"
				}
			}';
    }
    
    public function get_form_in() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';        
        $result = $this->mutasi_barang_model->get_form_in($search, $start, $limit);

        echo $result;
    }
    
    public function get_form_in_detail() {        
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';        
        $result = $this->mutasi_barang_model->get_form_in_detail($search);

        echo $result;
    }

    public function search_subbloktujuan() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $datablok = isset($_POST['datablok']) ? $this->db->escape_str($this->input->post('datablok', TRUE)) : '';
        $kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi',TRUE)) : '';
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : '';
        $result = $this->mutasi_barang_model->get_subbloktujuan($kd_produk, $kd_lokasi,$search, $start, $limit, $datablok);

        echo $result;
    }

    public function search_barang() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $datablok = isset($_POST['datablok']) ? $this->db->escape_str($this->input->post('datablok', TRUE)) : '';
        $no_mutasi_stok = isset($_POST['no_mutasi_stok']) ? $this->db->escape_str($this->input->post('no_mutasi_stok', TRUE)) : '';
        $lokasi = substr($datablok, 0, 2);
        $blok = substr($datablok, 2, 2);
        $subblok = substr($datablok, 4, 2);

        if($no_mutasi_stok === ''){
            $result = $this->mutasi_barang_model->select_inv_barang($search, $lokasi, $blok, $subblok, $start, $limit);
        }else{
            $result = $this->mutasi_barang_model->select_inv_barang_mutasi($search, $lokasi, $blok, $subblok, $no_mutasi_stok);
        }

        echo $result;
    }
    
    public function search_lokasi() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
                
        $result = $this->mutasi_barang_model->search_lokasi($search,$start, $limit);


        echo $result;
    }
    
    public function get_subblok(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
        $kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi',TRUE)) : '';
		
        $result = $this->mutasi_barang_model->get_subblok($kd_lokasi,$search, $start, $limit);
        
        echo $result;
	}
    
        public function get_subblok_out(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
        $kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi',TRUE)) : '';
		
        $result = $this->mutasi_barang_model->get_subblok_out($search, $start, $limit);
        
        echo $result;
	}
        
        public function search_subbloktujuan_out() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $datablok = isset($_POST['datablok']) ? $this->db->escape_str($this->input->post('datablok', TRUE)) : '';
        
        $result = $this->mutasi_barang_model->get_subbloktujuan_out($search, $start, $limit, $datablok);

        echo $result;
    }
    
    public function search_subbloktujuan_in() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $datablok = isset($_POST['datablok']) ? $this->db->escape_str($this->input->post('datablok', TRUE)) : '';
        
        $result = $this->mutasi_barang_model->get_subbloktujuan_out($search, $start, $limit, $datablok);

        echo $result;
    }

    public function update_row() {
        $no_mutasi_stok = isset($_POST['no_mutasi_stok']) ? $this->db->escape_str($this->input->post('no_mutasi_stok', TRUE)) : "";
        $tgl_mutasi = isset($_POST['tgl_mutasi']) ? $this->db->escape_str($this->input->post('tgl_mutasi', TRUE)) : "";
	$keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan',TRUE)) : "";
        $no_ref = isset($_POST['no_ref']) ? $this->db->escape_str($this->input->post('no_ref',TRUE)) : "";
        $no_mutasi_sales = isset($_POST['no_mutasi_sales']) ? $this->db->escape_str($this->input->post('no_mutasi_sales',TRUE)) : "";
        $nama_pengambil = isset($_POST['nama_pengambil']) ? $this->db->escape_str($this->input->post('nama_pengambil',TRUE)) : "";
        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();

        $header_result = 0;
        $detail_result = 0;
        $trx_result = 0;
        $inv_result = 0;

        if ((count($detail) == 0)) {
            echo '{"success":false,"errMsg":"Proses gagal"}';
            exit;
        }

        if ($tgl_mutasi) {
            $tgl_mutasi = date('Y-m-d', strtotime($tgl_mutasi));
        }

        $this->db->trans_start();

        unset($header_ms);
        $header_ms['no_mutasi_stok'] = $no_mutasi_stok;
        $header_ms['tgl_mutasi'] = $tgl_mutasi;
        $header_ms['keterangan'] = $keterangan;
        $header_ms['no_ref'] = $no_ref;
        $header_ms['nama_pengambil'] = $nama_pengambil;
        $header_ms['userid'] = $this->session->userdata('username');
        $header_ms['created_by'] = $this->session->userdata('username');
        $header_ms['created_date'] = date('Y-m-d H:i:s');
        $header_ms['updated_by'] = $this->session->userdata('username');
        $header_ms['updated_date'] = date('Y-m-d H:i:s');
        $header_ms['status'] = 1;
        $header_ms['approval_out'] = $this->session->userdata('username');
        $header_ms['approval_in'] = $this->session->userdata('username');
        $header_ms['tgl_approval_out']= date('Y-m-d H:i:s');
        $header_ms['tgl_approval_in']= date('Y-m-d H:i:s');

       
            if ($this->mutasi_barang_model->insert_row('inv.t_mutasi_barang', $header_ms)) {
                $header_result++;
                if ($header_result > 0) {
                    foreach ($detail as $obj) {
                        unset($detail_ms);
                        $kd_lokasi = substr($obj->sub_asal, 0, 2);
                        $kd_blok = substr($obj->sub_asal, 2, 2);
                        $kd_sub_blok = substr($obj->sub_asal, 4, 2);
                        
                        $kd_lokasi_t = substr($obj->sub_tujuan, 0, 2);
                        $kd_blok_t = substr($obj->sub_tujuan, 2, 2);
                        $kd_sub_blok_t = substr($obj->sub_tujuan, 4, 2);
                        
                        $detail_ms['no_mutasi_stok'] = $no_mutasi_stok;
                        $detail_ms['kd_produk'] = $obj->kd_produk;
                        $detail_ms['kd_lokasi_awal'] = $kd_lokasi;
                        $detail_ms['kd_blok_awal'] = $kd_blok;
                        $detail_ms['kd_sub_blok_awal'] = $kd_sub_blok;
                        $detail_ms['kd_lokasi_tujuan'] = $kd_lokasi_t;
                        $detail_ms['kd_blok_tujuan'] = $kd_blok_t;
                        $detail_ms['kd_sub_blok_tujuan'] = $kd_sub_blok_t;
                        $detail_ms['qty'] = $obj->qty;

                        if ($this->mutasi_barang_model->insert_row('inv.t_mutasi_barang_detail', $detail_ms)) {
                            unset($trx);
                            $trx['kd_produk'] = $obj->kd_produk;
                            $trx['no_ref'] = $no_mutasi_stok;
                            $trx['kd_lokasi'] = $kd_lokasi;
                            $trx['kd_blok'] = $kd_blok;
                            $trx['kd_sub_blok'] = $kd_sub_blok;
                            $trx['qty_in'] = 0;
                            $trx['qty_out'] = $obj->qty;
                            $trx['type'] = 6;
                            $trx['created_by'] = $this->session->userdata('username');
                            $trx['created_date'] = date('Y-m-d H:i:s');
                            $trx['tgl_trx'] = date('Y-m-d H:i:s');

                            if ($this->mutasi_barang_model->insert_row('inv.t_trx_inventory', $trx)) {
                                $trx_out_result++;
                            }

                            unset($trx['kd_lokasi']);
                            unset($trx['kd_blok']);
                            unset($trx['kd_sub_blok']);
                            unset($trx['qty_in']);
                            unset($trx['qty_out']);
                            $trx['qty_in'] = $obj->qty;
                            $trx['qty_out'] = 0;
                            $trx['kd_lokasi'] = $kd_lokasi_t;
                            $trx['kd_blok'] = $kd_blok_t;
                            $trx['kd_sub_blok'] = $kd_sub_blok_t;


                            if ($this->mutasi_barang_model->insert_row('inv.t_trx_inventory', $trx)) {
                                $trx_result++;
                            }

                            //out stok
                            $set = 'qty_oh';
                            $field = $obj->qty;
                            $where = array(
                                'kd_produk' => $obj->kd_produk,
                                'kd_lokasi' => $kd_lokasi,
                                'kd_blok' => $kd_blok,
                                'kd_sub_blok' => $kd_sub_blok
                            );
                            $sql = "UPDATE inv.t_brg_inventory SET qty_oh = qty_oh - " . $obj->qty . " WHERE kd_produk = '$obj->kd_produk' AND kd_lokasi = '$kd_lokasi' AND kd_blok = '$kd_blok' AND kd_sub_blok = '$kd_sub_blok'";
                             if ($this->mutasi_barang_model->select_inventory($where)) {                                 
                                if ($this->mutasi_barang_model->query_update($sql)) {
                                    $inv_result++;
                                }
                             } else {
                                unset($inv);
                                $inv['kd_produk'] = $obj->kd_produk;
                                $inv['kd_lokasi'] = $kd_lokasi;
                                $inv['kd_blok'] = $kd_blok;
                                $inv['kd_sub_blok'] = $kd_sub_blok;
                                $inv['qty_oh'] = $obj->qty * -1;
                                $inv['created_by'] = $this->session->userdata('username');
                                $inv['created_date'] = date('Y-m-d H:i:s');

                                if ($this->mutasi_barang_model->insert_row('inv.t_brg_inventory', $inv)) {
                                    $inv_result++;
                                }
                            }
                            
                            //in stok
                            $set = 'qty_oh';
                            $field = $obj->qty;
                            $where = array(
                                'kd_produk' => $obj->kd_produk,
                                'kd_lokasi' => $kd_lokasi_t,
                                'kd_blok' => $kd_blok_t,
                                'kd_sub_blok' => $kd_sub_blok_t
                            );
                            
                            if ($this->mutasi_barang_model->select_inventory($where)) {
                                $sql = "UPDATE inv.t_brg_inventory SET qty_oh = qty_oh + " . $obj->qty . " WHERE kd_produk = '$obj->kd_produk' AND kd_lokasi = '$kd_lokasi_t' AND kd_blok = '$kd_blok_t' AND kd_sub_blok = '$kd_sub_blok_t'";
                                if ($this->mutasi_barang_model->query_update($sql)) {
                                    $inv_result++;
                                }
                            } else {
                                unset($inv);
                                $inv['kd_produk'] = $obj->kd_produk;
                                $inv['kd_lokasi'] = $kd_lokasi_t;
                                $inv['kd_blok'] = $kd_blok_t;
                                $inv['kd_sub_blok'] = $kd_sub_blok_t;
                                $inv['qty_oh'] = $obj->qty;
                                $inv['created_by'] = $this->session->userdata('username');
                                $inv['created_date'] = date('Y-m-d H:i:s');

                                if ($this->mutasi_barang_model->insert_row('inv.t_brg_inventory', $inv)) {
                                    $inv_result++;
                                }
                            }

                            $detail_result++;
                        }
                    }
                }
            }
        
        if($no_mutasi_sales != ''){
            $sql = "UPDATE inv.t_mutasi_barang SET status = 11 WHERE no_mutasi_stok = '$no_mutasi_sales'";
            $this->mutasi_barang_model->query_update($sql);
        }    


        $this->db->trans_complete();


        if ($header_result > 0 && $detail_result > 0 && $trx_result > 0 && $trx_out_result>0 && $inv_result > 0) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }
    
    
    public function update_row_out() {
        $no_mutasi_stok = isset($_POST['no_mutasi_stok']) ? $this->db->escape_str($this->input->post('no_mutasi_stok', TRUE)) : "";
        $tgl_mutasi = isset($_POST['tgl_mutasi']) ? $this->db->escape_str($this->input->post('tgl_mutasi', TRUE)) : "";
	$keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan',TRUE)) : "";
        $no_ref = isset($_POST['no_ref']) ? $this->db->escape_str($this->input->post('no_ref',TRUE)) : "";

        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();

        $header_result = 0;
        $detail_result = 0;
        $trx_result = 0;
        $inv_result = 0;

        if ((count($detail) == 0)) {
            echo '{"success":false,"errMsg":"Proses gagal"}';
            exit;
        }

        if ($tgl_mutasi) {
            $tgl_mutasi = date('Y-m-d', strtotime($tgl_mutasi));
        }

        $this->db->trans_start();

        unset($header_ms);
        $header_ms['no_mutasi_stok'] = $no_mutasi_stok;
        $header_ms['tgl_mutasi'] = $tgl_mutasi;
        $header_ms['keterangan'] = $keterangan;
        $header_ms['no_ref'] = $no_ref;
        $header_ms['userid'] = $this->session->userdata('username');
        $header_ms['created_by'] = $this->session->userdata('username');
        $header_ms['created_date'] = date('Y-m-d H:i:s');
        $header_ms['updated_by'] = $this->session->userdata('username');
        $header_ms['updated_date'] = date('Y-m-d H:i:s');
        $header_ms['status'] = 0;
        $header_ms['approval_out'] = $this->session->userdata('username');
//        $header_ms['approval_in'] = $this->session->userdata('username');
        $header_ms['tgl_approval_out']= date('Y-m-d H:i:s');
//        $header_ms['tgl_approval_in']= date('Y-m-d H:i:s');

       
            if ($this->mutasi_barang_model->insert_row('inv.t_mutasi_barang', $header_ms)) {
                $header_result++;
                if ($header_result > 0) {
                    foreach ($detail as $obj) {
                        unset($detail_ms);
                        $kd_lokasi = substr($obj->sub_asal, 0, 2);
                        $kd_blok = substr($obj->sub_asal, 2, 2);
                        $kd_sub_blok = substr($obj->sub_asal, 4, 2);
                        
                        $kd_lokasi_t = substr($obj->sub_tujuan, 0, 2);
                        $kd_blok_t = substr($obj->sub_tujuan, 2, 2);
                        $kd_sub_blok_t = substr($obj->sub_tujuan, 4, 2);
                        
                        $detail_ms['no_mutasi_stok'] = $no_mutasi_stok;
                        $detail_ms['kd_produk'] = $obj->kd_produk;
                        $detail_ms['kd_lokasi_awal'] = $kd_lokasi;
                        $detail_ms['kd_blok_awal'] = $kd_blok;
                        $detail_ms['kd_sub_blok_awal'] = $kd_sub_blok;
                        $detail_ms['kd_lokasi_tujuan'] = $kd_lokasi_t;
                        $detail_ms['kd_blok_tujuan'] = $kd_blok_t;
                        $detail_ms['kd_sub_blok_tujuan'] = $kd_sub_blok_t;
                        $detail_ms['qty'] = $obj->qty;

                        if ($this->mutasi_barang_model->insert_row('inv.t_mutasi_barang_detail', $detail_ms)) {
                            unset($trx);
                            $trx['kd_produk'] = $obj->kd_produk;
                            $trx['no_ref'] = $no_mutasi_stok;
                            $trx['kd_lokasi'] = $kd_lokasi;
                            $trx['kd_blok'] = $kd_blok;
                            $trx['kd_sub_blok'] = $kd_sub_blok;
                            $trx['qty_in'] = 0;
                            $trx['qty_out'] = $obj->qty;
                            $trx['type'] = 6;
                            $trx['created_by'] = $this->session->userdata('username');
                            $trx['created_date'] = date('Y-m-d H:i:s');
                            $trx['tgl_trx'] = date('Y-m-d H:i:s');

                            if ($this->mutasi_barang_model->insert_row('inv.t_trx_inventory', $trx)) {
                                $trx_out_result++;
                            }

                            
                            $where = array(
                                'kd_produk' => $obj->kd_produk,
                                'kd_lokasi' => $kd_lokasi,
                                'kd_blok' => $kd_blok,
                                'kd_sub_blok' => $kd_sub_blok
                            );
                            $sql = "UPDATE inv.t_brg_inventory SET qty_oh = qty_oh - " . $obj->qty . " WHERE kd_produk = '$obj->kd_produk' AND kd_lokasi = '$kd_lokasi' AND kd_blok = '$kd_blok' AND kd_sub_blok = '$kd_sub_blok'";

                            if ($this->mutasi_barang_model->select_inventory($where)) {                           
                                if ($this->mutasi_barang_model->query_update($sql)) {
                                    $inv_result++;
                                }
                            } else {
                                unset($inv);
                                $inv['kd_produk'] = $obj->kd_produk;
                                $inv['kd_lokasi'] = $kd_lokasi;
                                $inv['kd_blok'] = $kd_blok;
                                $inv['kd_sub_blok'] = $kd_sub_blok;
                                $inv['qty_oh'] = $obj->qty * -1;
                                $inv['created_by'] = $this->session->userdata('username');
                                $inv['created_date'] = date('Y-m-d H:i:s');

                                if ($this->mutasi_barang_model->insert_row('inv.t_brg_inventory', $inv)) {
                                    $inv_result++;
                                }
                            }

                            $detail_result++;
                        }
                    }
                }
            }
        



        $this->db->trans_complete();


        if ($header_result > 0 && $detail_result > 0 && $trx_result > 0 && $trx_out_result>0 && $inv_result > 0) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }
    
    public function update_row_in() {
        $no_mutasi_stok = isset($_POST['no_mutasi_stok']) ? $this->db->escape_str($this->input->post('no_mutasi_stok', TRUE)) : "";
        $tgl_mutasi = isset($_POST['tgl_mutasi_in']) ? $this->db->escape_str($this->input->post('tgl_mutasi_in', TRUE)) : "";
	$keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan',TRUE)) : "";
        $no_ref = isset($_POST['no_ref']) ? $this->db->escape_str($this->input->post('no_ref',TRUE)) : "";

        $detail = isset($_POST['detail']) ? json_decode($this->input->post('detail', TRUE)) : array();

        $header_result = 0;
        $detail_result = 0;
        $trx_result = 0;
        $inv_result = 0;

        if ((count($detail) == 0)) {
            echo '{"success":false,"errMsg":"Proses gagal"}';
            exit;
        }

        if ($tgl_mutasi) {
            $tgl_mutasi = date('Y-m-d', strtotime($tgl_mutasi));
        }

        $this->db->trans_start();

        unset($header_ms);
       
        $header_ms['updated_by'] = $this->session->userdata('username');
        $header_ms['updated_date'] = date('Y-m-d H:i:s');
        $header_ms['status'] = 1;
        $header_ms['approval_in'] = $this->session->userdata('username');
        $header_ms['tgl_approval_in']= date('Y-m-d H:i:s');

       
            if ($this->mutasi_barang_model->update_mutasi($no_mutasi_stok, $header_ms)) {
                $header_result++;
                if ($header_result > 0) {
                    foreach ($detail as $obj) {                                       
                        $kd_produk=$obj->kd_produk;
                        $kd_lokasi_t = substr($obj->sub_tujuan, 0, 2);
                        $kd_blok_t = substr($obj->sub_tujuan, 2, 2);
                        $kd_sub_blok_t = substr($obj->sub_tujuan, 4, 2);
                        $qty=$obj->qty;                        

                        
                            unset($trx);
                            $trx['kd_produk'] = $kd_produk;
                            $trx['no_ref'] = $no_mutasi_stok;
                            $trx['kd_lokasi'] = $kd_lokasi_t;
                            $trx['kd_blok'] = $kd_blok_t;
                            $trx['kd_sub_blok'] = $kd_sub_blok_t;
                            $trx['qty_in'] = $qty;
                            $trx['qty_out'] = 0;
                            $trx['type'] = 6;
                            $trx['created_by'] = $this->session->userdata('username');
                            $trx['created_date'] = date('Y-m-d H:i:s');
                            $trx['tgl_trx'] = date('Y-m-d H:i:s');

                            if ($this->mutasi_barang_model->insert_row('inv.t_trx_inventory', $trx)) {
                                $trx_out_result++;
                            }

                                                       
                            $where = array(
                                'kd_produk' => $kd_produk,
                                'kd_lokasi' => $kd_lokasi_t,
                                'kd_blok' => $kd_blok_t,
                                'kd_sub_blok' => $kd_sub_blok_t
                            );
                            
                            $updated_by=$this->session->userdata('username');
                            $updated_date=date('Y-m-d H:i:s');
                            $sql = "UPDATE inv.t_brg_inventory SET qty_oh = qty_oh + " . $qty . ",updated_by ='$updated_by',updated_date='$updated_date'
                                WHERE kd_produk = '$kd_produk' AND kd_lokasi = '$kd_lokasi_t' AND kd_blok = '$kd_blok_t' 
                                    AND kd_sub_blok = '$kd_sub_blok_t'";

                            if ($this->mutasi_barang_model->select_inventory($where)) {                           
                                if ($this->mutasi_barang_model->query_update($sql)) {
                                    $inv_result++;
                                }
                            } else {
                                unset($inv);
                                $inv['kd_produk'] = $kd_produk;
                                $inv['kd_lokasi'] = $kd_lokasi_t;
                                $inv['kd_blok'] = $kd_blok_t;
                                $inv['kd_sub_blok'] = $kd_sub_blok_t;
                                $inv['qty_oh'] = $qty;
                                $inv['created_by'] = $this->session->userdata('username');
                                $inv['created_date'] = date('Y-m-d H:i:s');

                                if ($this->mutasi_barang_model->insert_row('inv.t_brg_inventory', $inv)) {
                                    $inv_result++;
                                }
                            }

                            $detail_result++;
                        
                    }
                }
            }
        



        $this->db->trans_complete();


        if ($header_result > 0 && $detail_result > 0 && $trx_out_result>0 && $inv_result > 0) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }
    
    public function search_mutasi() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
                
        $result = $this->mutasi_barang_model->search_mutasi($search,$start, $limit);


        echo $result;
    }

}