<?php
class Subsidary extends Model
{
	function exists($subsidary_id) /*HECTOR*/
	{
		$enterpriseID = $this->session->userdata('enterprise_id');
		
		$this->db->from('subsidaries');
		$this->db->where("subsidary_id = $subsidary_id and enterprise_id = $enterpriseID");
		$query = $this->db->get();

		return ($query && $query->num_rows()==1);
	}
	
	/*
	Returns all the Subsidaries
	*/
	function get_all()//saca la empresa por el empleado activo
	{
		$enterpriseID=$this->session->userdata('enterprise_id');
		$this->db->from('subsidaries');
		$this->db->where('enterprise_id',$enterpriseID);//change migue
		$this->db->order_by("company", "asc");
		return $this->db->get();
	}

    //HL 2013-09-11
    function get_all2($enterpriseID)
    {
        $this->db->from('subsidaries');
        $this->db->where('enterprise_id',$enterpriseID);
        $this->db->order_by("company", "asc");
        return $this->db->get();
    }
	
	/*
	Gets information about multiple subsidaries
	*/
	function get_multiple_info($subsidary_ids) /*HECTOR*/
	{
		$this->db->from('subsidaries');		
		$this->db->where_in('subsidary_id',$subsidary_ids);
		$this->db->order_by("company", "asc");
		return $this->db->get();		
	}
	
	function setSubsidary($subsidaryID)
	{	
		$this->db->from('subsidaries'); //empleados
		$this->db->where('subsidary_id', $subsidaryID);
		$q= $this->db->get();
		if($q->num_rows()!=1)			
			return "";
		
		$enterpriseID = $q->row()->enterprise_id;	
		
			
		$this->db->where('person_id', $this->session->userdata('person_id')); //change
		$this->db->update('people',array("subsidary_id"=>$subsidaryID , "enterprise_id"=>$enterpriseID ));//change		
		
		$this->session->set_userdata('subsidary_id',$subsidaryID);	
		$this->session->set_userdata('enterprise_id',$enterpriseID);	
	}
    
/*
	Deletes one subsidary
	*/
	function delete($subsidaryID)
	{
		$this->db->from('employees'); //empleados
		$this->db->join('people','employees.person_id=people.person_id');			
		$this->db->where('people.subsidary_id', $subsidaryID);
		$q= $this->db->get();
		foreach($q->result_array() as $row)
		{		
			$this->db->where('person_id',$row["person_id"]);
			$this->db->update("employees",array('deleted' => 1));//chequear q esto este bien	
		}
		
		
	//	$this->db->from('custumers');//clientes
//		$this->db->join('people','custumers.person_id=people.person_id');			
//		$this->db->where('people.subsidary_id', $subsidaryID);
//		$q= $this->db->get();
//		foreach($q->result() as $row)
//		{		
//			$this->db->where('person_id', 	$row["people.person_id"]);
//			$this->db->update("custumers",array('custumers.deleted' => 1));//chequear q esto este bien	
//		}
//		
//		$this->db->from('suppliers');//proveedores
//		$this->db->join('people','suppliers.person_id=people.person_id');			
//		$this->db->where('people.subsidary_id', $subsidaryID);
//		$q= $this->db->get();
//		foreach($q->result() as $row)
//		{		
//			$this->db->where('person_id', 	$row["people.person_id"]);
//			$this->db->update("suppliers",array('suppliers.deleted' => 1));//chequear q esto este bien	
//		}
//		 
//		$this->db->where("subsidary_id = $subsidaryID");//items
//		return $this->db->update('items', array('deleted' => 1));
		 
		$this->db->where('subsidary_id', $subsidaryID);//sucursales como tal
		return $this->db->update('subsidaries', array('deleted' => 1));
		
	}
	
	function IsDeleted($subsidaryID)
	{	
		$this->db->from('subsidaries');
		//$this->db->where(array("subsidary_id "=> $subsidaryID, 'deleted' => 1) );
		$this->db->where("subsidary_id = $subsidaryID and deleted = 1 ");
		$q= $this->db->get();
		return ($q->num_rows()== 1);

	}
	
	function UNdelete($subsidaryID)
	{	
        $this->db->from('employees'); //empleados
		$this->db->join('people','employees.person_id=people.person_id');			
		$this->db->where('people.subsidary_id', $subsidaryID);
		$q= $this->db->get();
		foreach($q->result_array() as $row)
		{		
			$this->db->where('person_id',$row["person_id"]);
			$this->db->update("employees",array('deleted' => 0));//chequear q esto este bien	
		}
		$this->db->where('subsidary_id', $subsidaryID);//sucursales como tal
		return $this->db->update('subsidaries', array('deleted' => 0));
		
	}
	
