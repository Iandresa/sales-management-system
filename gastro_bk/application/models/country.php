<?php
class Country extends Model
{	
	

	function get_all()
	{
		//change migue
		//echo 'person_id ='.$this->session->userdata('person_id');
		//echo 'enterprise_id ='.$this->session->userdata('enterprise_id');
		//echo 'subsidary_id ='.$this->session->userdata('subsidary_id');
		
		$this->db->from('countries');
		$this->db->order_by("name", "asc");
		return $this->db->get();		
	}
	
	function get_all_array()
	{
		$rows  = $this->get_all();
		$result = array();
		foreach($rows->result() as $row)	
		{
			$result[$row->code] = $row->name; 
		}
		return $result;
	}
	
}
	
?>
