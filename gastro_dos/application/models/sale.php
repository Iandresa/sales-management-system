<?php
class Sale extends Model
{
	public function get_info($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id',$sale_id);
		return $this->db->get();
	}

	function exists($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id',$sale_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}
        
        /*NEW*/
        function sale_Item_Exists($sale_id,$item_id)
	{
		$this->db->from('sales_items');
		$this->db->where("sale_id = $sale_id and item_id = $item_id and is_itemFinished = 0");
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}
        
        function update_sales_items($sale_id,$item_id,$line,$sales_items_data)
        {
            $this->db->where("sale_id = $sale_id and item_id = $item_id and line = $line and is_itemFinished = 0");
            $this->db->update('sales_items',$sales_items_data);
        }
        
        function saleItemTaxes_Item_Exists($sale_id,$item_id)
	{
		$this->db->from('sales_items_taxes');
		$this->db->where("sale_id = $sale_id and item_id = $item_id");
		$query = $this->db->get();

		return ($query->num_rows()==2);
	}
        /*****/
        
	function save($items,$customer_id,$employee_id,/*$comment,*/$payments,$state,$sale_id=false,$postponeName=null)
	{	    
                $subsidaryID = $this->session->userdata('subsidary_id');//change

		if(count($items)==0)
			return -1;

		//Alain Multiple payments
		//Build payment types string
		$payment_types='';
		foreach($payments as $payment_id=>$payment)
		{
			$payment_types=$payment_types.$payment['payment_type'].': '.to_currency($payment['payment_amount']).' - ';
		}               
     
                $mode = $this->sale_lib->get_mode();
		$dispatch_date = $this->sale_lib->get_dateFinish();
                
                //2013-07-24
                $subsidary_cycle = $this->Subsidary->get_subsidary_cycle($subsidaryID);
                $year = date('Y') - 2000;
                $billet_number = 0;
                if($subsidary_cycle)
                    $billet_number = $subsidary_cycle->count_cycles.$year.$subsidary_cycle->count_sales;
                /*****/
                
                $sales_data = array(
			'sale_time' => date('Y-m-d H:i:s'),
			'customer_id'=> $this->Customer->exists($customer_id) ? $customer_id : null,
			'employee_id'=>$employee_id,
			'payment_type'=>$payment_types,
			//'comment'=>$comment,
                        'isDone'=>($state=="regular")?1:0,
                        'state'=>$state,
                        'subsidary_id'=>$subsidaryID,
                        'mode'=>$mode
		);
                
                if($state=="regular")
                {
                    $sales_data['billet_number'] = $billet_number;
                    
                    //2013-07-24
                    if($subsidary_cycle)
                    {
                        $data_cycle = array(
                            'count_sales'=>($subsidary_cycle->count_sales + 1)
                        );

                        $this->db->where('subsidary_id', $subsidaryID);
                        $this->db->update('subsidaries_cycles',$data_cycle);
                    }
                    /*****/

                    $sales_data['is_finished'] = 1;
                }
                
                if($state!="regular" && $state!="postpone")
                   $sales_data['sale_timeToFinish'] = $dispatch_date; 

		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->db->insert('sales',$sales_data);
		$sale_id = $this->db->insert_id();

		foreach($payments as $payment_id=>$payment)
		{
			if ( substr( $payment['payment_type'], 0, strlen( $this->lang->line('sales_giftcard') ) ) == $this->lang->line('sales_giftcard') )
			{
				/* We have a gift card and we have to deduct the used value from the total value of the card. */
				$splitpayment = explode( ':', $payment['payment_type'] );
				$cur_giftcard_value = $this->getGiftcardValue( $splitpayment[1] );
				$this->Giftcard->update( $splitpayment[1], $cur_giftcard_value - $payment['payment_amount'] );
			}

			$sales_payments_data = array
			(
				'sale_id'=>$sale_id,
				'payment_type'=>$payment['payment_type'],
				'payment_amount'=>$payment['payment_amount']
			);
			$this->db->insert('sales_payments',$sales_payments_data);
		}

		foreach($items as $line=>$item)
		{
			$cur_item_info = $this->Item->get_info($item['item_id']);

			$sales_items_data = array
			(
				'sale_id'=>$sale_id,
				'item_id'=>$item['item_id'],
				'line'=>$item['line'],
				'description'=>$item['description'],
				'serialnumber'=>$item['serialnumber'],
				'quantity_purchased'=>$item['quantity'],
				'discount_percent'=>$item['discount'],
				'item_cost_price' => $cur_item_info->cost_price,
				'item_unit_price'=>$item['price']
			);

			$this->db->insert('sales_items',$sales_items_data);

			//Update stock quantity
			$item_data = array('quantity'=>$cur_item_info->quantity - $item['quantity']);
			$this->Item->save($item_data,$item['item_id']);

			//Ramel Inventory Tracking
			//Inventory Count Details
			$qty_buy = -$item['quantity'];
			$sale_remarks ='POS '.$sale_id;
			$inv_data = array
			(
				'trans_date'=>date('Y-m-d H:i:s'),
				'trans_items'=>$item['item_id'],
				'trans_user'=>$employee_id,
				'trans_comment'=>$sale_remarks,
				'trans_inventory'=>$qty_buy
			);
			$this->Inventory->insert($inv_data);
			//------------------------------------Ramel
                        
                        //Update stock quantity other items (HL 2013-04-12)
                        $items_for_noSale = $this->Item->get_items_for_noSale($item['item_id']);
                        foreach($items_for_noSale->result() as $item_for_noSale)
                        {
                            $item_for_noSale_data = array('quantity'=>$item_for_noSale->quantity - ($item_for_noSale->quantity_to_use*$item['quantity']));
                            $this->Item->save($item_for_noSale_data,$item_for_noSale->item_id);
                        
                            //Inventory Count Details
                            $qty_buy2 = -($item_for_noSale->quantity_to_use*$item['quantity']);
                            $sale_remarks2 ='POS '.$sale_id;
                            $inv_data2 = array
                            (
                                    'trans_date'=>date('Y-m-d H:i:s'),
                                    'trans_items'=>$item_for_noSale->item_id,
                                    'trans_user'=>$employee_id,
                                    'trans_comment'=>$sale_remarks2,
                                    'trans_inventory'=>$qty_buy2
                            );
                            $this->Inventory->insert($inv_data2);
                        }
                        /////////////////////////////////////////////////////
			$customer = $this->Customer->get_info($customer_id);
 			if ($customer_id == -1 or $customer->taxable)
 			{
				foreach($this->Item_taxes->get_info($item['item_id']) as $row)
				{
					$this->db->insert('sales_items_taxes', array(
						'sale_id' 	=>$sale_id,
						'item_id' 	=>$item['item_id'],
						'line'      =>$item['line'],
						'name'		=>$row['name'],
						'percent' 	=>$row['percent']
					));
				}
			}
		}
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			return -1;
		}
		
		return $sale_id;
	}
        
