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

		
	function get_all_modules($include_EnterpriseModule=FALSE)
	{
		$this->db->from('modules');
		if(!$include_EnterpriseModule)
			$this->db->where("module_id <> 'enterprises'");
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
}
?>
