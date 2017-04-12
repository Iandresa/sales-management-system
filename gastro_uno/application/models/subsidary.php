<?php
class Subsidary extends Model
{
	/*
	Returns all the Subsidaries
	*/
	function get_all()//saca la empresa por el empleado activo
	{
		$enterpriseID=$this->session->userdata('enterprise_id');
		$this->db->from('subsidaries');
		$this->db->where("enterprise_id = $enterpriseID and deleted=0");//change migue
		$this->db->order_by("Company", "asc");
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

	function delete($subsidaryID)
	{
//		$this->db->from('employees'); //empleados
//		$this->db->join('people','employees.person_id=people.person_id');			
//		$this->db->where('people.subsidary_id', $subsidaryID);
//		$q= $this->db->get();
//		foreach($q->result() as $row)
//		{		
//			$this->db->where('person_id', 	$row["people.person_id"]);
//			$this->db->update("employees",array('employees.deleted' => 1));//chequear q esto este bien	
//		}
		
		
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

	
//	function delete_list($item_ids)
//	{
//		$this->db->where_in('$subsidary_id',$item_ids);
//		return $this->db->update('subsidaries', array('deleted' => 1));
//		y quito todos sus modulos a los empleados
//		....
// 	}

}