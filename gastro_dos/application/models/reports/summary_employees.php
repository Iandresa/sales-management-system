<?php
require_once("report.php");
class Summary_employees extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array($this->lang->line('reports_employee'), $this->lang->line('reports_sales2'), $this->lang->line('reports_subtotal'), $this->lang->line('reports_total'), $this->lang->line('reports_tax'), $this->lang->line('reports_profit'), $this->lang->line('reports_cost'));
	}
	
	public function getData(array $inputs)/*ECP*/
	{
          
            
            $subsidaryID = $this->session->userdata('subsidary_id');//change
            $start_date = $inputs['start_date'];
            $end_date = $inputs['end_date'];        
                     
            $this->db->query("         
            
            CREATE TEMPORARY TABLE phppos_sales_items_temp_finished_sales (
                SELECT *
                from phppos_sales_items_temp
                where  phppos_sales_items_temp.is_finished = 1
                and phppos_sales_items_temp.sale_subsidary = $subsidaryID
                and phppos_sales_items_temp.sale_date BETWEEN '$start_date' and '$end_date'

                )
	
                            ");

            


            $query = $this->db->query("
            CREATE TEMPORARY TABLE phppos_temp_union (
            SELECT phppos_employees.person_id as ID_employee,
            phppos_sales_items_temp_finished_sales.*,
            CONCAT(phppos_people.first_name, ' ',phppos_people.last_name) as employee_name
            from phppos_sales_items_temp_finished_sales
            right outer JOIN phppos_employees ON phppos_employees.person_id = phppos_sales_items_temp_finished_sales.employee_id
            INNER JOIN phppos_people ON phppos_employees.person_id = phppos_people.person_id
            WHERE ( (phppos_employees.person_id = phppos_sales_items_temp_finished_sales.employee_id || 
            phppos_people.subsidary_id =$subsidaryID ) && phppos_employees.person_id <> 1) ||
            (phppos_employees.person_id = 1 && phppos_people.subsidary_id =$subsidaryID && phppos_sales_items_temp_finished_sales.employee_id IS NOT NULL)

            )        
                            ");
            
            $query = $this->db->query("
            CREATE TEMPORARY TABLE phppos_sales_temp 
            SELECT phppos_temp_union.ID_employee,
            phppos_temp_union.employee_name as employee,
            COUNT(DISTINCT phppos_temp_union.sale_id)as count_sales,
            sum(phppos_temp_union.subtotal) as subtotal,
            sum(phppos_temp_union.total) as total,
            sum(phppos_temp_union.tax) as tax, 
            sum(phppos_temp_union.profit) as profit,
            sum(phppos_temp_union.cost) as cost
            from phppos_temp_union 	

            where (phppos_temp_union.mode = 'sale'	|| phppos_temp_union.mode IS NULL)			
            group by phppos_temp_union.ID_employee
            order by phppos_temp_union.ID_employee         
                            ");
            
            $query = $this->db->query("
            
            CREATE TEMPORARY TABLE phppos_returns_temp (
            SELECT phppos_temp_union.ID_employee,
            phppos_temp_union.employee_name as employee,
            (0 - COUNT(DISTINCT phppos_temp_union.sale_id)) as count_sales,
            sum(phppos_temp_union.subtotal) as subtotal,
            sum(phppos_temp_union.total) as total,
            sum(phppos_temp_union.tax) as tax, 
            sum(phppos_temp_union.profit) as profit,
            sum(phppos_temp_union.cost) as cost
            from phppos_temp_union 

            where (phppos_temp_union.mode = 'return'	)			
            group by phppos_temp_union.ID_employee
            order by phppos_temp_union.ID_employee		
            )
                            ");

            $query = $this->db->query("
            SELECT *
            from phppos_sales_temp
            union all
            SELECT *
            from phppos_returns_temp
            order by ID_employee

                            ");
            

             return $query->result_array();
	}
	
        public function getGraph_Data(array $inputs)/*ECP*/
	{
       
            
            $subsidaryID = $this->session->userdata('subsidary_id');//change
            $start_date = $inputs['start_date'];
            $end_date = $inputs['end_date'];        
                     
            $this->db->query("           
            
            CREATE TEMPORARY TABLE phppos_sales_items_temp_finished_sales (
            SELECT *
            from phppos_sales_items_temp
            where  phppos_sales_items_temp.is_finished = 1
            and phppos_sales_items_temp.sale_subsidary = $subsidaryID
            and phppos_sales_items_temp.sale_date BETWEEN '$start_date' and '$end_date'
            )	
                            ");

            


            $query = $this->db->query("
            CREATE TEMPORARY TABLE phppos_temp_union (
            SELECT phppos_employees.person_id as ID_employee,
            phppos_sales_items_temp_finished_sales.*,
            CONCAT(phppos_people.first_name, ' ',phppos_people.last_name) as employee_name
            from phppos_sales_items_temp_finished_sales
            right outer JOIN phppos_employees ON phppos_employees.person_id = phppos_sales_items_temp_finished_sales.employee_id
            INNER JOIN phppos_people ON phppos_employees.person_id = phppos_people.person_id
            WHERE ( (phppos_employees.person_id = phppos_sales_items_temp_finished_sales.employee_id || 
            phppos_people.subsidary_id =$subsidaryID ) && phppos_employees.person_id <> 1) ||
            (phppos_employees.person_id = 1 && phppos_people.subsidary_id =$subsidaryID && phppos_sales_items_temp_finished_sales.employee_id IS NOT NULL)

            )        
                            ");
            
            $query = $this->db->query("
            CREATE TEMPORARY TABLE phppos_sales_temp 
            SELECT phppos_temp_union.ID_employee,
            phppos_temp_union.employee_name as employee,
            COUNT(DISTINCT phppos_temp_union.sale_id)as count_sales,
            sum(phppos_temp_union.subtotal) as subtotal,
            sum(phppos_temp_union.total) as total,
            sum(phppos_temp_union.tax) as tax, 
            sum(phppos_temp_union.profit) as profit,
            sum(phppos_temp_union.cost) as cost
            from phppos_temp_union 	

            where (phppos_temp_union.mode = 'sale'	|| phppos_temp_union.mode IS NULL)			
            group by phppos_temp_union.ID_employee
            order by phppos_temp_union.ID_employee	          
                            ");
            
            $query = $this->db->query("
            
            CREATE TEMPORARY TABLE phppos_returns_temp (
            SELECT phppos_temp_union.ID_employee,
            phppos_temp_union.employee_name as employee,
            (0 - COUNT(DISTINCT phppos_temp_union.sale_id)) as count_sales,
            sum(phppos_temp_union.subtotal) as subtotal,
            sum(phppos_temp_union.total) as total,
            sum(phppos_temp_union.tax) as tax, 
            sum(phppos_temp_union.profit) as profit,
            sum(phppos_temp_union.cost) as cost
            from phppos_temp_union  

            where (phppos_temp_union.mode = 'return'	)			
            group by phppos_temp_union.ID_employee
            order by phppos_temp_union.ID_employee		
            )          
                            ");

            $query = $this->db->query("
            CREATE TEMPORARY TABLE phppos_temp_results  
            SELECT ID_employee,employee, count_sales, subtotal, total, tax,  profit,  cost ,'sales' AS Relationship 
            FROM phppos_sales_temp
            UNION 
            SELECT ID_employee,employee, count_sales, subtotal, total, tax,  profit,  cost , 'returns'
            FROM phppos_returns_temp
            ORDER BY ID_employee           
                            ");
            $query = $this->db->query("
            SELECT ID_employee, employee,
            sum(count_sales) as count_sales,
            sum(subtotal) as subtotal,
            sum(total) as total,
            sum( tax) as tax,
            sum(profit) as profit,
            sum( cost) as cost
            FROM phppos_temp_results 
            group by ID_employee
            ORDER BY ID_employee               

                            ");


             return $query->result_array();
	}
	
        
	public function getData2(array $inputs)
	{
		$subsidaryID = $this->session->userdata('subsidary_id');//change
		
		$this->db->select('CONCAT(first_name, " ",last_name) as employee, sum(quantity_purchased) as quantity_purchased, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit, sum(cost) as cost', false);
		$this->db->from('sales_items_temp');
		$this->db->join('employees', 'employees.person_id = sales_items_temp.employee_id');
		$this->db->join('people', 'employees.person_id = people.person_id');
                $this->db->where('sale_date BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
		$this->db->where("subsidary_id = $subsidaryID and is_finished = 1");//change
		$this->db->group_by('employee_id');
		$this->db->order_by('last_name');

		return $this->db->get();		
	}
	
	public function getSummaryData(array $inputs)/*ECP*/
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