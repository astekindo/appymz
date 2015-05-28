<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_limit_approval
 *
 * @author miyzan
 */
class account_limit_approval extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('account_limit_approval_model', 'limit_model');
    }

    public function get_form() {
        $result = $this->limit_model->get_rows();
        $startapv1 = 0;
        $endapv1 = 0;
        $startapv2 = 0;
        $endapv2 = 0;
        $startapv3 = 0;
        $endapv3 = 0;

        if (count($result) > 0) {
            foreach ($result as $r) {
                $startapv1 = $r->startapv1;
                $endapv1 = $r->endapv1;
                $startapv2 = $r->startapv2;
                $endapv2 = $r->endapv2;
                $startapv3 = $r->startapv3;
                $endapv3 = $r->endapv3;
            }
        }

        $retval = '{"success":true,
				"data":{
					"startapv1":"' . $startapv1 . '",
                                            "endapv1":"' . $endapv1 . '",
                                                "startapv2":"' . $startapv2 . '",
                                                    "endapv2":"' . $endapv2 . '",
                                                        "startapv3":"' . $startapv3 . '",
                                                    "endapv3":"' . $endapv3 . '"
					
				}
			}';
        echo $retval;
    }
    
     public function get_row_data() {
        $result = $this->limit_model->get_rows_data();        
        echo $result;
    }

    public function update_row() {
        $data['startapv1'] = isset($_POST['pstartapv1']) ? $this->db->escape_str($this->input->post('pstartapv1', TRUE)) : 0;
        $data['endapv1'] = isset($_POST['pendapv1']) ? $this->db->escape_str($this->input->post('pendapv1', TRUE)) : 0;
        $data['startapv2'] = isset($_POST['pstartapv2']) ? $this->db->escape_str($this->input->post('pstartapv2', TRUE)) : 0;
        $data['endapv2'] = isset($_POST['pendapv2']) ? $this->db->escape_str($this->input->post('pendapv2', TRUE)) : 0;
        $data['startapv3'] = isset($_POST['pstartapv3']) ? $this->db->escape_str($this->input->post('pstartapv3', TRUE)) : 0;
        $data['endapv3'] = isset($_POST['pendapv3']) ? $this->db->escape_str($this->input->post('pendapv3', TRUE)) : 0;

        $retval = 0;

        $this->db->trans_start();
        if($this->limit_model->get_row_exists()){
            $retval = $this->limit_model->update_row($data);
        }else{
            $retval = $this->limit_model->insert_row($data);
        }
        
        $this->db->trans_complete();

        $title = 'Parameter Limit Approval';
        if ($retval > 0) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed"}';
        }

        echo $result;
    }

}

?>
