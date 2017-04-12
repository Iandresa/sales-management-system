<?php
class Campaign_offer_model extends Model{
	
    /*
    Returns all the Campaigns of the $employee_id. If null return all Campaigns;
    */
    function get_all()
    {
        $query = $this->db->get('campaigns_offers');
        return $query;
    }
        
    function get_offer($campaign_offer_id)
    {
        $this->db->where('campaign_offer_id', $campaign_offer_id);
        $query = $this->db->get('phppos_campaigns_offers');
        return $query;
    }
	function increase_campaign($personId,$campaign_offer_id)
	{		
		$q=$this->db->get_where('adviser_offer',array('id_adviser'=>$personId,'id_offer'=>$campaign_offer_id));
		//echo $this->db->last_query();
		if($q->num_rows()==0)
		{
			$this->db->insert('adviser_offer',array('id_adviser'=>$personId,'id_offer'=>$campaign_offer_id, 'amount'=>1));			
		}
		else
		{
			$valorIncrementado=$this->db->query("SELECT `amount` + 1 as incre  FROM `phppos_adviser_offer` WHERE  `id_adviser` =$personId AND  `id_offer` =$campaign_offer_id limit 1");		
			$valor=$valorIncrementado->row();
			$valor=$valor->incre;		
			$this->db->query("UPDATE  `phppos_adviser_offer` SET  `amount` = $valor WHERE  `id_adviser` = $personId AND  `id_offer` = $campaign_offer_id LIMIT 1");	
		}
	}
	function decrease_campaign($personId,$campaign_offer_id)
	{	
		$valorDecrementado=$this->db->query("SELECT ((`amount` - 1 > 0 ) * (`amount` - 1)) as decre  FROM `phppos_adviser_offer` WHERE  `id_adviser` =$personId AND  `id_offer` =$campaign_offer_id limit 1");		
		$valor=$valorDecrementado->row();
		$valor=$valor->decre;		
		$this->db->query("UPDATE  `phppos_adviser_offer` SET  `amount` = $valor WHERE  `id_adviser` = $personId AND  `id_offer` = $campaign_offer_id LIMIT 1");
		
	}	
    function have_actived($personId)
	{
		return $this->get_all_actived($personId)->num_rows() > 0;
	}
    function get_all_actived($personId=NULL)
	{			
            //date()
			if($personId==NULL)
			{
	            $this->db->where('(is_active = 1 && (date_expire IS NULL OR date_expire > NOW()) )');
	            $query = $this->db->get('campaigns_offers');
            }
			else
			{

				$this->db->where("id_adviser = $personId && amount > 0");
				$posible_offers = $this->db->get('adviser_offer');
				
				$parcheQuery="";
				
				//echo $this->db->last_query();
				
				
        		foreach($posible_offers->result() as $posible_offer)
				{
					$parcheQuery.='campaign_offer_id = '.$posible_offer->id_offer." || ";
				}
				$this->db->where("($parcheQuery FALSE) && ".'(is_active = 1 && (date_expire IS NULL OR date_expire > NOW()) )');
	            $query = $this->db->get('campaigns_offers'); 				
			}
           
            return $query;
	}
        
    function get_types($insert_blank)
    {
        $types = array();
        if($insert_blank)
            $types[] = '';
        $types['click'] = 'click';
        $types['print'] = 'print';

        return $types;
    }
    
    function get_positions($insert_blank)
    {
        $positions = array();
        if($insert_blank)
            $positions[] = '';
        $positions['side'] = 'side';
        $positions['bottom'] = 'bottom';
        $positions['login'] = 'login';

        return $positions;
    }
    
    function get_info($campaign_offer_id)
    {
	$query = $this->db->get_where('campaigns_offers', array('campaign_offer_id' => $campaign_offer_id));
		
	if($query->num_rows()==1)
	{
            return $query->row();
	}
	else
	{
            //create object with empty properties.
            $fields = $this->db->list_fields('campaigns_offers');
            $campaign_offer_obj = new stdClass;
			
            foreach ($fields as $field)
            {
		$campaign_offer_obj->$field='';
            }
			
            return $campaign_offer_obj;
	}
    }
    function save(&$data,$campaign_offer_id=-1)
    {	
        //$query = $this->db->get_where('phppos_campaigns_offers', "campaign_offer_id = $campaign_offer_id");		
        if(!$data['date_expire'])
            $data['date_expire'] = null;
        
        if($campaign_offer_id==-1)
        {
            $data['date_created'] = time();
            if($this->db->insert('phppos_campaigns_offers', $data))
            {
                $data['campaign_offer_id'] = $this->db->insert_id();
                return true;
            }
            else
                return false;
        }
        
        $this->db->where('campaign_offer_id', $campaign_offer_id);
        return $this->db->update('phppos_campaigns_offers', $data);
        
    }
    
    function delete($campaign_offer_id)
    {
        $this->db->where('campaign_offer_id', $campaign_offer_id);
        $this->db->delete('phppos_campaigns_offers');
    }
    
    function delete_list($campaign_offer_ids)
    {
	$this->db->where_in('campaign_offer_id',$campaign_offer_ids);
	return $this->db->delete('campaigns_offers');
    }
    
    function use_one_time($offer)
    {
        $this->db->where('campaign_offer_id', $offer->campaign_offer_id);
        $this->db->update('phppos_campaigns_offers', array('used_times' => $offer->used_times + 1 ));
    }
    
    function search($search)
    {	
	$this->db->from('campaigns_offers');		
	$this->db->where("(name LIKE '%".$this->db->escape_like_str($search)."%')");		
	$this->db->order_by("name", "asc");		
	return $this->db->get();	
    }
    
    //Ariel: Obtiene en forma de string una descripcion de la
    //       oferta usando la catidad, el tipo y la posicion.
    function get_offer_summary($campaign_offer)
    {
        return $campaign_offer->count.' '.$this->lang->line('campaign_type_'.$campaign_offer->type).' '.$this->lang->line('campaign_position_articulo').' '.$this->lang->line('campaign_position_'.$campaign_offer->position);
    }
	function get_offer_price($campaign_offer_id)
    {
        $offer = $this->get_offer($campaign_offer_id);
	    if($offer->num_rows() == 1)
		{
			$offer = $offer->row();			
			return $offer->price;
		}
		return NULL;
            
    }
    
}