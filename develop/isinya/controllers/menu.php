<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller {
    private $field=array('title', 'index_menu', 'parent_title', 'controller'); 
    function __construct()
    {
        parent::__construct();
        $this->load->model('menu_models');
        $this->load->model('login');
        $this->load->database();
        $this->load->model('fungsi');
        $this->load->model('getdata');  
    }
    public function index()
    {
        $judul=$this->config->item('judul');
        $data = array(
            'menu' => '',
            'nama' => $this->session->userdata('username'),
            'title' => $judul,
            'location' => 'Home - Setting - Menu'
        );
		
        if($this->session->userdata('username')){
            $res_menu=$this->menu_models->menu_content();
            $data['menu']=$res_menu;
            $rs_menu = $this->menu_models->tmenu_content();
            $data['rcmenu']=$rs_menu;
        }
        if($this->session->userdata('username')){
            $this->load->view('page/vmenu', $data);
        }
        else{
            $this->load->view('utama', $data);
        }
    }
    public function getData()
    {
        #$field=array('nik', 'nama_pegawai');
        $query="WITH RECURSIVE rqName (
					 nama_menu,ID_menu, id_parent, level, controller, sequence, arrHierarchy)
				 AS ( SELECT 
					 nama_menu, ID_menu, id_parent,1,controller,sequence,
					 ARRAY[coalesce(id_parent,0)]
				 FROM
					 mst.tm_menu
				 WHERE
					 id_parent = 0
				 UNION ALL
				   SELECT          
					 tn.nama_menu,
					 tn.id_menu,
					 tn.id_parent,
					 tp.LEVEL + 1,tn.controller,tn.sequence,
					 arrHierarchy || tn.id_menu
				 FROM
					 rqName tp, mst.tm_menu tn
				 WHERE
					 tp.id_menu = tn.id_parent
				 )
				 SELECT nama_menu, id_menu, id_parent,controller, sequence,
				 concat(CASE 
					 WHEN level = 1 THEN ''
					 WHEN level = 2 THEN '...' 
					 WHEN level = 3 THEN '......' 
					 WHEN level = 4 THEN '.........' 
					END,nama_menu) as desc
				   FROM rqName
				  ORDER BY arrHierarchy;";
        $this->getdata->listtable($this->field, $query, 'input_menu', 'menu');
    }

    public function form() {
        if ($this->session->userdata('username')) {
            $judul = $this->config->item('judul');
            if ($this->uri->segment(3)) {
                $query = $this->menu_models->getData($this->uri->segment(3));
                foreach ($query as $row) {
                        $data = $row;
                }
            } else {
                $data['id_menu'] = '';			
                $data['id_parent'] = '';
                $data['nama_menu'] = '';
                $data['deskripsi'] = '';			
                $data['controller'] = '';
                $data['bview'] = '';
                $data['binsert'] = '';
                $data['bupdate'] = '';			
                $data['bdelete'] = '';			
                $data['sequence'] = '';			
            }

			//Kategori 1
			$ambil_parent = $this->menu_models->get_parents();
			if(is_array($ambil_parent))
			{
				foreach ($ambil_parent as $barisparents)
				{
					$listparents[$barisparents->id_menu] = $barisparents->nama_menu;
				}

				$data['listparents'] = $listparents;
			}
			else
			{
				$data['listparents'] = array('0' => 'Tidak ada data');
			}
			
            $data['menu'] = $this->menu_models->menu_content();
            $data['nama'] = $this->session->userdata('username');
            $data['title'] = $judul;
            $data['location'] = 'Home - Master - Menu';
        }
        if ($this->session->userdata('username')) {
            $this->load->view('form/fmenu', $data);
        } else {
            $this->load->view('utama', $data);
        }
    }

    public function save() {
            $bview=$this->input->post('bview');
			if ($bview=='on') {$bview='1';} else {$bview='0';};
            $binsert=$this->input->post('binsert');
			if ($binsert=='on') {$binsert='1';} else {$binsert='0';};
            $bupdate=$this->input->post('bupdate');
			if ($bupdate=='on') {$bupdate='1';} else {$bupdate='0';};
            $bdelete=$this->input->post('bdelete');
			if ($bdelete=='on') {$bdelete='1';} else {$bdelete='0';};

			$data = array(
                'id_parent' => $this->input->post('id_parent'),
                'nama_menu' => $this->input->post('nama_menu'),
                'deskripsi' => $this->input->post('deskripsi'),
                'controller' => $this->input->post('controller'),
                'bview' => $bview,
                'binsert' => $binsert,
                'bupdate' => $bupdate,
                'bdelete' => $bdelete,
                'sequence' => $this->input->post('sequence'),
                'aktif' => '1'
            );


            if ($this->input->post('id_menu')=="") {
                $this->menu_models->add_record($data);
            } else {
                $this->menu_models->update_record($data, $this->input->post('id_menu'));
            }
            redirect(base_url() . "menu", "location");
        
    }
    
    public function delete() {
        $data = array(
            'aktif' => '0'
        );
        $this->load->model('menu_models');
        $this->menu_models->update_record($data, $this->uri->segment(3));
        $this->index();
    }
	
}
