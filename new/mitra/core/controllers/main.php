<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
		$data['username'] = $this->session->userdata('username');
		$data['usergroup'] = $this->session->userdata('nama_group');
		$accordion_menu = $this->my_auth->get_accordion_menu($this->session->userdata('kd_group'));
		$data['accordion_menu'] = $accordion_menu;
									
        $this->load->view('main', $data);  
    }
	
	public function all_menu($module = ""){
		$menu = $this->my_auth->get_tree_menu($module, $this->session->userdata('kd_group'));

		echo json_encode($menu);
	}
	
}
