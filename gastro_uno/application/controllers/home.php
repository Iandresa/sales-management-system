<?php
require_once ("secure_area.php");
class Home extends Secure_area 
{
	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{
		$this->session->set_userdata('subsidary_id',$this->session->userdata('subsidary_id'));
		$this->load->view("home");
	}
	
	function logout()
	{
		$this->Employee->logout();
	}
}
?>