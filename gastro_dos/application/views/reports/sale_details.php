<div id='print'>
<div style="margin-left: 330px;">
    <img width="60px" src="images/logotipo/<?php echo $this->lang->line('login_out'); ?>.png" 
         onmouseover="this.src='images/logotipo/<?php echo $this->lang->line('login_over'); ?>.png'" 
         onmouseout="this.src='images/logotipo/<?php echo $this->lang->line('login_out'); ?>.png'" 
         border="0">
</div>
<div id="page_title" style="margin-top:30px;margin-bottom:30px;">
    <?php echo $title ?>
</div>
<div id="page_subtitle" style="margin-bottom:15px;"><?php echo $subtitle ?></div>
<div id="table_holder">
    <table class="tablesorter report" id="sortable_table">
        <thead style="text-align:center;">
            <tr>
                <?php foreach ($headers as $header) { ?>
                <th><?php echo $header; ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody style="text-align:center;">
            <?php foreach ($data as $row) { ?>
            <tr>
                <?php foreach ($row as $cell) { ?>
                <td><?php echo $cell; ?></td>
                <?php } ?>
            </tr>
            <?php }
                if(isset($summary_data))
                echo $summary_data;
            ?>
        </tbody>
    </table>
</div>

<!--<div id="report_summary1">
<?php foreach($summary_data as $name=>$value) { ?>
	<div class="summary_row1"><?php echo $this->lang->line('reports_'.$name). ': '.to_currency($value); ?></div>
<?php }?>
</div>-->
</div>
<div id="print_details">
<a id="printButton" title="<?php echo $this->lang->line('sales_print_report'); ?>"><img src="images/print.jpg" alt="<?php echo $this->lang->line('sales_print_report'); ?>" border="0"/></a>
</div>

<script type="text/javascript" language="javascript">
function init_table_sorting()
{
	//Only init if there is more than one row
	if($('.tablesorter tbody tr').length >1)
	{
		$("#sortable_table").tablesorter(); 
	}
}

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
$(document).ready(function()
{
	init_table_sorting();
        $('#printButton').click(function(){
            var objBrowse = window.navigator;   
            if (objBrowse.appName == "Opera")                 
                 setTimeout('print2()', 2000);         
            else
                printNice();
        });        
});
</script>

