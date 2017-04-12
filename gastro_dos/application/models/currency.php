<?php
class Currency extends Model
{	
	

	function get_all()
	{
		//change migue
		//echo 'person_id ='.$this->session->userdata('person_id');
		//echo 'enterprise_id ='.$this->session->userdata('enterprise_id');
		//echo 'subsidary_id ='.$this->session->userdata('subsidary_id');
		
		$this->db->from('currency');
		$this->db->order_by("currency_id", "asc");
		return $this->db->get();		
	}
	
	function get_all_array($include_blanck)
	{
		$rows  = $this->get_all();
		$result = array();
                
                if($include_blanck)
                    $result[''] = '';
                
		foreach($rows->result() as $row)	
		{
			$result[$row->currency_id] = $row->cur_name." ($row->cur_alias) [$row->cur_symbol]"; 
		}
		return $result;
	}
        
        function get_country($currency_id)
	{
		$this->db->from('currency');
                $this->db->where('currency_id',$currency_id);
		$q = $this->db->get();
                
	}
        
        function format_price($amount, $symbol)
        {
            echo "ariel";
        }
	
        function get_info($currency_id)
        {
            $this->db->from('currency');
            $this->db->where('currency_id', $currency_id);
            $q = $this->db->get();
        
            if($q->num_rows() > 0)
                    return $q->row();
        }
        
     
}
	
?>
