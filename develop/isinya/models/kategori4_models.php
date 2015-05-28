<?php

class Kategori4_models extends CI_Model {
    
	public $kategori4 = '';

    public function kategori4_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select e.id_kategori4, e.kd_kategori1, e.nama_kategori1, e.kd_kategori2, e.nama_kategori2, e.kd_kategori3, e.nama_kategori3, e.kd_kategori4, e.nama_kategori4, f.kd_kategori1 kategori from (select d.id_kategori4, d.kd_kategori1, a.nama_kategori1, d.kd_kategori2, b.nama_kategori2, d.kd_kategori3,c.nama_kategori3, d.kd_kategori4,d.nama_kategori4 from mst.tm_kategori1 a, mst.tm_kategori2 b, mst.tm_kategori3 c, mst.tm_kategori4 d where d.kd_kategori3=c.kd_kategori3 and d.kd_kategori2=c.kd_kategori2 and d.kd_kategori1=c.kd_kategori1 and d.kd_kategori1=a.kd_kategori1 and c.kd_kategori2=b.kd_kategori2 and c.kd_kategori1=a.kd_kategori1 and b.kd_kategori1=a.kd_kategori1 and d.aktif = true) e left outer join (select kd_kategori1,kd_kategori2,kd_kategori3,kd_kategori4 from mst.tm_produk group by kd_kategori1, kd_kategori2, kd_kategori3, kd_kategori4) f on e.kd_kategori1=f.kd_kategori1 and e.kd_kategori2=f.kd_kategori2 and e.kd_kategori3=f.kd_kategori3 and e.kd_kategori4=f.kd_kategori4 order by e.kd_kategori1, e.nama_kategori1, e.kd_kategori2, e.nama_kategori2, e.kd_kategori3, e.nama_kategori3, e.kd_kategori4, e.nama_kategori4";
            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->kategori4.='<tr class="gradeX">';
                $this->kategori4.='<td align="center">' . $no . '</td><td align="center">' . $row->kd_kategori1 . '' . $row->kd_kategori2 . '' . $row->kd_kategori3 . '' . $row->kd_kategori4 . '</td>
									<td>' . $row->nama_kategori1 . ' - ' . $row->nama_kategori2 . ' - ' . $row->nama_kategori3 . ' - ' . $row->nama_kategori4 . '</td>
									<td align="center">';
				if(!$row->kategori){
				$this->kategori4.='<a href="kategori4/form/'. $row->id_kategori4 .'" title="Edit" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe1db;"></span></a>
									<a href="#" title="Delete" class="tablectrl_small bDefault tipS" onClick="confirmationDel('.$row->id_kategori4.',\''.base_url().'kategori4/delete\');"><span class="iconb" data-icon="&#xe136;"></a>
								   </td>';
				}else{
				$this->kategori4.='<a href="kategori4/form/'. $row->id_kategori4 .'" title="Edit" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe1db;"></span></a>
									<a href=\'javascript:window.alert("Kategori sudah terisi");\' title="Delete" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe136;"></a>
								   </td>';                
				}
				$this->kategori4.='</tr>';
            }
        } else {
            $this->kategori4 = '';
        }
        return $this->kategori4;
	}

     function getData($id_kategori4)
	{
		$query = $this->db->query("select d.id_kategori4, a.kd_kategori1, a.nama_kategori1, b.kd_kategori2, b.nama_kategori2, 		c.kd_kategori3,c.nama_kategori3, d.kd_kategori4,d.nama_kategori4 from mst.tm_kategori1 a, mst.tm_kategori2 b, mst.tm_kategori3 c, mst.tm_kategori4 d where d.kd_kategori3=c.kd_kategori3 and d.kd_kategori2=c.kd_kategori2 and d.kd_kategori1=c.kd_kategori1 and d.kd_kategori1=a.kd_kategori1 and c.kd_kategori2 = b.kd_kategori2 and c.kd_kategori1=a.kd_kategori1 and b.kd_kategori1=a.kd_kategori1 and d.aktif = true and d.id_kategori4='$id_kategori4'");
		return $query->result_array();
	}

     function getdisable($kd_kategori1,$kd_kategori2,$kd_kategori3,$kd_kategori4)
	{
		$query = $this->db->query("select count(1) from mst.tm_produk where kd_kategori1='$kd_kategori1' and kd_kategori2='$kd_kategori2' and kd_kategori3='$kd_kategori3' and kd_kategori4='$kd_kategori4'");
		return $query->result_array();
	}
	
	function get_last_records($kd_kategori1,$kd_kategori2,$kd_kategori3)
	{
		$query = $this->db->query("SELECT max(to_number(kd_kategori4,'99')) kd_kategori4 FROM mst.tm_kategori4 
									WHERE kd_kategori1 = '$kd_kategori1' and kd_kategori2='$kd_kategori2' and kd_kategori3='$kd_kategori3'");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = $row->kd_kategori4;
                }
                return $return_value;
	}
	
	function add_record($data) 
	{
		$this->db->insert('mst.tm_kategori4', $data);
		return;
	}
	
	function update_record($data,$id_kategori4) 
	{
		$this->db->where('id_kategori4', $id_kategori4);
		$this->db->update('mst.tm_kategori4', $data);
	}
	
	function delete_row($id_kategori4)
	{
		$this->db->where('id_kategori4', $this->uri->segment(3));
		$this->db->delete('mst.tm_kategori4');
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

	// function untuk mendapatkan data untuk dropdown kategori2, sesuai $id kategori1
	function get_kategori2($id1)
	{
		$query = $this->db->query("select TEXTCAT(a.kd_kategori1,a.kd_kategori2) kd_kat2,a.nama_kategori2 
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

	// function untuk mendapatkan data untuk dropdown kategori3, sesuai $id kategori2
	function get_kategori3($id2)
	{
		$query = $this->db->query("select a.kd_kategori3,a.nama_kategori3
									from mst.tm_kategori3 a,mst.tm_kategori2 b, mst.tm_kategori1 c
									where a.kd_kategori1=b.kd_kategori1  and a.kd_kategori2=b.kd_kategori2 
									and a.kd_kategori1=c.kd_kategori1 and b.kd_kategori1=c.kd_kategori1
									and a.kd_kategori1=substr('$id2',1,2) and a.kd_kategori2=substr('$id2',3,2) and a.aktif = true
									order by a.nama_kategori3 asc");
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