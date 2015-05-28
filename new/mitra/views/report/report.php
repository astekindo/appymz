<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//START laporan penjualan
$this->load->view('report/lap_penjualan_1');
$this->load->view('report/lap_penjualan_2');
$this->load->view('report/lap_penjualan_3');
$this->load->view('report/lap_penjualan_4');

$this->load->view('report/lap_penjualan_per_kategori1');
$this->load->view('report/lap_penjualan_per_kategori2');
$this->load->view('report/lap_penjualan_per_kategori3');
$this->load->view('report/lap_penjualan_per_kategori4');
$this->load->view('report/lap_penjualan_vs_cogs_per_kategori1');
$this->load->view('report/lap_penjualan_vs_cogs_per_kategori2');
//$this->load->view('report/lap_penjualan_vs_cogs_per_kategori3');
//$this->load->view('report/lap_penjualan_vs_cogs_per_kategori4');

$this->load->view('report/lap_penjualan_per_supplier');
$this->load->view('report/lap_penjualan_per_supplier_perQty');
$this->load->view('report/lap_rekap_produk_penjualan_persupplier');
$this->load->view('report/lap_rekap_penjualan_persupplier');
$this->load->view('report/lap_penjualan_top_down_per_kode_barang');
$this->load->view('report/lap_penjualan_per_kode_barang');

$this->load->view('report/lap_penjualan_sales_order');
$this->load->view('report/lap_penjualan_retur');
$this->load->view('report/lap_sum_penjualan_harian');
$this->load->view('report/lap_penjualan_per_surat_jalan');
//END laporan penjualan
//Laporan Pembelian

//Laporan Penerimaan
$this->load->view('report/lap_penerimaan_brg_perkategori1');
$this->load->view('report/lap_penerimaan_brg_perkategori2');
$this->load->view('report/lap_penerimaan_brg_perkategori3');
$this->load->view('report/lap_penerimaan_brg_perkategori4');
$this->load->view('report/lap_penerimaan_brg_per_kd_brg');
$this->load->view('report/lap_perincian_faktur_retur_pembelian');
$this->load->view('report/lap_faktur_retur_pembelian');
$this->load->view('report/lap_penerimaan_brg_yg_blm_difakturkan');

//Laporan Lain-Lain
$this->load->view('report/laporan_penerimaan_barang');
$this->load->view('report/lap_purchase_order');
$this->load->view('report/lap_outstanding_po');
$this->load->view('report/lap_daftar_supplier');
$this->load->view('report/lap_rekap_po');
$this->load->view('report/lap_sum_penjualan');
$this->load->view('report/lap_kartu_stok');
$this->load->view('report/lap_monitoring_setoran_kasir');
$this->load->view('report/lap_rekap_stok_pergudang');
$this->load->view('report/lap_rekap_stok_per_katagori');
$this->load->view('report/lap_mutasi_stok_lengkap');
$this->load->view('report/lap_umur_outstanding_po');

$this->load->view('report/lap_rekap_penerimaan_barang_persupp');
$this->load->view('report/lap_umur_hutang');
$this->load->view('report/lap_umur_piutang');
$this->load->view('report/lap_rekap_retur_pembelian_persupplier');
$this->load->view('report/lap_outstanding_hutang_persupplier');
$this->load->view('report/lap_outs_hutang_per_tgl_jatuhtempo');

$this->load->view('report/lap_rekap_penerimaan_barang_persupp');
$this->load->view('report/lap_rekap_prod_penjulan_persupp_perQty');
$this->load->view('report/lap_pendapatan_kasir');
$this->load->view('report/lap_setoran_transaksi_kasir');
$this->load->view('report/lap_selisih_setoran_kasir');
$this->load->view('report/lap_mutasi_stok_per_lokasi');
$this->load->view('report/lap_mutasi_stok_value');
$this->load->view('report/lap_nilai_stok');
$this->load->view('report/lap_pelunasan_piutang');
$this->load->view('report/lap_penjualan_per_jenis_transaksi');

$this->load->view('report/target_pencapaian_penjualan');
$this->load->view('report/target_pencapaian_pembelian');
?>
