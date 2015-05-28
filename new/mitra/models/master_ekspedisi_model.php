<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_ekspedisi_model extends MY_Model {
	
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
	 
	public function get_rows_master($search = '', $length, $offset){
        $results= array('data' => array(), 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $this->db->like('lower(nama_ekspedisi)', strtolower($search));
        }

        $this->db->select("kd_ekspedisi,nama_ekspedisi, CASE WHEN aktif=1 THEN 'Ya' ELSE 'Tidak' END aktif", false);
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('mst.t_ekpedisi');

        $this->db->order_by('kd_ekspedisi desc');
        $query = $this->db->get('mst.t_ekpedisi', $length, $offset);
        $results['lq'] = $this->db->last_query();
        if($results['total'] > 0) $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
	}
	
	public function get_rows_price($kd_ekspedisi = '', $search = "", $offset, $length){
        $results= array('data' => array(), 'total' => 0);

        $this->db->start_cache();
        if ($search != "") {
            $this->db->like('lower(a.nama_ekspedisi)', strtolower($search));
        }
        if($kd_ekspedisi != '') {
            $this->db->where('a.kd_ekspedisi', $kd_ekspedisi);
        }

        $this->db->select("a.kd_harga, a.kd_ekspedisi, a.tujuan, a.kd_satuan, nm_satuan, rp_harga, coalesce(nilai_satuan,0) nilai_satuan, a.keterangan", false)
          ->join('mst.t_satuan b', 'a.kd_satuan = b.kd_satuan');
        $this->db->stop_cache();
        $results['total'] = $this->db->count_all_results('mst.t_ekspedisi_price a');

        $this->db->order_by('kd_ekspedisi desc');
        $query = $this->db->get('mst.t_ekspedisi_price a', $length, $offset);
        $results['lq'] = $this->db->last_query();
        if($results['total'] > 0) $results['data'] = $query->result();

        $this->db->flush_cache();
        return $results;
	}
	
	public function get_row_master($kd_ekspedisi){
        $results= array('data' => null, 'total' => 0);

        $this->db->where('kd_ekspedisi', $kd_ekspedisi);
        $query = $this->db->get('mst.t_ekpedisi');

        $results['lq'] = $this->db->last_query();
        $results['total'] = $query->num_rows();
        if($results['total'] > 0) $results['data'] = $query->row();

        return $results;
	}

	public function get_row_price($kd_harga) {
        $results= array('data' => null, 'total' => 0);

        $this->db->where('a.kd_harga', $kd_harga);
        $this->db->select("a.kd_ekspedisi, a.tujuan, a.kd_satuan, nm_satuan, rp_harga, coalesce(nilai_satuan,0) nilai_satuan, a.keterangan", false)
          ->join('mst.t_satuan b', 'a.kd_satuan = b.kd_satuan');
        $query = $this->db->get('mst.t_ekspedisi_price a');
        $results['lq'] = $this->db->last_query();
        $results['total'] = $query->num_rows();
        if($results['total'] > 0) $results['data'] = $query->row();

        return $results;
	}
	
	public function insert_row($table = '', $data = NULL){
		return $this->db->insert($table, $data);
		
	}
	
	public function update_row($table = '', $kd1 = NULL, $data = NULL){
		$this->db->where("kd_ekspedisi",$kd1);
		return $this->db->update($table, $data);
	}
	
	public function delete_row($kd_ekspedisi = NULL, $tujuan = NULL){
		$where = array(
					'kd_ekspedisi' => $kd_ekspedisi,
					'tujuan' => $tujuan,
				);
		$this->db->where($where);
		
		return $this->db->delete('mst.t_ekspedisi_price');
	}
	
}
