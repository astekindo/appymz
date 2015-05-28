<?php

class Kategori1_models extends CI_Model {

    public $kategori1 = '';

    public function kategori1_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select e.id_kategori1, e.kd_kategori1, e.nama_kategori1, f.kd_kategori1 kategori from (select a.id_kategori1, a.kd_kategori1, a.nama_kategori1 from mst.tm_kategori1 a where a.aktif = true) e left outer join (select kd_kategori1 from mst.tm_produk group by kd_kategori1) f on e.kd_kategori1=f.kd_kategori1 order by f.kd_kategori1, e.kd_kategori1, e.nama_kategori1";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->kategori1.='<tr class="gradeX">';
                $this->kategori1.='<td align="center">' . $no . '</td><td align="center">' . $row->kd_kategori1 . '</td><td>' . $row->nama_kategori1 . '</td>
									<td align="center">';
				if(!$row->kategori){
				$this->kategori1.='<a href="kategori1/form/'. $row->id_kategori1 .'"  title="Edit" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe1db;"></span></a><a href="#" title="Delete" class="tablectrl_small bDefault tipS" onClick="confirmationDel('.$row->id_kategori1.',\''.base_url().'kategori1/delete\');"><span class="iconb" data-icon="&#xe136;"></a>';
				}else{
				$this->kategori1.='<a href="kategori1/form/'. $row->id_kategori1 .'"  title="Edit" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe1db;"></span></a><a href=\'javascript:window.alert("Kategori sudah terisi");\' title="Delete" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe136;"></a>';
                }
				$this->kategori1.='</td></tr>';
            }
        } else {
            $this->kategori1 = '';
        }
        return $this->kategori1;
	}

        function getData($id_kategori1)
	{
		$query = $this->db->get_where('mst.tm_kategori1',array(/**'status'=>'0',*/'id_kategori1'=>$id_kategori1));
		return $query->result_array();
	}
	
	function get_last_records()
	{
		$query = $this->db->query("SELECT to_number(kd_kategori1,'99') kd_kategori1 FROM mst.tm_kategori1 WHERE kd_kategori1 = (SELECT MAX(kd_kategori1) FROM mst.tm_kategori1)");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = $row->kd_kategori1;
                }
                return $return_value;
	}
	
	function add_record($data) 
	{
		$this->db->insert('mst.tm_kategori1', $data);
		return;
	}
	
	function update_record($datau,$id_kategori1) 
	{
		$this->db->where('id_kategori1', $id_kategori1);
		$this->db->update('mst.tm_kategori1', $datau);
	}
	
	function delete_row($id_kategori1)
	{
		$this->db->where('id_kategori1', $this->uri->segment(3));
		$this->db->delete('tm_kategori1');
	}
	
}
?>