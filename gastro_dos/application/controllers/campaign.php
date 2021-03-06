<?php
require_once ("secure_area.php");
class Campaign extends Secure_area
{
    function __construct()
    {
        parent::__construct('campaign');
        $this->load->library('auxiliar');
        //force_ssl();
    }

    function index()
    {
        //$this->Campaign_model->get_nextadvises('side');

        $person_id = $this->session->userdata('person_id');

        $campaigns = null;
        if($this->Employee->is_SuperUser($person_id))
            $campaigns = $this->Campaign_model->get_all(null);
        else
            $campaigns = $this->Campaign_model->get_all($person_id);


        $data['controller_name'] = strtolower($this->uri->segment(1));
        $data['form_width'] = $this->get_form_width();
        $data['manage_table'] = get_campaign_manage_table($campaigns, $this); 
        $this->load->view('campaign/manage',$data);
    }

    function get_row()
    {
	$campaign_id = $this->input->post('row_id');
	$data_row = get_campaign_data_row($this->Campaign_model->get_info($campaign_id),$this);
	echo $data_row;
    }
    
    function change_label()
    {
	echo json_encode(array('success'=>TRUE, 'message'=>'All ok'));
    }
    
    function view($campaign_id = -1)
    {
        $data = array();
        $campaign_info = array();

		if($this->Campaign_offer_model->have_actived($this->session->userdata('person_id')))
		{
	        $actived_offers = $this->Campaign_offer_model->get_all_actived($this->session->userdata('person_id'));
	        //$offers_options = array(-1=>'');
	        foreach($actived_offers->result() as $actived_offer)
	                $offers_options[$actived_offer->campaign_offer_id] = $actived_offer->count.' '.$actived_offer->type.'s at '.$actived_offer->position.': $'.number_format($actived_offer->price, 2); 
	
	        if($campaign_id > 0)
	        { 
	            $data['editing'] = false;
	
	            $campaign_info = $this->Campaign_model->get_campaign($campaign_id);
	            if($campaign_info->num_rows() == 1)
	            {
	                $campaign_info = $campaign_info->row_array();
	                
	                $data['offer_summary'] = $this->Campaign_model->get_offer_summary($campaign_info);
	            }
	        }
	        else
	        {
	
	           $data['editing'] = true;
	           $data['offers_options'] = $offers_options;
	
	           $campaign_info['campaign_id'] = -1;
	           $campaign_info['name'] = null;
	           $campaign_info['tooltip'] = null;
	           $campaign_info['is_active'] = true; 
	           $campaign_info['receive_stadistics'] = false;
	           $campaign_info['daily_top'] = null;
	           $campaign_info['link'] = null;
	        }
	
	        $data['campaign_info'] = $campaign_info;
	        $this->load->view('campaign/view', $data);
		}
    }

