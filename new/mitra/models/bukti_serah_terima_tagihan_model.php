<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bukti_serah_terima_tagihan_model extends MY_Model {
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	
        public function search_colector($search = "", $offset, $length) {
                $sql_search = " ";
                if ($search != "") {
                    $sql_search = "where (lower(kd_collector) LIKE '%" . strtolower($search) . "%' )";
                }

                $sql1 = "select * from mst.t_collection  " . $sql_search . "  order by kd_collector desc
                        limit " . $length . " offset " . $offset;

                $query = $this->db->query($sql1);
                //print_r($query);
                $rows = array();
                if ($query->num_rows() > 0) {
                    $rows = $query->result();
                }

                $this->db->flush_cache();
                $sql2 = "select count(*) as total 
                                from mst.t_collection";

                $query = $this->db->query($sql2);

                $total = 0;
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $total = $row->total;
                }

                $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

                return $results;
    }
     public function search_no_ttf($search = "", $offset, $length) {
                $sql_search = " ";
                if ($search != "") {
                    $sql_search = "where (lower(no_ttf) LIKE '%" . strtolower($search) . "%' )";
                }

                $sql1 = "select * from sales.t_ttf  " . $sql_search . "  order by no_ttf desc
                        limit " . $length . " offset " . $offset;

                $query = $this->db->query($sql1);
                //print_r($query);
                $rows = array();
                if ($query->num_rows() > 0) {
                    $rows = $query->result();
                }

                $this->db->flush_cache();
                $sql2 = "select count(*) as total 
                                from sales.t_ttf";

                $query = $this->db->query($sql2);

                $total = 0;
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $total = $row->total;
                }

                $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

                return $results;
    }
    public function search_no_faktur($kd_pelanggan = "",$search = "", $offset, $length) {
                $sql_search = " ";
                if ($search != "") {
                    $sql_search = "AND (lower(no_faktur) LIKE '%" . strtolower($search) . "%' )";
                }

                $sql1 = "select * 
                        from sales.t_faktur_jual 
                        where rp_kurang_bayar > 0
                        and no_faktur not in (select no_faktur from sales.t_ttf a, sales.t_ttf_detail b where a.no_ttf = b.no_ttf and status = 1)
                        and kd_pelanggan = '$kd_pelanggan' " . $sql_search . "  order by no_faktur desc
                        limit " . $length . " offset " . $offset;

                $query = $this->db->query($sql1);
                //print_r($query);
                $rows = array();
                if ($query->num_rows() > 0) {
                    $rows = $query->result();
                }

                $this->db->flush_cache();
                $sql2 = "select count(*) as total 
                         from sales.t_faktur_jual 
                        where rp_kurang_bayar > 0
                        and no_faktur not in (select no_faktur from sales.t_ttf a, sales.t_ttf_detail b where a.no_ttf = b.no_ttf and status = 1)
                        and kd_pelanggan = '$kd_pelanggan'";

                $query = $this->db->query($sql2);

                $total = 0;
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $total = $row->total;
                }

                $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

                return $results;
    }
    function search_faktur_by_ttf($no_ttf = "",$search = "", $offset, $length) {
                $sql_search = " ";
                if ($search != "") {
                    $sql_search = "AND (lower(no_ttf) LIKE '%" . strtolower($search) . "%' )";
                }
                if ($no_ttf != ''){
			$no_ttf_in_1 = '';
			$no_ttf = explode(';',$no_ttf);
			foreach ($no_ttf as $no_ttf_in){
				$no_ttf_in_1 = $no_ttf_in_1."'".$no_ttf_in."',";
			}
			$no_ttf = substr($no_ttf_in_1,0,-1);
		}
                $sql1 = "select a.*,b.kd_pelanggan,c.nama_pelanggan from sales.t_ttf_detail a
                        join sales.t_ttf b on a.no_ttf = b.no_ttf
                        join mst.t_pelanggan_dist c on c.kd_pelanggan = b.kd_pelanggan
                        where a.no_ttf in (".$no_ttf.")  " . $sql_search . "  order by no_faktur desc
                        limit " . $length . " offset " . $offset;

                $query = $this->db->query($sql1);
                //print_r($this->db->last_query());
                $rows = array();
                if ($query->num_rows() > 0) {
                    $rows = $query->result();
                }

                $this->db->flush_cache();
                $sql2 = "select count(*) as total 
                         from sales.t_ttf_detail a
                         join sales.t_ttf b on a.no_ttf = b.no_ttf
                         join mst.t_pelanggan_dist c on c.kd_pelanggan = b.kd_pelanggan
                         where a.no_ttf in (".$no_ttf.")";

                $query = $this->db->query($sql2);

                $total = 0;
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $total = $row->total;
                }

                $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

                return $results;
    }
    public function search_pelanggan($kd_colector = "",$search = "", $offset, $length) {
                $sql_search = " ";
                if ($search != "") {
                    $sql_search = "AND (lower(nama_pelanggan) LIKE '%" . strtolower($search) . "%' )";
                }

                $sql1 = "select a.*
                        from mst.t_pelanggan_dist a, mst.t_collection_area b, mst.t_collection c
                        where a.kd_area = b.kd_area
                        and b.kd_collector = c.kd_collector
                        and c.kd_collector = '$kd_colector' " . $sql_search . "  order by kd_pelanggan desc
                        limit " . $length . " offset " . $offset;

                $query = $this->db->query($sql1);
                //print_r($query);
                $rows = array();
                if ($query->num_rows() > 0) {
                    $rows = $query->result();
                }

                $this->db->flush_cache();
                $sql2 = "select count(*) as total 
                                from sales.t_faktur_jual where kd_pelanggan = '$kd_pelanggan'";

                $query = $this->db->query($sql2);

                $total = 0;
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $total = $row->total;
                }

                $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

                return $results;
    }
   public function insert_row($table = '', $data = NULL){
		$this->db->flush_cache();
		return $this->db->insert($table, $data);
		
		// print_r($this->db->last_query());
	}
   public function get_data_print($no_bstt = '') {
        $sql = "select 'BUKTI SERAH TERIMA TAGIHAN' title, a.*,b.nama_collector 
                from sales.t_bstt a, mst.t_collection b
                where a.no_bstt = '$no_bstt' and a.kd_collector = b.kd_collector";

        $query = $this->db->query($sql);
        if ($query->num_rows() == 0)
            return FALSE;

        $data['header'] = $query->row();

        $this->db->flush_cache();
        $sql_detail = "select a.*,b.nama_pelanggan,c.tgl_faktur, c.rp_faktur, c.tgl_jatuh_tempo
                from sales.t_bstt_detail a, mst.t_pelanggan_dist b, sales.t_faktur_jual c
                where no_bstt = '$no_bstt' and a.kd_pelanggan = b.kd_pelanggan
                and a.no_faktur = c.no_faktur
                ";
        $query_detail = $this->db->query($sql_detail);
       
        $data['detail'] = $query_detail->result();

        return $data;
    }
}