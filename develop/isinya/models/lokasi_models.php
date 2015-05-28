<?php

class Lokasi_models extends CI_Model {
	
	public $lokasi = '';
	
	public function lokasi_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "SELECT a.id_lokasi, a.kd_lokasi, a.nama_lokasi
            FROM mst.tm_lokasi a where aktif is true";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
				$jml['jml'] = $this->cek_lokasi($row->kd_lokasi);
				
                $this->lokasi.='<tr class="gradeX">';
				if ($jml['jml'] == '0')
				{
                $this->lokasi.='<td>' . $no . '</td>
								<td>' . $row->kd_lokasi . '</td>
								<td>' . $row->nama_lokasi . '</td>
								<td>
									<a href="'.base_url().'lokasi/form/'.$row->id_lokasi.'" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
									&nbsp;<a href="#" class="tablectrl_small bDefault tipS" title="Delete" onClick="confirmationDel('.$row->id_lokasi.',\''.base_url().'lokasi/delete\');"><span class="iconb" data-icon="&#xe136;"></span></a>
								</td>';
				} else {
				$this->lokasi.='<td>' . $no . '</td>
								<td>' . $row->kd_lokasi . '</td>
								<td>' . $row->nama_lokasi . '</td>
								<td>
									<a href="'.base_url().'lokasi/form/'.$row->id_lokasi.'" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
									&nbsp;<a href="#" class="tablectrl_small bDefault tipS" title="Delete" onClick=""><span class="iconb" data-icon="&#xe136;"></span></a>
									
								</td>';
				}
                $this->lokasi.='</tr>';
            }
        } else {
            $this->lokasi = '';
        }
        return $this->lokasi;
	}
	
	function getData($id)
	{
		$query = $this->db->get_where('mst.tm_lokasi',array('aktif'=>'true','id_lokasi'=>$id));
		return $query->result_array();
	}
	
        
	public function getMaxKode()
	{
		$q = $this->db->query("select MAX(RIGHT(kd_lokasi,2)) as kd_max from mst.tm_lokasi");
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
		$this->db->insert('mst.tm_lokasi', $data);
		return;
	}
	
	function update_record($data,$id) 
	{
		$this->db->where('id_lokasi',$id);
		$this->db->update('mst.tm_lokasi', $data);
	}
	
	function delete_row()
	{
		$this->db->where('kode_lokasi', $this->uri->segment(3));
		$this->db->delete('tm_lokasi');
	}
	
	function cek_lokasi($kd_lokasi)
	{
		$q = $this->db->query("
			select count(*) jml from mst.td_lokasi_per_brg
			where kd_lokasi = '".$kd_lokasi."'
		");
		$jml = "";
		foreach($q->result() as $d)
		{
			if (!$d->jml)
			{$jml = 0;} else {$jml = $d->jml;}
		}
		return $jml;
	}
	
	function get_kd_lokasi($id_lokasi)
	{
		$q = $this->db->query("
			select kd_lokasi from mst.tm_lokasi
			where id_lokasi = '".$id_lokasi."'
		");
		$kd_lokasi = "";
		foreach($q->result() as $d)
		{
			if (!$d->kd_lokasi)
			{$kd_lokasi ="";} else {$kd_lokasi = $d->kd_lokasi;}
		}
		return $kd_lokasi;
	}
	
}
?>