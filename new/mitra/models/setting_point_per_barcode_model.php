<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_point_per_barcode_model extends MY_Model {
	
	public function __construct(){
		parent::__construct();
	}	
	
	public function insert_row($data = NULL){
		return $this->db->insert('mst.t_point_setting   ',$data);
	}	
	
        public function update_row($updated_data = NULL,$kd_point_setting = '', $kd_produk = '', $tgl_awal ='',$tgl_akhir=''){
            $this->db->where("kd_produk",$kd_produk);
            $this->db->where("kd_point_setting",$kd_point_setting);
            $return = $this->db->update('mst.t_point_setting',$updated_data);
            //print_r($this->db->last_query());
            return $this->db->last_query();
	}
	
	
	public function get_row($kd_kategori1 = '', $kd_kategori2 = '', $kd_kategori3 = '',$kd_kategori4 = ''){
		$this->db->select("*");
		$this->db->where("kd_kategori1",$kd_kategori1);
		$this->db->where("kd_kategori2",$kd_kategori2);
		$this->db->where("kd_kategori3",$kd_kategori3);
		$this->db->where("kd_kategori4",$kd_kategori4);
		$query = $this->db->get("mst.t_point_setting");

		$row = '';
        if ($query->num_rows() != 0) {
            $row = $query->row();

        }

//        return $this->db->last_query();
        return $row;
	}
	

    public function search_kategori($kd_kategori1 = "", $kd_kategori2 = "", $kd_kategori3 = "", $kd_kategori4 = "", $search = ''){
        $where = '';

        if ($search != ''){
            $where .= " AND ((lower(a.nama_produk) LIKE '%" . strtolower($search) . "%') OR (lower(a.kd_produk_lama) LIKE '%" . strtolower($search) . "%') OR (lower(a.kd_produk) LIKE '%" . strtolower($search) . "%'))";
        }
        if ($kd_kategori1 != ''){
            $where .= " AND a.kd_kategori1 = '$kd_kategori1' ";
        }

        if ($kd_kategori2 != ''){
            $where .= " AND a.kd_kategori2 = '$kd_kategori2' ";
        }

        if ($kd_kategori3 != ''){
            $where .= " AND a.kd_kategori3 = '$kd_kategori3' ";
        }

        if ($kd_kategori4 != ''){
            $where .= " AND a.kd_kategori4 = '$kd_kategori4' ";
        }
       

        $sql = "SELECT
                a.kd_produk
                , a.kd_produk_lama
                , a.nama_produk
                , nm_satuan
                , b.disk_persen_kons1
                , b.disk_persen_kons2
                , b.disk_persen_kons3
                , b.disk_persen_kons4
                , b.disk_amt_kons1
                , b.disk_amt_kons2
                , b.disk_amt_kons3
                , b.disk_amt_kons4
                , b.disk_amt_kons5
                , h.kd_point_setting
                , h.tgl_awal
                , h.tgl_akhir
                , h.point
                FROM mst.t_produk a
                JOIN mst.t_diskon_sales b
                    ON b.kd_produk = a.kd_produk
                JOIN mst.t_satuan c
                    ON c.kd_satuan = a.kd_satuan
                JOIN mst.t_kategori1 d
                    ON a.kd_kategori1 = d.kd_kategori1
                JOIN mst.t_kategori2 e
                    ON a.kd_kategori2 = e.kd_kategori2
                    AND a.kd_kategori1 = e.kd_kategori1
                JOIN mst.t_kategori3 f
                    ON a.kd_kategori3 = f.kd_kategori3
                    AND a.kd_kategori2 = f.kd_kategori2
                    AND a.kd_kategori1 = f.kd_kategori1
                JOIN mst.t_kategori4 g
                    ON a.kd_kategori4 = g.kd_kategori4
                    AND a.kd_kategori3 = g.kd_kategori3
                    AND a.kd_kategori2 = g.kd_kategori2
                    AND a.kd_kategori1 = g.kd_kategori1
                LEFT JOIN mst.t_point_setting h
                    ON a.kd_produk = h.kd_produk
                    WHERE 1=1 $where"
                ;
        $query = $this->db->query($sql);
        //print_r($this->db->last_query());
        $rows = array();
        if($query->num_rows() > 0){
            $rows = $query->result();
        }

        return $rows;
    }
    public function select_data($kd_produk ="",$tgl_awal = "",$tgl_akhir = "",$kd_point_setting =""){
		$where ="";
                $point_setting ="";
                if ($kd_point_setting != ""){
                    $point_setting = " AND kd_point_setting not in ('$kd_point_setting')";
                }
                if ($kd_produk != ""){
                    $where = " AND kd_produk = '$kd_produk'";
                }
                $sql = "select * from mst.t_point_setting
                          where tgl_awal <= '$tgl_awal' and tgl_akhir >= '$tgl_awal'
                          $where $point_setting";
		
                $query = $this->db->query($sql);
                //print_r($this->db->last_query());exit;
                return $query->result();
	}
    public function select_data_end($kd_produk ="",$tgl_awal = "",$tgl_akhir = "",$kd_point_setting =""){
		$where ="";
                $point_setting ="";
                if ($kd_point_setting != ""){
                    $point_setting = " AND kd_point_setting not in ('$kd_point_setting')";
                }
                if ($kd_produk != ""){
                    $where = " AND kd_produk = '$kd_produk'";
                }
		$sql = "select * from mst.t_point_setting
                          where tgl_awal <= '$tgl_akhir' and tgl_akhir >= '$tgl_akhir'
                          $where $point_setting";
		
                $query = $this->db->query($sql);
                return $query->result();
	}
     public function select_data_point($kd_produk ="",$tgl_awal = "",$tgl_akhir = "",$kd_point_setting =""){
		$where ="";
                $point_setting ="";
                if ($kd_point_setting != ""){
                    $point_setting = " AND kd_point_setting ='$kd_point_setting'";
                }
                if ($kd_produk != ""){
                    $where = " AND kd_produk = '$kd_produk'";
                }
		$sql = "select * from mst.t_point_setting
                          where tgl_awal = '$tgl_awal'
                          $where $point_setting";
		
                $query = $this->db->query($sql);
                return $query->result();
	}
     public function select_data_end_not($kd_produk ="",$tgl_awal = "",$tgl_akhir = "",$kd_point_setting =""){
		$where ="";
                $point_setting ="";
                if ($kd_point_setting != ""){
                    $point_setting = " AND kd_point_setting = '$kd_point_setting'";
                }
                if ($kd_produk != ""){
                    $where = " AND kd_produk = '$kd_produk'";
                }
		$sql = "select * from mst.t_point_setting
                          where tgl_awal <= '$tgl_akhir' and tgl_akhir >= '$tgl_akhir'
                          $where $point_setting";
		
                $query = $this->db->query($sql);
                return $query->result();
	}
     public function select_data_start_not($kd_produk ="",$tgl_awal = "",$tgl_akhir = "",$kd_point_setting =""){
		$where ="";
                $point_setting ="";
                if ($kd_point_setting != ""){
                    $point_setting = " AND kd_point_setting = '$kd_point_setting'";
                }
                if ($kd_produk != ""){
                    $where = " AND kd_produk = '$kd_produk'";
                }
		$sql = "select * from mst.t_point_setting
                          where tgl_awal <= '$tgl_awal' and tgl_akhir >= '$tgl_awal'
                          $where $point_setting";
		
                $query = $this->db->query($sql);
                return $query->result();
	}
     public function select_data_sama($kd_kategori1 = "",$kd_kategori2 = "",$kd_kategori3 = "",$kd_kategori4 = "",$tgl_awal = "",$tgl_akhir = ""){
		$sql = "select * from mst.t_point_setting
                          where kd_kategori1 ='$kd_kategori1' and kd_kategori2 ='$kd_kategori2'  
                          and kd_kategori3 ='$kd_kategori3' and kd_kategori4 ='$kd_kategori4'  
                          and tgl_awal = '$tgl_awal' and tgl_akhir = '$tgl_akhir'
                          ";
		
                $query = $this->db->query($sql);
                return $query->result();
	}
}