    /*NEW*/
    function update($date,$items,$customer_id,$employee_id,/*$comment,*/$payments,$state,$sale_id=false)
	{	    
                $subsidaryID = $this->session->userdata('subsidary_id');
                
                $items_finished = $this->sale_lib->get_finished_cart();
                
		if(count($items)==0 && count($items_finished)==0)//revisar
			return -1;

		//Alain Multiple payments
		//Build payment types string
		$payment_types='';
		foreach($payments as $payment_id=>$payment)
		{
			$payment_types=$payment_types.$payment['payment_type'].': '.to_currency($payment['payment_amount']).' - ';
		}               
                
                $mode = $this->sale_lib->get_mode();
                $dispatch_date = $this->sale_lib->get_dateFinish();
                
                //2013-07-24
                $subsidary_cycle = $this->Subsidary->get_subsidary_cycle($subsidaryID);
                $year = date('Y') - 2000;
                $billet_number = 0;
                if($subsidary_cycle)
                    $billet_number = $subsidary_cycle->count_cycles.$year.$subsidary_cycle->count_sales;
                /*****/
                
		$sales_data = array(
			'sale_time' => date('Y-m-d H:i:s'),
			'customer_id'=> $this->Customer->exists($customer_id) ? $customer_id : null,
			'employee_id'=>$employee_id,
			'payment_type'=>$payment_types,
			//'comment'=>$comment,
                        'subsidary_id'=>$subsidaryID,
                        'mode'=>$mode
		);

                if($sale_id && ($state=="regular"))
                {
                    $sales_data['billet_number'] = $billet_number;
                    
                    //2013-07-24
                    if($subsidary_cycle)
                    {
                        $data_cycle = array(
                            'count_sales'=>($subsidary_cycle->count_sales + 1)
                        );

                        $this->db->where('subsidary_id', $subsidaryID);
                        $this->db->update('subsidaries_cycles',$data_cycle);
                    }
                    /*****/
            
                    $sales_data['is_finished'] = 1;
                }
                
                if($sale_id && ($state!="regular") && $state!="postpone")
                   $sales_data['sale_timeToFinish'] = $dispatch_date;
                
                if(count($items)>0)
                   $sales_data['isDone'] = 0; 
                
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
                
                $this->db->where('sales.sale_id', $sale_id);
		$this->db->update('sales',$sales_data);

                $this->db->where('sales_payments.sale_id', $sale_id);
                $this->db->delete('sales_payments');
		foreach($payments as $payment_id=>$payment)
		{
			if ( substr( $payment['payment_type'], 0, strlen( $this->lang->line('sales_giftcard') ) ) == $this->lang->line('sales_giftcard') )
			{
				/* We have a gift card and we have to deduct the used value from the total value of the card. */
				$splitpayment = explode( ':', $payment['payment_type'] );
				$cur_giftcard_value = $this->getGiftcardValue( $splitpayment[1] );
				$this->Giftcard->update( $splitpayment[1], $cur_giftcard_value - $payment['payment_amount'] );
			}

			$sales_payments_data = array
			(
				'sale_id'=>$sale_id,
				'payment_type'=>$payment['payment_type'],
				'payment_amount'=>$payment['payment_amount']
			);
                        
                        $this->db->insert('sales_payments',$sales_payments_data);
		}

                $items_temp = $this->sale_lib->get_cart_temp();
                foreach($items_temp as $line=>$item)
		{
			$cur_item_info = $this->Item->get_info($item['item_id']);

			//Update stock quantity
			$item_data = array('quantity'=>$cur_item_info->quantity + $item['quantity']);
			$this->Item->save($item_data,$item['item_id']);
		}
		
                $trans_comment ='POS '.$sale_id;
                $this->db->where('inventory.trans_comment', $trans_comment);
                $this->db->delete('inventory');
                        
                $this->db->where('sales_items.sale_id', $sale_id);
		$this->db->delete('sales_items');
                
                $this->db->where('sales_items_taxes.sale_id', $sale_id);
		$this->db->delete('sales_items_taxes');
                
                foreach($items as $line=>$item)
		{
			$cur_item_info = $this->Item->get_info($item['item_id']);

			$sales_items_data = array
			(
				'sale_id'=>$sale_id,
				'item_id'=>$item['item_id'],
				'line'=>$item['line'],
				'description'=>$item['description'],
				'serialnumber'=>$item['serialnumber'],
				'quantity_purchased'=>$item['quantity'],
				'discount_percent'=>$item['discount'],
				'item_cost_price' => $cur_item_info->cost_price,
				'item_unit_price'=>$item['price']
			);

                        /*if($this->sale_Item_Exists($sale_id,$item['item_id']))
                            $this->update_sales_items($sale_id,$item['item_id'],$item['line'],$sales_items_data);
                        else*/
                            $this->db->insert('sales_items',$sales_items_data);

			//Update stock quantity
			$item_data = array('quantity'=>$cur_item_info->quantity - $item['quantity']);
			$this->Item->save($item_data,$item['item_id']);

			//Ramel Inventory Tracking
			//Inventory Count Details
                        $qty_buy = -$item['quantity'];
                        
                        /*$items_finished = $this->sale_lib->get_finished_cart();
                        foreach($items_finished as $line=>$item_Finished)
                        {
                                if($item_Finished['item_id'] == $item['item_id'])
                                {
                                    $qty_buy += $item_Finished['quantity_purchased'];
                                    break;
                                }      
                        }*/
			
			$sale_remarks ='POS '.$sale_id;
                        
			$inv_data = array
			(
				'trans_date'=>date('Y-m-d H:i:s'),
				'trans_items'=>$item['item_id'],
				'trans_user'=>$employee_id,
				'trans_comment'=>$sale_remarks,
				'trans_inventory'=>$qty_buy
			);
                        /*if($this->Inventory->inventory_Item_Exists($sale_remarks,$item['item_id']))
                            $this->Inventory->update_inventory($sale_remarks,$item['item_id'],$inv_data);
                        else*/
                            $this->db->insert('inventory',$inv_data);
			//------------------------------------Ramel*/
                
                        //Update stock quantity other items (HL 2013-04-14)
                        $items_for_noSale = $this->Item->get_items_for_noSale($item['item_id']);
                        foreach($items_for_noSale->result() as $item_for_noSale)
                        {
                            $item_for_noSale_data = array('quantity'=>$item_for_noSale->quantity - ($item_for_noSale->quantity_to_use*$item['quantity']));
                            $this->Item->save($item_for_noSale_data,$item_for_noSale->item_id);
                        
                            //Inventory Count Details
                            $qty_buy2 = -($item_for_noSale->quantity_to_use*$item['quantity']);
                            $sale_remarks2 ='POS '.$sale_id;
                            $inv_data2 = array
                            (
                                    'trans_date'=>date('Y-m-d H:i:s'),
                                    'trans_items'=>$item_for_noSale->item_id,
                                    'trans_user'=>$employee_id,
                                    'trans_comment'=>$sale_remarks2,
                                    'trans_inventory'=>$qty_buy2
                            );
                            $this->Inventory->insert($inv_data2);
                        }
                        //////////////////////////////////////////////////////
			$customer = $this->Customer->get_info($customer_id);
 			if ($customer_id == -1 or $customer->taxable)
 			{
                            if(!$this->saleItemTaxes_Item_Exists($sale_id,$item['item_id']))
                            {
				foreach($this->Item_taxes->get_info($item['item_id']) as $row)
				{
                                        $this->db->insert('sales_items_taxes', array(
                                                'sale_id' 	=>$sale_id,
                                                'item_id' 	=>$item['item_id'],
                                                'line'          =>$item['line'],
                                                'name'		=>$row['name'],
                                                'percent' 	=>$row['percent']
                                        ));
				}
                            }
			}
		}
                
                //------------------------------------------------------------------------------//
                
                $items_temp_finished = $this->sale_lib->get_temp_finished_cart();
                foreach($items_temp_finished as $line=>$item)
		{
			$cur_item_info = $this->Item->get_info($item['item_id']);

			//Update stock quantity
			$item_data = array('quantity'=>$cur_item_info->quantity + $item['quantity']);
			$this->Item->save($item_data,$item['item_id']);
		}
                
                foreach($items_finished as $line=>$item)
		{
			$cur_item_info = $this->Item->get_info($item['item_id']);

			$sales_items_data = array
			(
				'sale_id'=>$sale_id,
				'item_id'=>$item['item_id'],
				'line'=>$item['line'],
				'description'=>$item['description'],
				'serialnumber'=>$item['serialnumber'],
				'quantity_purchased'=>$item['quantity'],
				'discount_percent'=>$item['discount'],
				'item_cost_price' => $cur_item_info->cost_price,
				'item_unit_price'=>$item['price'],
                                'is_itemFinished'=>1
			);

                        $this->db->insert('sales_items',$sales_items_data);

			//Update stock quantity
			$item_data = array('quantity'=>$cur_item_info->quantity - $item['quantity']);
			$this->Item->save($item_data,$item['item_id']);

			//Ramel Inventory Tracking
			//Inventory Count Details
                        $qty_buy = -$item['quantity'];
                        
                        foreach($items as $line=>$item_temp)
                        {
                                if($item_temp['item_id'] == $item['item_id'])
                                {
                                    $qty_buy += $item_temp['quantity'];
                                    break;
                                }      
                        }
			
			$sale_remarks ='POS '.$sale_id;
                        
			$inv_data = array
			(
				'trans_date'=>date('Y-m-d H:i:s'),
				'trans_items'=>$item['item_id'],
				'trans_user'=>$employee_id,
				'trans_comment'=>$sale_remarks,
				'trans_inventory'=>$qty_buy
			);
                        if($this->Inventory->inventory_Item_Exists($sale_remarks,$item['item_id']))
                            $this->Inventory->update_inventory($sale_remarks,$item['item_id'],$inv_data);
                        else
                            $this->db->insert('inventory',$inv_data);
			//------------------------------------Ramel*/
                
			$customer = $this->Customer->get_info($customer_id);
 			if ($customer_id == -1 or $customer->taxable)
 			{
                            if(!$this->saleItemTaxes_Item_Exists($sale_id,$item['item_id']))
                            {
				foreach($this->Item_taxes->get_info($item['item_id']) as $row)
				{
                                        $this->db->insert('sales_items_taxes', array(
                                                'sale_id' 	=>$sale_id,
                                                'item_id' 	=>$item['item_id'],
                                                'line'          =>$item['line'],
                                                'name'		=>$row['name'],
                                                'percent' 	=>$row['percent']
                                        ));
				}
                            }
			}
		}
                
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === FALSE)
		{
			return -1;
		}
		
