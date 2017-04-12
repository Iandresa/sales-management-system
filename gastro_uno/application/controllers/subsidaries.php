<?php
require_once ("secure_area.php");
class Subsidaries extends Secure_area
{
	function __construct()
	{
		parent::__construct('subsidaries');
	}
	
	function index()
	{
		$this->session->set_userdata('subsidary_id',$this->session->userdata('subsidary_id'));
		
		$data['controller_name']=strtolower($this->uri->segment(1));
		$data['form_width']= $this->get_form_width();
		$data['manage_table'] = get_subsidaries_manage_table($this->Subsidary->get_all(),$this);
		$this->load->view('subsidaries/manage', $data);
	}

	function get_form_width()
	{
		return 360;
	}
	
	function deactivate($subsidary_id)
	{
		$this->Subsidary->delete($subsidary_id);
	}
	
	function select($subsidary_id)
	{
		$this->Subsidary->setSubsidary($subsidary_id);
		$this->index();	
	}
	
	function delete_subsidary($subsidary_id)
	{
		$this->Subsidary->delete($subsidary_id);
		$this->index();	
	}
	function UNdelete_subsidary($subsidary_id)
	{
		$this->Enterprise->UNdelete($subsidary_id);
		$data['controller_name']=strtolower("enterprises");		
		$data['manage_table'] = get_enterprises_manage_table($this->Enterprise->get_all(),$this);
		$this->load->view('enterprises/manage', $data);
	
	}
}
	
?>