<?php 
//OJB: Check if for excel export process
if($export_excel == 1){
	ob_start();
	$this->load->view("partial/header_excel");
}else{
	$this->load->view("partial/header");
} 
?>
<div id="page_title" style="margin-bottom:8px;"><?php echo $title ?></div>
<div id="page_subtitle" style="margin-bottom:8px;"><?php echo $subtitle ?></div>
<div id="table_holder">
	<table class="tablesorter report" id="sortable_table">
		<thead>
			<tr>
				<?php foreach ($headers as $header) { ?>
				<th><?php echo $header; ?></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($data as $row) { ?>
			<tr>
				<?php foreach ($row as $cell) { ?>
				<td><?php echo $cell; ?></td>
				<?php } ?>
			</tr>
			<?php } 
//                            if(isset($summary_data))
//                                echo $summary_data;
                        ?>
			
		</tbody>
                <tfoot>                    
                    <?php if($report_data) { ?>
			<tr>
				<td colspan="8"><div class='warning_message' style='padding:7px;'><?php echo $this->lang->line('common_no_data_to_display'); ?></div></td>
			</tr>
			<?php }?>
                    <?php
                        if(isset($summary_data))
                            echo $summary_data;
                    ?>
                </tfoot>
	</table>
</div>
<!--<div id="report_summary">
<?php foreach($summary_data as $name=>$value) { ?>
	<div class="summary_row"><?php echo $this->lang->line('reports_'.$name). ': '.to_currency($value); ?></div>
<?php }?>
</div>-->
</br>        
<div style="text-align: center;">
    <img width="75px" src="images/logotipo/<?php echo $this->lang->line('login_out'); ?>.png" 
         onmouseover="this.src='images/logotipo/<?php echo $this->lang->line('login_over'); ?>.png'" 
         onmouseout="this.src='images/logotipo/<?php echo $this->lang->line('login_out'); ?>.png'" 
         border="0">
</div>
<div class="clearfix" style="margin-bottom:<?php echo $margin.'px'; ?>">&nbsp;</div>
<?php 
if($export_excel == 1){
	$this->load->view("partial/footer_excel");
	$content = ob_end_flush();
	
	$filename = trim($filename);
	$filename = str_replace(array(' ', '/', '\\'), '', $title);
	$filename .= "_Export.xls";
	header('Content-type: application/ms-excel');
	header('Content-Disposition: attachment; filename='.$filename);
	echo $content;
	die();
	
}else{
	$this->load->view("partial/footer"); 
?>

<script type="text/javascript" language="javascript">
function init_table_sorting()
{
	//Only init if there is more than one row
	if($('.tablesorter tbody tr').length >1)
	{                
                $("#sortable_table").tablesorter( {sortInitialOrder: 'desc',sortList: [[0,0]]} ); //ecp para ordenar al cargar la página, la tabla por la columna 1 es orden descendente, fue necesario para el caso de separar las ventas de lso pedidos en el reporte "resumen de ventas""
	}
}
$(document).ready(function()
{
	init_table_sorting();
});
</script>
<?php 
} // end if not is excel export 
?>