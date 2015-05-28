<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Utama extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 
	function __construct()
    	{
        	parent::__construct();
        	$this->load->model('menu_models');
			$this->load->model('login');
			$this->load->database();
			$this->load->model('fungsi');
		
    	}
	public function index()
	{
		$x=1;
		$judul=$this->config->item('judul');

		if(isset($_POST['login'])){
			$x=$this->login->chkUser($_POST['lUser'], $_POST['lPass']);
			if($x==1)
				$judul="Selamat Datang - ".$this->session->userdata('username');
		}
		$data = array(
				'menu' => '',
				'nama' => $this->session->userdata('username'),
				'title' => $judul,
				'location' => 'Home'
			);
		if($this->session->userdata('username')){
			$res_menu=$this->menu_models->menu_content();
			$data['menu']=$res_menu;
		}

		if($this->session->userdata('username')){
			$this->load->view('home', $data);
		}
		else{

			if($x==0)
				$this->load->view('gagal', $data);	
			else
				$this->load->view('utama', $data);
		}
	}

	public function inpophp(){
		phpinfo();		
	}
}



/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
