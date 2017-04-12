<?php
class Campaign_model extends Model
{

    /*
    Returns all the Campaigns of the $employee_id. If null return all Campaigns;
    */
    function get_all($employee_id) 
    {	
        if($employee_id != NULL)
            $this->db->where('user_id',$employee_id);

        $this->db->order_by("date_created", "des");
        $query = $this->db->get('campaigns');
        return $query;
    }

    function get_campaign($campaign_id)
    {
        $query = $this->db->get_where('campaigns', "campaign_id = $campaign_id");
        return $query;
    }
    
    function get_info($campaign_id)
    {
	$query = $this->db->get_where('campaigns', array('campaign_id' => $campaign_id));
		
	if($query->num_rows()==1)
	{
            return $query->row();
	}
	else
	{
            //create object with empty properties.
            $fields = $this->db->list_fields('campaigns');
            $campaign_obj = new stdClass;
			
            foreach ($fields as $field)
            {
		$campaign_obj->$field='';
            }
			
            return $campaign_obj;
	}
    }
    
    function save(&$data,$campaign_id=-1, $user_id = null, $offer_id = null)
    {
        if($campaign_id == -1)
        {
            $CI =& get_instance();
            $offer = $CI->Campaign_offer_model->get_offer($offer_id);
            
            if($offer->num_rows() == 1)
            {
                $offer = $offer->row();
                $CI->Campaign_offer_model->use_one_time($offer);
                if($offer->type == 'print')
                {
                    $data['impresions_left'] = $offer->count;
                    $data['impresions_count'] = 0;
                    $data['clicks_count'] = 0;
                }
                else if($offer->type == 'click')
                {
                    $data['clicks_left'] = $offer->count;
                    $data['clicks_count'] = 0;
                    $data['impresions_count'] = 0;
                }
                
                
                $data['date_created'] = time();
                $data['position'] = $offer->position;
                $data['type'] = $offer->type;
                $data['daily_count'] = 0;
                $data['user_id'] = $user_id;
                
                if($this->db->insert('phppos_campaigns', $data))
                {
                    
                    $data['campaign_id'] = $this->db->insert_id();
                    return true;
                }
                else
                    return false;
            }
        }
        
        $this->db->where('campaign_id', $campaign_id);
        return $this->db->update('phppos_campaigns', $data);
        
    }
    
    
    function reset_daily_count()
    {
        $this->db->update('phppos_campaigns', array('daily_count'=> 0));
    }
    
    function reset_showed($position)
    {
        $this->db->where('position', $position);
        $this->db->update('phppos_campaigns', array('showed'=> 0));
    }
    
    function showed_one_time($campaign)
    {
        $this->db->trans_start();
        
        if($campaign['type'] == 'print')
        {
            $this->db->where('campaign_id', $campaign['campaign_id']);
            $this->db->update('phppos_campaigns', array('impresions_left'=> $campaign['impresions_left'] - 1));

            $this->db->where('campaign_id', $campaign['campaign_id']);
            $this->db->update('phppos_campaigns', array('daily_count'=> $campaign['daily_count'] + 1));
        }
        $this->db->where('campaign_id', $campaign['campaign_id']);
        $this->db->update('phppos_campaigns', array('impresions_count'=> $campaign['impresions_count'] + 1));
        
        $this->db->where('campaign_id', $campaign['campaign_id']);
        $this->db->update('phppos_campaigns', array('showed'=> 1));
        
        $this->db->trans_complete();

    }
    
