<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('fpdf/fpdf.php');

class CreatePOPrint_pdf extends FPDF_Form_Template {
    private $CI;

    protected $font = 'courier';
    protected $pg_height = 210;
    protected $pg_width = 330;
    protected $y_max = 185;
    protected $x_max = 310;
    protected $margin = 10;

    protected $map = array(
        array('l' =>  8, 'b' => 'LRB', 'a' => 'C', 'd' => 'no_urut', 'h' => "No"),
        array('l' => 30, 'b' =>  'RB', 'a' => 'C', 'd' => 'kd_produk', 'h' => "Kode Barang\n(Kd Brg Supp)"),
        array('l' => 60, 'b' =>  'RB', 'a' => 'L', 'd' => 'nama_produk', 'h' => "Nama Barang"),
        array('l' => 20, 'b' =>  'RB', 'a' => 'R', 'd' => 'qty_po', 'h' => "Qty"),
        array('l' => 15, 'b' =>  'RB', 'a' => 'C', 'd' => 'nm_satuan', 'h' => "Sat."),
        array('l' => 20, 'b' =>  'RB', 'a' => 'R', 'd' => 'price_supp_po', 'h' => "Harga Beli"),
        array('l' => 13, 'b' =>  'RB', 'a' => 'R', 'd' => 'disk_1', 'h' => "1"),
        array('l' => 13, 'b' =>  'RB', 'a' => 'R', 'd' => 'disk_2', 'h' => "2"),
        array('l' => 13, 'b' =>  'RB', 'a' => 'R', 'd' => 'disk_3', 'h' => "3"),
        array('l' => 13, 'b' =>  'RB', 'a' => 'R', 'd' => 'disk_4', 'h' => "4"),
        array('l' => 13, 'b' =>  'RB', 'a' => 'R', 'd' => 'disk_5', 'h' => "5"),
        array('l' => 21, 'b' =>  'RB', 'a' => 'R', 'd' => 'rp_disk_po', 'h' => "Total Diskon"),
        array('l' => 23, 'b' =>  'RB', 'a' => 'R', 'd' => 'net_price_po', 'h' => "Harga NET"),
        array('l' => 23, 'b' =>  'RB', 'a' => 'R', 'd' => 'dpp_po', 'h' => "Hrg NET\n(Ex. PPN)"),
        array('l' => 25, 'b' =>  'RB', 'a' => 'R', 'd' => 'rp_total_po', 'h' => "Jumlah\n(Ex. PPN)")
    );

    protected $data;
    protected $cetak = 0;
    protected $total_brg = 0;

    function create_pdf($data) {
        $this->CI = & get_instance();
        $this->data = $data;
        $this->AddPage('L',array($this->pg_width,$this->pg_height));

        $this->FormSummary($data['header']);
        $this->TableHeader($this->GetY()+5);

        foreach ($data['detail'] as $key => $row) {
            $data['detail'][$key] = (object) $this->dataFormat($row);
        }

        $no = 1;
        foreach ($data['detail'] as $row) {
            $max_t = 8;
            foreach($this->map as $key => $column) {
                $this->map[$key]['t'] = $this->GetMultiCellHeight($column['l'], 4, $row->$column['d']);
                $max_t = $this->map[$key]['t'] > $max_t ? $this->map[$key]['t'] : $max_t;
            }

            $row->no_urut = $no;
            if($this->y_max - $this->GetY() < $max_t) {
                $this->Ln($this->y_max - $this->GetY());
                $this->AddPage('L',array($this->pg_width,$this->pg_height));
                $this->TableHeader(35);
            }
            $this->TableData($row, $max_t);
            $no++;
        }

        $this->Approval($data['header']);
        $this->AliasNbPages();
        $this->Output($data['header']->no_mutasi_stok,'I');
    }

