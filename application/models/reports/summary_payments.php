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
		$this->db->where('date(sale_time) BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
		$this->db->where("subsidary_id = $subsidaryID and is_finished = 1");//change
		$this->db->group_by("sales_payments.payment_type");
		return $this->db->get()->result_array();
	}
	
	public function getData2(array $inputs)
	{
		$subsidaryID = $this->session->userdata('subsidary_id');//change
		
		$this->db->select('sales_payments.payment_type, SUM(payment_amount) as payment_amount', false);
		$this->db->from('sales_payments');
		$this->db->join('sales', 'sales.sale_id=sales_payments.sale_id');
		$this->db->where('date(sale_time) BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
		$this->db->where("subsidary_id = $subsidaryID and is_finished = 1");//change
		$this->db->group_by("payment_type");
		return $this->db->get();
	}
	
	public function getSummaryData(array $inputs)
	{
                $report_data = $this->getData($inputs);
                
                $total = 0;
                foreach($report_data as $row)
		{
			$total += $row['payment_amount'];
		}
                
                $summaryData = array('total'=>$total);

		return $summaryData;
	}
	
	public function getSummaryData2(array $inputs)
	{
		$subsidaryID = $this->session->userdata('subsidary_id');//change
		
		$this->db->select('sum(total) as total, sum(tax) as tax, sum(profit) as profit');
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'items.item_id = sales_items_temp.item_id');//change
		$this->db->where('sale_date BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
		$this->db->where("subsidary_id = $subsidaryID and is_finished = 1");//change

		return $this->db->get()->row_array();
	}
}
?>