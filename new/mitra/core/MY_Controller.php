<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    var $params;
    protected $test = false;

    public function __construct() {

		parent::__construct();

        if (!$this->my_auth->logged_in() AND $this->uri->uri_string() != 'auth/login') {
        	// user not logged in
			if( ! $this->input->is_ajax_request()){
				// if not ajax request
				// send back to login page
				redirect(site_url('auth/login'));
			}else{
				// if ajax request
				// return json message
				echo '{"success":false,"errMsg":"Session Expired"}';
				exit;
			}
        }else{
        	// user logged in
			// get menu
        }
        if (defined('ENVIRONMENT')) {
            if(ENVIRONMENT == 'development') $this->test = true;
        }


    }


    /**
     * Daripada copy paste ternari, mending dibuat fungsi.
     * Parameter kedua untuk membersihkan kembalian data dari spasi. Berguna untuk kolom2 yang akan di explode
     * @param $field
     * @param null $default
     * @param bool $no_whitespace
     * @return mixed|null
     */
    protected function form_data($field, $default = null, $no_whitespace = false) {
        $result = $default;
        if(isset($_POST[$field]) && !empty($_POST[$field])) $result = $this->db->escape_str($this->input->post($field,TRUE));
        if($no_whitespace) $result = preg_replace('/\s+/', '', $result);

        return $result;
    }

    /**
     * Cetak json hasil query.
     * @param $data hasil query
     * @param bool $show_last_query jika dari model terdapat parameter lq, tampilkan sebagai untuk debug
     */
    protected function print_result_json($data, $show_last_query = false) {
        if(!is_array($data)) {
            $data = array();
        }
        header('Content-Type: application/json');
        $data['total']  = array_key_exists('total',$data) ? $data['total'] : 0;
        $data['data']   = array_key_exists('data',$data) ? $data['data'] : null;
        $data['lq']  = array_key_exists('lq',$data) ? $data['lq'] : null;
        $d_query = $show_last_query ? ', "last_query":' . json_encode($data['lq']) : '';

        echo '{"success": true, "record":' . $data['total'] . ', "data": ' . json_encode($data['data']) . $d_query . '}';
    }

    protected function print_json($data, $force = false, $prettyprint = false) {
        header('Content-Type: application/json');
        if($force) {
            echo json_encode($data, JSON_FORCE_OBJECT);
        }
        if($prettyprint) {
            echo json_encode($data, JSON_PRETTY_PRINT);
        }
        if($prettyprint && $force) {
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT);
        }
    }

    protected function returnError($message, $in_transaction = false, $query = null)
    {
        if($in_transaction) $this->db->trans_rollback();
        header('Content-Type:application/json');
        $msg = array('success' => false, 'errMsg' => $message);
        $query = empty($query) ? null : $query;
        if($this->test) {
            $msg['lq'] = $query;
        }
        echo $this->print_json($msg, true);
        exit;
    }

}
