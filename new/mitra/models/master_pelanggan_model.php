<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master_pelanggan_model extends MY_Model {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_rows($search = "", $offset, $length) {
        $sql_search = "";
        if ($search != "") {
            $sql_search = " and (lower(a.kd_pelanggan)  LIKE '%" . strtolower($search) . "%' or lower(a.nama_pelanggan) LIKE '%" . strtolower($search) . "%')";
        }

        $sql1 = <<<EOT
SELECT DISTINCT
    b.nama_propinsi, c.nama_kota, d.nama_kecamatan, e.nama_kalurahan, f.nama_cabang, a.*,
    CASE WHEN aktif = '1' THEN 'Ya' ELSE 'Tidak' end aktif,
    CASE WHEN is_pkp = '1' THEN 'Ya' ELSE 'Tidak' end pkp,
    CASE WHEN tipe = '1' THEN 'Toko' WHEN tipe ='2' THEN 'Modern Market' ELSE 'Agen' end tipe_pelanggan
FROM mst.t_pelanggan_dist a, mst.t_propinsi b, mst.t_kota c, mst.t_kecamatan d, mst.t_kalurahan e, mst.t_cabang f
where
    a.kd_propinsi = b.kd_propinsi
    and a.kd_kota = c.kd_kota
    and a.kd_kecamatan = d.kd_kecamatan
    and a.kd_kalurahan = e.kd_kalurahan
    and a.kd_cabang = f.kd_cabang
    $sql_search
order by kd_pelanggan
limit $length offset $offset;
EOT;

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total FROM mst.t_pelanggan_dist a, mst.t_propinsi b, mst.t_kota c, mst.t_kecamatan d
								where a.kd_propinsi = b.kd_propinsi
								and a.kd_kota = c.kd_kota
								and a.kd_kecamatan = d.kd_kecamatan" . $sql_search;

        $query = $this->db->query($sql2);

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
        $this->db->select("*", FALSE);
        $this->db->where("kd_pelanggan", $id);
        $query = $this->db->get('mst.t_pelanggan_dist');

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":' . json_encode($row) . '}';
        }
    }

    public function get_sales() {
        $sql = "select a. nama_sales, a.kd_sales from mst.t_sales a;";
        $query = $this->db->query($sql);

        $rows = $query->result();
        $results = '{success:true,data:' . json_encode($rows) . '}';

        return $results;
    }

    public function getArea() {
        $sql = "select a. nama_area, a.kd_area from mst.t_area a;";
        $query = $this->db->query($sql);

        $rows = $query->result();
        $results = '{success:true,data:' . json_encode($rows) . '}';

        return $results;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function insert_row($data = NULL) {
        return $this->db->insert('mst.t_pelanggan_dist', $data);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row($id = NULL, $data = NULL) {
//        return json_encode($data);
        $this->db->where('kd_pelanggan', $id);
        return $this->db->update('mst.t_pelanggan_dist', $data);
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
        $this->db->where('kd_pelanggan', $id);
        return $this->db->update('mst.t_pelanggan_dist', $data);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_last_records() {
        $query = $this->db->query("SELECT to_number(kd_pelanggan,'99') kd_pelanggan FROM mst.t_pelanggan_dist WHERE kd_pelanggan = (SELECT MAX(kd_pelanggan) FROM mst.t_pelanggan_dist)");
        $return_value = "";
        foreach ($query->result() as $row) {
            $return_value = $row->kd_pelanggan;
        }
        return $return_value;
    }

}
