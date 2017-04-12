<?php
require_once("report.php");
class Summary_payments extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array($this->lang->line('reports_payment_type'), $this->lang->line('reports_total'));
	}
	
	public function getData(array $inputs)
	{
		$subsidaryID = $this->session->userdata('subsidary_id');//change
		
		$this->db->select('sales_payments.payment_type, SUM(payment_amount) as payment_amount', false);
		$this->db->from('sales_payments');
		$this->db->join('sales', 'sales.sale_id=sales_payments.sale_id');
		$this->db->join('customers', 'customers.person_id = sales.customer_id');//change
		$this->db->join('people', 'customers.person_id = people.person_id');//change
		$this->db->where('date(sale_time) BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
		$this->db->where("subsidary_id = $subsidaryID");//change
		$this->db->group_by("payment_type");
		return $this->db->get()->result_array();
	}
	
	public function getData2(array $inputs)
	{
		$subsidaryID = $this->session->userdata('subsidary_id');//change
		
		$this->db->select('sales_payments.payment_type, SUM(payment_amount) as payment_amount', false);
		$this->db->from('sales_payments');
		$this->db->join('sales', 'sales.sale_id=sales_payments.sale_id');
		$this->db->join('customers', 'customers.person_id = sales.customer_id');//change
		$this->db->join('people', 'customers.person_id = people.person_id');//change
		$this->db->where('date(sale_time) BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
		$this->db->where("subsidary_id = $subsidaryID");//change
		$this->db->group_by("payment_type");
		return $this->db->get();
	}
	
	public function getSummaryData(array $inputs)
	{
		$subsidaryID = $this->session->userdata('subsidary_id');//change
		
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit');
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'sales_items_temp.item_id = items.item_id');
		$this->db->where('sale_date BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
		$this->db->where("subsidary_id = $subsidaryID");//change

		return $this->db->get()->row_array();
	}
}
?>