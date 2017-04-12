<?php
class Login extends Controller 
{
	function __construct()
	{
		parent::__construct();
                //force_ssl();
        }
	
	function index($lang='english')
	{
			
                $this->session->set_userdata('login', 'login');

                $lang = $this->session->userdata('lang');
                if(isset($lang))
                        $this->lang->switch_to($lang);
                $data['lang'] = $lang;
					
		$data['imagenes'] = $this->Campaign_model->get_nextadvises('login');
                
		if($this->Employee->is_logged_in())
		{
			redirect('home');
		}
		else
		{
			$this->form_validation->set_rules('username', 'lang:login_undername', 'callback_login_check');
                        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
                        
                        if($this->form_validation->run() == FALSE)
			{
				$this->load->view('login', $data);
			}
			else
			{	                               
				redirect('home');				
			}
		}
	}
        
	function show_publish_how($lang='english')
	{	
		if($lang != 'spanish')$lang='english';	
		$this->lang->switch_to($lang);
		$this->load->view("login_publish_how",array('lang'=>$lang));
	}
        
	function show_menu($lang='english',$menu='About_Us')
	{		
            if($lang != 'spanish')$lang='english';
		$this->lang->switch_to($lang);           
		$this->load->view("login_show_menu",array('lang'=>$lang,'menu'=>$menu));
	}
        
	function change_lang($lang='english')
	{
		$data['imagenes'] = $this->Campaign_model->get_nextadvises('login');
		
                $login = $this->session->userdata('login');
                
		if (isset($lang) && $lang == 'spanish')
                {
                $this->lang->switch_to('spanish');
                }	
		else
                {	
                $this->lang->switch_to('english');	
                }
		
                $this->config->set_item('language', $lang); 
                $this->session->set_userdata('lang', $lang);
               
                $data['lang'] = $lang;
                if($login == "recoverUserPass")
                    $this->load->view("login/recoverUserPass", array('lang'=>$lang,'username'=>'','validNickname'=>"NULL",'mailExist'=>"NULL",'mail'=>'','useMail'=>1,'question'=>'NULL','answer'=>'','questionExist'=>"NULL",'success'=>'NULL','newPassword'=>'NULL')); 
                else if($login == "registerEnterprise")
                    $this->load->view('register',$data);
                else if($login == "registerAdviser")
                     $this->load->view('registeradviser',$data);
                else $this->load->view('login',$data);                
	}
        
        function change_lang_menu($menu,$lang='english')
	{
		
                $this->session->set_userdata('lang', $lang);
		
		if (isset($lang) && $lang == 'spanish')
			{
			$this->lang->switch_to('spanish');
			}	
		else
			{	
			$this->lang->switch_to('english');	
			}
			
		$data['lang'] = $lang;
                $data['menu'] = $menu;
                
		$this->load->view('login_show_menu',$data);	 	 
	}
        
	function login_check($username)
	{
		$password = $this->input->post("password");	
	
		if($this->Employee->existsUsername($username,$password))
                {     
                    if(!($this->Employee->have_subsidary($username)) && 
                            !($this->Employee->is_AdviserUser_PerUserName($username)))
                    {
                            $this->form_validation->set_message('login_check', $this->lang->line('login_invalid_subsidary'));
                            return false;
                    }
                    else if($this->Employee->is_AdviserUser_PerUserName($username) && 
                            ($this->Employee->is_AdviserUser_Deny($username)))
                    {
                            $this->form_validation->set_message('login_check', $this->lang->line('login_invalid_adviser'));
                            return false;
                    }
                    else
                        $this->Employee->login($username,$password);
                }
		else
		{
			$this->form_validation->set_message('login_check', $this->lang->line('login_invalid_username_and_password'));
			return false;
		}
                
		return true;		
	}
        
           
        function recover_User_And_Password($lang='english')
	{
            $this->session->set_userdata('login', 'recoverUserPass');
            if($lang != 'spanish')$lang='english';	
            $this->lang->switch_to($lang);
            $this->load->view("login/recoverUserPass", array('lang'=>$lang,'username'=>'','validNickname'=>"NULL",'mailExist'=>"NULL",'mail'=>'','useMail'=>1,'question'=>'NULL','answer'=>'','questionExist'=>"NULL",'success'=>'NULL','newPassword'=>'NULL'));      
	}
        
