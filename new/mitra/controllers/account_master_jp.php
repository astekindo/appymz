<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_master_jp
 *
 * @author faroq
 */
class account_master_jp extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('account_master_jp_model','mjp_model') ;
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

        $result = $this->mjp_model->get_rows($search, $start, $limit);

        echo $result;
    }

    public function get_rows_akun() {
//		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
//		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->mjp_model->get_rows_akun($search);

        echo $result;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_row() {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'POST')) {
            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $result = $this->mjp_model->get_row($id);

            return $result;
        }
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row() {
        $kd_transaksi = isset($_POST['kd_transaksi']) ? $this->db->escape_str($this->input->post('kd_transaksi', TRUE)) : FALSE;
        $nama_transaksi = isset($_POST['nama_transaksi']) ? $this->db->escape_str($this->input->post('nama_transaksi', TRUE)) : FALSE;
        $aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif', TRUE)) : '0';
        $data_akun = isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)) : array();
        $retval = 0;

        if (!$kd_transaksi) { //save    
            $kd_transaksi='TP-' . $this->mjp_model->get_kode_sequence('TP-', 3);
            $data = array(
                'kd_transaksi' => $kd_transaksi,
                'nama_transaksi' => $nama_transaksi,
                'aktif' => '1'
            );
            $this->db->trans_start();
            if ($this->mjp_model->insert_row('acc.t_mjurnalpenutup', $data)) {
                $retval++;
                if (count($data_akun) > 0) {
                    foreach ($data_akun as $obj) {

                        $data_detail = array(
                            'kd_transaksi' => $kd_transaksi,
                            'kd_akun' => $obj->kd_akun,
                            'dk_akun' => $obj->dk_akun,
                            'dk_transaksi' => $obj->dk_transaksi
                        );
                        if ($this->mjp_model->insert_row('acc.t_mjurnalpenutup_detail', $data_detail)) {
                            $retval++;
                        }
                        unset($data_detail);
                    }
                }
            } else {
                $retval = 0;
            }
            $this->db->trans_complete();
        } else { //edit      			
//            $updated_by = $this->session->userdata('username');
//            $updated_date = date('Y-m-d H:i:s');
            $datau = array(
                'nama_transaksi' => $nama_transaksi,
                'aktif' => '1'
            );

            $this->db->trans_start();
            if ($this->mjp_model->update_row('acc.t_mjurnalpenutup', $kd_transaksi, $datau)) {
                $retval++;
                if (count($data_akun) > 0) {
                    if ($this->mjp_model->cek_exists_rowakun($kd_transaksi) > 0) {
                        $this->mjp_model->delete_rowAll($kd_transaksi);
                    }

                    foreach ($data_akun as $obj) {

                        $data_detail = array(
                            'kd_transaksi' => $kd_transaksi,
                            'kd_akun' => $obj->kd_akun,
                            'dk_akun' => $obj->dk_akun,
                            'dk_transaksi' => $obj->dk_transaksi
                        );

                        if ($this->mjp_model->insert_row('acc.t_mjurnalpenutup_detail', $data_detail)) {
                            $recdataakun = "insert row sucses";
                            $retval++;
                        }
                        unset($data_detail);
                    }
                } else {
                    if ($this->mjp_model->cek_exists_rowakun($kd_transaksi) > 0) {
                        $this->mjp_model->delete_rowAll($kd_transaksi);
                    }
                }
            } else {
                $retval = 0;
            }
            $this->db->trans_complete();
        }
        if ($retval > 0) {
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

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function delete_row() {
        $kd_transaksi = isset($_POST['kd_transaksi']) ? $this->db->escape_str($this->input->post('kd_transaksi', TRUE)) : FALSE;

        $data = array(
            'aktif' => '0'
        );

        if ($this->mjp_model->delete_row($kd_transaksi, $data)) {
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
        $result = $this->mjp_model->get_all();

        echo $result;
    }
}

?>
