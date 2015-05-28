<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Custom_report_query extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('custom_report_query_model');
    }
	
	public function index(){
	$embed_js = '$(document).ready(function () {									
						$("#datatable").mask("' . $this->lang->line('loading_data') . '");
				    	var url = "' . site_url('custom_report_query/load_datatable') . '";			    	
				    	$.post( url, {},
				      		function(result) {
								if (result.error){
				         			if(result.session_expired){
							         	jAlert(result.message, "Alert", function(s){
											if(s){
												window.location = result.redirect;
											}
										});
										return;
									}
									jAlert(result.message);
									
								}else{
									$("#datatable > tbody").html(result.data);		
									$("#dataTables_info").html(result.info);	
									$("#dataTables_paginate").html(result.pagination);								
								}											
				      		}, "json"
				    	);	
						$("#datatable").unmask();			  	
					';
		$embed_js .= '});'; 
		$embed_js .= 'function load_datatable(url){						
						$("#datatable").mask("' . $this->lang->line('loading_data') . '");	
																
				    	/* Send the data using post  */
				    	$.post( url, 
							{
								search:$("#search").val(),
								length:$("#length").val()						
							},
				      		function(result) {
								if (result.error){
				         			if(result.session_expired){
							         	jAlert(result.message, "Alert", function(s){
											if(s){
												window.location = result.redirect;
											}
										});
										return;
									}
									jAlert(result.message);
									
								}else{
									$("#datatable > tbody").html(result.data);		
									$("#dataTables_info").html(result.info);	
									$("#dataTables_paginate").html(result.pagination);						
								}											
				      		}, "json"
				    	);	
						$("#datatable").unmask();
					}';	
		$embed_js .= 'function confirm_delete(url,current_page) {
						jConfirm("' . $this->lang->line('confirm_delete') . '", "Please Confirm", function(result){
							if(result){
								$("#datatable").mask("' . $this->lang->line('loading_data') . '");
								$.post(url, {}, function(r){
									if (r.error) {
										if(r.session_expired){
							         			jAlert(r.message, "Alert", function(s){
													if(s){
														window.location = r.redirect;
													}
												});
												return;
											}
										jAlert(r.message);
										
									}else{
										jAlert(r.message, "Alert", function(s){
											if(s){
												load_datatable(current_page);	
											}
										});
									}
								}, "json");
								$("#datatable").unmask();
							}
						});
						  
						return;
					}';	
		$this->template->add_js($embed_js, 'embed');
		$this->params['site_title'] = 'custom_report_query';
		$this->template->write_view('content', 'custom_report_query/custom_report_query', $this->params, TRUE);
		$this->template->render(); // render template
	}
	
	// load datatable
	public function load_datatable($offset = 0){
		//$offset = isset($_POST['offset']) ? $this->db->escape_str($this->input->post('offset',TRUE)) : 0;
		$search = isset($_POST['search']) ? $this->db->escape_str($this->input->post('search',TRUE)) : '';
		$length = isset($_POST['length']) ? $this->db->escape_str($this->input->post('length',TRUE)) : $this->config->item("length_records");
		
		(int) $offset;
		
		// get datatable
		$rows = $this->custom_report_query_model->get_datatable($search, $offset, $length);
		$total_page = count($rows);
		
		$datatable = '';
		if($total_page > 0){
			$no = $offset + 1;
			foreach($rows as $obj){			
				$datatable .= '<tr class="odd">';
				$datatable .= '<td class="small sorting_1">' . $no. '</td>';
				$datatable .= '<td><a href="' . site_url('custom_report_query/add/' . $obj->id_report). '">' . $obj->report_name . '</a></td>';
				$datatable .= '<td>' . $obj->req_by . '</td>';
				$datatable .= '<td class="medium" style="width:230px">
								<a href="' . site_url('custom_report_query/add/' . $obj->id_report). '" class="btn btn-small btn-success"><i class="icon-edit icon-white"></i>Edit</a>
								<a href="javascript:confirm_delete(\''. site_url('custom_report_query/delete/' . $obj->id_report) . '\',\'' . site_url('custom_report_query/load_datatable/' . $offset)  . '\');" class="btn btn-small btn-danger"><i class="icon-trash icon-white"></i>Remove</a>
								<a href="' . site_url('custom_report_query/getReport/' . $obj->id_report.'/prev '). '" target="_blank" class="btn btn-info btn-small"><i class="icon-print icon-white"></i>' .'Preview'. '</a>
							</td>';
    			$datatable .= '</tr>';
				$no++;
			}
		}else{
			$datatable = '<tr><td align="center" colspan="6">' . $this->lang->line('data_not_found'). '</td></tr>';
		}
		
		// get info datatable
		$total_datatable = $this->custom_report_query_model->get_total_datatable($search);		
		if($total_datatable > 0){
			$to = ($total_datatable > $length) ? ($total_page < $length) ? $total_datatable : ($offset+$length) : $total_datatable;
			$info_datatable = 'Showing ' . ($offset+1) . ' to ' . $to . ' of ' . $total_datatable . ' entries';
		}else{
			$info_datatable = "";
		}
		
		// initializing pagination
		$config_pagination = $this->config->item('page');
		$config_pagination['function_js']	= 'load_datatable';
		$config_pagination['base_url'] 		= site_url('custom_report_query/load_datatable/');
		$config_pagination['total_rows'] 	= $total_datatable;
		$config_pagination['per_page'] 		= $length;			
		$this->my_pagination->initialize($config_pagination);
		
		$pagination = $this->my_pagination->create_links(); // create pagination
		
						
		$callback['error'] = FALSE;	
		$callback['data'] = $datatable;	
		$callback['info'] = $info_datatable;
		$callback['pagination'] = $pagination;
			
		echo json_encode($callback);	
		
		return;
	}
	
	// add / edit custom_report_query
	public function add($id_report = FALSE){
		$this->params['no']=FALSE;
		if($id_report){
			$action = "Edit Custom Report";
			$row = $this->custom_report_query_model->get_data_by_id($id_report);
			if(count($row) == 0){
				redirect(site_url('custom_report_query'), 'refresh');
				exit;
			}			
			$this->params['row']=$row;		
			$fldQ=$this->custom_report_query_model->get_field_in_query($id_report);	
			foreach ($fldQ as $qry){
				foreach($qry as $key=>$value){
				if ($key == 'fldQ')
					$this->params['fld'] = $value;
				else if ($key == 'tblQ')
					$this->params['tbl'] = $value;
				else if ($key == 'whrQ')
					$this->params['whr'] = $value;}				
			}
		}else{
			$action = "New Custom Report";			
		} 
		
		$this->params['site_title'] = $action;		
		$this->template->add_js("js/jquery.validationEngine-en.js");
		$this->template->add_js("js/jquery.validationEngine.js");
		$this->template->add_css("css/themes/custom-theme/jquery.ui.datepicker.css");
		$this->template->add_js("js/jquery-ui/jquery.ui.datepicker.min.js");
				
		$embed_js = '$(function() {	
					$(\'.datepicker\').datepicker({ 
							defaultDate: +7,
							nextText:"",
							prevText:"",
							autoSize: true,
							dateFormat: "yy-mm-dd",						
							changeYear: true,
							yearRange: "c-20:c+20",
							changeMonth: true
						});
					$("#formAdd").validationEngine();
					
					$("#formAdd").submit(function(event) {						
						$(this).mask("Please wait...");
				    	/* stop form from submitting normally */
				    	event.preventDefault(); 
				        
				    	/* get some values from elements on the page: */
				    	var $form = $( this ),
				        	url = $form.attr( \'action\' );
											
				    	/* Send the data using post  */
				    	$.post( url, $(this).serialize(),
				      		function(result) {
								if (result.error){
				         			if(result.session_expired){
							         	jAlert(result.message, "Alert", function(s){
											if(s){
												window.location = result.redirect;
											}
										});
										return;
									}
									jAlert(result.message);
									
								}else{
									jAlert(result.message, "Alert", function(s){
									 	if(s){
											window.location=result.redirect;
										}
									 });
									
								}			
				      		}, "json"
				    	);
						
						$(this).unmask();
						return;
				  	});
			';
		
		$embed_js .= '});'; 				
		$this->template->add_js($embed_js, 'embed');
		$this->template->write_view('content', 'custom_report_query/custom_report_query_add', $this->params, TRUE);
		$this->template->render(); // render template
	}
	
	
	// save / update custom_report_query
	public function save(){	
		$id_report = isset($_POST['id_custom_report_query']) ? ($this->input->post('id_custom_report_query') != 'automatic') ? $this->input->post('id_custom_report_query',TRUE) : FALSE : FALSE;
		$report_name = isset($_POST['report_name']) ? $this->input->post('report_name',TRUE) : '';
		$req_by = isset($_POST['req_by']) ? $this->input->post('req_by',TRUE) : '';
		$field = isset($_POST['field']) ? $this->input->post('field',TRUE) : '';
		$table = isset($_POST['table']) ? $this->input->post('table',TRUE) : '';
		$cond = isset($_POST['cond']) ? $this->input->post('cond',TRUE) : '';
		
		$error_message = "";
		
		if ($report_name == "" )
		{
			$error_message .= "- " . $this->lang->line('error_nama_missing') . "Report \n";	
		}
		if ($req_by == "" )
		{
			$error_message .= "- Please Input Request By \n";	
		}
		if ($field == "" && $table == "")
		{
			$error_message .= "- Please Input Field n Table \n";	
		}
		
		
				
		if ($error_message != "")
		{
			$validation['error'] = TRUE;	
			$validation['message'] = $error_message;	
			
			echo json_encode($validation);			
			return;
		}
		
		unset($data);
		if ($cond == "")
			$where = "";
		else $where = " WHERE ".$cond.' ';
		
		$query = 'SELECT '.$field. ' FROM '.$table.$where;
		
		$data['id_report'] = $this->db->escape_str($id_report);
		$data['report_name'] = $this->db->escape_str($report_name);	
		$data['req_by'] = $this->db->escape_str($req_by);	
		$data['query'] = $this->db->escape_str($query);				
		$data['type'] = 2;
		
		$callback = $this->custom_report_query_model->save($this->db->escape_str($id_report), $data);
		echo json_encode($callback);			
		return;
	}
	
	// delete custom_report_query
	public function delete($id_report = FALSE){
		$callback = $this->custom_report_query_model->delete($id_report);		
		
		echo json_encode($callback);
		return;
	}
	
	// get nama field by nama table
	public function get_field_name(){
		$t_name = isset($_POST['t_name']) ? $this->input->post('t_name', TRUE) : FALSE;
		
		$data = $this->db->list_fields($t_name);
		
		$cboKab = '<option value=""></option>';
		
		if($data){
			foreach($data as $obj){
				$cboKab .= '<option value="' . $obj . '">' . $obj . '</option>';
			}
		}
		
		echo $cboKab;
		return;
	}
	
	//get json data by id report, sortname and sortorder
	function get_json_data($id_report,$sName,$sOrder){
        $page = $_REQUEST['page']; // get the requested page
        $limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
        $sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
        $sord = $_REQUEST['sord']; // get the direction if(!$sidx) $sidx =1;
        $req_param = array (
				"sort_by" => $sidx,
				"sort_direction" => $sord,
				"limit" => null,
				"search" => $_REQUEST['_search'],
				"search_field" => isset($_REQUEST['searchField'])?$_REQUEST['searchField']:null,
				"search_operator" => isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:null,
				"search_str" => isset($_REQUEST['searchString'])?$_REQUEST['searchString']:null
		);

        $row = $this->custom_report_query_model->get_report($id_report,$sName,$sOrder)->result_array();
		
        $count = count($row);
        if( $count >0 ) {
            $total_pages = ceil($count/$limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages)
            $page=$total_pages;
        $start = $limit*$page - $limit; // do not put $limit*($page - 1)

        $req_param['limit'] = array(
                    'start' => $start,
                    'end' => $limit
        );

        $result = $this->custom_report_query_model->get_report($id_report,$sName,$sOrder);
		
		$responce = new StdClass();
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
		$listfields=$result->list_fields();
		$i=0;
		$j=0;
		foreach ($listfields as $fields){
				$i++;
		}
		foreach($result->result_array() as $data){				
			$responce->rows[$j][$listfields[0]]=$data[$listfields[0]];
			
			// data berikut harus sesuai dengan kolom-kolom yang ingin ditampilkan di view (js)

			for($k=0;$k<$i;$k++){
				$data[]=array(
						$data[$listfields[$k]]
						);
				}
			$responce->rows[$j]['cell']=$data;
			$j++;
		}
		
        echo json_encode($responce);
    }
	// get jqgrid column names
	function setColNames($id_report){
		$result = $this->custom_report_query_model->get_query($id_report);
		$listfields=$result->list_fields();
		$i=0;
		$arrModels=array();
		$cNames=array();
		$cModels='';
		foreach ($listfields as $fields){
				$cNames[]	 = $fields;
				$arrModels[] = 	array('name'  => $fields,
								'index' => $fields,
                                'width' => 35);
				$i++;
		}
		foreach($arrModels as $models){
			$cModels 	.= json_encode($models).','; 
		}
		$this->sortColNames = $cNames[0];
		$this->colNames=json_encode($cNames);
		$this->colModels='['.$cModels.']';
	}
	
	
	//get report by id report
	public function getReport($id_report){
		$report=end($this->uri->segments);  
		$this->setColNames($id_report);
		$this->params['id_report']=$id_report;
		$this->params['sidx']=$this->sortColNames;
		$this->params['colNames']=$this->colNames;
		$this->params['colModels']=$this->colModels;
		
		if($report=='prev'){
			$this->template->write_view('content', 'custom_report_query/custom_report_query_preview', $this->params, TRUE);
			$this->template->render(); // render template
		}else if ($report=='excel'){		
			$nColumn = $this->uri->segment(4);
			$segment=5;
			$listfield=array();
			for ($i=1;$i<$nColumn;$i++){
				$listfield[] = str_replace("%20"," ",$this->uri->segment($segment));
				$segment++;
			}
			$sName = $this->uri->segment($segment);
			$segment++;
			$sOrder = $this->uri->segment($segment);
			$rows	= $this->custom_report_query_model->get_datas($id_report);
			$result = $this->custom_report_query_model->get_report($id_report,$sName,$sOrder)->result_array();
			$oldIncludePath = get_include_path();
			set_include_path(APPPATH . 'libraries/PHPExcel');
			
			include_once 'PHPExcel.php';
			include_once 'PHPExcel/Writer/Excel2007.php';
			include_once 'PHPExcel/IOFactory.php';
			
			if($report=='excel'){
				$ext='xls';
				$header='vnd.ms-excel';
				$obj='Excel5';
				$render=false;
			}else{				
				$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
				$rendererLibrary = 'tcPDF5.9';
				$rendererLibraryPath = APPPATH.'libraries/tcpdf';
				$ext='pdf';
				$header='pdf';
				$obj='PDF';
				$render=true;
			}
			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()	->setTitle("title")
											->setDescription("description");
											
			// Assign cell values
			$objPHPExcel->setActiveSheetIndex(0);
			$sheet = $objPHPExcel->getActiveSheet();
			$sheet->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$styleArray = array(
			  'borders' => array(
				'allborders' => array(
				  'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			  )
			);
			
			$sheet->setShowGridlines(true);
			
			foreach($rows as $row){
				$row1=$row['report_name'];
				$row2=$row['req_by'];
				$row3=date('d-m-Y');
			}
			
			$sheet->getRowDimension('1')->setRowHeight(10);
			
			$sheet->setCellValue('A1', $row1);
			$sheet->setCellValue('A2', $row2);
			$sheet->setCellValue('A3', $row3);
			
			$char=65;
			$i=0;
			foreach($listfield as $fields){
				$sheet->setCellValue(chr($char).'5', $fields);
				${'length'.$i}=strlen($fields);
				$char++;
				$i++;
			}
			
			$char=65;
			if($report=='excel'){
				for($j=0;$j<$i;$j++){
					$sheet->getColumnDimension(chr($char))->setAutoSize(true);	
					$char++;
				}
			}
			$counter = 6;
			foreach ($result as $row){
				$char=65;
				if($counter==6 && $report=='pdf'){
					$charX=$char;
					for($j=0;$j<$i;$j++){
						if(${'length'.$j}>strlen($row[$listfield[$j]])){
							$sheet->getColumnDimension(chr($charX))->setWidth(${'length'.$j}+4);	
						}else{
							$sheet->getColumnDimension(chr($charX))->setWidth(30);
						}
					$charX++;
					}
				}
				for ($j=0;$j<$i;$j++){
					$sheet->setCellValue(chr($char).$counter, $row[$listfield[$j]]);
					$char++;
				}
				$counter++;
			}
			
			if ($render){
				if (!PHPExcel_Settings::setPdfRenderer(
							$rendererName,
							$rendererLibraryPath
						)) {
						die(
							'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
							'<br />' .
							'at the top of this script as appropriate for your directory structure'
						);
					}
			}
			$filename = $row1."-".date('d/m/Y H:i:s').'.'.$ext."";
			
			// Redirect output to a client’s web browser 
			header('Content-Type: application/'.$header);
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');
			
			 // Save it as a file
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $obj);
			$objWriter->save('php://output');
			
			set_include_path($oldIncludePath); 
		}
		else if ($report=='pdf'){
			$this->load->library('fpdf');
			$nColumn = $this->uri->segment(4);
			$segment=5;
			$listfield=array();
			for ($i=1;$i<$nColumn;$i++){
				$listfield[] = str_replace("%20"," ",$this->uri->segment($segment));
				$segment++;
			}
			$sName = $this->uri->segment($segment);
			$segment++;
			$sOrder = $this->uri->segment($segment);
			$rows	= $this->custom_report_query_model->get_datas($id_report);
			$result = $this->custom_report_query_model->get_report($id_report,$sName,$sOrder)->result_array();
			
			foreach($rows as $row){
				$row1=$row['report_name'];
				$row2=$row['req_by'];
				$row3=date('d-m-Y');
			}
			
			$html='';
			$html.='<table>
						<tr>
							<td>Nama Laporan</td>
							<td>:</td>
							<td>'.$row1.'</td>
						</tr>
						<tr>
							<td>Request By</td>
							<td>:</td>
							<td>'.$row2.'</td>
						</tr>
						<tr>
							<td>Tanggal</td>
							<td>:</td>
							<td>'.$row3.'</td>
						</tr>
						
					</table>';
			$row1.$row2.$row3;
			$html.='<table border="1">
			<tr>';
			$char=65;
			$i=0;
			foreach ($listfield as $fields){
				$html.= '<td><strong>'.$fields.'</strong></td>';
				$char++;
				$i++;
			}
			$html .= '</tr>';
			
			foreach ($result as $row){
				$char=65;
				$html.='<tr>';
				for ($j=0;$j<$i;$j++){
					$html.= '<td>'.$row[$listfield[$j]].'</td>';
					$char++;
				}
				$html.='</tr>';
			}
			$html.='
			</table>';
			$filename = $row1."-".date('d/m/Y H:i:s').'.pdf';
			ini_set('memory_limit', '-1');
			require_once(APPPATH.'/libraries/pdftable/pdftable.inc.php');
			$p = new PDFTable();
			$p->AddPage();
			$p->setfont('times','',10);
			$p->htmltable($html);
			$p->output($filename,'I');
		}
	}
		
	
}