    function dataFormat($data) {
        return array(
        'kd_produk'       => empty($data->kd_produk_supp) ? "$data->kd_produk -" : "$data->kd_produk $data->kd_produk_supp",
        'nama_produk'     => $data->nama_produk,
        'qty_po'          => $data->qty_po,
        'nm_satuan'       => $data->nm_satuan,
        'price_supp_po'   => number_format($data->price_supp_po, 0, ', ', '.'),
        'disk_1'          => $data->disk_persen_supp1_po > 0 ? $data->disk_persen_supp1_po .'%' : number_format($data->disk_amt_supp1_po, 0, ', ', '.'),
        'disk_2'          => $data->disk_persen_supp2_po > 0 ? $data->disk_persen_supp2_po .'%' : number_format($data->disk_amt_supp2_po, 0, ', ', '.'),
        'disk_3'          => $data->disk_persen_supp3_po > 0 ? $data->disk_persen_supp3_po .'%' : number_format($data->disk_amt_supp3_po, 0, ', ', '.'),
        'disk_4'          => $data->disk_persen_supp4_po > 0 ? $data->disk_persen_supp4_po .'%' : number_format($data->disk_amt_supp4_po, 0, ', ', '.'),
        'disk_5'          => number_format($data->disk_amt_supp5_po, 0, ', ', '.'),
        'rp_disk_po'      => number_format($data->rp_disk_po, 0, ', ', '.'),
        'net_price_po'    => number_format($data->net_price_po, 0, ', ', '.'),
        'dpp_po'          => number_format($data->dpp_po, 0, ', ', '.'),
        'rp_total_po'     => number_format($data->rp_total_po, 0, ', ', '.')
        );
    }

