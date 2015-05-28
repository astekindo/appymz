<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_models extends CI_Model {

	//public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}

	public function getListSubjReport()
	{
		return array
		(
			'01' => 'Report 1',
			'02' => 'Report 2',
			'03' => 'Report 3',
		);
	}

}

/* End of file report_models.php */
/* Location: ./application/models/report_models.php */