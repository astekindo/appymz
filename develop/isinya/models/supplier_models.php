<?php

class Supplier_models extends CI_Model {

    public $supplier = '';

    public function supplier_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select id_supplier, kd_supplier, nama_supplier, alias_supplier, pic, alamat, telpon, fax, email, npwp,
							CASE WHEN pkp='1' THEN 'Ya' ELSE 'Tidak' END pkp 
					from mst.tm_supplier where aktif=true";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->supplier.='<tr class="gradeX">';
                $this->supplier.='<td align="center">' . $no . '</td><td align="center">' . $row->kd_supplier . '</td><td>' . $row->nama_supplier .'('.$row->alias_supplier.') </td><td>' . $row->pic . '</td>
									<td>' . $row->alamat . '</td><td>' . $row->telpon . '</td><td>' . $row->fax . '</td><td>' . $row->email . '</td><td>' . $row->pkp . '</td><td>' . $row->npwp . '</td>
									<td align="center"><a href="supplier/form/'. $row->id_supplier .'"  title="Edit" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe1db;"></span></a>
									   <a href="#" title="Delete" class="tablectrl_small bDefault tipS" onClick="confirmationDel('.$row->id_supplier.',\''.base_url().'supplier/delete\');"><span class="iconb" data-icon="&#xe136;"></a>
								   </td>';
                $this->supplier.='</tr>';
            }
        } else {
            $this->supplier = '';
        }
        return $this->supplier;
	}

	function getData($id_supplier)
	{
		$sql1 = "select id_supplier, kd_supplier, nama_supplier, alias_supplier, pic, alamat, telpon, fax, email, npwp, 
						CASE WHEN pkp='1' THEN 'checked' ELSE 'false' END pkp,CASE WHEN status='1' THEN 'checked' ELSE 'false' END status
				from mst.tm_supplier where aktif=true and id_supplier='$id_supplier'";
		$query = $this->db->query($sql1);
		//$query = $this->db->get_where('mst.tm_supplier',array(/**'status'=>'0',*/'id_supplier'=>$id_supplier));
		return $query->result_array();
	}
	
        
	function get_last_records()
	{
		$query = $this->db->query("SELECT to_number(kd_supplier,'99') kd_supplier FROM mst.tm_supplier 
									WHERE kd_supplier = (SELECT MAX(kd_supplier) FROM mst.tm_supplier)");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = $row->kd_supplier;
                }
                return $return_value;
	}
	
	function add_record($data) 
	{
		$this->db->insert('mst.tm_supplier', $data);
		return;
	}
	
	function update_record($data,$id_supplier) 
	{
		$this->db->where('id_supplier',$id_supplier);
		$this->db->update('mst.tm_supplier', $data);
	}
	
	function delete_row()
	{
		$this->db->where('id_supplier', $this->uri->segment(3));
		$this->db->delete('mst.tm_supplier');
	}
	
}
?>