<?php

class retur_order_models extends CI_Model {
    
	public $returorder = '';

    public function returorder_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select id_retur, no_retur, tgl_retur, kd_supplier, created_by, 
					 to_char(created_date,'dd-mm-yyyy hh:mm:ss') created_date
					 FROM mst.tt_retur_order where status='0'";
            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->returorder.='<tr class="gradeX">';
                $this->returorder.='<td align="center">' . $no . '</td>
									<td align="center">' . $row->no_retur . '</td>
									<td>' . $row->tgl_retur . '</td>
									<td>' . $row->kd_supplier . '</td>
									<td align="center">' . $row->created_by . '</td>
									<td align="center">' . $row->created_date . '</td>';
                $this->returorder.='</tr>';
            }
        } else {
            $this->returorder = '';
        }
        return $this->returorder;
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
	
	public function getSuppData()
	{
		//return $this->db->get_where($table, $data);
		return $this->db->query("select a.kd_supplier, b.nama_supplier
								from mst.td_supp_per_brg a
								join mst.tm_supplier b on (b.kd_supplier = a.kd_supplier) 
								where a.konsinyasi is false
								group by a.kd_supplier, b.nama_supplier
								order by b.nama_supplier
								");
	}
	
}
?>