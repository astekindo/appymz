<?php

class Produk_models extends CI_Model {
	
	public $produk = '';
	
	public function produk_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "SELECT a.id_produk, a.kd_kategori1, c.nama_kategori1, a.kd_kategori2, d.nama_kategori2, a.kd_kategori3, e.nama_kategori3, a.kd_kategori4,
						f.nama_kategori4, a.thn_reg, a.no_urut, a.id_satuan, b.nm_satuan, a.nama_produk, a.kd_produk, a.kd_produk_lama, a.kd_produk_supp,
						a.kd_peruntukkan, a.qty_in, a.qty_out, a.qty_oh, a.qty_do, a.qty_siap_jual, a.min_stok, a.max_stok, a.min_order, a.hrg_supplier,
						a.hrg_hpp, a.hrg_jual, a.disk_persen_kons1, a.disk_persen_kons2, a.disk_persen_kons3, a.disk_persen_kons4, a.disk_amt_kons1,
						a.disk_amt_kons2, a.disk_amt_kons3, a.disk_amt_kons4
            FROM mst.tm_produk a
			LEFT JOIN mst.tm_satuan b ON b.id_satuan = a.id_satuan AND b.aktif is true
			LEFT JOIN mst.tm_kategori1 c ON c.kd_kategori1 = a.kd_kategori1 AND c.aktif is true
			LEFT JOIN mst.tm_kategori2 d ON d.kd_kategori2 = a.kd_kategori2 AND d.kd_kategori1 = c.kd_kategori1 AND d.aktif is true
			LEFT JOIN mst.tm_kategori3 e ON e.kd_kategori3 = a.kd_kategori3 AND e.kd_kategori2 = d.kd_kategori2 AND e.kd_kategori1 = c.kd_kategori1 AND e.aktif is true
			LEFT JOIN mst.tm_kategori4 f ON f.kd_kategori4 = a.kd_kategori4 AND f.kd_kategori3 = e.kd_kategori3 AND f.kd_kategori2 = d.kd_kategori2 AND f.kd_kategori1 = c.kd_kategori1 AND f.aktif is true
			where a.aktif is true
			";

