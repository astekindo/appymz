<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Input_Menu extends CI_Controller {
    private $field=array('title', 'index_menu', 'parent_title', 'controller'); 
    function __construct()
    {
        parent::__construct();
        $this->load->model('menu_models');
        $this->load->model('login');
        $this->load->database();
        $this->load->model('fungsi');
        $this->load->model('getdata');    
    }
    public function index()
    {
        if($this->session->userdata('username')){
            $judul=$this->config->item('judul');
            if($this->uri->segment(2)){
                $data=$this->menu_models->getData($this->uri->segment(2));
            }
            else{
                $data['vId'] = '';
                $data['vTitle'] = '';
                $data['vParent'] = '';
                $data['vIndex'] = '';
                $data['vController'] = '';
                $data['vDescription'] = ''; 
            }
            
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['listparent'] = $this->menu_models->parents();
            $data['location'] = 'Home - Setting - Menu';
        }
        if($this->session->userdata('username')){
            $this->load->view('input_menu', $data);
        }
        else{
            $this->load->view('utama', $data);
        }
    }
    public function save(){
        if(isset($_POST['simpan'])){
            if(empty($_POST['dId'])){
                $this->menu_models->addData($_POST);
            }
            else{
                $this->menu_models->editData($_POST);
            }
            redirect(base_url()."menu", "location");
        }
    }
}
