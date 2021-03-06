<?php
require_once ("secure_area.php");
class Enterprises extends Secure_area
{
	function __construct()
	{
            parent::__construct('enterprises');
            //force_ssl();
	}
	
	function index($success_msg = 0, $success = 1)
	{	
            $show_deleted=$this->input->post('show_deleted');                
            $data['show_deleted']=$show_deleted;
            $this->session->set_userdata('subsidary_id',$this->session->userdata('subsidary_id'));

            $data['controller_name']=strtolower($this->uri->segment(1));
            $data['form_width']= $this->get_form_width();
            $data['manage_table'] = get_enterprises_manage_table($this->Enterprise->get_all(),$this,$show_deleted);
            $data['margin'] = $this->margin_footer();
            $data['success_msg'] = $success_msg;
            $data['success'] = $success;
            $this->load->view('enterprises/manage', $data);
	}
	
	function get_form_width()
	{
		return 360;
	}
	
        function margin_footer()
        {		
                $banners = $this->session->userdata('real_banners_showed');
                $rows = (($banners%2)==0)?($banners/2):($banners/2)+1;
                $margin = ($rows == 0)?(1 * $this->config->item('banner_side_height')):($rows * $this->config->item('banner_side_height'));
                return ($margin-30);
        }
        
	/*
	Loads the subsidary edit form
	*/
	function view($subsidary_id=-1, $enterprise_id=-1)
	{	
            $this->select($subsidary_id, false);
            if($enterprise_id!=-1)
                $data['enterprise_id'] = $enterprise_id;
            else
                $data['enterprise_id'] = $this->session->userdata('enterprise_id');

            $data['subsidary_info']=$this->Appconfig->get_info($subsidary_id);
            $this->load->view("enterprises/form",$data);
	}
	
	function createEnterprise($enterprise_id=-1)
	{		
	 	$data['enterprise_id']	=$enterprise_id;
		if($enterprise_id!=-1)	
                    $data['name']=$this->Enterprise->getName($enterprise_id);				
		else	
                    $data['name']="";	
                $data['permi_gr_reports_days']=30;
		$data['permi_uncomplete_sale_days']=30;
		$data['permi_delivery_days']=30;
		$data['gr_reports']=$this->Enterprise->get_permi_gr_reports($enterprise_id);
		$data['uncomplete_sale']=$enterprise_id == -1? false : $this->Enterprise->get_permi_uncomplete_sale($enterprise_id);
		$data['delivery']=$enterprise_id == -1? false : $this->Enterprise->get_permi_delivery($enterprise_id);
                $data['hide_banners']=$this->Enterprise->get_permi_hide_banners($enterprise_id);		
		$data['currency_id']=$this->Enterprise->get_currency($enterprise_id);
                $this->load->view("enterprises/formEnterprise",$data);
	}

	function save_create_enterprise_config($enterprise_id=-1)
	{
            if($this->input->post('submited') == 'yes')
            {
                $data = array(
                    'name'=>$this->input->post('enterprise'),		
                    'permi_gr_reports'=>$this->input->post('permi_gr_reports'),
                    'permi_uncomplete_sale'=>$this->input->post('permi_uncomplete_sale'),
                    'permi_delivery'=>$this->input->post('permi_delivery'),
                    'permi_hide_banners'=>$this->input->post('permi_hide_banners'),	
                    'permi_gr_reports_days'=>$this->input->post('permi_gr_reports_days'),
                    'permi_uncomplete_sale_days'=>$this->input->post('permi_uncomplete_sale_days'),
                    'permi_delivery_days'=>$this->input->post('permi_delivery_days'),	
                    'currency_id'=>$this->input->post('currency')	
                );		

                if($enterprise_id==-1)
                    $data['company']=$this->input->post('subsidary');		
                 $success_save = $this->Enterprise->save($data,$enterprise_id);
              
                //$this->Appconfig->save_subsidary($batch_save_data,$subsidary_id); error

                $success = 0;
                if($enterprise_id == -1 && $success_save)
                {
                    $success_msg = $this->lang->line('enterprises_successful_adding');
                    $success = 1;
                }
                else if($enterprise_id != -1 && $success_save)
                {
                    $success_msg = $this->lang->line('enterprises_successful_updating');
                    $success = 1;
                }
                else if($enterprise_id == -1 && !$success_save)
                   $success_msg = $this->lang->line('enterprises_error_adding_updating');
                else if($enterprise_id != -1 && !$success_save)
                    $success_msg = $this->lang->line('enterprises_error_adding_updating');

                $success_msg.= ' '.$data['name']; 

                $this->index($success_msg, $success);
            }
            else
               $this->index();
	}
        