    function clicked_one_time($campaign)
    {
        $this->db->trans_start();
        
        if($campaign['type'] == 'click')
        {
            $this->db->where('campaign_id', $campaign['campaign_id']);
            $this->db->update('phppos_campaigns', array('clicks_left'=> $campaign['clicks_left'] - 1));

            $this->db->where('campaign_id', $campaign['campaign_id']);
            $this->db->update('phppos_campaigns', array('daily_count'=> $campaign['daily_count'] + 1));
        }
        
        $this->db->where('campaign_id', $campaign['campaign_id']);
        $this->db->update('phppos_campaigns', array('clicks_count'=> $campaign['clicks_count'] + 1));
        
        $this->db->trans_complete();
    }
    
    
    function get_nextadvises($position)
    {
        //No mostrar Adds si es el SUDO || Adviser
        if($this->session->userdata('person_id'))
        {
            if($this->Employee->is_SuperUser($this->session->userdata('person_id')) ||
               $this->Employee->is_AdviserUser($this->session->userdata('person_id')))
                return array();
        }   
        //No mostrar Adds si es la empresa los tiene deshabilitado
        if($this->session->userdata('enterprise_id'))
        {
            if($this->Enterprise->get_permi_hide_banners($this->session->userdata('enterprise_id')) != '0')
                    return array();
        }
        
        
        
        //Para garantizar que la samena comience por Lunes
        $this->load->library('fecha'); // change ariel
        $today = date('w', time());
        if(date('w', $this->fecha->date_to_timestamp('01/08/2011')) == '1')
        {
            $today = $today-1;
            if($today == -1)
                $today = 6;
        }
        
        
        //Extrar los avaibles.
        $this->db->where("position LIKE '%$position%' AND 
            is_active = 1 AND 
            (daily_top IS NULL OR daily_count < daily_top) AND 
            (('type' <> 'click' AND impresions_left > 0) OR ('type' <> 'print' AND clicks_left > 0)) AND
            (week_days IS NULL OR week_days LIKE '%$today%') ");
        $this->db->order_by('showed', 'random');
        $this->db->limit($this->config->item("banners_count_$position"));
        $available_campaigns = $this->db->get('phppos_campaigns'); 
        
       // echo $this->db->last_query();
        
        $available_campaigns = $available_campaigns->result_array();
        
        if(count($available_campaigns) > 0 && $available_campaigns[count($available_campaigns)-1]['showed'] == 1)
            $this->reset_showed($position);
        
        //echo $this->db->last_query();
        
        foreach($available_campaigns as $campaign)
            $this->showed_one_time($campaign);
        
        
        //Ariel: Si hay menos anuncios reales de los que tienen que estar,
        //       rellenar con anuncios fictisios con el logo del sitio.
        if(count($available_campaigns) < $this->config->item("banners_count_$position"))
        {
            $top = $this->config->item("banners_count_$position") - count($available_campaigns);
            for($i = 0; $i < $top ; $i++)
            {
                $fic_camp = array(
                    'campaign_id' => -1,
                    'tooltip' => "Here goes banners",
                    'position' => $position
                );
                if($position == "login")
                    $fic_camp['image_large'] = "defaultbannerlogin.png";
                else
                    $fic_camp['image_large'] = "defaultbanner.png";

                array_push($available_campaigns, $fic_camp);
            }
        }
        
        
        //Ariel: Randomizar aun mas los banners a mostrar
        //shuffle($available_campaigns);
        return $available_campaigns;
        
    }
    
    function delete_list($campaign_ids)
    {
	$this->db->where_in('campaign_id',$campaign_ids);
	return $this->db->delete('campaigns');
    }

    function search($search)
    {	
        $person_id = $this->session->userdata('person_id');
        
	
        if($this->Employee->is_SuperUser($person_id))
        {
            $this->db->from('campaigns');
            $this->db->where("(name LIKE '%".$this->db->escape_like_str($search)."%')");
            $this->db->order_by("name", "asc");      
        }   
        else    
        {
            $this->db->from('campaigns');
            $this->db->where("(name LIKE '%".$this->db->escape_like_str($search)."%') and user_id = $person_id");
            $this->db->order_by("name", "asc");
        }
        
	return $this->db->get();	
    }
    
    function get_offer_summary($campaign)
    {
        if($campaign['type'] == 'print')
            $count = $campaign['impresions_left'] + $campaign['impresions_count'];		
        else if($campaign['type'] == 'click')
            $count = $campaign['clicks_left'] + $campaign['clicks_count'];		
   
        return $count.' '.$this->lang->line('campaign_type_'.$campaign['type']).' '.$this->lang->line('campaign_position_articulo').' '.$this->lang->line('campaign_position_'.$campaign['position']);
    }
}