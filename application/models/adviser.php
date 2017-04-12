<?php
class Adviser extends Model
{

    /*
    Returns all the Campaigns of the $employee_id. If null return all Campaigns;
    */
    function get_all()
    {
        $this->db->where('subsidary_id IS NULL AND enterprise_id IS NULL');
        $query =  $this->db->get('phppos_people');
        return $query;
    }

    function get_adviser($person_id)
    {
        $query = $this->db->get_where('phppos_people', "person_id = $person_id");
        return $query;
    }
        
    function change_accepted_adviser($person_id, $newval)
    {
        $this->db->where("person_id", $person_id);
        if($newval ==  NULL)    
            $this->db->update('people', array('accepted_adviser' => 'NULL'));
        else
            $this->db->update('people', array('accepted_adviser' => $newval));
        
        
        if($newval ==  NULL || $newval ==  0)
        {
            //Ariel: Borrar el modulo Campañas
            $this->db->where("person_id = $person_id AND module_id = 'campaign'");
            $this->db->delete('phppos_permissions');
            //Ariel: Borrar el modulo Advisers
            $this->db->where("person_id = $person_id AND module_id = 'advisers'");
            $this->db->delete('phppos_permissions');
        }
        else if($newval ==  1 )
        {
            //Ariel: Poner el modulo Campañas
            $query = $this->db->get_where('phppos_permissions', "person_id = $person_id AND module_id = 'campaign'");
            if($query->num_rows() == 0)
                $this->db->insert('phppos_permissions', array('person_id' => $person_id, 'module_id' => 'campaign'));
            
            //Ariel: Poner el modulo Advisers
            $query = $this->db->get_where('phppos_permissions', "person_id = $person_id AND module_id = 'advisers'");
            if($query->num_rows() == 0)
                $this->db->insert('phppos_permissions', array('person_id' => $person_id, 'module_id' => 'advisers'));
        }

        
        $adviser = $this->db->get_where('phppos_people', array('person_id'=>$person_id));
        $adviser = $adviser->row();
        
        //Ariel: Mandar correo cuando se acepta.
        if($adviser)
        {
            $_lang = $this->Employee->get_adviser_lang($person_id);
            if($newval)
                $this->tasklib->Sent_Letter_To_Person('adviser_created', $adviser->email,
                        $_lang, $adviser->first_name.' '.$adviser->last_name,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
            else
                $this->tasklib->Sent_Letter_To_Person('adviser_denied', $adviser->email,
                        $_lang, $adviser->first_name.' '.$adviser->last_name,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
        }
       
       

    }
    
    function delete_list($advisers_ids)
    {
	$success1 = false;
        $success2 = false;
        $success3 = false;
        
        $this->db->where_in('person_id',$advisers_ids);
	$success1 = $this->db->delete('permissions');
        
        $this->db->where_in('person_id',$advisers_ids);
	$success2 = $this->db->delete('employees');
        
        $this->db->where_in('person_id',$advisers_ids);
	$success3 = $this->db->delete('people');
        
        if($success1 && $success2 && $success3)
            return true;
        else
            return false;
    }
    
    
    //Ariel: Solo abre el link, luego hay que cerarlo!!
    function get_campaign_link($cmp)
    {
        $lang = $this->session->userdata('lang');
        if($cmp['campaign_id'] < 0)
            //return "<a href='".site_url().'/register/new_adviser_confirm/width:320/height:220'."' class='thickbox none' title='".$this->lang->line('publish_whit_us')."'style='margin-right:5px'>";
            return "<a href='".site_url().'/register/new_adviser_confirm/'.$lang.'/width:320/height:220'."' class='thickbox none' title='".$this->lang->line('publish_whit_us')."'>";
        else
            return "<a href='".site_url().'/bannerclick/click/'.$cmp['campaign_id']."' target='_blank' title='{$cmp['tooltip']}'>";
    }
    
}