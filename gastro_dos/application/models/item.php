<?php
class Item extends Model
{
	/*
	Determines if a given item_id is an item
	*/
	function exists($item_id)
	{	
            $this->db->from('items');
            $subsidaryID = $this->session->userdata('subsidary_id');//change
            $this->db->where("item_id = $item_id and subsidary_id = $subsidaryID");//change
            $query = $this->db->get();

            return ($query && $query->num_rows()==1);
	}
        
        /*
	NEW
	*/
	function exists_import($item_number)
	{
            if($item_number==null) return false;
            $this->db->from('items');
            $subsidaryID = $this->session->userdata('subsidary_id');//change
            $this->db->where("item_number = $item_number and subsidary_id = $subsidaryID");//change
            $query = $this->db->get();

            return ($query && $query->num_rows()==1);
	}
	
	function exists_code() //oscar
	{
            $this->db->select('item_number');
            $this->db->from('items');
            //$this->db->where("deleted = 0");
            $this->db->where("item_number IS NOT NULL", null, false);
            $query = $this->db->get();

            return $query->result();
	}

	/*
	Returns all the items
	*/
	function get_all()
	{
            $subsidaryID = $this->session->userdata('subsidary_id');//change

            $this->db->from('items');
            $this->db->where("deleted = 0 and subsidary_id = $subsidaryID");//change migue
            $this->db->order_by("name", "asc");
            return $this->db->get();
	}
        
    //(HL 2013-04-12)
    function get_all_for_sale()
	{
            $subsidaryID = $this->session->userdata('subsidary_id');//change

            $this->db->from('items');
            $this->db->where("deleted = 0 and is_forSale = 1 and subsidary_id = $subsidaryID");//change migue
            $this->db->order_by("name", "asc");
            return $this->db->get();
	}
        
	function get_all_filtered($low_inventory=0,$is_serialized=0,$no_description)
	{
            $this->db->from('items');
            if ($low_inventory !=0 )
            {
                    $this->db->where('quantity <=','reorder_level');
            }
            if ($is_serialized !=0 )
            {
                    $this->db->where('is_serialized',1);
            }
            if ($no_description!=0 )
            {
                    $this->db->where('description','');
            }
            $subsidaryID = $this->session->userdata('subsidary_id');//change migue
            $this->db->where("deleted = 0 and subsidary_id = $subsidaryID");//change migue
            $this->db->order_by("name", "asc");
            return $this->db->get();
	}

	/*
	Gets information about a particular item
	*/
	function get_info($item_id)
	{
            $this->db->from('items');
            $this->db->where('item_id',$item_id);

            $query = $this->db->get();

            if($query->num_rows()==1)
            {
                    return $query->row();
            }
            else
            {
                    //Get empty base parent object, as $item_id is NOT an item
                    $item_obj=new stdClass();

                    //Get all the fields from items table
                    $fields = $this->db->list_fields('items');

                    foreach ($fields as $field)
                    {
                            $item_obj->$field='';
                    }

                    return $item_obj;
            }
	}
        
    //(HL 2013-04-12)
    function get_items_for_noSale($item_id)
	{
            $this->db->from('items');
            $this->db->where("is_forSale = 0 and owner_item_id = $item_id");

            $query = $this->db->get();
            
            return $query; 
	}

	/*
	Get an item id given an item number
	*/
	function get_item_id($item_number)
	{
            $this->db->from('items');
            $this->db->where('item_number',$item_number);

            $query = $this->db->get();

            if($query->num_rows()==1)
            {
                    return $query->row()->item_id;
            }

            return false;
	}

	/*
	Gets information about multiple items
	*/
	function get_multiple_info($item_ids)
	{
            $this->db->from('items');


            $this->db->where_in('item_id',$item_ids);
    //	$subsidaryID=$this->session->userdata('subsidary_id');//change migue	
    //	$this->db->where_in('item_id',$item_ids,'subsidary_id',$subsidaryID);

            $this->db->order_by("item", "asc");
            return $this->db->get();
	}

	/*
	Inserts or updates a item
	*/
	function save(&$item_data,$item_id=false)
	{
            if (!$item_id or !$this->exists($item_id))
            {
                    if($this->db->insert('items',$item_data))
                    {	
                            $item_data['item_id']=$this->db->insert_id();
                            $subsidaryID = $this->session->userdata('subsidary_id');//change migue				
                            $item_data['subsidary_id'] = $subsidaryID;
                            $this->db->where('item_id',$item_data['item_id']);
                            $this->db->update('items', array('subsidary_id' => $subsidaryID));//change
                            return true;
                    }
                    return false;
            }
            $this->db->where('item_id', $item_id);
            return $this->db->update('items',$item_data);
	}
        
