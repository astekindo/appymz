<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Set_lokasi_default extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('barang_per_lokasi_model', 'bpl_model');
                $this->load->model('set_lokasi_default_model', 'sld_model');
    }

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	$no_bpl = 'BL' . date('Ym') . '-';
    	$sequence = $this->bpl_model->get_kode_sequence($no_bpl, 4);
    	echo '{"success":true,
				"data":{
					"no_bpl":"' . $no_bpl . $sequence . '",
					"tanggal":"' . date('d-m-Y'). '"
				}
			}';
    }

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function update_row(){
        $cmd = isset($_POST['cmd']) ? $this->db->escape_str($this->input->post('cmd',TRUE)) : FALSE;
        $detail = isset($_POST['detail']) ? $this->db->escape_str($this->input->post('detail',TRUE)) : FALSE;
        $no_bpl = isset($_POST['no_bpl']) ? $this->db->escape_str($this->input->post('no_bpl',TRUE)) : FALSE;
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : FALSE;
        $kd_lokasi = isset($_POST['kd_lokasiGrid']) ? $this->db->escape_str($this->input->post('kd_lokasiGrid',TRUE)) : FALSE;
        $kd_blok = isset($_POST['kd_blokGrid']) ? $this->db->escape_str($this->input->post('kd_blokGrid',TRUE)) : FALSE;
        $kd_sub_blok = isset($_POST['kd_sub_blokGrid']) ? $this->db->escape_str($this->input->post('kd_sub_blokGrid',TRUE)) : FALSE;
        $keterangan = isset($_POST['keterangan']) ? $this->db->escape_str($this->input->post('keterangan',TRUE)) : FALSE;
        $kd_peruntukan = isset($_POST['kd_peruntukan']) ? $this->db->escape_str($this->input->post('kd_peruntukan',TRUE)) : FALSE;
        $detail = json_decode($detail,true);

        //belum ada disimpan di history
        $updated_by = $this->session->userdata('username');
        $updated_date = date('Y-m-d H:i:s');
        $count = 0;

        $no_sld = 'LD' . date('Ym') . '-';
        $no_bukti = $this->sld_model->get_kode_sequence($no_sld, 4);
        $no_bukti = $no_sld . $no_bukti;


        $this->db->trans_start();
        for($i=0;$i<count($detail);$i++) {
            if($detail[$i]['koreksi_lokasi'] == 'Y'){
                    if($detail[$i]['flag_lokasi'] == 'Gudang'){
                        $flag_lokasi = 'G';
                    }else {
                        $flag_lokasi = 'S';
                    }
            $old_data = $this->sld_model->get_row($detail[$i]['kd_produk'], $detail[$i]['kd_lokasi'], $detail[$i]['kd_blok'], $detail[$i]['kd_sub_blok']);
            $reset_default = $this->sld_model->update_row($detail[$i]['kd_produk'], array('flag_default'  => 0,'flag_lokasi' => $flag_lokasi), array('kd_produk'=>$detail[$i]['kd_produk']));
            if(!empty($old_data)) {
            //lokasi sudah ada
                $old_data = get_object_vars($old_data);
                $add_history   = $this->sld_model->insert_row_history($no_bukti, $updated_date,$flag_lokasi, $old_data);
                $add_default   = $this->sld_model->update_row($detail[$i]['kd_produk'], array('flag_default'  => 1,'flag_lokasi' => $flag_lokasi), $old_data);
            } else {
            //lokasi belum ada/lokasi baru
                $new_data = array(
                    'kd_produk'     => $detail[$i]['kd_produk'],
                    'kd_lokasi'     => $detail[$i]['kd_lokasi'],
                    'kd_blok'       => $detail[$i]['kd_blok'],
                    'kd_sub_blok'   => $detail[$i]['kd_sub_blok'],
                    'kd_peruntukan' => $this->sld_model->get_peruntukan($detail[$i]['kd_lokasi']),
                    'flag_lokasi'   => $flag_lokasi,
                    'flag_default'  => 1
                );
                $add_history   = $this->sld_model->insert_row_history($no_bukti, $updated_date,$flag_lokasi, $new_data);
                $this->sld_model->update_row($detail[$i]['kd_produk'],$new_data);
            }
            $count++;
            }
        }
        $this->db->trans_complete();

        if ($count = count($detail)) {
            $result = '{"success":true,"errMsg":""}';
        } elseif ($count < count($detail)) {
            $result = '{"success":true,"errMsg":"Sebagian data tidak ter-update.."}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }

        echo $result;
	}


	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function search_produk_by_kategori(){
		$kd_kategori1 = isset($_POST['kd_kategori1']) ? $this->db->escape_str($this->input->post('kd_kategori1',TRUE)) : '';
		$kd_kategori2 = isset($_POST['kd_kategori2']) ? $this->db->escape_str($this->input->post('kd_kategori2',TRUE)) : '';
		$kd_kategori3 = isset($_POST['kd_kategori3']) ? $this->db->escape_str($this->input->post('kd_kategori3',TRUE)) : '';
		$kd_kategori4 = isset($_POST['kd_kategori4']) ? $this->db->escape_str($this->input->post('kd_kategori4',TRUE)) : '';
		$search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		$hasil = $this->sld_model->search_produk_by_kategori($kd_kategori1,$kd_kategori2,$kd_kategori3,$kd_kategori4,$search);
		echo '{success:true,data:'.json_encode($hasil).'}';
	}

	public function get_detail(){
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : '';
		$search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';

		echo $this->bpl_model->get_detail($kd_produk,$search);
	}

	public function get_row(){
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : '';
		$kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi',TRUE)) : '';
		$kd_blok = isset($_POST['kd_blok']) ? $this->db->escape_str($this->input->post('kd_blok',TRUE)) : '';
		$kd_sub_blok = isset($_POST['kd_sub_blok']) ? $this->db->escape_str($this->input->post('kd_sub_blok',TRUE)) : '';

		$results =  $this->sld_model->get_row($kd_produk, $kd_lokasi, $kd_blok, $kd_sub_blok);
		echo '{"success":true,"data":'.json_encode($results).'}';
	}

    public function delete_row(){
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : FALSE;
		$kd_lokasi = isset($_POST['kd_lokasi']) ? $this->db->escape_str($this->input->post('kd_lokasi',TRUE)) : FALSE;
		$kd_blok = isset($_POST['kd_blok']) ? $this->db->escape_str($this->input->post('kd_blok',TRUE)) : FALSE;
		$kd_sub_blok = isset($_POST['kd_sub_blok']) ? $this->db->escape_str($this->input->post('kd_sub_blok',TRUE)) : FALSE;

		if ($this->bpl_model->delete_row($kd_produk, $kd_lokasi, $kd_blok, $kd_sub_blok)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}

    public function search_all_lokasi() {
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->sld_model->search_all_lokasi($search);

        echo $result;
    }

    public function search_lokasi() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';
        $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk', TRUE)) : '';

        $result = $this->sld_model->search_lokasi($kd_produk, $start, $limit, $search);


        echo $result;
    }
}
