<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('fpdf/fpdf.php');

class Laporan_Penjualan_SO_pdf extends FPDF_Form_Template {
    private $CI;

    protected $font = 'courier';
    protected $pg_height = 210;
    protected $pg_width = 297;
    protected $x_min = 5;
    protected $y_min = 5;
    protected $x_ref = 287;
    protected $x_max = 415;
    protected $y_ref = 185;
    protected $y_max = 200;

    protected $map = array(
        array('l' => 10, 'b' => 'LRB', 'a' => 'C', 'd' => 'no_urut', 'h' => 'No'),
        array('l' => 30, 'b' =>  'RB', 'a' => 'C', 'd' => 'no_bukti', 'h' => 'No. Bukti'),
        array('l' => 20, 'b' =>  'RB', 'a' => 'C', 'd' => 'tgl_so', 'h' => 'Tgl SO'),
        array('l' => 20, 'b' =>  'RB', 'a' => 'C', 'd' => 'tgl_do', 'h' => 'TGl DO'),
        array('l' => 20, 'b' =>  'RB', 'a' => 'C', 'd' => 'kd_member', 'h' => 'Kode Member'),
        array('l' => 25, 'b' =>  'RB', 'a' => 'C', 'd' => 'nm_konsumen', 'h' => 'Nama Konsumen'),
        array('l' => 20, 'b' =>  'RB', 'a' => 'C', 'd' => 'kd_produk', 'h' => 'Kode Produk'),
        array('l' => 40, 'b' =>  'RB', 'a' => 'C', 'd' => 'nama_produk', 'h' => 'Nama Produk'),
        array('l' =>  9, 'b' =>  'RB', 'a' => 'C', 'd' => 'qty_so', 'h' => 'SO'),
        array('l' =>  9, 'b' =>  'RB', 'a' => 'C', 'd' => 'qty_kirim', 'h' => 'Krm'),
        array('l' =>  9, 'b' =>  'RB', 'a' => 'C', 'd' => 'qty_sisa', 'h' => 'Sisa'),
        array('l' => 20, 'b' =>  'RB', 'a' => 'C', 'd' => 'tagihan_awal', 'h' => 'Awal'),
        array('l' => 20, 'b' =>  'RB', 'a' => 'C', 'd' => 'tagihan_upd', 'h' => 'Update'),
        array('l' => 20, 'b' =>  'RB', 'a' => 'C', 'd' => 'nilai_barang', 'h' => 'Nilai Barang'),
        array('l' => 15, 'b' =>  'RB', 'a' => 'C', 'd' => 'hari_gantung', 'h' => 'Gantung (Hari)')
    );

    function create_pdf($data) {
        $this->SetMargins(5,0,5);
        $this->y_min = $this->GetY();
        $this->CI = & get_instance();
        $this->AddPage('L','A3');

        $this->FormSummary('LAPORAN PENJUALAN SALES ORDER', $data['header'], 25);
        $this->TableHeader($this->GetY()+5);

        $no = 1;
        foreach ($data['detail'] as $row) {
            $max_t = 4;
            foreach($this->map as $key => $column) {
                $this->map[$key]['t'] = $this->GetMultiCellHeight($column['l'], 4, $row->$column['d']);
                $max_t = $this->map[$key]['t'] > $max_t ? $this->map[$key]['t'] : $max_t;
            }
            if($this->y_max - $this->GetY() < $max_t) $this->manualPageBreak(25, true);
            $row->no_urut = $no;
            $this->TableData($row, $max_t);
            $no++;
        }
//
//        $this->Approval($data['header']);
        $this->AliasNbPages();
        $this->Output('tes','I');
        $periode = $data['header']->dari_tgl.'-'.$data['header']->sampai_tgl;
        $this->Output("Lap_SO_Periode_$periode",'I');
    }

    function Header() {
        if($this->CI == null) {
            $this->CI = & get_instance();
        }
        $company_name = "PT. SURYA KENCANA KERAMINDO\nMITRA BANGUNAN SUPERMARKET";
        //company_name
        $this->SetFont($this->font, 'B', 12);
        $this->MultiCell(0,8, $company_name,0,'C');

        $this->SetLineWidth(.5);
        $this->Line(5, 20, 415, 20);
        $this->Ln(0);
    }
    function FormSummary($title, $data, $y) {
        $periode = $data->dari_tgl.' - '.$data->sampai_tgl;
        $this->SetY($y);
        $this->SetFont($this->font, 'B', 12);
        $this->Write(5,$title);
        $this->Ln(5);
        $this->SetFont($this->font, '', 12);
        $this->Cell(0.1*$this->x_max,5,'Periode')->Cell(3,5,":")->Cell(0,5,$periode)->Ln(5);
    }

    function TableHeader($y = 25) {
        $this->SetFont($this->font, 'B', 8);
        $x = $this->x_min;
        foreach($this->map as $key => $row) {
            $tinggi = 10;
            if(strpos($row['d'],'qty') !== false || strpos($row['d'],'tagihan_') !== false) {
                $tinggi = 5;
                $this->SetY($y+5);
            } else {
                $this->SetY($y);
            }
            if(in_array($key, array(4, 6, 13, 14))) $tinggi = 5;

            $this->SetX($x);

            $this->MultiCell($this->ScaleX($row['l']), $tinggi, $row['h'], 'T'.$row['b'], 'C');
            $x = $x + $row['l'];
        }

        $this->SetXY($this->ScaleX(185+$this->x_min),$y);
        $this->Cell($this->ScaleX(27),5,'Qty','TR',0,'C');
        $this->SetXY($this->ScaleX(212+$this->x_min),$y);
        $this->Cell($this->ScaleX(40),5,'Tagihan','TR',0,'C');
        $this->SetXY($this->x_min,$y+10);
        $this->SetFont($this->font, '', 12);
    }

    function TableData($data, $tinggi = 10) {
        $this->SetFont($this->font, '', 10);
        $x = $this->x_min;
        $y = $this->GetY();

        for($i = 0;$i < count($this->map);$i++) {
            $content = $this->map[$i]['d'];
//            $this->MultiCell($this->map[$i]['l'],(4/$this->map[$i]['t']) * $tinggi, $this->map[$i]['t'].'/'.$tinggi, $this->map[$i]['b'], $this->map[$i]['a']);
            $this->MultiCell($this->map[$i]['l'],(4/$this->map[$i]['t']) * $tinggi, $data->$content, $this->map[$i]['b'], $this->map[$i]['a']);
            $x = $x+$this->map[$i]['l'];
            $this->SetXY($x,$y);
        }

        $y += $tinggi;
        $this->SetXY($this->x_min,$y);
        $this->SetFont($this->font, '', 10);
    }

}