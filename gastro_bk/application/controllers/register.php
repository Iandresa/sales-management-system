<?php
class Register extends Controller 
{
	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		$data = array(
		'name'=>$this->input->post('username'),
		'password'=>md5($this->input->post('password')),
		'enterprise'=>$this->input->post('enterprise'),
		'subsidary'=>$this->input->post('subsidary')
		); 
		
		$this->form_validation->set_rules('username','lang:register_undername', 'callback_register_check');
    	$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			
		if($this->form_validation->run() == FALSE)
		{
			$this->load->view('register');
		}
		else
		{
			$this->Employee->saveRegister($data);
			redirect('home');
		}
	}
	
	function register_check($username)
	{
	    $user = $this->input->post('username');
	    $pass = $this->input->post('password');
	    $enterprise = $this->input->post('enterprise');
	
		if($user == "" || $pass == "" || $enterprise == "")
		{
			$this->form_validation->set_message('register_check', $this->lang->line('common_fields_required_message'));
			return false;
		}
		else if($this->Employee->validate_user($user))
		{
			$this->form_validation->set_message('register_check', $this->lang->line('register_unsuccessfully'));
			return false;
		}
		
		return true;		
	}
}
?>