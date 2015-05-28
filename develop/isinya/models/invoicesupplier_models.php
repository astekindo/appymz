<?php

class Invoicesupplier_models extends CI_Model {
    
	public $receiveorder = '';

    public function receiveorder_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select *,to_char(created_date,'dd-mm-yyyy hh:mm:ss') complite_date							
						FROM mst.tt_receive_order";
            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->receiveorder.='<tr class="gradeX">';
                $this->receiveorder.='<td align="center">' . $no . '</td><td align="center">' . $row->no_ro . '</td>
									<td>' . $row->no_po . '</td><td align="center">' . $row->no_pr . '</td><td align="center">' . $row->kd_supplier . '</td>
									<td align="center">' . $row->created_by . '</td><td align="center">' . $row->complite_date . '</td>
									';
                $this->receiveorder.='</tr>';
            }
        } else {
            $this->receiveorder = '';
        }
        return $this->receiveorder;
	}
	public function invoice_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select *,to_char(created_date,'dd-mm-yyyy hh:mm:ss') complite_date							
						FROM mst.tt_receive_order WHERE status_invoice=1";
            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->receiveorder.='<tr class="gradeX">';
                $this->receiveorder.='<td align="center">' . $no . '</td><td align="center">' . $row->no_ro . '</td>
									<td>' . $row->no_po . '</td><td align="center">' . $row->no_pr . '</td><td align="center">' . $row->kd_supplier . '</td>
									';
                $this->receiveorder.='</tr>';
            }
        } else {
            $this->receiveorder = '';
        }
        return $this->receiveorder;
	}
//<td align="center"><a href="receiveorder/form/'. $row->id_ro .'" title="Edit" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe1db;"></span></a><a href="#" title="Delete" class="tablectrl_small bDefault tipS" onClick="confirmationDel('.$row->id_ro.',\''.base_url().'receiveorder/hapuspr\');"><span class="iconb" data-icon="&#xe136;"></a></td>
	public function receiveorder_update_invoice($kdro){
		$sql = "UPDATE mst.tt_receive_order 
				SET status_invoice = 1
				WHERE no_ro = '" .$kdro. "'";
		$this->app_model->manualquery($sql);

	}
	function lokasi_data()
	{

		$query = $this->db->query("select kd_lokasi,nama_lokasi from mst.tm_lokasi where aktif is true");
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}
	
	function blok_data($id)
	{
		
		$query = $this->db->query("select a.kd_blok,a.nama_blok
										  from mst.tm_blok a, mst.tm_lokasi b  
									where a.kd_lokasi = b.kd_lokasi
										  and a.kd_lokasi = '$id' and a.aktif is true
									order by a.nama_blok asc");
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	function subblok_data($id)
	{
		
		$query = $this->db->query("select a.kd_sub_blok,a.nama_sub_blok
									from mst.tm_sub_blok a, mst.tm_blok b  
									where a.kd_blok = b.kd_blok
									and a.kd_blok = '$id' and a.aktif is true
									order by a.nama_sub_blok asc");
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	

	
}
?>