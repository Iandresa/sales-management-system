<?php
class Module extends Model 
{
    function __construct()
    {
        parent::__construct();
    }
	
	function get_module_name($module_id)
	{
		$query = $this->db->get_where('modules', array('module_id' => $module_id), 1);
		
		if ($query->num_rows() ==1)
		{
			$row = $query->row();
			return $this->lang->line($row->name_lang_key);
		}		
		return $this->lang->line('error_unknown');
	}
	
	function get_module_desc($module_id)
	{
		$query = $this->db->get_where('modules', array('module_id' => $module_id), 1);
		if ($query->num_rows() ==1)
		{
			$row = $query->row();
			return $this->lang->line($row->desc_lang_key);
		}
	
		return $this->lang->line('error_unknown');	
	}

	function get_all_modules($include_sudo_modules = FALSE)
	{
            $this->db->from('enterprises'); //empleados
            $this->db->where('enterprise_id', $this->session->userdata('enterprise_id'));
            $q = $this->db->get();

            if($q->num_rows()== 1)			
            {
                $row = $q->row();
                $cafeteria = ($row->permi_uncomplete_sale == '1') || ($row->permi_uncomplete_sale == '2');//HL (2013-05-16)
                $delivery = ($row->permi_delivery == '1') || ($row->permi_delivery == '2');//HL (2013-05-16)
            }
            else 	
            {
                $cafeteria = false;
                $delivery = false;
            } 
             //   $cafeteria = false;
              //  $delivery = false;

            $this->db->from('modules');
          if($include_sudo_modules)
          {
               $this->db->where("module_id <> 'enterprises' 
                    AND module_id <> 'cafeteria' 
                   AND module_id <> 'deliveries'
                   AND module_id <> 'advisers'");
           }
          else
           {
                $query = "module_id <> 'enterprises' 
                    AND module_id <> 'campaign'
                    AND module_id <> 'campaign_offer'
                    AND module_id <> 'advisers' ";
                if(!$cafeteria)  $query=$query." AND module_id <> 'cafeteria' ";
                 if(!$delivery)  $query=$query." AND module_id <> 'deliveries' ";
                $this->db->where($query);
          }

            $this->db->order_by("sort", "asc");

            return $this->db->get();		
	}
	
	function get_allowed_modules($person_id)
	{
		$this->db->from('modules');
		$this->db->join('permissions','permissions.module_id=modules.module_id');
		$this->db->where("permissions.person_id",$person_id);
		$this->db->order_by("sort", "asc");
		return $this->db->get();		
	}
        
        function get_allowed_module_sale()
	{
                $person_id = $this->session->userdata('person_id');
                
                $this->db->from('permissions');
		$this->db->where("person_id = $person_id and module_id = 'sales'");
		$query = $this->db->get();
 
                if ($query->num_rows() == 1)
                    return true;
                return false;
	}
}
?>
