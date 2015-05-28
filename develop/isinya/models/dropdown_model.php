<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dropdown_model extends CI_Model{

	// function untuk mendapatkan data untuk dropdown provinsi
	function get_provinsi()
	{
		$this->db->order_by("nama_provinsi", "asc");
		$query = $this->db->get('tabel_provinsi');
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}

	// function untuk mendapatkan data untuk dropdown kota, sesuai $id provinsi
	function get_kota($id)
	{
		$this->db->select('*');
		$this->db->order_by("nama_kota", "asc");
		$this->db->from('tabel_kota');
		$this->db->join('tabel_provinsi', 'tabel_kota.id_provinsi = tabel_provinsi.id_provinsi');
		$this->db->where('tabel_kota.id_provinsi', $id);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		else
		{
			return FALSE;
		}
	}
}