<?php
require_once ("secure_area.php");
class Home extends Secure_area 
{
	function __construct()
	{
		parent::__construct();
                //force_ssl();  
        }
	
	function index()
	{

		$this->session->set_userdata('subsidary_id',$this->session->userdata('subsidary_id'));
		$data['margin'] = $this->margin_footer();
                $this->load->view('home',$data);
	}
	
	function logout()
	{
            $row = $this->Subsidary->get_info($this->session->userdata('subsidary_id'));
            $this->Employee->logout($row->language,$this->Campaign_model->get_nextadvises('login'));
	}
        
        function margin_footer()
        {		
                $banners = $this->session->userdata('real_banners_showed');
                $rows = (($banners%2)==0)?($banners/2):($banners/2)+1;
                $margin = ($rows == 0)?(1 * $this->config->item('banner_side_height')):($rows * $this->config->item('banner_side_height'));
                return ($margin-70);
        }
        
        function test()
        {
            $this->load->view('test');
        }
}
?>