<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class Report
 * @property $test set true untuk menampilkan sql
 */
class Report extends MY_Controller {

    protected $test   = false;
    // protected $test   = true;

    /**
     * @author bambang
     * @lastedited 5 may 2014
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('report_model', 'rpt');
    }

    public function get_user(){
        $start  = $this->form_data('start',0);
        $limit  = $this->form_data('limit',$this->config->item("length_records"));
        $search = $this->form_data('query','');

        $this->print_result_json($this->rpt->get_user($search, $limit, $start),$this->test);
    }

    public function get_shift(){
        $start  = $this->form_data('start',0);
        $limit  = $this->form_data('limit',$this->config->item("length_records"));
        $search = $this->form_data('query','');
        $users  = $this->form_data('users','',true);

        $this->print_result_json($this->rpt->get_shift($search, $users, $limit, $start),$this->test);
    }

    public function get_member(){
        $start  = $this->form_data('start',0);
        $limit  = $this->form_data('limit',$this->config->item("length_records"));
        $search = $this->form_data('query','');

        $this->print_result_json($this->rpt->get_member($search, $limit, $start),$this->test);
    }

    public function get_kategori1(){
        $start  = $this->form_data('start',0);
        $limit  = $this->form_data('limit',$this->config->item("length_records"));
        $search = $this->form_data('query','');

        $this->print_result_json($this->rpt->get_kategori1($search, $limit, $start),$this->test);
    }

    public function get_kategori2(){
        $start      = $this->form_data('start',0);
        $limit      = $this->form_data('limit',$this->config->item("length_records"));
        $search     = $this->form_data('query','');
        $kategori1  = $this->form_data('kategori1','',true);

        $this->print_result_json($this->rpt->get_kategori2($search, $kategori1, $limit, $start),$this->test);
    }

    public function get_kategori3(){
        $start      = $this->form_data('start',0);
        $limit      = $this->form_data('limit',$this->config->item("length_records"));
        $search     = $this->form_data('query','');
        $kategori1  = $this->form_data('kategori1','',true);
        $kategori2  = $this->form_data('kategori2','',true);

        $this->print_result_json($this->rpt->get_kategori3($search, $kategori1, $kategori2, $limit, $start),$this->test);
    }

    public function get_kategori4(){
        $start      = $this->form_data('start',0);
        $limit      = $this->form_data('limit',$this->config->item("length_records"));
        $search     = $this->form_data('query','');
        $kategori1  = $this->form_data('kategori1','',true);
        $kategori2  = $this->form_data('kategori2','',true);
        $kategori3  = $this->form_data('kategori3','',true);

        $this->print_result_json($this->rpt->get_kategori4($search, $kategori1, $kategori2, $kategori3, $limit, $start),$this->test);
    }

    public function get_satuan(){
        $start  = $this->form_data('start',0);
        $limit  = $this->form_data('limit',$this->config->item("length_records"));
        $search = $this->form_data('query','');

        $this->print_result_json($this->rpt->get_satuan($search, $limit, $start),$this->test);
    }

    public function get_ukuran(){
        $start  = $this->form_data('start',0);
        $limit  = $this->form_data('limit',$this->config->item("length_records"));
        $search = $this->form_data('query','');

        $this->print_result_json($this->rpt->get_ukuran($search, $limit, $start),$this->test);
    }

    public function get_supplier() {
        $start  = $this->form_data('start',0);
        $limit  = $this->form_data('limit',$this->config->item("length_records"));
        $search = $this->form_data('query','');

        $this->print_result_json($this->rpt->get_supplier($search, $limit, $start),$this->test);
    }

    public function get_produk(){
        $start      = $this->form_data('start',0);
        $limit      = $this->form_data('limit',$this->config->item("length_records"));
        $search     = $this->form_data('query','');
        $kategori1  = $this->form_data('kategori1','',true);
        $kategori2  = $this->form_data('kategori2','',true);
        $kategori3  = $this->form_data('kategori3','',true);
        $kategori4  = $this->form_data('kategori4','',true);
        $ukuran     = $this->form_data('ukuran','',true);
        $satuan     = $this->form_data('satuan','',true);
        $supplier   = $this->form_data('supplier','',true);
        $konsinyasi = $this->form_data('konsinyasi','',false);

        $this->print_result_json($this->rpt->get_produk($search, $supplier, $kategori1, $kategori2, $kategori3, $kategori4, $ukuran, $satuan, $konsinyasi, $limit, $start),$this->test);
    }

    public function get_jns_bayar() {
        $start  = $this->form_data('start',0);
        $limit  = $this->form_data('limit',$this->config->item("length_records"));
        $search = $this->form_data('query','');

        $this->print_result_json($this->rpt->get_jns_bayar($search, $limit, $start),$this->test);
    }

    public function get_no_so(){
        $start      = $this->form_data('start',0);
        $limit      = $this->form_data('limit',$this->config->item("length_records"));
        $search     = $this->form_data('query','');
        $tgl_awal   = $this->form_data('tgl_awal', false)   ? date('Y-m-d', strtotime($this->form_data('tgl_awal'))) : false;
        $tgl_akhir  = $this->form_data('tgl_akhir', false)  ? date('Y-m-d', strtotime($this->form_data('tgl_akhir'))) : false;

        $this->print_result_json($this->rpt->get_no_so($search, $tgl_awal, $tgl_akhir, $limit, $start),$this->test);
    }

    public function get_no_po(){
        $start      = $this->form_data('start',0);
        $limit      = $this->form_data('limit',$this->config->item("length_records"));
        $search     = $this->form_data('query','');
        $tgl_awal   = $this->form_data('tgl_awal', false)   ? date('Y-m-d', strtotime($this->form_data('tgl_awal'))) : false;
        $tgl_akhir  = $this->form_data('tgl_akhir', false)  ? date('Y-m-d', strtotime($this->form_data('tgl_akhir'))) : false;
        $supplier   = $this->form_data('supplier',null);
        $no_ro      = $this->form_data('no_ro',null);
        $konsinyasi = $this->form_data('konsinyasi', null);

        $this->print_result_json($this->rpt->get_no_po(
          $tgl_awal,
          $tgl_akhir,
          $supplier,
          $no_ro,
          $konsinyasi,
          $search,
          $limit,
          $start
        ),$this->test);
    }

    public function get_no_ro(){
        $start      = $this->form_data('start',0);
        $limit      = $this->form_data('limit',$this->config->item("length_records"));
        $search     = $this->form_data('query','');
        $tgl_awal   = $this->form_data('tgl_awal', false)   ? date('Y-m-d', strtotime($this->form_data('tgl_awal'))) : false;
        $tgl_akhir  = $this->form_data('tgl_akhir', false)  ? date('Y-m-d', strtotime($this->form_data('tgl_akhir'))) : false;
        $supplier   = $this->form_data('supplier',null);
        $konsinyasi = $this->form_data('konsinyasi', null);

        $this->print_result_json($this->rpt->get_no_ro(
          $tgl_awal,
          $tgl_akhir,
          $supplier,
          $konsinyasi,
          $search,
          $limit,
          $start
        ),$this->test);
    }

}
