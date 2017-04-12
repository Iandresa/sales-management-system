<?php
require_once("report.php");
class Summary_customers extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array($this->lang->line('reports_customer'), $this->lang->line('reports_sales2'), $this->lang->line('reports_subtotal'), $this->lang->line('reports_total'), $this->lang->line('reports_tax'), $this->lang->line('reports_profit'), $this->lang->line('reports_cost'));
	}
	
	public function getData(array $inputs)
	{
              
            $subsidaryID = $this->session->userdata('subsidary_id');//change
            $start_date = $inputs['start_date'];
            $end_date = $inputs['end_date'];        
                     
            $query = $this->db->query("
            CREATE TEMPORARY TABLE phppos_sales_items_temp_customers_sub (
            SELECT phppos_sales_items_temp.*
            from phppos_sales_items_temp
            INNER JOIN phppos_sales ON  phppos_sales_items_temp.sale_id = phppos_sales.sale_id
            where  phppos_sales_items_temp.is_finished = 1
            and phppos_sales.subsidary_id = $subsidaryID 
            and phppos_sales_items_temp.sale_date BETWEEN '$start_date' and '$end_date'
            )
                            ");
            
            $this->db->query("

            CREATE TEMPORARY TABLE phppos_sales_temp (
            SELECT phppos_sales_items_temp_customers_sub.customer_id,
            CONCAT(phppos_people.first_name, ' ',phppos_people.last_name) as customer,
            COUNT(DISTINCT phppos_sales_items_temp_customers_sub.sale_id) as count_sales,
            sum(phppos_sales_items_temp_customers_sub.subtotal) as subtotal,
            sum(phppos_sales_items_temp_customers_sub.total) as total,
            sum(phppos_sales_items_temp_customers_sub.tax) as tax,
            sum(phppos_sales_items_temp_customers_sub.profit) as profit,
            sum(phppos_sales_items_temp_customers_sub.cost) as cost
            from phppos_sales_items_temp_customers_sub
            LEFT OUTER JOIN phppos_people ON phppos_sales_items_temp_customers_sub.customer_id = phppos_people.person_id
            WHERE phppos_sales_items_temp_customers_sub.mode = 'sale' 
            group by phppos_sales_items_temp_customers_sub.customer_id
            order by phppos_sales_items_temp_customers_sub.customer_id
            )

                            ");

            $this->db->query("

            CREATE TEMPORARY TABLE phppos_returns_temp (
            SELECT phppos_sales_items_temp_customers_sub.customer_id,
            CONCAT(phppos_people.first_name, ' ',phppos_people.last_name) as customer,
            (0-COUNT(DISTINCT phppos_sales_items_temp_customers_sub.sale_id)) as count_sales,
            sum(phppos_sales_items_temp_customers_sub.subtotal) as subtotal,
            sum(phppos_sales_items_temp_customers_sub.total) as total,
            sum(phppos_sales_items_temp_customers_sub.tax) as tax,
            sum(phppos_sales_items_temp_customers_sub.profit) as profit,
            sum(phppos_sales_items_temp_customers_sub.cost) as cost
            from phppos_sales_items_temp_customers_sub
            LEFT OUTER JOIN phppos_people ON phppos_sales_items_temp_customers_sub.customer_id = phppos_people.person_id
            WHERE phppos_sales_items_temp_customers_sub.mode = 'return' 
            group by phppos_sales_items_temp_customers_sub.customer_id
            order by phppos_sales_items_temp_customers_sub.customer_id
            )

                            ");


            $query = $this->db->query("
            SELECT *
            from phppos_sales_temp
            union all
            SELECT *
            from phppos_returns_temp
            order by customer_id
            
                            ");
            
            
            


             return $query->result_array();
            
            
            
            
                

		
               
	}
	
        public function getGraphicData(array $inputs)
	{
              
            $subsidaryID = $this->session->userdata('subsidary_id');//change
            $start_date = $inputs['start_date'];
            $end_date = $inputs['end_date'];        
                     
            $query = $this->db->query("
            CREATE TEMPORARY TABLE phppos_sales_items_temp_customers_sub (
            SELECT phppos_sales_items_temp.*
            from phppos_sales_items_temp
            INNER JOIN phppos_sales ON  phppos_sales_items_temp.sale_id = phppos_sales.sale_id
            where  phppos_sales_items_temp.is_finished = 1
            and phppos_sales.subsidary_id = $subsidaryID 
            and phppos_sales_items_temp.sale_date BETWEEN '$start_date' and '$end_date'
            )
                            ");
            
            $this->db->query("

            CREATE TEMPORARY TABLE phppos_sales_temp (
            SELECT phppos_sales_items_temp_customers_sub.customer_id,
            CONCAT(phppos_people.first_name, ' ',phppos_people.last_name) as customer,
            COUNT(DISTINCT phppos_sales_items_temp_customers_sub.sale_id) as count_sales,
            sum(phppos_sales_items_temp_customers_sub.subtotal) as subtotal,
            sum(phppos_sales_items_temp_customers_sub.total) as total,
            sum(phppos_sales_items_temp_customers_sub.tax) as tax,
            sum(phppos_sales_items_temp_customers_sub.profit) as profit,
            sum(phppos_sales_items_temp_customers_sub.cost) as cost
            from phppos_sales_items_temp_customers_sub
            LEFT OUTER JOIN phppos_people ON phppos_sales_items_temp_customers_sub.customer_id = phppos_people.person_id
            WHERE phppos_sales_items_temp_customers_sub.mode = 'sale' 
            group by phppos_sales_items_temp_customers_sub.customer_id
            order by phppos_sales_items_temp_customers_sub.customer_id
            )

                            ");

            $this->db->query("

            CREATE TEMPORARY TABLE phppos_returns_temp (
            SELECT phppos_sales_items_temp_customers_sub.customer_id,
            CONCAT(phppos_people.first_name, ' ',phppos_people.last_name) as customer,
            (0-COUNT(DISTINCT phppos_sales_items_temp_customers_sub.sale_id)) as count_sales,
            sum(phppos_sales_items_temp_customers_sub.subtotal) as subtotal,
            sum(phppos_sales_items_temp_customers_sub.total) as total,
            sum(phppos_sales_items_temp_customers_sub.tax) as tax,
            sum(phppos_sales_items_temp_customers_sub.profit) as profit,
            sum(phppos_sales_items_temp_customers_sub.cost) as cost
            from phppos_sales_items_temp_customers_sub
            LEFT OUTER JOIN phppos_people ON phppos_sales_items_temp_customers_sub.customer_id = phppos_people.person_id
            WHERE phppos_sales_items_temp_customers_sub.mode = 'return' 
            group by phppos_sales_items_temp_customers_sub.customer_id
            order by phppos_sales_items_temp_customers_sub.customer_id
            )

                            ");


            $query = $this->db->query("
            CREATE TEMPORARY TABLE phppos_temp_results  
            SELECT customer_id,customer, count_sales, subtotal, total, tax,  profit,  cost ,'sales' AS Relationship 
            FROM phppos_sales_temp
            UNION 
            SELECT customer_id,customer, count_sales, subtotal, total, tax,  profit,  cost , 'returns'
            FROM phppos_returns_temp
            ORDER BY customer_id
            
                            ");
            
            $query = $this->db->query("
            SELECT customer_id, customer,
            sum(count_sales) as count_sales,
            sum(subtotal) as subtotal,
            sum(total) as total,
            sum( tax) as tax,
            sum(profit) as profit,
            sum( cost) as cost
            FROM phppos_temp_results 
            group by customer_id
            ORDER BY customer_id
            
                            ");


             return $query->result_array();
            
            
            
            
                

			
              
	}
	public function getData2(array $inputs)
	{
		$subsidaryID = $this->session->userdata('subsidary_id');//change
		
		$this->db->select('CONCAT(first_name, " ",last_name) as customer, sum(quantity_purchased) as quantity_purchased, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit, sum(cost) as cost', false);
		$this->db->from('sales_items_temp');
		$this->db->join('customers', 'customers.person_id = sales_items_temp.customer_id');
		$this->db->join('people', 'customers.person_id = people.person_id');
                $this->db->where('sale_date BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
		$this->db->where("subsidary_id = $subsidaryID and is_finished = 1");//change
		$this->db->group_by('sale_date');
		$this->db->order_by('last_name');

		return $this->db->get();		
	}
	
	public function getSummaryData(array $inputs)
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
        
 
}
?>