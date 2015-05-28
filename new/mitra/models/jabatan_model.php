<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jabatan_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_rows($search = "", $offset, $length) {
        $this->db->select("*,CASE WHEN aktif =1 THEN 'Ya' ELSE 'Tidak' END aktif", FALSE);
        if ($search != "") {
            $sql_search = "(lower(nama_jabatan) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $this->db->where('aktif', '1');
        $this->db->order_by("kd_jabatan", "desc");
        $query = $this->db->get("secman.t_jabatan", $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        if ($search != "") {
            $sql_search = "(lower(nama_jabatan) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $query = $this->db->get("secman.t_jabatan");

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
        $this->db->where("kd_jabatan", $id);
        $query = $this->db->get('secman.t_jabatan');

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":' . json_encode($row) . '}';
        }
    }
    
    public function get_lvljabatan($id = NULL) {
        $this->db->select("lvl_jabatan", FALSE);
        $this->db->where("kd_jabatan", $id);
        $query = $this->db->get('secman.t_jabatan');
        $retval=0;
        if ($query->num_rows() != 0) {
            foreach ($query->result_array() as $row) {
                $retval = $row['lvl_jabatan'];
            }
            $retval=$retval+1;
        }
        return $retval;   
        
    }

    public function insert_row($data = NULL) {
        return $this->db->insert('secman.t_jabatan', $data);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row($id = NULL, $data = NULL) {
        $this->db->where('kd_jabatan', $id);
        return $this->db->update('secman.t_jabatan', $data);
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
        $this->db->where('kd_jabatan', $id);
        return $this->db->update('secman.t_jabatan', $data);
    }
    
    public function get_cabang($search = "", $offset, $length) {
        $this->db->select("kd_cabang,nama_cabang", FALSE);
        $this->db->order_by("kd_cabang", "desc");
        $query = $this->db->get("mst.t_cabang", $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        $query = $this->db->get("mst.t_cabang");

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
