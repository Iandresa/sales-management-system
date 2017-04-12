<?php
require_once ("secure_area.php");
require_once ("Enums/state.php");

class Deliveries extends Secure_area
{
	function __construct()
	{
		parent::__construct('deliveries');
		$this->load->library('sale_lib');
		$this->load->helper('url');
                //force_ssl();
	}
	
	function index($success=false)
	{	
		$data['controller_name']= strtolower($this->uri->segment(1));
		$data['lista_clientes'] = $this->Delivery->get_all();
                $data['deliveries_to_finish'] = $this->Delivery->get_deliveries_to_finish();
                $data['margin'] = $this->margin_footer();
                $data['language'] = $this->Subsidary->get_language();
                if($success)
                    $data['success'] = $this->lang->line('sales_successfully');
                
		$this->load->view('delivery/delivery_view',$data);
	}

        function update_div()
	{	
		$data['controller_name']=strtolower($this->uri->segment(1));
		$data['lista_clientes'] = $this->Delivery->get_all();
                $data['language'] = $this->Subsidary->get_language();
		$this->load->view('delivery/refresh_view',$data);
	}
        
        function update_div2()
	{	
		$data['controller_name']=strtolower($this->uri->segment(1));
		$data['deliveries_to_finish'] = $this->Delivery->get_deliveries_to_finish();
		$this->load->view('delivery/refresh_view2',$data);
	}
        
        function get_language()
        {
            $row = $this->Subsidary->get_info($this->session->userdata('subsidary_id'));
            return $row->language;
        }
        
	function get_taxes($sale_id,$cart)
	{
		$customer_id = $this->Delivery->get_sale($sale_id);
		if(!$customer_id->customer_id)
			$customer_id->customer_id = 1;
		$customer = $this->Customer->get_info($customer_id->customer_id);

		//Do not charge sales tax if we have a customer that is not taxable
		if (!$customer->taxable and $customer_id->customer_id!=-1)
		{
		   return array();
		}

		$taxes = array();
		foreach($cart->result() as $line=>$item)
		{
			$tax_info = $this->Item_taxes->get_info($item->item_id);

			foreach($tax_info as $tax)
			{
				$name = $tax['percent'].'% ' . $tax['name'];
				$tax_amount=($item->item_unit_price*$item->quantity_purchased-$item->item_unit_price*$item->quantity_purchased*$item->discount_percent/100)*(($tax['percent'])/100);


				if (!isset($taxes[$name]))
				{
					$taxes[$name] = 0;
				}
				$taxes[$name] += $tax_amount;
			}
		}

		return $taxes;
	}
	
	function get_subtotal($cart)
	{
            $subtotal = 0;
            foreach($cart->result() as $item)
            {
                $subtotal+=($item->item_unit_price*$item->quantity_purchased-$item->item_unit_price*$item->quantity_purchased*$item->discount_percent/100);
            }
            return to_currency_no_money($subtotal);
	}
	
	function get_total($sale_id,$cart)
	{
		$total = 0;
		foreach($cart->result() as $item)
		{
                    $total+=($item->item_unit_price*$item->quantity_purchased-$item->item_unit_price*$item->quantity_purchased*$item->discount_percent/100);
		}

		foreach($this->get_taxes($sale_id,$cart) as $tax)
		{
			$total+=$tax;
		}

		return to_currency_no_money($total);
	}
	
	function view_report()//ECP imprimir 2013-08-12 aplicar
	{				
		$data['controller_name']=strtolower($this->uri->segment(1));
		$data['lista_clientes'] = $this->Delivery->get_all();
                $data['language'] = $this->Subsidary->get_language();
                
                $date = getdate();
                $today = mktime(23, 59, 59, $date['mon'], $date['mday'], $date['year']);
                $data['transaction_time'] = date("d/M/Y h:i:s a", $today); 
                
                $this->load->view("delivery/print",$data);
	}
        
        function view_report2()//ECP imprimir 2013-08-12 aplicar
	{				
		$data['controller_name']=strtolower($this->uri->segment(1));
		$data['deliveries_to_finish'] = $this->Delivery->get_deliveries_to_finish();
                $data['language'] = $this->Subsidary->get_language();
                
                $date = getdate();
                $today = mktime(23, 59, 59, $date['mon'], $date['mday'], $date['year']);
                $data['transaction_time'] = date("d/M/Y h:i:s a", $today); 
                
                $this->load->view("delivery/print2",$data);
	}
        
        /*NEW*/
        function goToPostpone($sale_id)
        {
            $this->Coffee->done_sale($sale_id);
            $this->Sale->change_sale_state($sale_id,State::delivery);
            redirect('deliveries');
        }
        
