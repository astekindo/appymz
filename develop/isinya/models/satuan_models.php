<?php

class Satuan_models extends CI_Model {
	
	public $satuan = '';
	
	public function satuan_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "SELECT a.id_satuan, a.nm_satuan, a.keterangan
            FROM mst.tm_satuan a
			where a.aktif is true";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->satuan.='<tr class="gradeX">';
                $this->satuan.='<td>'. $no .'</td>
							    <td>'. $row->nm_satuan .'</td>
							    <td>'. $row->keterangan .'</td>
							    <td>
								<a href="'.base_url().'satuan/form/'.$row->id_satuan.'" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
								&nbsp;<a href="#" class="tablectrl_small bDefault tipS" title="Delete" onClick="confirmationDel('.$row->id_satuan.',\''.base_url().'satuan/delete\');"><span class="iconb" data-icon="&#xe136;"></span></a>
							    </td>';
                $this->satuan.='</tr>';
            }
        } else {
            $this->satuan = '';
        }
        return $this->satuan;
	}
	
	function getData($id)
	{
		$query = $this->db->get_where('mst.tm_satuan',array('aktif'=>'true','id_satuan'=>$id));
		return $query->result_array();
	}
	
        
	function get_last_records()
	{
		$query = $this->db->query('SELECT * FROM mst.tm_satuan WHERE id_satuan = (SELECT MAX(id_satuan) FROM mst.tm_satuan)');
		return $query->result_array();
	}
	
	function add_record($data) 
	{
		$this->db->insert('mst.tm_satuan', $data);
		return;
	}
	
	function update_record($data,$id) 
	{
		$this->db->where('id_satuan',$id);
		$this->db->update('mst.tm_satuan', $data);
	}
	
	function delete_row()
	{
		$this->db->where('id_satuan', $this->uri->segment(3));
		$this->db->delete('mst.tm_satuan');
	}
	
}
?>