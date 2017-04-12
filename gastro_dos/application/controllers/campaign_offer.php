<?php
require_once ("secure_area.php");
class Campaign_offer extends Secure_area
{
    function __construct()
    {
        parent::__construct('campaign_offer');
        $this->load->library('fecha'); // change Oscar
        //force_ssl();
    }

    function index()
    {
            $campaign_offers = $this->Campaign_offer_model->get_all_actived();

            $data['controller_name'] = strtolower($this->uri->segment(1));
            $data['form_width'] = $this->get_form_width();
            $data['manage_table'] = get_campaign_offer_manage_table($campaign_offers, $this);   
            
            $this->load->view('campaign_offers/manage',$data);
    }
	
    function get_row()
    {
	$campaign_offer_id = $this->input->post('row_id');
	$data_row = get_campaign_offer_data_row($this->Campaign_offer_model->get_info($campaign_offer_id),$this);
	echo $data_row;
    }
    
    /*
    Returns campaing_offer table data rows. This will be called with AJAX.
    */
    function search()
    {
	$search=$this->input->post('search');
	$data_rows=get_campaing_offer_manage_table_data_rows($this->Campaign_offer_model->search($search),$this);
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
        
    function view($campaign_offer_id = -1)
    {
        $data = array();
        $offer_info = array();
        
        if($campaign_offer_id == -1)
        {
            $offer_info['campaign_offer_id'] = -1;
            $offer_info['name'] = '';
            $offer_info['type'] = '';
            $offer_info['count'] = '';
            $offer_info['price'] = '';
            $offer_info['position'] = '';
            $offer_info['date_expire'] = '';
            $offer_info['message'] = '';
           
        }
        else
        {
            $offer = $this->Campaign_offer_model->get_offer($campaign_offer_id);
            if($offer->num_rows() == 0)
                redirect('campaign_offer');
            
            $offer = $offer->row();

            $offer_info['campaign_offer_id'] = $offer->campaign_offer_id;
            $offer_info['name'] = $offer->name;
            $offer_info['type'] = $offer->type;
            $offer_info['count'] = $offer->count;
            $offer_info['price'] = $offer->price;
            $offer_info['position'] = $offer->position;
            $offer_info['date_expire'] = $offer->date_expire;
            $offer_info['message'] = $offer->message;
        }
        
        $data['types'] = $this->Campaign_offer_model->get_types(false);
        $data['positions'] = $this->Campaign_offer_model->get_positions(false);
        $data['offer_info'] = $offer_info;
        
        $this->load->view('campaign_offers/view', $data);
    }
        
    function save($campaign_offer_id=-1)
    {
        $data = array(
        'name'=> $this->input->post('name'),
        'type'=> $this->input->post('type'),
        'count'=> $this->input->post('count'),
        'price'=> $this->input->post('price'),
        'position'=> $this->input->post('position'),
        //$data['date_expire'] = $this->input->post('date_expire');
        'date_expire' => ($this->input->post('date_expire')) ? $this->fecha->date_to_timestamp($this->input->post('date_expire')):NULL,// change Oscar
        'message' => $this->input->post('message'));
        
        //$this->Campaign_offer_model->insert_or_update($campaign_offer_id, $data);
        
        if($this->Campaign_offer_model->save($data,$campaign_offer_id))
	{
            //New campaign offer
            if($campaign_offer_id==-1)
            {
		echo json_encode(array('success'=>true,'message'=>$this->lang->line('campaign_offer_successful_adding').' '.
		$data['name'],'campaign_offer_id'=>$data['campaign_offer_id']));
            }
            else //previous campaign offer
            {
		echo json_encode(array('success'=>true,'message'=>$this->lang->line('campaign_offer_successful_updating').' '.
		$data['name'],'campaign_offer_id'=>$campaign_offer_id));
            }
	}
	else//failure
	{	
            echo json_encode(array('success'=>false,'message'=>$this->lang->line('campaign_offer_error_adding_updating').' '.
            $data['name'],'campaign_offer_id'=>-1));
	}	
        
        //redirect('campaign_offer'); 
    }
    
    /*
    get the width for the add/edit form
    */
    function get_form_width()
    {			
		return 360;
	}
        
    /*function delete($campaign_offer_id)
    {
        $this->Campaign_offer_model->delete($campaign_offer_id);
        
        redirect('campaign_offer');
    }*/
        
    function delete()
    {
	$campaigns_offer_to_delete=$this->input->post('ids');
		
        if($this->Campaign_offer_model->delete_list($campaigns_offer_to_delete))
	{
            echo json_encode(array('success'=>true,'message'=>$this->lang->line('campaign_offer_successful_deleted').' '.
            count($campaigns_offer_to_delete).' '.$this->lang->line('campaign_offer_one_or_multiple')));
	}
	else
	{
            echo json_encode(array('success'=>false,'message'=>$this->lang->line('campaign_offer_cannot_be_deleted')));
	}
    }
}
?>