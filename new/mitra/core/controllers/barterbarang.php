<?php

 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of barterbarang
 *
 * @author faroq
 */
class barterbarang extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('barterbarang_model','trans_model');
    }
    
    public function search_supplier() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->trans_model->search_supplier($search, $start, $limit);


        echo $result;
    }
}

?>
