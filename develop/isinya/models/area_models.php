<?php

class Area_models extends CI_Model {
    public $area = '';

    public function area_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select * from mst.tm_area where aktif=true";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->area.='<tr class="gradeX">';
                $this->area.='<td align="center">' . $no . '</td><td align="center">' . $row->nama_area . '</td><td>' . $row->alamat . '</td><td>' . $row->keterangan . '</td>
									<td align="center"><a href="area/form/'. $row->id_area .'"  title="Edit" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe1db;"></span></a>
									   <a href="#" title="Delete" class="tablectrl_small bDefault tipS" onClick="confirmationDel('.$row->id_area.',\''.base_url().'area/delete\');"><span class="iconb" data-icon="&#xe136;"></a>
								   </td>';
                $this->area.='</tr>';
            }
        } else {
            $this->area = '';
        }
        return $this->area;
	}

	function getData($id_area)
	{
		$query = $this->db->get_where('mst.tm_area',array('aktif'=>'true','id_area'=>$id_area));
		return $query->result_array();
	}
	
	function add_record($data) 
	{
		$this->db->insert('mst.tm_area', $data);
		return;
	}
	
	function update_record($data,$id_area) 
	{
		$this->db->where('id_area',$id_area);
		$this->db->update('mst.tm_area', $data);
	}
	
	function delete_row()
	{
		$this->db->where('id_area', $this->uri->segment(3));
		$this->db->delete('mst.tm_area');
	}
	
}
?>