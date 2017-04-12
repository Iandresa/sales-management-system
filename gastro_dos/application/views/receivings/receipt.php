<?php //$this->load->view("partial/header"); ?>
<?php
//if (isset($error_message))
//{
//	echo '<h1 style="text-align: center;">'.$error_message.'</h1>';
//	exit;
//}
?>
<div id="receipt_wrapper">
     <div id='print'>         
           <img style="margin-left:650px" width="50px" src="<?php echo base_url().'/images/logotipo/'.$this->lang->line('login_out'); ?>.png"                  
                 border="0"/>
	<div id="receipt_header" style="margin-left:50px">
		<div id="company_name"><?php echo $this->config->item('company'); ?></div>
		<div id="company_address"><?php echo nl2br($this->config->item('address')); ?></div>
		<div id="company_phone"><?php echo $this->config->item('phone'); ?></div>
		<div id="sale_receipt"><?php echo $receipt_title; ?></div>
		<div id="sale_time"><?php echo $transaction_time ?></div>
                </br>
		<?php if(isset($supplier))
		{
		?>
			<div id="customer"><?php echo $this->lang->line('suppliers_supplier').": ".$supplier; ?></div>
		<?php
		}
		?>
		<div id="sale_id"><?php echo $this->lang->line('recvs_id').": ".$receiving_id; ?></div>
		<div id="employee"><?php echo $this->lang->line('employees_employee').": ".$employee; ?></div>
	</div>
         <br/><br/>
	<table id="receipt_items">
	<tr>
	<th style="width:50%;text-align:center;"><?php echo $this->lang->line('items_item'); ?></th>
	<th style="width:17%;text-align:center;"><?php echo $this->lang->line('common_price'); ?></th>
	<th style="width:16%;text-align:center;"><?php echo $this->lang->line('sales_quantity'); ?></th>
	<th style="width:16%;text-align:center;"><?php echo $this->lang->line('sales_discount'); ?></th>
	<th style="width:17%;text-align:right;"><?php echo $this->lang->line('sales_total'); ?></th>
	</tr>
	<?php
	foreach($cart as $item_id=>$item)
	{
	?>
		<tr>
		<td style='text-align:center;'><span class='long_name'><?php echo $item['name']; ?></span> </td>
		<td style='text-align:center;'><?php echo to_currency($item['price']); ?></td>
		<td style='text-align:center;'><?php echo $item['quantity']; ?></td>
		<td style='text-align:center;'><?php echo $item['discount']; ?></td>
		<td style='text-align:right;'><?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></td>
		</tr>
	    <tr>

	    <td colspan="2" align="center"><?php echo $item['description']; ?></td>
		<td colspan="2" ><?php echo $item['serialnumber']; ?></td>
		<td colspan="1"><?php echo '---'; ?></td>
	    </tr>
	<?php
	}
	?>	
	<tr>
	<td colspan="3" style='text-align:right;'><?php echo $this->lang->line('sales_total'); ?></td>
	<td colspan="2" style='text-align:right'><?php echo to_currency($total); ?></td>
	</tr>

	<tr>
	<td colspan="3" style='text-align:right;'><?php echo $this->lang->line('sales_payment'); ?></td>
	<td colspan="2" style='text-align:right'><?php echo $payment_type; ?></td>
	</tr>

	<?php if(isset($amount_change))
	{
	?>
		<tr>
		<td colspan="3" style='text-align:right;'><?php echo $this->lang->line('sales_amount_tendered'); ?></td>
		<td colspan="2" style='text-align:right'><?php echo to_currency($amount_tendered); ?></td>
		</tr>

		<tr>
		<td colspan="3" style='text-align:right;'><?php echo $this->lang->line('sales_change_due'); ?></td>
		<td colspan="2" style='text-align:right'><?php echo $amount_change; ?></td>
		</tr>
	<?php
	}
	?>
	</table>

	<div id="sale_return_policy">
	<?php echo nl2br($this->config->item('return_policy')); ?>
	</div>
	<div id='barcode'>
	<?php echo "<img src='index.php?c=barcode&barcode=$receiving_id&text=$receiving_id&width=250&height=50' />"; ?>
	</div>
         
       
     </div>
    
<script type="text/javascript" language="javascript">
  function printNice()

  {
      var ficha = $('#print').html();
      var ventimp = window.open(' ', 'popimpr');
      ventimp.document.write( ficha );
      ventimp.document.close();
      ventimp.print( );
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
	
        $('#printButton').click(function(){
            var objBrowse = window.navigator;   
            if (objBrowse.appName == "Opera")                 
                 setTimeout('print2()', 2000);         
            else
                printNice();
        });        
});
</script> 
<div id="printBox" style="float: left">
    <a id="printButton" title="<?php echo $this->lang->line('sales_print_report'); ?>">
        <img src="images/print.jpg" alt="<?php echo $this->lang->line('sales_print_report'); ?>" border="0"/>
    </a>
</div>

  <?php
  if(!isset($historical) || !$historical){
      if($mode=='receive') 
      {
               echo anchor("receivings/complete_final",
                "<div class='small_button2' style='float:right;margin-top:5px;'><span>".$this->lang->line('recvs_complete')."</span></div>",
                array('class'=>'none','title'=>$this->lang->line('recvs_complete'))); 
      }
      else
      {
          echo anchor("receivings/complete_final",
                "<div class='small_button2' style='float:right;margin-top:5px;'><span>".$this->lang->line('sales_complete_return')."</span></div>",
                array('class'=>'none','title'=>$this->lang->line('sales_complete_return')));
      }
  }
         ?>
</div>


<?php //$this->load->view("partial/footer"); ?>