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
	
	/*
	Loads the subsidary edit form
	*/
	function view($subsidary_id=-1)
	{
		$data['subsidary_info']=$this->Appconfig->get_info($subsidary_id);
		$this->load->view("subsidaries/form",$data);
	}
	
	function save_config($subsidary_id=-1)
	{
		$batch_save_data=array(
		'company'=>$this->input->post('company'),
		'country'=>$this->input->post('country'),
		'address'=>$this->input->post('address'),
		'phone'=>$this->input->post('phone'),
		'email'=>$this->input->post('email'),
		'fax'=>$this->input->post('fax'),
		'website'=>$this->input->post('website'),
		'default_tax_1_rate'=>$this->input->post('default_tax_1_rate'),		
		'default_tax_1_name'=>$this->input->post('default_tax_1_name'),		
		'default_tax_2_rate'=>$this->input->post('default_tax_2_rate'),	
		'default_tax_2_name'=>$this->input->post('default_tax_2_name'),		
		'return_policy'=>$this->input->post('return_policy'),
		'language'=>$this->input->post('language'),
		'timezone'=>$this->input->post('timezone'),
		'print_after_sale'=>$this->input->post('print_after_sale')	
		);
		
		$this->Appconfig->save_subsidary($batch_save_data,$subsidary_id);
		
		$this->index();
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