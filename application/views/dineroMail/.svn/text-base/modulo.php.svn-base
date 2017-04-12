<?php $this->load->view("partial/header"); 
$correo = explode("@",$email); 
 
if ($telefono == " ") { 
   $telefono = "1234567"; 
} 
$langEnd='-'.$this->config->item('language');
$enterprise_id=$this->session->userdata('enterprise_id');
?>
<div id="page_title"><?php echo $this->lang->line("common_acquire_modules"); ?></div>
</br>
<h3><?php echo $this->lang->line("adquire_modules_info"); ?></h3>
</br>
<center>
<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 >
<TR><TD>

	<TABLE Border=1 CellPadding=3 CellSpacing=1 style='text-align:center'>     

	    <TR>
			<TD><?php echo $this->lang->line("adquire_modules_modules"); ?></TD>
                        <TD><?php echo $this->lang->line("adquire_modules_monthly"); ?></TD>
                        <TD><?php echo $this->lang->line("adquire_modules_semiannual"); ?></TD>
                        <TD><?php echo $this->lang->line("adquire_modules_annual"); ?></TD>
	    </TR>
	<?php
	
		$this->load->helper('dinero_mail'); 
		echo beginModuleRow($langEnd,'order');
		echo MYpagomasterCartAddProduct("111".$enterprise_id,$langEnd,"5600","Monthly Order module");
		echo MYpagomasterCartAddProduct("112".$enterprise_id,$langEnd,"29800","Semiannual Order module");
		echo MYpagomasterCartAddProduct("113".$enterprise_id,$langEnd,"57600","Annual Order module");
                        
		//	echo priceTD($this->auxiliar->base_Url_DMFormat(),$langEnd,$nombre,$apellido,$telefono,$correo,5600,'Monthly Order module',1,711,"index.php/dineroMailer");
		//	echo priceTD($this->auxiliar->base_Url_DMFormat(),$langEnd,$nombre,$apellido,$telefono,$correo,29800,'Semiannual Order module',2,711,"index.php/dineroMailer");
		//	echo "falta";//priceTD($this->auxiliar->base_Url_DMFormat(),$langEnd,$nombre,$apellido,$telefono,$correo,57600,'Annual Order module',3,711,"index.php/dineroMailer");	
		echo endModuleRow();
		
		echo beginModuleRow($langEnd,'delivery');
                echo MYpagomasterCartAddProduct("121".$enterprise_id,$langEnd,"5600","Monthly Delivery module");
		echo MYpagomasterCartAddProduct("122".$enterprise_id,$langEnd,"29800","Semiannual Delivery module");
		echo MYpagomasterCartAddProduct("123".$enterprise_id,$langEnd,"57600","Annual Delivery module");
		//	echo "falta";//priceTD($this->auxiliar->base_Url_DMFormat(),$langEnd,$nombre,$apellido,$telefono,$correo,5600,'Monthly Delivery module',4,711,"index.php/dineroMailer");
		//	echo "falta";//priceTD($this->auxiliar->base_Url_DMFormat(),$langEnd,$nombre,$apellido,$telefono,$correo,29800,'Semiannual Delivery module',5,711,"index.php/dineroMailer");
		//	echo "falta";//priceTD($this->auxiliar->base_Url_DMFormat(),$langEnd,$nombre,$apellido,$telefono,$correo,57600,'Annual Delivery module',6,711,"index.php/dineroMailer");
		echo endModuleRow();
		
		echo beginModuleRow($langEnd,'report');	
                echo MYpagomasterCartAddProduct("131".$enterprise_id,$langEnd,"6568","Monthly Report module");
		echo MYpagomasterCartAddProduct("132".$enterprise_id,$langEnd,"35854","Semiannual Report module");
		echo MYpagomasterCartAddProduct("133".$enterprise_id,$langEnd,"69678","Annual Report module");
		//	echo "falta";//priceTD($this->auxiliar->base_Url_DMFormat(),$langEnd,$nombre,$apellido,$telefono,$correo,5600,'Monthly Report module',7,711,"index.php/dineroMailer");
		//	echo "falta";//priceTD($this->auxiliar->base_Url_DMFormat(),$langEnd,$nombre,$apellido,$telefono,$correo,29800,'Semiannual Report module',8,711,"index.php/dineroMailer");
		//	echo "falta";//priceTD($this->auxiliar->base_Url_DMFormat(),$langEnd,$nombre,$apellido,$telefono,$correo,57600,'Annual Report module',9,711,"index.php/dineroMailer");
		echo endModuleRow();
	
	?>		
	</TABLE>

		</TD></TR>
		<TR><TD ALIGN="center">
                <?php //echo pagomasterCartLink($langEnd);?>
		<!--<A href="./index.php/dineroMailer" onclick="window.open('https://chile.dineromail.com/Carrito/cart.asp?Comercio=391608','Carrito','width=600,height=275,toolbar=no,location=no,status=no,menubar=no,resizable=yes,scrollbars=no,directories=no');"><img src="<?php echo base_url();?>/images/cart/viewcart<?php echo $langEnd?>.jpg" border="0"></A>-->
                
                <!--<script src="http://www.gmodules.com/ig/ifr?url=http://www.pixelmedia.nl/gmodules/ucc.xml&amp;up_fromcur=CLP&amp;up_tocur=USD&amp;synd=open&amp;w=320&amp;h=110&amp;title=Currency Converter&amp;lang=eng&amp;country=CL&amp;border=%23ffffff%7C3px%2C1px+solid+%23999999&amp;output=js"></script>-->
                </br>           
                <?php
                $url=base_url();
                echo "<a href='./index.php/dineroMailer' onclick=\"window.open('https://www.google.com/finance/converter?from=CLP&to=USD');\"><img src='{$url}images/cart/currencyconverter$langEnd.jpg' border='0'></a>";
                ?>

<BR>
		
</TD></TR>
</TABLE>
</center>
    
<?php 
$this->load->view("partial/footer"); 
?>