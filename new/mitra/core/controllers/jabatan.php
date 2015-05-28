<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jabatan extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('jabatan_model');
    }

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->jabatan_model->get_rows($search, $start, $limit);

        echo $result;
    }

    public function get_row() {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $result = $this->jabatan_model->get_row($id);

            return $result;
        }
    }
    public function update_row() {
        $kd_jabatan = isset($_POST['kd_jabatan']) ? $this->db->escape_str($this->input->post('kd_jabatan', TRUE)) : FALSE;        
        $kd_parent_jabatan = isset($_POST['kdparent']) ? $this->db->escape_str($this->input->post('kdparent', TRUE)) : FALSE;
        $nama_jabatan = isset($_POST['nama_jabatan']) ? $this->db->escape_str($this->input->post('nama_jabatan', TRUE)) : FALSE;
        $lvl_jabatan = isset($_POST['lvl_jabatan']) ? $this->db->escape_str($this->input->post('lvl_jabatan', TRUE)) : FALSE;
        $kd_divisi = isset($_POST['kddivisi']) ? $this->db->escape_str($this->input->post('kddivisi', TRUE)) : FALSE;        
        $aktif = '1';
        
        if(!$kd_parent_jabatan){
            $lvl_jabatan='0';
        }else{
            $lvl_jabatan=$this->jabatan_model->get_lvljabatan($kd_parent_jabatan);
//            $lvl_jabatan=$lvl_jabatan+1;
        }

        if (!$kd_jabatan) { //save     
            $created_by = $this->session->userdata('username');
            $created_date = date('Y-m-d H:i:s');

            $data = array(
                'kd_jabatan' => 'J'.$this->jabatan_model->get_kode_sequence("J", 3),                                
                'kd_parent_jabatan'=>$kd_parent_jabatan,
                'nama_jabatan'=>$nama_jabatan,
                'lvl_jabatan'=>$lvl_jabatan,
                'kd_divisi'=>$kd_divisi                
            );

            if ($this->jabatan_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        } else { //edit       
            $updated_by = $this->session->userdata('username');
            $updated_date = date('Y-m-d H:i:s');

            $datau = array(
                'kd_parent_jabatan'=>$kd_parent_jabatan,
                'nama_jabatan'=>$nama_jabatan,
                'lvl_jabatan'=>$lvl_jabatan,
                'kd_divisi'=>$kd_divisi
            );

            if ($this->jabatan_model->update_row($kd_jabatan, $datau)) {
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
                    if ($this->$kd_jabatan->delete_row($id)) {
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
        $kd_jabatan = isset($_POST['kd_jabatan']) ? $this->db->escape_str($this->input->post('kd_jabatan', TRUE)) : FALSE;

        if ($this->jabatan_model->delete_row($kd_jabatan)) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }


}

?>
