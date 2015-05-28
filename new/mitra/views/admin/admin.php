<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
	
	$this->load->view('admin/user');
	$this->load->view('admin/group');
	$this->load->view('admin/usergroup');
	$this->load->view('admin/usergroup_akses');
	$this->load->view('admin/menu');
        $this->load->view('admin/jabatan');
        $this->load->view('admin/divisi');
        $this->load->view('admin/mac_address');
?>