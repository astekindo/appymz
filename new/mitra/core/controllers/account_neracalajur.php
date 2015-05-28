<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of account_neracalajur
 *
 * @author faroq
 */
class account_neracalajur extends MY_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('account_neracalajur_model','nl_model');
    }
    public function get_child_level($findhead, $child, $level) {
        $resArr = array();
//        print 'find '.$findhead."\n";

        foreach ($child as $c) {
            if ($c->parent_kd_akun == $findhead) {
                $levelt = $level + 1;                
                array_push($resArr, array('groupakun'=>$c->groupakun,'isheader' => $c->header_status,'kd_akun' => $c->kd_akun, 'nama' => $c->nama, 'jumlah' => $c->jumlah,'total' => NULL ));
                $arrget = $this->get_child_level($c->kd_akun, $child, $levelt);
                if (count($arrget) > 0) {
                    $jumlah=0;
                    foreach ($arrget as $ag) {
                        if(is_null($ag['jumlah'])){
                            $ag['jumlah']=0;
                        }
                        $jumlah=$jumlah+ $ag['jumlah'];
                        array_push($resArr, array('groupakun'=>$ag['groupakun'],'isheader'=>$ag['header_status'],'kd_akun' => $ag['kd_akun'], 'nama' => $ag['nama'], 'jumlah' =>$ag['jumlah'],'total' => NULL ));
                    }
                   // array_push($resArr, array('groupakun'=>$c->groupakun,'kd_akun' => $levelt . '-' . $c->kd_akun, 'nama' => 'TOTAL '.$c->nama, 'jumlah' =>$jumlah));
                     array_push($resArr, array('groupakun'=>$c->groupakun,'isheader' => $c->header_status,'kd_akun' => $c->kd_akun, 'nama' => 'TOTAL '.$c->nama, 'jumlah' =>NULL,'total' => $jumlah ));
                }
            }
        }
        return $resArr;
    }

    public function get_level($head, $child) {
        $resArr = array();
        $level = 0;
//        print json_encode($child)."\n";

        foreach ($head as $h) {
            //array_push($resArr, array('kd_akun' => $level.'-'.$h->kd_akun,'parent_kd_akun' =>''));
            $arrch = $this->get_child_level($h->kd_akun, $child, $level);
            if (count($arrch) > 0) {
                foreach ($arrch as $ac) {
                    array_push($resArr, $ac);
                }
            }
            $level = 0;
        }
        return $resArr;
    }
    
    public function get_rows() {
//        $thbl=isset($_POST['thbl']) ? $this->db->escape_str($this->input->post('thbl', TRUE)) : null;
//        $kd_cabang=isset($_POST['kd_cabang']) ? $this->db->escape_str($this->input->post('kd_cabang', TRUE)) : null;
        $head = $this->nl_model->getheader();
        $child = $this->nl_model->getchild();//getchild_bln_berjalan($thbl,$kd_cabang);
        $resArr = $this->get_level($head, $child);

        $total=count($resArr);
        
        $results = '{success:true,record:' . $total . ',data:' . json_encode($resArr) . '}';
        echo $results;
    }
}

?>
