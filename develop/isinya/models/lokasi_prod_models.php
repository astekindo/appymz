<?php

class Lokasi_prod_models extends CI_Model {
	
	public $lokasiprod = '';
	
	public function lokasiprod_content() {
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
                $this->lokasiprod.='<tr class="gradeX">';
                $this->lokasiprod.='<td align="center">' . $no . '</td>
							  <td>'. $row->nama_lokasi .'</td>
							  <td>'. $row->nama_blok .'</td>
							  <td>'. $row->nama_sub_blok .'</td>
							  <td>'. $row->kapasitas .'</td>
							  <td align="center"  width="8%">
								<a href="lokasi_prod/detail_produk_lokasi/'. $row->kd_lokasi .'%7C'. $row->kd_blok .'%7C'. $row->kd_sub_blok .'" class="cblsupprod" />
								<input type="button" style="float: center;margin-top:-10px;margin-right:5px;" name="detailpr" id="detailpr" value="Detail" class="buttonM bRed" />
								</a>
							  </td>';
                $this->lokasiprod.='</tr>';
            }
        } else {
            $this->lokasiprod = '';
        }
        return $this->lokasiprod;
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
	
}
?>