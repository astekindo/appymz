<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('member_models');
        
    }
	
	function convertDate($date) {
       // EN-Date to GE-Date
       if (strstr($date, "/") || strstr($date, "/"))   {
               $date = preg_split("/[\/]|[-]+/", $date);
               $date = $date[2]."/".$date[1]."/".$date[0];
               return $date;
       }
       return false;
	}	
    public function index()
    {
        $judul=$this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Home - Master - Member'
        );
        if($this->session->userdata('username')){
            $res_menu=$this->menu_models->menu_content();            
            $data['menu']=$res_menu;
            $res_member = $this->member_models->member_content();
            $data['rcmember']=$res_member;
            $this->load->view('page/member', $data);
        }else{
            $this->load->view('utama', $data);
        }

    }
    public function getData()
    {//NMMEMBER,JENIS, alamat_rumah, KOTA , KODEPOS, TELEPON, EMAIL
        $query="select * from mst.tm_member
            where aktif = true";
        $this->getdata->listtable($this->field, $query, 'member/form','member/delete','all');
    }
    
    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->member_models->getData($this->uri->segment(3));
                foreach ($query as $row) {
                        $data = $row;
                }
            } else {
                $data['id_member'] = '';			
                $data['kd_member'] = str_pad($this->member_models->get_last_records()+1,3,"0",STR_PAD_LEFT);;
                $data['nmmember'] = '';
                $data['alamat_rumah'] = '';			
                $data['telepon'] = '';
                $data['hp'] = '';
                $data['jenis'] = '';
                $data['sdtgl'] = '';			
                $data['tgljoin'] = '';			
                $data['tgllahir'] = '';			
                $data['idno'] = '';			
                $data['status'] = '';			
                $data['tmplahir'] = '';			
                $data['agama'] = '';			
                $data['kelamin'] = '';			
                $data['kelurahan'] = '';			
                $data['kecamatan'] = '';			
                $data['kota'] = '';
                $data['kodepos'] = '';			
                $data['fax'] = '';			
                $data['email'] = '';			
                $data['profesi'] = '';			
                $data['nmpersh'] = '';			
                $data['alamat_kantor'] = '';			
                $data['teleponk'] = '';		
                $data['faxk'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Home - Master - Member';
        }
        if ($this->session->userdata('username')) {
            $this->load->view('form/member', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }
    
    public function save() {
			$data = array(
                'kd_member' => $this->input->post('kd_member'),
                'nmmember' => $this->input->post('nmmember'),
                'alamat_rumah' => $this->input->post('alamat_rumah'),
                'telepon' => $this->input->post('telepon'),
                'hp' => $this->input->post('hp'),
                'jenis' => $this->input->post('jenis'),
                'sdtgl' => $this->convertDate($this->input->post('sdtgl')),
                'tgljoin' => $this->convertDate($this->input->post('tgljoin')),
                'tgllahir' => $this->convertDate($this->input->post('tgllahir')),
                'idno' => $this->input->post('idno'),
                'status' => $this->input->post('status'),
                'tmplahir' => $this->input->post('tmplahir'),
                'agama' => $this->input->post('agama'),
                'kelamin' => $this->input->post('kelamin'),
                'kelurahan' => $this->input->post('kelurahan'),
                'kecamatan' => $this->input->post('kecamatan'),
                'kota' => $this->input->post('kota'),
                'kodepos' => $this->input->post('kodepos'),
                'fax' => $this->input->post('fax'),
                'email' => $this->input->post('email'),
                'profesi' => $this->input->post('profesi'),
                'nmpersh' => $this->input->post('nmpersh'),
                'alamat_kantor' => $this->input->post('alamat_kantor'),
                'teleponk' => $this->input->post('teleponk'),
                'faxk' => $this->input->post('faxk'),
                'aktif' => '1',
            );


            if ($this->input->post('id_member')=="") {
                $this->member_models->add_record($data);
            } else {
                $this->member_models->update_record($data, $this->input->post('id_member'));
            }
            redirect(base_url() . "member", "location");
        
    }
    
    public function delete() {
        $data = array(
            'aktif' => '0'
        );
        $this->load->model('member_models');
        $this->member_models->update_record($data, $this->uri->segment(3));
        $this->index();
    }
        
    /*public function delete() {
        $data = array(
            'status' => '1'
        );
        $this->load->model('supplier_models');
        $this->supplier_models->update_record($data, $this->uri->segment(3));
        $this->index();
    }*/
    
    /*public function deleteData($id)
    {
        $this->member_models->deleteData($id);
        redirect(base_url()."member", "location");
    }*/
}

