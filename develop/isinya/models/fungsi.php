<?
class Fungsi extends CI_Model {

	public function __construct()
    {
		$this->load->database();
        $this->load->library('session');
		parent::__construct();
		
    }
	
	public function maxID($tbl, $fl='id'){
		$sql1= "SELECT coalesce(max(".$fl."),0)+1 as idmax FROM ".$tbl;
		$q=$this->db->query($sql1);
		foreach ($q->result() as $r)
		{
			$max=$r->idmax;			
		}
		
		return $max;
	}

	public function maxNIK($tbl, $fl='id'){
		$sql1= "SELECT coalesce(max(".$fl."::integer),0)+1 as idmax FROM ".$tbl;
		$q=$this->db->query($sql1);
		foreach ($q->result() as $r)
		{
			$max=$r->idmax;			
		}
		
		return $max;
	}
	public function maxKode($tbl, $fl='id', $b=4){
		$sql1= "SELECT coalesce(max(substring(".$fl." from ".$b.")::integer),0)+1 as idmax FROM ".$tbl." WHERE status =1";
		#echo $sql1;
		$q=$this->db->query($sql1);
		foreach ($q->result() as $r)
		{
			$max=$r->idmax;			
		}
		
		return $max;
	}
	public function getsdKode($c, $m){
		$sql1= "SELECT distinct on (kode_sdana) kode_sdana
				FROM tb_sdana
				WHERE kode_currency='".$c."' AND kode_mastersdana='".$m."'";
		#echo $sql1;
		$q=$this->db->query($sql1);
		foreach ($q->result() as $r)
		{
			$output=$r->kode_sdana;			
		}
		
		return $output;
		
	}
	public function terbilang ($number) {
	$number = strval($number);
	if (!preg_match("/^[0-9]{1,15}$/", $number)) 
		return(false); 
	$ones = array("", "satu", "dua", "tiga", "empat", 
		"lima", "enam", "tujuh", "delapan", "sembilan");
	$majorUnits = array("", "ribu", "juta", "milyar", "trilyun");
	$minorUnits = array("", "puluh", "ratus");
	$result = "";
	$isAnyMajorUnit = false;
	$length = strlen($number);
	for ($i = 0, $pos = $length - 1; $i < $length; $i++, $pos--) {
		if ($number{$i} != '0') {
			if ($number{$i} != '1')
				$result .= $ones[$number{$i}].' '.$minorUnits[$pos % 3].' ';
			else if ($pos % 3 == 1 && $number{$i + 1} != '0') {
				if ($number{$i + 1} == '1') 
					$result .= "sebelas "; 
				else 
					$result .= $ones[$number{$i + 1}]." belas ";
				$i++; $pos--;
			} else if ($pos % 3 != 0)
				$result .= "se".$minorUnits[$pos % 3].' ';
			else if ($pos == 3 && !$isAnyMajorUnit)
				$result .= "se";
			else
				$result .= "satu ";
			$isAnyMajorUnit = true;
		}
		if ($pos % 3 == 0 && $isAnyMajorUnit) {
			$result .= $majorUnits[$pos / 3].' ';
			$isAnyMajorUnit = false;
		}
	}
	$result = trim($result);
	if ($result == "") $result = "nol";
	return($result);
}
	
	function nvl($val, $replace)
	{
		if ( is_null($val) || $val === '' ) {
			return $replace;
		} else {
			return $val;
		}
	}
	
	public function getSelectedData($table,$data)
	{
		return $this->db->get_where($table, $data);
	}
	
	public function getAllData($table)
	{
		return $this->db->get($table);
	}
}
?>
