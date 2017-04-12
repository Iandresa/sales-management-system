<?php
echo form_open('enterprises/save_create_enterprise_config/'.$enterprise_id,array('id'=>'create_enterprise_form'));
?>
<input type="hidden" name="submited" value="yes"/>
<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>
<ul id="error_message_box"></ul>
<fieldset id="#config_info">
<legend><?php echo $this->lang->line("config_info"); ?></legend>

<div class="field_row clearfix">	
    <?php echo form_label($this->lang->line('register_enterprise').':', 'enterprise',array('class'=>'wide2 required')); ?>
    <div class="form_field">
        <?php echo form_input(array(
        'name'=>'enterprise', 
        'value'=>$name,
        'size'=>'20')); ?>
    </div>
</div>


<div class="field_row clearfix">	
    <?php echo form_label($this->lang->line('currency').':', 'enterprise',array('class'=>'wide2 required')); ?>
    <?php echo form_dropdown('currency', $this->Currency->get_all_array(true),
        $currency_id, "style='width:160px;'");
    ?>
</div>


<div class="field_row clearfix">	
    <?php echo form_label($this->lang->line('enterprises_permi_gr_reports').':','permi_gr_reports',array('class'=>'wide')); ?>
    <div class='form_field'>
        <?php echo form_checkbox(array(
            'name'=>'permi_gr_reports',
            'id'=>'permi_gr_reports',
            'value'=>'1',
            'checked'=>$gr_reports)
        );?>
    </div>
</div>

<div class="field_row clearfix">	
<label for="permi_hide_banners" class="wide"><?php echo $this->lang->line('enterprises_permi_hide_banners')?>:</label>	
<div class="form_field">
	<input type="checkbox" name="permi_hide_banners" value="0" unchecked="true" disabled="true">	
</div>
</div>

<div class="field_row clearfix">	
    <?php echo form_label($this->lang->line('enterprises_permi_uncomplete_sale').':','permi_uncomplete_sale',array('class'=>'wide')); ?>
        <div class='form_field'>
        <?php echo form_checkbox(array(
                'name'=>'permi_uncomplete_sale',
                'id'=>'permi_uncomplete_sale',
                'value'=>'1',
                'checked'=>$uncomplete_sale)
        );?>
</div>
    
<br /><br />
<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('enterprises_permi_delivery').':','permi_delivery',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_checkbox(array(
		'name'=>'permi_delivery',
		'id'=>'permi_delivery',
		'value'=>'1',
		'checked'=>$delivery)
	);?>	
</div>

<?php if($enterprise_id==-1)
{
?>
    <br /><br />
<div class="field_row clearfix">	
    <?php echo form_label($this->lang->line('enterprises_first_subsidary').':', 'subsidary',array('class'=>'wide2 required')); ?>
        <div class="form_field">
		<?php echo form_input(array(
		'name'=>'subsidary', 
		'value'=>set_value('subsidary'),
		'size'=>'20')); ?>
	</div>
</div>
<?php	
}
?>

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
	$('#create_enterprise_form').validate({
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
			enterprise: "required",
                        subsidary: "required",
                        currency:"required"
   		},
		messages: 
		{
                    enterprise: "<?php echo $this->lang->line('config_company_required'); ?>",  		
                    subsidary: "<?php echo $this->lang->line('config_subsidary_required'); ?>",
                    currency: "<?php echo $this->lang->line('currency_required_field'); ?>"
		}
	});
});
</script>