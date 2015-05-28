<?
class Login extends CI_Model {

	public function __construct()
    	{
		$this->load->database();
        // Call the Model constructor
        parent::__construct();
		$this->load->library('session');
    	}
	
	public function chkUser($user, $pass){
		$search=array("'", " ", "-", "*", "/");
		$user=str_replace($search, "", $user);
		$pass=str_replace($search, "", $pass);
		$sql1= "SELECT a.id_user, a.username, a.id_usergroup, b.nama_usergroup
				FROM mst.tm_user a
				JOIN mst.tm_usergroup b on b.id_usergroup = a.id_usergroup AND b.aktif is true
				WHERE lower(a.username)=lower('".$user."') AND a.passwd='".md5($pass)."'";
#exit($sql1);
		$query = $this->db->query($sql1);
		$i=0;
		foreach ($query->result() as $row)
		{
			$newdata = array(
		           'username' 		=> $row->username,
		           'id' 			=> $row->id_user,
		           'id_usergroup'   => $row->id_usergroup,
				   'nama_usergroup'	=> $row->nama_usergroup,
		           'logintime' 	=> date("U"),
			);
			$i++;
		}
		if($i>0)
			$this->session->set_userdata($newdata);
		return $i;
    
    }
}
?>
