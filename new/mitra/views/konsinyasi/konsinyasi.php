<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

	$this->load->view('konsinyasi/create_request');
	$this->load->view('konsinyasi/approval');
	$this->load->view('konsinyasi/approval_manager');
	$this->load->view('konsinyasi/create_po');
	$this->load->view('konsinyasi/approve_po');
	$this->load->view('konsinyasi/receive_order');
	$this->load->view('konsinyasi/create_invoice');
	$this->load->view('konsinyasi/pelunasan_hutang');
	$this->load->view('konsinyasi/faktur');
	$this->load->view('konsinyasi/retur_order');
	$this->load->view('konsinyasi/purchase_request_print');
	$this->load->view('konsinyasi/purchase_order_print');
	$this->load->view('konsinyasi/receive_order_print');
	$this->load->view('konsinyasi/create_po_non_request');
        $this->load->view('konsinyasi/close_pr');
        $this->load->view('konsinyasi/close_po');
        $this->load->view('konsinyasi/view_invoice');
        $this->load->view('konsinyasi/create_kwitansi');
        $this->load->view('konsinyasi/cetak_pelunasan_hutang');
        $this->load->view('konsinyasi/view_pelunasan_hutang');
        $this->load->view('konsinyasi/create_surat_pesanan_konsinyasi');
        $this->load->view('konsinyasi/approve_surat_pesanan');
        $this->load->view('konsinyasi/create_po_konsinyasi');
       $this->load->view('konsinyasi/cetak_surat_pesanan');
        $this->load->view('konsinyasi/generate_po');
        $this->load->view('konsinyasi/receive_order_by_po');
?>