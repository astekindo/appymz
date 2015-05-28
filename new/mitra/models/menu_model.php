<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menu_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_rows($search = "", $offset, $length) {
        $this->db->select("*,CASE WHEN aktif =1 THEN 'Ya' ELSE 'Tidak' END aktif,CASE WHEN menu_leaf IS true THEN 'true' ELSE 'false' END menu_leaf,
            CASE WHEN menu_expanded IS true THEN 'true' ELSE 'false' END menu_expanded", FALSE);
        if ($search != "") {
            $sql_search = "(lower(menu_text) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $this->db->where('aktif', '1');
        $this->db->order_by("menu_text", "asc");
        $query = $this->db->get("secman.t_menu");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        if ($search != "") {
            $sql_search = "(lower(menu_text) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $query = $this->db->get("secman.t_menu");


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
        $this->db->select("*,CASE WHEN aktif =1 THEN 'Ya' ELSE 'Tidak' END aktif,CASE WHEN menu_leaf IS true THEN 'true' ELSE 'false' END menu_leaf,
            CASE WHEN menu_expanded IS true THEN 'true' ELSE 'false' END menu_expanded", FALSE);
        $this->db->where("kd_menu", $id);
        $this->db->order_by("menu_text", "asc");
        $query = $this->db->get('secman.t_menu');

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":' . json_encode($row) . '}';
        }
    }
    
    
    public function insert_row($data = NULL) {
        return $this->db->insert('secman.t_menu', $data);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function update_row($id = NULL, $data = NULL) {
        $this->db->where('kd_menu', $id);
        return $this->db->update('secman.t_menu', $data);
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
        $this->db->where('kd_menu', $id);
        return $this->db->update('secman.t_menu', $data);
    }

}

?>
