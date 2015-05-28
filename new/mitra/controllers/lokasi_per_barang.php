<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lokasi_per_barang extends MY_Controller {

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('lokasi_per_barang_model');
    }

	public function get_lokasi_barang(){
        $params = array(
          'kdLokasi'    => $this->form_data('kdLokasi'),
          'kdBlok'      => $this->form_data('kdBlok'),
          'kdSubBlok'   => $this->form_data('kdSubBlok'),
          'kdSatuan'    => $this->form_data('kdSatuan'),
          'kdUkuran'    => $this->form_data('kdUkuran'),
          'kdKategori1' => $this->form_data('kdKategori1'),
          'kdKategori2' => $this->form_data('kdKategori2'),
          'kdKategori3' => $this->form_data('kdKategori3'),
          'kdKategori4' => $this->form_data('kdKategori4'),
          'kdSuplier'   => $this->form_data('kdSuplier'),
        );
        if(!empty($params['kdKategori4']) && strlen($params['kdKategori4']) === 8) {
            $params['kdKategori1'] = substr( $params['kdKategori4'], 0, 1);
            $params['kdKategori2'] = substr( $params['kdKategori4'], 1, 2);
            $params['kdKategori3'] = substr( $params['kdKategori4'], 3, 3);
            $params['kdKategori4'] = substr( $params['kdKategori4'], 6, 2);
        } elseif(!empty($params['kdKategori3']) && strlen($params['kdKategori3']) === 6) {
            $params['kdKategori1'] = substr( $params['kdKategori3'], 0, 1);
            $params['kdKategori2'] = substr( $params['kdKategori3'], 1, 2);
            $params['kdKategori3'] = substr( $params['kdKategori3'], 3, 3);
        } elseif(!empty($params['kdKategori2']) && strlen($params['kdKategori2']) === 3) {
            $params['kdKategori1'] = substr( $params['kdKategori2'], 0, 1);
            $params['kdKategori2'] = substr( $params['kdKategori2'], 1, 2);
        }
        $peruntukan = $this->form_data('peruntukan', $this->session->userdata('user_peruntukan'));
        $search     = $this->form_data('query');
        $start      = $this->form_data('start', 0);
        $limit      = $this->form_data('limit', $this->config->item("length_records"));
        $result = $this->lokasi_per_barang_model->get_lokasi_barang($peruntukan, $search, $params, $start, $limit);

        $this->print_result_json($result, $this->test);
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_barang_per_lokasi($kdLokasi = "", $kdBlok = "", $kdSubBlok = ""){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
        $result = $this->lokasi_per_barang_model->get_barang_per_lokasi($kdLokasi, $kdBlok, $kdSubBlok, $search, $start, $limit);

        echo $result;
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_row(){
		if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
			$id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id',TRUE)) : NULL;
            $result = $this->lokasi_per_barang_model->get_row($id);

            return $result;
        }
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
        $result = '{"success":true,"errMsg":""}';

        echo $result;
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_rows(){
		$result = '{"success":true,"errMsg":""}';

        echo $result;
	}
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row(){
		$result = '{"success":true,"errMsg":""}';

        echo $result;
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all(){
		$result = $this->lokasi_per_barang_model->get_all();

        echo $result;
	}

    public function get_kategori1() {
        $this->print_result_json($this->lokasi_per_barang_model->get_kategori1(), $this->test);
    }

    public function get_kategori2() {
        $kategori1 = $this->form_data('kategori1');
        $this->print_result_json($this->lokasi_per_barang_model->get_kategori2($kategori1), $this->test);
    }

    public function get_kategori3() {
        $kategori1 = $this->form_data('kategori1');
        $kategori2 = $this->form_data('kategori2');
        $this->print_result_json($this->lokasi_per_barang_model->get_kategori3($kategori1, $kategori2), $this->test);
    }

    public function get_kategori4() {
        $kategori1 = $this->form_data('kategori1');
        $kategori2 = $this->form_data('kategori2');
        $kategori3 = $this->form_data('kategori3');
        $this->print_result_json($this->lokasi_per_barang_model->get_kategori4($kategori1, $kategori2, $kategori3), $this->test);
    }

    public function get_peruntukan() {
        $result = array();
//        $peruntukan = $this->form_data('peruntukan');
        $peruntukan = intval($this->session->userdata('user_peruntukan'));
        if($peruntukan == 0 || $peruntukan == 2) {
            $result[] = array('kd_peruntukan' => 0,  'nama_peruntukan' => 'Supermarket');
        }
        if($peruntukan == 1 || $peruntukan == 2) {
            $result[] = array('kd_peruntukan' => 1,  'nama_peruntukan' => 'Distribusi');
        }
        if($peruntukan == 2) {
            $result[] = array('kd_peruntukan' => 2,  'nama_peruntukan' => 'All');
        }
        header('Content-Type: application/json');
        echo json_encode(array('success' => true, 'data' => $result));
    }

    public function get_report() {
        $parameter = null;
        $params = array(
            'lokasi'    => $this->form_data('kd_lokasi'),
            'blok'      => $this->form_data('kd_blok'),
            'subblok'   => $this->form_data('kd_sub_blok'),
            'ukuran'    => $this->form_data('kd_ukuran'),
            'satuan'    => $this->form_data('kd_satuan'),
            'peruntukan'=> $this->form_data('nama_peruntukan'),
            'kategori1' => $this->form_data('nama_kategori1'),
            'kategori2' => $this->form_data('nama_kategori2'),
            'kategori3' => $this->form_data('nama_kategori3'),
            'kategori4' => $this->form_data('nama_kategori4'),
            'supplier'  => $this->form_data('kd_supplier'),
            'creator'   => $this->session->userdata('username')
        );
        if(!empty($params['kategori4']) && strlen($params['kategori4']) === 8) {
            $params['kategori1'] = substr( $params['kategori4'], 0, 1);
            $params['kategori2'] = substr( $params['kategori4'], 1, 2);
            $params['kategori3'] = substr( $params['kategori4'], 3, 3);
            $params['kategori4'] = substr( $params['kategori4'], 6, 2);
        } elseif(!empty($params['kategori3']) && strlen($params['kategori3']) === 6) {
            $params['kategori1'] = substr( $params['kategori3'], 0, 1);
            $params['kategori2'] = substr( $params['kategori3'], 1, 2);
            $params['kategori3'] = substr( $params['kategori3'], 3, 3);
        } elseif(!empty($params['kategori2']) && strlen($params['kategori2']) === 3) {
            $params['kategori1'] = substr( $params['kategori2'], 0, 1);
            $params['kategori2'] = substr( $params['kategori2'], 1, 2);
        }
        foreach ($params as $key => $value) {
            if(!empty($value)) $parameter .= "&$key=$value";
        }
        $reportURL = BIRT_BASE_URL . '/frameset?__report=report/lokasi_per_barang.rptdesign' . $parameter;
        echo '{"success":true, "errMsg":"", "successMsg":"Siapkan kertas Letter (Continuous Form)", "printUrl":"' . $reportURL . '"}';
    }
}