    function save($campaign_id=-1)
    {
        $data = array();
        $data['name'] = $this->input->post('name');
        //$data['offer'] = $this->input->post('offer'); No se puede porque este array se usa para insertar
        $data['tooltip'] = $this->input->post('tooltip');
        $data['is_active'] = ($this->input->post('is_active') == '') ? 0 : 1;
        $data['link'] = $this->input->post('link'); 
        $data['receive_stadistics'] = ($this->input->post('receive_stadistics') == '') ? 0 : 1; 
        $data['daily_top'] = ($this->input->post('daily_top') =='') ? null : $this->input->post('daily_top');
          
        if($campaign_id == -1)
        {
            if($this->session->userdata('image_large'))
                $data['image_large'] = $this->session->userdata('image_large');
            if($this->session->userdata('image_small'))
                $data['image_small'] = $this->session->userdata('image_small');
            $this->session->unset_userdata('image_large');
            $this->session->unset_userdata('image_small');
        }
        
        $lu = $this->input->post('lunes')    =='' ? '' : $this->input->post('lunes');
        $ma = $this->input->post('martes')   =='' ? '' : $this->input->post('martes');
        $mi = $this->input->post('miercoles')=='' ? '' : $this->input->post('miercoles');
        $ju = $this->input->post('jueves')   =='' ? '' : $this->input->post('jueves');
        $vi = $this->input->post('viernes')  =='' ? '' : $this->input->post('viernes');
        $sa = $this->input->post('sabado')   =='' ? '' : $this->input->post('sabado');
        $do = $this->input->post('domingo')  =='' ? '' : $this->input->post('domingo');
        
        
        $data['week_days'] = $lu.$ma.$mi.$ju.$vi.$sa.$do;
        //Ariel: Parche mientras que los checkbox de 
        //los dias de las semandas estan deshabilitados.
        $data['week_days'] = '0123456';
        
        //$this->Campaign_model->insert_or_update($campaign_id, $data, $this->session->userdata('person_id'), $this->input->post('offer'));
        
        if($this->Campaign_model->save($data, $campaign_id, $this->session->userdata('person_id'), $this->input->post('offer')))
        {
            //New campaign
            if($campaign_id==-1)
            {
                echo json_encode(array('success'=>true,
                                       'message'=>$this->lang->line('campaign_successful_adding').' '.$data['name'],
                                       'campaign_id'=>$data['campaign_id']));
	    	$this->Campaign_offer_model->decrease_campaign($this->session->userdata('person_id'),$this->input->post('offer'));
            }
            else //previous campaign
            {
                echo json_encode(array('success'=>true,'message'=>$this->lang->line('campaign_successful_updating').' '.
                $data['name'],'campaign_id'=>$campaign_id));
            }
        }
        else//failure
        {	
                echo json_encode(array('success'=>false,'message'=>$this->lang->line('campaign_error_adding_updating').' '.
                $data['name'],'campaign_id'=>-1));
        }	
        //redirect('campaign'); 
    }

    /*
    get the width for the add/edit form
    */
    function get_form_width()
    {			
            return 360;
    }
    
//    function click($campaign_id)
//    {
//        $campaign = $this->Campaign_model->get_campaign($campaign_id);
//        if($campaign->num_rows() == 1)
//        {
//            $campaign = $campaign->row_array();
//            $this->Campaign_model->clicked_one_time($campaign);
//            
//            //echo $campaign['link'];
//            
//            redirect($campaign['link']);
//        }
//    }
    
    function delete()
    {
	$campaigns_to_delete=$this->input->post('ids');
		
        if($this->Campaign_model->delete_list($campaigns_to_delete))
	{
            echo json_encode(array('success'=>true,'message'=>$this->lang->line('campaign_successful_deleted').' '.
            count($campaigns_to_delete).' '.$this->lang->line('campaign_one_or_multiple')));
	}
	else
	{
            echo json_encode(array('success'=>false,'message'=>$this->lang->line('campaign_cannot_be_deleted')));
	}
    }
    
    function search()
    {
	$search=$this->input->post('search');
	$data_rows=get_campaing_manage_table_data_rows($this->Campaign_model->search($search),$this);
	echo $data_rows;
    }
	
    /*
    Gives search suggestions based on what is being searched for
    */
    function suggest()
    {
	//$suggestions = $this->Customer->get_search_suggestions($this->input->post('q'),$this->input->post('limit'));
	//echo implode("\n",$suggestions);
    }
    
    //size can be large | small
    function upload_photo($size)
    {
        try 
        {
            $file_name = $_FILES['uploadfile']['name'];
            $ext = pathinfo($file_name);
            $ext = $ext['extension'];
            
            $file_name = uniqid().'.'.$ext;
            
           
            if(@copy($_FILES['uploadfile']['tmp_name'], './images/banners_pics/'.$file_name))
            {
                 if ($dim = @getimagesize('./images/banners_pics/'.$file_name))
                 {
                     $this->session->set_userdata("image_$size", $file_name);
                     echo json_encode(array('success'=>TRUE, 'newfilename'=>$file_name ,'size'=>$size,'type'=>$dim[2],'width'=>$dim[0], 'height'=>$dim[1]));
                 }                 
                 else 
                    json_encode(array('success'=>TRUE, 'message'=>'Not image'));
                
            }
            else
                echo json_encode(array('success'=>FALSE, 'message'=>'Not image'));
           
             
        } 
        catch (Exception $exc) 
        {
            echo json_encode(array('success'=>FALSE, 'message'=>'catch'));
        }
    }
    

}
?>