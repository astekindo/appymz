<?php

class Usergroup_models extends CI_Model {
	
	public $usergroup = '';
	
	public function usergroup_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "SELECT a.id_usergroup, a.nama_usergroup, a.deskripsi
            FROM mst.tm_usergroup a
			where a.aktif is true";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->usergroup.='<tr class="gradeX">';
                $this->usergroup.='<td>'. $no .'</td>
							    <td>'. $row->nama_usergroup .'</td>
							    <td>'. $row->deskripsi .'</td>
							    <td>
								<a href="'.base_url().'usergroup/form/'.$row->id_usergroup.'" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
								&nbsp;<a href="#" class="tablectrl_small bDefault tipS" title="Delete" onClick="confirmationDel('.$row->id_usergroup.',\''.base_url().'usergroup/delete\');"><span class="iconb" data-icon="&#xe136;"></span></a>
							    </td>';
                $this->usergroup.='</tr>';
            }
        } else {
            $this->usergroup = '';
        }
        return $this->usergroup;
	}
	
	function getData($id)
	{
		$query = $this->db->get_where('mst.tm_usergroup',array('aktif'=>'true','id_usergroup'=>$id));
		return $query->result_array();
	}
	
        
	function get_last_records()
	{
		$query = $this->db->query('SELECT * FROM mst.tm_usergroup WHERE id_usergroup = (SELECT MAX(id_usergroup) FROM mst.tm_usergroup)');
		return $query->result_array();
	}
	
	function add_record($data) 
	{
		$this->db->insert('mst.tm_usergroup', $data);
		return;
	}
	
	function update_record($data,$id) 
	{
		$this->db->where('id_usergroup',$id);
		$this->db->update('mst.tm_usergroup', $data);
	}
	
	function delete_row()
	{
		$this->db->where('id_usergroup', $this->uri->segment(3));
		$this->db->delete('mst.tm_usergroup');
	}
	
}
?>