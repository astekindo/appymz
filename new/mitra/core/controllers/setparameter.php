<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setparameter extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('setparameter_model');
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

        $result = $this->setparameter_model->get_rows($search, $start, $limit);

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_row() {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $result = $this->setparameter_model->get_row($id);

            return $result;
        }
    }

    public function get_type() {

        $result = '{success:true,data:[{"id":"1","type":"Akun"},{"id":"2","type":"Nilai"}]}';
        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row() {
        $kd_parameter = isset($_POST['kd_parameter']) ? $this->db->escape_str($this->input->post('kd_parameter', TRUE)) : FALSE;
        $nilai_parameter = isset($_POST['nilai_parameter']) ? $this->db->escape_str($this->input->post('nilai_parameter', TRUE)) : FALSE;
        $nama_parameter = isset($_POST['nama_parameter']) ? $this->db->escape_str($this->input->post('nama_parameter', TRUE)) : FALSE;
        $ref_parameter = isset($_POST['ref_parameter']) ? $this->db->escape_str($this->input->post('ref_parameter', TRUE)) : FALSE;
        $keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan', TRUE)) : FALSE;
        $type_parameter = isset($_POST['type_parameter']) ? $this->db->escape_str($this->input->post('type_parameter', TRUE)) : '0';

        if (!$kd_parameter) { //save  
            $created_by = $this->session->userdata('username');
            $created_date = date('Y-m-d H:i:s');

            $data = array(
                'kd_parameter' => 'PRM' . $this->setparameter_model->get_kode_sequence('PRM', 1),
                'type_parameter' => $type_parameter,
                'nama_parameter' => $nama_parameter,
                'nilai_parameter' => $nilai_parameter,
                'ref_parameter' => $ref_parameter,
                'keterangan' => $keterangan,
                'created_by' => $created_by,
                'created_date' => $created_date
            );

            if ($this->setparameter_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit      			
            $updated_by = $this->session->userdata('username');
            $updated_date = date('Y-m-d H:i:s');

            $datau = array(
                'type_parameter' => $type_parameter,
                'nama_parameter' => $nama_parameter,
                'nilai_parameter' => $nilai_parameter,
                'ref_parameter' => $ref_parameter,
                'keterangan' => $keterangan,
                'updated_by' => $updated_by,
                'updated_date' => $updated_date
            );
            if ($this->setparameter_model->update_row($kd_parameter, $datau)) {
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
    public function delete_row() {
        $kd_parameter = isset($_POST['kd_parameter']) ? $this->input->post('kd_parameter', TRUE) : FALSE();

        if ($this->setparameter_model->delete_row($kd_parameter)) {
            $result = '{"success":true,"errMsg":""}';
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
    public function get_all() {
        $result = $this->setparameter_model->get_all();

        echo $result;
    }
}