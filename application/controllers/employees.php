<?php
require_once ("person_controller.php");
class Employees extends Person_controller
{
	function __construct()
	{
		parent::__construct('employees');
                //force_ssl();
	}
	
	function index()
	{
		$this->session->set_userdata('subsidary_id',$this->session->userdata('subsidary_id'));
		
		$data['controller_name']=strtolower($this->uri->segment(1));
		$data['form_width']=$this->get_form_width();
		$data['manage_table']=get_people_manage_table($this->Employee->get_all(),$this);
		$data['margin'] = $this->margin_footer();

        $this->load->view('people/manage',$data);
	}
	
	/*
	Returns employee table data rows. This will be called with AJAX.
	*/
	function search()
	{
		$search=$this->input->post('search');
		$data_rows=get_people_manage_table_data_rows($this->Employee->search($search),$this);
		echo $data_rows;
	}
	
	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		$suggestions = $this->Employee->get_search_suggestions($this->input->post('q'),$this->input->post('limit'));
		echo implode("\n",$suggestions);
	}
	
	/*
	Loads the employee edit form
	*/
	function view($employee_id=-1)
	{
          
		$data['person_info']=$this->Employee->get_info($employee_id);
		
		//has_permission
		//if($this->session->userdata('enterprise_id'))
		//	$data['all_modules']=$this->Module->get_all_modules(false);
		
		//$logged_person_id = $this->session->userdata('person_id');
		//if($this->Employee->has_permission("enterprises", $logged_person_id))
			//$data['all_modules']=$this->Module->get_all_modules(true);
		//
        if($this->Employee->is_SuperUser($this->session->userdata('person_id')))
			$data['all_modules']=$this->Module->get_all_modules(true);
        else
			$data['all_modules']=$this->Module->get_all_modules(false);
		
        $data['is_adviser_user'] = 0;
        
		$this->load->view("employees/form",$data);
	}
	
	/*
	Inserts/updates an employee
	*/
	function save($employee_id=-1)
	{
		$person_data = array(
		'first_name'=>$this->input->post('first_name'),
		'last_name'=>$this->input->post('last_name'),
		'email'=>$this->input->post('email'),
		'phone_number'=>$this->input->post('phone_number'), 
		'address_1'=>$this->input->post('address_1'),
		'address_2'=>$this->input->post('address_2'),
		'city'=>$this->input->post('city'),
		'state'=>$this->input->post('state'),
		'zip'=>$this->input->post('zip'),
		'country'=>$this->input->post('country'),
		'comments'=>$this->input->post('comments')
		);
                
		$permission_data = $this->input->post("permissions")!=false ? $this->input->post("permissions"):array();
		
		//Password has been changed OR first time password set
		if($this->input->post('password')!='')
		{
			$employee_data=array(
			'username'=>$this->input->post('username'),
			'password'=>md5($this->input->post('password')),
			'secret_answer'=>$this->input->post('secret_answer'),
			'secret_question'=>$this->input->post('secret_question'))
			;
		}
		else //Password not changed
		{
			$employee_data=array(
			'username'=>$this->input->post('username'),
			'secret_answer'=>$this->input->post('secret_answer'),
			'secret_question'=>$this->input->post('secret_question'));
		}
		
		if ($_SERVER['HTTP_HOST'] == 'demo.phppointofsale.com' && $employee_id == 1)
		{
			//failure
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('employees_error_updating_demo_admin').' '.
			$person_data['first_name'].' '.$person_data['last_name'],'person_id'=>-1));
		}
		elseif($this->Employee->save($person_data,$employee_data,$permission_data,$employee_id))
		{
			//New employee
			if($employee_id==-1)
			{
				echo json_encode(array('success'=>true,'message'=>$this->lang->line('employees_successful_adding').' '.
				$person_data['first_name'].' '.$person_data['last_name'],'person_id'=>$employee_data['person_id']));
			}
			else //previous employee
			{
				echo json_encode(array('success'=>true,'message'=>$this->lang->line('employees_successful_updating').' '.
				$person_data['first_name'].' '.$person_data['last_name'],'person_id'=>$employee_id));
			}
		}
		else//failure
		{
                        if($this->Employee->validate_user($employee_data['username']))//2013-02-04 HL
                        {
                            echo json_encode(array('success'=>false,'message'=>$this->lang->line('register_unsuccessfully').': '.
                            $employee_data['username'].' ','person_id'=>-1));
                        }
                        else
                        {
                            echo json_encode(array('success'=>false,'message'=>$this->lang->line('employees_error_adding_updating').' '.
                            $person_data['first_name'].' '.$person_data['last_name'],'person_id'=>-1));
                        }
		}
	}
	
	/*
	This deletes employees from the employees table
	*/
	function delete()
	{
		$employees_to_delete=$this->input->post('ids');
		
		if ($_SERVER['HTTP_HOST'] == 'demo.phppointofsale.com' && in_array(1,$employees_to_delete))
		{
			//failure
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('employees_error_deleting_demo_admin')));
		}
		elseif($this->Employee->delete_list($employees_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>$this->lang->line('employees_successful_deleted').' '.
			count($employees_to_delete).' '.$this->lang->line('employees_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('employees_cannot_be_deleted')));
		}
	}
	/*
	get the width for the add/edit form
	*/
	function get_form_width()
	{
		return 650;
	}
	
        function margin_footer()
        {		
                $banners = $this->session->userdata('real_banners_showed');
                $rows = (($banners%2)==0)?($banners/2):($banners/2)+1;
                $margin = ($rows == 0)?(1 * $this->config->item('banner_side_height')):($rows * $this->config->item('banner_side_height'));
                return ($margin-30);
        }
        
        function get_campaigns($person_id = null)
        {
            if($person_id)
            {
                $query = $this->db->get_where('campaigns');
                return $query;
            }
            else
            {
                $query = $this->db->get('campaigns');
                return $query;
            }
        }
}
?>