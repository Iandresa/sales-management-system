<?php

require_once ("secure_area.php");

class Receivings extends Secure_area
{

    function __construct()
    {
        parent::__construct('receivings');
        $this->load->library('receiving_lib');
        //force_ssl();
    }

    function index()
    {
        $this->_reload();
    }

    function item_search()
    {
        $suggestions = $this->Item->get_item_search_suggestions($this->input->post('q'), $this->input->post('limit'));
        echo implode("\n", $suggestions);
    }

    function supplier_search()
    {
        $suggestions = $this->Supplier->get_suppliers_search_suggestions($this->input->post('q'), $this->input->post('limit'));
        echo implode("\n", $suggestions);
    }

    function select_supplier()
    {
        $supplier_id = $this->input->post("supplier");
        $this->receiving_lib->set_supplier($supplier_id);
        $this->_reload();
    }
    
    function receiving_search()
    {
        $suggestions = $this->Receiving->get_receiving_search_suggestions($this->input->post('q'), $this->input->post('limit'));
        echo implode("\n", $suggestions);
    }
    
    function select_receiving()
    {
        $receiving_id = $this->input->post("receiving");
        $this->_load_historical_view($receiving_id);
    }
    
    function show_all()
    {
        $this->_load_historical_view();
    }

    function change_mode()
    {
        $this->receiving_lib->clear_all(); //HECTOR
        $mode = $this->input->post("mode");
        $this->receiving_lib->set_mode($mode);
        $this->_reload();
    }

    function add($isnewitem = false)
    {
        $data = array();
        $mode = $this->receiving_lib->get_mode();
        $item_id_or_number_or_receipt = $this->input->post("item");
        $quantity = $mode == "receive" ? 1 : -1;

        if ($this->receiving_lib->is_valid_receipt($item_id_or_number_or_receipt) && $mode == 'return')
        {
            $this->receiving_lib->return_entire_receiving($item_id_or_number_or_receipt);
        }
        elseif (!$this->receiving_lib->add_item($item_id_or_number_or_receipt, $quantity))
        {    
            $data['error'] = $this->lang->line('recvs_unable_to_add_item');
        }
        
        if($this->input->post("directadd") != "yes")
            $data['message'] = $this->lang->line("recvs_itemadded");
        $this->_reload($data);
    }

    function edit_item($item_id)
    {
        $data = array();

        $mode = $this->receiving_lib->get_mode();//HL 2013-04-14

        $this->form_validation->set_rules('price', 'lang:items_price', 'required|numeric');
        $this->form_validation->set_rules('quantity', 'lang:items_quantity', 'required|numeric');//HL (2014-01-03) antes: |integer
        $this->form_validation->set_rules('discount', 'lang:items_discount', 'required|numeric');//HL (2014-01-03) antes: |integer

        $description = $this->input->post("description");
        $serialnumber = $this->input->post("serialnumber");
        $price = $this->input->post("price");
        $quantity = $this->input->post("quantity");
        $discount = $this->input->post("discount");

        if ($this->form_validation->run() != FALSE)
        {
            if(($mode == "receive" && $quantity <= 0) || ($mode == "return" && $quantity >= 0) ||
               ($price <= 0) || ($discount < 0) )//HL (2013-05-27)
            {
                $data['error'] = $this->lang->line('sales_error_editing_item2');
            }
            else
                $this->receiving_lib->edit_item($item_id, $description, $serialnumber, $quantity, $discount, $price);
        }
        else
        {
            $data['error'] = $this->lang->line('sales_error_editing_item2');
        }

        $this->_reload($data);
    }

    function delete_item($item_number)
    {
        $this->receiving_lib->delete_item($item_number);
        $this->_reload();
    }

    function delete_supplier()
    {
        $this->receiving_lib->delete_supplier();
        $this->_reload();
    }

