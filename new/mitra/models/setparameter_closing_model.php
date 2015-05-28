<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setparameter_closing_model extends MY_Model {
    
    protected $table = 'mst.t_param_closing';
    protected $pk = 'periode';

    function __construct() {
        parent::__construct();
    }

    public function get_rows($search = "", $start, $limit) {
        if($search != "") {
            $this->db->like($this->pk, $search);
        }
        $query = $this->db->get($this->table, $limit, $start);

        $rows = array();
        if($query->num_rows() > 0) {
            $rows = $query->result();
        }

        $results = '{"success":true,"record":' . $query->num_rows() . ',"data":' . json_encode($rows) . '}';
        return $results;

    }

    public function get_row($periode) {
        $this->db->where($this->pk,$periode);
        $query = $this->db->get($this->table);
        $rows = array();
        if($query->num_rows() > 0) {
            $rows = $query->result();
        }
        return '{"success":true,"data":' . json_encode($rows) . '}';
    }

    public function insert_row($data = null) {
        return $this->db->insert($this->table,$data);
    }

    public function update_row($id, $data = null) {
        $this->db->where($this->pk, $id);
        return $this->db->update($this->table, $data);
    }

    public function delete_row($id) {
        return $this->db->delete($this->table, array($this->pk, $id));
    }

    public function check_if_exists($periode) {
        $query = <<<EOT
SELECT COUNT(t_param_closing.periode)
FROM
  $this->table
WHERE
  periode = '$this->pk';
EOT;
        $result = $this->db->query($query);
        if(count($result->result_array()) < 1) {
            return false;
        } else return true;
    }

}