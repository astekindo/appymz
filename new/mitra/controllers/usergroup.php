<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usergroup extends MY_Controller {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('usergroup_model');
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

        $result = $this->usergroup_model->get_rows($search, $start, $limit);

        echo $result;
    }

    public function get_cbusergroup($idgroup = NULL) {
        $result = $this->usergroup_model->get_cbusergroup($idgroup);
        echo $result;
    }

    public function get_row() {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $id1 = isset($_POST['id1']) ? $this->db->escape_str($this->input->post('id1', TRUE)) : NULL;
            $result = $this->usergroup_model->get_row($id, $id1);

            return $result;
        }
    }

    public function get_menu() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->usergroup_model->get_menu($search, $start, $limit);

        echo $result;
    }

    public function genMenuC($findhead, $child) {
        $resArr = array();
        foreach ($child as $c) {
            if ($c->parent == $findhead) {
                array_push($resArr, $c);
                $arrget = $this->genMenuC($c->kd_menu, $child);
                if (count($arrget) > 0) {
                    foreach ($arrget as $ag) {
                        array_push($resArr, $ag);
                    }
                }
            }
        }
        return $resArr;
    }

    public function genMenu($head, $child) {
        $resArr = array();
        foreach ($head as $h) {
            array_push($resArr, $h);
            $arrch = $this->genMenuC($h->kd_menu, $child);
            if (count($arrch) > 0) {
                foreach ($arrch as $ac) {
                    array_push($resArr, $ac);
                }
            }
        }
        return $resArr;
    }

    public function get_all_menu() {
        $kd_group = isset($_POST['kd_group']) ? $this->db->escape_str($this->input->post('kd_group', TRUE)) : FALSE;
//        echo $kd_group;
        $head = $this->usergroup_model->get_tree_child('');
        $child = $this->usergroup_model->get_tree_menu();
        $arrs = $this->genMenu($head, $child);
        
        $gmenu=$this->usergroup_model->get_groupmenu($kd_group);
        
        if(count($gmenu)>0){
            foreach ($gmenu as $value) {
                foreach ($arrs as $v) {
                    if($v->kd_menu === $value->kd_menu){
                        $v->sel=1;
                        $v->vie=$value->bview;
                        $v->ins=$value->binsert;   
                        $v->upd=$value->bupdate;  
                        $v->del=$value->bdelete;
//                        break;
                    }
                }
            }
        }
        
        
        
//        echo json_encode($gmenu);
        echo '{success:true,record:' . count($arrs) . ',data:' . json_encode($arrs) . '}'; //json_encode($menu);
    }

    public function get_rows_detail($no_ro = '') {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");

        $result = $this->usergroup_model->get_rows_detail($no_ro, $start, $limit);

        echo $result;
    }

    public function delete_row() {
        $kd_group = isset($_POST['kd_group']) ? $this->db->escape_str($this->input->post('kd_group', TRUE)) : FALSE;
        $kd_menu = isset($_POST['kd_menu']) ? $this->db->escape_str($this->input->post('kd_menu', TRUE)) : FALSE;

        if ($this->usergroup_model->delete_row($kd_group, $kd_menu)) {
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
    public function update_row() {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'save')) {
            $kd_group = isset($_POST['kd_group']) ? $this->db->escape_str($this->input->post('kd_group', TRUE)) : FALSE;
            $data_in = isset($_POST['data']) ? json_decode($this->input->post('data', TRUE)) : array();
//            $kd_menu = isset($_POST['kd_menu']) ? $this->db->escape_str($this->input->post('kd_menu', TRUE)) : FALSE;
//            $binsert = isset($_POST['binsert']) ? $this->db->escape_str($this->input->post('binsert', TRUE)) : '0';
//            $bupdate = isset($_POST['bupdate']) ? $this->db->escape_str($this->input->post('bupdate', TRUE)) : '0';
//            $bdelete = isset($_POST['bdelete']) ? $this->db->escape_str($this->input->post('bdelete', TRUE)) : '0';
//            $bview = isset($_POST['bview']) ? $this->db->escape_str($this->input->post('bview', TRUE)) : '0';
//            $aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif', TRUE)) : '0';

            $created_by = $this->session->userdata('username');
            $created_date = date('Y-m-d H:i:s');
            $retval = 0;
            $this->db->trans_start();
            foreach ($data_in as $obj) {
                $menuexists = $this->usergroup_model->cek_exists_menu($kd_group, $obj->kd_menu);
                if ($menuexists > 0) {
                    $data = array(
                        'bview' => $obj->vie,
                        'binsert' => $obj->ins,
                        'bupdate' => $obj->upd,
                        'bdelete' => $obj->del,
                        'updated_by' => $created_by,
                        'updated_date' => $created_date,
                        'aktif' => '1'
                    );
                    if ($this->usergroup_model->update_groupmenu($kd_group, $obj->kd_menu, $data)) {
                        $retval++;
                    }
                } else {
                    $data = array(
                        'kd_menu' => $obj->kd_menu,
                        'bview' => $obj->vie,
                        'binsert' => $obj->ins,
                        'bupdate' => $obj->upd,
                        'bdelete' => $obj->del,
                        'kd_group' => $kd_group,
                        'created_by' => $created_by,
                        'created_date' => $created_date,
                        'aktif' => '1'
                    );
                    if ($this->usergroup_model->insert_row($data)) {
                        $retval++;
                    }
                }
            }
            $this->db->trans_complete();


            if ($retval > 0) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }

            echo $result;
        }
    }

}