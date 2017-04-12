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
		$this->db->where('subsidary_id', $subsidaryID);//sucursales como tal
		return $this->db->update('subsidaries', array('deleted' => 1));		
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
		$this->db->where('subsidary_id', $subsidaryID);//sucursales como tal
		return $this->db->update('subsidaries', array('deleted' => 0));
		
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
	}
}