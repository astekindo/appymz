<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penjualan_pelunasan_piutang extends MY_Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function __construct() {
        parent::__construct();
		$this->load->model('penjualan_pelunasan_piutang_model', 'ppp_model');
    }

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function get_form(){
    	$no_kwit = 'PP' . date('Ym') . '-';
    	$sequence = $this->ppp_model->get_kode_sequence($no_kwit, 3);
    	echo '{"success":true,
				"data":{

					"tanggal":"' . date('d-M-Y'). '"
				}
			}';
    }

	public function get_rows(){
		$no_faktur = isset($_POST['no_faktur']) ? $this->db->escape_str($this->input->post('no_faktur',TRUE)) : '';
		$no_bstt = isset($_POST['no_bstt']) ? $this->db->escape_str($this->input->post('no_bstt',TRUE)) : '';
		$result = $this->ppp_model->get_rows($no_faktur,$no_bstt);

		echo $result;

	}
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
    public function update_row(){
		$no_faktur = $this->form_data('no_faktur');
		$tanggal = $this->form_data('tanggal');
		$keterangan = $this->form_data('keterangan');

		$total_faktur = $this->form_data('_total_faktur', 0);
		$total_bayar = $this->form_data('_total_bayar', 0);
		$total_potongan = $this->form_data('_total_potongan', 0);
		$total_dibayar = $this->form_data('_rp_bayar', 0);
		$kurang_bayar = $this->form_data('_kurang_bayar', 0);
		$selisih = $this->form_data('_selisih', 0);

        $detail = empty($_POST['detail']) ? array() : json_decode($this->input->post('detail',TRUE));
		$detailbayar = empty($_POST['detailbayar']) ? array() : json_decode($this->input->post('detailbayar',TRUE));

		if($tanggal != '') $tanggal = date('Y-m-d', strtotime($tanggal));
		if(count($detail) == 0 && count($detailbayar) == 0) $this->returnError("Data tidak lengkap");

        $current_date = date('Ym', strtotime($tanggal));
		$no_kwit = 'PP' . $current_date .'-';
        $sequence = $this->ppp_model->get_kode_sequence($no_kwit, 4);
        $no_bukti = $no_kwit . $sequence;
		$this->db->trans_begin();
		$header_ppp['no_pelunasan_piutang'] = $no_bukti;
		$header_ppp['tanggal'] = $tanggal;
		$header_ppp['no_faktur'] = $no_faktur;
		$header_ppp['rp_faktur'] = (int) $total_faktur;
		$header_ppp['rp_extra_diskon'] = (int) $total_potongan;
		$header_ppp['rp_pelunasan'] = (int) $total_bayar;
        $header_ppp['rp_total_dibayar'] = (int) $total_dibayar;
		$header_ppp['rp_selisih'] = (int) $selisih;
		//$header_ppp['rp_kurang_bayar'] =(int) $kurang_bayar;
		$header_ppp['keterangan'] = $keterangan;
        $header_ppp['created_by'] = $this->session->userdata('username');
		$header_ppp['created_date'] = date('Y-m-d H:i:s');
		if( ! $this->ppp_model->insert_row('sales.t_piutang_pelunasan', $header_ppp)){
			echo '{"success":false,"errMsg":"Process Failed.."}';
			$this->db->trans_rollback();
			exit;
		}

		foreach($detail as $obj){
			$detail_ppp['no_pelunasan_piutang'] = $no_bukti;
			$detail_ppp['no_faktur'] = $obj->no_so;
			$detail_ppp['rp_faktur'] = (int) $obj->rp_grand_total;
			$detail_ppp['rp_potongan'] = (int) $obj->rp_potongan;
			$detail_ppp['tgl_faktur'] = $obj->tgl_so;
			$detail_ppp['rp_bayar'] = (int) $obj->rp_bayar;
			$detail_ppp['rp_dibayar'] = (int) $obj->rp_dibayar;
            $detail_ppp['rp_sisa'] = (int) $obj->sisa_bayar;
            $detail_result = 0;

			if($this->ppp_model->insert_row('sales.t_piutang_detail', $detail_ppp)){
				$detail_result++;
			}

			if( $this->ppp_model->update_sales_order($obj->no_so, $obj->sisa_bayar, $obj->rp_bayar)){
				$detail_result++;
			}
		}



		foreach($detailbayar as $obj){
			$detail_bayar['no_pelunasan_piutang'] = $no_bukti;
			$detail_bayar['kd_jns_bayar'] = $obj->kd_jenis_bayar;
			$detail_bayar['nomor_bank'] = $obj->nomor_bank;
			$detail_bayar['nomor_ref'] = $obj->nomor_ref;
			if($obj->tgl_jth_tempo != ''){
				$tgl_jth_tempo = date('Y-m-d', strtotime($obj->tgl_jth_tempo));
			}
			$detail_bayar['tgl_jth_tempo'] = $tgl_jth_tempo;
			$detail_bayar['rp_bayar'] = (int) $obj->rp_bayar_piutang;

			if(! $this->ppp_model->insert_row('sales.t_piutang_bayar', $detail_bayar)){
				echo '{"success":false,"errMsg":"Process Failed.."}';
				$this->db->trans_rollback();
				exit;
			}
		}

		$this->db->trans_commit();
                 $result = '{"success":true,"errMsg":"Data Berhasil Disimpan","printUrl":"' . site_url("penjualan_pelunasan_piutang/print_form/" . $no_bukti) . '"}';
                  echo $result;
                 //echo '{"success":true,"errMsg":""}';
    }

    public function print_form($no_bukti = '') {
        $data = $this->ppp_model->get_data_print($no_bukti);
        if (!$data)
            show_404('page');

        $this->output->set_content_type("application/pdf");
        require_once(APPPATH . 'libraries/PelunasanPiutangPrint.php');
        $pdf = new PelunasanPiutangPrint(PDF_PAGE_ORIENTATION_LANDSCAPE, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setKertas();
        $pdf->privateData($data['header'], $data['detail'], $data['detail_bayar']);
        $pdf->Output();
        exit;
    }
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_faktur(){
        header('Content-Type:application/json');
		$search = isset($_POST['query']) ? $this->db->escape_str($this->input->post('query',TRUE)) : "";
		$result = $this->ppp_model->get_all_faktur($search);

        echo $result;
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function get_all_jenis_pembayaran(){
		$result = $this->ppp_model->get_all_jenis_pembayaran(true);

        echo $result;
	}

	public function search_pelanggan(){
		echo $this->ppp_model->search_pelanggan();

	}

}