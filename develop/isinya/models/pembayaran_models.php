<?php
class Pembayaran_models extends CI_Model {
	
	public $pembayaran = '';
	
	public function pembayaran_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "SELECT a.id_pembayaran, a.nm_pembayaran, a.charge, a.jenis, a.status_aktif
            FROM mst.tm_jns_pembayaran a
			where a.aktif is true";

            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->pembayaran.='<tr class="gradeX">';
                $this->pembayaran.='<td>'. $no .'</td>
									<td>'. $row->nm_pembayaran .'</td>
									<td>'. $row->charge .'</td>
									<td>'. $row->jenis .'</td>
									<td>'. $row->status_aktif .'</td>
									<td>
										<a href="'.base_url().'pembayaran/form/'.$row->id_pembayaran.'" class="tablectrl_small bDefault tipS" title="Edit"><span class="iconb" data-icon="&#xe1db;"></span></a>
										&nbsp;<a href="#" class="tablectrl_small bDefault tipS" title="Delete" onClick="confirmationDel('.$row->id_pembayaran.',\''.base_url().'pembayaran/delete\');"><span class="iconb" data-icon="&#xe136;"></span></a>
									</td>';
                $this->pembayaran.='</tr>';
            }
        } else {
            $this->pembayaran = '';
        }
        return $this->pembayaran;
	}
	
	function getData($id)
	{
		$query = $this->db->get_where('mst.tm_jns_pembayaran',array('aktif'=>'true','id_pembayaran'=>$id));
		return $query->result_array();
	}
	
        
	function get_last_records()
	{
		$query = $this->db->query('SELECT * FROM mst.tm_jns_pembayaran WHERE id_pembayaran = (SELECT MAX(id_pembayaran) FROM mst.tm_jns_pembayaran)');
		return $query->result_array();
	}
	
	function add_record($data) 
	{
		$this->db->insert('mst.tm_jns_pembayaran', $data);
		return;
	}
	
	function update_record($data,$id) 
	{
		$this->db->where('id_pembayaran',$id);
		$this->db->update('mst.tm_jns_pembayaran', $data);
	}
	
	function delete_row()
	{
		$this->db->where('id_pembayaran', $this->uri->segment(3));
		$this->db->delete('mst.tm_jns_pembayaran');
	}
	
}
?>
