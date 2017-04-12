<?php

function beginModuleRow($langEnd='es',$imageName='order')
{
$link='<TR>
			<TD ALIGN="Center">
			<img src="'.base_url()."images/cart/$imageName$langEnd.jpg".'" border="0"></TD>';
			
return  $link;   
}
function endModuleRow()
{
	return '</TR>';
}
//terminaqr el dir
function priceTD($base_Url_DMFormat,$langEnd,$nombre,$apellido,$telefono,$correo,$price='5600',$nombreItem='Monthly Order module',$nroItem='11',$TRX_ID='711',$dir='')
{
$link='<TD ALIGN="Left">$'.$price.'<A href="'.base_url().$dir.'" onclick="window.open(';
$link=$link."'https://chile.dineromail.com/Carrito/cart.asp?languge=".substr($langEnd, 1, 2)."&NombreItem=$nombreItem&TipoMoneda=1&PrecioItem=$price&NroItem=$nroItem&usr_nombre=$nombre&usr_apellido=$apellido&usr_tel_numero=$telefono&usr_email=$correo[0]%40$correo[1]&TRX_ID=$TRX_ID&image_url=";
$link=$link.base_url()."images/cart/iandresalogo$langEnd.jpg&DireccionExito={$base_Url_DMFormat}index.php/dineroMailer/saleOk&DireccionFracaso={$base_Url_DMFormat}index.php/dineroMailer/saleError&DireccionEnvio=0&Mensaje=1&MediosPago=&Comercio=391608','Carrito','width=600,height=275,toolbar=no,location=no,status=no,menubar=no,resizable=yes,scrollbars=yes,directories=no');".'"><img src="'.base_url().'images/cart/addcart'.$langEnd.'.jpg" border="0"></A></TD>'; 
return  $link; 
}

function pagomasterCartLink($langEnd)
{
    $image=base_url()."/images/cart/viewcart".$langEnd.".jpg";
    $link="<a href='https://www.pagomaster.com/cl/cuenta/shopCart/showCart.php'
target='_blank' class='see_shopping_cart'><img
src='{$image}'
border='0'></a>";
    return $link;
}
function MYpagomasterCartAddProduct($id,$langEnd,$price,$nombreProducto)
{
    //ivan@iandresa.com  es este al parecer!!!
    //iandresa@msn.com
    //hacer un carro de compra en lugar de un boton de pago!!!!
    $base=base_url();
    $image=base_url()."images/cart/addcart".$langEnd.".jpg";
    
    $imgUrl="https://lh5.googleusercontent.com/-uq5c2aFRslw/T2oonzVLCSI/AAAAAAAAABE/uOs1qNsoVdA/h120/logoregular.png";

   if($langEnd == "-english") $imgUrl="https://lh3.googleusercontent.com/-8eHxYqnVp-U/T7EM6VYgdFI/AAAAAAAAABU/26G6emAuj6w/s145/logoregularE.png";      

    //codigo generado por dineromail
    //https://lh5.googleusercontent.com/-uq5c2aFRslw/T2oonzVLCSI/AAAAAAAAABE/uOs1qNsoVdA/h120/logoregular.png
    //https://lh3.googleusercontent.com/-LeYGA2j8awE/T2onFGuE-CI/AAAAAAAAAA4/PQpq7keSLlU/h120/logochico.png
    //https://picasaweb.google.com/lh/webUpload?uname=106339924062188645469
    //https://lh3.googleusercontent.com/-8eHxYqnVp-U/T7EM6VYgdFI/AAAAAAAAABU/26G6emAuj6w/s145/logoregularE.png
          $link = <<<TEXT
<TD ALIGN='Left'><span>$ $price</span>
<form action="https://www.pagomaster.com/cl/cuenta/?cmd=checkout2" method="post" target="_blank">
	<input type="hidden" name="merchantAccount" value="support@iandresa.com">
	<input type="hidden" name="amount" value="{$price}">
	<input type="hidden" name="currency" value="CLP">
	<input type="hidden" name="item_id" value="{$nombreProducto}">
	<input type="hidden" name="setupFee" value="">
	<input type="hidden" name="return_url" value="{$base}index.php/dineroMailer/saleOk/">
	<input type="hidden" name="cancel_url" value="{$base}index.php/dineroMailer/saleError/">
    <input type="hidden" name="callback_url" value="{$base}index.php/dineroMailerSecure/notification/">
	<input type="hidden" name="stopNumber" value="">
	<input type="hidden" name="stopRecurring" value="">
	<input type="hidden" name="durationType" value="">
	<input type="hidden" name="duration" value="">
	<input type="hidden" name="merchant_logo" value="{$imgUrl}">
	<input type="hidden" name="addresscheckoutstep" value="0">
	<input type="hidden" name="pst" value="1">
	<input type="hidden" name="ipk" value="1">
  	<input type="hidden" name="merchant_transaction_id" value="{$id}">
	<input type="image" name="cartImage" src="{$image}">
	</form></TD>
TEXT;
      return $link;
}


function pagomasterCartAddProduct($langEnd,$price)
{
    $link="<TD ALIGN='Left'>$".$price."<a href='https://www.pagomaster.com/cl/cuenta/shopCart/showCart.php?action=add&id=6624035757&m=4244&merchant_transaction_id=0'
target='_blank' class='add_to_cart'><img
src='".base_url().'images/cart/addcart'.$langEnd.'.jpg'."'
border='0'></a></TD>";
    return $link;
}


function MYpagomasterSimulateCallBack($merchant_transaction_id='001t1-1',$text=NULL)
{
	if($text==NULL)$text='simulate call back ($merchant_transaction_id)';
	return "<form  target='_blank' action='".base_url()."index.php/dineroMailerSecure/notification' method='post'> 
	<input type='hidden' name='merchant_transaction_id' value='$merchant_transaction_id'>  
     <input type='hidden' name='status' value='1'>            
	<input type='submit' value='$text' /> 	
           </form>";		   
}

?>
