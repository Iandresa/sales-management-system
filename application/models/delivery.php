<?php
class Delivery extends Model
{
	function get_all()
	{
            return $this->get_all_unsettled_sale();           
	}
        
	function complete_sale($sale_id)
        {
            $this->db->where('sales.sale_id', $sale_id);
            return $this->db->update('sales', array('is_finished'=>1, 'sale_time'=>date('Y-m-d H:i:s')));
        }
        
        function complete_delivery($sale_id)
        {
            $this->db->where('sales.sale_id', $sale_id);
            return $this->db->update('sales', array('isDone'=>1));
        }
        
        function put_pending($sale_id)
        {
            $this->db->where('sales.sale_id', $sale_id);
            return $this->db->update('sales', array('isDone'=>0));
        }
        
        //REVISAR
        function finish_saleItemsList($sale_id,$items_ids)
        {
            //$this->db->where("sales_items.sale_id = $sale_id and sales_items.is_itemFinished = 0");
            //echo $sale_id;
            foreach($items_ids as $item_id)
            {
                $this->db->where("sale_id = $sale_id and item_id = $item_id and is_itemFinished = 0");
                $this->db->update('sales_items', array('is_itemFinished' => 1));     
            }
            
            /* Si todos los items de la venta estan terminados,
               se pone la venta como realizada, aun no terminada.*/ 
            /*$this->db->from('sales_items'); 
            $this->db->where("sales_items.sale_id = $sale_id and is_itemFinished = 0");
            $query = $this->db->get(); 
            if($query->num_rows()==0)
            {
                $this->db->where('sales.sale_id', $sale_id);
                $this->db->update('sales', array('isDone'=>1));
            }*/
        }
        //-------
        
        function done_sale($sale_id)
        {
            /* Si todos los items de la venta estan terminados,
               se pone la venta como realizada, aun no terminada.*/ 
            $this->db->from('sales_items'); 
            $this->db->where("sales_items.sale_id = $sale_id and is_itemFinished = 0");
            $query = $this->db->get(); 
            if($query->num_rows()==0)
            {
                $this->db->where('sales.sale_id', $sale_id);
                $this->db->update('sales', array('isDone'=>1));
            }
        }
        
        /*NEW*/
        function cancel_saleItem($sale_id,$item_id)
        {
            $this->db->where("sale_id = $sale_id and item_id = $item_id");
            return $this->db->update('sales_items', array('is_canceled'=>1));
            
            /*$total = 0;
            $tax_amount = 0;
            
            $sItem = $this->get_sale_item($sale_id,$item_id);

            $total = $sItem->quantity_purchased*$sItem->item_unit_price; 

            $tax_info = $this->Item_taxes->get_info($item_id);
            foreach($tax_info as $tax)
            {
                $tax_amount+=($sItem->item_unit_price*$sItem->quantity_purchased-$sItem->item_unit_price*$sItem->quantity_purchased*$sItem->discount_percent/100)*(($tax['percent'])/100);
            }
            
            $this->db->where("sale_id = $sale_id and item_id = $item_id and is_itemFinished = 0");
            $this->db->delete('sales_items');     
                        
            $this->db->where("sale_id = $sale_id and item_id = $item_id");
            $this->db->delete('sales_items_taxes');     
 
            /*****************************************************************************/
            /*$payment1 = $this->get_details_from_sale_payments($sale_id);
            
            $end = false;
            $diff = $total + $tax_amount;
            $new_payment = 0;

            foreach($payment1->result() as $p1)
            {
                if(!$end)
                {
                    if($p1->payment_amount > $diff)
                    {
                        $new_payment = $p1->payment_amount - $diff;
                        
                        $sales_payments_data = array
			(
				'sale_id'=>$sale_id,
				'payment_type'=>$p1->payment_type,
				'payment_amount'=>$new_payment
			);
                         
                        $this->db->where("sale_id = $sale_id and payment_type = '$p1->payment_type'");
                        $this->db->update('sales_payments',$sales_payments_data);

                        $end = true;   
                    }
                }     
            }
            
            $payments = array();
            $details_payments = $this->get_details_from_sale_payments($sale_id);
            foreach($details_payments->result() as $p2)
            { 
                $payment = array(($p2->payment_type)=>
                array(
                        'payment_type'=>$p2->payment_type,
                        'payment_amount'=>$p2->payment_amount
                        )
                );

                $payments+=$payment;
            }
            
            $payment_types='';
            foreach($payments as $payment_id=>$payment)
            {
                $payment_types=$payment_types.$payment['payment_type'].': '.to_currency($payment['payment_amount']).' - ';
            }  
            $this->db->where('sales.sale_id', $sale_id);
            $this->db->update('sales', array('payment_type'=>$payment_types));
            /*****************************************************************************/
        }
        
