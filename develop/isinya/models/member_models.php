<?php

class Member_models extends CI_Model {
    public $member = '';

    public function member_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select id_member, kd_member, nmmember, alamat_rumah, telepon, hp, 
							case when jenis='G' then 'GOLD' when jenis='S' then 'SILVER' when jenis='P' then 'PLATINUM' end jenis, 
							sdtgl, tgljoin, tgllahir, idno, status, tmplahir, agama, kelamin, kelurahan, kecamatan, kota, kodepos, fax, email, profesi, 
							nmpersh, alamat_kantor, teleponk, faxk 
					from mst.tm_member where aktif=true";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->member.='<tr class="gradeX">';
                $this->member.='<td align="center">' . $no . '</td><td align="center">' . $row->kd_member . '</td><td>' . $row->nmmember . '</td><td>' . $row->jenis . '</td>
									<td>' . $row->alamat_rumah . '</td><td>' . $row->telepon . '</td><td>' . $row->hp . '</td><td>' . $row->email . '</td>
									<td align="center"><a href="member/form/'. $row->id_member .'"  title="Edit" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe1db;"></span></a>
									   <a href="#" title="Delete" class="tablectrl_small bDefault tipS" onClick="confirmationDel('.$row->id_member.',\''.base_url().'member/delete\');"><span class="iconb" data-icon="&#xe136;"></a>
								   </td>';
                $this->member.='</tr>';
            }
        } else {
            $this->member = '';
        }
        return $this->member;
	}

    function getData($id)
	{
		$sql1 = "select id_member, kd_member, nmmember, alamat_rumah, telepon, hp, jenis, 
						to_char(sdtgl,'dd/mm/yyyy') sdtgl, to_char(tgljoin,'dd/mm/yyyy') tgljoin, to_char(tgllahir,'dd/mm/yyyy') tgllahir, 
						idno, status, tmplahir, agama, kelamin, kelurahan, kecamatan, kota, kodepos, fax, email, profesi, 
						nmpersh, alamat_kantor, teleponk, faxk 
				from mst.tm_member where aktif=true and id_member='$id'";
        $query = $this->db->query($sql1);
		return $query->result_array();
	}
	
	/*function get_records()
	{
		$query = $this->db->get('member');
		return $query->result_array();
	}*/
	function get_last_records()
	{
		$query = $this->db->query('select kd_member from mst.tm_member where kd_member = (select max(kd_member) from mst.tm_member)');
                $return_value = "";
                foreach ($query->result() as $row) {
                    $return_value = $row->kd_member;
                }
		return $return_value;
	}
        
	function add_record($data) 
	{
		$this->db->insert('mst.tm_member', $data);
		return;
	}
	
	function update_record($data,$id) 
	{
		$this->db->where('id_member', $id);
		$this->db->update('mst.tm_member', $data);
	}
	
	function delete_row($id)
	{
		$this->db->where('kd_member', $this->uri->segment(3));
		$this->db->delete('mst.tm_member');
	}
	public function nama_member(){
        $sql= "SELECT kd_member, nmmember
        FROM mst.tm_member";
        $q = $this->db->query($sql);
        $row[0] = ' - ';
        foreach ($q->result() as $r)
        {
            $row[$r->kd_member]=$r->nmmember;
        }
        return $row;
    }
}
?>