	function getName($subsidaryID)
	{		
		$this->db->from('subsidaries'); //empleados
		$this->db->where('subsidary_id', $subsidaryID);
		$q= $this->db->get();
		if($q->num_rows()==1)			
			return $q->row()->company;		
	}

	/*
	Deletes a list of subsidaries
	*/
	function delete_list($subsidary_ids)
	{
		$this->db->where_in('subsidary_id',$subsidary_ids);
		return $this->db->update('subsidaries', array('deleted' => 1));
 	}

	/*
	Gets information about a particular subsidary
	*/
	function get_info($subsidary_id) /*HECTOR*/
	{
		$this->db->from('subsidaries');	
		$this->db->where('subsidary_id',$subsidary_id);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $subsidary_id is NOT an subsidary
			$subsidary_obj=new stdClass();
			
			//Get all the fields from subsidaries table
			$fields = $this->db->list_fields('subsidaries');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$subsidary_obj->$field='';
			}
			
			return $subsidary_obj;
		}
	}
	
	function save(&$data,$subsidary_id=false, $enterprise_id=-1) 
	{
            if($enterprise_id==-1)
                $enterprise_id = $this->session->userdata('enterprise_id');

    //print_r($data);
            if (!$subsidary_id || !$this->exists($subsidary_id))
            {
      //  echo $subsidary_id;
              // print_r($data);
    /*	  $dataInsert=$data;
                    if(!$dataInsert['enterprise_id'] || $dataInsert['enterprise_id']==-1)			
                            $dataInsert['enterprise_id']=$this->session->userdata('enterprise_id');	*/		
                    $this->db->insert('subsidaries',$data);
                    $id = $this->db->insert_id();
                    
                    //2013-07-23
                    $data_cycle = array(
                        'subsidary_id'=>$id,
                        'total_cycles'=>(12/$data['closing_cycle'])
                    );
                    $this->db->insert('subsidaries_cycles',$data_cycle);	
                    /*****/
                    $this->db->where('subsidary_id', $id);
                    return $this->db->update('subsidaries',array('enterprise_id'=>$enterprise_id));			
            }
            //2013-07-30
            $sub_cycle = $this->get_subsidary_cycle($subsidary_id);
            if($sub_cycle)
            {
                $sub = $this->get_info($subsidary_id);
                if($sub->closing_cycle != $data['closing_cycle'])
                {
                    $cycles = (12/$data['closing_cycle']);
                    $data_cycle = array(
                        'daily_count' => 1,
                        'total_cycles'=> $sub_cycle->count_cycles + ($cycles - 1)
                    );

                    $this->db->where('subsidary_id', $subsidary_id);
                    $this->db->update('subsidaries_cycles',$data_cycle);
                }
            }
            else
            {
                $data_cycle = array(
                    'subsidary_id'=>$subsidary_id,
                    'total_cycles'=>(12/$data['closing_cycle'])
                );
                $this->db->insert('subsidaries_cycles',$data_cycle);
            }
            /*****/

            $this->db->where('subsidary_id', $subsidary_id);

            return $this->db->update('subsidaries',$data);	
	}
	
	function get_print_after_sale()
	{	
                $subsidaryID = $this->session->userdata('subsidary_id');
		
		$this->db->from('subsidaries');	
		$this->db->where("print_after_sale = 1 and subsidary_id = $subsidaryID");
		$query = $this->db->get(); 
		if($query->num_rows()==1)
                    return true;
		else
                    return false;
	}
        
        function get_order_and_finishSale()
	{	
                $subsidaryID = $this->session->userdata('subsidary_id');
		
		$this->db->from('subsidaries');	
		$this->db->where("order_and_finishSale = 1 and subsidary_id = $subsidaryID");
		$query = $this->db->get(); 
		if($query->num_rows()==1)
                    return true;
		else
                    return false;
	}
        
        function get_delivery_and_finishSale()
	    {
            $subsidaryID = $this->session->userdata('subsidary_id');
		
            $this->db->from('subsidaries');
            $this->db->where("delivery_and_finishSale = 1 and subsidary_id = $subsidaryID");
            $query = $this->db->get();
            if($query->num_rows()==1)
                return true;
            else
                return false;
	    }
        
        function get_currency($subsidary_id)
        {
           $sub = $this->get_info($subsidary_id);
           if($sub->currency_id)
           {
               $cur = $this->Currency->get_info($sub->currency_id);
               return $cur;
           }
        }
        
        function get_language()
        {
            $row = $this->get_info($this->session->userdata('subsidary_id'));
            return $row->language;
        }
        
        function get_subsidary_cycle($subsidaryID)
	    {
            $this->db->from('subsidaries_cycles');	
            $this->db->where("subsidary_id = $subsidaryID");
            $query = $this->db->get();
            
            if($query->num_rows()==1)
                return $query->row();
            else
                return false;
	    }
}