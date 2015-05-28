<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prod_bln_thn extends CI_Controller {
    
	function __construct() {
        parent::__construct();
        $this->load->model('prod_bln_thn_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Inventori - Detail Produk per Bulan per Tahun'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
			$res_prodblnthn = $this->prod_bln_thn_models->prodblnthn_content();
            $data['rcprodblnthn']=$res_prodblnthn;
            $this->load->view('page/vw_listprodblnthn', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }
	
}
?>
