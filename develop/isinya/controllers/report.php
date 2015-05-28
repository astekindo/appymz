<?php
	class Report extends CI_Controller
	{
		public function view()
		{
			$data['title']	= 'Report';
			$data['menu']	= $this->menu_models->menu_content();
			$this->load->model('report_models');
			$data['listSubject'] = $this->report_models->getListSubjReport();
			$data['is_not_selected'] = true;
			$this->load->view('report/view', $data);
		}
	}
?>