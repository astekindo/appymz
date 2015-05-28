<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master_member extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('master_member_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_rows(){
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		
        $result = $this->master_member_model->get_rows($search, $start, $limit);
        
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
            $result = $this->master_member_model->get_row($id);
            
            return $result;
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
		$kdcab = isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang',TRUE)) : FALSE;
		$kd_member = isset($_POST['kd_member']) ? $this->db->escape_str($this->input->post('kd_member',TRUE)) : FALSE;
		$nmmember = isset($_POST['nmmember']) ? $this->db->escape_str($this->input->post('nmmember',TRUE)) : FALSE;
		$jenis = isset($_POST['jenis']) ? $this->db->escape_str($this->input->post('jenis',TRUE)) : FALSE;
		$idtype = isset($_POST['idtype']) ? $this->db->escape_str($this->input->post('idtype',TRUE)) : FALSE;
		$idno = isset($_POST['idno']) ? $this->db->escape_str($this->input->post('idno',TRUE)) : FALSE;
		$kelamin = isset($_POST['jenis_kelamin']) ? $this->db->escape_str($this->input->post('jenis_kelamin',TRUE)) : FALSE;
		$telepon = isset($_POST['telepon']) ? $this->db->escape_str($this->input->post('telepon',TRUE)) : FALSE;
		$kdprop = isset($_POST['kd_propinsi']) ? $this->db->escape_str($this->input->post('kd_propinsi',TRUE)) : FALSE;
		$kota = isset($_POST['kd_kota']) ? $this->db->escape_str($this->input->post('kd_kota',TRUE)) : FALSE;
		$kecamatan = isset($_POST['kd_kecamatan']) ? $this->db->escape_str($this->input->post('kd_kecamatan',TRUE)) : FALSE;
		$kelurahan = isset($_POST['kd_kelurahan']) ? $this->db->escape_str($this->input->post('kd_kelurahan',TRUE)) : FALSE;
		$kodepos = isset($_POST['kodepos']) ? $this->db->escape_str($this->input->post('kodepos',TRUE)) : FALSE;
		$alamat_pengiriman = isset($_POST['alamat_pengiriman']) ? $this->db->escape_str($this->input->post('alamat_pengiriman',TRUE)) : FALSE;
		$total_point = isset($_POST['total_point']) ? $this->db->escape_str($this->input->post('total_point',TRUE)) : FALSE;
		$tmplahir = isset($_POST['tmplahir']) ? $this->db->escape_str($this->input->post('tmplahir',TRUE)) : FALSE;
		$tgllahir = isset($_POST['tgllahir']) ? $this->db->escape_str($this->input->post('tgllahir',TRUE)) : FALSE;
		$agama = isset($_POST['agama']) ? $this->db->escape_str($this->input->post('agama',TRUE)) : FALSE;
		$tgljoin = isset($_POST['tgljoin']) ? $this->db->escape_str($this->input->post('tgljoin',TRUE)) : FALSE;
		$sdtgl = isset($_POST['sdtgl']) ? $this->db->escape_str($this->input->post('sdtgl',TRUE)) : FALSE;
		$teleponk = isset($_POST['teleponkantor']) ? $this->db->escape_str($this->input->post('teleponkantor',TRUE)) : FALSE;
		$hp = isset($_POST['hp']) ? $this->db->escape_str($this->input->post('hp',TRUE)) : FALSE;
		$email = isset($_POST['email']) ? $this->db->escape_str($this->input->post('email',TRUE)) : FALSE;
		$fax = isset($_POST['fax']) ? $this->db->escape_str($this->input->post('fax',TRUE)) : FALSE;
		$status = isset($_POST['status']) ? $this->db->escape_str($this->input->post('status',TRUE)) : FALSE;
		$alamat_penagihan = isset($_POST['alamat_penagihan']) ? $this->db->escape_str($this->input->post('alamat_penagihan',TRUE)) : FALSE;
		$npwp = isset($_POST['npwp']) ? $this->db->escape_str($this->input->post('npwp',TRUE)) : FALSE;
		$alamat_npwp = isset($_POST['alamat_npwp']) ? $this->db->escape_str($this->input->post('alamat_npwp',TRUE)) : FALSE;
		$is_pelanggan_dist = isset($_POST['is_pelanggan_dist']) ? $this->db->escape_str($this->input->post('is_pelanggan_dist',TRUE)) : FALSE;
		$limit_dist = isset($_POST['limit_dist']) ? $this->db->escape_str($this->input->post('limit_dist',TRUE)) : FALSE;
		$top_dist = isset($_POST['top_dist']) ? $this->db->escape_str($this->input->post('top_dist',TRUE)) : FALSE;
		$total_point = isset($_POST['total_point']) ? $this->db->escape_str($this->input->post('total_point',TRUE)) : FALSE;
		$aktif = isset($_POST['aktif']) ? $this->db->escape_str($this->input->post('aktif',TRUE)) : FALSE;
		if($aktif=='0')
			$aktif = 'FALSE';
		else $aktif = 'TRUE';
		
		// $aktif = '1';
		
		if ( ! $kd_member) { //save    
			$created_by = $this->session->userdata('username');
			$created_date = date('Y-m-d H:i:s');        
            
            $data = array(
				'kd_member' => $kdcab.date('ym').$this->master_member_model->get_kode_sequence("M",4),
				'nmmember' => $nmmember,
				'jenis' => $jenis,
				'idtype' => $idtype,
				'idno' => $idno,
				'jenis_kelamin' => $kelamin,
				'telepon' => $telepon,
                'kd_propinsi' => $kdprop,
				'kd_kota' => $kota,
				'kd_kecamatan' => $kecamatan,
				'kd_kelurahan' => $kelurahan,
				'kodepos' => $kodepos,
				'alamat_pengiriman' => $alamat_pengiriman,
				'tmplahir' => $tmplahir,
				'tgllahir' => $tgllahir,
				'agama' => $agama,
				'tgljoin' => $tgljoin,
				'sdtgl' => $sdtgl,
				'teleponkantor' => $teleponk,
				'hp' => $hp,
				'email' => $email,
				'fax' => $fax,
				'status' => $status,
				'alamat_penagihan' => $alamat_penagihan,
                'aktif' => $aktif,
                'kd_cabang' => $kdcab,
                'npwp' => $npwp,
                'alamat_npwp' => $alamat_npwp,
                /*'is_pelanggan_dist' => $is_pelanggan_dist,
                'limit_dist' => $limit_dist,
                'top_dist' => $top_dist,
                'total_point' => $total_point,*/
				'created_by'	=>	$created_by,
				'created_date'	=>	$created_date
            );
			
            if ($this->master_member_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit        
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			            
           	$datau = array(
				'nmmember' => $nmmember,
				'jenis' => $jenis,
				'idtype' => $idtype,
				'idno' => $idno,
				'jenis_kelamin' => $kelamin,
				'telepon' => $telepon,
                'kd_propinsi' => $kdprop,
				'kd_kota' => $kota,
				'kd_kecamatan' => $kecamatan,
				'kd_kelurahan' => $kelurahan,
				'kodepos' => $kodepos,
				'alamat_pengiriman' => $alamat_pengiriman,
				'tmplahir' => $tmplahir,
				'tgllahir' => $tgllahir,
				'agama' => $agama,
				'tgljoin' => $tgljoin,
				'sdtgl' => $sdtgl,
				'teleponkantor' => $teleponk,
				'hp' => $hp,
				'email' => $email,
				'fax' => $fax,
				'status' => $status,
				'alamat_penagihan' => $alamat_penagihan,
                'aktif' => $aktif,
                'kd_cabang' => $kdcab,
                'npwp' => $npwp,
                'alamat_npwp' => $alamat_npwp,
                /*'is_pelanggan_dist' => $is_pelanggan_dist,
                'limit_dist' => $limit_dist,
                'top_dist' => $top_dist,
                'total_point' => $total_point,*/
				'updated_by'	=>	$updated_by,
				'updated_date'	=>	$updated_date
            );
           
            if ($this->master_member_model->update_row($kd_member, $datau)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        }       
        
        echo $result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_rows(){
		$postdata = isset($_POST['postdata']) ? $this->input->post('postdata',TRUE) : array();
		
		if(count($postdata) > 0){
			$records = explode(';', $this->input->post('postdata'));
	        $i = 0;
	        foreach ($records as $id) {
	            if ($id != '') {
	                
	                $this->db->trans_start();
	                if ($this->master_member_model->delete_row($id)) {
	                    $i++;
	                }
	                $this->db->trans_complete();
	            }
	        
	        }
	        if ($i > 0) {
	            $result = '{"success":true,"errMsg":""}';
	        } else {
	            $result = '{"success":false,"errMsg":"Process Failed.."}';
	        }
	        echo $result;
		}		
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function delete_row(){
		$kd_member = isset($_POST['kd_member']) ? $this->db->escape_str($this->input->post('kd_member',TRUE)) : FALSE;
		
		if ($this->master_member_model->delete_row($kd_member)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}
	
	public function get_jenis(){
	
		$result = '{success:true,data:[{"jenis":"1","jenismember":"GOLD"},{"jenis":"2","jenismember":"SILVER"},{"jenis":"3","jenismember":"PLATINUM"}]}';
		echo $result;
	}
	
	//cboJenisID
	public function get_jenisid(){
	
		$result = '{success:true,data:[{"idtype":"1","jenisid":"KTP"},{"idtype":"2","jenisid":"SIM"},{"idtype":"3","jenisid":"PASSPORT"},{"idtype":"4","jenisid":"KARTU PELAJAR"}]}';
		echo $result;
	}
	
	public function get_agama(){
	
		$result = '{success:true,data:[{"id":"1","agama":"Budha"},{"id":"2","agama":"Hindu"},{"id":"3","agama":"Islam"},{"id":"4","agama":"Katolik"},{"id":"5","agama":"Kristian"},{"id":"6","agama":"Yahudi"}]}';
		echo $result;
	}
	
	public function get_Cab(){
		$result = $this->master_member_model->get_Cab();
        echo $result;
	}
	public function get_prop(){
		$result = $this->master_member_model->get_prop();
        echo $result;
	}
	
	public function get_kota($prop=''){
		$result = $this->master_member_model->get_kota($prop);
        echo $result;
	}
	
	public function get_kec($prop='' , $kota=''){
		$result = $this->master_member_model->get_kec($prop,$kota);
        echo $result;
	}
	
	public function get_kel($prop='' , $kota='', $kec = ''){
		$result = $this->master_member_model->get_kel($prop,$kota,$kec);
        echo $result;
	}
	
	public function get_histo1($kd_member=''){
		$result = $this->master_member_model->get_histo1($kd_member);
        echo $result;
	}
	
	public function get_histo2($no_so=''){
		if($no_so === ''){
			$no_so = isset($_POST['no_so']) ? $this->db->escape_str($this->input->post('no_so',TRUE)) : FALSE;
		}
		
		$filter = isset($_POST['filter']) ? $this->db->escape_str($this->input->post('filter',TRUE)) : FALSE;
		$kd_kategori1 = isset($_POST['kd_kategori1']) ? $this->db->escape_str($this->input->post('kd_kategori1',TRUE)) : FALSE;
		$kd_kategori2 = isset($_POST['kd_kategori2']) ? $this->db->escape_str($this->input->post('kd_kategori2',TRUE)) : FALSE;
		$kd_kategori3 = isset($_POST['kd_kategori3']) ? $this->db->escape_str($this->input->post('kd_kategori3',TRUE)) : FALSE;
		$kd_kategori4 = isset($_POST['kd_kategori4']) ? $this->db->escape_str($this->input->post('kd_kategori4',TRUE)) : FALSE;
		$dari = isset($_POST['dari']) ? $this->db->escape_str($this->input->post('dari',TRUE)) : FALSE;
		$sampai = isset($_POST['sampai']) ? $this->db->escape_str($this->input->post('sampai',TRUE)) : FALSE;
		
		$dari = date('Y-m-d', strtotime($dari));
		$sampai = date('Y-m-d', strtotime($sampai));
		
		if($filter == '1')
			$result = $this->master_member_model->get_histo2_by_filter($no_so, $kd_kategori1, $kd_kategori2, $kd_kategori3, $kd_kategori4, $dari, $sampai);				
		else
			$result = $this->master_member_model->get_histo2($no_so);
        echo $result;
	}
}