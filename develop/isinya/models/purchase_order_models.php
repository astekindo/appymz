<?php

class Purchase_order_models extends CI_Model {

    public $approvedpr = '';

    public function approved_pr_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select id_pr, no_pr, subject, created_by, to_char(created_date,'dd-mm-yyyy hh:mm:ss') created_date, 
							case when status = '0' then 'Pending' when status = '1' then 'Approved' when status = '2' then 'Create PO' 
							when status = '3' then 'Approved Buyer' when status = '4' then 'Not Approved' end status, 
							status sts
						FROM mst.tt_purchase_request 
						WHERE status = '1'
						order by no_pr";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->approvedpr.=' <tr class="gradeX">';
                $this->approvedpr.=' <td align="center">' . $no . '</td>
									<td align="center">
										<a href="approval_pr/daftar_produkpr_edit/'. $row->id_pr .'" class="cblsupprod" />
										' . $row->no_pr . ' </a>
									</td>
									<td>'. $row->subject.'</td>
									<td align="center">' . $row->created_date . '</td>
									<td align="center">' . $row->created_by . '</td>
									<td align="center"  width="8%">
										<a href="purchase_order/form/'. $row->no_pr .'" />
										<input type="button" style="float: center;margin-top:-10px;margin-right:5px;" name="create_po" id="create_po" value="Create PO" class="buttonM bRed" />
										</a>
								   </td>';
                $this->approvedpr.='</tr>';
            }
        } else {
            $this->approvedpr = '';
        }
        return $this->approvedpr;
	}

	function getData($id_po)
	{
		$sql1 = "select a.no_po, a.no_pr, a.subject, a.kd_supplier, a.kd_kategori1, a.kd_kategori2, a.kd_kategori3, a.kd_kategori4, a.thn_reg,
				 a.no_urut, a.qty_beli, a.disk_persen_supp1, a.disk_persen_supp2, a.disk_persen_supp3, a.disk_persen_supp4, a.disk_amt_supp1,
				 a.disk_amt_supp2, a.disk_amt_supp3, a.disk_amt_supp4, a.hrg_supplier, a.dpp
					from mst.tt_purchase_order a where a.id_po='$id_po'";
		$query = $this->db->query($sql1);
		return $query->result_array();
	}
	
	public function getMaxKode()
	{
		$q = $this->db->query("select MAX(RIGHT(no_po,3)) as kd_max from mst.tt_purchase_order");
		$kd = "";
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
		return "PO".$kd;
	}
	
	function add_record($data) 
	{
		$this->db->insert('mst.tt_purchase_order', $data);
		return;
	}
	
	function update_record($data,$id_po) 
	{
		$this->db->where('id_po',$id_supp_per_brg);
		$this->db->update('mst.tt_purchase_order', $data);
	}
	
	function delete_row()
	{
		$this->db->where('id_po', $this->uri->segment(3));
		$this->db->delete('mst.tt_purchase_order');
	}
	
	public function getSuppData($kd_produk)
	{
		//return $this->db->get_where($table, $data);
		return $this->db->query("select a.kd_supplier, b.nama_supplier, a.waktu_top, a.konsinyasi, a.kd_produk, a.disk_persen_supp1, a.disk_persen_supp2,
								  a.disk_persen_supp3, a.disk_persen_supp4, a.disk_amt_supp1, a.disk_amt_supp2, a.disk_amt_supp3, a.disk_amt_supp4, 
								  a.hrg_supplier, a.dpp
								from mst.td_supp_per_brg a
								join mst.tm_supplier b on (b.kd_supplier = a.kd_supplier) where a.kd_produk='".$kd_produk."'
								and a.konsinyasi is false");
	}
}
?>