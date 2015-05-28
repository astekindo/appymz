<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! class_exists('Controller'))
{
	class Controller extends CI_Controller {}
}

class Auth extends Controller {
	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	function logged_in(){
		return $this->my_auth->logged_in();
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	function login()
	{
		if( ! $this->input->is_ajax_request()){
			if ( $this->my_auth->logged_in() ) {
            	//redirect them to the base_url() if looged in
            	redirect(base_url(), 'refresh');
        	}

			$this->load->view('login', array('boleh' => $this->valuser()));
		}else{
			$username = isset($_POST['username']) ? $this->db->escape_str($this->input->post('username',TRUE)) : '';
			$password = isset($_POST['password']) ? $this->input->post('password',TRUE) : '';

			$error_message = "";

			if ($username == "")
			{
				$error_message .= "- " . $this->lang->line('error_username_missing') . "\n";
			}
			if ($password == "")
			{

				$error_message .= "- " . $this->lang->line('error_password_missing') . "\n";
			}


			if ($error_message != "")
			{
				$validation['error'] = TRUE;
				$validation['message'] = $error_message;

				echo json_encode($validation);
				return;
			}

			if ($this->my_auth->login($username, $password)){
				// if the login is successful

				// redirect
				$result['error'] = FALSE;
				$result['message'] = $this->my_auth->messages();
				$result['redirect'] = base_url();
			}else{
				// if the login was un-successful
				$result['error'] = TRUE;
				$result['message'] = $this->my_auth->errors();
			}

			echo json_encode($result);
			return;
		}
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	function logout()
	{
		//log the user out
		$logout = $this->my_auth->logout();

		if( ! $this->input->is_ajax_request()){
			//redirect them back to the page they came from
			redirect(site_url('auth/login'), 'refresh');
		}else{
			echo '{"success":true,"errMsg":""}';
			exit;
		}
	}

	/**
	 * @author dhamarsu
	 * @editedby luxse
	 * @lastedited 2 jan 2012
	 */
	public function change_password(){
		$old_password = isset($_POST['old_password']) ? $this->input->post('old_password',TRUE) : '';
		$new_password = isset($_POST['new_password']) ? $this->input->post('new_password',TRUE) : '';
		$re_new_password = isset($_POST['re_new_password']) ? $this->input->post('re_new_password',TRUE) : '';

		$error_message = "";

		if($new_password != "" && $re_new_password != ""){
			if($new_password != $re_new_password){
				$error_message .= $this->lang->line('newpassword_notmatch');
			}
		}

		if ($error_message != "")
		{
			echo '{"success":false,"errMsg":"' . $error_message . '"}';
			exit;
		}

		if ($this->my_auth->change_password($this->session->userdata('username'), $old_password, $new_password)){
			$result = '{"success":true,"errMsg":""}';
		}else{
			$result = '{"success":false,"errMsg":"' . $this->my_auth->errors() . '"}';
		}

		echo $result;
		exit;
	}

	public function valuser() {
		//return true;
		//bypass otentikasi u/ ip 172.17.10.*
            return true;
            if(strpos($_SERVER['REMOTE_ADDR'], '172.17.10.') !== false) {
			return true;
		}

		$ipAddress=str_replace('.', '\.', $_SERVER['REMOTE_ADDR']);

		$perintah="arp -e | grep eth0 | grep '^$ipAddress\s' | awk '{print $3}'";
		exec($perintah, $mac) or $result = false;
		if($result !== false) {
			$result = $this->auth_model->get_mac($mac[0]);
		}
		return $result;
	}

}
