<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {

	 
	function __construct()
    {
        parent::__construct();
		
    }
	public function index()
	{
		$this->session->sess_destroy();
		redirect(base_url(), 'location');  
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */