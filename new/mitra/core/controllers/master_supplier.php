<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master_supplier extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('master_supplier_model');
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

        $result = $this->master_supplier_model->get_rows($search, $start, $limit);

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
            $result = $this->master_supplier_model->get_row($id);

            return $result;
        }
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row() {
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : FALSE;
        $nama_supplier = isset($_POST['nama_supplier']) ? $this->db->escape_str($this->input->post('nama_supplier', TRUE)) : FALSE;
        $alias_supplier = isset($_POST['alias_supplier']) ? $this->db->escape_str($this->input->post('alias_supplier', TRUE)) : FALSE;
        $alamat = isset($_POST['alamat']) ? $this->db->escape_str($this->input->post('alamat', TRUE)) : FALSE;
        $telpon = isset($_POST['telpon']) ? $this->db->escape_str($this->input->post('telpon', TRUE)) : FALSE;
        $fax = isset($_POST['fax']) ? $this->db->escape_str($this->input->post('fax', TRUE)) : FALSE;
        $email = isset($_POST['email']) ? $this->db->escape_str($this->input->post('email', TRUE)) : FALSE;
        $pic = isset($_POST['pic']) ? $this->db->escape_str($this->input->post('pic', TRUE)) : FALSE;
        $pkp = isset($_POST['pkp']) ? $this->db->escape_str($this->input->post('pkp', TRUE)) : FALSE;
        $status = isset($_POST['status']) ? $this->db->escape_str($this->input->post('status', TRUE)) : FALSE;
        $npwp = isset($_POST['npwp']) ? $this->db->escape_str($this->input->post('npwp', TRUE)) : FALSE;
        $top = isset($_POST['top']) ? $this->db->escape_str($this->input->post('top', TRUE)) : FALSE;
        $update_top = isset($_POST['update_top']) ? $this->db->escape_str($this->input->post('update_top', TRUE)) : FALSE;

        $aktif = '1';

        $check_result = $this->master_supplier_model->check_data('nama_supplier', $nama_supplier, 'mst.t_supplier');
		
		if($kd_supplier){
			$field_result = $this->master_supplier_model->get_data_field('nama_supplier','kd_supplier',$kd_supplier,'mst.t_supplier');
			if($field_result->nama_supplier == $nama_supplier){
				$check_result = FALSE;
			}
		}
		
        if ($check_result) {
            $errMsg = "Data dengan Nama Supplier: " . $nama_supplier . " Sudah Ada di dalam Database. Silahkan Input Ulang";
            $result = '{"success":false,"errMsg":"' . $errMsg . '"}';
            echo $result;
            exit;
        }

        if (!$kd_supplier) { //save        
            $created_by = $this->session->userdata('username');
            $created_date = date('Y-m-d H:i:s');

            $data = array(
                'kd_supplier' => "01".strtoupper(substr($nama_supplier,0,1)).$this->master_supplier_model->get_kode_sequence("01".strtoupper(substr($nama_supplier,0,1)), 3),
                'nama_supplier' => strtoupper($nama_supplier),
                'alias_supplier' => $alias_supplier,
                'alamat' => strtoupper($alamat),
                'telpon' => $telpon,
                'fax' => $fax,
                'email' => $email,
                'pic' => strtoupper($pic),
                'pkp' => $pkp,
                'status' => $status,
                'npwp' => $npwp,
                'created_by' => $created_by,
                'created_date' => $created_date,
                'top' => $top,
                'aktif' => $aktif
            );

            if ($this->master_supplier_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        } else { //edit       
            $updated_by = $this->session->userdata('username');
            $updated_date = date('Y-m-d H:i:s');

            $datau = array(
                'nama_supplier' => strtoupper($nama_supplier),
                'alias_supplier' => $alias_supplier,
                'alamat' => strtoupper($alamat),
                'telpon' => $telpon,
                'fax' => $fax,
                'email' => $email,
                'pic' => strtoupper($pic),
                'pkp' => $pkp,
                'status' => $status,
                'npwp' => $npwp,
                'updated_by' => $updated_by,
                'updated_date' => $updated_date,
                'top' => $top,
                'aktif' => $aktif
            );

            if ($this->master_supplier_model->update_row($kd_supplier, $datau)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            if ($update_top === '1') {
                $supplier_per_brg = array(
                    'waktu_top' => $top
                );
                $this->master_supplier_model->update_supplier_per_brg($kd_supplier, $supplier_per_brg);
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
                    if ($this->master_supplier_model->delete_row($id)) {
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
        $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier', TRUE)) : FALSE;

        if ($this->master_supplier_model->delete_row($kd_supplier)) {
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
        $result = $this->master_supplier_model->get_all();

        echo $result;
    }

}
