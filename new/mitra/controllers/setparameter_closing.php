<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setparameter_closing extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('setparameter_closing_model');
    }

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->setparameter_closing_model->get_rows($search, $start, $limit);

        echo $result;
    }

    public function get_row() {
        $result = '{"success":false,"errMsg":"Process Failed.."}';
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $periode = isset($_POST['periode']) ? $this->db->escape_str($this->input->post('periode', TRUE)) : NULL;
            $result = $this->setparameter_closing_model->get_row($periode);

        }
        echo $result;
    }

    public function update_row() {
        $result = '{"success":false,"errMsg":"Process Failed.."}';
        $success = false;
        $periode = isset($_POST['periode']) ? $this->db->escape_str($this->input->post('periode', TRUE)) : FALSE;
        $data = array(
            'tgl_closing_pembelian'     => isset($_POST['tgl_closing_pembelian']) ? $this->db->escape_str($this->input->post('tgl_closing_pembelian', TRUE)) : null,
            'tgl_closing_penjualan'     => isset($_POST['tgl_closing_penjualan']) ? $this->db->escape_str($this->input->post('tgl_closing_penjualan', TRUE)) : null,
            'tgl_closing_inventory'     => isset($_POST['tgl_closing_inventory']) ? $this->db->escape_str($this->input->post('tgl_closing_inventory', TRUE)) : null,
            'tgl_closing_accounting'    => isset($_POST['tgl_closing_accounting']) ? $this->db->escape_str($this->input->post('tgl_closing_accounting', TRUE)) : null,
            'is_aktif'                  => isset($_POST['is_aktif']) ? $this->db->escape_str($this->input->post('is_aktif', TRUE)) : 0
        );

        if(isset($periode) && !empty($periode)) {


            if($this->setparameter_closing_model->check_if_exists($periode)) {
                //input data baru
                $newdata = array(
                    'created_by'                => $this->session->userdata('username'),
                    'created_date'              => date('Y-m-d H:i:s')
                );
                $data = array_merge(array('periode' => $periode), $data, $newdata);

                $success = $this->setparameter_closing_model->insert_row($data);

            } else {
                //update data
                $update = array(
                    'updated_by'                => $this->session->userdata('username'),
                    'updated_date'              => date('Y-m-d H:i:s')
                );
                $data = array_merge($data, $update);
                if($this->input->post('is_closing', TRUE) === 1) {
                    $closing = array(
                        'closing_by'                => $this->session->userdata('username'),
                        'closing_date'              => date('Y-m-d H:i:s')
                    );
                    $data = array_merge($data, $closing);
                }
                $success = $this->setparameter_closing_model->update_row($periode, $data);
            }
        }
        if ($success) {
            $result = '{"success":true,"errMsg":""}';
        }

        echo $result;
    }

    function delete_row() {
        $result = '{"success":false,"errMsg":"Process Failed.."}';
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $periode = isset($_POST['periode']) ? $this->db->escape_str($this->input->post('periode', TRUE)) : NULL;
            $result = $this->setparameter_closing_model->delete_row($periode);
            $result = '{"success":true,"errMsg":""}';
        }
        echo $result;
    }

}