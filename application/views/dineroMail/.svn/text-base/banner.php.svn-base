<?php $this->load->view("partial/header"); 
# Lee variables de nombre, apellido, telefono y correo via POST
//391608
$correo = explode("@",$email); 
 
if ($telefono == " ") { 
   $telefono = "1234567"; 
} 
$langEnd='-'.$this->config->item('language');
$enterprise_id=$this->session->userdata('enterprise_id');
$person_id=$this->session->userdata('person_id');
?>
<div id="page_title"><?php echo $this->lang->line("common_acquire_banner"); ?></div>
</br>
<h3><?php echo $this->lang->line("adquire_modules_info2"); ?></h3>
</br>
<center>
<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 style='text-align:center'> 
<TR><TD>

	<TABLE Border=1 CellPadding=3 CellSpacing=1>
        <CAPTION></CAPTION>
	    <TR>
			<TD>Banners</TD>
			<TD>Pixeles</TD>
			<TD>1,000 <?php echo $this->lang->line("adquire_modules_prints"); ?></TD>
			<TD>30,000 <?php echo $this->lang->line("adquire_modules_prints"); ?></TD>
			<TD>70,000 <?php echo $this->lang->line("adquire_modules_prints"); ?></TD>
	    </TR>
	
		<TR>
			<TD ALIGN="Center"><?php echo $this->lang->line("adquire_modules_end"); ?></TD>
			<TD ALIGN="Center">300x100</TD>
			<?php	
		$this->load->helper('dinero_mail');
		echo MYpagomasterCartAddProduct("201".$person_id,$langEnd,$this->Campaign_offer_model->get_offer_price(1),"Banner at the end of the page x 1,000 Prints (no login page)"); 
		echo MYpagomasterCartAddProduct("202".$person_id,$langEnd,$this->Campaign_offer_model->get_offer_price(2),"Banner at the end of the page x 30,000 Prints (no login page)"); 
		echo MYpagomasterCartAddProduct("203".$person_id,$langEnd,$this->Campaign_offer_model->get_offer_price(3),"Banner at the end of the page x 70,000 Prints (no login page)"); 
		?>
					
		</TR>
		
	
		<TR>
			<TD ALIGN="Center"><?php echo $this->lang->line("adquire_modules_side"); ?></TD>
			<TD ALIGN="Center">150x60</TD>
						<?php	
		echo MYpagomasterCartAddProduct("204".$person_id,$langEnd,$this->Campaign_offer_model->get_offer_price(4),"Banner at side of the page x 1,000 Prints (no login page)");
		echo MYpagomasterCartAddProduct("205".$person_id,$langEnd,$this->Campaign_offer_model->get_offer_price(5),"Banner at side of the page x 30,000 Prints (no login page)"); 
		echo MYpagomasterCartAddProduct("205".$person_id,$langEnd,$this->Campaign_offer_model->get_offer_price(6),"Banner at side of the page x 70,000 Prints (no login page)");  	
		?>
			
		</TR>
		
	
		<TR>
			<TD ALIGN="Center"><?php echo $this->lang->line("adquire_modules_login"); ?></TD>
			<TD ALIGN="Center">500x400</TD>
<?php		
		echo MYpagomasterCartAddProduct("207".$person_id,$langEnd,$this->Campaign_offer_model->get_offer_price(7),"Banner only at login x 1,000 Prints");
		echo MYpagomasterCartAddProduct("208".$person_id,$langEnd,$this->Campaign_offer_model->get_offer_price(8),"Banner only at login x 30,000 Prints");
		echo MYpagomasterCartAddProduct("209".$person_id,$langEnd,$this->Campaign_offer_model->get_offer_price(9),"Banner only at login x 70,000 Prints");
?>			
		</TR>

	</TABLE>
		</TD></TR>
		<TR><TD ALIGN="center">
		<?php //echo pagomasterCartLink($langEnd);?>
		<!--<A href="./index.php/dineroMailer/banners" onclick="window.open('https://chile.dineromail.com/Carrito/cart.asp?Comercio=391608','Carrito','width=600,height=275,toolbar=no,location=no,status=no,menubar=no,resizable=yes,scrollbars=no,directories=no');"><img src="<?php echo base_url();?>/images/cart/viewcart<?php echo $langEnd?>.jpg" border="0"></A>-->

                </br>           
                <?php
                $url=base_url();
                echo "<a href='./index.php/dineroMailer/banners' onclick=\"window.open('https://www.google.com/finance/converter?from=CLP&to=USD');\"><img src='{$url}images/cart/currencyconverter$langEnd.jpg' border='0'></a>";
                ?>
<BR>
		
</TD></TR>
</TABLE>
</center>
<?php 
$this->load->view("partial/footer"); 
?>