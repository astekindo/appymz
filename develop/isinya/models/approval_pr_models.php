<?php

class approval_pr_models extends CI_Model {

    public $approval_pr = '';
    public function approval_pr_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select id_pr, no_pr, subject, created_by, to_char(created_date,'dd-mm-yyyy hh:mm:ss') created_date, 
							case when status = '0' then 'Pending' when status = '1' then 'Approved' when status = '2' then 'Create PO' 
							when status = '3' then 'Approved Buyer' when status = '4' then 'Not Approved' end status, 
							status sts FROM mst.tt_purchase_request order by no_pr";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->approval_pr.='<tr id="' . $row->no_pr . '" class="edit_tr gradeX">';
                $this->approval_pr.='<td align="center">' . $no . '</td><td align="center">' . $row->no_pr . '</td><td align="center">' . $row->subject . '</td><td align="center">' . $row->created_date . '</td>
									<td align="center">' . $row->created_by . '</td><td align="center">' . $row->status . '</td>
									<td align="center"  width="8%">
									<a href="approval_pr/daftar_produkpr_edit/'. $row->id_pr .'" class="cblsprodukpr" />
									<input type="button" style="float: center;margin-top:-10px;margin-right:5px;" name="detailpr" id="detailpr" value="Detail" class="buttonM bBlue" />
									</a>
								   </td>';
                $this->approval_pr.='</tr>';
            }
        } else {
            $this->approval_pr = '';
        }
        return $this->approval_pr;
	}

    function approval_pr_data($offset,$limit) {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = $this->db->query ("select id_pr, no_pr, subject, created_by, to_char(created_date,'dd-mm-yyyy hh:mm:ss') created_date, 
					 case when status = '0' then 'Pending' when status = '1' then 'Approved' when status = '2' then 'Create PO' 
					 when status = '3' then 'Approved Buyer' when status = '4' then 'Not Approved' end status, 
					 status sts FROM mst.tt_purchase_request order by no_pr limit $offset OFFSET $limit");

        return $sql1;
	}
	}
	function tot_hal()
	{
		$q = $this->db->query("select id_pr, no_pr, subject, created_by, to_char(created_date,'dd-mm-yyyy hh:mm:ss') created_date, 
					 case when status = '0' then 'Pending' when status = '1' then 'Approved' when status = '2' then 'Create PO' 
					 when status = '3' then 'Approved Buyer' when status = '4' then 'Not Approved' end status, 
					 status sts FROM mst.tt_purchase_request");
		return $q;
	}

    public $listbarang_pr = '';
	
    public function listbarang_pr_content($no_pr) {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select a.no_pr, a.kd_produk, b.qty_oh, a.qty, a.thn_reg, b.nama_produk 
					from mst.tt_dtl_purchase_request a left join mst.tm_produk b 
					on a.kd_produk=b.kd_produk where a.no_pr='$no_pr'";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->listbarang_pr.='<tr class="gradeX">';
                $this->listbarang_pr.='<td align="center">' . $no . '</td><td align="center">' . $row->kd_produk . '</td><td align="center">' . $row->nama_produk . '</td>
									<td align="center">' . str_pad($row->thn_reg,4,"20",STR_PAD_LEFT) . '</td><td align="center">' . $row->qty_oh . '</td>
									<td align="center"><input type="text" value="' . $row->qty . '" class="input-read-only" style="width:40px;align:right;" name="qty" id="qty" readonly="readonly" /></td>';
                $this->listbarang_pr.='<td align="center"><div class="grid9 on_off" onclick="bolehUbah();"><input type="checkbox" id="bapprove" name="bapprove" /></div></td></tr>';
            }
        } else {
            $this->listbarang_pr = '';
        }
        return $this->listbarang_pr;
	}
	

    function getData($id)
	{
		$sql1 = "select id_approval_pr, kd_approval_pr, nmapproval_pr, alamat_rumah, telepon, hp, jenis, 
						to_char(sdtgl,'dd/mm/yyyy') sdtgl, to_char(tgljoin,'dd/mm/yyyy') tgljoin, to_char(tgllahir,'dd/mm/yyyy') tgllahir, 
						idno, status, tmplahir, agama, kelamin, kelurahan, kecamatan, kota, kodepos, fax, email, profesi, 
						nmpersh, alamat_kantor, teleponk, faxk 
				from mst.tm_approval_pr where aktif=true and id_approval_pr='$id'";
        $query = $this->db->query($sql1);
		return $query->result_array();
	}
	
	/*function get_records()
	{
		$query = $this->db->get('approval_pr');
		return $query->result_array();
	}*/
	function get_last_records()
	{
		$query = $this->db->query('select kd_approval_pr from mst.tm_approval_pr where kd_approval_pr = (select max(kd_approval_pr) from mst.tm_approval_pr)');
                $return_value = "";
                foreach ($query->result() as $row) {
                    $return_value = $row->kd_approval_pr;
                }
		return $return_value;
	}
        
	function add_record($data) 
	{
		$this->db->insert('mst.tm_approval_pr', $data);
		return;
	}
	
	function update_record($data,$id) 
	{
		$this->db->where('id_approval_pr', $id);
		$this->db->update('mst.tm_approval_pr', $data);
	}
	
	function delete_row($id)
	{
		$this->db->where('kd_approval_pr', $this->uri->segment(3));
		$this->db->delete('mst.tm_approval_pr');
	}
	
}
?>