		return $sale_id;
	}
        
        //NEW
        function delete_sale($sale_id)
        {
            $this->db->trans_start();
            
            $items_temp = $this->sale_lib->get_cart_temp();
            foreach($items_temp as $line=>$item)
            {
                    $cur_item_info = $this->Item->get_info($item['item_id']);

                    //Update stock quantity
                    $item_data = array('quantity'=>$cur_item_info->quantity + $item['quantity']);
                    $this->Item->save($item_data,$item['item_id']);
                    
                    //Update stock quantity other items (HL 2013-04-14)
                    $items_for_noSale = $this->Item->get_items_for_noSale($item['item_id']);
                    foreach($items_for_noSale->result() as $item_for_noSale)
                    {
                        $index = 0;
                        while($index != $item['quantity'])
                        {    
                            $item_for_noSale_data = array('quantity'=>$item_for_noSale->quantity + $item_for_noSale->quantity_to_use);
                            $this->Item->save($item_for_noSale_data,$item_for_noSale->item_id);

                            $index++;
                        }
                    }
            }
            
            $items_finished = $this->sale_lib->get_finished_cart();
            foreach($items_finished as $line=>$item)
            {
                    $cur_item_info = $this->Item->get_info($item['item_id']);

                    //Update stock quantity
                    $item_data = array('quantity'=>$cur_item_info->quantity + $item['quantity']);
                    $this->Item->save($item_data,$item['item_id']);
                    
                    //Update stock quantity other items (HL 2013-04-14)
                    $items_for_noSale = $this->Item->get_items_for_noSale($item['item_id']);
                    foreach($items_for_noSale->result() as $item_for_noSale)
                    {
                        $index = 0;
                        while($index != $item['quantity'])
                        {    
                            $item_for_noSale_data = array('quantity'=>$item_for_noSale->quantity + $item_for_noSale->quantity_to_use);
                            $this->Item->save($item_for_noSale_data,$item_for_noSale->item_id);

                            $index++;
                        }
                    }
            }
            
            $this->db->where('sales.sale_id', $sale_id);
            $this->db->delete('sales');
            
            $trans_comment ='POS '.$sale_id;
            $this->db->where('inventory.trans_comment', $trans_comment);
            $this->db->delete('inventory');
                
            $this->db->where('sales_items.sale_id', $sale_id);
            $this->db->delete('sales_items');

            $this->db->where('sales_items_taxes.sale_id', $sale_id);
            $this->db->delete('sales_items_taxes');
            
            $this->db->where('sales_payment.sale_id', $sale_id);
            $this->db->delete('sales_payments');
            
            $this->db->trans_complete();
        }
        
	function get_sale_items($sale_id)
	{
		$this->db->from('sales_items');
		$this->db->where('sale_id',$sale_id);
		return $this->db->get();
	}
        
        function get_customer_last_sale($customer_id) //ECP V_2 punto 3.7 :para obtener las ventas de un cliente dado
	{
                $this->db->select('*');
		$this->db->from('sales');
		$this->db->where('customer_id',$customer_id);
                $this->db->order_by("sale_time", "asc");
		$sales = $this->db->get();
                $sale_id = -1;
                foreach($sales->result() as $row)
		{
			$sale_id=$row->sale_id;		
		}
                return $sale_id;
            
            

        }

	function get_sale_payments($sale_id)
	{
		$this->db->from('sales_payments');
		$this->db->where('sale_id',$sale_id);
		return $this->db->get();
	}

	function get_customer($sale_id)
	{
		$this->db->from('sales');
		$this->db->where('sale_id',$sale_id);
		return $this->Customer->get_info($this->db->get()->row()->customer_id);
	}
        
	//We create a temp table that allows us to do easy report/sales queries
	public function create_sales_items_temp_table()
	{//ECP LEFT OUTER JOIN para incluir los clientes NULL (ninguno)
		$this->db->query("CREATE TEMPORARY TABLE ".$this->db->dbprefix('sales_items_temp')."
		(SELECT date(sale_time) as sale_date,
                ".$this->db->dbprefix('sales_items').".sale_id,
                    comment,
                    payment_type,
                    customer_id,
                    employee_id, 
		".$this->db->dbprefix('items').".item_id,
                    supplier_id,
                    quantity_purchased,
                    item_cost_price,
                    item_unit_price,
                    SUM(percent) as item_tax_percent,
		discount_percent,
                (item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) as subtotal,
		".$this->db->dbprefix('sales_items').".line as line, serialnumber,
                    ".$this->db->dbprefix('sales_items').".description as description,
		ROUND((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(1+(SUM(percent)/100)),2) as total,
		ROUND((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100)*(SUM(percent)/100),2) as tax,
		(item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100) - (item_cost_price*quantity_purchased) as profit,
                (item_cost_price*quantity_purchased) as cost,isDone,is_finished, ".$this->db->dbprefix('sales').".mode,
		phppos_sales.subsidary_id as sale_subsidary		
                FROM ".$this->db->dbprefix('sales_items')."
		INNER JOIN ".$this->db->dbprefix('sales')." ON  ".$this->db->dbprefix('sales_items').'.sale_id='.$this->db->dbprefix('sales').'.sale_id'." 
		INNER JOIN ".$this->db->dbprefix('items')." ON  ".$this->db->dbprefix('sales_items').'.item_id='.$this->db->dbprefix('items').'.item_id'."
		LEFT OUTER JOIN ".$this->db->dbprefix('suppliers')." ON  ".$this->db->dbprefix('items').'.supplier_id='.$this->db->dbprefix('suppliers').'.person_id'."
		LEFT OUTER JOIN ".$this->db->dbprefix('sales_items_taxes')." ON  "
		.$this->db->dbprefix('sales_items').'.sale_id='.$this->db->dbprefix('sales_items_taxes').'.sale_id'." and "
		.$this->db->dbprefix('sales_items').'.item_id='.$this->db->dbprefix('sales_items_taxes').'.item_id'." and "
		.$this->db->dbprefix('sales_items').'.line='.$this->db->dbprefix('sales_items_taxes').'.line'."
		GROUP BY sale_id, item_id, line)");

		//Update null item_tax_percents to be 0 instead of null
		$this->db->where('item_tax_percent IS NULL');
		$this->db->update('sales_items_temp', array('item_tax_percent' => 0));

		//Update null tax to be 0 instead of null
		$this->db->where('tax IS NULL');
		$this->db->update('sales_items_temp', array('tax' => 0));

		//Update null subtotals to be equal to the total as these don't have tax
		$this->db->query('UPDATE '.$this->db->dbprefix('sales_items_temp'). ' SET total=subtotal WHERE total IS NULL');
	
                
        }
	
	public function getGiftcardValue( $giftcardNumber )
	{
            if ( !$this->Giftcard->exists( $this->Giftcard->get_giftcard_id($giftcardNumber)))
                    return 0;

            $this->db->from('giftcards');
            $this->db->where('giftcard_number',$giftcardNumber);
            return $this->db->get()->row()->value;
	}
        
        /*
	Gets information about a particular sale item
	*/
	function get_sale_item_info($sale_id,$item_id)
	{
            $this->db->from('sales_items');
            $this->db->where("sale_id = $sale_id and item_id = $item_id");

            $query = $this->db->get();

            if($query->num_rows()==1)
            {
                return $query->row();
            }
            else
            {
                //Get empty base parent object, as $item_id is NOT an item
                $item_obj=new stdClass();

                //Get all the fields from items table
                $fields = $this->db->list_fields('sales_items');

                foreach ($fields as $field)
                {
                        $item_obj->$field='';
                }

                return $item_obj;
            }
	}
        
        function get_sales_per_subsidary($subsidary_id, $mode = null)
        {
            if(isset($subsidary_id))
                $this->db->where("subsidary_id", $subsidary_id);
            if(isset($mode))
                $this->db->where("mode", $mode);
            
            $query  = $this->db->get("sales");
            return $query;
        }
        
        function get_sales_finished_per_subsidary($subsidary_id, $mode = null)
        {
           if(isset($subsidary_id))
                $this->db->where("subsidary_id", $subsidary_id);
            if(isset($mode))
                $this->db->where("mode", $mode);
            
            $this->db->where("is_finished", 1);
            
            $query  = $this->db->get("sales");
            return $query;
        }

        //HL (2014-01-15)
        function get_dailyCash_finished_per_subsidary($subsidary_id)
        {
            if(isset($subsidary_id))
                $this->db->where("subsidary_id", $subsidary_id);

            $this->db->where("is_completed", 1);

            $query = $this->db->get("daily_cash");
            return $query;
        }

        //HL (2014-01-15)
        function get_cycleCash_finished_per_subsidary($subsidary_id)
        {
            if(isset($subsidary_id))
                $this->db->where("subsidary_id", $subsidary_id);

            //$this->db->where("is_completed", 1);

            $query  = $this->db->get("cycle_cash");
            return $query;
        }

        //HL 2013-08-07
        function get_sales_finished_per_bill($subsidary_id, $sale_id)
        {
            if(isset($subsidary_id))
            {
                $this->db->where("subsidary_id", $subsidary_id);
            }

            $this->db->where("sale_id", $sale_id);

            $this->db->where("is_finished", 1);

            $query  = $this->db->get("sales");
            return $query;
        }

        //HL 2013-07-25
        function get_sales_finished_per_subsidary_cycle($subsidary_id, $mode = null)
        {
            if(isset($subsidary_id))
            {
                //$sub_cycle = $this->Subsidary->get_subsidary_cycle($subsidary_id);
                $this->db->where("subsidary_id", $subsidary_id);
            }
            //strncmp($str1, $str2, 2);
            /*if(isset($mode))
                $this->db->where("mode", $mode);*/
            
            $this->db->where("is_finished", 1);
            $this->db->where("id_dailyCash",0);
            $this->db->order_by("sale_time","asc");
            
            $query  = $this->db->get("sales");
            return $query;
        }

        //HL (2014-01-15)
        function get_sales_finished_per_daily_cash($dailyCash_id,$subsidary_id)
        {
            if(isset($subsidary_id))
                $this->db->where("subsidary_id",$subsidary_id);
            if(isset($dailyCash_id))
                $this->db->where("id_dailyCash",$dailyCash_id);

            $this->db->where("id_dailyCash",$dailyCash_id);
            $this->db->order_by("sale_time","asc");

            $query  = $this->db->get("sales");
            return $query;
        }
        
        //HL (2014-01-16)
        /*
	Gets information about a particular daily cash
	*/
	function get_dailyCash_info($dailyCash_id)
	{
            $this->db->from('daily_cash');
            $this->db->where("id_dailyCash",$dailyCash_id);

            $query = $this->db->get();

            if($query->num_rows()==1)
            {
                return $query->row();
            }
            else
            {
                //Get empty base parent object, as $item_id is NOT an item
                $item_obj=new stdClass();

                //Get all the fields from items table
                $fields = $this->db->list_fields('daily_cash');

                foreach ($fields as $field)
                {
                        $item_obj->$field='';
                }

                return $item_obj;
            }
	}
        
        //HL (2014-01-16)
        /*
	Gets information about a particular cycle cash
	*/
	function get_cycleCash_info($cycleCash_id)
	{
            $this->db->from('cycle_cash');
            $this->db->where("id_cycleCash",$cycleCash_id);

            $query = $this->db->get();

            if($query->num_rows()==1)
            {
                return $query->row();
            }
            else
            {
                //Get empty base parent object, as $item_id is NOT an item
                $item_obj=new stdClass();

                //Get all the fields from items table
                $fields = $this->db->list_fields('cycle_cash');

                foreach ($fields as $field)
                {
                        $item_obj->$field='';
                }

                return $item_obj;
            }
	}
        
        //NEW
        function change_sale_state($id,$state)
        {
            $sales_data = array();
            if($state==State::order)
                $sales_data['state']="postpone_order";   
            else if($state==State::postpone)
                $sales_data['state']="postpone";
            else        
                $sales_data['state']="postpone_delivery";
            $this->db->where('sales.sale_id', $id);
            $this->db->update('sales',$sales_data);
        }
        
        //NEW (HL 2013-04-13)
        function item_for_noSale_low_stock($item_id)
        {
            $this->db->from('items');
            $this->db->where("is_forSale = 0 and owner_item_id = $item_id");

            $query = $this->db->get();
            
            foreach($query->result() as $item_for_noSale)
            {
                if($item_for_noSale->quantity_to_use > $item_for_noSale->quantity)
                    return true;
            }
            
            return false;
        }
        
        //NEW (HL 2013-04-13)
        function item_for_noSale_low_stock2($item_id,$quantity)
        {
            $this->db->from('items');
            $this->db->where("is_forSale = 0 and owner_item_id = $item_id");

            $query = $this->db->get();
            
            foreach($query->result() as $item_for_noSale)
            {
                if(($item_for_noSale->quantity_to_use*$quantity) > $item_for_noSale->quantity)
                    return true;
            }
            
            return false;
        }
        
        //(2013-07-23)
        function save_dailyCash($employee_id,$total,$sales)
	{	    
            $subsidaryID = $this->session->userdata('subsidary_id');
            
            $sub_cycle = $this->Subsidary->get_subsidary_cycle($subsidaryID);

            $last = $this->get_last_dailyCash($subsidaryID);
            if($last)
                $this->set_last_dailyCash($subsidaryID);

            $dailyCash_data = array(
                    'date_time' => date('Y-m-d H:i:s a'),
                    'employee_id'=>$employee_id,
                    'total_amount'=>$total,
                    'subsidary_id'=>$subsidaryID,
                    'cycle_number'=>$sub_cycle->count_cycles,
                    'is_last'=>true
            );
            
            //Run these queries as a transaction, we want to make sure we do all or nothing
            $this->db->trans_start();

            $this->db->insert('daily_cash',$dailyCash_data);
            $dailyCash_id = $this->db->insert_id();

            $this->db->trans_complete();

            //2013-08-13
            foreach($sales as $sale)
            {
                $sale_id = $sale['sale_id'];
                $this->db->where("sale_id = $sale_id");
                $this->db->update('sales', array('id_dailyCash'=> $dailyCash_id));
            }
		
            if ($this->db->trans_status() === FALSE)
            {
                    return -1;
            }

            return $dailyCash_id;
        }
        
        //(2013-07-23)
        function save_cycleCash($employee_id,$total,$daily_cashes)
	{	    
            $subsidaryID = $this->session->userdata('subsidary_id');

            $sub_cycle = $this->Subsidary->get_subsidary_cycle($subsidaryID);
            
            $last = $this->get_last_cycleCash($subsidaryID);
            if($last)
                $this->set_last_cycleCash($subsidaryID);
            
            $cycleCash_data = array(
                    'date_time' => date('Y-m-d H:i:s'),
                    'employee_id'=>$employee_id,
                    'total_amount'=>$total,
                    'subsidary_id'=>$subsidaryID,
                    'cycle_number'=>$sub_cycle->count_cycles,
                    'is_last'=>true
            );
            
            //Run these queries as a transaction, we want to make sure we do all or nothing
            $this->db->trans_start();

            $this->db->insert('cycle_cash',$cycleCash_data);
            $cycleCash_id = $this->db->insert_id();
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE)
            {
                return -1;
            }
            else
            {
                //2013-08-13
                foreach($daily_cashes as $daily_cash)
                {
                    $daily_cash_id = $daily_cash['id_dailyCash'];
                    $this->db->where("id_dailyCash = $daily_cash_id");
                    $this->db->update('daily_cash', array('id_cycleCash'=> $cycleCash_id));
                }
            
                $this->db->where("subsidary_id = $subsidaryID");
                $this->db->update('subsidaries_cycles', array('count_cycles'=> ($sub_cycle->total_cycles == ($sub_cycle->count_cycles + 1))?1:($sub_cycle->count_cycles + 1),'count_sales'=> 1,'daily_count'=>1,'is_completed'=> false));
            }

            return $cycleCash_id;
        }
        
        //(2013-07-23)
        function get_dailyCash_by_cycleNumber($subsidary_id)
        {
            if(isset($subsidary_id))
            {
                $sub_cycle = $this->Subsidary->get_subsidary_cycle($subsidary_id);
                $this->db->where("cycle_number = $sub_cycle->count_cycles and subsidary_id = $subsidary_id and id_cycleCash = 0");
            }
            
            $query  = $this->db->get("daily_cash");
            return $query;
        }
        
        //HL (2014-01-16)
        function get_dailyCash_by_cycleId($id_cycleCash,$subsidary_id)
        {
            if(isset($subsidary_id))
                $this->db->where("subsidary_id",$subsidary_id);
            
            if(isset($id_cycleCash))
                $this->db->where("id_cycleCash",$id_cycleCash);
            
            $query  = $this->db->get("daily_cash");
            return $query;
        }
        /*
	Get search suggestions to find sales (2013-07-24)
	*/
	function get_sale_search_suggestions($search,$limit=25)
	{
            $subsidaryID = $this->session->userdata('subsidary_id');//change migue

            $suggestions = array();

            $this->db->from('sales');	
            $this->db->where("is_finished = 1 and subsidary_id = $subsidaryID");
            $this->db->like('billet_number', $search);
            $this->db->order_by("billet_number", "asc");
            $by_billet_number = $this->db->get();
            foreach($by_billet_number->result() as $row)
            {
                $suggestions[] = $row->sale_id.'|'.$row->billet_number/*.' - '.$row->receiving_time*/;
            }

            //only return $limit suggestions
            if(count($suggestions > $limit))
            {
                $suggestions = array_slice($suggestions, 0,$limit);
            }
            
            return $suggestions;
	}
        
        //(2013-07-25)
        function get_last_dailyCash($subsidary_id)
        {
            $this->db->from('daily_cash');
            $this->db->where("is_last = 1 and subsidary_id = $subsidary_id");
            
            $query = $this->db->get();
            
            if($query->num_rows()==1)
                return $query->row();
            else
                return false;
        }
        
        //(2013-07-25)
        function set_last_dailyCash($subsidary_id)
        {
            $this->db->where("is_last = 1 and subsidary_id = $subsidary_id");
            $this->db->update('daily_cash', array('is_last'=> false,'is_completed'=> false));
        }
        
        //(2013-07-25)
        function get_last_cycleCash($subsidary_id)
        {
            $this->db->from('cycle_cash');
            $this->db->where("is_last = 1 and subsidary_id = $subsidary_id");
            
            $query = $this->db->get();
            
            if($query->num_rows()==1)
                return $query->row();
            else
                return false;
        }
        
        //(2013-07-25)
        function set_last_cycleCash($subsidary_id)
        {
            $this->db->where("is_last = 1 and subsidary_id = $subsidary_id");
            $this->db->update('cycle_cash', array('is_last'=> false));
        }
        
        //(2013-07-25)
        function get_dailyCash_completed($subsidary_id)
        {
            $this->db->from('daily_cash');
            $this->db->where("is_last = 1 and is_completed = 1 and subsidary_id = $subsidary_id");
            
            $query = $this->db->get();
            
            if($query->num_rows()==1)
                return true;
            else
                return false;
        }

        //HL 2013-09-11
        function updateAll()
        {
            $enterprises = $this->Enterprise->get_all();
            if($enterprises)
            {
                foreach($enterprises->result() as $e)
                {
                    $subs = $this->Subsidary->get_all2($e->enterprise_id);
                    if($subs)
                    {
                        foreach($subs->result() as $s)
                        {
                            $sub_cycle_data = array(
                                'subsidary_id' => $s->subsidary_id,
                                'total_cycles'=>12,
                            );

                            //Run these queries as a transaction, we want to make sure we do all or nothing
                            $this->db->trans_start();

                            $this->db->insert('subsidaries_cycles',$sub_cycle_data);

                            $this->db->trans_complete();
                        }
                    }
                }
            }
        }
}
?>
