<?php

class Kategori2_models extends CI_Model {

    public function __construct() {
        $this->load->database();
        parent::__construct();
        $this->load->model('fungsi');
    }

    public $kategori2 = '';

    public function kategori2_content() {
        $user = $this->session->userdata('kodex');
        if (isset($user)) {
            $sql1 = "select e.id_kategori2, e.kd_kategori1, e.nama_kategori1, e.kd_kategori2, e.nama_kategori2, f.kd_kategori1 kategori from (select b.id_kategori2, b.kd_kategori1, a.nama_kategori1, b.kd_kategori2, b.nama_kategori2 from mst.tm_kategori1 a, mst.tm_kategori2 b where b.kd_kategori1=a.kd_kategori1 and b.aktif = true) e left outer join (select kd_kategori1,kd_kategori2 from mst.tm_produk group by kd_kategori1, kd_kategori2) f on e.kd_kategori1=f.kd_kategori1 and e.kd_kategori2=f.kd_kategori2 order by f.kd_kategori1, e.kd_kategori1, e.nama_kategori1, e.kd_kategori2, e.nama_kategori2";
            $query = $this->db->query($sql1);
			$no=0;
            foreach ($query->result() as $row) {
				$no=$no+1;
                $this->kategori2.='<tr class="gradeX">';
                $this->kategori2.='<td align="center">' . $no . '</td><td align="center">' . $row->kd_kategori1 .''.$row->kd_kategori2 . '</td>
									<td>' . $row->nama_kategori1.' - '.$row->nama_kategori2 . '</td>
									<td align="center">';
				if(!$row->kategori){
				$this->kategori2.='<a href="kategori2/form/'. $row->id_kategori2 .'"  title="Edit" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe1db;"></span></a><a href="#" title="Delete" class="tablectrl_small bDefault tipS" onClick="confirmationDel('.$row->id_kategori2.',\''.base_url().'kategori2/delete\');"><span class="iconb" data-icon="&#xe136;"></a>';
				}else{
				$this->kategori2.='<a href="kategori2/form/'. $row->id_kategori2 .'"  title="Edit" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe1db;"></span></a><a href=\'javascript:window.alert("Kategori sudah terisi");\' title="Delete" class="tablectrl_small bDefault tipS"><span class="iconb" data-icon="&#xe136;"></a>';
                }
				$this->kategori2.='</td></tr>';
            }
        } else {
            $this->kategori2 = '';
        }
        return $this->kategori2;
	}
		
	function getData($id_kategori2)
	{
		$query = $this->db->query("select b.id_kategori2, a.kd_kategori1, a.nama_kategori1, b.kd_kategori2, b.nama_kategori2 from mst.tm_kategori1 a, mst.tm_kategori2 b where b.kd_kategori1=a.kd_kategori1 and b.aktif = true and b.id_kategori2='$id_kategori2'");
		//$query = $this->db->get_where('kategori_2',array(/**'status'=>'0',*/'id_kategori2'=>$id_kategori2));
		return $query->result_array();
	}
	
	function get_last_records($kd_kategori1)
	{
		$query = $this->db->query("SELECT max(to_number(kd_kategori2,'99')) kd_kategori2 FROM mst.tm_kategori2 WHERE kd_kategori1 = '$kd_kategori1'");
		$return_value = "";
                foreach($query->result() as $row){
                    $return_value = $row->kd_kategori2;
                }
                return $return_value;
	}
	
	function add_record($data) 
	{
		$this->db->insert('mst.tm_kategori2', $data);
		return;
	}
	
	function update_record($datau,$id_kategori2) 
	{
		$this->db->where('id_kategori2', $id_kategori2);
		$this->db->update('mst.tm_kategori2', $datau);
	}
	
	function delete_row($id_kategori2)
	{
		$this->db->where('id_kategori2', $this->uri->segment(3));
		$this->db->delete('mst.tm_kategori2');
	}

	public function nama_kategori1(){
		$sql= "SELECT kd_kategori1, nama_kategori1
		FROM mst.tm_kategori1 WHERE aktif=true";
		$q = $this->db->query($sql);
		$row[0] = ' - ';
		foreach ($q->result() as $r)
		{
			$row[$r->kd_kategori1]=$r->nama_kategori1;
		}
		return $row;
	}
	
}
?>