<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//  CI 2.0 Compatibility
if(!class_exists('CI_Model')) { class CI_Model extends Model {} }

class custom_report_query_model extends CI_Model {
	
	public function __construct(){
		parent::__construct();
	}
	/**
	 * get_datatable
	 *
	 * @return objects
	 * @author Andhi
	 **/
	public function get_datatable($search = "", $offset, $length){
		if($search != ""){
			$sql_search = "(id_report LIKE '%" . $search . "%' 							
							OR report_name LIKE '%" . $search . "%'
							)";
			$this->db->where($sql_search, NULL);
		}
		$this->db->where('type',2);
		$this->db->order_by("id_report", "asc");
		$query = $this->db->get("report", $length, $offset);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
			
		}
		 $this->db->last_query();
		return $rows;
	}
	
	/**
	 * get_total_datatable
	 *
	 * @return int
	 * @author Andhi
	 **/
	function get_total_datatable($search = ""){
		$this->db->select('count(*) as total');
		if($search != ""){
			$sql_search = "(id_report LIKE '%" . $search . "%' 							
							OR report_name LIKE '%" . $search . "%'
							)";
			$this->db->where($sql_search, NULL);
		}
		$this->db->where('type',2);
		$query = $this->db->get("report");
		
		$total = 0;
		if($query->num_rows() > 0){
			$row = $query->row();
			$total = $row->total;
		}
		
		return (int) $total;
	}
	
	/**
	 * get_data_by_id (id = primary key)
	 *
	 * @return object
	 * @author Andhi
	 **/
	public function get_data_by_id($id = FALSE){
		$this->db->where("id_report", $this->db->escape_str($id));
		$query = $this->db->get("report");
		
		$row = $query->row();
		
		return $row;
	}
	
	
	/**
	 * save/update custom_reporter
	 *
	 * @return array
	 * @author Andhi
	 **/
	public function save($id_report = FALSE, $data = NULL){
		
		$this->db->trans_start();
		
		if($id_report){
			$this->db->where("id_report", $id_report);
			$this->db->update("report", $data);
				
			$success_message = $this->lang->line('success_db_updated');
		}else{
			$id_report = $this->_get_id();
			$data['id_report'] = $id_report;
			$this->db->insert("report", $data);
				
			$success_message = $this->lang->line('success_db_saved');
			$callback['id_report'] = $id_report;
		}
		
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE){
    		$callback['error'] = TRUE;
			$callback['message'] = $this->lang->line('error_db_transaction');
		}else{
			$callback['error'] = FALSE;
			$callback['message'] = $success_message;
			$callback['redirect'] = site_url('custom_report_query');
		} 
						
		return $callback;
	}
	
	/**
	 * delete
	 *
	 * @return array
	 * @author Andhi
	 **/
	public function delete($id = FALSE){
		// check data
		$this->db->where("id_report", $id);
		$query = $this->db->get("report");
		
		if($query->num_rows() > 0){
			$this->db->trans_start();
			
			$this->db->where("id_report", $id);
			$this->db->delete("report");
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE){
    			$callback['error'] = TRUE;
				$callback['message'] = $this->lang->line('error_db_transaction');
			}else{
				$callback['error'] = FALSE;
				$callback['message'] = $this->lang->line('success_db_deleted');;
			}
		}else{
			$callback['error'] = TRUE;
			$callback['message'] = $this->lang->line('data_not_found');			
		}
		
		return $callback;
	}
	/**
	 * (private) get custom_reporter code
	 *
	 * @return string
	 * @author Andhi
	 **/
	private function _get_id(){
		$this->db->select_max("id_report");
		$query = $this->db->get("report");
		
		$row = $query->row();			
		$generate_code = $row->id_report+1;
				
		
		return $generate_code;
	}
	
	public function get_field_name(){
		$this->db->select("t_name,f_name");
		$this->db->order_by("t_name,id_report_field","asc");
		$query = $this->db->get("report_field");
		
		$rows=$query->result();
		
		return $rows;
	}
	
	public function get_table_name(){	
		$this->db->group_by("t_name");
		$this->db->order_by("t_name,id_report_field","asc");		
		$query = $this->db->get("report_field");		
		
		$rows=$query->result();
		
		return $rows;
	}
	
	public function get_cond_field(){
		$this->db->select("t_name,f_name");
		$this->db->order_by("t_name,id_report_field","asc");
		$this->db->where("is_condition","1");
		$query = $this->db->get("report_field");
		
		$rows=$query->result();
		
		return $rows;
	}
		
	public function get_field_in_query($id_report=false){
		$this->db->select("query");
		$this->db->where("id_report",$id_report);
		
		$query=$this->db->get("report");
		$rows=$query->row();
		$haystack=$rows->query;
		
		$pos=strpos($haystack,'T ');
		$pos1=strpos($haystack,' FROM');
		$pos2=strpos($haystack,' WHERE');
		
		$field=substr($haystack,$pos+1,$pos1-5);
		if ($pos2){
			$table=substr($haystack,$pos1+5,$pos2-5-$pos1);
			$where=substr($haystack,$pos2+7,-1);
		}
		else {
			$table=substr($haystack,$pos1+5);
			$where='';
		}
		
		$data[]=array(
						'fldQ'=>trim($field),
						'tblQ'=>trim($table),
						'whrQ'=>trim($where)
					);
		
		return $data;
		}
	
	//get data report by id
	function get_datas($id_report){
		$this->db->where("id_report",$id_report);
		$query=$this->db->get('report');
		$result=$query->result_array();
		return $result;
	}
	
	//get field query by id
	public function get_query($id_report=false){
		$this->db->select('query');
		$this->db->where("id_report",$id_report);
		
		$query	= $this->db->get('report');
		$rows	= $query->row();
		$report	= $rows->query;
		
		return $this->db->query(stripslashes($report));
		}
		
	//get report by id report, sortname	and sort order
	function get_report($id_report,$sName,$sOrder){	
		$sName = str_replace("%20"," ",$sName);
		$string = trim($sName);

		if (strpos($string, ' ') !== false) {
			$string = "'".$string."'";
		}
		
		$this->db->select('query');
		$this->db->where("id_report",$id_report);
		
		$query	= $this->db->get('report');
		$rows	= $query->row();
		$report	= $rows->query;
		// $report .= ' ORDER BY '.$string.' '.$sOrder.' limit 170';
		
		return $this->db->query(stripslashes($report));
	}  
	
	
}
