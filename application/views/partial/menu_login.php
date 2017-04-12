<div id="menubar"><!-- contiene la imagen del menu -->

	<div id="bola">
            
        <a href="<?php echo site_url('login/show_menu/'.$lang.'/Services');?>" 
           title="<?php echo $this->lang->line('login_Services'); ?>">
            <img src="images/logotipo/<?php echo $this->lang->line('login_out'); ?>.png" 
                 onmouseover="this.src='images/logotipo/<?php echo $this->lang->line('login_over'); ?>.png'" 
                 onmouseout="this.src='images/logotipo/<?php echo $this->lang->line('login_out'); ?>.png'" 
                 height="100" border="0">
        </a>
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
                    <a href="<?php echo site_url('login/change_lang/english');?>" title="Translate to English"><img src="images/england.png" border="0"></a>	
                <?php }
                else
                { ?>
                    <a href="<?php echo site_url('login/change_lang/spanish');?>" title="Traducir a Español"><img src="images/spain.png" border="0"></a>
            <?php } ?>
        </div>
		
	<div id="other_links">
		<?php 
                    $login = $this->session->userdata('login');
                    if($login != "registerAdviser" && $login != "registerEnterprise")
                    {
                ?>
                        <a href="<?php echo site_url('register/index/'.$lang); ?>" title="<?php echo $this->lang->line('login_register'); ?>"><?php echo $this->lang->line('login_register'); ?></a>
                        <a href="<?php echo site_url('register/new_adviser_confirm/1/width:320/height:220'); ?>" class="thickbox none" title="<?php echo $this->lang->line('publish_whit_us'); ?>"><?php echo $this->lang->line('publish_whit_us'); ?></a>
                <?php } ?>        
        </div>
</div>
