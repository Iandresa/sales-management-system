<?php
if (isset($error_message))
{
	echo '<h1 style="text-align: center;">'.$error_message.'</h1>';
	exit;
}
?>
<div id="receipt_wrapper">
    <div id='print'>
        <img width="50px" style="margin-left:370px" src="<?php echo base_url().'/images/logotipo/'.$this->lang->line('login_out'); ?>.png"
                 border="0"/>
        <div id="receipt_header" style="margin-left:50px">
		<div id="company_name"><?php echo $this->config->item('company'); ?></div>
		<div id="company_address"><?php echo nl2br($this->config->item('address')); ?></div>
		<div id="company_phone"><?php echo $this->config->item('phone'); ?></div>
		<div id="sale_receipt"><?php echo $receipt_title; ?></div>
		<div id="sale_time"><?php echo $transaction_time ?></div>
	</div>
	<div id="receipt_general_info" style="margin-left:50px">
		<?php if(isset($customer))
		{
		?>
			<div id="customer"><?php echo $this->lang->line('customers_customer').": ".$customer; ?></div>
		<?php
		}
		?>
		<!--<div id="sale_id"><?php echo $this->lang->line('sales_id').": ".$sale_id; ?></div>-->
		<div id="employee"><?php echo $this->lang->line('employees_employee').": ".$employee; ?></div>
        <?php if(isset($historical)) { ?>
            <div id="employee"><?php echo $this->lang->line('sales_bill_id').": ".$bill_number; ?></div>
        <?php } ?>
	</div>
       
          <br/>
	<table id="receipt_items" style="margin-left:10px">
	<tr>
	<!--<th style="width:25%;"><?php echo $this->lang->line('sales_item_number'); ?></th>-->
	<th style="width:25%;text-align:center;"><?php echo $this->lang->line('items_item'); ?></th>
	<th style="width:17%;text-align:center;"><?php echo $this->lang->line('common_price'); ?></th>
	<th style="width:16%;text-align:center;"><?php echo $this->lang->line('sales_quantity'); ?></th>
	<th style="width:16%;text-align:center;"><?php echo $this->lang->line('sales_discount'); ?></th>
	<th style="width:17%;text-align:right;"><?php echo $this->lang->line('sales_total'); ?></th>
	</tr>
	<?php
	foreach($cart as $line=>$item)
	{ 
	?>
            <tr>
                <!--<td><?php echo $item['item_number']; ?></td>-->
                <td style='text-align:center;'><span class='long_name'><?php echo $item['name']; ?></span> </td>
                <td style='text-align:center;'><?php echo to_currency($item['price']); ?></td>
                <td style='text-align:center;'><?php echo $item['quantity']; ?></td>
                <td style='text-align:center;'><?php echo $item['discount']; ?></td>
                <td style='text-align:right;'><?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></td>
            </tr>
            <tr style="height: 10px;"></tr>
            <tr>
                <th colspan="2" style="text-align:center;"><?php echo $this->lang->line('sales_description_abbrv'); ?></th>
                <th colspan="2" style="text-align:center;"><?php echo $this->lang->line('sales_serial'); ?></th>
            </tr>
	    <tr>
                <td colspan="2" style='text-align:center;'><?php echo $item['description']; ?></td>
                <td colspan="2" style='text-align:center;'><?php echo $item['serialnumber']; ?></td>
	    </tr>

	<?php
	}
	?>
	<tr>
	<td colspan="4" style='text-align:right;border-top:2px solid #000000;'><?php echo $this->lang->line('sales_sub_total'); ?></td>
	<td colspan="2" style='text-align:right;border-top:2px solid #000000;'><?php echo to_currency($subtotal); ?></td>
	</tr>

	<?php foreach($taxes as $name=>$value) { ?>
		<tr>
			<td colspan="4" style='text-align:right;'><?php echo "$name"; ?>:</td>
			<td colspan="2" style='text-align:right;'><?php echo to_currency($value); ?></td>
		</tr>
	<?php }; ?>

	<tr>
	<td colspan="4" style='text-align:right;'><?php echo $this->lang->line('sales_total'); ?></td>
	<td colspan="2" style='text-align:right'><?php echo to_currency($total); ?></td>
	</tr>

    <tr><td colspan="6">&nbsp;</td></tr>

	<?php
		foreach($payments as $payment_id=>$payment)
	{ ?>
		<tr>
		<td colspan="2" style="text-align:right;"><?php echo $this->lang->line('sales_payment'); ?></td>
		<td colspan="2" style="text-align:right;"><?php $splitpayment=explode(':',$payment['payment_type']); echo $splitpayment[0]; ?> </td>
		<td colspan="2" style="text-align:right"><?php echo to_currency( $payment['payment_amount'] * -1 ); ?>  </td>
	    </tr>
	<?php
	}
	?>

    <tr><td colspan="6">&nbsp;</td></tr>

	<tr>
		<td colspan="4" style='text-align:right;'><?php echo $this->lang->line('sales_change_due'); ?></td>
		<td colspan="2" style='text-align:right'><?php echo  to_currency($amount_change); ?></td>
	</tr>

	</table>

	<div id="sale_return_policy">
	<?php echo nl2br($this->config->item('return_policy')); ?>
	</div>
	<div id='barcode'>
	<?php echo "<img src='index.php?c=barcode&barcode=$sale_id&text=$sale_id&width=250&height=50' />"; ?>
	</div>
    
