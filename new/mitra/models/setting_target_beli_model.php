<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_target_beli_model extends MY_Model {
	
	public function __construct(){
		parent::__construct();
	}	
	
	public function insert_row($data = NULL){
		return $this->db->insert('mst.t_setting_target_beli',$data);
	}	
	
        public function update_row($updated_data = NULL, $bulan = '',$tahun = '', $kd_kategori1 = '', $kd_kategori2 = '', $kd_kategori3 = '',$kd_kategori4 = ''){
            $this->db->where("kd_kategori1",$kd_kategori1);
            $this->db->where("kd_kategori2",$kd_kategori2);
            $this->db->where("kd_kategori3",$kd_kategori3);
            $this->db->where("kd_kategori4",$kd_kategori4);
            $this->db->where("bulan",$bulan);
            $this->db->where("tahun",$tahun);
            $return = $this->db->update('mst.t_setting_target_beli',$updated_data);
            return $this->db->last_query();
	}
	
	
	public function get_row($bulan = '',$tahun = '', $kd_kategori1 = '', $kd_kategori2 = '', $kd_kategori3 = '',$kd_kategori4 = ''){
		$this->db->select("*");
		$this->db->where("kd_kategori1",$kd_kategori1);
		$this->db->where("kd_kategori2",$kd_kategori2);
		$this->db->where("kd_kategori3",$kd_kategori3);
		$this->db->where("kd_kategori4",$kd_kategori4);
		$this->db->where("bulan",$bulan);
                $this->db->where("tahun",$tahun);
		$query = $this->db->get("mst.t_setting_target_beli");

		$row = '';
        if ($query->num_rows() != 0) {
            $row = $query->row();

        }

//        return $this->db->last_query();
        return $row;
	}
	

    public function search_kategori($bulan = "",$tahun = "", $kd_kategori1 = "", $kd_kategori2 = "", $kd_kategori3 = "", $kd_kategori4 = "", $search = ''){
        $where = '';

        if ($search != ''){
            $where .= " AND ((lower(a.nama_produk) LIKE '%" . strtolower($search) . "%') OR (lower(a.kd_produk_lama) LIKE '%" . strtolower($search) . "%') OR (lower(a.kd_produk) LIKE '%" . strtolower($search) . "%'))";
        }
        if ($kd_kategori1 != ''){
            $where .= " AND a.kd_kategori1 = '$kd_kategori1' ";
        }

        if ($kd_kategori2 != ''){
            $where .= " AND b.kd_kategori2 = '$kd_kategori2' ";
        }

        if ($kd_kategori3 != ''){
            $where .= " AND c.kd_kategori3 = '$kd_kategori3' ";
        }

        if ($kd_kategori4 != ''){
            $where .= " AND d.kd_kategori4 = '$kd_kategori4' ";
        }
//        if ($bulan != ''){
//            $where .= " AND e.bulan = '$bulan' ";
//        }
//        if ($tahun != ''){
//            $where .= " AND e.tahun = '$tahun' ";
//        }

        $sql = "select a.nama_kategori1,a.kd_kategori1,b.nama_kategori2,b.kd_kategori2,c.nama_kategori3,c.kd_kategori3,d.nama_kategori4,d.kd_kategori4,e.target_qty,e.target_rupiah
                from mst.t_kategori1 a
                left join mst.t_kategori2 b on b.kd_kategori1 = a.kd_kategori1
                left join mst.t_kategori3 c on c.kd_kategori1 = a.kd_kategori1 and c.kd_kategori2 = b.kd_kategori2
                left join mst.t_kategori4 d on d.kd_kategori1 = a.kd_kategori1 and d.kd_kategori2 = b.kd_kategori2 and  d.kd_kategori3 = c.kd_kategori3
                left join  (select * from mst.t_setting_target_beli where bulan = '$bulan' and tahun = '$tahun')  e on e.kd_kategori1 = a.kd_kategori1 and e.kd_kategori2 = b.kd_kategori2 and e.kd_kategori3 = c.kd_kategori3 and e.kd_kategori4 = d.kd_kategori4
                where 1=1 $where";
        $query = $this->db->query($sql);
        //print_r($this->db->last_query());
        $rows = array();
        if($query->num_rows() > 0){
            $rows = $query->result();
        }

        return $rows;
    }
}
