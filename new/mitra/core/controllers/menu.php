<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menu extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('menu_model');
    }

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->menu_model->get_rows($search, $start, $limit);

        echo $result;
    }

    public function get_row() {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $result = $this->menu_model->get_row($id);

            return $result;
        }
    }
    public function update_row() {
        
        $kd_menu=isset($_POST['kd_menu']) ? $this->db->escape_str($this->input->post('kd_menu', TRUE)) : FALSE;   
        $kd_parent_menu=isset($_POST['kdparent']) ? $this->db->escape_str($this->input->post('kdparent', TRUE)) : FALSE;   
        $menu_id=isset($_POST['menu_id']) ? $this->db->escape_str($this->input->post('menu_id', TRUE)) : FALSE;   
        $menu_text=isset($_POST['menu_text']) ? $this->db->escape_str($this->input->post('menu_text', TRUE)) : FALSE;   
        $menu_leaf=isset($_POST['menuleaf']) ? $this->db->escape_str($this->input->post('menuleaf', TRUE)) : FALSE;   
        $menu_expanded=isset($_POST['menuexpanded']) ? $this->db->escape_str($this->input->post('menuexpanded', TRUE)) : FALSE;   
        $menu_description=isset($_POST['menu_description']) ? $this->db->escape_str($this->input->post('menu_description', TRUE)) : FALSE;   


        $aktif = '1';
         
        if (!$menu_leaf){            
            $menu_leaf='0';
        }else{
            $menu_leaf='1';
        }
        if (!$menu_expanded){
            $menu_expanded='0';
        }else{
            $menu_expanded='1';
        }
        
        if (!$kd_menu) { //save     
            $created_by = $this->session->userdata('username');
            $created_date = date('Y-m-d H:i:s');

            $data = array(                
                'kd_menu'=>'MNU-'.$this->menu_model->get_kode_sequence("MNU-", 3),                                
                'kd_parent_menu'=>$kd_parent_menu,
                'menu_id'=>$menu_id,
                'menu_text'=>$menu_text,
                'menu_leaf'=>$menu_leaf,
                'menu_expanded'=>$menu_expanded,
                'menu_description'=>$menu_description
            
            );

            if ($this->menu_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        } else { //edit       
            $updated_by = $this->session->userdata('username');
            $updated_date = date('Y-m-d H:i:s');

            $datau = array(
                'kd_parent_menu'=>$kd_parent_menu,
                'menu_id'=>$menu_id,
                'menu_text'=>$menu_text,
                'menu_leaf'=>$menu_leaf,
                'menu_expanded'=>$menu_expanded,
                'menu_description'=>$menu_description
            );

            if ($this->menu_model->update_row($kd_menu, $datau)) {
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
                    if ($this->$kd_menu->delete_row($id)) {
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
        $kd_menu = isset($_POST['kd_menu']) ? $this->db->escape_str($this->input->post('kd_menu', TRUE)) : FALSE;

        if ($this->menu_model->delete_row($kd_menu)) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }


}

?>
