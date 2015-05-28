<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mac_address extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('mac_address_model');
    }

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->mac_address_model->get_rows($search, $start, $limit);

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
            $result = $this->mac_address_model->get_row($id);

            return $result;
        }
    }

    public function update_row() {
        $mac_address = isset($_POST['mac_address']) ? $this->db->escape_str($this->input->post('mac_address', TRUE)) : FALSE;
        $nama = isset($_POST['nama']) ? $this->db->escape_str($this->input->post('nama', TRUE)) : FALSE;
        $aktif = isset($_POST['rb-aktif']) ? $this->db->escape_str($this->input->post('rb-aktif', TRUE)) : FALSE;
        $keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan', TRUE)) : FALSE;
        
        $result = $this->mac_address_model->select_temp($mac_address);
        if (!empty($result)) { //Update
            $datau = array(
                'nama' => $nama,
                'status' => $aktif,
                'keterangan' => $keterangan
            );

            if ($this->mac_address_model->update_row($mac_address, $datau)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }         
                       
         } else{
        //save
            $data = array(
                'mac_address' => $mac_address,
                'nama' => $nama,
                'status' => $aktif,
                'keterangan' => $keterangan,
            );

            if ($this->mac_address_model->insert_row($data)) {
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
            foreach ($records as $mac_address) {
                if ($id != '') {

                    $this->db->trans_start();
                    if ($this->$mac_address->delete_row($id)) {
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
        $mac_address = isset($_POST['kd_mac_addres']) ? $this->db->escape_str($this->input->post('kd_mac_addres', TRUE)) : FALSE;

        if ($this->mac_address_model->delete_row($mac_address)) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }

}

?>
