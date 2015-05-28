<?php

class Min_stok_models extends CI_Model {
	
	public $minstok = '';
	
	public function minstok_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select a.kd_produk, a.nama_produk, a.qty_oh, a.min_stok
					from mst.tm_produk a
					where a.qty_oh < a.min_stok";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->minstok.='<tr class="gradeX">';
                $this->minstok.='<td align="center">' . $no . '</td>
							  <td>'. $row->kd_produk .'</td>
							  <td>'. $row->nama_produk .'</td>
							  <td>'. $row->qty_oh .'</td>
							  <td>'. $row->min_stok .'</td>
							  <td align="center"  width="8%">
								<a href="min_stok/detail_min_stok/'. $row->kd_produk .'" class="cblsupprod" />
								<input type="button" style="float: center;margin-top:-10px;margin-right:5px;" name="detailminstok" id="detailminstok" value="Detail" class="buttonM bRed" />
								</a>
							  </td>';
                $this->minstok.='</tr>';
            }
        } else {
            $this->minstok = '';
        }
        return $this->minstok;
	}
	
}
?>