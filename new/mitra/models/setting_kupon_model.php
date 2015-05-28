<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Setting_kupon_model extends MY_Model {

    /**
     * @author dhamarsu
     * @editedby luxse
     * @lastedited 2 jan 2012
     */
    public function __construct() {
        parent::__construct();
    }

    public function get_rows($search,$start,$limit)
    {
        $result = array('total' => 0, 'data' => array());
        $this->db->start_cache();
        if(!empty($search)) {
            $this->db->like('kd_kupon',$search)->or_like('tgl_awal',$search)->or_like('tgl_akhir',$search);
        }

        $this->db->select('kd_kupon, rupiah, kupon, tgl_awal, tgl_akhir')->from('mst.t_kupon_rupiah');
        $this->db->stop_cache();
        $result['total'] = $this->db->count_all_results();
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        $result['lq']   = $this->db->last_query();
        $result['data'] = $query->result();

        $this->db->flush_cache();
        return $result;
    }

    public function get_row($kd_kupon)
    {
        $result = array('total' => 0, 'data' => null);
        $this->db->start_cache();
        $this->db->where('kd_kupon',$kd_kupon);
        $this->db->select('kd_kupon, rupiah, kupon, tgl_awal, tgl_akhir')->from('mst.t_kupon_rupiah');
        $this->db->stop_cache();
        $result['total'] = intval($this->db->count_all_results());
        $query = $this->db->get();
        $result['lq'] = $this->db->last_query();
        $result['data'] = $query->row();

        $this->db->flush_cache();
        return $result;
    }

    public function insert_row($kd_kupon, $data)
    {
        $result = array('success' => false);
        $get = $this->get_row($kd_kupon);
        $data['kd_kupon'] = $kd_kupon;
        if($get['total'] == 0) {
            $result['success'] = $this->db->insert('mst.t_kupon_rupiah',$data);
            $result['lq'] = $this->db->last_query();
        }
        return $result;
    }

    public function update_row($kd_kupon, $data)
    {
        $result = array('success' => false);
        if(!empty($kd_kupon)) {
            $this->db->where('kd_kupon', $kd_kupon);
            $result['success'] = $this->db->update('mst.t_kupon_rupiah',$data);
            $result['lq'] = $this->db->last_query();
        }
        return $result;
    }
    
    public function select_data($rupiah = "",$tgl_awal = "",$tgl_akhir = ""){
		$sql = "select * from mst.t_kupon_rupiah
                          where rupiah ='$rupiah' 
                          and tgl_awal <= '$tgl_awal' and tgl_akhir >= '$tgl_awal'
                          ";
		
                $query = $this->db->query($sql);
                return $query->result();
	}
    public function select_data_end($rupiah = "",$tgl_awal = "",$tgl_akhir = ""){
		
		$sql = "select * from mst.t_kupon_rupiah
                          where rupiah ='$rupiah' 
                          and tgl_awal <= '$tgl_akhir' and tgl_akhir >= '$tgl_akhir'
                          ";
		
                $query = $this->db->query($sql);
                return $query->result();
	}
}