            $query = $this->db->query($sql1);
			$no = 0;
            foreach ($query->result() as $row) {
				$no = $no+1;
                $this->produk.='<tr class="gradeX">';
                $this->produk.='<td>'. $no .'</td>
							    <td>'. $row->nama_kategori1 .'</td>
							    <td>'. $row->nama_kategori2 .'</td>
								<td>'. $row->nama_kategori3 .'</td>
								<td>'. $row->nama_kategori4 .'</td>
								<td>'. $row->thn_reg .'</td>
								<td>'. $row->no_urut .'</td>
								<td>'. $row->nama_produk .'</td>
								<td>'. $row->kd_produk .'</td>
								<td>'. $row->kd_produk_lama .'</td>
								<td>'. $row->kd_produk_supp .'</td>
								<td>'. $row->nm_satuan .'</td>
								<td>'. $row->kd_peruntukkan .'</td>
								<td>'. $row->qty_in .'</td>
								<td>'. $row->qty_out .'</td>
								<td>'. $row->qty_oh .'</td>
								<td>'. $row->qty_do .'</td>
								<td>'. $row->qty_siap_jual .'</td>
								<td>'. $row->min_stok .'</td>
								<td>'. $row->max_stok .'</td>
								<td>'. $row->min_order .'</td>
								<td>'. $row->hrg_supplier .'</td>
								<td>'. $row->hrg_hpp .'</td>
								<td>'. $row->hrg_jual .'</td>
								<td>'. $row->disk_persen_kons1 .'</td>
								<td>'. $row->disk_amt_kons1 .'</td>
								<td>'. $row->disk_persen_kons2 .'</td>
								<td>'. $row->disk_amt_kons2 .'</td>
								<td>'. $row->disk_persen_kons3 .'</td>
								<td>'. $row->disk_amt_kons3 .'</td>
								<td>'. $row->disk_persen_kons4 .'</td>
								<td>'. $row->disk_amt_kons4 .'</td>
							    <td>
								<a href="'.base_url().'produk/form/'.$row->id_produk.'" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
								&nbsp;<a href="#" class="tablectrl_small bDefault tipS" title="Delete" onClick="confirmationDel('.$row->id_produk.',\''.base_url().'produk/delete\');"><span class="iconb" data-icon="&#xe136;"></span></a>
							    </td>';
                $this->produk.='</tr>';
            }
        } else {
            $this->produk = '';
        }
        return $this->produk;
	}
	
	function getData($id)
	{
		//$query = $this->db->get_where('mst.tm_produk',array('aktif'=>'true','id_produk'=>$id));
		$sql1 = "SELECT a.id_produk, a.kd_kategori1, c.nama_kategori1, a.kd_kategori2, d.nama_kategori2, a.kd_kategori3, e.nama_kategori3, a.kd_kategori4,
						f.nama_kategori4, a.thn_reg, a.no_urut, a.id_satuan, b.nm_satuan, a.nama_produk, a.kd_produk, a.kd_produk_lama, a.kd_produk_supp,
						CASE WHEN a.kd_peruntukkan='1' THEN 'checked' ELSE 'false' END kd_peruntukkan, a.qty_in, a.qty_out, a.qty_oh, a.qty_do, a.qty_siap_jual,
						a.min_stok, a.max_stok, a.min_order, a.hrg_supplier, a.hrg_hpp, a.hrg_jual, a.disk_persen_kons1, a.disk_persen_kons2, 
						a.disk_persen_kons3, a.disk_persen_kons4, a.disk_amt_kons1, a.disk_amt_kons2, a.disk_amt_kons3, a.disk_amt_kons4
            FROM mst.tm_produk a
			LEFT JOIN mst.tm_satuan b ON b.id_satuan = a.id_satuan AND b.aktif is true
			LEFT JOIN mst.tm_kategori1 c ON c.kd_kategori1 = a.kd_kategori1 AND c.aktif is true
			LEFT JOIN mst.tm_kategori2 d ON d.kd_kategori2 = a.kd_kategori2 AND d.kd_kategori1 = c.kd_kategori1 AND d.aktif is true
			LEFT JOIN mst.tm_kategori3 e ON e.kd_kategori3 = a.kd_kategori3 AND e.kd_kategori2 = d.kd_kategori2 AND e.kd_kategori1 = c.kd_kategori1 AND e.aktif is true
			LEFT JOIN mst.tm_kategori4 f ON f.kd_kategori4 = a.kd_kategori4 AND f.kd_kategori3 = e.kd_kategori3 AND f.kd_kategori2 = d.kd_kategori2 AND f.kd_kategori1 = c.kd_kategori1 AND f.aktif is true
			where a.aktif is true and a.id_produk = '$id'";
		$query = $this->db->query($sql1);
		return $query->result_array();
	}
	
        
	function get_last_records()
	{
		$query = $this->db->query('SELECT * FROM mst.tm_produk WHERE id_produk = (SELECT MAX(id_produk) FROM mst.tm_produk)');
		return $query->result_array();
	}
	
	function add_record($data) 
	{
		$this->db->insert('mst.tm_produk', $data);
		return;
	}
	
	function update_record($data,$id) 
	{
		$this->db->where('id_produk',$id);
		$this->db->update('mst.tm_produk', $data);
	}
	
	function delete_row()
	{
		$this->db->where('id_produk', $this->uri->segment(3));
		$this->db->delete('mst.tm_produk');
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
		$query = $this->db->query("select a.kd_kategori1||a.kd_kategori2||a.kd_kategori3 kd_kat3,a.nama_kategori3
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
	
	// function untuk mendapatkan data untuk dropdown kategori3, sesuai $id kategori2
	function get_kategori4($id3)
	{
		$query = $this->db->query("select d.kd_kategori1||d.kd_kategori2||d.kd_kategori3||d.kd_kategori4 kd_kat4,d.nama_kategori4
									from mst.tm_kategori1 a, mst.tm_kategori2 b, mst.tm_kategori3 c, mst.tm_kategori4 d
									where d.kd_kategori3=c.kd_kategori3 and d.kd_kategori2=c.kd_kategori2
									and d.kd_kategori1=c.kd_kategori1 and d.kd_kategori1=a.kd_kategori1
									and c.kd_kategori2=b.kd_kategori2 and c.kd_kategori1=a.kd_kategori1
									and b.kd_kategori1=a.kd_kategori1
									and d.kd_kategori1=substr('$id3',1,2) and d.kd_kategori2=substr('$id3',3,2) and d.kd_kategori3=substr('$id3',5,2) and d.aktif = true
									order by d.nama_kategori4 asc");
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}
	
	public function satuan_data(){
		$sql= "SELECT id_satuan, nm_satuan
		FROM mst.tm_satuan WHERE aktif is true";
		$q = $this->db->query($sql);
		$row[0] = ' Pilih Satuan ';
		foreach ($q->result() as $r)
		{
			$row[$r->id_satuan]=$r->nm_satuan;
		}
		return $row;
	}
	
	public function getMaxKode($kd_kategori)
	{
		$q = $this->db->query("select MAX(RIGHT(kd_produk,3)) as kd_max from mst.tm_produk 
			where kd_kategori1||kd_kategori2||kd_kategori3||kd_kategori4 ='$kd_kategori'
			");
		$kd = "";
		$this_year = date("y");
		if($q->num_rows()>0)
		{
			foreach($q->result() as $k)
			{
				$tmp = ((int)$k->kd_max)+1;
				$kd = sprintf("%03s", $tmp);
			}
		}
		else
		{
			$kd = "001";
		}	
		return $kd;
	}
	
}
?>