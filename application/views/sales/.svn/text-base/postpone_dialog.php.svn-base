<div id="required_fields_message">
<?php echo $this->lang->line('common_fields_required_message'); ?>
</div>
<ul id="error_message_box"></ul>
<?php
echo form_open('sales/add_postpone',array('id'=>'postpone_form'));
?>
<fieldset id="item_basic_info">
<legend><?php echo $this->lang->line("sales_postpone_info"); ?></legend>


<div class="field_row clearfix">
<?php echo form_label($this->lang->line('sales_postpone_name').':', 'name',array('class'=>'required wide','title'=>$this->lang->line('sales_postpone_name'))); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'name',
		'id'=>'name',
		'value'=>''),'',"title='{$this->lang->line('sales_postpone_name')}'");?>
	</div>
</div>
<?php
echo form_submit(array(
	'name'=>'submit',
	'id'=>'submit',
	'value'=>$this->lang->line('common_submit'),
	'class'=>'submit_button float_right')
);
?>
</fieldset>
<?php
echo form_close();
?>

<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	$('#postpone_form').validate(
        {
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules:
		{
			name:"required"                       

   		},
		messages:
		{
			name:"<?php echo $this->lang->line('sales_postpone_name_required'); ?>"
                }
	});
        $('#TB_window').css('top','40%').css('height','220px');//para q me salga arriba
});
 
 
 

</script>
