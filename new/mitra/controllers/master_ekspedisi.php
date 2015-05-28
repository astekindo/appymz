<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master_ekspedisi extends MY_Controller {

    protected $test   = false;
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('master_ekspedisi_model');
    }

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_rows_master(){
        $start      = $this->form_data('start',0);
        $limit      = $this->form_data('limit',$this->config->item("length_records"));
        $search     = $this->form_data('query','');

        $this->print_result_json($this->master_ekspedisi_model->get_rows_master($search, $limit, $start),$this->test);
	}

	public function get_rows_price($kd_ekspedisi=''){
        $start      = $this->form_data('start',0);
        $limit      = $this->form_data('limit',$this->config->item("length_records"));
        $search     = $this->form_data('query','');
		if($kd_ekspedisi ==''){
            $kd_ekspedisi   = $this->form_data('fieldId',$kd_ekspedisi);
		}
        $this->print_result_json($this->master_ekspedisi_model->get_rows_price($kd_ekspedisi, $search, $start, $limit),$this->test);
	}

	/**
	 * @author dhamarsu
	 * @editedby bambang
	 * @lastedited 18 jun 2014
	 */
	public function get_row_master(){
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $id     = $this->form_data('id',null);
            $this->print_result_json($this->master_ekspedisi_model->get_row_master($id), $this->test);
        }
	}

	public function get_row_price(){
		if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $id     = $this->form_data('id',null);
            $this->print_result_json($this->master_ekspedisi_model->get_row_price($id), $this->test);
        }
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
        $action         = $this->form_data('action',false);
		$kd_ekspedisi   = $this->form_data('kd_ekspedisi',false);
		$nama_ekspedisi = $this->form_data('nama_ekspedisi',false);
		$aktif          = $this->form_data('aktif',false);
		$tujuan         = $this->form_data('tujuan',false);
		$kd_satuan      = $this->form_data('kd_satuan',false);
		$nilai_satuan  = $this->form_data('nilai_satuan',false);
		$rp_harga       = $this->form_data('rp_harga',false);


        $result         = array('success' => false, 'errMsg' => 'Process Failed..');

        switch($action) {
            case "save_master":
                $kd_ekspedisi = $this->master_ekspedisi_model->get_kode_sequence("EX",3);
                $data = array(
                  'kd_ekspedisi'	=>	$kd_ekspedisi,
                  'nama_ekspedisi'	=>	$nama_ekspedisi,
                  'aktif'	        =>	$aktif,
                );

                $result['success'] = $this->master_ekspedisi_model->insert_row("mst.t_ekpedisi",$data);
                break;

            case "update_master":
                $datau = array(
                  'nama_ekspedisi'  =>	$nama_ekspedisi,
                  'aktif'	        =>	$aktif
                );

                $result['success'] = $this->master_ekspedisi_model->update_row("mst.t_ekpedisi", $kd_ekspedisi, $datau);
                break;

            case "save_price":
                $data = array(
                  'kd_ekspedisi'	=>	$kd_ekspedisi,
                  'tujuan'	        =>	$tujuan,
                  'kd_satuan'	    =>	$kd_satuan,
                  'rp_harga'	    =>	$rp_harga,
                  'nilai_satuan'	=>	$nilai_satuan,
                  'keterangan'	    =>	$rp_harga
                );

                $result['success'] = $this->master_ekspedisi_model->insert_row("mst.t_ekspedisi_price",$data);
                break;

            case "update_price":
                $datau = array(
                  'tujuan'          =>	$tujuan,
                  'kd_satuan'	    =>	$kd_satuan,
                  'rp_harga'	    =>	$rp_harga,
                  'nilai_satuan'	=>	$nilai_satuan,
                  'keterangan'	    =>	$rp_harga
                );

                $result['success'] = $this->master_ekspedisi_model->update_row("mst.t_ekspedisi_price", $kd_ekspedisi, $datau);
                break;
        }

        if($result['success']) $result['errMsg'] = '';
        echo json_encode($result);
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_rows(){
		$postdata = isset($_POST['postdata']) ? $this->input->post('postdata',TRUE) : array();

		if(count($postdata) > 0){
			$records = explode(';', $this->input->post('postdata'));
	        $i = 0;
	        foreach ($records as $id) {
	            if ($id != '') {
	                $kd = explode('-', $id);
	                $this->db->trans_start();
	                if ($this->master_ekspedisi_model->delete_row($kd[0],$kd[1])) {
	                    $i++;
	                }
	                $this->db->trans_complete();
	            }

	        }
	        if ($i > 0) {
	            $result = '{"success":true,"errMsg":""}';
	        } else {
	            $result = '{"success":false,"errMsg":"Process Failed.."}';
	        }
	        echo $result;
		}
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */

	public function get_ekpedisi(){
		$result = $this->master_ekspedisi_model->get_ekpedisi();

        echo $result;
	}

	public function get_produk($search_by = ""){

		$keyword = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : "";
		$result = $this->master_ekspedisi_model->get_all_produk($search_by, $keyword);
        echo $result;
	}

    public function delete_row(){
        $kd_ekspedisi = isset($_POST['kd_ekspedisi']) ? $this->db->escape_str($this->input->post('kd_ekspedisi',TRUE)) : FALSE;
        $tujuan = isset($_POST['tujuan']) ? $this->db->escape_str($this->input->post('tujuan',TRUE)) : FALSE;

        if ($this->master_ekspedisi_model->delete_row($kd_ekspedisi, $tujuan)) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    public function delete_row_price(){
//        $kd_ekspedisi = isset($_POST['kd_ekspedisi']) ? $this->db->escape_str($this->input->post('kd_ekspedisi',TRUE)) : FALSE;
//        $tujuan = isset($_POST['tujuan']) ? $this->db->escape_str($this->input->post('tujuan',TRUE)) : FALSE;
//
//        if ($this->master_ekspedisi_model->delete_row($kd_ekspedisi, $tujuan)) {
//            $result = '{"success":true,"errMsg":""}';
//        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
//        }
        echo $result;
    }
}
