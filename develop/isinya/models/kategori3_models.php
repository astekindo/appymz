<?php

class Kategori3_models extends CI_Model {
    public $kategori3 = '';

    public function kategori3_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select e.id_kategori3, e.kd_kategori1, e.nama_kategori1, e.kd_kategori2, e.nama_kategori2, e.kd_kategori3, e.nama_kategori3, f.kd_kategori1 kategori from (select c.id_kategori3, c.kd_kategori1, a.nama_kategori1, c.kd_kategori2, b.nama_kategori2, c.kd_kategori3,c.nama_kategori3 from mst.tm_kategori1 a, mst.tm_kategori2 b, mst.tm_kategori3 c where c.kd_kategori2=b.kd_kategori2 and c.kd_kategori1=a.kd_kategori1 and b.kd_kategori1=a.kd_kategori1 and c.aktif = true) e left outer join (select kd_kategori1, kd_kategori2, kd_kategori3 from mst.tm_produk group by kd_kategori1, kd_kategori2, kd_kategori3) f on e.kd_kategori1=f.kd_kategori1 and e.kd_kategori2 = f.kd_kategori2 and e.kd_kategori3=f.kd_kategori3 order by e.kd_kategori1, e.nama_kategori1, e.kd_kategori2, e.nama_kategori2, e.kd_kategori3, e.nama_kategori3 ";
            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->kategori3.='<tr class="gradeX">';
                $this->kategori3.='<td align="center">' . $no . '</td><td align="center">' . $row->kd_kategori1 . '' . $row->kd_kategori2 . '' . $row->kd_kategori3 . '</td>
									<td>' . $row->nama_kategori1 . ' - ' . $row->nama_kategori2 . ' - ' . $row->nama_kategori3 . '</td>
									<td align="center">';
				if(!$row->kategori){
				$this->kategori3.='<a href="kategori3/form/'. $row->id_kategori3 .'" title="Edit" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe1db;"></span></a>
									   <a href="#" title="Delete" class="tablectrl_small bDefault tipS" onClick="confirmationDel('.$row->id_kategori3.',\''.base_url().'kategori3/delete\');"><span class="iconb" data-icon="&#xe136;"></a>';
				}ELSE{
				$this->kategori3.='<a href="kategori3/form/'. $row->id_kategori3 .'" title="Edit" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe1db;"></span></a>
									   <a href=\'javascript:window.alert("Kategori sudah terisi");\' title="Delete" class="tablectrl_small bDefault tipS"> <span class="iconb" data-icon="&#xe136;"></a>';
				}
                $this->kategori3.='</td></tr>';
            }
        } else {
            $this->kategori3 = '';
        }
        return $this->kategori3;
	}

     function getData($id_kategori3)
	{
		$query = $this->db->query("select c.id_kategori3, a.kd_kategori1, a.nama_kategori1, b.kd_kategori2, b.nama_kategori2, 		c.kd_kategori3,c.nama_kategori3 from mst.tm_kategori1 a, mst.tm_kategori2 b, mst.tm_kategori3 c where c.kd_kategori2 = b.kd_kategori2 and c.kd_kategori1=a.kd_kategori1 and b.kd_kategori1=a.kd_kategori1 and c.aktif = true and c.id_kategori3='$id_kategori3'");
		return $query->result_array();
	}
	
	function get_last_records($kd_kategori1,$kd_kategori2)
	{
		$query = $this->db->query("SELECT max(to_number(kd_kategori3,'99')) kd_kategori3 FROM mst.tm_kategori3 
									WHERE kd_kategori1 = '$kd_kategori1' and kd_kategori2='$kd_kategori2'");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = $row->kd_kategori3;
                }
                return $return_value;
	}
	function add_record($data) 
	{
		$this->db->insert('mst.tm_kategori3', $data);
		return;
	}
	
	function update_record($data,$id_kategori3) 
	{
		$this->db->where('id_kategori3', $id_kategori3);
		$this->db->update('mst.tm_kategori3', $data);
	}
	
	function delete_row($id_kategori3)
	{
		$this->db->where('id_kategori3', $this->uri->segment(3));
		$this->db->delete('mst.tm_kategori3');
	}

	// function untuk mendapatkan data untuk dropdown kategori1
	function get_kategori1()
	{
		$query = $this->db->query("select kd_kategori1,nama_kategori1 from mst.tm_kategori1 where aktif = true");

		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	// function untuk mendapatkan data untuk dropdown kategori2, sesuai $id_kategori3 kategori1
	function get_kategori2($id1)
	{
		$query = $this->db->query("select a.kd_kategori2,a.nama_kategori2 
										  from mst.tm_kategori2 a,mst.tm_kategori1 b  
									where a.kd_kategori1=b.kd_kategori1 
										  and a.kd_kategori1='$id1' and a.aktif = true
									order by a.nama_kategori2 asc");
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