    function complete()
    {
        $data['cart'] = $this->receiving_lib->get_cart();
        $data['total'] = $this->receiving_lib->get_total();
        $data['receipt_title'] = $this->lang->line('recvs_receipt');
        $data['transaction_time'] = date('m/d/Y h:i:s a');
        $supplier_id = $this->receiving_lib->get_supplier();
        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $comment = $this->input->post('comment');
        $emp_info = $this->Employee->get_info($employee_id);
        $payment_type = $this->input->post('payment_type');
        $data['payment_type'] = $this->input->post('payment_type');
        $data['mode'] = $this->receiving_lib->get_mode();//HECTOR

        if ($this->input->post('amount_tendered'))
        {
            $data['amount_tendered'] = $this->input->post('amount_tendered');
            $data['amount_change'] = to_currency($data['amount_tendered'] - round($data['total'], 2));
        }
        $data['employee'] = $emp_info->first_name . ' ' . $emp_info->last_name;

        if ($supplier_id != -1)
        {
            $suppl_info = $this->Supplier->get_info($supplier_id);
            $data['supplier'] = $suppl_info->first_name . ' ' . $suppl_info->last_name;
        }

        //Ariel:
        $data["supplier_id"] = $supplier_id;
        $data["employee_id"] = $employee_id;
        $data["comment"] = $comment;
        //-----

        $this->session->set_userdata("recv", $data);
        //$this->receiving_lib->clear_all();//HECTOR (2012-12-03)


        echo json_encode(array("id" => "s"));
    }

    function receipt($receiving_id = null)
    {
        if ($receiving_id)
        {
            $data = $this->session->userdata("recv");
        }
        else
        {
            $receiving_info = $this->Receiving->get_info($receiving_id)->row_array();
            $this->receiving_lib->copy_entire_receiving($receiving_id);

            $data['cart'] = $this->receiving_lib->get_cart();
            $data['total'] = $this->receiving_lib->get_total();
            $data['receipt_title'] = $this->lang->line('recvs_receipt');
            $data['transaction_time'] = date('m/d/Y h:i:s a', strtotime($receiving_info['receiving_time']));
            $supplier_id = $this->receiving_lib->get_supplier();
            $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
            $emp_info = $this->Employee->get_info($employee_id);
            $data['payment_type'] = $receiving_info['payment_type'];
            $data['mode'] = $this->receiving_lib->get_mode();//HECTOR
            
            if ($amount_tendered && gettype(strpos($amount_tendered, "random")) != "integer")
            {
                $data['amount_tendered'] = $amount_tendered;
                $data['amount_change'] = to_currency($data['amount_tendered'] - round($data['total'], 2));
            }
            $data['employee'] = $emp_info->first_name . ' ' . $emp_info->last_name;

            if ($supplier_id != -1)
            {
                $supplier_info = $this->Supplier->get_info($supplier_id);
                $data['supplier'] = $supplier_info->first_name . ' ' . $supplier_info->last_name;
            }
        }
        
        $data['receiving_id'] = 'RECV ' . $receiving_id;
        $this->load->view("receivings/receipt", $data);
        //$this->receiving_lib->clear_all();//HECTOR (2012-12-03)
    }

    function complete_final()
    {
        //SAVE receiving to database
        $data = $this->session->userdata("recv");
        $receiving_id = $this->Receiving->save($data['cart'], $data["supplier_id"], $data["employee_id"], $data["comment"], $data["payment_type"], $data["mode"]);

        $this->session->unset_userdata("recv");
        $this->receiving_lib->clear_all();//HECTOR (2012-12-03)
        $this->_reload();
    }

