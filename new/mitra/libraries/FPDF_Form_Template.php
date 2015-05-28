<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('fpdf/fpdf.php');

class FPDF_Form_Template extends FPDF {
    private $CI;

    protected $font = 'courier';
    protected $pg_height = 210;
    protected $pg_width = 297;
    protected $y_max = 185;
    protected $x_max = 287;
    protected $margin = 10;

    protected $data;

    function Header() {
        if($this->CI == null) {
            $this->CI = & get_instance();
        }
        $company_name = '';
        //PT. SURYA KENCANA KERAMINDO
        $company_name = "$company_name\nMITRA BANGUNAN SUPERMARKET";
        //company_name
        $this->SetFont($this->font, 'B', 12);
        $this->MultiCell(0,8, $company_name,0,'C');

        $this->SetLineWidth(.5);
        $this->Line(5, 20, 292, 20);
        $this->Ln(0);
    }

    function TableHeader($map, $y = 40) {
        $this->SetFont($this->font, 'B', 12);
        $x = 10;
        $this->SetXY($x,$y);
        foreach($map as $row) {
            $this->MultiCell($row['l'], 10, $row['h'], 'T'.$row['b'], 'C');
            $x += $row['l'];
            $this->SetXY($x,$y);
        }
        $this->SetXY(10,$y+10);
        $this->SetFont($this->font, '', 12);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetLineWidth(.3);
        $this->Line(10, 190, 287, 190);
        $this->SetFont($this->font, 'I', 12);
        $this->Cell(50, 5,date('d-M-Y H:i',time()), 0,'L')
            ->Cell(150)
            ->Cell(50, 5, '', 0, 0, 'L')
            ->Cell(0, 5, 'Page ' . $this->PageNo() .' of {nb}', 0, 0, 'R');
    }


    function HitungLebar($persen, $sisa = 0, $lebar = 0) {
        if($lebar === 0) $lebar = $this->x_max;
        $r_lebar = $sisa === 0 ? round($persen * $lebar) : round($persen * ($lebar-$sisa));
        return $r_lebar;
    }

    function manualPageBreak($start = 35, $show_header = false) {
        $this->AddPage('L');
        if($show_header) {
            $this->TableHeader($this->map, $start);
            $this->SetXY(10, $this->GetY());
        } else {
            $this->SetXY(10, $start);
        }
    }
    /**
     * Override method
     */

    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='') {
        parent::Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);
        return $this;
    }

    function Spacer($w, $h=0, $border=0,$fill = false) {
        parent::Cell($w,$h,'',$border,0,'C',$fill);
        return $this;
    }

    function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false) {
        parent::MultiCell($w, $h, $txt, $border, $align, $fill);
        return $this;
    }

    function GetMultiCellHeight($w, $h, $txt, $border=null, $align='J') {
        $cw = &$this->CurrentFont['cw'];
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
        $s = str_replace("\r",'',$txt);
        $nb = strlen($s);
        if($nb>0 && $s[$nb-1]=="\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $height = 0;
        while($i<$nb)
        {
            // Get next character
            $c = $s[$i];
            if($c=="\n")
            {
                // Explicit line break
                if($this->ws>0)
                {
                    $this->ws = 0;
                    $this->_out('0 Tw');
                }
                //Increase Height
                $height += $h;
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                continue;
            }
            if($c==' ')
            {
                $sep = $i;
                $ls = $l;
                $ns++;
            }
            $l += $cw[$c];
            if($l>$wmax)
            {
                // Automatic line break
                if($sep==-1)
                {
                    if($i==$j)
                        $i++;
                    if($this->ws>0)
                    {
                        $this->ws = 0;
                        $this->_out('0 Tw');
                    }
                    //Increase Height
                    $height += $h;
                }
                else
                {
                    if($align=='J')
                    {
                        $this->ws = ($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                        $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
                    }
                    //Increase Height
                    $height += $h;
                    $i = $sep+1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
            }
            else
                $i++;
        }
        // Last chunk
        if($this->ws>0)
        {
            $this->ws = 0;
            $this->_out('0 Tw');
        }
        //Increase Height
        $height += $h;

        return $height;
    }

}