	function save_config($subsidary_id=-1, $enterprise_id=-1)
	{
            if($this->input->post('submited') == 'yes')
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
                    //'default_tax_1_name'=>$this->input->post('default_tax_1_name'),		
                    'default_tax_2_rate'=>$this->input->post('default_tax_2_rate'),	
                    //'default_tax_2_name'=>$this->input->post('default_tax_2_name'),		
                    'return_policy'=>$this->input->post('return_policy'),
                    'language'=>$this->input->post('language'),
                    'timezone'=>$this->input->post('timezone'),
                    //'print_after_sale'=> $this->input->post('print_after_sale')	
                    'print_after_sale'=> $this->input->post('print_after_sale') != ''? '1':'0'	,
                     'currency_id'=>$this->input->post('currency')
                );
                $this->Appconfig->batch_save($batch_save_data);
                $success_save = $this->Subsidary->save($batch_save_data,$subsidary_id, $enterprise_id);
                $this->lang->switch_to($batch_save_data['language']);
                
                if($success_save)
                    $this->Item->update_taxes($subsidary_id);

                $success = 0;
                if($subsidary_id == -1 && $success_save)
                {
                    $success_msg = $this->lang->line('subsidaries_successful_adding');
                    $success = 1;
                }
                else if($subsidary_id != -1 && $success_save)
                {
                    $success_msg = $this->lang->line('subsidaries_successful_updating');
                    $success = 1;
                }
                else if($subsidary_id == -1 && !$success_save)
                   $success_msg = $this->lang->line('subsidaries_error_adding_updating');
                else if($subsidary_id != -1 && !$success_save)
                    $success_msg = $this->lang->line('subsidaries_error_adding_updating');

                $success_msg.= ' '.$batch_save_data['company'];
                
                $this->index($success_msg, $success);
            }
            else
                $this->index();

	}
	
	function select($subsidary_id, $redirect=true)
	{
		$this->Enterprise->setSubsidary($subsidary_id);
		//$this->index();
		if($redirect)
		redirect('enterprises');	
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
		//else //aqui ser�a mandar un mensaje informando que la subsidiaria seleccionada pertenece a esta empresa.
		

		$this->index();			
	}
	
	function delete_subsidary_of_enterprise($subsidary_id)
	{ 
		$subsidaryID = $this->session->userdata('subsidary_id');
		
		if(!($subsidary_id == $subsidaryID))
			$this->Enterprise->delete_Subsidary($subsidary_id);
		//else //aqui ser�a mandar un mensaje informando que esa subsidiaria es la que est� seleccionada.
		
		$this->index();	
	}
	
	function UNdelete_subsidary_of_enterprise($subsidary_id)
	{	
                $show_deleted=$this->input->post('show_deleted');
                $data['show_deleted']=$show_deleted;
		$this->session->set_userdata('subsidary_id',$this->session->userdata('subsidary_id'));
		
		$this->Enterprise->UNdelete_Subsidary($subsidary_id);
		$data['controller_name']=strtolower("enterprises");		
		$data['manage_table'] = get_enterprises_manage_table($this->Enterprise->get_all(),$this,$show_deleted);
                $data['margin'] = $this->margin_footer();
		$this->load->view('enterprises/manage', $data);
	}
        
        function dailycheck()
        {
            $this->load->library("taskLib");
            
            $this->tasklib->DailyTask_Checker();
            
            redirect('enterprises');
        }
        
     
        
}
	
?>