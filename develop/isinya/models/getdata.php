<?
class Getdata extends CI_Model {

	public function __construct()
    	{
		$this->load->database();
		$this->load->model('fungsi');
        	// Call the Model constructor
        	parent::__construct();
    	}

	public function listtable($field, $sql, $link1, $link2, $par="all"){
		$sColumns[0]='to_char(id,\'999999\')';
		$qColumns[0]='id';
		$x=1;
		foreach($field as $vs){
			$sColumns[$x]='upper('.$vs.')';
			if($vs[0]=='#'){
				$vs=substr($vs, 1);
				$sColumns[$x]='to_char('.$vs.',\'999999999999\')';
			}
			$aColumns[$x]=$vs;
			$qColumns[$x]=$vs;
			$x++;
		}
		$aColumns[$x]='id';
		$qColumns[$x]='id';
/*
		$aColumns = array( 'nik', 'nama_pegawai', 'id' );
		$sColumns = array( 'to_char(id,\'999\')','upper(nik)', 'upper(nama_pegawai)');
		$qColumns = array( 'id', 'nik','nama_pegawai', 'id' );
*/	
		$sIndexColumn = "id";
		$sOrder="";
		$sql1="";
		
		/* DB table to use */
		$sTable = "tb_pegawai";
		
	
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )

		{
			$sLimit = "LIMIT ".pg_escape_string( $_GET['iDisplayLength'] )." OFFSET ".
				pg_escape_string( $_GET['iDisplayStart'] );
		}
		
		
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= $qColumns[ intval( $_GET['iSortCol_'.$i] ) ]."

						".pg_escape_string( $_GET['sSortDir_'.$i] ) .", ";
				}
			}
			
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}
		
		$sWhere = "";
		if ( isset($_GET['sSearch']) )
		{
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($sColumns) ; $i++ )
			{
				$sWhere .= $sColumns[$i]." LIKE '%".pg_escape_string( strtoupper($_GET['sSearch']) )."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		
		for ( $i=0 ; $i<count($sColumns) ; $i++ )
		{
			if(isset($_GET['bSearchable_'.$i]) || isset($_GET['sSearch_'.$i])){
				if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
				{
					if ( $sWhere == "" )
					{
						$sWhere = "WHERE ";
					}
					else
					{
						$sWhere .= " AND ";
					}
					$sWhere .= $sColumns[$i]." LIKE '%".pg_escape_string(strtoupper($_GET['sSearch_'.$i]))."%' ";
				}
			}
		}
			$sQuery = "

			SELECT * FROM(
			".$sql.") as tbx
			$sWhere

			$sOrder
			$sLimit

			";
			
			$rResult  = $this->db->query($sQuery) or die(pg_error());
		
		/* Data set length after filtering */
			$sQuery = "

			SELECT count(*) as jml FROM(
			".$sql.") as tbx
			$sWhere

				

		";
		#exit($sQuery);
		$rResultFilterTotal = $query = $this->db->query($sQuery) or die("Gagal");
		foreach ($query->result() as $row)
		{
			$iFilteredTotal = $row->jml;
		}
		
		
		/* Total data set length */
		$sQuery = "

			SELECT count(*) as jml FROM(
			".$sql.") as tbx";
	
		$rResultTotal =$query = $this->db->query($sQuery) or die("Gagal");
		foreach ($query->result() as $row)
		{
			$iTotal = $row->jml;
		}
		$sEcho="";
		if(isset($_GET['sEcho']))
			$sEcho=$_GET['sEcho'];
	
		$output = array(
			"sEcho" => intval($sEcho),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
	
		foreach ($rResult->result() as $r)
		{
			$row = array();
			$jd=count($qColumns)-1;
			$i=0;
			foreach($qColumns as $v){
				if($i==$jd){
					$act='';
					if($par=='all'){
						$act.='<a href="'.base_url().$link1.'/'.$r->$v.'">
							  	<img src="'.base_url().'img/edit.gif" alt="Edit Data"></a>';
						$act.='<a href="#" onClick="confirmationDel('.$r->$v.',\''.base_url().$link2.'\');">
								<img src="'.base_url().'img/del.gif" alt="Delete Data"></a>';
					}
					if($par=='edit'){
						$act.='<a href="'.base_url().$link1.'/'.$r->$v.'">
							  	<img src="'.base_url().'img/edit.gif" alt="Edit Data"></a>';
					}
					if($par=='delete'){
						$act.='<a href="#" onClick="confirmationDel('.$r->$v.',\''.base_url().$link2.'\');">
								<img src="'.base_url().'img/del.gif" alt="Delete Data"></a>';
					}
					if($par!='report'){
						$row[] = $act;
						 
					}
				}
				else{
					$row[] =$r->$v;
				}

				$i++;
			}
			$output['aaData'][] = $row;
		}
		
		echo json_encode( $output );
    
    }

	
}
?>