<?php
require_once ("secure_area.php");
class Enterprises extends Secure_area
{
	function __construct()
	{
		parent::__construct('enterprises');
	}
	
	function index()
	{	
		$this->session->set_userdata('subsidary_id',$this->session->userdata('subsidary_id'));
		
		$data['controller_name']=strtolower($this->uri->segment(1));
		$data['form_width']= $this->get_form_width();
		$data['manage_table'] = get_enterprises_manage_table($this->Enterprise->get_all(),$this);
		$this->load->view('enterprises/manage', $data);
	}
	
	function get_form_width()
	{
		return 360;
	}
		
		
	function select($subsidary_id)
	{
		$this->Enterprise->setSubsidary($subsidary_id);
		$this->index();	
	}
	
	function deleteEnterprise($enterprise_id)
	{
		$subsidaryID = $this->session->userdata('subsidary_id');
		$subs        = $this->Enterprise->get_all_subsidaries_from_enterprise($enterprise_id);
		$selected    = false;
		
		foreach($subs->result() as $sub)
		{
			if( $sub->subsidary_id == $subsidaryID )
				$selected = true;
			else
				$this->Subsidary->delete($sub->subsidary_id);	
		}
		
		/*if(!$selected)
		{
			$this->Enterprise->delete($enterprise_id);
		}*/		
		//else //aqui sería mandar un mensaje informando que la subsidiaria seleccionada pertenece a esta empresa.
		

		$this->index();			
	}
	
	function delete_subsidary_of_enterprise($subsidary_id)
	{ 
		$subsidaryID = $this->session->userdata('subsidary_id');
		
		if(!($subsidary_id == $subsidaryID))
			$this->Enterprise->delete_Subsidary($subsidary_id);
		//else //aqui sería mandar un mensaje informando que esa subsidiaria es la que está seleccionada.
		
		$this->index();	
	}
	
	function UNdelete_subsidary_of_enterprise($subsidary_id)
	{	
		$this->session->set_userdata('subsidary_id',$this->session->userdata('subsidary_id'));
		
		$this->Enterprise->UNdelete_Subsidary($subsidary_id);
		$data['controller_name']=strtolower("enterprises");		
		$data['manage_table'] = get_enterprises_manage_table($this->Enterprise->get_all(),$this);
		$this->load->view('enterprises/manage', $data);
	}

}
	
?>