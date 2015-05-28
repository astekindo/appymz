<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usergroup_model extends MY_Model {

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
    public function get_row($id = NULL, $id1 = NULL) {
        $this->db->select("a.kd_group, b.kd_menu, b.menu_text, b.menu_description,a.bview,a.bdelete,a.bupdate,a.binsert,b.aktif", FALSE);
        $this->db->join('secman.t_menu b', 'b.kd_menu=a.kd_menu');
        $this->db->where("a.kd_group", $id);
        $this->db->where("a.kd_menu", $id1);
        $this->db->where('a.aktif', '1');
        $query = $this->db->get('secman.t_groupmenu a');

        if ($query->num_rows() != 0) {
            $row = $query->row();

            echo '{"success":true,"data":' . json_encode($row) . '}';
        }
    }

    public function insert_row($data = NULL) {
        return $this->db->insert('secman.t_groupmenu', $data);
    }
    
     public function update_groupmenu($id = NULL, $id1 = NULL, $data = NULL){
                $this->db->where('kd_group', $id);
		$this->db->where('kd_menu', $id1);		
		return $this->db->update('secman.t_groupmenu', $data);
    }
    
    public function get_groupmenu($kd_group=''){
        $sql="select kd_group,kd_menu, bview,binsert,bupdate,bdelete from secman.t_groupmenu
              where kd_group='$kd_group' and aktif is true" ;
        $query = $this->db->query($sql);
        $rows = array();
        if($query->num_rows() > 0){
            $rows = $query->result();
        }
        return $rows;
    }
        
    public function cek_exists_menu($kd_group='',$kd_menu=''){
            $sql="select * from secman.t_groupmenu
                  where kd_group='$kd_group' and kd_menu='$kd_menu'";
            
            $query = $this->db->query($sql);
//            $rows = array();
//            
//            if($query->num_rows() > 0){
//                $rows = $query->result();
//            }
            
            return $query->num_rows();
        }

    public function delete_row($kd_group = NULL, $kd_menu = NULL) {
        $updated_by = $this->session->userdata('username');
        $updated_date = date('Y-m-d H:i:s');
        $data = array(
            'aktif' => '0',
            'updated_by' => $updated_by,
            'updated_date' => $updated_date
        );
        $this->db->where('kd_group', $kd_group);
        $this->db->where('kd_menu', $kd_menu);
        return $this->db->update('secman.t_groupmenu', $data);
    }

    public function get_menu($search = "", $offset, $length) {
        $this->db->select("*,CASE WHEN aktif is TRUE THEN '1' ELSE '0' END aktif", FALSE);
        if ($search != "") {
            $sql_search = "(lower(kd_group) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $this->db->where('aktif', '1');
        $this->db->order_by("kd_group", "desc");
        $query = $this->db->get("secman.t_group");

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $this->db->select('count(*) as total');
        if ($search != "") {
            $sql_search = "(lower(kd_group) LIKE '%" . strtolower($search) . "%')";
            $this->db->where($sql_search, NULL);
        }
        $query = $this->db->get("secman.t_group");

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }
        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_rows_detail($search = "") {
        $sql_search = "";
        $sql_search = "  (lower(a.kd_group) = '" . strtolower($search) . "') AND ";


        $sql1 = "SELECT  a.kd_group, b.kd_menu, b.menu_text, b.menu_description,
					CASE WHEN a.bview = '1' THEN 1 ELSE 0 END bview,
					CASE WHEN a.bdelete = '1' THEN 1 ELSE 0 END bdelete,
					CASE WHEN a.bupdate = '1' THEN 1 ELSE 0 END bupdate,
					CASE WHEN a.binsert = '1' THEN 1 ELSE 0 END binsert
					FROM secman.t_groupmenu a, secman.t_menu b
					WHERE " . $sql_search . " 
					b.kd_menu=a.kd_menu
					and a.aktif = '1'";

        $query = $this->db->query($sql1);

        $rows = array();
        if ($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $this->db->flush_cache();
        $sql2 = "select count(*) as total from (SELECT  a.kd_group, b.kd_menu, b.menu_text, b.menu_description
					FROM secman.t_groupmenu a, secman.t_menu b
					WHERE " . $sql_search . " 
					b.kd_menu=a.kd_menu) as tabel";

        $query = $this->db->query($sql2);

        $total = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $total = $row->total;
        }

        $results = '{success:true,record:' . $total . ',data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_cbusergroup($idgroup = NULL) {
        $sql = "select b.menu_text,b.kd_menu, a.kd_group from secman.t_menu b
					left join
					(select * from secman.t_groupmenu
					where kd_group = '" . $idgroup . "') a on b.kd_menu = a.kd_menu
					where kd_group is null";
        $query = $this->db->query($sql);

        $rows = $query->result();
        $results = '{success:true,data:' . json_encode($rows) . '}';

        return $results;
    }

    public function get_tree_child($menu_parent) {
        $sql = "SELECT 
					kd_menu, kd_parent_menu as parent, menu_text as text,
                                        0 sel,0 vie, 0 ins, 0 upd, 0 del
                                        FROM
					secman.t_menu
				WHERE (case when kd_parent_menu is null then '' else kd_parent_menu end ) = '" . $menu_parent . "'				
				AND aktif = 1 order by kd_menu";

        $query = $this->db->query($sql);

        if ($query->num_rows() == 0) {
            return FALSE;
        }

        $rows = $query->result();
        return $rows; //$rows;
    }

//    public function get_tree_menu2($menu_parent = '') {
//        $sql = "SELECT 
//					kd_menu, kd_parent_menu as parent, menu_text as text,
//                                        0 sel,0 vie, 0 ins, 0 upd, 0 del
//                                        FROM
//					secman.t_menu
//				WHERE (case when kd_parent_menu is null then '' else kd_parent_menu end ) = '" . $menu_parent . "'				
//				AND aktif = 1 order by kd_menu";
//
//        $query = $this->db->query($sql);
//
//        if ($query->num_rows() === 0) {
//            return FALSE;
//        }
//
//        $rows = $query->result();
//
//        foreach ($rows as $obj) {
//            $have_children = $this->get_tree_menu2($obj->kd_menu);
//            if ($have_children) {
//                $obj->children = $have_children;
//            }
//        }
//
//        return $rows;
//    }

    public function get_tree_menu() {
        $sql = "SELECT kd_menu, kd_parent_menu as parent, menu_text as text,
                0 sel,0 vie, 0 ins, 0 upd, 0 del
                FROM secman.t_menu
		WHERE aktif = 1 order by kd_menu";
        $query = $this->db->query($sql);

        if ($query->num_rows() === 0) {
            return FALSE;
        }

        $rows = $query->result();

//        foreach ($rows as $obj) {
//            $have_children = $this->get_tree_menu2($obj->kd_menu);
//            if ($have_children) {
//                $obj->children = $have_children;
//            }
//        }

        return $rows;
    }

}