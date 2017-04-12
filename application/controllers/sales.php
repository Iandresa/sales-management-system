<?php

require_once ("secure_area.php");
require_once ("Enums/state.php");

class Sales extends Secure_area
{
    function __construct()
    {
        parent::__construct('sales');
        $this->load->library('sale_lib');
        $this->load->helper('report');
        //force_ssl();
    }

    function index()
    { 

        $this->sale_lib->clear_all();//ECP V_2 punto 3.7
        //$this->Sale->updateAll();//HL 2013-09-11
        $this->_reload(); 
        
        
    }

    function item_search() //(HL 2013-04-12)
    {
        $suggestions = $this->Item->get_item_search_suggestions2($this->input->post('q'), $this->input->post('limit'));
        echo implode("\n", $suggestions);
    }

    function customer_search()
    {
        $suggestions = $this->Customer->get_customer_search_suggestions($this->input->post('q'), $this->input->post('limit'));
        echo implode("\n", $suggestions);
    }

    function select_customer()
    {
        $customer_id = $this->input->post("customer");
        $this->sale_lib->set_customer($customer_id);
//       _reload();  //ECP V_2 punto 3.7
        
        $data = array();//ini ECP V_2 punto 3.8.2 mostrar sms si cliente VIP en sales
        if($this->Customer->is_customer_VIP($customer_id))
        {
          $data['error'] = $this->lang->line('sales_custumer_VIP');  
        }
        //fin ECP V_2 punto 3.8.1


        
        if ((!$this->_reload_selecting_customer($data)) && ($this->session->userdata('customer_last_sale') != 'last_sale_loaded'))////ECP V_2 punto 3.7 ini:  si carga last sale devuelve true
        {
          $this->session->set_userdata('customer_last_sale', 'NULO');  //ECP V_2 punto 3.7 pongo el valor last_sale, para que en el view se chequee al hacer el submit, y si es este estado no permita editar esta venta, ya que es solo informativa
        }
        else
        {            
           $this->session->set_userdata('customer_last_sale', 'last_sale_loaded');         
        }//ECP V_2 punto 3.7 fin
        
        
    }

    function sale_search()
    {
        $suggestions = $this->Sale->get_sale_search_suggestions($this->input->post('q'), $this->input->post('limit'));
        echo implode("\n", $suggestions);
    }

    function select_sale()
    {
        $sale_id = $this->input->post("sale");
        $this->_load_historical_view($sale_id);
    }

    function show_all()
    {
        $this->_load_historical_view();
    }

    function change_mode()
    {
        $this->sale_lib->clear_all(); //HECTOR
        $mode = $this->input->post("mode");
        $this->sale_lib->set_mode($mode);
        $this->_reload();
    }

    //Alain Multiple Payments
    function add_payment($sale_id=false)
    {
        $data = array();
        $this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'numeric');

        if ($this->form_validation->run() == FALSE)
        {
            if ($this->input->post('payment_type') == $this->lang->line('sales_gift_card'))
                $data['error'] = $this->lang->line('sales_must_enter_numeric_giftcard');
            else
                $data['error'] = $this->lang->line('sales_must_enter_numeric');

            $this->_reload($data);
            return;
        }

        $data['sale_id'] = $sale_id;

        $payment_type = $this->input->post('payment_type');
        if ($payment_type == $this->lang->line('sales_giftcard'))
        {
            $payment_type = $this->input->post('payment_type') . ':' . $payment_amount = $this->input->post('amount_tendered');
            $cur_giftcard_value = $this->Sale->getGiftcardValue($this->input->post('amount_tendered'));
            if ($cur_giftcard_value <= 0)
            {
                $data['error'] = 'Giftcard balance is ' . to_currency($this->Sale->getGiftcardValue($this->input->post('amount_tendered'))) . ' !';
                $this->_reload($data);
                return;
            }
            elseif (( $this->Sale->getGiftcardValue($this->input->post('amount_tendered')) - $this->sale_lib->get_total() ) > 0)
            {
                $data['warning'] = 'Giftcard balance is ' . to_currency($this->Sale->getGiftcardValue($this->input->post('amount_tendered')) - $this->sale_lib->get_total()) . ' !';
            }
            $payment_amount = min($this->sale_lib->get_total(), $this->Sale->getGiftcardValue($this->input->post('amount_tendered')));
        }
        else
            $payment_amount=$this->input->post('amount_tendered');

        if (!$this->sale_lib->add_payment($payment_type, $payment_amount))
        {
            $data['error'] = 'Unable to Add Payment! Please try again!';
        }

