<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Account_master_account extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('account_master_account_model','macc_model');
    }
	public function get_kd_akun(){
		echo $this->macc_model->get_kd_akun();
	}
	
    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->macc_model->get_rows($search, $start, $limit);

        echo $result;
    }

    public function get_row() {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $result = $this->macc_model->get_row($id);

            return $result;
        }
    }
	
    public function update_row() {
        $kd_akun = isset($_POST['kd_akun']) ? $this->db->escape_str($this->input->post('kd_akun', TRUE)) : FALSE;        
        $parent_kd_akun = isset($_POST['parent_kd_akun']) ? $this->db->escape_str($this->input->post('parent_kd_akun', TRUE)) : FALSE;
        $nama = isset($_POST['nama']) ? $this->db->escape_str($this->input->post('nama', TRUE)) : FALSE;
        $deskripsi = isset($_POST['deskripsi']) ? $this->db->escape_str($this->input->post('deskripsi', TRUE)) : FALSE;
        $dk = isset($_POST['dk']) ? $this->db->escape_str($this->input->post('dk', TRUE)) : FALSE;        
        $type_akun = isset($_POST['type_akun']) ? $this->db->escape_str($this->input->post('type_akun', TRUE)) : FALSE;        
        $labarugi = isset($_POST['labarugi']) ? $this->db->escape_str($this->input->post('labarugi', TRUE)) : FALSE;        
        $neraca = isset($_POST['neraca']) ? $this->db->escape_str($this->input->post('neraca', TRUE)) : FALSE;        
        $header_status = isset($_POST['header_status']) ? $this->db->escape_str($this->input->post('header_status', TRUE)) : FALSE;        
        $aktif = '1';
        if($labarugi=='on'){
            $labarugi='true';
        }else{
            $labarugi='false';
        }
        
        if($neraca=='on'){
            $neraca='true';
        }else{
            $neraca='false';
        }
        if($header_status=='on'){
            $header_status='true';
        }else{
            $header_status='false';
        }

        if ($this->macc_model->select_akun($kd_akun)) { //edit       
            $updated_by = $this->session->userdata('username');
            $updated_date = date('Y-m-d H:i:s');

            $datau = array(
                'parent_kd_akun'=>$parent_kd_akun,
                'nama'=>$nama,
                'deskripsi'=>$deskripsi,
                'dk'=>$dk,
                'type_akun'=>$type_akun,
                'updated_by'=>$updated_by,
                'updated_date'=>$updated_date, 
                'labarugi'=>$labarugi, 
                'neraca'=>$neraca, 
                'header_status'=>$header_status
            );

            if ($this->macc_model->update_row($kd_akun, $datau)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        } else { //save     
            $created_by = $this->session->userdata('username');
            $created_date = date('Y-m-d H:i:s');

            $data = array(
                'kd_akun' => $kd_akun,                                
                'parent_kd_akun'=>$parent_kd_akun,
                'nama'=>$nama,
                'deskripsi'=>$deskripsi,
                'dk'=>$dk,
                'type_akun'=>$type_akun,
                'aktif'=>$aktif,    
                'created_by'=>$created_by,
                'created_date'=>$created_date,   
                'labarugi'=>$labarugi, 
                'neraca'=>$neraca, 
                'header_status'=>$header_status
            );

            if ($this->macc_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        }

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function delete_rows() {
        $postdata = isset($_POST['postdata']) ? $this->input->post('postdata', TRUE) : array();

        if (count($postdata) > 0) {
            $records = explode(';', $this->input->post('postdata'));
            $i = 0;
            foreach ($records as $id) {
                if ($id != '') {

                    $this->db->trans_start();
                    if ($this->$kd_akun->delete_row($id)) {
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
    public function delete_row() {
        $kd_akun = isset($_POST['kd_akun']) ? $this->db->escape_str($this->input->post('kd_akun', TRUE)) : FALSE;

        if ($this->macc_model->delete_row($kd_akun)) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }
    
    public function get_akun_twin() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->macc_model->get_akun_twin($search, $start, $limit);

        echo $result;
    }
    

}

?>
