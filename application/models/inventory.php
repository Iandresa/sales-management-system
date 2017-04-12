<?php
class Inventory extends Model 
{	
	function insert($inventory_data)
	{
		return $this->db->insert('inventory',$inventory_data);
	}
        
        function inventory_Item_Exists($sale_remarks,$item_id)
	{
		$this->db->from('inventory');
		$this->db->where("trans_comment = '$sale_remarks' and trans_items = $item_id");
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}
        
        function update_inventory($sale_remarks,$item_id,$inv_data)
	{
		$this->db->where("trans_comment = '$sale_remarks' and trans_items = $item_id");
                $this->db->update('inventory',$inv_data);
	}
}

?>