        /*
	NEW
	*/
	function save_import(&$item_data)
	{
            if (!$this->exists_import($item_data['item_number']))
            {
                    if($this->db->insert('items',$item_data))
                    {	
                            $item_data['item_id']=$this->db->insert_id();
                            $subsidaryID = $this->session->userdata('subsidary_id');//change migue				
                            $item_data['subsidary_id'] = $subsidaryID;
                            $this->db->where('item_id',$item_data['item_id']);
                            $this->db->update('items', array('subsidary_id' => $subsidaryID));//change
                            return true;
                    }
                    return false;
            }

            $this->db->where('item_number', $item_data['item_number']);
            $item_data['deleted'] = 0;                

            $result = $this->db->update('items',$item_data);

            $item_data['item_id'] = $this->get_item_id($item_data['item_number']);

            return $result;
	}

	/*
	Updates multiple items at once
	*/
	function update_multiple($item_data,$item_ids)
	{
            $this->db->where_in('item_id',$item_ids);
            return $this->db->update('items',$item_data);
	}

	/*
	Deletes one item
	*/
	function delete($item_id)
	{
            $this->db->where('item_id', $item_id);
            return $this->db->update('items', array('deleted' => 1));
	}

	/*
	Deletes a list of items
	*/
	function delete_list($item_ids)
	{
            $this->db->where_in('item_id',$item_ids);
            return $this->db->update('items', array('deleted' => 1));
 	}

 	/*
	Get search suggestions to find items
	*/
	function get_search_suggestions($search,$limit=25)
	{
            $suggestions = array();

            $this->db->from('items');
            $this->db->like('name', $search);
            //$this->db->where('deleted',0);
            $subsidaryID=$this->session->userdata('subsidary_id');//change migue
            $this->db->where("deleted = 0 and subsidary_id = $subsidaryID");//change migue
            $this->db->order_by("name", "asc");
            $by_name = $this->db->get();
            foreach($by_name->result() as $row)
            {
                    $suggestions[]=$row->name;
            }

            $this->db->select('category');
            $this->db->from('items');
            //$this->db->where('deleted',0);
            $this->db->where("deleted = 0 and subsidary_id = $subsidaryID");//change migue
            $this->db->distinct();
            $this->db->like('category', $search);
            $this->db->order_by("category", "asc");
            $by_category = $this->db->get();
            foreach($by_category->result() as $row)
            {
                    $suggestions[]=$row->category;
            }

            $this->db->from('items');
            $this->db->like('item_number', $search);
            $this->db->where("deleted = 0 and subsidary_id = $subsidaryID");//change migue
            $this->db->order_by("item_number", "asc");
            $by_item_number = $this->db->get();
            foreach($by_item_number->result() as $row)
            {
                    $suggestions[]=$row->item_number;
            }


            //only return $limit suggestions
            if(count($suggestions > $limit))
            {
                    $suggestions = array_slice($suggestions, 0,$limit);
            }

            return $suggestions;
	}

	function get_item_search_suggestions($search,$limit=25)
	{
            //TODO aki es el error de venta al poner un producto q ya esta.
            $subsidaryID=$this->session->userdata('subsidary_id');//change migue

            $suggestions = array();

            $this->db->from('items');
            //$this->db->where('deleted',0);	
            $this->db->where("deleted = 0 and subsidary_id = $subsidaryID");//change migue
            $this->db->like('name', $search);
            $this->db->order_by("name", "asc");
            $by_name = $this->db->get();
            foreach($by_name->result() as $row)
            {
                    $suggestions[]=$row->item_id.'|'.$row->name;
            }
/*
            $this->db->from('items');
            $this->db->where("deleted = 0 and subsidary_id = $subsidaryID");//change migue
            $this->db->like('item_number', $search);
            $this->db->order_by("item_number", "asc");
            $by_item_number = $this->db->get();
            foreach($by_item_number->result() as $row)
            {
                    $suggestions[]=$row->item_id.'|'.$row->item_number;
            }
*/
            //only return $limit suggestions
            if(count($suggestions > $limit))
            {
                    $suggestions = array_slice($suggestions, 0,$limit);
            }

            return $suggestions;
	}

