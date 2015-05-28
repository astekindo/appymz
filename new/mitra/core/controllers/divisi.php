<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Divisi extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('divisi_model');
    }

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->divisi_model->get_rows($search, $start, $limit);

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
            $result = $this->divisi_model->get_row($id);

            return $result;
        }
    }

    public function update_row() {
        $kd_divisi = isset($_POST['kd_divisi']) ? $this->db->escape_str($this->input->post('kd_divisi', TRUE)) : FALSE;
        $nama_divisi = isset($_POST['nama_divisi']) ? $this->db->escape_str($this->input->post('nama_divisi', TRUE)) : FALSE;
        $kepala_divisi = isset($_POST['kepala_divisi']) ? $this->db->escape_str($this->input->post('kepala_divisi', TRUE)) : FALSE;
        $aktif = '1';

        if (!$kd_divisi) { //save     
            $created_by = $this->session->userdata('username');
            $created_date = date('Y-m-d H:i:s');

            $data = array(
                'kd_divisi' => 'DIV'.$this->divisi_model->get_kode_sequence("DIV", 2),
                'nama_divisi' => $nama_divisi,
                'kepala_divisi' => $kepala_divisi
            );

            if ($this->divisi_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        } else { //edit       
            $updated_by = $this->session->userdata('username');
            $updated_date = date('Y-m-d H:i:s');

            $datau = array(
                'nama_divisi' => $nama_divisi,
                'kepala_divisi' => $kepala_divisi
            );

            if ($this->divisi_model->update_row($kd_divisi, $datau)) {
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
                    if ($this->$kd_divisi->delete_row($id)) {
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
        $kd_divisi = isset($_POST['kd_divisi']) ? $this->db->escape_str($this->input->post('kd_divisi', TRUE)) : FALSE;

        if ($this->divisi_model->delete_row($kd_divisi)) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

}

?>
