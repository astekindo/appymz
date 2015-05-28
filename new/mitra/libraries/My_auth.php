<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  My Auth
* 
* Project : Mitra Bangunan POS System
*
* Author: Andhi
*		  andinoerdianto@gmail.com
*         @cahkaibon
*
* Created:  10.04.2013
*
*/

class My_auth
{
	/**
	 * CodeIgniter global
	 *
	 * @var string
	 **/
	protected $ci;


	/**
	 * message (uses lang file)
	 *
	 * @var string
	 **/
	protected $messages;

	/**
	 * error message (uses lang file)
	 *
	 * @var string
	 **/
	protected $errors = array();


	/**
	 * __construct
	 *
	 * @return void
	 * @author Andhi
	 **/
	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->model('auth_model');
		$this->ci->load->helper('cookie');
		
		$this->messages = array();
		$this->errors = array();
	}

	/**
	 * __call
	 *
	 * Acts as a simple way to call model methods without loads of stupid alias'
	 *
	 **/
	public function __call($method, $arguments)
	{
		if (!method_exists( $this->ci->auth_model, $method) )
		{
			throw new Exception('Undefined method Auth::' . $method . '() called');
		}

		return call_user_func_array( array($this->ci->auth_model, $method), $arguments);
	}


	/**
	 * login
	 *
	 * @return void
	 * @author Andhi
	 **/
	public function login($username, $password, $remember=false)
	{
		if ($this->ci->auth_model->login($username, $password, $remember))
		{
			$this->set_message('login_successful');
			return TRUE;
		}

		$this->set_error('login_unsuccessful');
		return FALSE;
	}

	/**
	 * logout
	 *
	 * @return void
	 * @author Andhi
	 **/
	public function logout()
	{
		$this->ci->session->unset_userdata('username');
		$this->ci->session->unset_userdata('kd_group');
		$this->ci->session->unset_userdata('nama_group');
		$this->ci->session->unset_userdata('nama_lengkap');	
		$this->ci->session->unset_userdata('email');
		$this->ci->session->unset_userdata('kd_jabatan');
		$this->ci->session->unset_userdata('nama_jabatan');
		$this->ci->session->unset_userdata('kd_kategori1');
		$this->ci->session->unset_userdata('kd_kategori2');
		$this->ci->session->unset_userdata('kd_kategori3');
		$this->ci->session->unset_userdata('kd_kategori4');
			
		$this->ci->session->sess_destroy();

		return TRUE;
	}

	/**
	 * logged_in
	 *
	 * @return bool
	 * @author Andhi
	 **/
	public function logged_in()
	{
		return (bool) $this->ci->session->userdata('username');
	}
	
	/**
	 * get_accordion_menu
	 *
	 * @return objects
	 * @author Andhi
	 **/
	public function get_accordion_menu($kd_group)
	{
		if (empty($kd_group))
	    {
	    	return FALSE;
	    }
		
		return $this->ci->auth_model->get_accordion_menu($kd_group);
	}
	
	/**
	 * get_accordion_menu
	 *
	 * @return objects
	 * @author Andhi
	 **/
	public function get_tree_menu($module, $kd_group)
	{
		if (empty($module) && empty($kd_group))
	    {
	    	return FALSE;
	    }
		
		return $this->ci->auth_model->get_tree_menu($module, $kd_group);
	}
	
	/**
	 * Change password.
	 *
	 * @return void
	 * @author Andhi
	 **/
	public function change_password($username, $old, $new)
	{
		if ($this->ci->auth_model->change_password($username, $old, $new))
		{
			$this->set_message('password_change_successful');
			return TRUE;
		}

		$this->set_error('password_change_unsuccessful');
		return FALSE;
	}
	
	/**
	 * hash_password.
	 *
	 * @return void
	 * @author Andhi
	 **/
	public function hash_password($password)
	{
		if (empty($password))
	    {
	    	return FALSE;
	    }
		
		return $this->ci->auth_model->hash_password($password);
	}
	

	/**
	 * set_message
	 *
	 * Set a message
	 *
	 * @return void
	 * @author Andhi
	 **/
	public function set_message($message)
	{
		$this->messages[] = $message;

		return $message;
	}

	/**
	 * messages
	 *
	 * Get the messages
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function messages()
	{
		$_output = '';
		foreach ($this->messages as $message)
		{
			$_output .= '<p>' . $this->ci->lang->line($message) . '</p>';
		}

		return $_output;
	}

	/**
	 * set_error
	 *
	 * Set an error message
	 *
	 * @return void
	 * @author Andhi
	 **/
	public function set_error($error)
	{
		$this->errors[] = $error;

		return $error;
	}

	/**
	 * errors
	 *
	 * Get the error message
	 *
	 * @return void
	 * @author Andhi
	 **/
	public function errors()
	{
		$_output = '';
		foreach ($this->errors as $error)
		{
			$_output .= '<p>' . $this->ci->lang->line($error) . '</p>';
		}

		return $_output;
	}

}
