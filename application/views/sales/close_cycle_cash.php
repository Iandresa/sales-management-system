<?php
if (isset($error_message))
{
	echo '<h1 style="text-align: center;">'.$error_message.'</h1>';
	exit;
}
?>
<div id="receipt_wrapper">
    <div id='print'>
        <img width="50px" style="margin-left:360px" src="<?php echo base_url().'/images/logotipo/'.$this->lang->line('login_out'); ?>.png"                  
                 border="0"/>
        <div id="receipt_header" style="margin-left:50px">
		<div id="company_name"><?php echo $this->config->item('company'); ?></div>
		<div id="company_address"><?php echo nl2br($this->config->item('address')); ?></div>
		<div id="company_phone"><?php echo $this->config->item('phone'); ?></div>
		<div id="sale_receipt"><?php echo $receipt_title; ?></div>
		<div id="sale_time"><?php echo $transaction_time ?></div>
	</div>
	<div id="receipt_general_info" style="margin-left:50px">
		<div id="employee"><?php echo $this->lang->line('employees_employee').": ".$employee; ?></div>
	</div>
       
        <br/>
        <table id="receipt_items" style="margin-left:10px">
	<tr>
	<th style="width:35%;text-align:center;"><?php echo $this->lang->line('employees_employee'); ?></th>
	<th style="width:35%;text-align:center;"><?php echo $this->lang->line('sales_sale_time'); ?></th>
	<th style="width:30%;text-align:right;"><?php echo $this->lang->line('sales_total'); ?></th>
	</tr>
	<?php
	foreach($daily_cashes as $daily_cash)
	{ 
	?>
            <tr>
                <td style='text-align:center;'><?php echo $daily_cash['employee']; ?></td>
                <td style='text-align:center;'><?php echo $daily_cash['sale_time']; ?></td>
                <td style='text-align:right;'><?php echo to_currency($daily_cash['total']); ?></td>
            </tr>
            <tr style="height: 10px;"></tr>
	<?php
	}
	?>
        <tr><td colspan="3">&nbsp;</td></tr>
	<tr>
	<td colspan="2" style='text-align:right;border-top:2px solid #000000;'><?php echo $this->lang->line('sales_total'); ?></td>
	<td colspan="1" style='text-align:right;border-top:2px solid #000000;'><?php echo to_currency($total); ?></td>
	</tr>
        <tr><td colspan="3">&nbsp;</td></tr>
	</table>
</div>
</div>
<div id="vinc1">
<?php 
    if($daily_cashes)
        echo anchor("sales/complete_close_cycle_cash/$total", "<div class='sb1' style='float:right;'><span>".$this->lang->line('sales_close_cycle_cash')."</span></div>", array('title'=>$this->lang->line('sales_close_cycle_cash')));
    else
        echo "<a id='bComplete' title='".$this->lang->line('sales_close_cycle_cash')."'><div class='sb1' style='float:right;'><span>".$this->lang->line('sales_close_cycle_cash')."</span></div></a>";    
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

<script language="JavaScript" type="text/javascript">
    $("#bComplete").click(function()
    {
         alert("<?php echo $this->lang->line("sales_not_cycle_cash"); ?>");
    });
</script>

<a id="printButton" title="<?php echo $this->lang->line('sales_print_report'); ?>"><img src="images/print.jpg" alt="<?php echo $this->lang->line('sales_print_report'); ?>" border="0"/></a>
</div>
