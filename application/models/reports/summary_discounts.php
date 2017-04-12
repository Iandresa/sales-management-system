<?php
require_once("report.php");
class Summary_discounts extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array($this->lang->line('reports_discount_percent'),$this->lang->line('reports_count'));
	}
	
	public function getData(array $inputs)
	{
		$subsidaryID = $this->session->userdata('subsidary_id');//change
		
		$this->db->select('CONCAT(discount_percent, "%") as discount_percent, count(*) as count', false);
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'items.item_id = sales_items_temp.item_id');//change
                $this->db->where('sale_date BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'" and discount_percent > 0');
		$this->db->where("subsidary_id = $subsidaryID and is_finished = 1");//change
		$this->db->group_by('sales_items_temp.discount_percent');
		$this->db->order_by('discount_percent');
		return $this->db->get()->result_array();		
	}
	
	public function getData2(array $inputs)
	{
		$subsidaryID = $this->session->userdata('subsidary_id');//change
		
		$this->db->select('CONCAT(discount_percent, "%") as discount_percent, count(*) as count', false);
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'items.item_id = sales_items_temp.item_id');//change
                $this->db->where('sale_date BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'" and discount_percent > 0');
		$this->db->where("subsidary_id = $subsidaryID and is_finished = 1");//change
		$this->db->group_by('sales_items_temp.discount_percent');
		$this->db->order_by('discount_percent');
		return $this->db->get();		
	}
	
	public function getSummaryData(array $inputs)
	{
		$subsidaryID = $this->session->userdata('subsidary_id');//change
		
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax,sum(profit) as profit');
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'items.item_id = sales_items_temp.item_id');//change
                $this->db->where('sale_date BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
		$this->db->where("subsidary_id = $subsidaryID and is_finished = 1");//change
		return $this->db->get()->row_array();		
	}
}
?>