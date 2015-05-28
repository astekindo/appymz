<?php

class User_models extends CI_Model {
	
	public $user = '';
	
	public function user_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "SELECT a.id_user, a.username, a.email, a.id_usergroup, b.nama_usergroup
            FROM mst.tm_user a
			JOIN mst.tm_usergroup b on b.id_usergroup = a.id_usergroup AND b.aktif is true
			where a.aktif is true";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->user.='<tr class="gradeX">';
                $this->user.='<td>'. $no .'</td>
							  <td>'. $row->username .'</td>
							  <td>'. $row->email .'</td>
							  <td>'. $row->nama_usergroup .'</td>
							  <td>
								<a href="'.base_url().'user/form/'.$row->id_user.'" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
								&nbsp;<a href="#" class="tablectrl_small bDefault tipS" title="Delete" onClick="confirmationDel('.$row->id_user.',\''.base_url().'user/delete\');"><span class="iconb" data-icon="&#xe136;"></span></a>
							</td>';
                $this->user.='</tr>';
            }
        } else {
            $this->user = '';
        }
        return $this->user;
	}
	
	function getData($id)
	{
		$query = $this->db->get_where('mst.tm_user',array('aktif'=>'true','id_user'=>$id));
		return $query->result_array();
	}
		
	function add_record($data) 
	{
		$this->db->insert('mst.tm_user', $data);
		return;
	}
	
	function update_record($data,$id) 
	{
		$this->db->where('id_user',$id);
		$this->db->update('mst.tm_user', $data);
	}
	
	function delete_row()
	{
		$this->db->where('id_user', $this->uri->segment(3));
		$this->db->delete('mst.tm_user');
	}
	
	public function usergroup_data(){
		$sql= "SELECT id_usergroup, nama_usergroup
		FROM mst.tm_usergroup WHERE aktif is true";
		$q = $this->db->query($sql);
		$row[0] = '- Pilih Usergroup -';
		foreach ($q->result() as $r)
		{
			$row[$r->id_usergroup]=$r->nama_usergroup;
		}
		return $row;
	}
	
}
?>