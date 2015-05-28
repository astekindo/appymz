<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Blok_lokasi extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('blok_lokasi_model');
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
        $this->session->userdata('user_peruntukan');

        $result = $this->blok_lokasi_model->get_rows($search, $start, $limit);

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
            $id1 = isset($_POST['id1']) ? $this->db->escape_str($this->input->post('id1', TRUE)) : NULL;
            $result = $this->blok_lokasi_model->get_row($id, $id1);

            return $result;
        }
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row() {
        $kd_blok = isset($_POST['kd_blok']) ? $this->db->escape_str($this->input->post('kd_blok', TRUE)) : FALSE;
        $nama_blok = isset($_POST['nama_blok']) ? $this->db->escape_str($this->input->post('nama_blok', TRUE)) : FALSE;
        $nama_blok2 = isset($_POST['nama_blok2']) ? $this->db->escape_str($this->input->post('nama_blok2', TRUE)) : FALSE;
        $kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi', TRUE)) : FALSE;
        $aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif', TRUE)) : '0';

        if (!$kd_blok) { //save
            $created_by = $this->session->userdata('username');
            $created_date = date('Y-m-d H:i:s');

            $data = array(
                'kd_blok' => $this->blok_lokasi_model->get_kode_sequence('B', 2),
                'nama_blok' => strtoupper($nama_blok),
                'kd_lokasi' => $kd_lokasi,
                'created_by' => $created_by,
                'created_date' => $created_date,
                'aktif' => '1',
                'nama_blok2' => strtoupper($nama_blok2),
            );

            if ($this->blok_lokasi_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        } else { //edit
            $updated_by = $this->session->userdata('username');
            $updated_date = date('Y-m-d H:i:s');

            $datau = array(
                'nama_blok' => strtoupper($nama_blok),
                'updated_by' => $updated_by,
                'updated_date' => $updated_date,
                'aktif' => '1',
                'nama_blok2' => strtoupper($nama_blok2),
            );
            if ($this->blok_lokasi_model->update_row($kd_blok, $kd_lokasi, $datau)) {
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
                    $kd = explode('-', $id);
                    $this->db->trans_start();
                    if ($this->blok_lokasi_model->delete_row($kd[0], $kd[1])) {
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

    public function delete_row() {
        $kd_blok = isset($_POST['kd_blok']) ? $this->db->escape_str($this->input->post('kd_blok', TRUE)) : FALSE;
        $kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi', TRUE)) : FALSE;

        if ($this->blok_lokasi_model->delete_row($kd_blok, $kd_lokasi)) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

    public function get_all() {
        $peruntukan = $this->session->userdata('user_peruntukan');
        $this->print_result_json($this->blok_lokasi_model->get_all(intval($peruntukan)), true);
    }
    public function get_lokasi() {
        $peruntukan = $this->session->userdata('user_peruntukan');
        $result = $this->blok_lokasi_model->get_lokasi(intval($peruntukan));
        return $result;
    }

}