    /**
     * Header
     * [1] | [2] | [3] | [4] | [5] | [1]
     * 10  | 33  | 4   | 170 |  70 | 10
     * 1: margin
     * 2: logo
     * 3: spacer
     * 4: judul
     * 5: alamat
     */
    function Header() {
        if($this->CI == null) {
            $this->CI = & get_instance();
        }
        $company_name = "PT. SURYA KENCANA KERAMINDO\nMITRA BANGUNAN SUPERMARKET";
        $alamat = $this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT1)
            ."\n".$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT2)
            ."\n".$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT3)
            ."\n".$this->CI->session->userdata(PRM_HEADER_CETAK_DOC_RIGHT4);
        $this->Image('assets/img/logo-mbs.jpg', 10, 8, 33);
        $this->Cell(47);
        //company_name
        $this->SetFont($this->font, 'B', 12);
        $x = $this->GetX() + 120;
        $y = $this->GetY();
        $this->MultiCell(170,8, $company_name,0,'C');

        //alamat
        $this->SetXY($x, $y);
        $this->SetFont($this->font, '', 8);
        $this->MultiCell(0,5,$alamat,0,'R');

        $this->SetLineWidth(.5);
        $this->Line($this->margin, 30, $this->x_max+$this->margin, 30);
        $this->Ln(5);
    }

    function FormSummary($data) {
        if($data->tanggal_po){
            $tanggal_po = date('d-m-Y', strtotime($data->tanggal_po));
        }

        if($data->tgl_berlaku_po){
            $tgl_berlaku_po = date('d-m-Y', strtotime($data->tgl_berlaku_po));
        }
        $kolom_kiri  = array('No PO','Supplier','NPWP','Kepada','No Telp','No Fax','E-mail');
        $kiri = array($data->no_po, $data->nama_supplier,$data->npwp,$data->pic,$data->telpon,$data->fax,$data->email);
        $kolom_kanan = array('Tanggal PO','Dibuat Oleh','TOP','Masa Berlaku PO','Kirim Ke','Alamat');
        $kanan = array($tanggal_po,$data->order_by_po,$data->top,$tgl_berlaku_po,$data->kirim_po,$data->alamat_kirim_po);
        //subjudul
        $this->SetFont($this->font, 'B', 12);
        $judul = 'PURCHASE ORDER FORM';
        switch($data->kd_peruntukan) {
            case '1':
                $judul .= ' (DISTRIBUSI)';
                break;
            default:
                $judul .= ' (SUPERMARKET)';
                break;
        }
        $this->Write(5,$judul);
        $this->Ln(5);
        //buat multicell u/ kolom samping kiri.
        $this->SetFont($this->font, '', 10);
        $y = $this->GetY();
        for($i = 0;$i<7;$i++) {
            $y1 = $this->GetY();
            $this->SetXY(10, $y1);
            $this->Cell(35,5,$kolom_kiri[$i],0,'L')
                ->Cell(2,5,": ",0,'L')
                ->MultiCell(100,5,(!empty($kiri) && count($kiri)> $i) ? $kiri[$i] : '',0,'L');
        }

        //buat multicell u/ kolom samping kanan.
        $this->SetXY(150, $y);
        for($i = 0;$i<6;$i++) {
            $y1 = $this->GetY();
            $this->SetXY(150, $y1);
            $this->Cell(35,5,$kolom_kanan[$i],0,'L')->Cell(2,5,": ",0,'L')
                ->MultiCell(0,5,(!empty($kanan) && count($kanan)> $i) ? $kanan[$i] : '',0,'L');
        }
    }

    function TableHeader($y = 40) {
        $this->SetFont($this->font, '', 8);
        $x = 10;
        $disk = 10;
        foreach($this->map as $key => $row) {
            if($key<6) $disk += $row['l'];
            $tinggi = 10;
            if( $row['d'] == 'kd_produk' || $row['d'] == 'rp_disk_po' || $row['d'] == 'dpp_po' || $row['d'] == 'rp_total_po')
                $tinggi = 5;
            $this->SetXY($x,$y);
            if(strpos($row['d'],'disk') !== FALSE && strpos($row['d'],'po')  === FALSE) {
                $this->SetXY($x,$y+5);
                $this->MultiCell($row['l'], $tinggi/2, $row['h'], 'T'.$row['b'], 'C');
            } else {
                $this->MultiCell($row['l'], $tinggi, $row['h'], 'T'.$row['b'], 'C');
            }
            $x += $row['l'];
        }
        $this->SetXY($disk,$y);
        $this->MultiCell(65, 5, 'Diskon', 'T'.$row['b'], 'C');
        $this->SetXY(10,$y+10);
    }

    function TableData($data, $tinggi) {

        $x = 10;
        $x1 = 10;
        $y = $this->GetY();

        for($i = 0;$i < count($this->map);$i++) {
            $x1 = $x;

            $this->SetFont($this->font, '', 9);
            $content = $this->map[$i]['d'];
            if($i === 1) {
                $k_tinggi = $tinggi/2;
            } else {
                $k_tinggi = $this->GetMultiCellHeight($this->map[$i]['l'], 4, $data->$content);
                $k_tinggi = (4/$k_tinggi) * $tinggi;
//                $k_tinggi = (4/$this->map[$i]['t']) * $tinggi;
            }

            $this->MultiCell($this->map[$i]['l'], $k_tinggi, $data->$content, '', $this->map[$i]['a']);
//            $this->MultiCell($this->map[$i]['l'], $k_tinggi, $data->$content, $this->map[$i]['b'], $this->map[$i]['a']);
            $x = $x+$this->map[$i]['l'];
            $this->SetXY($x,$y);
            if($content == 'qty_po') $this->total_brg = $this->total_brg + $data->$content;
            $this->Line($x1, $y, $x, $y);
            $this->Line($x1, $y+$tinggi, $x, $y+$tinggi);
            $this->Line($x1, $y, $x1, $y+$tinggi);
            if($i+1 == count($this->map)) $this->Line($x, $y, $x, $y+$tinggi);
        }

        $y += $tinggi;
        $this->SetXY(10,$y);
    }

    function Approval($data,$y = null) {
        $qty            = $this->map[0]['l']+$this->map[1]['l']+$this->map[2]['l'];
        $col_non_hang   = 249;  //kolom non gantung, tanpa placeholder tanda tangan
        $col_hang       = 120;  //kolom gantung, untuk placeholder tanda tangan
        $num_hang       = 23;   //lebar kolom persen diskon

        $this->SetFont($this->font, '', 10);
        $this->Cell($qty, 5, 'Total: ',1,0,'R')
            ->Cell($this->map[3]['l'], 5, number_format($this->total_brg, 0,',','.'),'TRB',0,'R')
            ->Cell(0, 5, '','TRB',0,'R');
        //jika sisa kertas tidak cukup untuk sel approval, pindah halaman baru
        if($y === null) $y = $this->GetY();
        if($this->y_max - $y < 50) {
            $this->AddPage('L',array($this->pg_width,$this->pg_height));
            $this->SetXY(10,20);
        }

        $this->Ln(10);
        $this->Cell($col_non_hang,5,'Total',0,0,'R')
            ->Cell($num_hang,5,'',0,0,'R')
            ->Cell(0,5,number_format($data->rp_jumlah_po, 0,',','.'),0,0,'R')
            ->Ln(5);

        $this->Cell($col_hang,5,'Hormat Kami',0,0,'C')
            ->Cell($col_non_hang-$col_hang,5,'Diskon Tambahan',0,0,'R')
            ->Cell($num_hang,5,number_format($data->rp_jumlah_po / $data->rp_diskon_po, 0,',','.').' %',0,0,'R')
            ->Cell(0,5,number_format($data->rp_diskon_po, 0,',','.'),0,0,'R')
            ->Ln(5);

        $this->Cell($col_non_hang,5,'Total Tagihan',0,0,'R')
            ->Cell($num_hang,5,'',0,0,'R')
            ->Cell(0,5,number_format($data->rp_jumlah_po - $data->rp_diskon_po, 0,',','.'),0,0,'R')
            ->Ln(5);

        $this->Cell($col_non_hang,5,'PPN',0,0,'R')
            ->Cell($num_hang,5,number_format($data->ppn_percent_po, 0,',','.').' %',0,0,'R')
            ->Cell(0,5,number_format($data->rp_ppn_po, 0,',','.'),0,0,'R')
            ->Ln(5);

        $this->Cell($col_non_hang,5,'Grand Total',0,0,'R')
            ->Cell($num_hang,5,number_format($data->rp_jumlah_po / $data->rp_diskon_po, 0,',','.').' %',0,0,'R')
            ->Cell(0,5,number_format($data->rp_total_po, 0,',','.'),0,0,'R')
            ->Ln(5);

        $this->Cell($col_non_hang,5,'DP',0,0,'R')
            ->Cell($num_hang,5,'',0,0,'R')
            ->Cell(0,5,number_format($data->rp_dp, 0,',','.'),0,0,'R')
            ->Ln(5);


        $this->Cell($col_hang,5,"( $data->approval_by )",0,0,'C')
            ->Cell($col_non_hang-$col_hang,5,'Sisa Bayar',0,0,'R')
            ->Cell($num_hang,5,'',0,0,'R')
            ->Cell(0,5,number_format($data->rp_total_po - $data->rp_dp, 0,',','.'),0,0,'R')
            ->Ln(10);

        $this->SetFont($this->font, '', 12);
        $this->MultiCell(0,10,"*) FORM INI TERCETAK LANGSUNG DARI SYSTEM, SEHINGGA TIDAK DIPERLUKAN TANDA TANGAN",0,"L");
        $this->MultiCell(0,5,"PERHATIAN :\n$data->remark",0,"L");
        $this->SetFont($this->font, '', 10);

    }

    function block($start = 0,$width = 1,$addmargin = true) {
        if($addmargin) {
            return $this->margin + array_sum(array_slice($this->cw,$start,$width));
        } else {
            return array_sum(array_slice($this->cw,$start,$width));
        }
    }

    function manualPageBreak($start = 35) {
        $this->AddPage('L');
        $this->TableHeader($start);
        $this->SetXY(10, $this->GetY());
    }

    function Footer() {
        $this->SetFont($this->font, '', 10);
        $this->SetY(-10);
        $this->SetLineWidth(.3);
        $this->Line($this->margin, $this->y_max+$this->margin, $this->x_max+$this->margin, $this->y_max+$this->margin);
        $this->Cell(80, 5,date('d-M-Y H:i',time()), 0,'L')
            ->Cell(80, 5, 'Cetak ke: ' . $this->data['header']->cetak_ke, 0, 0, 'L')
            ->Cell(60, 5,'No.Ref: '. $this->data['header']->no_po, 0,'L')
            ->Cell(0, 5, 'Page ' . $this->PageNo() .' of {nb}', 0, 0, 'R');
    }
}
