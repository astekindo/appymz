<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$this->load->view('pembelian/create_request');
$this->load->view('pembelian/create_request_asset');
$this->load->view('pembelian/approval');
$this->load->view('pembelian/approval_manager');
$this->load->view('pembelian/create_po');
$this->load->view('pembelian/create_po_asset');
$this->load->view('pembelian/create_po_non_request');
$this->load->view('pembelian/create_po_bonus');
$this->load->view('pembelian/approve_po');
$this->load->view('pembelian/receive_order');
$this->load->view('pembelian/receive_order_asset');
$this->load->view('pembelian/receive_order_bonus');
$this->load->view('pembelian/create_invoice');
$this->load->view('pembelian/create_kwitansi');
$this->load->view('pembelian/pelunasan_hutang');
$this->load->view('pembelian/retur_pembelian');
$this->load->view('pembelian/monitoring_purchase_order');
$this->load->view('pembelian/monitoring_qty_request');
$this->load->view('pembelian/monitoring_purchase_request');
$this->load->view('pembelian/purchase_request_print');
$this->load->view('pembelian/purchase_order_print');
$this->load->view('pembelian/receive_order_print');
$this->load->view('pembelian/monitoring_receive_order');
$this->load->view('pembelian/view_detail_po');
$this->load->view('pembelian/view_detail_ro');
$this->load->view('pembelian/close_po');
$this->load->view('pembelian/pembayaran_po');
$this->load->view('pembelian/view_retur_pembelian');
$this->load->view('pembelian/view_invoice');
$this->load->view('pembelian/view_pelunasan_hutang');
$this->load->view('pembelian/close_pr');
$this->load->view('pembelian/invoice_order_print');
$this->load->view('pembelian/cetak_retur_pembelian');
$this->load->view('pembelian/cetak_pelunasan_hutang');
$this->load->view('pembelian/retur_receive_order');
$this->load->view('pembelian/cetak_retur_receive_order');
$this->load->view('pembelian/invoice_order_konsinyasi_print');
$this->load->view('pembelian/pembayaran_uang_muka_po');
$this->load->view('pembelian/open_close_po');
$this->load->view('pembelian/create_request_bonus');
$this->load->view('pembelian/create_purchase_order_bonus');
$this->load->view('pembelian/monitoring_purchase_order_bonus');
?>