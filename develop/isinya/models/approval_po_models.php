<?php

class approval_po_models extends CI_Model {

    public $approval_po = '';
    public function approval_po_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select a.id_po, a.no_po, a.no_pr, b.subject, a.jumlah, a.ppn, a.grand_total, a.masa_berlaku, 
							a.created_by, to_char(a.created_date,'dd-mm-yyyy hh:mm:ss') created_date, 
							case when a.approval = '0' then 'Pending' when a.approval = '1' then 'Approved' when a.approval = '2' then 'Create RO' 
							when a.approval = '3' then 'Approved Buyer' when a.approval = '4' then 'Not Approved' end status, 
							a.approval sts
						FROM mst.tt_purchase_order a
						JOIN mst.tt_purchase_request b on (b.no_pr = a.no_pr)
						order by a.no_po";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->approval_po.='<tr id="' . $row->no_po . '" class="edit_tr gradeX">';
                $this->approval_po.='<td align="center">' . $no . '</td>
									<td align="center">' . $row->no_po . '</td>
									<td align="center">' . $row->no_pr . '</td>
									<td align="center">' . $row->subject . '</td>
									<td align="center">' . $row->masa_berlaku . ' hari</td>
									<td align="center">' . $row->jumlah . '</td>
									<td align="center">' . $row->grand_total . '</td>
									<td align="center">' . $row->created_date . '</td>
									<td align="center">' . $row->created_by . '</td>
									<td align="center">' . $row->status . '</td>
									<td align="center"  width="8%">
									<a href="approval_po/daftar_produkpo_edit/'. $row->id_po .'" class="cblsupprod" />
									<input type="button" style="float: center;margin-top:-10px;margin-right:5px;" name="detailpr" id="detailpr" value="Detail" class="buttonM bRed" />
									</a>
								   </td>';
                $this->approval_po.='</tr>';
            }
        } else {
            $this->approval_po = '';
        }
        return $this->approval_po;
	}

    public $listbarang_po = '';
    public function listbarang_po_content($no_pr) {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select a.no_po, a.no_pr, a.kd_produk, a.kd_supplier, a.thn_reg, a.qty_beli, a.disk_persen_supp1, a.disk_persen_supp2,
							a.disk_persen_supp3, a.disk_persen_supp4, a.disk_amt_supp1, a.disk_amt_supp2, a.disk_amt_supp3, a.disk_amt_supp4, 
							a.hrg_supplier, a.dpp, a.approval, d.nm_satuan,b.nama_produk, c.approval app, b.qty_oh
							from mst.tt_dtl_purchase_order a 
							left join mst.tm_produk b on a.kd_produk=b.kd_produk 
							left join mst.tt_purchase_order c on a.no_pr=c.no_po 
							left join mst.tm_satuan d on b.id_satuan=d.id_satuan 
							where a.no_po='$no_po'";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->listbarang_po.='<tr class="gradeX">';
                $this->listbarang_po.='<td align="center">' . $no . '</td>
									<td align="center">' . $row->kd_produk . '</td>
									<td align="center">' . $row->nama_produk . '</td>
									<td align="center">' . str_pad($row->thn_reg,4,"20",STR_PAD_LEFT) . '</td>
									<td align="center">' . $row->qty_oh . '</td>
									<td align="center"><input type="text" value="' . $row->qty_beli . '" class="input-read-only" style="width:40px;align:right;" name="qty" id="qty" readonly="readonly" /></td>';
                $this->listbarang_po.='<td align="center"><div class="grid9 on_off" onclick="bolehUbah();"><input type="checkbox" id="bapprove" name="bapprove" /></div></td></tr>';
            }
        } else {
            $this->listbarang_po = '';
        }
        return $this->listbarang_po;
	}
	
}
?>