        function edit_sale($sale_id)
	{
                $this->sale_lib->set_saleId($sale_id);
                
                $items = array();
                
                $items_finished = array();
                
                $data['sale_id'] = $sale_id;
                
                $data['hide_buttons'] = false;
                
                $details_sale = $this->Delivery->get_details_from_unsettled_sale($sale_id);
                
                $sale_items_finished = $this->Delivery->get_sale_items_finished($sale_id); 
                
                $sale = $this->Delivery->get_sale($sale_id);
                $data['sale_date'] = $sale->sale_time;
                //$data['comment'] = $sale->comment;
                
                $this->sale_lib->set_mode($sale->mode);
                
                foreach($details_sale->result() as $item)
                {  
                    //array/cart records are identified by $insertkey and item_id is just another field.
                    $item = array(($item->line)=>
                    array(
                            'item_id'=>$item->item_id,
                            'line'=>$item->line,
                            'name'=>$item->name,
                            'item_number'=>$item->item_number,
                            'description'=>$item->description,
                            'serialnumber'=>$item->serialnumber,
                            'allow_alt_description'=>$item->allow_alt_description,
                            'is_serialized'=>$item->is_serialized,
                            'quantity'=>$item->quantity_purchased,
                            'discount'=>$item->discount_percent,
                            'price'=>$item->unit_price
                            )
                    );

                    //add to existing array
                    $items+=$item;
                }
                
                $index = 0;
                foreach($sale_items_finished->result() as $item)
                {  
                    //array/cart records are identified by $insertkey and item_id is just another field.
                    $item = array(($index++)=>
                    array(
                            'item_id'=>$item->item_id,
                            'line'=>$item->line,
                            'name'=>$item->name,
                            'item_number'=>$item->item_number,
                            'description'=>$item->description,
                            'serialnumber'=>$item->serialnumber,
                            'allow_alt_description'=>$item->allow_alt_description,
                            'is_serialized'=>$item->is_serialized,
                            'quantity'=>$item->quantity_purchased,
                            'discount'=>$item->discount_percent,
                            'price'=>$item->unit_price
                            )
                    );

                    //add to existing array
                    $items_finished+=$item;
                }

		$this->sale_lib->set_cart($items);
                $this->sale_lib->set_temp_cart($items);
                $this->sale_lib->set_finished_cart($items_finished);
                $this->sale_lib->set_temp_finished_cart($items_finished);
		 
                $person_info = $this->Employee->get_logged_in_employee_info();
		$data['cart']=$this->sale_lib->get_cart();
                $data['cart_finished']=$this->sale_lib->get_finished_cart();
		$data['modes']=array('sale'=>$this->lang->line('sales_sale'),'return'=>$this->lang->line('sales_return'));
		$data['mode']=$this->sale_lib->get_mode();
		$data['subtotal']=$this->sale_lib->get_subtotal();
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=$this->sale_lib->get_total();
		$data['items_module_allowed'] = $this->Employee->has_permission('items', $person_info->person_id);
		//Alain Multiple Payments
		$data['payments_total']=$this->sale_lib->get_payments_total();
		$data['amount_due']=$this->sale_lib->get_amount_due();
		
                $payments = array();
                $details_payments= $this->Delivery->get_details_from_sale_payments($sale_id);
		foreach($details_payments->result() as $p)
                { 
                    $payment = array(($p->payment_type)=>
                    array(
                            'payment_type'=>$p->payment_type,
                            'payment_amount'=>$p->payment_amount
                            )
                    );

                    $payments+=$payment;
                }
		$this->sale_lib->set_payments($payments);
                $data['payments']=$this->sale_lib->get_payments();
		
                $data['payment_options']=array(
			$this->lang->line('sales_cash') => $this->lang->line('sales_cash'),
			$this->lang->line('sales_check') => $this->lang->line('sales_check'),
			//$this->lang->line('sales_giftcard') => $this->lang->line('sales_giftcard'),
			$this->lang->line('sales_debit') => $this->lang->line('sales_debit'),
			$this->lang->line('sales_credit') => $this->lang->line('sales_credit'),
                        $this->lang->line('sales_transfer') => $this->lang->line('sales_transfer')
		);

		$customer=$this->Delivery->get_sale($sale_id);
		$customer_id = $customer->customer_id;
                $this->sale_lib->set_customer($customer_id);
		if($customer_id!=-1)
		{
			$info=$this->Customer->get_info($customer_id);
			$data['customer']=$info->first_name.' '.$info->last_name;
		}
                $this->session->set_userdata('customer_last_sale', 'NULO'); //ECP V_2 punto 3.7 2013-08-12 aplicar
                
		$this->load->view("sales/register",$data);
	}
        
        /*function edit_item_sale($sale_id,$item_id)
	{       
                $data['sale_id'] = $sale_id; 
                $data['sale_item_info']=$this->Sale->get_sale_item_info($sale_id,$item_id);
                
                $this->load->view("delivery/form",$data);
	}*/
        
        function finish_item_sale($sale_id)
	{
            $saleItems_to_finish = $this->input->post('selected');

            $this->Delivery->finish_saleItemsList($sale_id,$saleItems_to_finish); 
            
            redirect('deliveries');
        }
        
        /*NEW*/
        function cancel_item_sale($sale_id,$item_id)
	{
            //$saleItems_to_cancel = $this->input->post('selected');

            $this->Delivery->cancel_saleItem($sale_id,$item_id); 
            
            redirect('deliveries');
        }
	
	function complete_delivery($sale_id)
	{					
		$this->Delivery->complete_delivery($sale_id); // funcion que completa la entrega
		//$this->index();
                redirect('deliveries');
	}
        
        function complete_sale($sale_id)
	{					
		$this->Delivery->complete_sale($sale_id); // funcion que completa la venta
		$this->index(true);
	}
	
	function margin_footer()
        {		
                $banners = $this->session->userdata('real_banners_showed');
                $rows = (($banners%2)==0)?($banners/2):($banners/2)+1;
                $margin = ($rows == 0)?(1 * $this->config->item('banner_side_height')):($rows * $this->config->item('banner_side_height'));
                return $margin;
        }
        
        //función que pone la venta en estado pendiente
        function put_pending($sale_id)
	{					
		$this->Delivery->put_pending($sale_id); 
                redirect('deliveries');
	}
}
	
?>