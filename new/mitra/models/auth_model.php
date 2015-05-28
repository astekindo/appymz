<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//  CI 2.0 Compatibility
if (!class_exists('CI_Model')) {

    class CI_Model extends Model {

    }

}

class Auth_model extends CI_Model {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();

        $this->salt_length = $this->config->item('salt_length');
        $this->min_password_length = $this->config->item('min_password_length');

        /**
         * Checks if salt length is at least the length
         * of the minimum password length.
         * */
        if ($this->salt_length < $this->min_password_length) {
            $this->salt_length = $this->min_password_length;
        }
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function login($username, $password, $remember = false) {
        if (empty($username) || empty($password)) {
            return FALSE;
        }

        $this->db->select("a.*, b.nama_group,c.nama_jabatan,d.nama_lengkap,e.nama_cabang");
        $this->db->join("secman.t_group b", "b.kd_group = a.kd_group");
        $this->db->join("secman.t_jabatan c", "c.kd_jabatan = a.kd_jabatan", "left");
        $this->db->join("secman.t_user_info d", "d.username = a.username", "left");
        $this->db->join("mst.t_cabang e", "e.kd_cabang = a.kd_cabang", "left");
        $this->db->where("a.username", $username);
        $this->db->where("a.aktif is true", NULL);
        $this->db->where("b.aktif is true", NULL);
        $this->db->limit(1);
        $query = $this->db->get("secman.t_user a");

        $result = $query->row();

        if ($query->num_rows() == 1) {
            $password = $this->md5_password($password);

            if ($result->passwd === $password) {

                $session_data = array(
                    'username' => $result->username,
                    'nama_lengkap' => $result->nama_lengkap,
                    'email' => $result->email,
                    'kd_group' => $result->kd_group,
                    'nama_group' => $result->nama_group,
                    'kd_jabatan' => $result->kd_jabatan,
                    'nama_jabatan' => $result->nama_jabatan,
                    'kd_kategori1' => $result->kd_kategori1,
                    'kd_kategori2' => $result->kd_kategori2,
                    'kd_kategori3' => $result->kd_kategori3,
                    'kd_kategori4' => $result->kd_kategori4,
                    'user_peruntukan' => $result->kd_peruntukan,
                    'kd_cabang' => $result->kd_cabang,
                    'nama_cabang' => $result->nama_cabang,
                );

                $this->session->set_userdata($session_data);

                $param_data = $this->loadParameter();
                foreach ($param_data as $param){
                    $param_array = array(
                        $param->kd_parameter    => $param->nilai_parameter
                    );
                    $this->session->set_userdata($param_array);
                }
                return TRUE;
            }
        }

        return FALSE;
    }

    public function loadParameter(){
        $this->db->select('kd_parameter, nilai_parameter');
        $query = $this->db->get('mst.t_parameter');
        return $query->result();
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function change_password($username, $old, $new) {
        $query = $this->db->select('passwd')
                ->where('username', $username)
                ->where('aktif is true', NULL)
                ->limit(1)
                ->get('secman.t_user');

        $result = $query->row();

        $db_password = $result->passwd;
        $old = $this->md5_password($old);
        $new = $this->md5_password($new);

        if ($db_password === $old) {
            //store the new password
            $data = array(
                'passwd' => $new
            );

            $this->db->where('username', $username);
            $this->db->update('secman.t_user', $data);

            return TRUE;
        }

        return FALSE;
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function hash_password($password) {
        if (empty($password)) {
            return FALSE;
        }

        $salt = $this->salt();
        return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function hash_password_db($username, $password) {
        if (empty($username) || empty($password)) {
            return FALSE;
        }

        $query = $this->db->select('passwd')
                ->where('username', $username)
                ->where('aktif is true', NULL)
                ->limit(1)
                ->get('secman.t_user');

        $result = $query->row();

        if ($query->num_rows() !== 1) {
            return FALSE;
        }


        $salt = substr($result->passwd, 0, $this->salt_length);

        return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function md5_password($password) {


        return md5($password);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function salt() {
        return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_accordion_menu($kd_group) {
        $this->db->select("b.*");
        $this->db->where("a.kd_group", $kd_group);
        $this->db->where("a.aktif is true", NULL);
        $this->db->where("b.aktif", 1);
        $this->db->where("(b.kd_parent_menu = '' OR b.kd_parent_menu is null)", NULL);
        $this->db->order_by("menu_text", "asc");
        $this->db->join("secman.t_menu b", "a.kd_menu = b.kd_menu", "inner");
        $query = $this->db->get("secman.t_groupmenu a");

        if ($query->num_rows() === 0) {
            return FALSE;
        }
        return $query->result();
    }

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function get_tree_menu($menu_parent, $kd_group) {
        $sql = "SELECT
					kd_menu, kd_parent_menu, menu_id as id, menu_text as text
				FROM
					secman.t_menu
				WHERE
					kd_parent_menu = '" . $menu_parent . "'
				AND
					kd_menu IN (
						SELECT kd_menu FROM secman.t_groupmenu
						WHERE kd_group = '" . $kd_group . "' AND aktif is true
					)
				AND aktif = 1 order by menu_text asc";

        $query = $this->db->query($sql);

        if ($query->num_rows() === 0) {
            return FALSE;
        }

        $rows = $query->result();

        foreach ($rows as $obj) {
            $obj->leaf = true;
            $have_children = $this->get_tree_menu($obj->kd_menu, $kd_group);

            if ($have_children) {
                $obj->children = $have_children;
                $obj->leaf = false;
                $obj->expanded = true;
            }
        }

        return $rows;
    }

    public function get_mac($mac)
    {
        $mac = strtolower($mac);
        $mac_alt = strtolower(str_replace(':', '-'));

        $query = $this->db->query("select * from secman.t_mac_address where (lower(mac_address) = '$mac' or lower(mac_address) = '$mac_alt') and status = 1");
        $count = $query->num_rows();
        return $count > 0;
    }

}
