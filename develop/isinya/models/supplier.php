<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Supplier extends CI_Controller {
    
    private $field = array('nmsup','contax', 'telp', 'email');

    function __construct() {
        parent::__construct();
        $this->load->model('supplier_models');
    }

    public function index() {
        $data = array();
        $judul = $this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Home - Master - Supplier'
        );

        if ($this->session->userdata('username')) {
            $res_menu = $this->menu_models->menu_content();
            $data['menu'] = $res_menu;
            $res_supplier = $this->supplier_models->supplier_content();
            $data['rcsupplier']=$res_supplier;
            $this->load->view('page/supplier', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function getData() {
        $query = "SELECT * FROM mst.tm_supplier WHERE aktif = true";
        $this->getdata->listtable($this->field, $query, 'supplier/form', 'supplier/delete','all');
    }

    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->supplier_models->getData($this->uri->segment(3));
				foreach ($query as $row) {
					$data = $row;
				}
            } else {
				$data['id_supplier'] = '';			
                $data['kd_supplier'] = str_pad($this->supplier_models->get_last_records()+1,2,"0",STR_PAD_LEFT);
                $data['nama_supplier'] = '';
                $data['alias_supplier'] = '';
                $data['alamat'] = '';
                $data['telpon'] = '';
                $data['fax'] = '';
                $data['email'] = '';
                $data['pic'] = '';
                $data['pkp'] = '';
                $data['npwp'] = '';
                $data['status'] = '';
            }
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Home - Master - Supplier';
        }
        
        if ($this->session->userdata('username')) {
            $this->load->view('form/supplier', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function save() {
			$vpkp=$this->input->post('pkp');
			if ($vpkp=='on')
			{$vpkp='1';}
			else
			{$vpkp='0';};
			$vstatus=$this->input->post('status');
			if ($vstatus=='on')
			{$vstatus='1';}
			else
			{$vstatus='0';};

            $data['kd_supplier'] = $this->input->post('kd_supplier');
            $data['nama_supplier'] = $this->input->post('nama_supplier');
            $data['alias_supplier'] = $this->input->post('alias_supplier');
            $data['alamat'] = $this->input->post('alamat');
            $data['telpon'] = $this->input->post('telpon');
            $data['fax'] = $this->input->post('fax');
            $data['email'] = $this->input->post('email');
            $data['pic'] = $this->input->post('pic');
            $data['pkp'] = $vpkp; //substr($this->input->post('pkp'),1,1);
            $data['npwp'] = $this->input->post('npwp');
            $data['aktif'] = '1';
            $data['status'] = $vstatus;
			

            if ($this->input->post('id_supplier')=="") {
				$data['created_by'] = $this->session->userdata('username');
				$data['created_date'] = date('Y-m-d H:i:s');
                $this->supplier_models->add_record($data);
            } else {
				$data['updated_by'] = $this->session->userdata('username');
				$data['updated_date'] = date('Y-m-d H:i:s');
                $this->supplier_models->update_record($data, $this->input->post('id_supplier'));
            }
            redirect(base_url() . "supplier", "location");
        
    }

    public function delete() {
        $data = array(
            'aktif' => '0'
        );
        $this->load->model('supplier_models');
        $this->supplier_models->update_record($data, $this->uri->segment(3));
        $this->index();
    }

}

?>