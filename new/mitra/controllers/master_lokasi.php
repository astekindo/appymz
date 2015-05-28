<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master_lokasi extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('master_lokasi_model');
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
        $peruntukan = $this->session->userdata('user_peruntukan');
        $result = $this->master_lokasi_model->get_rows($peruntukan,$search, $start, $limit);

        echo $result;
    }
    
    public function get_form(){
    	
    	echo '{"success":true,
				"data":{
					"user_peruntukan":"'. $this->session->userdata('user_peruntukan') .'",    
					}
			}';
    }
    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_row() {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $result = $this->master_lokasi_model->get_row($id);

            return $result;
        }
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row() {
        $kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi', TRUE)) : FALSE;
        $nama_lokasi = isset($_POST['nama_lokasi']) ? $this->db->escape_str($this->input->post('nama_lokasi', TRUE)) : FALSE;
        $nama_lokasi2 = isset($_POST['nama_lokasi2']) ? $this->db->escape_str($this->input->post('nama_lokasi2', TRUE)) : FALSE;
        $kd_peruntukan = isset($_POST['kd_peruntukan']) ? $this->db->escape_str($this->input->post('kd_peruntukan', TRUE)) : FALSE;
        $aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif', TRUE)) : FALSE;
        // $aktif = '1';
        if ($aktif == '0')
            $aktif = 'FALSE';
        else
            $aktif = 'TRUE';

        if (!$kd_lokasi) { //save       
            $created_by = $this->session->userdata('username');
            $created_date = date('Y-m-d H:i:s');

            $data = array(
                'kd_lokasi' => $this->master_lokasi_model->get_kode_sequence("G", 2),
                'nama_lokasi' => strtoupper($nama_lokasi),
                'kd_peruntukan' => $kd_peruntukan,
                'created_by' => $created_by,
                'created_date' => $created_date,
                'nama_lokasi2' => strtoupper($nama_lokasi2),
                'aktif' => $aktif
            );

            if ($this->master_lokasi_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        } else { //edit          
            $updated_by = $this->session->userdata('username');
            $updated_date = date('Y-m-d H:i:s');

            $datau = array(
                'nama_lokasi' => strtoupper($nama_lokasi),
                'updated_by' => $updated_by,
                'updated_date' => $updated_date,
                'kd_peruntukan' => $kd_peruntukan,
                'aktif' => $aktif,
                'nama_lokasi2' => strtoupper($nama_lokasi2),
            );

            if ($this->master_lokasi_model->update_row($kd_lokasi, $datau)) {
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
                    if ($this->master_lokasi_model->delete_row($id)) {
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
        $kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi', TRUE)) : FALSE;

        if ($this->master_lokasi_model->delete_row($kd_lokasi)) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

}