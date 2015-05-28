<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Barang_per_lokasi_model extends MY_Model {
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function __construct(){
		parent::__construct();
	}	

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function insert_row($data = NULL){
		$result = $this->db->insert('mst.t_produk_lokasi',$data);
                //print_r($result);
                return $result ;
	}	
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($kd_produk = '', $datau = NULL){
//		$this->db->where('kd_produk',$kd_produk);
//		$result = $this->db->update('mst.t_produk_lokasi',$datau);
//                
////                print_r($this->db->last_query());
//                return $result;
            
            $sqlCount = "SELECT * FROM mst.t_produk_lokasi 
                            WHERE kd_produk = '". $kd_produk ."' 
                            AND kd_lokasi = '". $datau['kd_lokasi'] ."' 
                            AND kd_blok = '". $datau['kd_blok'] ."' 
                            AND kd_sub_blok = '". $datau['kd_sub_blok'] ."'";
            
            
            $query = $this->db->query($sqlCount);
            $sqlReset = "update mst.t_produk_lokasi set flag_default = 0 where kd_produk = '". $kd_produk ."'";
            $this->db->query($sqlReset);
            $sqlUpdIns = "";
            if($query->num_rows() > 0) {
                $sqlUpdIns = "update mst.t_produk_lokasi set flag_default = 1 WHERE kd_produk = '". $kd_produk ."' 
                            AND kd_lokasi = '". $datau['kd_lokasi'] ."' 
                            AND kd_blok = '". $datau['kd_blok'] ."' 
                            AND kd_sub_blok = '". $datau['kd_sub_blok'] ."'";
            } else {
                $sqlUpdIns = "insert into mst.t_produk_lokasi(kd_produk, kd_lokasi, kd_blok, kd_sub_blok, flag_default) values ('". $kd_produk ."', '". $datau['kd_lokasi'] ."', '". $datau['kd_blok'] ."','". $datau['kd_sub_blok'] ."', 1)";
            }
            $result = $this->db->query($sqlUpdIns);
            
            return $result;
	}
	
	public function insert_row_history($kd_produk = '', $no_bukti = '', $tanggal = ''){
		$sql = "INSERT INTO mst.t_produk_lokasi_history
				SELECT '".$no_bukti." ', '".$tanggal."',* FROM mst.t_produk_lokasi
				WHERE kd_produk = '".$kd_produk."'";
				
		return $this->db->query($sql);
	}
	
	public function search_produk_by_kategori( $kd_kategori1 = "", $kd_kategori2 = "", $kd_kategori3 = "", $kd_kategori4 = "", $search = ''){
		$where = '';
		
		if ($search != ''){
			$where .= " AND ((lower(nama_produk) LIKE '%" . strtolower($search) . "%') OR (lower(kd_produk_lama) LIKE '%" . strtolower($search) . "%') OR (lower(b.kd_produk) LIKE '%" . strtolower($search) . "%'))";
		}
		if ($kd_kategori1 != ''){
			$where .= " AND b.kd_kategori1 = '$kd_kategori1' ";
		}
		
		if ($kd_kategori2 != ''){
			$where .= " AND b.kd_kategori2 = '$kd_kategori2' ";
		}
		
		if ($kd_kategori3 != ''){
			$where .= " AND b.kd_kategori3 = '$kd_kategori3' ";
		}
		
		if ($kd_kategori4 != ''){
			$where .= " AND b.kd_kategori4 = '$kd_kategori4' ";
		}
		
		$sql = "SELECT b.*, nm_satuan 
					FROM mst.t_produk b 
					JOIN mst.t_satuan c 
						ON c.kd_satuan = b.kd_satuan 
					JOIN mst.t_kategori1 d
						ON b.kd_kategori1 = d.kd_kategori1
					JOIN mst.t_kategori2 e
						ON b.kd_kategori2 = e.kd_kategori2 
						AND b.kd_kategori1 = e.kd_kategori1
					JOIN mst.t_kategori3 f
						ON b.kd_kategori3 = f.kd_kategori3
						AND b.kd_kategori2 = f.kd_kategori2
						AND b.kd_kategori1 = f.kd_kategori1
					JOIN mst.t_kategori4 g
						ON b.kd_kategori4 = g.kd_kategori4
						AND b.kd_kategori3 = g.kd_kategori3
						AND b.kd_kategori2 = g.kd_kategori2
						AND b.kd_kategori1 = g.kd_kategori1
					WHERE 1=1 ".$where;
		$query = $this->db->query($sql);
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}

        return $rows;
	}
	
	public function get_detail($kd_produk = '',$peruntukan ='', $search = ''){
		if($search!=''){
			$this->db->where("((lower(nama_produk) LIKE '%" . $search . "%') OR (a.kd_produk LIKE '%" . $search . "%'))", NULL);
		}
                if($peruntukan == '1' || $peruntukan == '0'){
                    $this->db->where("a.kd_peruntukan","$peruntukan");
                }
		$this->db->select("a.kd_produk, d.kd_lokasi, d.kd_blok, d.kd_sub_blok, nama_produk, nama_lokasi, nama_blok, nama_sub_blok, keterangan, 
							CASE WHEN a.kd_peruntukan = 0 THEN 'Supermarket' ELSE 'Distribusi' END kd_peruntukan
							", FALSE);
		$this->db->join("mst.t_lokasi b","b.kd_lokasi = a.kd_lokasi");
		$this->db->join("mst.t_blok c","c.kd_blok = a.kd_blok AND c.kd_lokasi = a.kd_lokasi");
		$this->db->join("mst.t_sub_blok d","d.kd_sub_blok = a.kd_sub_blok AND d.kd_blok = a.kd_blok AND d.kd_lokasi = a.kd_lokasi");
		$this->db->join("mst.t_produk e","e.kd_produk = a.kd_produk");
		$this->db->where("a.kd_produk",$kd_produk);
		$query = $this->db->get("mst.t_produk_lokasi a");
		$rows = array();
		if($query->num_rows() > 0){
			$rows = $query->result();
		}
		$results = '{success:true,data:'.json_encode($rows).'}';
		
		return $results;
	}
	
	public function get_row($kd_produk = '', $kd_lokasi = '', $kd_blok = '', $kd_sub_blok = ''){
		$this->db->select("a.kd_produk, nama_produk, nama_lokasi, nama_blok, nama_sub_blok");
		$this->db->join("mst.t_lokasi b","a.kd_lokasi = b.kd_lokasi");
		$this->db->join("mst.t_blok c","a.kd_blok = c.kd_blok AND a.kd_lokasi = c.kd_lokasi");
		$this->db->join("mst.t_sub_blok d","a.kd_sub_blok = d.kd_sub_blok AND a.kd_blok = d.kd_blok AND a.kd_lokasi = d.kd_lokasi");
		$this->db->join("mst.t_produk e","a.kd_produk = e.kd_produk");
		$this->db->where("a.kd_produk",$kd_produk);
		$this->db->where("a.kd_lokasi",$kd_lokasi);
		$this->db->where("a.kd_blok",$kd_blok);
		$this->db->where("a.kd_sub_blok",$kd_sub_blok);
		
		$query = $this->db->get("mst.t_produk_lokasi a");

		$row = '';
        if ($query->num_rows() != 0) {
            $row = $query->row();
			
        }
		$results =  '{"success":true,"data":'.json_encode($row).'}';
		return $results;
	}
	
	public function delete_row($kd_produk = NULL, $kd_lokasi = NULL, $kd_blok = NULL, $kd_sub_blok = NULL){
		$this->db->where("kd_produk",$kd_produk);
		$this->db->where("kd_lokasi",$kd_lokasi);
		$this->db->where("kd_blok",$kd_blok);
		$this->db->where("kd_sub_blok",$kd_sub_blok);
		
		return $this->db->delete('mst.t_produk_lokasi');
	}
        
        public function search_all_lokasi($search = "") {
            $sql_search = "";
            if ($search != "") {
                $sql_search .= " and (lower(kd_lokasi) LIKE '%" . strtolower($search) . "%' )";
            }
            
            $sql1 = "select b.kd_lokasi, c.kd_blok, d.kd_sub_blok, b.nama_lokasi || ' - ' || c.nama_blok || ' - ' || d.nama_sub_blok nama_lokasi
                    from mst.t_lokasi b, mst.t_blok c, mst.t_sub_blok d
                    where c.kd_lokasi = b.kd_lokasi order by kd_lokasi desc";

            $query = $this->db->query($sql1);

            $rows = array();
            if ($query->num_rows() > 0) {
                $rows = $query->result();
            }

            $results = '{success:true,data:' . json_encode($rows) . '}';
            return $results;
        }
        
        public function search_lokasi($search = "", $kd_produk = "") {
            $sql_search = "";
            if ($search != "") {
                $sql_search .= " and (lower(kd_lokasi) LIKE '%" . strtolower($search) . "%' )";
            }
            
            if($kd_produk != "") {
                $sql_search .= " and a.kd_produk = '". $kd_produk ."'";
            }

            $sql1 = "select b.kd_lokasi, c.kd_blok, d.kd_sub_blok, b.nama_lokasi || ' - ' || c.nama_blok || ' - ' || d.nama_sub_blok nama_lokasi,a.flag_default
                    from mst.t_produk_lokasi a, mst.t_lokasi b, mst.t_blok c, mst.t_sub_blok d
                    where 1=1 ". $sql_search ." 
                    and a.kd_lokasi = b.kd_lokasi
                    and a.kd_blok = c.kd_blok
                    and b.kd_lokasi = c.kd_lokasi
                    and a.kd_sub_blok = d.kd_sub_blok
                    and b.kd_lokasi = d.kd_lokasi
                    and c.kd_blok = d.kd_blok order by kd_lokasi desc";

            $query = $this->db->query($sql1);
            
            //print_r($sql1);
            $rows = array();
            if ($query->num_rows() > 0) {
                $rows = $query->result();
            }

            $results = '{success:true,data:' . json_encode($rows) . '}';
            return $results;
        }

}
