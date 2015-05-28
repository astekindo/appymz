<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class List_barang extends MY_Controller {
    
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('list_barang_model');
    }
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_rows(){
                $kd_kategori1 = isset($_POST['kd_kategori1']) ? $this->db->escape_str($this->input->post('kd_kategori1',TRUE)) : '';
		$kd_kategori2 = isset($_POST['kd_kategori2']) ? $this->db->escape_str($this->input->post('kd_kategori2',TRUE)) : '';
		$kd_kategori3 = isset($_POST['kd_kategori3']) ? $this->db->escape_str($this->input->post('kd_kategori3',TRUE)) : '';
		$kd_kategori4 = isset($_POST['kd_kategori4']) ? $this->db->escape_str($this->input->post('kd_kategori4',TRUE)) : '';
                $kd_ukuran = isset($_POST['kd_ukuran']) ? $this->db->escape_str($this->input->post('kd_ukuran',TRUE)) : '';
                $kd_satuan = isset($_POST['kd_satuan']) ? $this->db->escape_str($this->input->post('kd_satuan',TRUE)) : '';
                $kd_supplier = isset($_POST['kd_supplier']) ? $this->db->escape_str($this->input->post('kd_supplier',TRUE)) : '';
                $is_konsinyasi = isset($_POST['is_konsinyasi']) ? $this->db->escape_str($this->input->post('is_konsinyasi',TRUE)) : '';
                
		$list = isset($_POST['list']) ? $this->db->escape_str($this->input->post('list',TRUE)) : '';
		$start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start',TRUE)) : 0;
		$limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit',TRUE)) : $this->config->item("length_records");
                $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : '';
		
		
       // $result = $this->list_barang_model->get_rows($search, $start, $limit);
        if($is_konsinyasi === 'false'){
			$is_konsinyasi = 0;
		}else{
			$is_konsinyasi = 1;
		}
        //echo $result;
        if($list != ''){
			$list_exp = explode(',',$list);
			$list_imp = implode("','",$list_exp);
			$list = strtoupper("'".$list_imp."'");
		}
		
		$data_result = $this->list_barang_model->get_rows($kd_kategori1,$kd_kategori2,$kd_kategori3,$kd_kategori4,$kd_ukuran,$kd_satuan,$kd_supplier,$is_konsinyasi,$search,$start,$limit);
		
		$hasil = $data_result['rows'];
		//$results = array();
		
		echo $data_result;
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_row(){
		if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
			$id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id',TRUE)) : NULL;
            $result = $this->list_barang_model->get_row($id);
            
            return $result;
        }
	}
	
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function update_row(){
		// $kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : FALSE;
		$kd_kategori1 = isset($_POST['nama_kategori1']) ? $this->db->escape_str($this->input->post('nama_kategori1',TRUE)) : FALSE;
		$kd_kategori2 = isset($_POST['nama_kategori2']) ? $this->db->escape_str($this->input->post('nama_kategori2',TRUE)) : FALSE;
		$kd_kategori3 = isset($_POST['nama_kategori3']) ? $this->db->escape_str($this->input->post('nama_kategori3',TRUE)) : FALSE;
		$kd_kategori4 = isset($_POST['nama_kategori4']) ? $this->db->escape_str($this->input->post('nama_kategori4',TRUE)) : FALSE;
		
		$no_urut = $this->list_barang_model->get_kode_sequence("BRG".$kd_kategori1.$kd_kategori2.$kd_kategori3.$kd_kategori4,3);
		$thn_reg = date('y');
		
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : FALSE;
		
		$nama_produk = isset($_POST['nama_produk']) ? $this->db->escape_str($this->input->post('nama_produk',TRUE)) : FALSE;
		$kd_produk_lama = isset($_POST['kd_produk_lama']) ? $this->db->escape_str($this->input->post('kd_produk_lama',TRUE)) : FALSE;
		$kd_produk_supp = isset($_POST['kd_produk_supp']) ? $this->db->escape_str($this->input->post('kd_produk_supp',TRUE)) : FALSE;
		$kd_satuan = isset($_POST['kd_satuan']) ? $this->db->escape_str($this->input->post('kd_satuan',TRUE)) : FALSE;
		$kd_peruntukkan = isset($_POST['kd_peruntukkan']) ? $this->db->escape_str($this->input->post('kd_peruntukkan',TRUE)) : FALSE;
		$hrg_supplier = isset($_POST['hrg_supplier']) ? $this->db->escape_str($this->input->post('hrg_supplier',TRUE)) : FALSE;
		$hrg_hpp = isset($_POST['hrg_hpp']) ? $this->db->escape_str($this->input->post('hrg_hpp',TRUE)) : FALSE;
		$hrg_jual = isset($_POST['hrg_jual']) ? $this->db->escape_str($this->input->post('hrg_jual',TRUE)) : FALSE;
		$disk_persen_kons1 = isset($_POST['disk_persen_kons1']) ? $this->db->escape_str($this->input->post('disk_persen_kons1',TRUE)) : FALSE;
		$disk_persen_kons2 = isset($_POST['disk_persen_kons2']) ? $this->db->escape_str($this->input->post('disk_persen_kons2',TRUE)) : FALSE;
		$disk_persen_kons3 = isset($_POST['disk_persen_kons3']) ? $this->db->escape_str($this->input->post('disk_persen_kons3',TRUE)) : FALSE;
		$disk_persen_kons4 = isset($_POST['disk_persen_kons4']) ? $this->db->escape_str($this->input->post('disk_persen_kons4',TRUE)) : FALSE;
		$disk_amt_kons1 = isset($_POST['disk_amt_kons1']) ? $this->db->escape_str($this->input->post('disk_amt_kons1',TRUE)) : FALSE;
		$disk_amt_kons2 = isset($_POST['disk_amt_kons2']) ? $this->db->escape_str($this->input->post('disk_amt_kons2',TRUE)) : FALSE;
		$disk_amt_kons3 = isset($_POST['disk_amt_kons3']) ? $this->db->escape_str($this->input->post('disk_amt_kons3',TRUE)) : FALSE;
		$disk_amt_kons4 = isset($_POST['disk_amt_kons4']) ? $this->db->escape_str($this->input->post('disk_amt_kons4',TRUE)) : FALSE;
		$min_stok = isset($_POST['min_stok']) ? $this->db->escape_str($this->input->post('min_stok',TRUE)) : FALSE;
		$max_stok = isset($_POST['max_stok']) ? $this->db->escape_str($this->input->post('max_stok',TRUE)) : FALSE;
		$min_order = isset($_POST['min_order']) ? $this->db->escape_str($this->input->post('min_order',TRUE)) : FALSE;
		$qty_beli_bonus = isset($_POST['qty_beli_bonus']) ? $this->db->escape_str($this->input->post('qty_beli_bonus',TRUE)) : FALSE;
		$kd_produk_bonus = isset($_POST['kd_produk_bonus']) ? $this->db->escape_str($this->input->post('kd_produk_bonus',TRUE)) : FALSE;
		$qty_bonus = isset($_POST['qty_bonus']) ? $this->db->escape_str($this->input->post('qty_bonus',TRUE)) : FALSE;
		$is_bonus_kelipatan = isset($_POST['is_bonus_kelipatan']) ? $this->db->escape_str($this->input->post('is_bonus_kelipatan',TRUE)) : '0'; 
		$disk_persen_member1 = isset($_POST['disk_persen_member1']) ? $this->db->escape_str($this->input->post('disk_persen_member1',TRUE)) : FALSE;
		$disk_persen_member2 = isset($_POST['disk_persen_member2']) ? $this->db->escape_str($this->input->post('disk_persen_member2',TRUE)) : FALSE;
		$disk_persen_member3 = isset($_POST['disk_persen_member3']) ? $this->db->escape_str($this->input->post('disk_persen_member3',TRUE)) : FALSE; 
		$disk_persen_member4 = isset($_POST['disk_persen_member4']) ? $this->db->escape_str($this->input->post('disk_persen_member4',TRUE)) : FALSE;
		$disk_amt_member1 = isset($_POST['disk_amt_member1']) ? $this->db->escape_str($this->input->post('disk_amt_member1',TRUE)) : FALSE;
		$disk_amt_member2 = isset($_POST['disk_amt_member2']) ? $this->db->escape_str($this->input->post('disk_amt_member2',TRUE)) : FALSE;
		$disk_amt_member3 = isset($_POST['disk_amt_member3']) ? $this->db->escape_str($this->input->post('disk_amt_member3',TRUE)) : FALSE; 
		$disk_amt_member4 = isset($_POST['disk_amt_member4']) ? $this->db->escape_str($this->input->post('disk_amt_member4',TRUE)) : FALSE;
		$qty_beli_member = isset($_POST['qty_beli_member']) ? $this->db->escape_str($this->input->post('qty_beli_member',TRUE)) : FALSE;
		$kd_produk_member = isset($_POST['kd_produk_member']) ? $this->db->escape_str($this->input->post('kd_produk_member',TRUE)) : FALSE;
		$qty_member = isset($_POST['qty_member']) ? $this->db->escape_str($this->input->post('qty_member',TRUE)) : FALSE;
		$is_member_kelipatan = isset($_POST['is_member_kelipatan']) ? $this->db->escape_str($this->input->post('is_member_kelipatan',TRUE)) : FALSE;
		$is_bonus = isset($_POST['is_bonus']) ? $this->db->escape_str($this->input->post('is_bonus',TRUE)) : '0';
		$aktif = '1';
		
		if ( ! $kd_produk) { //save  
			$created_by = $this->session->userdata('username');
			$created_date = date('Y-m-d H:i:s');          
            
            $data = array(
				'kd_produk' => $kd_kategori1.$kd_kategori2.$kd_kategori3.$kd_kategori4.$thn_reg.$no_urut,
				'kd_kategori1' => $kd_kategori1,
				'kd_kategori2' => $kd_kategori2,
				'kd_kategori3' => $kd_kategori3,
				'kd_kategori4' => $kd_kategori4,
				'thn_reg' => $thn_reg,
				'no_urut' => $no_urut,
				'nama_produk' => $nama_produk,
				'kd_produk_lama' => $kd_produk_lama,
				'kd_produk_supp' => $kd_produk_supp,
				'kd_satuan' => $kd_satuan,
				'kd_peruntukkan' => $kd_peruntukkan,
				'hrg_supplier' => $hrg_supplier,
				'hrg_hpp' => $hrg_hpp,
				'hrg_jual' => $hrg_jual,
				'disk_persen_kons1' => $disk_persen_kons1,
				'disk_persen_kons2' => $disk_persen_kons2,
				'disk_persen_kons3' => $disk_persen_kons3,
				'disk_persen_kons4' => $disk_persen_kons4,
				'disk_amt_kons1' => $disk_amt_kons1,
				'disk_amt_kons2' => $disk_amt_kons2,
				'disk_amt_kons3' => $disk_amt_kons3,
				'disk_amt_kons4' => $disk_amt_kons4,
				'min_stok' => $min_stok,
				'max_stok' => $max_stok,
				'qty_beli_bonus'=>	$qty_beli_bonus,
				'kd_produk_bonus'=>	$kd_produk_bonus,
				'qty_bonus'=>	$qty_bonus,
				'is_bonus_kelipatan'=> 	$is_bonus_kelipatan,
				'disk_persen_member1'=>	$disk_persen_member1,
				'disk_persen_member2'=>	$disk_persen_member2,
				'disk_persen_member3'=> $disk_persen_member3,
				'disk_persen_member4'=>	$disk_persen_member4,
				'disk_amt_member1'=>	$disk_amt_member1,
				'disk_amt_member2'=>	$disk_amt_member2,
				'disk_amt_member3'=> 	$disk_amt_member3,
				'disk_amt_member4'=>	$disk_amt_member4,
				'qty_beli_member'=>	$qty_beli_member,
				'kd_produk_member'=>	$kd_produk_member,
				'qty_member'=>	$qty_member,
				'is_member_kelipatan'=>	$is_member_kelipatan,
				'is_bonus' =>	$is_bonus,
				'min_order' => $min_order,
				'created_by' => $created_by,
				'created_date' => $created_date,
                'aktif' => $aktif
            );
			
            if ($this->list_barang_model->insert_row($data)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
            
        } else { //edit            
			$updated_by = $this->session->userdata('username');
			$updated_date = date('Y-m-d H:i:s');
			        
           	$datau = array(
				'nama_produk' => $nama_produk,
				'kd_produk_lama' => $kd_produk_lama,
				'kd_produk_supp' => $kd_produk_supp,
				'kd_peruntukkan' => $kd_peruntukkan,
				'hrg_supplier' => $hrg_supplier,
				'hrg_hpp' => $hrg_hpp,
				'hrg_jual' => $hrg_jual,
				'disk_persen_kons1' => $disk_persen_kons1,
				'disk_persen_kons2' => $disk_persen_kons2,
				'disk_persen_kons3' => $disk_persen_kons3,
				'disk_persen_kons4' => $disk_persen_kons4,
				'disk_amt_kons1' => $disk_amt_kons1,
				'disk_amt_kons2' => $disk_amt_kons2,
				'disk_amt_kons3' => $disk_amt_kons3,
				'disk_amt_kons4' => $disk_amt_kons4,
				'min_stok' => $min_stok,
				'max_stok' => $max_stok,
				'min_order' => $min_order,
				'qty_beli_bonus'=>	$qty_beli_bonus,
				'kd_produk_bonus'=>	$kd_produk_bonus,
				'qty_bonus'=>	$qty_bonus,
				'is_bonus_kelipatan'=> 	$is_bonus_kelipatan,
				'disk_persen_member1'=>	$disk_persen_member1,
				'disk_persen_member2'=>	$disk_persen_member2,
				'disk_persen_member3'=> $disk_persen_member3,
				'disk_persen_member4'=>	$disk_persen_member4,
				'disk_amt_member1'=>	$disk_amt_member1,
				'disk_amt_member2'=>	$disk_amt_member2,
				'disk_amt_member3'=> 	$disk_amt_member3,
				'disk_amt_member4'=>	$disk_amt_member4,
				'qty_beli_member'=>	$qty_beli_member,
				'kd_produk_member'=>	$kd_produk_member,
				'qty_member'=>	$qty_member,
				'is_member_kelipatan'=>	$is_member_kelipatan,
				'is_bonus' =>	$is_bonus,
				'updated_by'	=>	$updated_by,
				'updated_date'	=>	$updated_date,
                'aktif' => $aktif
            );
           
            if ($this->list_barang_model->update_row($kd_produk, $datau)) {
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
	                if ($this->list_barang_model->delete_row($id)) {
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
		$kd_produk = isset($_POST['kd_produk']) ? $this->db->escape_str($this->input->post('kd_produk',TRUE)) : FALSE;
		
		if ($this->list_barang_model->delete_row($kd_produk)) {
			$result = '{"success":true,"errMsg":""}';
        } else {
			$result = '{"success":false,"errMsg":"Process Failed.."}';
		}
		echo $result;
	}	
	
	public function get_kategori4($kd_kategori1 = '',$kd_kategori2 = '',$kd_kategori3 = ''){
		$result = $this->list_barang_model->get_kategori4($kd_kategori1,$kd_kategori2,$kd_kategori3);
        echo $result;
	}
	
	public function get_satuan(){
		$result = $this->list_barang_model->get_satuan();
        echo $result;
	}
        
}