        $this->_reload($data);
    }

    //Alain Multiple Payments
    function delete_payment($payment_id)
    {
        $this->sale_lib->delete_payment($payment_id);
        $this->_reload();
    }

    function add()
    {
        $data = array();
        $mode = $this->sale_lib->get_mode();
        $item_id_or_number_or_receipt = $this->input->post("item");
        $quantity = $mode == "sale" ? 1 : -1;
        
        $subsidary = $this->Subsidary->get_info($this->session->userdata('subsidary_id'));
        $managers = $this->Enterprise->get_enterprise_managers($subsidary->enterprise_id);
        $item = $this->Item->get_info($item_id_or_number_or_receipt);

        if ($item_id_or_number_or_receipt != "")
        {
            if ($this->sale_lib->is_valid_receipt($item_id_or_number_or_receipt) && $mode == 'return')
            {
                $this->sale_lib->return_entire_sale($item_id_or_number_or_receipt);
            }
            elseif (!$this->Item->exists($item_id_or_number_or_receipt))
            {
                $data['error'] = $this->lang->line('sales_item_noExist');
            }//HL 2013-04-14
            elseif ($this->Sale->item_for_noSale_low_stock($item_id_or_number_or_receipt) && $mode == 'sale')
            {
                $data['error'] = $this->lang->line('sales_item_for_noSale_low_stock');
                //HL 2013-04-22
                foreach($managers->result() as $m)
                {
                    $this->tasklib->Sent_Letter_To_Person("item_low_stock", $m->email, $subsidary->language,
                    $client_name=$m->first_name." ".$m->last_name,
                    $username=null,
                    $password=null, 
                    $days_trial=null, 
                    $purchased_module=null,
                    $impresion_amount=null,
                    $item_name=$item->name,
                    $subsidary_name=$subsidary->company);
                }
                
            }//HL 2013-04-14
            /*elseif ($this->sale_lib->out_of_stock($item_id_or_number_or_receipt))
            {
                $data['warning'] = $this->lang->line('sales_quantity_less_than_zero');
            }*/
            elseif (!$this->sale_lib->add_item($item_id_or_number_or_receipt, $quantity))
            {
                $data['error'] = $this->lang->line('sales_unable_to_add_item');
            }
            
            if ($this->sale_lib->out_of_stock($item_id_or_number_or_receipt))
            {
                $data['warning'] = $this->lang->line('sales_quantity_less_than_zero');
                //HL 2013-04-22
                foreach($managers->result() as $m)
                {
                    $this->tasklib->Sent_Letter_To_Person("item_low_stock", $m->email, $subsidary->language,
                    $client_name=$m->first_name." ".$m->last_name,
                    $username=null,
                    $password=null, 
                    $days_trial=null, 
                    $purchased_module=null,
                    $impresion_amount=null,
                    $item_name=$item->name,
                    $subsidary_name=$subsidary->company);
                }
            }
        }
        
        $this->_reload($data);
    }

    function edit_item($line)
    {
        $data = array();

        $mode = $this->sale_lib->get_mode();//HL 2013-04-14

        $this->form_validation->set_rules('price', 'lang:items_price', 'required|numeric');
        $this->form_validation->set_rules('quantity', 'lang:items_quantity', 'required|numeric');
        $this->form_validation->set_rules('discount', 'lang:items_discount', 'required|numeric');

        $description = $this->input->post("description");
        $serialnumber = $this->input->post("serialnumber");
        $price = $this->input->post("price");
        $quantity = $this->input->post("quantity");
        $discount = $this->input->post("discount");

        if ($this->form_validation->run() != FALSE)
        {   //HL 2013-04-14
            if ($this->Sale->item_for_noSale_low_stock2($this->sale_lib->get_item_id($line),$quantity) && $mode == 'sale')
            {
                $data['error'] = $this->lang->line('sales_item_for_noSale_low_stock');
            }//HL 2013-04-14
            /*elseif ($this->sale_lib->out_of_stock($this->sale_lib->get_item_id($line)))
            {
                $data['warning'] = $this->lang->line('sales_quantity_less_than_zero');
            }*/
            elseif(($mode == "sale" && $quantity <= 0) || ($mode == "return" && $quantity >= 0) ||
                    ($price <= 0) || ($discount < 0) )//HL (2013-05-27)
            {
                $data['error'] = $this->lang->line('sales_error_editing_item2');
            }
            else
                $this->sale_lib->edit_item($line, $description, $serialnumber, $quantity, $discount, $price);
        }
        else
        {
            $data['error'] = $this->lang->line('sales_error_editing_item2');
        }

        if ($this->sale_lib->out_of_stock($this->sale_lib->get_item_id($line)))
        {
            $data['warning'] = $this->lang->line('sales_quantity_less_than_zero');
        }

        $this->_reload($data);
    }

    function delete_item($item_number)
    {
        $this->sale_lib->delete_item($item_number);
        $this->_reload();
    }

    /* NEW */

    function delete_item_finished($item_number)
    {
        $this->sale_lib->delete_item_finished($item_number);
        $this->_reload();
    }

    /*     * ** */

    function delete_customer()
    {
        $this->sale_lib->delete_customer();
        if($this->session->userdata('customer_last_sale') == 'last_sale_loaded') //ECP V_2 punto 3.7 : para limpiar cuando elimino al cliente de la venta si estaba cargada la última venta de ese cliente
        {
            $this->sale_lib->clear_all();
            $this->session->set_userdata('customer_last_sale', 'NULO');
        }
                
        $this->_reload();
    }

    function complete($sale_id=false, $state=State::regular, $postponeName="")
    {
        $data['cart'] = $this->sale_lib->get_cart();
        $data['subtotal'] = $this->sale_lib->get_subtotal();
        $data['taxes'] = $this->sale_lib->get_taxes();
        $data['total'] = $this->sale_lib->get_total();
        $data['receipt_title'] = $this->lang->line('sales_receipt');
        $data['transaction_time'] = date('m/d/Y h:i:s a');
        $customer_id = $this->sale_lib->get_customer();
        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
        //$comment = $this->sale_lib->get_comment();
        $emp_info = $this->Employee->get_info($employee_id);
        
        //Alain Multiple payments
        $data['payments'] = $this->sale_lib->get_payments();
        $data['amount_change'] = to_currency($this->sale_lib->get_amount_due() * -1);
        $data['employee'] = $emp_info->first_name . ' ' . $emp_info->last_name;

        if ($customer_id != -1)
        {
            $cust_info = $this->Customer->get_info($customer_id);
            $data['customer'] = $cust_info->first_name . ' ' . $cust_info->last_name;
        }

        $total_payments = 0;

        foreach ($data['payments'] as $payment)
        {
            $total_payments += $payment['payment_amount'];
        }

        /* Changed the conditional to account for floating point rounding */
        if (( $this->sale_lib->get_mode() == 'sale' ) && ( (( to_currency_no_money($data['total']) - to_currency_no_money($total_payments) ) > 1e-6 ) ||
                ( (( to_currency_no_money($data['total']) - to_currency_no_money($total_payments) ) < 1e-6 ) && (( to_currency_no_money($data['total']) - to_currency_no_money($total_payments) ) != 0 ) ) ))
        {
            $data['error'] = $this->lang->line('sales_payment_not_cover_total');
            $this->_reload($data);
            return false;
        }

        //SAVE sale to database
        $id = 0;
        if (!$sale_id)
        {
            $id = $this->Sale->save($data['cart'], $customer_id, $employee_id, /*$comment,*/ $data['payments'], $state, false,$postponeName);

            $data['sale_id'] = 'POS ' . $id;
            if ($data['sale_id'] == 'POS -1')
            {
                $data['error_message'] = $this->lang->line('sales_transaction_failed');
                $this->_reload($data);
                return false;
            }
        }
        else
        {
            $sale = $this->Delivery->get_sale($sale_id);
            $this->Sale->update($sale->sale_time, $data['cart'], $customer_id, $employee_id, /*$comment,*/ $data['payments'], $state, $sale_id);
            
            if($this->session->userdata('hide_buttons'))
            {
                /*if($state==State::regular)
                    $data['success'] = $this->lang->line('sales_successfully');*/
                $this->sale_lib->clear_all();
                //$this->_reload($data);
            }
            else
            {
                $this->sale_lib->clear_all();

                if($state!=State::regular)
                    $this->load_postpone_view($state);
            }
            
            return false;
        }

        /* if(!$un_seted && ($this->Subsidary->get_print_after_sale()))
          {
          $this->load->view("sales/receipt",$data);
          } */
        
        $mode = $this->sale_lib->get_mode();
        if($state==State::postpone)
        {
            $data['success'] = $this->lang->line('sales_postpone_successfully');
                        
            $this->sale_lib->clear_all();
            
            $this->_reload($data); 
        }
        else if ($state==State::order)
        {
            if($this->Subsidary->get_order_and_finishSale())
            {
                $this->sale_lib->set_saleId($id);
                $this->sale_lib->set_hideButtons(true);
                
                $data['success'] = $this->lang->line('sales_order_successfully');
            }
            else
            {
                $data['success'] = $this->lang->line('sales_postponeOrder_successfully');
                //enviar a ventas en proceso
                $this->Sale->change_sale_state($id,$state);
                
                $this->sale_lib->clear_all();
            }
            
            $this->_reload($data);     
        }
        else if($state==State::delivery)
        {
            if($this->Subsidary->get_delivery_and_finishSale())
            {
                $this->sale_lib->set_saleId($id);
                $this->sale_lib->set_hideButtons(true);
                
                $data['success'] = $this->lang->line('sales_delivery_successfully');
            }
            else
            {
                $data['success'] = $this->lang->line('sales_postponeDelivery_successfully');
                
                //enviar a ventas en proceso
                $this->Sale->change_sale_state($id,$state);
                
                $this->sale_lib->clear_all();
            }
            
            $this->_reload($data);
        }
        else
        {
            $this->sale_lib->clear_all();
            return $id;
        }

        /*if ($state==State::regular) //HL 2013-09-05
        {
            ($mode == 'sale') ? $data['success'] = $this->lang->line('sales_successfully') : $data['success'] = $this->lang->line('return_successfully');
            $this->_reload($data);
        }*/
    }

    //HL 2013-09-05
    function complete_to_print($sale_id = null)
    {
        if(isset($sale_id))
            $this->complete($sale_id);
        else
            $sale_id = $this->complete();

        if($sale_id)
            $this->load_historical_sale_bill($sale_id);
    }

    //NEW
    function close_sale($sale_id)
    {
        $sale_info = $this->Sale->get_info($sale_id)->row_array();
        if($sale_info['state']==State::order)
            $this->Sale->change_sale_state($sale_id,State::order);
        else if($sale_info['state']==State::delivery)
            $this->Sale->change_sale_state($sale_id,State::delivery);
        
        $this->complete($sale_id,$sale_info['state']);
    }
    /****/
    
    function receipt($sale_id)
    {
        $sale_info = $this->Sale->get_info($sale_id)->row_array();
        $this->sale_lib->copy_entire_sale($sale_id);
        $data['cart'] = $this->sale_lib->get_cart();
        $data['payments'] = $this->sale_lib->get_payments();
        $data['subtotal'] = $this->sale_lib->get_subtotal();
        $data['taxes'] = $this->sale_lib->get_taxes();
        $data['total'] = $this->sale_lib->get_total();
        $data['receipt_title'] = $this->lang->line('sales_receipt');
        $data['transaction_time'] = date('m/d/Y h:i:s a', strtotime($sale_info['sale_time']));
        $customer_id = $this->sale_lib->get_customer();
        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $emp_info = $this->Employee->get_info($employee_id);
        $data['payment_type'] = $sale_info['payment_type'];
        $data['amount_change'] = to_currency($this->sale_lib->get_amount_due() * -1);
        $data['employee'] = $emp_info->first_name . ' ' . $emp_info->last_name;

        if ($customer_id != -1)
        {
            $cust_info = $this->Customer->get_info($customer_id);
            $data['customer'] = $cust_info->first_name . ' ' . $cust_info->last_name;
        }
        $data['sale_id'] = 'POS ' . $sale_id;
        $data['mode'] = $this->sale_lib->get_mode();
        $this->load->view("sales/receipt", $data);
        $this->sale_lib->clear_all();
    }

    //HL (2013-09-05)
    function go_to_sales()
    {
        $this->sale_lib->clear_all();
        $this->_reload();
    }
    ////////////////

    /************OJO***************/
    function _reload($data=array())
    {
       $this->session->set_userdata('customer_last_sale', 'NULO'); //ECP V_2 punto 3.7

        if ($this->sale_lib->get_mode() == "historical")
        {
            $this->_load_historical_view();
            return;
        }  elseif ($this->sale_lib->get_mode() == "historical_dailyCash") {
            $this->_load_historical_dailyCash_view();
            return;
        }  elseif ($this->sale_lib->get_mode() == "historical_cycleCash"){
            $this->_load_historical_cycleCash_view();
            return;
        }
        
        if (!$this->session->userdata('sale_id'))
        {
            $data['sale_id'] = false;
            $data['hide_buttons'] = false;
            $data['isDone'] = false;
        }
        else
        {
            $sale_id = $this->session->userdata('sale_id');
            $sale = $this->Delivery->get_sale($sale_id);
            $data['sale_date'] = $sale->sale_time;
            //$data['comment'] = $sale->comment;
            $data['sale_id'] = $sale_id;
          
            $data['hide_buttons'] = $this->session->userdata('hide_buttons');
            
            $data['isDone'] = $sale->isDone; 
        }

        $person_info = $this->Employee->get_logged_in_employee_info();
        $data['cart'] = $this->sale_lib->get_cart();
        $data['cart_finished'] = $this->sale_lib->get_finished_cart();
        $data['modes'] = array('sale' => $this->lang->line('sales_sale'), 'return' => $this->lang->line('sales_return'), 'historical' => $this->lang->line('sales_historical'), 'historical_dailyCash' => $this->lang->line('sales_historical_dailyCash'), 'historical_cycleCash' => $this->lang->line('sales_historical_cycleCash'));
        $data['mode'] = $this->sale_lib->get_mode();
        $data['subtotal'] = $this->sale_lib->get_subtotal();
        $data['taxes'] = $this->sale_lib->get_taxes();
        $data['total'] = $this->sale_lib->get_total();
        $data['items_module_allowed'] = $this->Employee->has_permission('items', $person_info->person_id);
        //$data['comment'] = $this->sale_lib->get_comment();

        //Alain Multiple Payments
        $data['payments_total'] = $this->sale_lib->get_payments_total();
        $data['amount_due'] = $this->sale_lib->get_amount_due();
        $data['payments'] = $this->sale_lib->get_payments();
        $data['payment_options'] = array(
            $this->lang->line('sales_cash') => $this->lang->line('sales_cash'),
            $this->lang->line('sales_check') => $this->lang->line('sales_check'),
            //$this->lang->line('sales_giftcard') => $this->lang->line('sales_giftcard'),
            $this->lang->line('sales_debit') => $this->lang->line('sales_debit'),
            $this->lang->line('sales_credit') => $this->lang->line('sales_credit'),
            $this->lang->line('sales_transfer') => $this->lang->line('sales_transfer')
        );

        $customer_id = $this->sale_lib->get_customer();
        if ($customer_id != -1)
        {
            $info = $this->Customer->get_info($customer_id);
            $data['customer'] = $info->first_name . ' ' . $info->last_name;
        }
        
        $data['language'] = $this->Subsidary->get_language();

        //HL 2013-07-27
        $subsidary_id = $this->session->userdata('subsidary_id');

        $sub_cycle = $this->Subsidary->get_subsidary_cycle($subsidary_id);
        if($sub_cycle)
        {
            if($sub_cycle->is_completed)
            {
                $cycle_cash = $this->Sale->get_last_cycleCash($subsidary_id);
                if($cycle_cash)
                {
                    $emp_info = $this->Employee->get_info($cycle_cash->employee_id);
                    $data['cycle_cash_completed'] = $this->lang->line('sales_cycle_cash_completed') . $cycle_cash->date_time . ' ' . $emp_info->first_name . ' ' . $emp_info->last_name;
                }
                else
                    $data['cycle_cash_completed'] = $this->lang->line('sales_cycle_cash_completed');
            }
        }

        $daily_cash = $this->Sale->get_last_dailyCash($subsidary_id);
        if($daily_cash)
        {
            if($daily_cash->is_completed)
            {
                $emp_info = $this->Employee->get_info($daily_cash->employee_id);
                $data['daily_cash_completed'] = $this->lang->line('sales_daily_cash_completed') . $daily_cash->date_time . ' ' . $emp_info->first_name . ' ' . $emp_info->last_name;
            }
        }
        else
        {
            $data['daily_cash_completed'] = $this->lang->line('sales_daily_cash_completed');
            $data['daily_cash_is_completed'] = true;
        }

        $data['sub_cycle_is_completed'] = (isset($sub_cycle->is_completed))?$sub_cycle->is_completed:false;
        if($daily_cash)
            $data['daily_cash_is_completed'] = (isset($daily_cash->is_completed))?$daily_cash->is_completed:false;
        /*****/

        $this->load->view("sales/register", $data);
    
    } 
    
    /*ECP V_2 punto 3.7 se ejecuta cuando se selecciona el cliente en una venta como primera acción*/
    function _reload_selecting_customer($data=array())
    {      
       $return_value = false;
        
        if ($this->sale_lib->get_mode() == "historical")
        {
            $this->_load_historical_view();
            return;
        }  elseif ($this->sale_lib->get_mode() == "historical_dailyCash") {
            $this->_load_historical_dailyCash_view();
            return;
        }  elseif ($this->sale_lib->get_mode() == "historical_cycleCash"){
            $this->_load_historical_cycleCash_view();
            return;
        }
        
        if (!$this->session->userdata('sale_id'))
        {
            $data['sale_id'] = false;
            $data['hide_buttons'] = false;
            $data['isDone'] = false;
        }
        else
        {
            $sale_id = $this->session->userdata('sale_id');
            $sale = $this->Delivery->get_sale($sale_id);
            $data['sale_date'] = $sale->sale_time;
            //$data['comment'] = $sale->comment;
            $data['sale_id'] = $sale_id;
          
            $data['hide_buttons'] = $this->session->userdata('hide_buttons');
            
            $data['isDone'] = $sale->isDone;
        }
        
        $last_sale = $this->session->userdata('customer_last_sale');
        $customer_id = $this->sale_lib->get_customer();
        $cart = $this->sale_lib->get_cart();
        
//cargar los datos de la última venta si :
//no se han seleccionado items (count($cart) == 0)
//hay un cliente seleccionado ($customer_id)
//y la variable de session customer_last_sale, tiene el valor "load_last_sale"
      
        $sale_id = $this->Sale->get_customer_last_sale($customer_id);//ventas del cliente
        
        if ((count($cart) == 0) && ($customer_id) && ($last_sale == "load_last_sale") && ($sale_id != -1))//ECP V_2 punto 3.7 mostrar ulima venta al seleccionar al cliente
        {
            
//en este caso debo buscar la últia venta realizada, cómo la busco, desde el modelo sale.php, ejecutar uan consulta
//que busque la últim aventa registrada para ese customer
//entonces desde aquí sales.php que es el controlador llamo al modelo $this->load->model('sale'); 
//de esa venta necesito los items del modelo utilizar function get_sale_items($sale_id), cuando tenga el id de la última venta
//con eso llenar (aún no sé cómo el cart, posiblemente utilizando function set_cart($cart_data) )
// para entonces ejecutar el código que está a continuación
//y bueno al final de esta misma función se carga el view $this->load->view("sales/register", $data);
           
	    $return_value = true;           

            $this->sale_lib->set_cart(array());//para limpiar el cart
            
//            $sale_id = 0;
//            foreach($this->Sale->get_sale_items($sales)->result() as $row)
//            {
//                 $this->sale_lib->add_item($row->item_id,$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,null,$row->description,$row->serialnumber);
//            
//                 $sale_id  =   $row->sale_id;
//            }

 //------------------------------------------------------------------------------------------ 
            $sale_info = $this->Sale->get_info($sale_id)->row_array();
            $this->sale_lib->copy_entire_sale($sale_id);
            
            $person_info = $this->Employee->get_logged_in_employee_info();
            $data['cart'] = $this->sale_lib->get_cart();
            $data['cart_finished'] = $this->sale_lib->get_finished_cart();
            $data['modes'] = array('sale' => $this->lang->line('sales_sale'), 'return' => $this->lang->line('sales_return'), 'historical' => $this->lang->line('sales_historical'), 'historical_dailyCash' => $this->lang->line('sales_historical_dailyCash'), 'historical_cycleCash' => $this->lang->line('sales_historical_cycleCash'));
            $data['mode'] = $this->sale_lib->get_mode();
            $data['subtotal'] = $this->sale_lib->get_subtotal();
            $data['taxes'] = $this->sale_lib->get_taxes();
            $data['total'] = $this->sale_lib->get_total();
            $data['items_module_allowed'] = $this->Employee->has_permission('items', $person_info->person_id);
            //$data['comment'] = $this->sale_lib->get_comment();

            //Alain Multiple Payments

            $data['payments_total'] = $this->sale_lib->get_payments_total();
            $data['amount_due'] = $this->sale_lib->get_amount_due();
            $data['payments'] = $this->sale_lib->get_payments();
            $data['payment_options'] = array(
                $this->lang->line('sales_cash') => $this->lang->line('sales_cash'),
                $this->lang->line('sales_check') => $this->lang->line('sales_check'),
                //$this->lang->line('sales_giftcard') => $this->lang->line('sales_giftcard'),
                $this->lang->line('sales_debit') => $this->lang->line('sales_debit'),
                $this->lang->line('sales_credit') => $this->lang->line('sales_credit'),
                $this->lang->line('sales_transfer') => $this->lang->line('sales_transfer')
            );

        }
        else//ejecutar lo que sería un _reload normal
        {
            $person_info = $this->Employee->get_logged_in_employee_info();
            $data['cart'] = $this->sale_lib->get_cart();
            $data['cart_finished'] = $this->sale_lib->get_finished_cart();
            $data['modes'] = array('sale' => $this->lang->line('sales_sale'), 'return' => $this->lang->line('sales_return'), 'historical' => $this->lang->line('sales_historical'));
            $data['mode'] = $this->sale_lib->get_mode();
            $data['subtotal'] = $this->sale_lib->get_subtotal();
            $data['taxes'] = $this->sale_lib->get_taxes();
            $data['total'] = $this->sale_lib->get_total();
            $data['items_module_allowed'] = $this->Employee->has_permission('items', $person_info->person_id);
            //$data['comment'] = $this->sale_lib->get_comment();

            //Alain Multiple Payments
            $data['payments_total'] = $this->sale_lib->get_payments_total();
            $data['amount_due'] = $this->sale_lib->get_amount_due();
            $data['payments'] = $this->sale_lib->get_payments();
            $data['payment_options'] = array(
                $this->lang->line('sales_cash') => $this->lang->line('sales_cash'),
                $this->lang->line('sales_check') => $this->lang->line('sales_check'),
                //$this->lang->line('sales_giftcard') => $this->lang->line('sales_giftcard'),
                $this->lang->line('sales_debit') => $this->lang->line('sales_debit'),
                $this->lang->line('sales_credit') => $this->lang->line('sales_credit'),
                $this->lang->line('sales_transfer') => $this->lang->line('sales_transfer')
            );
        }

        $customer_id = $this->sale_lib->get_customer();
        if ($customer_id != -1)
        {
            $info = $this->Customer->get_info($customer_id);
            $data['customer'] = $info->first_name . ' ' . $info->last_name;
        }
        
        $data['language'] = $this->Subsidary->get_language();

        //HL 2013-07-27
        $subsidary_id = $this->session->userdata('subsidary_id');

        $sub_cycle = $this->Subsidary->get_subsidary_cycle($subsidary_id);
        if($sub_cycle)
        {
            if($sub_cycle->is_completed)
            {
                $cycle_cash = $this->Sale->get_last_cycleCash($subsidary_id);
                if($cycle_cash)
                {
                    $emp_info = $this->Employee->get_info($cycle_cash->employee_id);
                    $data['cycle_cash_completed'] = $this->lang->line('sales_cycle_cash_completed') . $cycle_cash->date_time . ' ' . $emp_info->first_name . ' ' . $emp_info->last_name;
                }
            }
        }

        $daily_cash = $this->Sale->get_last_dailyCash($subsidary_id);
        if($daily_cash)
        {
            if($daily_cash->is_completed)
            {
                $emp_info = $this->Employee->get_info($daily_cash->employee_id);
                $data['daily_cash_completed'] = $this->lang->line('sales_daily_cash_completed') . $daily_cash->date_time . ' ' . $emp_info->first_name . ' ' . $emp_info->last_name;
            }
        }
        else
        {
            $data['daily_cash_completed'] = $this->lang->line('sales_daily_cash_completed');
            $data['daily_cash_is_completed'] = true;
        }

        $data['sub_cycle_is_completed'] = (isset($sub_cycle->is_completed))?$sub_cycle->is_completed:false;
        if($daily_cash)
            $data['daily_cash_is_completed'] = (isset($daily_cash->is_completed))?$sub_cycle->is_completed:false;
        /*****/

        $this->load->view("sales/register", $data);
        
        return $return_value;
    }
    
    
    public function getDataColumns()
    {
        return array($this->lang->line('reports_date'), $this->lang->line('items_item'), $this->lang->line('reports_subtotal'), $this->lang->line('reports_total'), $this->lang->line('reports_tax'), $this->lang->line('reports_profit'), $this->lang->line('reports_cost'));
    }

    function view_details($date)
    {
        $this->load->model('reports/Summary_sales');
        $model = $this->Summary_sales;

        $tabular_data = array();

        $report_data = $model->getData3($date);

        foreach ($report_data as $row)
        {
            $tabular_data[] = array($row['sale_date'], $row['name'],$row['quantity_purchased'], to_currency($row['subtotal']), to_currency($row['total']), to_currency($row['tax']), to_currency($row['profit']), to_currency($row['cost']));
        }

        $data = array(
            "title" => $this->lang->line('reports_detailed_sale'),
            "subtitle" => date('m/d/Y', strtotime($date)),
            "headers" => $model->getDataColumnsDetails(),
            "data" => $tabular_data,
            "summary_data" => get_report_footer(2, 0, $model->getSummaryDataDetails($date)),
        );

        $this->load->view("reports/sale_details", $data);
    }

    function cancel_sale($sale_id=false)
    {
        $this->sale_lib->clear_all();
        if ($sale_id)
            $this->load_postpone_view();
        else
            $this->_reload();
    }

    function add_unsettled_sale($sale_id=false)
    {
        if($sale_id)
        {
            if($this->Delivery->getSaleState($sale_id)==State::delivery || 
               $this->Delivery->getSaleState($sale_id)==State::postpone_delivery ||
               $this->Delivery->getSaleState($sale_id)==State::postpone)
            {
               $this->Sale->change_sale_state($sale_id,State::order);
            }
        }
        $this->complete($sale_id, State::order);       
    }
    
    function save_sale($sale_id=false)
    {
        $this->complete($sale_id, State::postpone);       
    }

    function add_delivery($sale_id=false)
    {
        if($sale_id)
        {
            if($this->Delivery->getSaleState($sale_id)==State::order || 
               $this->Delivery->getSaleState($sale_id)==State::postpone_order ||
               $this->Delivery->getSaleState($sale_id)==State::postpone)
            {
               $this->Sale->change_sale_state($sale_id,State::delivery);
            }
        }
        $this->complete($sale_id, State::delivery);        
    }

    function ready_to_complete($sale_id = null)
    {
        if (!$this->session->userdata('sale_id'))
            $data['sale_id'] = false;
        else
        {
            $sale_id = $this->session->userdata('sale_id');
            $data['sale_id'] = $sale_id;
        }

        //2013-02-04 HL 
        if($this->session->userdata('sale_id'))
        {
            if( ($this->Delivery->getSaleState($sale_id)==State::order) && ($this->Subsidary->get_order_and_finishSale()) )
                $data['cart'] = $this->sale_lib->get_cart();
            else if(($this->Delivery->getSaleState($sale_id)==State::delivery) && ($this->Subsidary->get_delivery_and_finishSale()))
                $data['cart'] = $this->sale_lib->get_cart();
            else if($this->Delivery->getSaleState($sale_id)==State::postpone)
                $data['cart'] = $this->sale_lib->get_cart();
            else
                $data['cart'] = $this->sale_lib->get_finished_cart();
        }    
        else 
            $data['cart'] = $this->sale_lib->get_cart();
             
        $data['subtotal'] = $this->sale_lib->get_subtotal();
        $data['taxes'] = $this->sale_lib->get_taxes();
        $data['total'] = $this->sale_lib->get_total();
        $data['receipt_title'] = $this->lang->line('sales_receipt');
        $data['transaction_time'] = date('m/d/Y h:i:s a');
        $customer_id = $this->sale_lib->get_customer();
        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $emp_info = $this->Employee->get_info($employee_id);

        //Alain Multiple payments
        $data['payments'] = $this->sale_lib->get_payments();
        $data['amount_change'] = to_currency($this->sale_lib->get_amount_due() * -1);
        $data['employee'] = $emp_info->first_name . ' ' . $emp_info->last_name;

        if ($customer_id != -1)
        {
            $cust_info = $this->Customer->get_info($customer_id);
            $data['customer'] = $cust_info->first_name . ' ' . $cust_info->last_name;
        }

        $data['mode'] = $this->sale_lib->get_mode();
       
        $this->load->view("sales/receipt", $data);
    }

    function delete_sale($sale_id)
    {
        $this->Sale->delete_sale($sale_id);
        $this->sale_lib->clear_all();
        $this->_reload();
    }

    function activate_module()
    {
        $data['module_report'] = false;
        $this->load->view("viewmsg_activate_module",$data);
    }
    
    function activate_module_report()
    {
        $data['module_report'] = true;
        $this->load->view("viewmsg_activate_module",$data);
    }

    //Ariel:
    function _load_historical_view($sale_id = null)
    {
        //Ariel: SOlo para volver a la vista Sale, cuando todo este bien borrar esta linea
        $this->sale_lib->set_mode("historical");

        $subsidary_id = $this->session->userdata('subsidary_id');
        $person_id = $this->session->userdata('person_id');

        /*
          //Ariel: Por si despues se quieren mostrar
          todas las Sales si es el SUDO.
          if($this->Employee->is_SuperUser($person_id))
          $sales = ...
          else
          $sales = ...
         */

        if($sale_id)
            $sales = $this->Sale->get_sales_finished_per_bill($subsidary_id,$sale_id);
        else
            $sales = $this->Sale->get_sales_finished_per_subsidary($subsidary_id);

        $data['controller_name'] = strtolower($this->uri->segment(1));
        //$data['form_width'] = $this->get_form_width();
        $data['manage_table'] = get_saleshistorical_manage_table($sales);
        
        $data['modes'] = array('sale' => $this->lang->line('sales_sale'), 'return' => $this->lang->line('sales_return'), 'historical' => $this->lang->line('sales_historical'), 'historical_dailyCash' => $this->lang->line('sales_historical_dailyCash'), 'historical_cycleCash' => $this->lang->line('sales_historical_cycleCash'));
        $data['mode'] = $this->sale_lib->get_mode();
        
        $this->load->view('sales/historical', $data);
    }
    
    //Hector
    function _load_historical_dailyCash_view($sale_id = null)
    {
        //Ariel: SOlo para volver a la vista Sale, cuando todo este bien borrar esta linea
        $this->sale_lib->set_mode("historical_dailyCash");

        $subsidary_id = $this->session->userdata('subsidary_id');

        $sales = $this->Sale->get_dailyCash_finished_per_subsidary($subsidary_id);

        $data['controller_name'] = strtolower($this->uri->segment(1));

        $data['manage_table'] = get_dailyCashHistorical_manage_table($sales);
        
        $data['modes'] = array('sale' => $this->lang->line('sales_sale'), 'return' => $this->lang->line('sales_return'), 'historical' => $this->lang->line('sales_historical'), 'historical_dailyCash' => $this->lang->line('sales_historical_dailyCash'), 'historical_cycleCash' => $this->lang->line('sales_historical_cycleCash'));
        $data['mode'] = $this->sale_lib->get_mode();
        
        $this->load->view('sales/historical', $data);
    }
    
    //Hector
    function _load_historical_cycleCash_view($sale_id = null)
    {
        //Ariel: SOlo para volver a la vista Sale, cuando todo este bien borrar esta linea
        $this->sale_lib->set_mode("historical_cycleCash");

        $subsidary_id = $this->session->userdata('subsidary_id');

        $sales = $this->Sale->get_cycleCash_finished_per_subsidary($subsidary_id);

        $data['controller_name'] = strtolower($this->uri->segment(1));

        $data['manage_table'] = get_cycleCashHistorical_manage_table($sales);
        
        $data['modes'] = array('sale' => $this->lang->line('sales_sale'), 'return' => $this->lang->line('sales_return'), 'historical' => $this->lang->line('sales_historical'), 'historical_dailyCash' => $this->lang->line('sales_historical_dailyCash'), 'historical_cycleCash' => $this->lang->line('sales_historical_cycleCash'));
        $data['mode'] = $this->sale_lib->get_mode();
        
        $this->load->view('sales/historical', $data);
    }
    
    //Hector
    function margin_footer()
    {		
        $banners = $this->session->userdata('real_banners_showed');
        $rows = (($banners%2)==0)?($banners/2):($banners/2)+1;
        $margin = ($rows == 0)?(1 * $this->config->item('banner_side_height')):($rows * $this->config->item('banner_side_height'));
        return $margin;
    }
    
    //Migue:
    function load_postpone_view($state=State::regular)
    {    
        $data['margin'] = $this->margin_footer();
        
        if($state==State::order)
            $data['success'] = $this->lang->line('sales_postponeOrder_successfully_saved');
        else if($state==State::delivery)
            $data['success'] = $this->lang->line('sales_postponeDelivery_successfully_saved');
        
        $this->load->view('sales/postpone',$data);
    }
   /* //Migue:
    function postpone_dialog()
    {
        $this->load->view('sales/postpone_dialog');
    }*/
    //Migue:
    function update_div()
    {	
        //$data['controller_name']=strtolower($this->uri->segment(1));
        $this->load->model('Postpone');
        $data['lista_clientes'] = $this->Postpone->get_all();
        $this->load->view('sales/refresh_view',$data);
    }
    //Migue:
    function add_postpone($sale_id=false)
    {
        //$saleName = $this->input->post("name");
        $this->complete($sale_id,State::postpone);  
    }
    
    //Ariel
    function load_historical_sale_bill($sale_id)
    {
        $data['sale_id'] = $sale_id;

        $sale = $this->Sale->get_info($data['sale_id']);
        $sale = $sale->row_array();
        
        $subtotal = 0;
        
        $items = $this->Sale->get_sale_items($data['sale_id']);
        $cart = array();
        foreach($items->result_array() as $item)
        {
            $itemrow = $this->Item->get_info($item['item_id']);
            $cart_item = array(
                "item_number" => $itemrow->item_number,
                "name"=> $itemrow->name,
                "price" => $item["item_unit_price"],
                "quantity" => $item["quantity_purchased"],
                "discount" => $item["discount_percent"],
                "description" => $item["description"],
                "serialnumber" => $item["serialnumber"]
            );
            $temp = ($cart_item['price']*$cart_item['quantity']-$cart_item['price']*$cart_item['quantity']*$cart_item['discount']/100);
            $subtotal += $temp;
            $cart_item["subtotal"] = $temp;
            $cart[$item['item_id']] = $cart_item;
        }
        $data['cart'] = $cart;//$cart;
        $data['subtotal'] = $subtotal;
        
        $sale_taxes = $this->Item_taxes->get_sale_taxes($data['sale_id']);
        $taxes = array();
        foreach($sale_taxes->result_array() as $tax)
        {
            $temp = ($cart[$tax["item_id"]]["subtotal"] * $tax['percent'])/100;
            $taxes[$tax['percent']."%"] = $temp;
            $subtotal += $temp;
        }
        $data['taxes'] = $taxes;
        
        
        $data['total'] = $subtotal;
        $data['receipt_title'] = $this->lang->line('sales_receipt');
        $data['transaction_time'] = date("d/M/Y h:i:s a", strtotime($sale["sale_time"]));
        $customer_id = $sale['customer_id'];
        $emp_info = $this->Employee->get_info($sale["employee_id"]);

        //Alain Multiple payments
        $sale_payments = $this->Sale->get_sale_payments($data['sale_id']);
        $sale_payments = $sale_payments->result_array();
     
        $totalpayments = 0;
        foreach($sale_payments as $pay)
        {
            $totalpayments += $pay["payment_amount"];
        }
        $data['payments'] = $sale_payments;
        
        $data['amount_change'] = $totalpayments - $subtotal;
       
        $data['employee'] = $emp_info->first_name . ' ' . $emp_info->last_name;
        if ($customer_id > 0)
        {
            $cust_info = $this->Customer->get_info($customer_id);
            $data['customer'] = $cust_info->first_name . ' ' . $cust_info->last_name;
        }

        $data['mode'] = $sale['mode'];
        $data['historical'] = true;

        $data['bill_number'] = $sale['billet_number'];

        if($this->sale_lib->get_mode() != 'historical')
        {
            ($data['mode'] == 'sale') ? $data['success'] = $this->lang->line('sales_successfully') : $data['success'] = $this->lang->line('return_successfully');
            $this->load->view("sales/receipt", $data);
        }
        else
            $this->load->view("sales/receipt_historical", $data);
    }

    //HL (2014-01-15)
    function load_historical_dailyCash_bill($dailyCash_id)
    {
        $sales = array();
        $subsidary_id = $this->session->userdata('subsidary_id');
        $dailyCash = $this->Sale->get_dailyCash_info($dailyCash_id);
        $sales_finished = $this->Sale->get_sales_finished_per_daily_cash($dailyCash_id,$subsidary_id);
        //conformar el array que quiero mostrar a partir del resultado de -
        //la linea anterior

        foreach($sales_finished->result() as $sale)
        {
            $customer_id = $sale->customer_id;
            if ($customer_id != -1)
            {
                $cust_info = $this->Customer->get_info($customer_id);
                $customer = $cust_info->first_name . ' ' . $cust_info->last_name;
            }

            $sale_payments = $this->Sale->get_sale_payments($sale->sale_id);
            $sale_payments = $sale_payments->result_array();

            $totalpayments = 0;
            $sale_payments_types = "";

            foreach($sale_payments as $pay)
            {
                $sale_payments_types = ($sale_payments_types == "")?$pay["payment_type"]:$sale_payments_types . ',' . $pay["payment_type"];
                $totalpayments += $pay["payment_amount"];
            }

            $sale = array(($sale->sale_id)=>
            array(
                'billet_number'=>$sale->billet_number, //# boleta
                'customer'=>$customer,
                'sale_time'=>$sale->sale_time,
                'payment_types'=>$sale_payments_types,
                'total'=>$totalpayments
            )
            );

            //add to existing array
            $sales+=$sale;
        }

        $data['sales'] = $sales;

        $total = 0;
        foreach($sales as $sale)
        {
            $total += $sale['total'];
        }

        $data['total'] = $total;
        $data['receipt_title'] = $this->lang->line('sales_receipt');

        $data['transaction_time'] = $dailyCash->date_time;

        $emp_info = $this->Employee->get_info($dailyCash->employee_id);
        $data['employee'] = $emp_info->first_name . ' ' . $emp_info->last_name;

        $this->load->view("sales/close_daily_cash", $data);
    }

    //HL (2014-01-15)
    function load_historical_cycleCash_bill($cycleCash_id)
    {
        $total = 0;

        $daily_cashes = array();
        $subsidary_id = $this->session->userdata('subsidary_id');
        $cycleCash = $this->Sale->get_cycleCash_info($cycleCash_id);
        $daily = $this->Sale->get_dailyCash_by_cycleId($cycleCash_id,$subsidary_id);
        //conformar el array que quiero mostrar a partir del resultado de -
        //la linea anterior
        foreach($daily->result() as $daily_cash)
        {
            $employee_id = $daily_cash->employee_id;
            $emp_info = $this->Employee->get_info($employee_id);
            $employee = $emp_info->first_name . ' ' . $emp_info->last_name;

            $total += $daily_cash->total_amount;

            $daily_cash = array(($daily_cash->id_dailyCash)=>
            array(
                'employee'=>$employee,
                'sale_time'=>$daily_cash->date_time,
                'total'=>$daily_cash->total_amount
            )
            );

            //add to existing array
            $daily_cashes+=$daily_cash;
        }

        $data['daily_cashes'] = $daily_cashes;

        $data['total'] = $total;
        $data['receipt_title'] = $this->lang->line('sales_receipt');
        $data['transaction_time'] = $cycleCash->date_time;

        $emp_info = $this->Employee->get_info($cycleCash->employee_id);
        $data['employee'] = $emp_info->first_name . ' ' . $emp_info->last_name;

        $this->load->view("sales/close_cycle_cash", $data);
    }

    //Hector
    function close_daily_cash()
    {
        $sales = array();
        $subsidary_id = $this->session->userdata('subsidary_id');
        $sales_finished = $this->Sale->get_sales_finished_per_subsidary_cycle($subsidary_id);
        //conformar el array que quiero mostrar a partir del resultado de - 
        //la linea anterior

        $sub_cycle = $this->Subsidary->get_subsidary_cycle($subsidary_id);
        foreach($sales_finished->result() as $sale)
        {
            //if($sale->billet_number != 0){
            if( (strpos($sale->billet_number,$sub_cycle->count_cycles) == 0) )
            {
                $customer_id = $sale->customer_id;
                if ($customer_id != -1)
                {
                    $cust_info = $this->Customer->get_info($customer_id);
                    $customer = $cust_info->first_name . ' ' . $cust_info->last_name;
                }

                $sale_payments = $this->Sale->get_sale_payments($sale->sale_id);
                $sale_payments = $sale_payments->result_array();

                $totalpayments = 0;
                $sale_payments_types = "";

                foreach($sale_payments as $pay)
                {
                    $sale_payments_types = ($sale_payments_types == "")?$pay["payment_type"]:$sale_payments_types . ',' . $pay["payment_type"];
                    $totalpayments += $pay["payment_amount"];
                }

                $sale = array(($sale->sale_id)=>
                array(
                        'billet_number'=>$sale->billet_number, //# boleta
                        'customer'=>$customer,
                        'sale_time'=>$sale->sale_time,
                        'payment_types'=>$sale_payments_types,
                        'total'=>$totalpayments
                        )
                );

                //add to existing array
                $sales+=$sale;
            }
        }
        
        $data['sales'] = $sales;
        
        $total = 0;
        foreach($sales as $sale)
        { 
            $total += $sale['total'];
        }
        
        $data['total'] = $total;
        $data['receipt_title'] = $this->lang->line('sales_receipt');
        $data['transaction_time'] = date('m/d/Y h:i:s a');
        
        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $emp_info = $this->Employee->get_info($employee_id);
        $data['employee'] = $emp_info->first_name . ' ' . $emp_info->last_name;
       
        $this->load->view("sales/close_daily_cash", $data);
    }

    //Hector
    function complete_close_daily_cash($total)
    {
        if($total != 0)
        {
            $sales = array();
            $subsidary_id = $this->session->userdata('subsidary_id');
            $sales_finished = $this->Sale->get_sales_finished_per_subsidary_cycle($subsidary_id);
            //conformar el array que quiero mostrar a partir del resultado de -
            //la linea anterior

            $sub_cycle = $this->Subsidary->get_subsidary_cycle($subsidary_id);
            foreach($sales_finished->result() as $sale)
            {
                //if($sale->billet_number != 0){
                if( (strpos($sale->billet_number,$sub_cycle->count_cycles) == 0) )
                {
                    $sale = array(($sale->sale_id)=>
                    array(
                        'sale_id'=>$sale->sale_id //id
                    )
                    );

                    //add to existing array
                    $sales+=$sale;
                }
            }

            $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
            $this->Sale->save_dailyCash($employee_id,$total,$sales);
        }
        
        $this->_reload();
    }
    
    //Hector
    function close_cycle_cash()
    {
        $total = 0;
        
        $daily_cashes = array();
        $subsidary_id = $this->session->userdata('subsidary_id');
        $daily = $this->Sale->get_dailyCash_by_cycleNumber($subsidary_id);
        //conformar el array que quiero mostrar a partir del resultado de - 
        //la linea anterior
        foreach($daily->result() as $daily_cash)
        { 
            $employee_id = $daily_cash->employee_id;
            $emp_info = $this->Employee->get_info($employee_id);
            $employee = $emp_info->first_name . ' ' . $emp_info->last_name;

            $total += $daily_cash->total_amount;
        
            $daily_cash = array(($daily_cash->id_dailyCash)=>
            array(
                    'employee'=>$employee,
                    'sale_time'=>$daily_cash->date_time,
                    'total'=>$daily_cash->total_amount
                    )
            );

            //add to existing array
            $daily_cashes+=$daily_cash;
        }
        
        $data['daily_cashes'] = $daily_cashes;
        
        $data['total'] = $total;
        $data['receipt_title'] = $this->lang->line('sales_receipt');
        $data['transaction_time'] = date('m/d/Y h:i:s a');
        
        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $emp_info = $this->Employee->get_info($employee_id);
        $data['employee'] = $emp_info->first_name . ' ' . $emp_info->last_name;
       
        $this->load->view("sales/close_cycle_cash", $data);
    }
    
    //Hector
    function complete_close_cycle_cash($total)
    {
        if($total != 0)
        {
            $daily_cashes = array();
            $subsidary_id = $this->session->userdata('subsidary_id');
            $daily = $this->Sale->get_dailyCash_by_cycleNumber($subsidary_id);
            //conformar el array que quiero mostrar a partir del resultado de - 
            //la linea anterior
            foreach($daily->result() as $daily_cash)
            { 
                $daily_cash = array(($daily_cash->id_dailyCash)=>
                array(
                        'id_dailyCash'=>$daily_cash->id_dailyCash //id
                     )
                );

                //add to existing array
                $daily_cashes+=$daily_cash;
            }
            
            $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
            $this->Sale->save_cycleCash($employee_id,$total,$daily_cashes);
        }
        
        $this->_reload();
    }

    //Hector
    function view_calendar()
    {
        $data['lang'] = $this->Subsidary->get_language();
        $data['pDate'] = $this->sale_lib->get_dateFinish();
        $data['datecmp'] = date('Y-m-d H:i');
        
        $this->load->view("sales/calendar",$data);
    }
    
    //Hector
    function set_dispatch_date()
    {
        $dateFinish = $this->input->post("date");
        $this->sale_lib->set_dateFinish($dateFinish);
        $this->_reload();
    }

}

?>
