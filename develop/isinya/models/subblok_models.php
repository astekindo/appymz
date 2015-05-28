<?php

class Subblok_models extends CI_Model {
	
	public $subblok = '';
	
	public function subblok_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "SELECT a.id_sub_blok, a.kd_sub_blok, a.kd_blok, a.kd_lokasi, b.nama_blok, c.nama_lokasi, a.nama_sub_blok, a.kapasitas
            FROM mst.tm_sub_blok a
			join mst.tm_blok b on b.kd_blok = a.kd_blok and b.kd_lokasi = a.kd_lokasi
			join mst.tm_lokasi c on c.kd_lokasi = b.kd_lokasi
			where a.aktif is true";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
				$jml['jml'] = $this->cek_sub_blok($row->kd_lokasi, $row->kd_blok, $row->kd_sub_blok);
				
                $this->subblok.='<tr class="gradeX">';
				if ($jml['jml'] == '0')
				{
                $this->subblok.='<td>'. $no .'</td>
							  <td>'. $row->kd_lokasi .'</td>
							  <td>'. $row->kd_blok .'</td>
							  <td>'. $row->kd_sub_blok .'</td>
							  <td>'. $row->nama_lokasi .'</td>
							  <td>'. $row->nama_blok .'</td>
							  <td>'. $row->nama_sub_blok .'</td>
							  <td>'. $row->kapasitas .'</td>
							  <td>
								<a href="'.base_url().'sub_blok/form/'.$row->id_sub_blok.'" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
								&nbsp;<a href="#" class="tablectrl_small bDefault tipS" title="Delete" onClick="confirmationDel('.$row->id_sub_blok.',\''.base_url().'sub_blok/delete\');"><span class="iconb" data-icon="&#xe136;"></span></a>
							</td>';
				} else {
				$this->subblok.='<td>'. $no .'</td>
							  <td>'. $row->kd_lokasi .'</td>
							  <td>'. $row->kd_blok .'</td>
							  <td>'. $row->kd_sub_blok .'</td>
							  <td>'. $row->nama_lokasi .'</td>
							  <td>'. $row->nama_blok .'</td>
							  <td>'. $row->nama_sub_blok .'</td>
							  <td>'. $row->kapasitas .'</td>
							  <td>
								<a href="'.base_url().'sub_blok/form/'.$row->id_sub_blok.'" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
								&nbsp;<a href="#" class="tablectrl_small bDefault tipS" title="Delete" onClick=""><span class="iconb" data-icon="&#xe136;"></span></a>
							</td>';
				}
                $this->subblok.='</tr>';
            }
        } else {
            $this->subblok = '';
        }
        return $this->subblok;
	}
	
	function getData($id)
	{
		//$query = $this->db->get_where('mst.tm_sub_blok',array('aktif'=>'true','id_sub_blok'=>$id));
		$sql1 = "select a.id_sub_blok, a.kd_lokasi, a.kd_blok, a.kd_sub_blok, c.nama_lokasi, b.nama_blok, a.nama_sub_blok, a.kapasitas
				from mst.tm_sub_blok a
				join mst.tm_blok b on b.kd_blok = a.kd_blok 
				join mst.tm_lokasi c on c.kd_lokasi = a.kd_lokasi and c.kd_lokasi = b.kd_lokasi
				where a.aktif=true and a.id_sub_blok='$id'";
		$query = $this->db->query($sql1);
		return $query->result_array();
	}
	
        
	public function getMaxKode($kd_lokasi, $kd_blok)
	{
		$q = $this->db->query("select MAX(RIGHT(kd_sub_blok,2)) as kd_max from mst.tm_sub_blok where kd_lokasi ='$kd_lokasi' and kd_blok = '$kd_blok'");
		$kd = "";
		if($q->num_rows()>0)
		{
			foreach($q->result() as $k)
			{
				$tmp = ((int)$k->kd_max)+1;
				$kd = sprintf("%02s", $tmp);
			}
		}
		else
		{
			$kd = "01";
		}	
		return $kd;
	}
	
	function add_record($data) 
	{
		$this->db->insert('mst.tm_sub_blok', $data);
		return;
	}
	
	function update_record($data,$id) 
	{
		$this->db->where('id_sub_blok',$id);
		$this->db->update('mst.tm_sub_blok', $data);
	}
	
	function delete_row()
	{
		$this->db->where('id_sub_blok', $this->uri->segment(3));
		$this->db->delete('mst.tm_sub_blok');
	}
	
	function lokasi_data()
	{
		//$this->db->order_by("nama_lokasi", "asc");
		//$query = $this->db->get_where('mst.tm_lokasi',array('aktif'=>'true'));;
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
		/*$this->db->select('mst.tm_blok.kd_blok,mst.tm_blok.nama_blok');
		$this->db->order_by("nama_blok", "asc");
		$this->db->from('mst.tm_blok');
		$this->db->join('mst.tm_lokasi', 'mst.tm_blok.kd_lokasi = mst.tm_lokasi.kd_lokasi');
		$this->db->where('mst.tm_blok.kd_lokasi', $id);
		$this->db->where('mst.tm_blok.aktif', 'true');
		$query = $this->db->get();*/
		
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
	
	function cek_sub_blok($kd_lokasi, $kd_blok, $kd_sub_blok)
	{
		$q = $this->db->query("
			select count(*) jml from mst.td_lokasi_per_brg
			where kd_lokasi = '".$kd_lokasi."'
			and kd_blok = '".$kd_blok."'
			and kd_sub_blok = '".$kd_sub_blok."'
		");
		$jml = "";
		foreach($q->result() as $d)
		{
			if (!$d->jml)
			{$jml = 0;} else {$jml = $d->jml;}
		}
		return $jml;
	}
	
}
?>