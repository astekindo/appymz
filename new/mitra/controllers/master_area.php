<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master_area extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('master_area_model', 'master_area');
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
        $result = $this->master_area->get_rows($search, $start, $limit);

        echo $result;
    }

    public function finalGetCustomers() {
        $offset = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kdArea = isset($_POST['kd_area']) ? $this->db->escape_str($this->input->post('kd_area', TRUE)) : '';
        $result = $this->master_area->getCustomers($limit, $offset, $search, $kdArea);

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
            $result = $this->master_area->get_row($id);

            return $result;
        }
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row() {
        $kd_area = isset($_POST['kd_area']) ? $this->db->escape_str($this->input->post('kd_area', TRUE)) : '';
        $nama_area = isset($_POST['nama_area']) ? $this->db->escape_str($this->input->post('nama_area', TRUE)) : '';
        $kd_propinsi = isset($_POST['kd_propinsi']) ? $this->db->escape_str($this->input->post('kd_propinsi', TRUE)) : '';
        $status = isset($_POST['status']) ? $this->db->escape_str($this->input->post('status', TRUE)) : 1;

        if (!$kd_area) { //save   
            $inisial = strtoupper(substr($nama_area, 0, 1));
            $kd_area = $inisial . $this->master_area->get_kode_sequence($inisial, 3);
            $data = array(
                'kd_area' => $kd_area,
                'nama_area' => strtoupper($nama_area),
                'kd_propinsi' => $kd_propinsi,
                'status' => $status
            );
//            var_dump($data);exit;

            if ($this->master_area->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        } else { //edit         			
            $datau = array(
                'nama_area' => strtoupper($nama_area),
                'kd_propinsi' => $kd_propinsi,
                'status' => $status
            );

            if ($this->master_area->update_row($kd_area, $datau)) {
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
                    if ($this->master_area->delete_row($id)) {
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
        $kd_area = isset($_POST['kd_area']) ? $this->db->escape_str($this->input->post('kd_area', TRUE)) : FALSE;

        if ($this->master_area->delete_row($kd_area)) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

}
