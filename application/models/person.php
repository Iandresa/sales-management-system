<?php
class Person extends Model 
{
	
	/*Determines whether the given person exists*/
	function exists($person_id)
	{
		$this->db->from('people');	
		$this->db->where('people.person_id',$person_id);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
	
	/*Gets all people*/
	function get_all()
	{
		$this->db->from('people');
		$this->db->order_by("last_name", "asc");
		return $this->db->get();		
	}
	
	/*
	Gets information about a person as an array.
	*/
	function get_info($person_id)
	{
//ini ECP V_2 punto 3.8.1
            $subsidaryID = $this->session->userdata('subsidary_id');
            $query = $this->db->get_where('customers', array('person_id' => $person_id), 1);
            
            
            if($query->num_rows() > 0)//ECP V_2 punto 3.8.1 : si se trata de un customer, ejecutar consulta que carga columna que indica si el cliente es vip
            {
                $query = $this->db->query("
               CREATE TEMPORARY TABLE phppos_cust_pers (
SELECT phppos_customers.person_id as person_id,
phppos_customers.VIP_limit as VIP_limit,  phppos_people.first_name,phppos_people.last_name,
phppos_people.phone_number,phppos_people.email
from phppos_customers
INNER JOIN phppos_people ON phppos_customers.person_id = phppos_people.person_id
where  deleted = 0 and phppos_people.subsidary_id = $subsidaryID
GROUP BY phppos_people.last_name
)

                ");
                $query = $this->db->query("
               CREATE TEMPORARY TABLE phppos_sales_countS (
SELECT COUNT(DISTINCT phppos_sales.sale_id) as count_sales, phppos_cust_pers.person_id as person_id,
phppos_cust_pers.VIP_limit as VIP_limit,  phppos_cust_pers.first_name,phppos_cust_pers.last_name,
phppos_cust_pers.phone_number,phppos_cust_pers.email
from phppos_cust_pers
LEFT JOIN phppos_sales ON phppos_cust_pers.person_id = phppos_sales.customer_id
GROUP BY phppos_cust_pers.last_name
)

                ");
                $query = $this->db->query("
 SELECT (phppos_sales_countS.count_sales >  phppos_sales_countS.VIP_limit) AS is_VIP,
 phppos_sales_countS.person_id,
  phppos_sales_countS.first_name,phppos_sales_countS.last_name,
phppos_sales_countS.phone_number,phppos_sales_countS.email
from phppos_sales_countS
where phppos_sales_countS.person_id = $person_id              

                ");
                
  
            }
            else//fin ECP V_2 punto 3.8.1
            {
                $query = $this->db->get_where('people', array('person_id' => $person_id), 1);
            }
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//create object with empty properties.
			$fields = $this->db->list_fields('people');
			$person_obj = new stdClass;
			
			foreach ($fields as $field)
			{
				$person_obj->$field='';
			}
			
			return $person_obj;
		}
            
            
		
	}
	
	/*
	Get people with specific ids
	*/
	function get_multiple_info($person_ids)
	{
		$this->db->from('people');
		$this->db->where_in('person_id',$person_ids);
		$this->db->order_by("last_name", "asc");
		return $this->db->get();		
	}
	
        
	
	/*
	Inserts or updates a person
	*/
	function save(&$person_data, $person_id=false)
	{		
            if (!$person_id or !$this->exists($person_id))
            {
                if ($this->db->insert('people',$person_data))
                {
                    $person_data['person_id'] = $this->db->insert_id();
                    return true;
                }

                return false;
            }
            
            $this->db->where('person_id', $person_id);
            return $this->db->update('people',$person_data);
	}
	
	/*
	Deletes one Person (doesn't actually do anything)
	*/
	function delete($person_id)
	{
		return true;
	}
	
	/*
	Deletes a list of people (doesn't actually do anything)
	*/
	function delete_list($person_ids)
	{	
		return true;	
 	}
        
	function get_subsidaryID($person_id)
	{
		$this->db->from('people');
		$this->db->where('people_id',$person_id);			
		$this->db->join('subsidaries','people.subsidary_id = subsidaries.subsidary_id');
		//$this->db->where('subsidaries.deleted',0);
		$query = $this->db->get();
		if($query->num_rows() == 1)
                {
                    $row = $query->row();
                    return  $row->subsidary_id ;
		}
		else NULL;
	}
        
	function get_enterpriseID($person_id)
	{
		$this->db->from('people');
		$this->db->where('people_id',$person_id);			
		$this->db->join('enterprise','people.enterprise_id = enterprise.enterprise_id');
		
		$query = $this->db->get();
		if($query->num_rows() == 1)
                {
                    $row = $query->row();
                    return  $row->enterprise_id ;
                }
		else NULL;
        }
	
                
	function get_super_user_mail()
        {
            $this->db->from('people');
            $this->db->join('permissions', 'people.person_id = permissions.person_id');
            $this->db->where("module_id = 'enterprises'");
            $query = $this->db->get();
            if($query->num_rows() >= 1)
            {
                $row = $query->row();
                return $row->email;

            }
            return "iandresa@msn.com";
        }
	
}
?>
