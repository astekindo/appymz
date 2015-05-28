<?php

class mutasibarang_models extends CI_Model {
    
	public $mutasibarang = '';

    public function mutasibarang_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select * from mst.tt_mst_mutasi_barang a";
            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->mutasibarang.='<tr class="gradeX">';
                $this->mutasibarang.='<td align="center">' . $no . '</td><td align="center">' . $row->no_mutasi .'</td>
									<td align="left">' . $row->keterangan .'</td>
									<td align="left">' . $row->created_by .'</td><td align="center">' . $row->created_date .'</td>
									<td align="center">
									<a href="mutasibarang/detail_mutasibarang/'. $row->no_mutasi .'" class="cbdetailmutasi" />
									<input type="button" style="float: center;margin-top:-10px;margin-right:5px;" name="detailpr" id="detailpr" value="Detail" class="buttonM bBlue" />
									</a>
								   </td>';
                $this->mutasibarang.='</tr>';
            }
        } else {
            $this->mutasibarang = '';
        }
        return $this->mutasibarang;
	}

	// function untuk mendapatkan data untuk dropdown kategori1
	function get_lokasi()
	{
		$query = $this->db->query("select kd_lokasi,nama_lokasi from mst.tm_lokasi where aktif = true");

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
	function get_blok($id1)
	{
		$query = $this->db->query("select TEXTCAT(b.kd_lokasi,b.kd_blok) kd_blok,b.nama_blok
										  from mst.tm_lokasi a,mst.tm_blok b  
									where a.kd_lokasi=b.kd_lokasi
										  and b.kd_lokasi='$id1' and b.aktif = true
									order by b.nama_blok asc");

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
	function get_sub_blok($id2)
	{
		$query = $this->db->query("select a.kd_sub_blok,a.nama_sub_blok
									from mst.tm_sub_blok a,mst.tm_blok b, mst.tm_lokasi c
									where a.kd_lokasi=b.kd_lokasi  and a.kd_blok=b.kd_blok
									and a.kd_lokasi=c.kd_lokasi and b.kd_lokasi=c.kd_lokasi
									and a.kd_lokasi=substr('$id2',1,2) and a.kd_blok=substr('$id2',3,2) and a.aktif = true
									order by a.nama_sub_blok asc");
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