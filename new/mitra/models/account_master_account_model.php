<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Account_master_account_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_kd_akun() {
        $this->db->select('kd_akun');
        $query = $this->db->get("acc.t_akun");

        $rows = array();
        $total = 0;
        if ($query->num_rows() > 0) {
            $total=$query->num_rows();
            $rows = $query->result();
        }
		
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }
	
	public function select_akun($where = ""){
		$this->db->where('kd_akun',$where);
		$query = $this->db->get('acc.t_akun');
		
		$results = FALSE;
		
		if($query->num_rows() > 0 ){
			$results = TRUE;
		}
		
		return $results;
		
	}
        
        
	public function get_rows($search = "", $offset, $length) {
            
//        $this->db->select("ta.kd_akun, ta.parent_kd_akun, ta.nama, ta.deskripsi, ta.dk, ta.aktif, tt.type_akun, ta.created_by, ta.created_date, ta.updated_by, ta.updated_date, ta.labarugi, ta.neraca, ta.header_status,CASE WHEN ta.aktif ='1' THEN 1 ELSE 0 END aktif,
//		CASE WHEN ta.dk ='d' THEN 'Debet' ELSE 'Kredit' END dk,
//		CASE WHEN ta.labarugi is true THEN 1 ELSE 0 END labarugi,
//            CASE WHEN ta.neraca is true THEN 1 ELSE 0 END neraca,
//            CASE WHEN ta.header_status is true THEN 1 ELSE 0 END header_status", FALSE);
//        if ($search != "") {
//            $sql_search = "(lower(ta.nama) LIKE '%" . strtolower($search) . "%')";
//            $this->db->where($sql_search, NULL);
//        }
        if ($search != "") {
            $sql_search = "and (lower(ta.nama) LIKE '%" . strtolower($search) . "%')";
//            $this->db->where($sql_search, NULL);
        }
        $sql1="SELECT 
  ta.kd_akun, 
  ta.parent_kd_akun, 
  ta.nama, 
  ta.deskripsi, 
  CASE WHEN ta.dk ='D' THEN 'Debet' ELSE 'Kredit' END dk, 
  CASE WHEN ta.aktif ='1' THEN 1 ELSE 0 END aktif, 
  tt.type_akun, 
  ta.created_by, 
  ta.created_date, 
  ta.updated_by, 
  ta.updated_date, 
  CASE WHEN ta.labarugi ='1' THEN 1 ELSE 0 END labarugi,
 CASE WHEN ta.neraca ='1' THEN 1 ELSE 0 END neraca,
 CASE WHEN ta.header_status ='1' THEN 1 ELSE 0 END header_status
FROM 
  acc.t_akun ta, 
  acc.t_typeakun tt
WHERE 
  ta.type_akun = tt.id $sql_search
ORDER BY ta.kd_akun
LIMIT $length OFFSET $offset";
        
//        $this->db->join("acc.t_typeakun tt","ta.kd_akun=tt.id");
//        $this->db->where('ta.aktif', '1');
//        $this->db->order_by("ta.kd_akun", "desc");
//        $query = $this->db->get("acc.t_akun ta", $length, $offset);
        $query = $this->db->query($sql1);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        if ($search != "") {
            $sql_search = "(lower(nama) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $query = $this->db->get("acc.t_akun");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';
        return $results;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_row($id = NULL) {
        $this->db->select("*,CASE WHEN labarugi is true THEN 1 ELSE 0 END labarugi,
            CASE WHEN neraca is true THEN 1 ELSE 0 END neraca,
            CASE WHEN header_status is true THEN 1 ELSE 0 END header_status
            ", FALSE);
        $this->db->where("kd_akun", $id);
        $query = $this->db->get('acc.t_akun');

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":' . json_encode($row) . '}';
        }
    }

    public function insert_row($data = NULL) {
        return $this->db->insert('acc.t_akun', $data);
		// print_r($this->db->last_query());
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row($id = NULL, $data = NULL) {
        $this->db->where('kd_akun', $id);
        return $this->db->update('acc.t_akun', $data);
		// print_r($this->db->last_query());
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function delete_row($id = NULL) {
        $data = array(
            'aktif' => '0'
        );
        $this->db->where('kd_akun', $id);
        return $this->db->update('acc.t_akun', $data);
    }
    
    
    public function get_akun_twin($search = "", $offset, $length) {
        $sql_search="" ;
        if ($search != ""){
            $sql_search="(kd_akun like '%".  strtolower($search)."%' or lower(nama) like '%".  strtolower($search)."%')" ;
        }
        $this->db->select("kd_akun,nama,dk");
        $this->db->where("aktif","1");
        $this->db->where("header_status is false");
        if ($sql_search!=""){
            $this->db->where($sql_search);       
            }
        
        $query = $this->db->get("acc.t_akun", $length, $offset);

        $rows = array();
        
        if ($query->num_rows() > 0) {            
            $rows = $query->result();
        }
        
        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        $this->db->where("aktif","1");
        $this->db->where("header_status is false"); 
        if ($sql_search!=""){
            $this->db->where($sql_search);       
            }
        $query = $this->db->get("acc.t_akun");
        
        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;            
        }
        
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

}

?>
