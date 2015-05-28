<?php

class Rekening_models extends CI_Model {
	
	public $rekening = '';
	
	public function rekening_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "SELECT a.id_rekening, a.kd_rekening, a.nm_rekening
            FROM mst.tm_rekening a
			where a.aktif is true";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->rekening.='<tr class="gradeX">';
                $this->rekening.='<td>'. $no .'</td>
							    <td>'. $row->kd_rekening .'</td>
							    <td>'. $row->nm_rekening .'</td>
							    <td>
								<a href="'.base_url().'rekening/form/'.$row->id_rekening.'" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
								&nbsp;<a href="#" class="tablectrl_small bDefault tipS" title="Delete" onClick="confirmationDel('.$row->id_rekening.',\''.base_url().'rekening/delete\');"><span class="iconb" data-icon="&#xe136;"></span></a>
							    </td>';
                $this->rekening.='</tr>';
            }
        } else {
            $this->rekening = '';
        }
        return $this->rekening;
	}
	
	function getData($id)
	{
		$query = $this->db->get_where('mst.tm_rekening',array('aktif'=>'true','id_rekening'=>$id));
		return $query->result_array();
	}
	
        
	function get_last_records()
	{
		$query = $this->db->query('SELECT * FROM mst.tm_rekening WHERE id_rekening = (SELECT MAX(id_rekening) FROM mst.tm_rekening)');
		return $query->result_array();
	}
	
	function add_record($data) 
	{
		$this->db->insert('mst.tm_rekening', $data);
		return;
	}
	
	function update_record($data,$id) 
	{
		$this->db->where('id_rekening',$id);
		$this->db->update('mst.tm_rekening', $data);
	}
	
	function delete_row()
	{
		$this->db->where('id_rekening', $this->uri->segment(3));
		$this->db->delete('mst.tm_rekening');
	}
	
}
?>