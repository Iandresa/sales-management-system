<?php
class Customer extends Person
{	
	
	/*
	Determines if a given person_id is a customer
	*/
	function exists($person_id)
	{
		$this->db->from('customers');	
		$this->db->join('people', 'people.person_id = customers.person_id');
		$this->db->where('customers.person_id',$person_id);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
	/*
	Returns all the customers
	*/
	function get_all()
	{
		//change migue
		//echo 'person_id ='.$this->session->userdata('person_id');
		//echo 'enterprise_id ='.$this->session->userdata('enterprise_id');
		//echo 'subsidary_id ='.$this->session->userdata('subsidary_id');
		
		$subsidaryID = $this->session->userdata('subsidary_id');//change
		
		$this->db->from('customers');
		$this->db->join('people','customers.person_id = people.person_id');			
		$this->db->where("deleted = 0 and subsidary_id = $subsidaryID");//change
		$this->db->order_by("last_name", "asc");
		return $this->db->get();		
	}
        
        public function get_all_VIP_column()//ECP V_2 punto 3.8.1
	{
              
            $subsidaryID = $this->session->userdata('subsidary_id');        
                                

            $this->db->query("

                CREATE TEMPORARY TABLE phppos_cust_pers (
                    SELECT phppos_customers.person_id as person_id,
                    phppos_customers.VIP_limit as VIP_limit,  phppos_people.first_name,phppos_people.last_name,
                    phppos_people.phone_number,phppos_people.email
                    from phppos_customers
                    INNER JOIN phppos_people ON phppos_customers.person_id = phppos_people.person_id
                    where  deleted = 0 and phppos_people.subsidary_id = $subsidaryID
                    
                )

                            ");


            $query = $this->db->query("
            
                CREATE TEMPORARY TABLE phppos_sales_countS (
                    SELECT COUNT(DISTINCT phppos_sales.sale_id) as count_sales, phppos_cust_pers.person_id as person_id,
                    phppos_cust_pers.VIP_limit as VIP_limit,  phppos_cust_pers.first_name,phppos_cust_pers.last_name,
                    phppos_cust_pers.phone_number,phppos_cust_pers.email
                    from phppos_cust_pers
                    LEFT JOIN phppos_sales ON phppos_cust_pers.person_id = phppos_sales.customer_id
                    GROUP BY phppos_cust_pers.person_id
		    
                )

                            ");
            
            $query = $this->db->query("
            
                SELECT (phppos_sales_countS.count_sales >  phppos_sales_countS.VIP_limit) AS is_VIP,
                    phppos_sales_countS.person_id,
                    phppos_sales_countS.first_name,phppos_sales_countS.last_name,
                    phppos_sales_countS.phone_number,phppos_sales_countS.email
                    from phppos_sales_countS
                    ORDER BY phppos_sales_countS.last_name ASC
                            ");
            


             return $query;//$query->result_array();
              
	}
	
        
	/*
	Gets information about a particular customer
	*/
	function get_info($customer_id)
	{
		$this->db->from('customers');	
		$this->db->join('people', 'people.person_id = customers.person_id');
		$this->db->where('customers.person_id',$customer_id);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $customer_id is NOT an customer
			$person_obj=parent::get_info(-1);
			
			//Get all the fields from customer table
			$fields = $this->db->list_fields('customers');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$person_obj->$field='';
			}
			
			return $person_obj;
		}
	}
	
	/*
	Gets information about multiple customers
	*/
	function get_multiple_info($customer_ids)
	{
		$this->db->from('customers');
		$this->db->join('people', 'people.person_id = customers.person_id');		
		$this->db->where_in('customers.person_id',$customer_ids);
		$this->db->order_by("last_name", "asc");
		return $this->db->get();		
	}
	
	/*
	Inserts or updates a customer
	*/
	function save(&$person_data, &$customer_data,$customer_id=false)
	{
		$success=false;
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		
		if(parent::save($person_data,$customer_id))
		{
			
			if (!$customer_id or !$this->exists($customer_id))
			{
				$customer_data['person_id'] = $person_data['person_id'];
				$success = $this->db->insert('customers',$customer_data);
				$subsidaryID = $this->session->userdata('subsidary_id');  //change
				$enterpriseID = $this->session->userdata('enterprise_id');//change
				$this->db->where('person_id', $person_data['person_id']); //change
				$this->db->update('people',array("subsidary_id"=>$subsidaryID,"enterprise_id"=>$enterpriseID));//change				
			}
			else
			{
				$this->db->where('person_id', $customer_id);
				$success = $this->db->update('customers',$customer_data);
			}
			
		}
		
		$this->db->trans_complete();		
		return $success;
	}
	
	/*
	Deletes one customer
	*/
	function delete($customer_id)
	{
		$this->db->where('person_id', $customer_id);
		return $this->db->update('customers', array('deleted' => 1));
	}
	
	/*
	Deletes a list of customers
	*/
	function delete_list($customer_ids)
	{
		$this->db->where_in('person_id',$customer_ids);
		return $this->db->update('customers', array('deleted' => 1));
 	}
 	
 	/*
	Get search suggestions to find customers
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$subsidaryID = $this->session->userdata('subsidary_id');  //change
		
		$suggestions = array();
		
		$this->db->from('customers');
		$this->db->join('people','customers.person_id=people.person_id');	
		$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0 and subsidary_id = $subsidaryID");//change
		$this->db->order_by("last_name", "asc");		
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->first_name.' '.$row->last_name;		
		}
		
		$this->db->from('customers');
		$this->db->join('people','customers.person_id=people.person_id');	
		//$this->db->where('deleted',0);
		$this->db->where("deleted = 0 and subsidary_id = $subsidaryID");//change		
		$this->db->like("email",$search);
		$this->db->order_by("email", "asc");		
		$by_email = $this->db->get();
		foreach($by_email->result() as $row)
		{
			$suggestions[]=$row->email;		
		}

		$this->db->from('customers');
		$this->db->join('people','customers.person_id=people.person_id');	
		//$this->db->where('deleted',0);
		$this->db->where("deleted = 0 and subsidary_id = $subsidaryID");//change		
		$this->db->like("phone_number",$search);
		$this->db->order_by("phone_number", "asc");		
		$by_phone = $this->db->get();
		foreach($by_phone->result() as $row)
		{
			$suggestions[]=$row->phone_number;		
		}
		
		$this->db->from('customers');
		$this->db->join('people','customers.person_id=people.person_id');	
		//$this->db->where('deleted',0);
		$this->db->where("deleted = 0 and subsidary_id = $subsidaryID");//change		
		$this->db->like("account_number",$search);
		$this->db->order_by("account_number", "asc");		
		$by_account_number = $this->db->get();
		foreach($by_account_number->result() as $row)
		{
			$suggestions[]=$row->account_number;		
		}
		
		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;
	
	}
	
	/*
	Get search suggestions to find customers
	*/
	function get_customer_search_suggestions($search,$limit=25)
	{
		$subsidaryID = $this->session->userdata('subsidary_id');  //change
		
		$suggestions = array();
		
		$this->db->from('customers');
		$this->db->join('people','customers.person_id=people.person_id');	
		$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0 and subsidary_id = $subsidaryID");//change
		$this->db->order_by("last_name", "asc");		
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->person_id.'|'.$row->first_name.' '.$row->last_name;		
		}
		
		$this->db->from('customers');
		$this->db->join('people','customers.person_id=people.person_id');	
		//$this->db->where('deleted',0);
		$this->db->where("deleted = 0 and subsidary_id = $subsidaryID");//change		
		$this->db->like("account_number",$search);
		$this->db->order_by("account_number", "asc");		
		$by_account_number = $this->db->get();
		foreach($by_account_number->result() as $row)
		{
			$suggestions[]=$row->person_id.'|'.$row->account_number;
		}

		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;

	}
	/*
	Preform a search on customers
	*/
	function search($search)
	{
		$subsidaryID = $this->session->userdata('subsidary_id');  //change
		
		$this->db->from('customers');
		$this->db->join('people','customers.person_id=people.person_id');		
		$this->db->where("(first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
		email LIKE '%".$this->db->escape_like_str($search)."%' or 
		phone_number LIKE '%".$this->db->escape_like_str($search)."%' or 
		account_number LIKE '%".$this->db->escape_like_str($search)."%' or 
		CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0 and subsidary_id = $subsidaryID");//change		
		$this->db->order_by("last_name", "asc");		
		return $this->db->get();	
	}
        
        function search_VIP_column($search)//ECP V_2 punto 3.8.1
	{

                
                
                $subsidaryID = $this->session->userdata('subsidary_id');  //change
                
                $query = $this->db->query("
            
               CREATE TEMPORARY TABLE phppos_cust_pers (
                    SELECT phppos_customers.person_id as person_id,
                    phppos_customers.VIP_limit as VIP_limit,  phppos_people.first_name,phppos_people.last_name,
                    phppos_people.phone_number,phppos_people.email
                    from phppos_customers
                    INNER JOIN phppos_people ON phppos_customers.person_id = phppos_people.person_id
                    where  deleted = 0 and phppos_people.subsidary_id = $subsidaryID
                    
                )


                            ");
                $query = $this->db->query("
            
                CREATE TEMPORARY TABLE phppos_sales_countS (
                SELECT COUNT(DISTINCT phppos_sales.sale_id) as count_sales,
                phppos_cust_pers.person_id as person_id,
                phppos_cust_pers.VIP_limit as VIP_limit, 
                phppos_cust_pers.first_name,
                phppos_cust_pers.last_name,
                phppos_cust_pers.phone_number,
                phppos_cust_pers.email
                from phppos_cust_pers
                LEFT JOIN phppos_sales ON phppos_cust_pers.person_id = phppos_sales.customer_id
                GROUP BY phppos_cust_pers.person_id
                )


                            ");
                $query = $this->db->query("
            
               CREATE TEMPORARY TABLE phppos_customer_search (
                    SELECT phppos_people.*
                    from phppos_customers
                    INNER JOIN phppos_people ON phppos_customers.person_id = phppos_people.person_id
                    WHERE (phppos_people.first_name LIKE '%".$this->db->escape_like_str($search)."%' or 
                    last_name LIKE '%".$this->db->escape_like_str($search)."%' or 
                    email LIKE '%".$this->db->escape_like_str($search)."%' or 
                    phone_number LIKE '%".$this->db->escape_like_str($search)."%' or 
                    account_number LIKE '%".$this->db->escape_like_str($search)."%' or
                    CONCAT(`first_name`,' ',`last_name`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0 and subsidary_id = $subsidaryID
                 )	

                            ");
                
                $query = $this->db->query("
                SELECT (phppos_sales_countS.count_sales >  phppos_sales_countS.VIP_limit) AS is_VIP,
                phppos_sales_countS.person_id,
                phppos_sales_countS.first_name,
                phppos_sales_countS.last_name,
                phppos_sales_countS.phone_number,
                phppos_sales_countS.email
                from phppos_sales_countS
                INNER JOIN phppos_customer_search ON phppos_sales_countS.person_id = phppos_customer_search.person_id
                GROUP BY phppos_sales_countS.person_id
                ORDER BY phppos_sales_countS.last_name ASC
                            ");
            


             return $query;//$query->result_array();       
             
             
             
	}
        
        function is_customer_VIP($person_id)//ECP V_2 punto 3.8.2
	{                
                
                $subsidaryID = $this->session->userdata('subsidary_id');  //change
                
                
                $query = $this->db->query("
            
                CREATE TEMPORARY TABLE phppos_customer_count_sales (
                    SELECT COUNT(DISTINCT phppos_sales.sale_id) as count_sales, phppos_customers.*
                    from phppos_customers
                    LEFT JOIN phppos_sales ON phppos_customers.person_id = phppos_sales.customer_id
                    WHERE phppos_customers.person_id = $person_id
                    GROUP BY phppos_customers.person_id 
                    )

                    ");
                
                
                $query = $this->db->query("
                SELECT (phppos_customer_count_sales.count_sales >  phppos_customer_count_sales.VIP_limit) AS is_VIP                    
                    from phppos_customer_count_sales
                    

                            ");
            
 
                if($query->row()->is_VIP == true)
                  return true;
                else
                  return false;
             
             
             
	}

}
?>
