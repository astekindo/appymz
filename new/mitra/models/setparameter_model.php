<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setparameter_model extends MY_Model {

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
        $this->db->select("*,CASE WHEN type_parameter = '1' THEN 'Nilai' ELSE 'Akun' END type_parameter", FALSE);
        if ($search != "") {
            $sql_search = "(lower(kd_parameter) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $this->db->order_by("kd_parameter", "asc");
        $query = $this->db->get("mst.t_parameter", $length, $offset);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        if ($search != "") {
            $sql_search = "(lower(kd_parameter) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $query = $this->db->get("mst.t_parameter");

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
        $this->db->select("*,CASE WHEN type_parameter = '1' THEN '1' ELSE '2' END type_parameter", FALSE);
        $this->db->where("kd_parameter", $id);
        $query = $this->db->get('mst.t_parameter');

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":' . json_encode($row) . '}';
        }
    }

    public function insert_row($data = NULL) {
        $param = array($data['kd_parameter']  => $data['nilai_parameter']);
        
        $this->session->set_userdata($param);
        return $this->db->insert('mst.t_parameter', $data);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row($id = NULL, $data = NULL) {
        $param = array($id  => $data['nilai_parameter']);
        
        $this->session->set_userdata($param);
        $this->db->where('kd_parameter', $id);
        return $this->db->update('mst.t_parameter', $data);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function delete_row($id = NULL) {
        $this->db->where('kd_parameter', $id);
        return $this->db->delete('mst.t_parameter', $id);
    }
    
    public function getRowByIds($ids = NULL){
        $this->db->or_where_in('kd_parameter', $ids);
        $query = $this->db->get('mst.t_parameter');
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }
        $results = '{success:true,' . json_encode($rows) . '}';

        return $results;
    }

}
