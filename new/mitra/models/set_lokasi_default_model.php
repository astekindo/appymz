<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Set_lokasi_default_model extends MY_Model {

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
		return $this->db->insert('mst.t_produk_lokasi',$data);
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row($kd_produk = '', $data_new = NULL, $data_old = NULL){
        if( $data_old != NULL) {
            $this->db->where('kd_produk',$kd_produk);
            if(array_key_exists('kd_lokasi', $data_old)) $this->db->where('kd_lokasi',$data_old['kd_lokasi']);
            if(array_key_exists('kd_blok', $data_old)) $this->db->where('kd_blok',$data_old['kd_blok']);
            if(array_key_exists('kd_sub_blok', $data_old)) $this->db->where('kd_sub_blok',$data_old['kd_sub_blok']);
            if(array_key_exists('kd_peruntukan', $data_old)) $this->db->where('kd_peruntukan',$data_old['kd_peruntukan']);
            $return = $this->db->update('mst.t_produk_lokasi',$data_new);
        } else {
            $return = $this->db->insert('mst.t_produk_lokasi',$data_new);
        }
		return $this->db->last_query();
	}

	public function insert_row_history($no_bukti, $tanggal,$flag_lokasi, $old_data){
        if(!isset($tanggal) || empty($tanggal)) $tanggal = date('Y-m-d H:i:s');
        $hist_data = array(
            'no_bukti'      => $no_bukti,
            'tanggal'         => $tanggal,
            'flag_lokasi'         => $flag_lokasi,
        );
        if(array_key_exists('kd_produk', $old_data)) {
            $hist_data['kd_produk'] = $old_data['kd_produk'];
            $hist_data['kd_lokasi'] = array_key_exists('kd_lokasi', $old_data) ? $old_data['kd_lokasi'] : null;
            $hist_data['kd_blok'] = array_key_exists('kd_blok', $old_data) ? $old_data['kd_blok'] : null;
            $hist_data['kd_sub_blok'] = array_key_exists('kd_sub_blok', $old_data) ? $old_data['kd_sub_blok'] : null;
            $hist_data['keterangan'] = array_key_exists('keterangan', $old_data) ? $old_data['keterangan'] : null;
            $hist_data['kd_peruntukan'] = array_key_exists('kd_peruntukan', $old_data) ? $old_data['kd_peruntukan'] : 0;
            $hist_data['flag_default'] = array_key_exists('flag_default', $old_data) ? $old_data['flag_default'] : 0;
            $hist_data['flag_lokasi'] = array_key_exists('flag_lokasi', $old_data) ? $old_data['flag_lokasi'] : null;
        }
		$this->db->insert('mst.t_produk_lokasi_history', $hist_data);
        return $this->db->last_query();
	}

	public function get_detail($kd_produk = '', $search = ''){
		if($search!=''){
			$this->db->where("((lower(nama_produk) LIKE '%" . $search . "%') OR (a.kd_produk LIKE '%" . $search . "%'))", NULL);
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

    public function get_peruntukan($kd_lokasi){
        $this->db->select("kd_peruntukan")
        ->from("mst.t_lokasi")
        ->where("kd_lokasi", $kd_lokasi);
        $query = $this->db->get();

        $kode = 0;
        if ($query->num_rows() != 0) {
            $row = $query->row();
            $kode = $row->kd_peruntukan;
        }
        return $kode;
    }

    public function get_row($kd_produk, $kd_lokasi = '', $kd_blok = '', $kd_sub_blok = ''){
        $this->db->select("a.*, nama_produk, nama_lokasi, nama_blok, nama_sub_blok")
        ->from("mst.t_produk_lokasi a")
        ->join("mst.t_lokasi b","a.kd_lokasi = b.kd_lokasi")
        ->join("mst.t_blok c","a.kd_blok = c.kd_blok AND a.kd_lokasi = c.kd_lokasi")
        ->join("mst.t_sub_blok d","a.kd_sub_blok = d.kd_sub_blok AND a.kd_blok = d.kd_blok AND a.kd_lokasi = d.kd_lokasi")
        ->join("mst.t_produk e","a.kd_produk = e.kd_produk")
        ->where("a.kd_produk",$kd_produk)
        ->where("a.kd_lokasi",$kd_lokasi)
        ->where("a.kd_blok",$kd_blok)
        ->where("a.kd_sub_blok",$kd_sub_blok);

        $query = $this->db->get();

        $row = '';
        if ($query->num_rows() != 0) {
            $row = $query->row();
        }

//        return $this->db->last_query();
        return $row;
    }

    public function get_default_row($kd_produk){
        $row = null;
        $this->db->select("a.*, nama_produk, nama_lokasi, nama_blok, nama_sub_blok")
        ->from("mst.t_produk_lokasi a")
        ->join("mst.t_lokasi b","a.kd_lokasi = b.kd_lokasi")
        ->join("mst.t_blok c","a.kd_blok = c.kd_blok AND a.kd_lokasi = c.kd_lokasi")
        ->join("mst.t_sub_blok d","a.kd_sub_blok = d.kd_sub_blok AND a.kd_blok = d.kd_blok AND a.kd_lokasi = d.kd_lokasi")
        ->join("mst.t_produk e","a.kd_produk = e.kd_produk")
        ->where("a.kd_produk",$kd_produk)
        ->where("a.flag_default",1);

        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            $row = $query->row();
        }
        return $row;
    }

	public function delete_row($kd_produk = NULL, $kd_lokasi = NULL, $kd_blok = NULL, $kd_sub_blok = NULL){
		$this->db->where("kd_produk",$kd_produk);
		$this->db->where("kd_lokasi",$kd_lokasi);
		$this->db->where("kd_blok",$kd_blok);
		$this->db->where("kd_sub_blok",$kd_sub_blok);

		return $this->db->delete('mst.t_produk_lokasi');
	}

    public function search_all_lokasi($search = "") {
        $sql_search = null;
        $peruntukan = null;
        if ($search != "") {
            $sql_search .= " and (lower(kd_lokasi) LIKE '%" . strtolower($search) . "%' )";
        }
	    $kd_peruntukan = intval($this->session->userdata('user_peruntukan'));
        if($kd_peruntukan != 2) {
            $peruntukan = " and b.kd_peruntukan = '$kd_peruntukan'";
        }

        $sql1 = "select distinct on (d.kd_sub_blok, c.kd_blok, b.kd_lokasi)
                    b.kd_lokasi, c.kd_blok, d.kd_sub_blok,
                    b.nama_lokasi || ' - ' || c.nama_blok || ' - ' || d.nama_sub_blok nama_lokasi,
                    CASE WHEN b.kd_peruntukan = '0' THEN 'Supermarket' WHEN b.kd_peruntukan = '1' THEN 'Distribusi' END peruntukan
                    from mst.t_lokasi b, mst.t_blok c, mst.t_sub_blok d
                    where d.kd_lokasi = b.kd_lokasi and d.kd_blok = c.kd_blok $peruntukan
                    group by  b.kd_lokasi, c.kd_blok, d.kd_sub_blok, b.nama_lokasi, c.nama_blok, d.nama_sub_blok, b.kd_peruntukan
                    order by kd_lokasi desc";

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true,data:' . json_encode($rows) . '}';
        return $results;
    }

    public function search_lokasi($kd_produk = "", $start, $limit, $search = "") {
        $sql_search = "";
        if ($search != "") {
            $sql_search .= " and (lower(nama_lokasi) LIKE '%" . strtolower($search) . "%' )";
        }

        if($kd_produk != "") {
            $sql_search .= " and a.kd_produk = '". $kd_produk ."'";
        }

        $sql1 = "select distinct on (b.kd_lokasi, c.kd_blok, d.kd_sub_blok)
                    b.kd_lokasi, c.kd_blok, d.kd_sub_blok,
                    b.kd_lokasi || c.kd_blok || d.kd_sub_blok kd_sub,
                    b.nama_lokasi || ' - ' || c.nama_blok || ' - ' || d.nama_sub_blok nama_lokasi,
                    a.flag_default
                    from mst.t_produk_lokasi a, mst.t_lokasi b, mst.t_blok c, mst.t_sub_blok d
                    where 1=1 $sql_search
                    and a.kd_lokasi = b.kd_lokasi
                    and a.kd_blok = c.kd_blok
                    and b.kd_lokasi = c.kd_lokasi
                    and a.kd_sub_blok = d.kd_sub_blok
                    and b.kd_lokasi = d.kd_lokasi
                    and c.kd_blok = d.kd_blok order by kd_lokasi desc limit $limit offset $start";

        $query = $this->db->query($sql1);

        //print_r($sql1);
        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{success:true, data:' . json_encode($rows) . '}';
        return $results;
    }

    public function search_produk_by_kategori( $kd_kategori1 = "", $kd_kategori2 = "", $kd_kategori3 = "", $kd_kategori4 = "", $search = ''){
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

        $sql = <<<EOT
SELECT
    a.kd_produk,
    a.kd_produk_lama,
    a.nama_produk,
    b.nm_satuan,
    a.kd_peruntukkan,
    g.kd_lokasi,
    g.kd_blok,
    g.kd_sub_blok,
    CASE WHEN g.flag_lokasi = 'G' THEN 'Gudang' ELSE 'Supermarket' end flag_lokasi,
    g.flag_default,
    h.nama_lokasi || ' - ' || i.nama_blok || ' - ' || j.nama_sub_blok lokasi_default
FROM mst.t_produk a
    JOIN mst.t_satuan b ON b.kd_satuan = a.kd_satuan
    JOIN mst.t_kategori1 c ON a.kd_kategori1 = c.kd_kategori1
    JOIN mst.t_kategori2 d ON a.kd_kategori2 = d.kd_kategori2 AND a.kd_kategori1 = d.kd_kategori1
    JOIN mst.t_kategori3 e ON a.kd_kategori3 = e.kd_kategori3 AND a.kd_kategori2 = e.kd_kategori2 AND a.kd_kategori1 = e.kd_kategori1
    JOIN mst.t_kategori4 f ON a.kd_kategori4 = f.kd_kategori4 AND a.kd_kategori3 = f.kd_kategori3 AND a.kd_kategori2 = f.kd_kategori2 AND a.kd_kategori1 = f.kd_kategori1
    LEFT JOIN mst.t_produk_lokasi g ON a.kd_produk = g.kd_produk and g.flag_default = 1
    LEFT JOIN mst.t_lokasi h ON g.kd_lokasi = h.kd_lokasi
    LEFT JOIN mst.t_blok i ON g.kd_blok = i.kd_blok AND g.kd_lokasi = i.kd_lokasi
    LEFT JOIN mst.t_sub_blok j ON g.kd_sub_blok = j.kd_sub_blok AND g.kd_blok = j.kd_blok AND g.kd_lokasi = j.kd_lokasi
WHERE 1=1 $where order by a.kd_produk asc, g.flag_default desc
EOT;
        $query = $this->db->query($sql);
        $rows = array();
        if($query->num_rows() > 0){
            $rows = $query->result();
        }

        return $rows;
    }
}
