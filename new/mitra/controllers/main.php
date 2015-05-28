<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('auth_model');
    }

    public function index() {
	if(!$this->valuser()) {
		$this->load->view('login', array('boleh' => $this->valuser()));
	} else {
		$data['username'] = $this->session->userdata('username');
		$data['usergroup'] = $this->session->userdata('nama_group');
		$accordion_menu = $this->my_auth->get_accordion_menu($this->session->userdata('kd_group'));
		$data['accordion_menu'] = $accordion_menu;

        $this->load->view('main', $data);
	}
    }

	public function all_menu($module = ""){
		$menu = $this->my_auth->get_tree_menu($module, $this->session->userdata('kd_group'));

		echo json_encode($menu);
	}

    public function valuser() {
    	//return true;
        //bypass otentikasi u/ ip 172.17.10.*
        return true;
        if(strpos($_SERVER['REMOTE_ADDR'], '172.17.10.') !== false) {
            return true;
        }

        $ipAddress=str_replace('.', '\.', $_SERVER['REMOTE_ADDR']);

        $perintah="arp -e | grep eth0 | grep '^$ipAddress\s' | awk '{print $3}'";
        exec($perintah, $mac) or $result = false;
        if($result !== false) {
            $result = $this->auth_model->get_mac($mac[0]);
        }
        return $result;
    }

}
