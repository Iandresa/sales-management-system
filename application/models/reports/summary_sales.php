<?php
require_once("report.php");
class Summary_sales extends Report
{
	function __construct()
	{
		parent::__construct();
	}

	public function getDataColumns()
	{
		return array($this->lang->line('reports_date'), $this->lang->line('reports_sales2'), $this->lang->line('reports_subtotal'), $this->lang->line('reports_total'), $this->lang->line('reports_tax'), $this->lang->line('reports_profit'), $this->lang->line('reports_cost'), $this->lang->line('common_actions'));
	}
        
        public function getDataColumnsDetails()//ecp
	{
            
            return array($this->lang->line('reports_date'), $this->lang->line('items_item'),$this->lang->line('reports_sales_detail_count'), $this->lang->line('reports_subtotal'), $this->lang->line('reports_total'), $this->lang->line('reports_tax'), $this->lang->line('reports_profit'), $this->lang->line('reports_cost'));
    
		
	}
	
	public function getData(array $inputs)//devuelve las ventas
	{            
            $subsidaryID = $this->session->userdata('subsidary_id');//change
            $start_date = $inputs['start_date'];
            $end_date = $inputs['end_date'];        
                     
            $this->db->query("

            CREATE TEMPORARY TABLE phppos_sales_temp (
            SELECT phppos_sales_items_temp.sale_date,
            COUNT(DISTINCT phppos_sales_items_temp.sale_id)as count_sales,
            sum(phppos_sales_items_temp.subtotal) as subtotal,
            sum(phppos_sales_items_temp.total) as total,
            sum(phppos_sales_items_temp.tax) as tax, 
            sum(phppos_sales_items_temp.profit) as profit,
            sum(phppos_sales_items_temp.cost) as cost
            from phppos_sales_items_temp 
            where  phppos_sales_items_temp.is_finished = 1 
            and phppos_sales_items_temp.mode = 'sale' 
            and phppos_sales_items_temp.sale_subsidary = $subsidaryID 
            and phppos_sales_items_temp.sale_date BETWEEN '$start_date' and '$end_date'
            group by phppos_sales_items_temp.sale_date
            order by phppos_sales_items_temp.sale_date
            )

                            ");

            $this->db->query("

            CREATE TEMPORARY TABLE phppos_returns_temp (
            SELECT phppos_sales_items_temp.sale_date,
            (0-COUNT(DISTINCT phppos_sales_items_temp.sale_id))as count_sales,
            sum(phppos_sales_items_temp.subtotal) as subtotal,
            sum(phppos_sales_items_temp.total) as total,
            sum(phppos_sales_items_temp.tax) as tax, 
            sum(phppos_sales_items_temp.profit) as profit,
            sum(phppos_sales_items_temp.cost) as cost
            from phppos_sales_items_temp
            where  phppos_sales_items_temp.is_finished = 1 
            and phppos_sales_items_temp.mode = 'return' 
            and phppos_sales_items_temp.sale_subsidary = $subsidaryID 
            and phppos_sales_items_temp.sale_date BETWEEN '$start_date' and '$end_date'		
            group by phppos_sales_items_temp.sale_date
            order by phppos_sales_items_temp.sale_date
            )     

                            ");


            $query = $this->db->query("
            SELECT *
            from phppos_sales_temp
            union all
            SELECT *
            from phppos_returns_temp
                            ");
            
            
            


             return $query->result_array();
	}
        public function getGraphicData(array $inputs)
	{
            $subsidaryID = $this->session->userdata('subsidary_id');//change
            $start_date = $inputs['start_date'];
            $end_date = $inputs['end_date'];        
                     
            $this->db->query("

            CREATE TEMPORARY TABLE phppos_sales_temp (
            SELECT phppos_sales_items_temp.sale_date,
            COUNT(DISTINCT phppos_sales_items_temp.sale_id)as count_sales,
            sum(phppos_sales_items_temp.subtotal) as subtotal,
            sum(phppos_sales_items_temp.total) as total,
            sum(phppos_sales_items_temp.tax) as tax, 
            sum(phppos_sales_items_temp.profit) as profit,
            sum(phppos_sales_items_temp.cost) as cost
            from phppos_sales_items_temp 
            where  phppos_sales_items_temp.is_finished = 1 
            and phppos_sales_items_temp.mode = 'sale' 
            and phppos_sales_items_temp.sale_subsidary = $subsidaryID 
            and phppos_sales_items_temp.sale_date BETWEEN '$start_date' and '$end_date'
            group by phppos_sales_items_temp.sale_date
            order by phppos_sales_items_temp.sale_date
            )

                            ");

            $this->db->query("

            CREATE TEMPORARY TABLE phppos_returns_temp (
            SELECT phppos_sales_items_temp.sale_date,
            (0-COUNT(DISTINCT phppos_sales_items_temp.sale_id))as count_sales,
            sum(phppos_sales_items_temp.subtotal) as subtotal,
            sum(phppos_sales_items_temp.total) as total,
            sum(phppos_sales_items_temp.tax) as tax, 
            sum(phppos_sales_items_temp.profit) as profit,
            sum(phppos_sales_items_temp.cost) as cost
            from phppos_sales_items_temp
            where  phppos_sales_items_temp.is_finished = 1 
            and phppos_sales_items_temp.mode = 'return' 
            and phppos_sales_items_temp.sale_subsidary = $subsidaryID 
            and phppos_sales_items_temp.sale_date BETWEEN '$start_date' and '$end_date'		
            group by phppos_sales_items_temp.sale_date
            order by phppos_sales_items_temp.sale_date
            )     

                            ");


            $query = $this->db->query("
            CREATE TEMPORARY TABLE phppos_temp_results 			
            SELECT *
            from phppos_sales_temp
            union all
            SELECT *
            from phppos_returns_temp
                            ");
            
            $query = $this->db->query("
            SELECT sale_date, 
            sum(count_sales) as count_sales,
            sum(subtotal) as subtotal,
            sum(total) as total,
            sum( tax) as tax,
            sum(profit) as profit,
            sum( cost) as cost
            FROM phppos_temp_results 
            group by sale_date
            ORDER BY sale_date
                            ");
            


             return $query->result_array();
        }
//	public function getDataReturn(array $inputs)//ecp devuelve las devoluciones
//	{		
//		$subsidaryID = $this->session->userdata('subsidary_id');//change
//		
//		$this->db->select('sale_date, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax,sum(profit) as profit, sum(cost) as cost');
//		$this->db->from('sales_items_temp');
//		$this->db->join('items', 'items.item_id = sales_items_temp.item_id');//change
//		$this->db->where("subsidary_id = $subsidaryID and is_finished = 1 and mode = 'return'");//change
//		$this->db->group_by('sale_date');
//		$this->db->having('sale_date BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
//		$this->db->order_by('sale_date');
//		return $this->db->get()->result_array();
//	}
        
	public function getData2(array $inputs)
	{		
		$subsidaryID = $this->session->userdata('subsidary_id');//change
		
		$this->db->select('sale_date, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax,sum(profit) as profit, sum(cost) as cost');
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'items.item_id = sales_items_temp.item_id');//change
		$this->db->where("subsidary_id = $subsidaryID and is_finished = 1");//change
		$this->db->group_by('sale_date');
		$this->db->having('sale_date BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
		$this->db->order_by('sale_date');
		return $this->db->get();
	}
        
        public function getData3($date)//for details
	{		
		$subsidaryID = $this->session->userdata('subsidary_id');//change
		
		$this->db->select('sale_date,name,quantity_purchased,subtotal,total,tax,profit,cost');
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'items.item_id = sales_items_temp.item_id');//change
		$this->db->where("subsidary_id = $subsidaryID and is_finished = 1");//change
		$this->db->having('sale_date = "'.$date.'"');
                $this->db->order_by('subtotal');
		return $this->db->get()->result_array();
	}
	
	public function getSummaryData(array $inputs)//este es el utilizado para llenar el footer
	{
		$subsidaryID = $this->session->userdata('subsidary_id');//change               
                $start_date = $inputs['start_date'];
                $end_date = $inputs['end_date'];

                $this->db->query("

                CREATE TEMPORARY TABLE phppos_sales_items_temp_finished_sales (
                SELECT phppos_sales_items_temp.*
                from phppos_sales_items_temp                
                where  phppos_sales_items_temp.sale_subsidary = $subsidaryID
                and phppos_sales_items_temp.is_finished = 1
                and phppos_sales_items_temp.sale_date BETWEEN '$start_date' and '$end_date'
                )

                ");
                $this->db->query("
                CREATE TEMPORARY TABLE phppos_sales_countS (
                SELECT COUNT(DISTINCT phppos_sales_items_temp_finished_sales.sale_id)as count_sales
                from phppos_sales_items_temp_finished_sales
                where   phppos_sales_items_temp_finished_sales.mode = 'sale'  


                )


                ");
                
                $this->db->query("
                CREATE TEMPORARY TABLE phppos_sales_countR (
                SELECT COUNT(DISTINCT phppos_sales_items_temp_finished_sales.sale_id)as count_return
                from phppos_sales_items_temp_finished_sales
                where phppos_sales_items_temp_finished_sales.mode = 'return'

                )

                ");
                
                               
                $query = $this->db->query("                       
                CREATE TEMPORARY TABLE phppos_sales_count_sum(
                SELECT SUM(phppos_sales_countS.count_sales-phppos_sales_countR.count_return) as suma
                from phppos_sales_countS
                INNER JOIN phppos_sales_countR
                )        
                    
                ");
                
                $query = $this->db->query("                       
                SELECT (phppos_sales_count_sum.suma) as sales_amount,
                sum(phppos_sales_items_temp_finished_sales.subtotal) as subtotal,
                sum(phppos_sales_items_temp_finished_sales.total) as total,
                sum(phppos_sales_items_temp_finished_sales.tax) as tax,
                sum(phppos_sales_items_temp_finished_sales.profit) as profit,
                sum(phppos_sales_items_temp_finished_sales.cost) as cost
                from phppos_sales_items_temp_finished_sales
                INNER JOIN phppos_sales_count_sum 
                GROUP BY sales_amount                       
                    
                ");
                
                return $query->row_array();               
		

	}
        
        public function getSummaryData2($date)
	{
		$subsidaryID = $this->session->userdata('subsidary_id');
		
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax,sum(profit) as profit,sum(cost) as cost');
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'items.item_id = sales_items_temp.item_id');
		$this->db->where("subsidary_id = $subsidaryID and is_finished = 1");
                $this->db->where('sale_date = "'.$date.'"');
		return $this->db->get()->row_array();		
	}
        
        public function getSummaryDataDetails($date)//ecp
	{
		$subsidaryID = $this->session->userdata('subsidary_id');
		
		$this->db->select('sum(quantity_purchased) as quantity_sold,sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax,sum(profit) as profit,sum(cost) as cost');
		$this->db->from('sales_items_temp');
		$this->db->join('items', 'items.item_id = sales_items_temp.item_id');
		$this->db->where("subsidary_id = $subsidaryID and is_finished = 1");
                $this->db->where('sale_date = "'.$date.'"');
		return $this->db->get()->row_array();		
	}
        
        
//        public function getSummarySales($pdate)//ecp suma las ventas de una subsidiaria en una fehca dada
//	{           
//                $subsidaryID = $this->session->userdata('subsidary_id');
//                
//                $this->db->select('COUNT(DISTINCT sale_id)as count_sales');
//                $this->db->from('sales');
//                $this->db->where("subsidary_id = $subsidaryID and is_finished = 1");
//                $this->db->where('date(sale_time) = "'.$pdate.'" and mode = "sale"');
//                
//                
//                return $this->db->get()->row()->count_sales;          
//                
//	}
//        
//        public function getSummaryReturns($pdate)//ecp suma las devoluciones de una subsidiaria en una fehca dada
//	{           
//                $subsidaryID = $this->session->userdata('subsidary_id');
//                
//                $this->db->select('COUNT(DISTINCT sale_id)as count_sales');
//                $this->db->from('sales');
//                $this->db->where("subsidary_id = $subsidaryID and is_finished = 1");
//                $this->db->where('date(sale_time) = "'.$pdate.'" and mode = "return"');
//                
//                
//                return $this->db->get()->row()->count_sales;          
//                
//	}
        
        
        

}
?>