	function generate_Password($lang='english')
	{
        if($lang != 'spanish')$lang='english';
		$this->lang->switch_to($lang);	
        $username=$this->input->post('username');
        $useMail=$this->input->post('useMail');
        $validNickname="NULL";
        $mailExist="NULL";
		$questionExist="NULL";
        $mail='';
        $question=$this->input->post('question');
		$answer=$this->input->post('answer');
		$success="NULL"; 
		$newPassword="NULL";       
        if($this->Employee->validate_user($username))
        {
				//echo '0';
                $newPassword=random_string();                    
                if($useMail)
                {
					//echo '1';
                    $mail=$this->Employee->change_password_using_mail($username,$newPassword,$lang);
                    if($mail)
                    {			
                        //echo 'se ha enviado un correo con el nombre y contrasenna: '.$newPassword;
						//echo '2';
                        $validNickname="1";
                        $mailExist="1";
						$questionExist="0";                           
                    }
                    else
                    {
                        //echo 'no se pudo cambiar el password pq no tiene correo';
						//echo '3';
                        $validNickname="1";
                        $mailExist="0";
						//$useMail="0"; 	
						$questionExist="0";			
                    }
                }
        	    else
                {		
				    //echo '4';			
					//$mailExist="0";
                    $questionArray=$this->Employee->change_password_whitout_mail($username,$newPassword,$answer);
					$question=$questionArray['question'];
                    if($questionArray['success'])
                    {	
						//echo '5';
						$success="1";		
                        $validNickname="1";
                        $questionExist="1"; 	                       
                    }
                    else
                    {
						//echo '6';
						$success="0";
                        $validNickname="1";
                        if($question)$questionExist="1"; 
						else $questionExist="0"; 				
                    }
                }
        }
        else
        {
            //echo 'usuario no valido';
			//echo '7';
            $validNickname="0";
            $mailExist="0";
        }
        //echo "validNickname: $validNickname and mailExist: $mailExist";
        $this->load->view("login/recoverUserPass", array('lang'=>$lang,'username'=>$username,'validNickname'=>$validNickname,'mailExist'=>$mailExist,'mail'=>$mail,'useMail'=>$useMail,'question'=>$question,'answer'=>$answer,'questionExist'=>$questionExist,'success'=>$success,'newPassword'=>$newPassword));     
	}

    function testmails($email, $language)
    {
        if($email)
        {
            $this->tasklib->Sent_Letter_To_Person('enterprise_created', $email, $language, 'Ariel F. Cabañas',null,null,null,null,null,null,null);
            $this->tasklib->Sent_Letter_To_Person('inactivity_account', $email, $language, 'Ariel F. Cabañas',null,null,null,null,null,null,null);
            $this->tasklib->Sent_Letter_To_Person('recover_username', $email, $language, 'Ariel F. Cabañas',null,null,null,null,null,null,null);
            $this->tasklib->Sent_Letter_To_Person('recover_password', $email, $language, 'Ariel F. Cabañas', $username='username', $password='password', null,null,null,null,null);
            $this->tasklib->Sent_Letter_To_Person('warning_module_trial_expire', $email, $language, 'Ariel F. Cabañas', null,null,$days_trial=998,null,null,null,null);
            $this->tasklib->Sent_Letter_To_Person('module_trial_expired', $email, $language, 'Ariel F. Cabañas',null,null,null,null,null,null,null);
            $this->tasklib->Sent_Letter_To_Person('module_purchased', $email, $language, 'Ariel F. Cabañas', null,null,null,"cafeteria....ok", null,null,null);
            $this->tasklib->Sent_Letter_To_Person('banner_purchased', $email, $language, 'Ariel F. Cabañas', null,null,null,null,$impresion_amount=998,null,null);

            //echo "mandado email a: $email en idioma $language";
        }
    }

}
?>