</div>
</div>
<div id="vinc1">
<?php 
if(!isset($historical))
{
    if($mode=='sale') 
    { 
        if($sale_id)
            echo anchor("sales/complete/$sale_id", "<div class='sb1' style='float:right;'><span>".$this->lang->line('cafeteria_complete_sale')."</span></div>", array('title'=>$this->lang->line('cafeteria_complete_sale'),'onclick'=>"return ".$this->lang->line('cafeteria_confirm_sale')."()"));
        else
            echo anchor("sales/complete/0", "<div class='sb1' style='float:right;'><span>".$this->lang->line('cafeteria_complete_sale')."</span></div>", array('title'=>$this->lang->line('cafeteria_complete_sale'),'onclick'=>"return ".$this->lang->line('cafeteria_confirm_sale')."()"));
    }
    else
    {
        if($sale_id)
            echo anchor("sales/complete/$sale_id", "<div class='small_button2' style='float:right;'><span>".$this->lang->line('sales_complete_return')."</span></div>", array('title'=>$this->lang->line('sales_complete_return'),'onclick'=>"return ".$this->lang->line('cafeteria_confirm_sale')."()"));
        else
            echo anchor("sales/complete/0", "<div class='small_button2' style='float:right;'><span>".$this->lang->line('sales_complete_return')."</span></div>", array('title'=>$this->lang->line('sales_complete_return'),'onclick'=>"return ".$this->lang->line('cafeteria_confirm_sale')."()"));
    }
}
?>
<script type="text/javascript" language="javascript">

function printNice()
{
    var ficha = $('#print').html();
    var ventimp = window.open(' ', 'popimpr');
    ventimp.document.write( ficha );
    ventimp.document.close();
    ventimp.focus();
    ventimp.print();
    ventimp.close();   
}
function print2()
{
    var win=null;
    var ficha = $('#print').html();
    
    win = window.open();
    self.focus();
    win.document.open();
    win.document.write('<'+'html'+'><'+'head'+'><'+'style'+'>');
    win.document.write('body, td { font-family: Verdana; font-size: 10pt;}');
    win.document.write('<'+'/'+'style'+'><'+'/'+'head'+'><'+'body'+'>');
    win.document.write('<input type="button" onClick="window.print()" value="<?php echo $this->lang->line('common_print_this');?>"/>');
    win.document.write('<p>'+ficha+'</p>');
    win.document.write('<'+'/'+'body'+'><'+'/'+'html'+'>');
    win.document.close();
    win.print();
    win.close();
}
    $(document).ready(function(){
        $('#printButton').click(function()
        {        
            var objBrowse = window.navigator;   
            if (objBrowse.appName == "Opera")                 
                 setTimeout('print2()', 2000);         
            else
                printNice();
        })     
});
</script> 

<a id="printButton" title="<?php echo $this->lang->line('sales_print_report'); ?>"><img src="images/print.jpg" alt="<?php echo $this->lang->line('sales_print_report'); ?>" border="0"/></a>
</div>