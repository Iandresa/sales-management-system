<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<base href="<?php echo base_url();?>" />
<title>IANDRESA Sale System <?php echo $this->lang->line('login_login'); ?></title>

<link rel="shortcut icon" href="images/icos/iandresa.ico" type="image/x-icon" />

<link rel="stylesheet" rev="stylesheet" href="css/phppos.css" />
<link rel="stylesheet" rev="stylesheet" href="css/phppos_print.css"  media="print"/>
<link rel="stylesheet" rev="stylesheet" href="css/login.css" />
<link rel="stylesheet" rev="stylesheet" href="css/index.css" />	
<link rel="stylesheet" rev="stylesheet" href="css/menu_info.css" />
	
<script src="js/jquery-1.2.6.min.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="js/jquery.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="js/thickbox1.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="js/common.js" type="text/javascript" language="javascript" charset="UTF-8"></script>

</head>
<body>

<div id="menubar"><!-- contiene la imagen del menu -->

	<div id="bola">
	<a href="<?php echo site_url('login/show_menu/'.$lang.'/Services');?>" title="<?php echo $this->lang->line('login_Services'); ?>"><img src="images/logotipo/<?php echo $this->lang->line('login_out'); ?>.png" onmouseover="this.src='images/logotipo/<?php echo $this->lang->line('login_over'); ?>.png'" onmouseout="this.src='images/logotipo/<?php echo $this->lang->line('login_out'); ?>.png'" height="100" border="0"></a>
	</div>
	
	<div id="mymenu">
                <a href="<?php echo site_url('login');?>" > <?php echo $this->lang->line('login_menu_login'); ?></a>	
		<a href="<?php echo site_url('login/show_menu/'.$lang.'/About_Us');?>" > <?php echo $this->lang->line('login_About_Us'); ?></a>
		<a href="<?php echo site_url('login/show_menu/'.$lang.'/Services');?>" > <?php echo $this->lang->line('login_Services'); ?></a>
		<a href="<?php echo site_url('login/show_menu/'.$lang.'/Terms');?>" > <?php echo $this->lang->line('login_Terms'); ?></a>
		<a href="<?php echo site_url('login/show_menu/'.$lang.'/Contact');?>" > <?php echo $this->lang->line('login_Contact'); ?></a>
                <?php 
                if (isset($lang) && $lang == 'spanish')
                {?>
                    <a href="<?php echo site_url('login/change_lang_menu/'.$menu.'/english');?>" title="Translate to English"><img src="images/england.png" border="0"></a>	
                <?php }
                else
                { ?>
                    <a href="<?php echo site_url('login/change_lang_menu/'.$menu.'/spanish');?>" title="Traducir a Español"><img src="images/spain.png" border="0"></a>
            <?php } ?>
        </div>
		
	<div id="other_links">
		<a href="<?php echo site_url('register/index/'.$lang); ?>" title="<?php echo $this->lang->line('login_register'); ?>"><?php echo $this->lang->line('login_register'); ?></a>
		<!--<a href="<?php echo site_url('register/newadviser/'.$lang); ?>" title="<?php echo $this->lang->line('publish_whit_us'); ?>"><?php echo $this->lang->line('publish_whit_us'); ?></a>-->
                <a href="<?php echo site_url('register/new_adviser_confirm/1/width:320/height:220'); ?>" class="thickbox" title="<?php echo $this->lang->line('publish_whit_us'); ?>"><?php echo $this->lang->line('publish_whit_us'); ?></a>
	</div>
</div>
    
<div class="thickbox">
<br />

<?php 


switch($menu) 
{
case "About_Us":
?>

<div id="caja">
	<div id='Titulo'><?php echo $this->lang->line('login_About_Us'); ?></div>
	
	<div id='SubTitulo'><?php echo $this->lang->line('login_History'); ?></div>
	<div id='texto'><?php echo $this->lang->line('login_History_Text'); ?></div>
	
	<div id='SubTitulo'><?php echo $this->lang->line('login_Vision'); ?></div>
	<div id='texto'><?php echo $this->lang->line('login_Vision_Text'); ?></div>
	
	<div id='SubTitulo'><?php echo $this->lang->line('login_Mission'); ?></div>
	<div id='texto'><?php echo $this->lang->line('login_Mission_Text'); ?></div>
</div>

<br />

</ul>
<?php break; 
case "Services":
?>

<div id="caja">
	<div id='Titulo'><?php echo $this->lang->line('login_Services'); ?></div>

	<div id='texto'><?php echo $this->lang->line('login_Services_Text'); ?></div>
</div>

<br />
<br />

	<a href="mailto:sales@iandresa.com"> sales@iandresa.com </a>
<br />
<br />

<?php break;  
case "Contact":
?>

<div id="caja">
	<div id='Titulo'><?php echo $this->lang->line('login_Contact'); ?></div>
	
	<div id='SubTitulo1'><?php echo $this->lang->line('login_Contact_Text'); ?></div>
	<div id='texto'><a href="mailto:sales@iandresa.com"> sales@iandresa.com </a></div>
	
	<div id='SubTitulo1'><?php echo $this->lang->line('login_Contact_Text2'); ?></div>
	<div id='texto'><a href="mailto:support@iandresa.com"> support@iandresa.com </a></div>

</div>

<br />	
<br />
	

<?php break;  
case "Terms":
?>

<div id="caja">
	<div id='Titulo'><?php echo $this->lang->line('login_Terms'); ?></div>
	
	<div id='texto'><?php echo $this->lang->line('login_Terms_Text'); ?></div>
	
</div>





<br />
	
	<br />
	<a href="mailto:support@iandresa.com"> support@iandresa.com </a>
<br /><br />

<?php break; ?>

<?php } ?>

<div id="google_translate_element"></div>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script>
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: '<?php echo $lang=="spanish"?'es':'en' ?>'
  }, 'google_translate_element');
}
googleTranslateElementInit();
</script>

</div>

</body>
</html>