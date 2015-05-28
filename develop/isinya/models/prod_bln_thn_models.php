<?php

class Prod_bln_thn_models extends CI_Model {
	
	public $prodblnthn = '';
	
	public function prodblnthn_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select a.bulan, a.tahun, a.kd_produk, b.nama_produk, a.qty_in, a.qty_out, a.qty_oh, a.qty_mutasi_in, a.qty_mutasi_out, a.qty_target
					from mst.td_brg_per_bln_thn a
					join mst.tm_produk b on (b.kd_produk = a.kd_produk)";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->prodblnthn.='<tr class="gradeX">';
                $this->prodblnthn.='<td align="center">' . $no . '</td>
							  <td>'. $row->bulan .'</td>
							  <td>'. $row->tahun .'</td>
							  <td>'. $row->kd_produk .'</td>
							  <td>'. $row->nama_produk .'</td>
							  <td>'. $row->qty_in .'</td>
							  <td>'. $row->qty_out .'</td>
							  <td>'. $row->qty_oh .'</td>
							  <td>'. $row->qty_mutasi_in .'</td>
							  <td>'. $row->qty_mutasi_out .'</td>
							  <td>'. $row->qty_target .'</td>
							  ';
                $this->prodblnthn.='</tr>';
            }
        } else {
            $this->prodblnthn = '';
        }
        return $this->prodblnthn;
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