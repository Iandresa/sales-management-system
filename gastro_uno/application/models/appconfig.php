<?php
class Appconfig extends Model 
{
	//change migue all pq ya no es de una empresa sino de toda la base de datos
	//////////////////////
	
	function exists($key)
	{
//		$this->db->from('app_config');	
//		$this->db->where('app_config.key',$key);
//		$query = $this->db->get();		
//		return ($query->num_rows()==1);
	
		$subsidaryID=$this->session->userdata('subsidary_id');
		
		$this->db->from('subsidaries');		
		$this->db->where("subsidary_id = $subsidaryID and $key not ''" );
		$query = $this->db->get();		
		return ($query->num_rows()==1);
	}
	
	function get_all()
	{
//		$this->db->from('app_config');
//		$this->db->order_by("key", "asc");
//		return $this->db->get();
		
		$subsidaryID=$this->session->userdata('subsidary_id');
		if($subsidaryID)
		{
			$this->db->from('subsidaries');		
			$this->db->where("subsidary_id = $subsidaryID");
			//$this->db->where("subsidary_id = 1");		
			$q = $this->db->get();
			$result=array();
			$pos=0;
			
			foreach($q->result() as $row)
			{				
				if($row->company)
				{
					$result[$pos]=new stdClass();
					$result[$pos]->key = "company";
					$result[$pos]->value = $row->company;
					$pos=$pos+1;	
				}
				if($row->address)
				{
					$result[$pos]=new stdClass();
					$result[$pos]->key = "address";
					$result[$pos]->value = $row->address;
					$pos=$pos+1;	
				}				
				if($row->default_tax_1_name)
				{
					$result[$pos]=new stdClass();
					$result[$pos]->key = "default_tax_1_name";
					$result[$pos]->value = $row->default_tax_1_name;
					$pos=$pos+1;	
				}					
				if($row->default_tax_1_rate)
				{
					$result[$pos]=new stdClass();
					$result[$pos]->key = "default_tax_1_rate";
					$result[$pos]->value = $row->default_tax_1_rate;
					$pos=$pos+1;	
				}
				if($row->default_tax_2_name)
				{
					$result[$pos]=new stdClass();
					$result[$pos]->key = "default_tax_2_name";
					$result[$pos]->value = $row->default_tax_2_name;
					$pos=$pos+1;	
				}
				if($row->default_tax_2_rate)
				{
					$result[$pos]=new stdClass();
					$result[$pos]->key = "default_tax_2_rate";
					$result[$pos]->value = $row->default_tax_2_rate;
					$pos=$pos+1;	
				}
				if($row->default_tax_rate)
				{
					$result[$pos]=new stdClass();
					$result[$pos]->key = "default_tax_rate";
					$result[$pos]->value = $row->default_tax_rate;
					$pos=$pos+1;	
				}
				if($row->email)
				{
					$result[$pos]=new stdClass();
					$result[$pos]->key = "email";
					$result[$pos]->value = $row->email;
					$pos=$pos+1;	
				}
				if($row->fax)
				{
					$result[$pos]=new stdClass();
					$result[$pos]->key = "fax";
					$result[$pos]->value = $row->fax;
					$pos=$pos+1;	
				}
				if($row->language)
				{
					$result[$pos]=new stdClass();
					$result[$pos]->key = "language";
					$result[$pos]->value = $row->language;
					$pos=$pos+1;	
				}
				if($row->phone)
				{
					$result[$pos]=new stdClass();
					$result[$pos]->key = "phone";
					$result[$pos]->value = $row->phone;
					$pos=$pos+1;	
				}
				if($row->print_after_sale)
				{
					$result[$pos]=new stdClass();
					$result[$pos]->key = "print_after_sale";
					$result[$pos]->value = $row->print_after_sale;
					$pos=$pos+1;	
				}
				if($row->return_policy)
				{
					$result[$pos]=new stdClass();
					$result[$pos]->key = "return_policy";
					$result[$pos]->value = $row->return_policy;
					$pos=$pos+1;	
				}
				if($row->timezone)
				{
					$result[$pos]=new stdClass();
					$result[$pos]->key = "timezone";
					$result[$pos]->value = $row->timezone;
					$pos=$pos+1;	
				}
				if($row->version)
				{
					$result[$pos]=new stdClass();
					$result[$pos]->key = "version";
					$result[$pos]->value = $row->version;
					$pos=$pos+1;	
				}
				if($row->website)
				{
					$result[$pos]=new stdClass();
					$result[$pos]->key = "website";
					$result[$pos]->value = $row->website;
					$pos=$pos+1;	
				}	
			}
			return $result;
		}
		else
		{
		return  array();
			//$this->db->from('app_config');
			//$this->db->order_by("key", "asc");
			return array();
		}		
	}
	
	function get($key)/////////////////////////////////
	{
//		$query = $this->db->get_where('app_config', array('key' => $key), 1);
//		
//		if($query->num_rows()==1)
//		{
//			return $query->row()->value;
//		}
//		
//		return "";
		//////////////////////////////change migue all/////////////
		$subsidaryID=$this->session->userdata('subsidary_id');
		
		$this->db->from('subsidaries');		
		$this->db->where("subsidary_id = $subsidaryID");
		$query = $this->db->get();
		if($query->num_rows()==1)
		{
			return $query->row()->$key;
		}
		return "";
	}
	
	function save($key,$value)/////////////////////////////////////////
	{
//		$config_data=array(
//		'key'=>$key,
//		'value'=>$value
//		);
//				
//		if (!$this->exists($key))
//		{
//			return $this->db->insert('app_config',$config_data);
//		}
//		
//		$this->db->where('key', $key);
//		return $this->db->update('app_config',$config_data);			
		$subsidaryID=$this->session->userdata('subsidary_id');		
		$this->db->where('subsidary_id',$subsidaryID);
		return	$this->db->update('subsidaries', array($key => $value));	
	}
	
	function batch_save($data)//solo llama a metodos ya arreglados
	{
		$success=true;
		
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		foreach($data as $key=>$value)
		{
			if(!$this->save($key,$value))
			{
				$success=false;
				break;
			}
		}
		
		$this->db->trans_complete();		
		return $success;
		
	}
		
	function delete($key)//no hace falta borrar
	{
		//return $this->db->delete('app_config', array('key' => $key)); 
	}
	
	function delete_all()//no hace falta borrar
	{
	  //	return $this->db->empty_table('app_config'); 
	}
}

?>