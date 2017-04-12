<?php 
require_once ("secure_area.php");
class DineroMailer extends Secure_area 
{
	function __construct()
	{
		parent::__construct();
                //force_ssl();
        }
	
	function index()//modulos
	{			
            $person=$this->Person->get_info($this->session->userdata('person_id'));
            $data['nombre']=$person->first_name;
            $data['apellido']=$person->last_name;   
            $data['telefono']=$person->phone_number;  
            $data['email']=$person->email;           
            $this->session->set_userdata('subsidary_id',$this->session->userdata('subsidary_id'));
            $data['margin'] = $this->margin_footer();
            $this->load->view('dineroMail/modulo', $data);
	} 
	function banners()//banners
	{			
            $person=$this->Person->get_info($this->session->userdata('person_id'));
            $data['nombre']=$person->first_name;
            $data['apellido']=$person->last_name;   
            $data['telefono']=$person->phone_number;  
            $data['email']=$person->email;           
            $this->session->set_userdata('subsidary_id',$this->session->userdata('subsidary_id'));
            $data['margin'] = $this->margin_footer();
            $this->load->view('dineroMail/banner', $data);
	} 
	function saleOk()
	{         
            //$data=array('info'=>$info);
            $this->load->view('dineroMail/buyOk', array());
	} 
    function test()
    { 
        $person=$this->Person->get_info($this->session->userdata('person_id'));
            $data['nombre']=$person->first_name;
            $data['apellido']=$person->last_name;   
            $data['telefono']=$person->phone_number;  
            $data['email']=$person->email;           
        $this->load->view('dineroMail/test', $data);
    }
    
    function saleError()
    {
            //$data=array('info'=>$info);
            $this->load->view('dineroMail/buyError', array());
    }

    function margin_footer()
    {		
                $banners = $this->session->userdata('real_banners_showed');
                $rows = (($banners%2)==0)?($banners/2):($banners/2)+1;
                $margin = ($rows == 0)?(1 * $this->config->item('banner_side_height')):($rows * $this->config->item('banner_side_height'));
                return $margin;
    }
	
}
?>