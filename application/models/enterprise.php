<?php
class Enterprise extends Model
{
	/*
	Returns all the Enterprise
	*/
	
	function get_all_subsidaries_from_enterprise($empresaID)//falta!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	{
		$this->db->from('subsidaries');
		$this->db->where("enterprise_id = $empresaID");//change migue
		$this->db->order_by("Company", "asc");
		return $this->db->get();
	}
	
	function get_all()//los datos de las empresas
	{		
		$this->db->from('enterprises');
		return $this->db->get();
	}
        
	function is_empty($empresaID)
	{
		$this->db->from('subsidaries'); 
		$this->db->where("enterprise_id = $empresaID AND deleted = 0");
		$q = $this->db->get();
		return ($q->num_rows() == 0);	
		
	}
	function is_selected_and_the_only($empresaID)
	{	
		$this->db->from('subsidaries'); 
		$this->db->where("enterprise_id = $empresaID");
		$q = $this->db->get();		
		return ($q->num_rows() == 1 && $empresaID == $this->session->userdata('enterprise_id') ); 		
	}
//	function setEnterprise($enterpriseID)
//	{	
//		ESTA FUNCION NO EXISTE PQ SE USA LA DE SUBSIDARY PARA CAMBIAR TAMBIEN LA EMPRESA	
//	}
	function getName($enterpriseID)
	{			
		$this->db->from('enterprises'); //empleados
		$this->db->where('enterprise_id', $enterpriseID);
		$q= $this->db->get();
		if($q->num_rows()==1)			
			return $q->row()->name;		
	}
        
        function get_currency($enterprise_id)
        {
            $this->db->from('enterprises'); //empleados
            $this->db->where('enterprise_id', $enterprise_id);
            $q= $this->db->get();
            if($q->num_rows()==1)			
                    return $q->row()->currency_id;	
        }
	
	function delete($enterpriseID)//falta!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	{
		$this->db->from('subsidaries'); //empleados
		$this->db->where('enterprise_id', $enterpriseID);
		$q= $this->db->get();
		foreach($q->result() as $row)//subsidiaria I
		{		
			$this->Subsidary->delete($row->subsidary_id);
		}		
	}
	
	
	function delete_Subsidary($subsidaryID)
	{		 
		return $this->Subsidary->delete($subsidaryID);	
	}
	
	function UNdelete($enterpriseID)//falta!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	{
		$this->db->from('subsidaries'); //empleados
		$this->db->where('enterprise_id', $enterpriseID);
		$q= $this->db->get();
		foreach($q->result() as $row)//subsidiaria I
		{		
			$this->Subsidary->UNdelete($row->subsidary_id);
		}		
	}
	
	function UNdelete_Subsidary($subsidaryID)
	{		 
		return $this->Subsidary->UNdelete($subsidaryID);		
	}
	
	function IsDeleted($subsidaryID)
	{
		$this->db->from('subsidaries'); //empleados
		$this->db->where('enterprise_id', $enterpriseID);
		$q= $this->db->get();
		foreach($q->result() as $row)//subsidiaria I
		{		
			if(!$this->Subsidary->IsDeleted($row->subsidary_id))return false;
		}	
		return true;	

	}
	
	function IsDeleted_Subsidary($subsidaryID)
	{	
		$this->db->from('subsidaries');
		//$this->db->where(array("subsidary_id "=> $subsidaryID, 'deleted' => 1) );
		$this->db->where("subsidary_id = $subsidaryID and deleted = 1 ");
		$q= $this->db->get();
		return ($q->num_rows()== 1);

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
		
		$this->db->from('enterprises');
		$this->db->where('enterprise_id', $this->session->userdata('enterprise_id'));
		$e= $this->db->get();
		if($e->num_rows()==1)			
			return "";		
	
	}
	function exists($enterprise_id) /*MIgue*/
	{
		
		$this->db->from('enterprises');
		$this->db->where("enterprise_id = $enterprise_id");
		$query = $this->db->get();

		return ($query && $query->num_rows()==1);
	}
	function get_permi_gr_reports($enterprise_id)/*MIgue*/
	{
		$this->db->from('enterprises'); //empleados
		$this->db->where('enterprise_id', $enterprise_id);
		$q= $this->db->get();
		if($q->num_rows()==1)			
			return $q->row()->permi_gr_reports;	
		return 0;//change '0'
	}
	function get_permi_uncomplete_sale($enterprise_id)/*MIgue*/
	{
		$this->db->from('enterprises'); //empleados
		$this->db->where('enterprise_id', $enterprise_id);
		$q= $this->db->get();
		if($q->num_rows()==1)			
			return $q->row()->permi_uncomplete_sale;	
		return '0';
	}        
        function get_permi_delivery($enterprise_id)/*HECTOR*/
	{
		$this->db->from('enterprises'); //empleados
		$this->db->where('enterprise_id', $enterprise_id);
		$q= $this->db->get();
		if($q->num_rows()==1)			
			return $q->row()->permi_delivery;	
		return '0';
	}
	function get_permi_hide_banners($enterprise_id)/*MIgue*/
	{
		$this->db->from('enterprises'); //empleados
		$this->db->where('enterprise_id', $enterprise_id);
		$q= $this->db->get();
		if($q->num_rows()==1)			
			return $q->row()->permi_hide_banners;	
		return '0';
	}
	/*
	Gets information about a particular subsidary
	*/
	function get_info($enterprise_id) /*HECTOR*/
	{
		$this->db->from('enterprises');	
		$this->db->where('enterprise_id',$enterprise_id);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $subsidary_id is NOT an subsidary
			$enterprise_obj=new stdClass();
			
			//Get all the fields from subsidaries table
			$fields = $this->db->list_fields('subsidaries');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$enterprise_obj->$field='';
			}
			
			return $enterprise_obj;
		}
	}
	
	function save(&$data,$enterprise_id=-1) /*MIgue*/
	{
           
           // print_r($data);
		if ($enterprise_id==-1 || !$this->exists($enterprise_id))
		{
		  // print_r($data);
		  	
			if($this->db->insert('enterprises',array('name'=>$data['name'],'permi_gr_reports'=>$data['permi_gr_reports'],'permi_uncomplete_sale'=>$data['permi_uncomplete_sale'],'permi_hide_banners'=>$data['permi_hide_banners'], 'currency_id'=>$data['currency_id'])))
			{					
				$dataSub = array(
					'company'=>$data['company'],
					'country'=>"CUB",
					'address'=>"",
					//'default_tax_1_name'=>"Sales Tax",
					'default_tax_1_rate'=>0,
					//'default_tax_2_name'=>"Sales Tax 2",
					'default_tax_2_rate'=>0,
					"default_tax_rate"=>0,
					'email'=>"",
					'language'=>"spanish",
					'phone'=>"",
					'print_after_sale'=>0,
					'return_policy'=>"",
					'timezone'=>"",
					'version'=>"",
					'website'=>"",
					'enterprise_id'	=> $this->db->insert_id(),
					'deleted'=>0,
                                        'currency_id'=>$data['currency_id']
					);					
				return $this->db->insert('subsidaries',$dataSub);						 
			}			
			return false;
		}	
		        
        //if (!$this->Employee->is_SuperUser( $this->session->userdata('person_id')))
		//{
                $this->db->select('people.person_id');
                $this->db->from('people');
                $this->db->join('enterprises', 'people.enterprise_id = enterprises.enterprise_id');
                $this->db->where('enterprises.enterprise_id', $enterprise_id);
                $persons = $this->db->get();                         
          
                if($data['permi_uncomplete_sale'] == '1' || $data['permi_uncomplete_sale'] == '2')
		        {	
	                    if(isset ($data['permi_uncomplete_sale_days']))
                            {                               
                                $days=$data['permi_uncomplete_sale_days'];                            
                                $this->db->query("UPDATE phppos_enterprises SET permi_uncomplete_sale_expiredate = DATE_ADD(NOW(), INTERVAL $days day) WHERE enterprise_id = $enterprise_id");
                            }
	                    foreach ($persons->result() as $p)
		               	if($this->Employee->has_permission('subsidaries',$p->person_id))
	                               $this->db->insert('phppos_permissions', array('person_id'=>$p->person_id, 'module_id'=>'cafeteria'));
		        }
		        else
		        {
		      
		            foreach ($persons->result() as $p)
		            {
					if(!$this->Employee->is_SuperUser($p->person_id))
						{ 
							$this->db->where("person_id = $p->person_id AND module_id='cafeteria'");
			                $this->db->delete('phppos_permissions');					
						}	               
		            }  
		        }
	            if($data['permi_delivery'] == '1' || $data['permi_delivery'] == '2')
		        {
                        
                            if(isset ($data['permi_delivery_days']))
                            {
                                $days=$data['permi_delivery_days'];                    
                                $this->db->query("UPDATE phppos_enterprises SET permi_delivery_expiredate = DATE_ADD(NOW(), INTERVAL $days day) WHERE enterprise_id = $enterprise_id");
                            }
                              foreach ($persons->result() as $p)
	                      if($this->Employee->has_permission('subsidaries',$p->person_id))
		                $this->db->insert('phppos_permissions', array('person_id'=>$p->person_id, 'module_id'=>'deliveries'));
		        }
		        else
		        {
		      
		            foreach ($persons->result() as $p)
		            {
					if(!$this->Employee->is_SuperUser($p->person_id))
						{ 
							$this->db->where("person_id = $p->person_id AND module_id='deliveries'");
			                $this->db->delete('phppos_permissions');					
						}	               
		            }  
		        }
	        
			//}
	                 
	             //   $this->db->where('enterprise_id', $enterprise_id);
			//return $this->db->update('enterprises',array('permi_gr_reports_expiredate'=>date('d m Y')));//date();//time();//date('d m Y');
	          				    
	            if($data['permi_gr_reports'] == '1' || $data['permi_gr_reports'] == '2') 
                    {//echo "AQUI-11";
                       if(isset ($data['permi_gr_reports_days']))
                       {//echo "AQUI-12";
                       $days=$data['permi_gr_reports_days'];                       
	               $this->db->query("UPDATE phppos_enterprises SET permi_gr_reports_expiredate = DATE_ADD(NOW(), INTERVAL $days day)  WHERE enterprise_id = $enterprise_id"); 
                       }
                    }
	                
                    //Ariel: Cambiar los Currencies de las subsidiarias que de esta empresa menos 
                    //       las subsidiarias que tengas distinta currency.
                    
                        $subs = $this->get_all_subsidaries_from_enterprise($enterprise_id);
                        $ent = $this->get_info($enterprise_id);
                        foreach ($subs->result() as $sub)
                        {
                            if($sub->currency_id == $ent->currency_id || !$sub->currency_id)
                            {
                                $this->db->where("subsidary_id", $sub->subsidary_id);
                                $this->db->Update("subsidaries", array('currency_id'=> $data['currency_id']));
                            }
                         
                        }
                    
                    
	               $this->db->where('enterprise_id', $enterprise_id);
			return $this->db->update('enterprises',array('name'=>$data['name'],'permi_gr_reports'=>$data['permi_gr_reports'],'permi_uncomplete_sale'=>$data['permi_uncomplete_sale'],'permi_hide_banners'=>$data['permi_hide_banners'],'permi_delivery'=>$data['permi_delivery'], 'currency_id'=>$data['currency_id']));	
                
        }
        
        /**
         * @author Ariel F. Cabañas
         * @abstract Esta funcion devuelve los jefes de una enterprise teniendo en cuenta
         * que stos son lo que tienen activado el permiso/modulo "subsidaries"
         **/
        function get_enterprise_managers($enterprise_id)
        {
            
            $this->db->select('*');
            $this->db->from('people');
            $this->db->join('permissions', 'phppos_people.person_id = phppos_permissions.person_id');
            $this->db->where('phppos_people.enterprise_id', $enterprise_id);
            $this->db->where('phppos_permissions.module_id', "subsidaries");
            return $this->db->get();
        }
        
        /**
         * @author Ariel F. Cabañas
         * @abstract Funcion que desactiva los permisos:
         * 1. permi_gr_reports
         * 2. permi_uncomplete_sale
         * 3. permi_delivery
         **/
        function set_permit_trial_off($enterprise_id)
        {
            $this->db->where('enterprise_id', $enterprise_id);
            $this->db->where('permi_gr_reports', '2');
            $this->db->update('phppos_enterprises', array('permi_gr_reports'=> '0'));
            
            $this->db->where('enterprise_id', $enterprise_id);
            $this->db->where('permi_uncomplete_sale', '2');
            $this->db->update('phppos_enterprises', array('permi_uncomplete_sale'=> '0'));
            
            $this->db->where('enterprise_id', $enterprise_id);
            $this->db->where('permi_delivery', '2');
            $this->db->update('phppos_enterprises', array('permi_delivery'=> '0'));            
        }
}
