<?php

class Supp_prod_models extends CI_Model {

    public $supp_prod = '';

    public function supp_prod_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select a.kd_supplier, b.nama_supplier
					from mst.td_supp_per_brg a 
					join mst.tm_supplier b on b.kd_supplier = a.kd_supplier and b.aktif = true
					where a.aktif=true
					group by a.kd_supplier, b.nama_supplier";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->supp_prod.=' <tr class="gradeX">';
                $this->supp_prod.=' <td align="center">' . $no . '</td>
									<td align="center">' . $row->kd_supplier . '</td>
									<td>' . $row->nama_supplier . '</td>
									<td align="center"  width="8%">
										<a href="supp_prod/detail_produk_supp/'. $row->kd_supplier .'" class="cblsupprod" />
										<input type="button" style="float: center;margin-top:-10px;margin-right:5px;" name="detailpr" id="detailpr" value="Detail" class="buttonM bRed" />
										</a>
									</td>
									<td align="center">
										<a href="supp_prod/form/'. $row->kd_supplier .'"  title="Edit" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe1db;"></span></a>
										<a href="#" title="Delete" class="tablectrl_small bDefault tipS" onClick="confirmationDel('.$row->kd_supplier.',\''.base_url().'supp_prod/hapussuppprod\');"><span class="iconb" data-icon="&#xe136;"></a>
								   </td>';
                $this->supp_prod.='</tr>';
            }
        } else {
            $this->supp_prod = '';
        }
        return $this->supp_prod;
	}

	function getData($id_supp_per_brg)
	{
		$sql1 = "select id_supp_per_brg, kd_supplier, nama_supplier, alias_supplier, alamat, telpon, npwp,
						CASE WHEN pkp='1' THEN 'checked' ELSE 'false' END pkp
				from mst.td_supp_per_brg where aktif=true and id_supp_per_brg='$id_supp_per_brg'";
		$query = $this->db->query($sql1);
		return $query->result_array();
	}
	
        
	function get_last_records()
	{
		$query = $this->db->query("SELECT to_number(kd_supplier,'99') kd_supplier FROM mst.td_supp_per_brg 
									WHERE kd_supplier = (SELECT MAX(kd_supplier) FROM mst.td_supp_per_brg)");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = $row->kd_supplier;
                }
                return $return_value;
	}
	
	function add_record($data) 
	{
		$this->db->insert('mst.td_supp_per_brg', $data);
		return;
	}
	
	function update_record($data,$id_supp_per_brg) 
	{
		$this->db->where('id_supp_per_brg',$id_supp_per_brg);
		$this->db->update('mst.td_supp_per_brg', $data);
	}
	
	function delete_row()
	{
		$this->db->where('id_supp_per_brg', $this->uri->segment(3));
		$this->db->delete('mst.td_supp_per_brg');
	}
	
	function supplier_data(){
		$sql= "SELECT kd_supplier, nama_supplier
		FROM mst.tm_supplier WHERE aktif is true";
		$q = $this->db->query($sql);
		$row[0] = '- Pilih Supplier -';
		foreach ($q->result() as $r)
		{
			$row[$r->kd_supplier]=$r->nama_supplier;
		}
		return $row;
	}
	
	function produk_data(){
		$sql= "SELECT kd_produk, nama_produk
		FROM mst.tm_produk WHERE aktif is true";
		$q = $this->db->query($sql);
		$row[0] = '- Pilih Produk -';
		foreach ($q->result() as $r)
		{
			$row[$r->kd_produk]=$r->nama_produk;
		}
		return $row;
	}
}
?>