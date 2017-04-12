<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<base href="<?php echo base_url();?>" />

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>IANDRESA Sale System <?php echo $this->lang->line('register_new'); ?></title>

<link rel="stylesheet" rev="stylesheet" href="css/phppos.css" />
<link rel="stylesheet" rev="stylesheet" href="css/phppos_print.css"  media="print"/>
<link rel="stylesheet" rev="stylesheet" href="css/login.css" />
<link rel="stylesheet" rev="stylesheet" href="css/index.css" />

<link rel="shortcut icon" href="images/icos/iandresa.ico" type="image/x-icon" />

<script src="js/jquery-1.2.6.min.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
</head>
<body>
<?php // echo 'lang='.$lang.', username='.$username.', validNickname='.$validNickname.', mailExist='.$mailExist.', mail='.$mail.', useMail='.$useMail.', question='.$question.', answer='.$answer.', questionExist='.$questionExist.', success='.$success.', newPassword='.$newPassword?> 


<?php $this->load->view("partial/menu_login"); ?>

<?php if(!isset($lang))$lang='english'; 
	$showSubmit=true;
?>

<div id="content_area_wrapper">
    
<div id="content_area">
<?php
    echo form_open("login/generate_Password/".$lang,array('id'=>'recover_form','name'=>'recover_form'));
?>
<div id="config_wrapper">    
<fieldset id="register_info"/>
<legend><?php echo $this->lang->line("login_recover_user_pass_title"); ?></legend>        

<div id="required_fields_message"><?php 
 if($validNickname=="1") 
 	if($success=="1")
		echo $this->lang->line('login_recover_pass_recover');
	else
 		echo $this->lang->line('login_recover_user_exist');
 else  
 	echo $this->lang->line('common_fields_required_message');
 ?></div>
<ul id="error_message_box"></ul>

<div class="field_row clearfix">
    <span 
<?php 
$showUsername=TRUE;
if($validNickname=="NULL") 
  	echo ">".$this->lang->line('login_recover_enter_user'); 
elseif($validNickname=="1") 
{	$showUsername=FALSE;
	if($useMail=="1" || $useMail)
	{		
	    if($mailExist=="1")	
		{
			$showSubmit=FALSE;
			echo ">".$this->lang->line('login_recover_OK_mail').$mail; 
		}		
	    elseif($mailExist=="0")	
		{
			$showSubmit=FALSE;
			echo " class=error2>".$this->lang->line('login_recover_valid_user_whit_NO_mail');
		}				
	}
	else
	{
		if($success=="1")
		{
			//contesto bien la pregunta secreta
			$showSubmit=FALSE;
			echo ">".$this->lang->line('login_username').': '.$username."     ".$this->lang->line('login_password').': '.$newPassword;
		}
		else
		{
			if($questionExist=="0")
			{
				$showSubmit=FALSE;
				echo " class=error2>".$this->lang->line('login_recover_valid_user_whit_NO_question');
			}	
			else echo " class=error2>".$this->lang->line('login_recover_invalid_answer');//try again pregunta secreta
		}
	}
}
else echo " class=error2>".$this->lang->line('login_recover_invalid_user');
    ?></span>   
</div>

<?php if($validNickname!='1'){ ?>
	<div class="field_row clearfix">
	<?php echo form_label($this->lang->line('login_recover_use_mail').':', 'useMail'); ?>
				 <div class="form_field">		
				<?php echo form_checkbox(array(
						'name'=>'useMail', 
						'id'=>'useMail',
						'value'=>1,
						'checked'=>$useMail,
						'size'=>'5')); ?>
				</div>
	</div>
<?php }else {echo form_hidden('useMail',$useMail);} ?>

    <?php if($showUsername)	{?>	
<div class="field_row clearfix">	
	<?php echo form_label($this->lang->line('login_username').':', 'username',array('class'=>'required')); ?>
            <div class="form_field">
            <?php echo form_input(array(
                    'name'=>'username',
                    'id'=>'username',
                    'value'=>$username)
            );?>
            </div>
</div>
<?php }else {echo form_hidden('username',$username);} ?>

<?php if($question && $question!="NULL" && $success!='1') { ?>
	<div class="field_row clearfix" id='div1'>
	<?php echo form_label($this->lang->line('login_secret_question').':', 'username'); ?>				
				<?php echo $question; ?>			
	</div>
	<div class="field_row clearfix" id='div2'>	
	    <?php
		echo form_label($this->lang->line('login_recover_answer').':', 'username',array('class'=>'required')); ?>
	            <div class="form_field">
	            <?php		
					echo form_input(array(
		                    'name'=>'answer',
		                    'id'=>'answer',
		                    'value'=>$answer,						
		            ));
				?>
	            </div>
	</div>
<?php } ?>   
    <?php	
   if($showSubmit) echo form_submit(array(
            'name'=>'submit',
            'id'=>'submit',
            'value'=>$this->lang->line('common_submit'),
            'class'=>'submit_button float_right')
    );
    ?>
</div>
</div>

<?php 
echo form_close();
?>
<!--
<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	$('#useMail').click(function()
    {	
		if (this.checked)
			$('#div1,#div2').hide();
		else
			$('#div1,#div2').show();
		    	
    });
});
</script>
-->
</div> 
</body>