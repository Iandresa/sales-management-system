<?php $this->load->view("partial/header_login"); ?>
</head>
<body>
<?php $this->load->view("partial/menu_login"); ?>

<!--<h1>IANDRESA</h1>-->

<?php

if(!isset($lang))$lang='english';

 echo form_open('login')


?>
<div id="container">
<?php echo validation_errors(); ?>
	<div id="top">
	<?php
	echo $this->lang->line('login_login'); ?>
	</div>
	<div id="login_form">
		<div id="welcome_message">
		<?php echo $this->lang->line('login_welcome_message'); ?>
		</div>
		
		<div class="form_field_label"><?php echo $this->lang->line('login_username'); ?>: </div>
		<div class="form_field">
		<?php echo form_input(array(
		'name'=>'username', 
		'value'=>set_value('username'),
		'size'=>'20')); ?>
		</div>

		<div class="form_field_label"><?php echo  $this->lang->line('login_password');?>: </div>
		<div class="form_field">
		<?php echo form_password(array(
		'name'=>'password', 
		'value'=>set_value('password'),
		'size'=>'20')); ?>
		
		</div>
		
		<div id="submit_button">
		<?php echo form_submit('loginButton',$this->lang->line('login_go'), "style='width:30px'"); ?>
		</div>
                
               
                    <div id="pass_recover">
                    <?php 
                        echo anchor("login/recover_User_And_Password/$lang",$this->lang->line('login_pass_recover'),
array( 'border'=>0));                   
//array('class'=>'thickbox none', 'border'=>0));
                    ?>
                    </div>
               

	</div>
</div>
<?php echo form_close(); ?>

<!-- begin EXTERIOR -->
<div id="TODO">

<?php
	$cantidad_imagenes = count($imagenes);
	
	if($cantidad_imagenes > 0)
	{
?>

	<!-- begin componente -->
        <div id="slideViewer" class="svw">
            <ul>
            <?php
                foreach($imagenes as $im){
            ?>
                   <li>                   
            <?php  
                $openLink = $this->Adviser->get_campaign_link($im);               
                echo $openLink;
               // echo "<img border='0' alt='banner' height='".$this->config->item('banner_login_height')."' width='".$this->config->item('banner_login_width')."' src='".base_url()."images/banners_pics/".$im['image_large']."'/>";
                echo "<img  border='0' alt='banner' src='".base_url()."images/banners_pics/".$im['image_large']."'/>";
                echo "</a>";
            ?>                
                   </li>
            <?php  }?>	
        </div>		
	<!-- end componente -->
	
	<?php 
	} 
	?>
            </ul>
       </div>
<!-- end EXTERIOR -->
</body>
</html>
