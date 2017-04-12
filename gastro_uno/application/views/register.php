<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/phppos.css" />
<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/phppos_print.css"  media="print"/>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>PHP Point Of Sale <?php echo $this->lang->line('register_new'); ?></title>
<script src="<?php echo base_url();?>js/jquery-1.2.6.min.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
</head>
<body>
<div id="content_area_wrapper">
<div id="content_area">
<?php echo form_open('register') ?>
<div id="config_wrapper">
<fieldset id="register_info">
<legend><?php echo $this->lang->line("register_new"); ?></legend>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('register_username').':', 'user',array('class'=>'wide2 required')); ?>
	<div class="form_field">
		<?php echo form_input(array(
		'name'=>'username', 
		'value'=>set_value('username'),
		'size'=>'20')); ?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('register_password').':', 'pass',array('class'=>'wide2 required')); ?>
	<div class="form_field">
		<?php echo form_password(array(
		'name'=>'password', 
		'value'=>set_value('password'),
		'size'=>'20')); ?>	
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('register_enterprise').':', 'enterprise',array('class'=>'wide2 required')); ?>
	<div class="form_field">
		<?php echo form_input(array(
		'name'=>'enterprise', 
		'value'=>set_value('enterprise'),
		'size'=>'20')); ?>
	</div>
</div>

<div class="field_row clearfix">	
<?php echo form_label($this->lang->line('register_subsidary').':', 'subsidary',array('class'=>'wide2')); ?>
	<div class="form_field">
		<?php echo form_input(array(
		'name'=>'subsidary', 
		'value'=>set_value('subsidary'),
		'size'=>'20')); ?>
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

<div id="new_button">
	<a class="submit_button float_right" title="<?php echo $this->lang->line('register_cancel'); ?>" href="<?php echo site_url('login');?>"><?php echo $this->lang->line('register_cancel'); ?></a>
</div>
</fieldset>
<div id="required_fields_message">
<?php echo validation_errors(); ?>
</div>
</div>
<?php
echo form_close();
?>
<div id="feedback_bar"></div>
</div>
</div>
<?php $this->load->view("partial/footer"); ?>
</body>
</html>