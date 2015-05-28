<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
	$this->load->model('auth_model');
    }

    public function get_rows() {
        $start = isset($_POST['start']) ? $this->db->escape_str($this->input->post('start', TRUE)) : 0;
        $limit = isset($_POST['limit']) ? $this->db->escape_str($this->input->post('limit', TRUE)) : $this->config->item("length_records");
        $search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query', TRUE)) : '';

        $result = $this->user_model->get_rows($search, $start, $limit);

        echo $result;
    }

    public function get_row() {
        if (isset($_POST['cmd']) && ($_POST['cmd'] == 'get')) {
            $id = isset($_POST['id']) ? $this->db->escape_str($this->input->post('id', TRUE)) : NULL;
            $result = $this->user_model->get_row($id);

            return $result;
        }
    }
    public function update_row() {

        $username = isset($_POST['username']) ? $this->db->escape_str($this->input->post('username', TRUE)) : FALSE;
        $passwd = isset($_POST['passwd']) ? $this->db->escape_str($this->input->post('passwd', TRUE)) : FALSE;
        $passwd2 = isset($_POST['passwd2']) ? $this->db->escape_str($this->input->post('passwd2', TRUE)) : FALSE;
        $email = isset($_POST['email']) ? $this->db->escape_str($this->input->post('email', TRUE)) : FALSE;      

        $kd_jabatan = isset($_POST['pkd_jabatan']) ? $this->db->escape_str($this->input->post('pkd_jabatan', TRUE)) : FALSE;
        $kd_kategori1 = isset($_POST['pkd_kategori1']) ? $this->db->escape_str($this->input->post('pkd_kategori1', TRUE)) : FALSE;
        $kd_kategori2 = isset($_POST['pkd_kategori2']) ? $this->db->escape_str($this->input->post('pkd_kategori2', TRUE)) : FALSE;
        $kd_kategori3 = isset($_POST['pkd_kategori3']) ? $this->db->escape_str($this->input->post('pkd_kategori3', TRUE)) : FALSE;
        $kd_kategori4 = isset($_POST['pkd_kategori4']) ? $this->db->escape_str($this->input->post('pkd_kategori4', TRUE)) : FALSE;
        $kd_group = isset($_POST['kd_group']) ? $this->db->escape_str($this->input->post('kd_group', TRUE)) : FALSE;

        $nama_lengkap = isset($_POST['nama_lengkap']) ? $this->db->escape_str($this->input->post('nama_lengkap', TRUE)) : FALSE;
        $gelar_akademis = isset($_POST['gelar_akademis']) ? $this->db->escape_str($this->input->post('gelar_akademis', TRUE)) : FALSE;
        $jns_kelamin = isset($_POST['rb-kelamin']) ? $this->db->escape_str($this->input->post('rb-kelamin', TRUE)) : FALSE;
        $tmp_lahir = isset($_POST['tmp_lahir']) ? $this->db->escape_str($this->input->post('tmp_lahir', TRUE)) : FALSE;
        $tgl_lahir = isset($_POST['tgl_lahir']) ? $this->db->escape_str($this->input->post('tgl_lahir', TRUE)) : FALSE;
        $no_ktp = isset($_POST['no_ktp']) ? $this->db->escape_str($this->input->post('no_ktp', TRUE)) : FALSE;
        $alamat = isset($_POST['alamat']) ? $this->db->escape_str($this->input->post('alamat', TRUE)) : FALSE;
        $foto = isset($_POST['foto']) ? $this->db->escape_str($this->input->post('foto', TRUE)) : FALSE;
        $agama = isset($_POST['agama']) ? $this->db->escape_str($this->input->post('agama', TRUE)) : FALSE;
        $no_npwp = isset($_POST['no_npwp']) ? $this->db->escape_str($this->input->post('no_npwp', TRUE)) : FALSE;
        $no_telp = isset($_POST['no_telp']) ? $this->db->escape_str($this->input->post('no_telp', TRUE)) : FALSE;
        $no_hp = isset($_POST['no_hp']) ? $this->db->escape_str($this->input->post('no_hp', TRUE)) : FALSE;
        $exec  = isset($_POST['pexec']) ? $this->db->escape_str($this->input->post('pexec', TRUE)) : FALSE;      


        $aktif = '1';
        

        if ($exec=='insert') { //save     
            $created_by = $this->session->userdata('username');
            $created_date = date('Y-m-d H:i:s');

	    $data = array(
                'username'=> $username,
                'passwd'=> $this->auth_model->md5_password($passwd),
                'passwd2'=> $this->auth_model->md5_password($passwd2),
                'email'=>$email,
                'created_by'=>$created_by,
                'created_date'=>$created_date,               
                'aktif'=>$aktif,
                'kd_jabatan'=>$kd_jabatan,
                'kd_kategori1'=>$kd_kategori1,
                'kd_kategori2'=>$kd_kategori2,
                'kd_kategori3'=>$kd_kategori3,
                'kd_kategori4'=>$kd_kategori4,
                'kd_group'=>$kd_group
            );
            $datai = array(
                'username'=>$username,
                'nama_lengkap'=>$nama_lengkap,
                'gelar_akademis'=>$gelar_akademis,
                'jns_kelamin'=>$jns_kelamin,
                'tmp_lahir'=>$tmp_lahir,
                'tgl_lahir'=>$tgl_lahir,
                'no_ktp'=>$no_ktp,
                'alamat'=>$alamat,
                'foto'=>$foto,
                'agama'=>$agama,
                'no_npwp'=>$no_npwp,
                'no_telp'=>$no_telp,
                'no_hp'=>$no_hp
            );
            
            if ($this->user_model->insert_row($data) && $this->user_model->insert_row_info($datai)) {
                $result = '{"success":true,"errMsg":""}';
            } else {
                $result = '{"success":false,"errMsg":"Process Failed.."}';
            }
        } else { //edit       
            $updated_by = $this->session->userdata('username');
            $updated_date = date('Y-m-d H:i:s');
            
            if(strlen($passwd) != 32 ){
                $passwd = $this->auth_model->md5_password($passwd);
            }
            
            if(strlen($passwd2) != 32 ){
                $passwd2 = $this->auth_model->md5_password($passwd2);
            }
            
            
	    $datau = array(               
                'passwd'=> $passwd,
                'passwd2'=> $passwd2,
                'email'=>$email,
                'updated_by'=>$updated_by,
                'updated_date'=>$updated_date,
                'aktif'=>$aktif,
                'kd_jabatan'=>$kd_jabatan,
                'kd_kategori1'=>$kd_kategori1,
                'kd_kategori2'=>$kd_kategori2,
                'kd_kategori3'=>$kd_kategori3,
                'kd_kategori4'=>$kd_kategori4,
                'kd_group'=>$kd_group
            );
            $dataui = array(
                'nama_lengkap'=>$nama_lengkap,
                'gelar_akademis'=>$gelar_akademis,
                'jns_kelamin'=>$jns_kelamin,
                'tmp_lahir'=>$tmp_lahir,
                'tgl_lahir'=>$tgl_lahir,
                'no_ktp'=>$no_ktp,
                'alamat'=>$alamat,
                'foto'=>$foto,
                'agama'=>$agama,
                'no_npwp'=>$no_npwp,
                'no_telp'=>$no_telp,
                'no_hp'=>$no_hp
            );

            if ($this->user_model->update_row($username, $datau)
                    && $this->user_model->update_row_info($username, $dataui)) {
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
    public function delete_rows() {
        $postdata = isset($_POST['postdata']) ? $this->input->post('postdata', TRUE) : array();

        if (count($postdata) > 0) {
            $records = explode(';', $this->input->post('postdata'));
            $i = 0;
            foreach ($records as $id) {
                if ($id != '') {

                    $this->db->trans_start();
                    if ($this->$username->delete_row($id)) {
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
    public function delete_row() {
        $username = isset($_POST['username']) ? $this->db->escape_str($this->input->post('username', TRUE)) : FALSE;

        if ($this->user_model->delete_row($username)) {
            $result = '{"success":true,"errMsg":""}';
        } else {
            $result = '{"success":false,"errMsg":"Process Failed.."}';
        }
        echo $result;
    }
	public function get_group(){
		$result = $this->user_model->get_group();
        echo $result;
	}

}

?>
