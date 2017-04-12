<?php $this->load->view("partial/header"); ?>
<div id="page_title" style="margin-bottom:8px;">
<?php 
//echo $this->lang->line('reports_report_input');
switch($graph_header_name)
	{
		case 1:
			echo $this->lang->line('reports_graphical_summary_sales');
		break;
		case 2:
			echo $this->lang->line('reports_graphical_summary_categories');
		break;
		case 3:
			echo $this->lang->line('reports_graphical_summary_customers');
		break;
		case 4:
			echo $this->lang->line('reports_graphical_summary_suppliers');
		break;
		case 5:
			echo $this->lang->line('reports_graphical_summary_items');
		break;
		case 6:
			echo $this->lang->line('reports_graphical_summary_employees');
		break;
		case 7:
			echo $this->lang->line('reports_graphical_summary_taxes');
		break;
		case 8:
			echo $this->lang->line('reports_graphical_summary_discounts');
		break;
		case 9:
			echo $this->lang->line('reports_graphical_summary_payments');
		break;
	} 

//switch($graph_header_name)
//{
//	case basename($_SERVER[PHP_SELF]):
//		echo basename($_SERVER[PHP_SELF]);//$this->lang->line('reports_sales_summary_report');
//	break;
//}
?>
</div>
<?php
if(isset($error))
{
	echo "<div class='error_message'>".$error."</div>";
}
?>
<div align="center">
	<?php echo form_label($this->lang->line('reports_date_range'), 'report_date_range_label', array('class'=>'required')); ?>
	<div id='report_date_range_simple'>
		<input type="radio" name="report_type" id="simple_radio" value='simple' checked='checked'/>
		<?php echo form_dropdown('report_date_range_simple',$report_date_range_simple, '', 'id="report_date_range_simple"'); ?>
	</div>
	
	<div id='report_date_range_complex'>
		<input type="radio" name="report_type" id="complex_radio" value='complex' />
		<?php echo form_dropdown('start_month',$months, $selected_month, 'id="start_month"'); ?>
		<?php echo form_dropdown('start_day',$days, $selected_day, 'id="start_day"'); ?>
		<?php echo form_dropdown('start_year',$years, $selected_year, 'id="start_year"'); ?>
		-
		<?php echo form_dropdown('end_month',$months, $selected_month, 'id="end_month"'); ?>
		<?php echo form_dropdown('end_day',$days, $selected_day, 'id="end_day"'); ?>
		<?php echo form_dropdown('end_year',$years, $selected_year, 'id="end_year"'); ?>
	</div>
<?php
echo form_button(array(
	'name'=>'generate_report',
	'id'=>'generate_report',
	'content'=>$this->lang->line('common_submit'),
	'class'=>'submit_button')
);
?>
    </br></br>
    <div>
        <label class="required">
            <?php echo $this->lang->line('reports_warning_msg1'); ?>
            <a href="https://get.adobe.com/es/flashplayer/otherversions/" target="_blank"><?php echo "Flash"; ?></a>
            <?php echo $this->lang->line('reports_warning_msg1_cont'); ?>
        </label>		
    </div>
</div>

<div class="clearfix" style="margin-bottom:140px">&nbsp;</div>

<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	$("#generate_report").click(function()
	{		
		if ($("#simple_radio").attr('checked'))
		{
			window.location = window.location+'/'+$("#report_date_range_simple option:selected").val();
		}
		else
		{
			var start_date = $("#start_year").val()+'-'+$("#start_month").val()+'-'+$('#start_day').val();
			var end_date = $("#end_year").val()+'-'+$("#end_month").val()+'-'+$('#end_day').val();
	
			window.location = window.location+'/'+start_date + '/'+ end_date;
		}
	});
	
	$("#start_month, #start_day, #start_year, #end_month, #end_day, #end_year").click(function()
	{
		$("#complex_radio").attr('checked', 'checked');
	});
	
	$("#report_date_range_simple").click(function()
	{
		$("#simple_radio").attr('checked', 'checked');
	});
	
});
</script>