    function _reload($data=array())
    {
        if ($this->receiving_lib->get_mode() == "historical")
        {
            $this->_load_historical_view();
            return;
        }
        
        $person_info = $this->Employee->get_logged_in_employee_info();
        $data['cart'] = $this->receiving_lib->get_cart();
        $data['modes'] = array('receive' => $this->lang->line('recvs_receiving'), 'return' => $this->lang->line('recvs_return'),'historical' => $this->lang->line('sales_historical'));
        $data['mode'] = $this->receiving_lib->get_mode();
        $data['total'] = $this->receiving_lib->get_total();
        $data['items_module_allowed'] = $this->Employee->has_permission('items', $person_info->person_id);
        $data['payment_options'] = array(
            $this->lang->line('sales_cash') => $this->lang->line('sales_cash'),
            $this->lang->line('sales_check') => $this->lang->line('sales_check'),
            $this->lang->line('sales_debit') => $this->lang->line('sales_debit'),
            $this->lang->line('sales_credit') => $this->lang->line('sales_credit'),
            $this->lang->line('sales_transfer') => $this->lang->line('sales_transfer')
        );

        $supplier_id = $this->receiving_lib->get_supplier();
        if ($supplier_id != -1)
        {
            $info = $this->Supplier->get_info($supplier_id);
            $data['supplier'] = $info->first_name . ' ' . $info->last_name;
        }
        $this->load->view("receivings/receiving", $data);
    }

    function cancel_receiving()
    {
        //$this->load->view("receivings/receipt",$data);
        $this->receiving_lib->clear_all();
        $this->_reload();
    }
    
      //Ariel:
    function _load_historical_view($receiving_id = -1)
    {
        //Ariel: SOlo para volver a la vista Recv, cuando todo este bien borrar esta linea
        $this->receiving_lib->set_mode("historical");

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

        if($receiving_id != -1)
            $receivings = $this->Receiving->get_receivings_finished_per_id($receiving_id,$subsidary_id);
        else
            $receivings = $this->Receiving->get_receivings_finished_per_subsidary($subsidary_id);

        $data['controller_name'] = strtolower($this->uri->segment(1));
        //$data['form_width'] = $this->get_form_width();
        $data['manage_table'] = get_receivingshistorical_manage_table($receivings);
        
        $data['modes'] = array('receive' => $this->lang->line('recvs_receiving'), 'return' => $this->lang->line('recvs_return'),'historical' => $this->lang->line('sales_historical'));
        $data['mode'] = $this->receiving_lib->get_mode();
        
        $this->load->view('receivings/historical', $data);
    }
    
     //Ariel
    function load_historical_receiving_bill($receiving_id)
    {
       
        $data['receiving_id'] = $receiving_id;
        $recv = $this->Receiving->get_info($receiving_id);
        $recv = $recv->row_array();
        
        $subtotal = 0;
        
        $items = $this->Receiving->get_receiving_items($receiving_id);
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
        
        
        
        
        $data['total'] = $subtotal;
        $data['receipt_title'] = $this->lang->line('sales_receipt');
        $data['transaction_time'] = date("d/M/Y h:i:s a", strtotime($recv["receiving_time"]));
        $supplier_id = $recv['supplier_id'];
        $emp_info = $this->Employee->get_info($recv["employee_id"]);

        //Alain Multiple payments
//        $receiving_payments = $this->Receiving->get_receiving_payments($receiving_id);
//        $receiving_payments = $receiving_payments->result_array();
//     
//        $totalpayments = 0;
//        foreach($receiving_payments as $pay)
//        {
//            $totalpayments += $pay["payment_amount"];
//        }
//        $data['payments'] = $receiving_payments;
        
        //$data['amount_change'] = $totalpayments - $subtotal;
       
        $data['employee'] = $emp_info->first_name . ' ' . $emp_info->last_name;
        if ($supplier_id > 0)
        {
            $cust_info = $this->Customer->get_info($supplier_id);
            $data['customer'] = $cust_info->first_name . ' ' . $cust_info->last_name;
        }

        $data['payment_type'] = $recv['payment_type'];
        $data['historical'] = true;
        
        $this->load->view("receivings/receipt", $data);
    }

}

?>