	function get_all_unsettled_sale()
        {
            $subsidaryID = $this->session->userdata('subsidary_id');

                    $query = $this->db->query("
                select phppos_sales_payments.sale_id, phppos_sales_payments.payment_type AS ptype, phppos_sales_payments.payment_amount, 
                phppos_sales.*,
                phppos_people.first_name, phppos_people.last_name, phppos_people.address_1, phppos_people.address_2, phppos_people.city, phppos_people.state, phppos_people.comments as comments_People
                from phppos_sales                        
                join phppos_sales_payments ON phppos_sales.sale_id=phppos_sales_payments.sale_id

                left join phppos_customers ON phppos_customers.person_id = phppos_sales.customer_id
                left join phppos_people ON phppos_customers.person_id = phppos_people.person_id
                where (phppos_sales.state='delivery' or phppos_sales.state='postpone_delivery') and isDone = 0 and phppos_sales.subsidary_id = $subsidaryID
                order by sale_time ");
                    return $query;
        }
        
        function get_deliveries_to_finish()
        {
            $subsidaryID = $this->session->userdata('subsidary_id');

                    $query = $this->db->query("
                select phppos_sales_payments.sale_id, phppos_sales_payments.payment_type AS ptype, phppos_sales_payments.payment_amount, 
                phppos_sales.*,
                phppos_people.first_name, phppos_people.last_name, phppos_people.address_1, phppos_people.address_2, phppos_people.city, phppos_people.state, phppos_people.comments as comments_People
                from phppos_sales                        
                join phppos_sales_payments ON phppos_sales.sale_id=phppos_sales_payments.sale_id

                left join phppos_customers ON phppos_customers.person_id = phppos_sales.customer_id
                left join phppos_people ON phppos_customers.person_id = phppos_people.person_id
                where (phppos_sales.state='delivery' or phppos_sales.state='postpone_delivery') and isDone = 0 and is_finished = 0 and phppos_sales.subsidary_id = $subsidaryID
                order by sale_time ");
                    return $query;
        }
    
        function get_all_unsettled_sale_by_saleID($saleID)
        {
            $subsidaryID = $this->session->userdata('subsidary_id');

                    $query = $this->db->query("
                select phppos_sales_payments.sale_id, phppos_sales_payments.payment_type AS ptype, phppos_sales_payments.payment_amount, 
                phppos_sales.*,
                phppos_people.first_name, phppos_people.last_name, phppos_people.address_1, phppos_people.address_2, phppos_people.city, phppos_people.state, phppos_people.comments as comments_People
                from phppos_sales                        
                join phppos_sales_payments ON phppos_sales.sale_id=phppos_sales_payments.sale_id

                left join phppos_customers ON phppos_customers.person_id = phppos_sales.customer_id
                left join phppos_people ON phppos_customers.person_id = phppos_people.person_id
                where (phppos_sales.state='delivery' or phppos_sales.state='postpone_delivery') and isDone = 0 and phppos_sales.subsidary_id=$subsidaryID AND phppos_sales.sale_id=$saleID
                order by sale_time ");
                    return $query;
        }
    
        function  get_details_from_unsettled_sale($saleID)
        {
            $query = $this->db->query("
                SELECT * FROM `phppos_sales_items` 
                join  phppos_items on phppos_sales_items.item_id = phppos_items.item_id 
                where sale_id = $saleID and is_itemFinished = 0 and is_canceled = 0");
                    return $query;
        }
        
        function  get_sale_items_finished($saleID)
        {
            $query = $this->db->query("
                SELECT * FROM `phppos_sales_items` 
                join  phppos_items on phppos_sales_items.item_id = phppos_items.item_id 
                where sale_id = $saleID and is_itemFinished = 1");
                    return $query;
        }
        
        function  get_details_from_sale_payments($saleID)
        {
            $query = $this->db->query("
                SELECT * FROM `phppos_sales_payments` where sale_id = $saleID");
                    return $query;
        }
        
        function get_payment_type($saleID)
	{
		$query = $this->db->query("
            SELECT payment_type FROM `phppos_sales_payments`  
            where sale_id = $saleID");
		return $query;
	}   
	
	function get_sale_comment($saleID)
	{
		$query = $this->db->query("
            SELECT comment FROM `phppos_sales`  
            where sale_id = $saleID");
		return $query;
	}   
	
	function get_customer($customer_id)
	{
		$this->db->from('customers');	
		$this->db->join('people', 'people.person_id = customers.person_id');
		$this->db->where('customers.person_id',$customer_id);
		return $this->db->get(); 
	}
	
	function get_sale($saleID)
	{		
		$this->db->from('sales');	
		$this->db->where('sale_id',$saleID);
		$query = $this->db->get(); 
		return $query->row();
	}   

        function getSaleState($saleID)
	{   
            if($saleID)
            {		
                $this->db->from('sales');	
                $this->db->where('sale_id',$saleID);
                $query = $this->db->get(); 
                return $query->row()->state;
            }
            else
                return "";
	}  
        
        /*NEW*/
        function getSaleItemsCanceled($saleID)
	{    	
            $this->db->from('sales_items');
            $this->db->join('items', 'items.item_id = sales_items.item_id');
            $this->db->where("sale_id = $saleID and is_canceled = 0");
            $query = $this->db->get();
            
            if($query->num_rows()==0)
                return true;
            return false;
	}  
        
        function get_sale_item($sale_id,$item_id)
	{		
            $this->db->from("sales_items");	
            $this->db->where("sale_id = $sale_id and item_id = $item_id and is_itemFinished = 0");
            $query = $this->db->get();
            return $query->row();
	}  
             
}

?>