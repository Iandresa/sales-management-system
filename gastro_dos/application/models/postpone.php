<?php
class Postpone extends Model
{
	function get_all()
	{
                $subsidaryID = $this->session->userdata('subsidary_id');
            
                $query = $this->db->query("
                select phppos_sales_payments.sale_id, phppos_sales_payments.payment_type AS ptype, phppos_sales_payments.payment_amount, 
                phppos_sales.*,
                phppos_people.first_name, phppos_people.last_name, phppos_people.comments as comments_People
                from phppos_sales                        
                join phppos_sales_payments ON phppos_sales.sale_id=phppos_sales_payments.sale_id

                left join phppos_customers ON phppos_customers.person_id = phppos_sales.customer_id
                left join phppos_people ON phppos_customers.person_id = phppos_people.person_id
                where (phppos_sales.state='postpone' or phppos_sales.state='postpone_order' or phppos_sales.state='postpone_delivery') and is_finished != 1 and phppos_sales.subsidary_id = $subsidaryID
                order by sale_time ");
                    return $query;          
	}   
        function get_count()
	{
                $subsidaryID = $this->session->userdata('subsidary_id');
                $this->db->where("phppos_sales.state IN ('postpone','postpone_delivery','postpone_order')");
                $this->db->where('is_finished != 1');
                $this->db->where('subsidary_id',$subsidaryID);
                $this->db->from('phppos_sales');
                return $this->db->count_all_results();    
	} 
}
?>
