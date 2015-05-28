<?php

class purchaserequest_models extends CI_Model {
    
	public $purchaserequest = '';

    public function purchaserequest_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select id_pr, no_pr, subject, created_by, to_char(created_date,'dd-mm-yyyy hh:mm:ss') created_date, 
							case when status = '0' then 'Pending' when status = '1' then 'Approved' end status
						FROM mst.tt_purchase_request where status='0'";
            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->purchaserequest.='<tr class="gradeX">';
                $this->purchaserequest.='<td align="center">' . $no . '</td><td align="center">' . $row->no_pr . '</td>
									<td>' . $row->subject . '</td><td align="center">' . $row->created_by . '</td><td align="center">' . $row->created_date . '</td><td align="center">' . $row->status . '</td>
									<td align="center"><a href="purchaserequest/form/'. $row->id_pr .'" title="Edit" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe1db;"></span></a>
									   <a href="#" title="Delete" class="tablectrl_small bDefault tipS" onClick="confirmationDel('.$row->id_pr.',\''.base_url().'purchaserequest/hapuspr\');"><span class="iconb" data-icon="&#xe136;"></a>
								   </td>';
                $this->purchaserequest.='</tr>';
            }
        } else {
            $this->purchaserequest = '';
        }
        return $this->purchaserequest;
	}

	function add_record($data) 
	{
		$this->db->insert('mst.tt_dtl_purchase_request_temp', $data);
		return;
	}	

	function getData($id_pr)
	{
		$query = $this->db->query("select id_pr, no_pr, subject, created_by, created_date, case when status = '0' then 'Pending' when status = '1' then 'Approved'
									from FROM mst.tt_purchase_request");
		return $query->result_array();
	}
	
	function get_last_records()
	{
		$query = $this->db->query("SELECT max(to_number(no_pr,'999')) no_pr FROM mst.tt_purchase_request");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = $row->no_pr;
                }
                return $return_value;
	}
	
	
}
?>