        //(HL 2013-04-12)
        function get_item_search_suggestions2($search,$limit=25)
	{
            //TODO aki es el error de venta al poner un producto q ya esta.
            $subsidaryID=$this->session->userdata('subsidary_id');//change migue

            $suggestions = array();

            $this->db->from('items');
            //$this->db->where('deleted',0);	
            $this->db->where("deleted = 0 and is_forSale = 1 and quantity != 0 and subsidary_id = $subsidaryID");//change migue
            $this->db->like('name', $search);
            $this->db->order_by("name", "asc");
            $by_name = $this->db->get();
            foreach($by_name->result() as $row)
            {
                    $suggestions[]=$row->item_id.'|'.$row->name;
            }

            //only return $limit suggestions
            if(count($suggestions > $limit))
            {
                    $suggestions = array_slice($suggestions, 0,$limit);
            }
            
            return $suggestions;
	}
        
	function get_category_suggestions($search)
	{
            $subsidaryID=$this->session->userdata('subsidary_id');//change migue

            $suggestions = array();
            $this->db->distinct();
            $this->db->select('category');
            $this->db->from('items');
            $this->db->like('category', $search);	
            $this->db->where("deleted = 0 and subsidary_id = $subsidaryID");//change migue
            $this->db->order_by("category", "asc");
            $by_category = $this->db->get();
            foreach($by_category->result() as $row)
            {
                    $suggestions[]=$row->category;
            }

            return $suggestions;
	}

	/*
	Preform a search on items
	*/
	function search($search)
	{
            $subsidaryID = $this->session->userdata('subsidary_id');//change migue

            $this->db->from('items');
            $this->db->where("(name LIKE '%".$this->db->escape_like_str($search)."%' or 
            item_number LIKE '%".$this->db->escape_like_str($search)."%' or 
            category LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0 and subsidary_id = $subsidaryID");
            $this->db->order_by("name", "asc");

            return $this->db->get();	
	}

	function get_categories()
	{
            $subsidaryID = $this->session->userdata('subsidary_id');//change migue

            $this->db->select('category');
            $this->db->from('items');
            $this->db->where("deleted = 0 and subsidary_id = $subsidaryID");//change migue
            $this->db->distinct();
            $this->db->order_by("category", "asc");

            return $this->db->get();
	}
        
        function update_taxes($subsidary_id)
        {
            $tax_names = array();
            $tax_percents = array();
            $items_taxes_data = array();
            
            if($subsidary_id!=-1)
            {
                //$tax_names[0] = $this->Appconfig->get('default_tax_1_name');
                //$tax_names[1] = $this->Appconfig->get('default_tax_2_name');
                $tax_percents[0] = $this->Appconfig->get('default_tax_1_rate');
                $tax_percents[1] = $this->Appconfig->get('default_tax_2_rate');

                $this->db->from('items');
                $this->db->where("deleted = 0 and subsidary_id = $subsidary_id and taxes_from_subsidary = 1");
		$items_by_subsidary = $this->db->get();
                
                if($items_by_subsidary->num_rows() > 0)
                {
                    foreach($items_by_subsidary->result() as $row)
                    {
                        for($k=0;$k<count($tax_percents);$k++)
                            $items_taxes_data[] = array('percent'=>$tax_percents[$k]);

                        $success_save = $this->save_taxes($items_taxes_data,$row->item_id);
                    }
                    return true;
                }
                else
                    return false;
            }
            return false;
        }
        
        function save_taxes(&$items_taxes_data, $item_id)
	{
            //Run these queries as a transaction, we want to make sure we do all or nothing
            $this->db->trans_start();

            $this->delete_taxes($item_id);

            foreach ($items_taxes_data as $row)
            {
                    $row['item_id'] = $item_id;
                    $this->db->insert('items_taxes',$row);		
            }

            $this->db->trans_complete();
            return true;
	}
        
        /*
	Deletes taxes given an item
	*/
	function delete_taxes($item_id)
	{
            return $this->db->delete('items_taxes', array('item_id' => $item_id)); 
	}
        
        //Ariel
        function check_item_name($item_name)
        {
            $subsidaryID = $this->session->userdata('subsidary_id');//HL (2013-05-16)

            $this->db->where("name", $item_name);
            $this->db->where("subsidary_id", $subsidaryID);//HL (2013-05-16)
            $this->db->where("deleted", "0");
            return $this->db->count_all_results("items");
        }
        
        //Hector (2013-03-15)
        function item_name_equal($item_name, $item_id)
        {
            $subsidaryID = $this->session->userdata('subsidary_id');//HL (2013-05-16)

            $this->db->where("name", $item_name);
            $this->db->where("item_id", $item_id);
            $this->db->where("subsidary_id", $subsidaryID);//HL (2013-05-16)
            $this->db->where("deleted", "0");
            return $this->db->count_all_results("items");
        }
}
?>
