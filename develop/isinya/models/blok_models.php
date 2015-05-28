<?php

class Blok_models extends CI_Model {
	
	public $blok = '';
	
	public function blok_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "SELECT a.id_blok, a.kd_blok, a.kd_lokasi, b.nama_lokasi, a.nama_blok
            FROM mst.tm_blok a
			join mst.tm_lokasi b on b.kd_lokasi = a.kd_lokasi
			where a.aktif is true";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
				$jml['jml'] = $this->cek_blok($row->kd_lokasi, $row->kd_blok);
				
                $this->blok.='<tr class="gradeX">';
				if ($jml['jml'] == '0')
				{
                $this->blok.='<td>'. $no .'</td>
							  <td>'. $row->kd_lokasi .'</td>
							  <td>'. $row->kd_blok .'</td>
							  <td>'. $row->nama_lokasi .'</td>
							  <td>'. $row->nama_blok .'</td>
							  <td>
								<a href="'.base_url().'blok/form/'.$row->id_blok.'" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
								&nbsp;<a href="#" class="tablectrl_small bDefault tipS" title="Delete" onClick="confirmationDel('.$row->id_blok.',\''.base_url().'blok/delete\');"><span class="iconb" data-icon="&#xe136;"></span></a>
							</td>';
				} else {
				$this->blok.='<td>'. $no .'</td>
							  <td>'. $row->kd_lokasi .'</td>
							  <td>'. $row->kd_blok .'</td>
							  <td>'. $row->nama_lokasi .'</td>
							  <td>'. $row->nama_blok .'</td>
							  <td>
								<a href="'.base_url().'blok/form/'.$row->id_blok.'" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
								&nbsp;<a href="#" class="tablectrl_small bDefault tipS" title="Delete" onClick=""><span class="iconb" data-icon="&#xe136;"></span></a>
							</td>';
				}
                $this->blok.='</tr>';
            }
        } else {
            $this->blok = '';
        }
        return $this->blok;
	}
	
	function getData($id)
	{
		$sql1 = "select a.id_blok, a.kd_lokasi, a.kd_blok, a.nama_blok, b.nama_lokasi
				from mst.tm_blok a
				join mst.tm_lokasi b on b.kd_lokasi = a.kd_lokasi
				where a.aktif=true and a.id_blok='$id'";
		$query = $this->db->query($sql1);
		//$query = $this->db->get_where('mst.tm_blok',array('aktif'=>'true','id_blok'=>$id));
		return $query->result_array();
	}
	
        
	public function getMaxKode($kd_lokasi)
	{
		$q = $this->db->query("select MAX(RIGHT(kd_blok,2)) as kd_max from mst.tm_blok where kd_lokasi ='$kd_lokasi'");
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
		$this->db->insert('mst.tm_blok', $data);
		return;
	}
	
	function update_record($data,$id) 
	{
		$this->db->where('id_blok',$id);
		$this->db->update('mst.tm_blok', $data);
	}
	
	function delete_row()
	{
		$this->db->where('id_blok', $this->uri->segment(3));
		$this->db->delete('mst.tm_blok');
	}
	
	public function lokasi_data(){
		$sql= "SELECT kd_lokasi, nama_lokasi
		FROM mst.tm_lokasi WHERE aktif is true";
		$q = $this->db->query($sql);
		$row[0] = ' Pilih Lokasi ';
		foreach ($q->result() as $r)
		{
			$row[$r->kd_lokasi]=$r->nama_lokasi;
		}
		return $row;
	}
	
	function cek_blok($kd_lokasi, $kd_blok)
	{
		$q = $this->db->query("
			select count(*) jml from mst.td_lokasi_per_brg
			where kd_lokasi = '".$kd_lokasi."'
			and kd_blok = '".$kd_blok."'
		");
		$jml = "";
		foreach($q->result() as $d)
		{
			if (!$d->jml)
			{$jml = 0;} else {$jml = $d->jml;}
		}
		return $jml;
	}
	
	function get_kd_blok($id_blok)
	{
		$q = $this->db->query("
			select kd_blok from mst.tm_blok
			where id_blok = '".$id_blok."'
		");
		$kd_blok = "";
		foreach($q->result() as $d)
		{
			if (!$d->kd_blok)
			{$kd_blok ="";} else {$kd_blok = $d->kd_blok;}
		}
		return $kd_blok;
	}
	
}
?>