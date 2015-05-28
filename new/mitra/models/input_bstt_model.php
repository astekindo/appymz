<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Input_bstt_model extends MY_Model {
	
	public function __construct(){
		parent::__construct();
	}
	
	
	
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
    public function search_no_faktur($kd_pelanggan = "",$search = "", $offset, $length) {
                $sql_search = " ";
                if ($search != "") {
                    $sql_search = "AND (lower(no_faktur) LIKE '%" . strtolower($search) . "%' )";
                }

                $sql1 = "select * from sales.t_faktur_jual where kd_pelanggan = '$kd_pelanggan' " . $sql_search . "  order by no_faktur desc
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
    public function search_bstt($kd_colector = "",$search = "", $offset, $length) {
                $sql_search = " ";
                if ($search != "") {
                    $sql_search = "AND (lower(no_bstt) LIKE '%" . strtolower($search) . "%' )";
                }

                $sql1 = "select * from sales.t_bstt where kd_collector ='$kd_colector'
                         " . $sql_search . "  order by no_bstt desc
                        limit " . $length . " offset " . $offset;

                $query = $this->db->query($sql1);
                //print_r($query);
                $rows = array();
                if ($query->num_rows() > 0) {
                    $rows = $query->result();
                }

                $this->db->flush_cache();
                $sql2 = "select count(*) as total 
                                 from sales.t_bstt where kd_collector ='$kd_colector'";

                $query = $this->db->query($sql2);

                $total = 0;
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $total = $row->total;
                }

                $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

                return $results;
    }
    public function get_rows($no_bstt = "",$search = "", $offset, $length) {
                
                $sql1 = "select a.*,b.tanggal,c.nama_pelanggan 
                        from sales.t_bstt_detail a 
                        join sales.t_bstt b on a.no_bstt = b.no_bstt 
                        join mst.t_pelanggan_dist c on a.kd_pelanggan = c.kd_pelanggan 
                        where a.no_bstt ='$no_bstt' order by a.no_bstt
                         limit " . $length . " offset " . $offset;

                $query = $this->db->query($sql1);
                //print_r($query);
                $rows = array();
                if ($query->num_rows() > 0) {
                    $rows = $query->result();
                }

                $this->db->flush_cache();
                $sql2 = "select count(*) as total 
                         from sales.t_bstt_detail a 
                        join sales.t_bstt b on a.no_bstt = b.no_bstt 
                        join mst.t_pelanggan_dist c on a.kd_pelanggan = c.kd_pelanggan 
                        where a.no_bstt ='$no_bstt'";

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