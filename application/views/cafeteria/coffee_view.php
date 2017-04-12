<?php $this->load->view("partial/header"); ?> 

<?php
    if(isset($success))
    { 
        echo "<div id='success_sale' class='success_message'>".$success."</div>";
    } 
?>

<div id="title_bar"  style="width: 100%">
    <div id="title" class="float_left"  style="width: 100%"><?php echo $this->lang->line('common_list_of') . ' ' . $this->lang->line('module_' . $controller_name); ?>

    
                             
	<?php 
			echo anchor("cafeteria/view_report",
			"<div class='small_button2' style='float:right;margin-top:5px;'><span>".$this->lang->line('cafeteria_imprimir')."</span></div>",
			array('class'=>'thickbox none','title'=>$this->lang->line('common_list_of') . ' ' . $this->lang->line('module_' . $controller_name)));
		
	?> 
	
	
    
        </div>
</div>


<script type='text/javascript'>  
/* Codigo a ejecutarse tan pronto como la 
   pagina ha sido cargada por el navegador */  
/*$(document).ready(function ()  
{  
    callMeOften(); 
});*/



    
function callMeOften()
{
      $.ajax({
            method: 'post',
            url : '<?php echo site_url();?>/cafeteria/update_div',
            dataType : 'text',
            success: function (text) { 
                //alert(text);
                $('#updateMe').html(text); 
            }
      });
      //alert("UPDATE ME");
}

function callMeOften2()
{
      $.ajax({
            method: 'post',
            url : '<?php echo site_url();?>/cafeteria/update_div2',
            dataType : 'text',
            success: function (text) { 
                //alert(text);
                $('#updateMe2').html(text); 
            }
      });
      //alert("UPDATE ME");
}

var holdTheInterval = setInterval(callMeOften, 30000);
var holdTheInterval2 = setInterval(callMeOften2, 30000);
</script>  

<script>
function confirmar_venta()
{
	if (confirm('Seguro que desea completar la venta ?'))
	{
		//alert('eliminada');
		return true;
	}
	else return false;
}

function confirmar_editar_venta()
{
	if (confirm('Seguro que desea editar la venta ?'))
	{
		//alert('eliminada');
		return true;
	}
	else return false;
}

function confirm_sale()
{
	if (confirm('Are you sure you want to complete the sale ?'))
	{
		//alert('eliminada');
		return true;
	}
	else return false;
}

function confirm_edit_sale()
{
	if (confirm('Are you sure you want to edit the sale ?'))
	{
		//alert('eliminada');
		return true;
	}
	else return false;
}
</script>

<script type="text/javascript" language="javascript">
// haces referencia al elemento (en este caso div) e indicas el tiempo de espera en segundos
var strCmd = "document.getElementById('success_sale').style.display = 'none'";
var waitseconds = 5;

// Calculas el tiempo en milisegundos y ejecutas la acción
var timeOutPeriod = waitseconds * 1000;
var hideTimer = setTimeout(strCmd, timeOutPeriod);
</script>

<style type="text/css">

	#Cafeteria 
	{	
		margin: 8px auto  ;
		width: 80%;
		border-collapse: collapse; 
		border-color:#3C606D;
		border: #B9D0D9 solid 1px;
                text-align: center;
	}
	
	#Cafeteria th 
	{	
		background-color:#717171;
		font-family: sans-serif;
		font-size:12px;
		padding: .4em .8em;
		color: #ffffff;
		/*text-align:center;
		text-shadow: #A2C0CC -2px 1px 1px;*/
	}
	
	#Cafeteria td 
	{	
        	/*background-color:#ffffff;*/
		font-family: sans-serif;
		font-size:11px;
		padding: .4em 1em;
	}
	
	#Cafeteria td:hover 
	{	
        	/*background-color:#FFFFd0;*/
		cursor:default;
	}
        
    	#finish_button
    	{
        	position:relative;
        	cursor:pointer;
        	width:91%;
    	}
        
    	#finish_button span
    	{
        	color:#FFF;
        	background-color:#808080;
        	border:2px solid #DDDDDD;
        	font-size:14px;
        	padding:2px 5px 2px 5px;
        	float: right;
    	}
        
        #cancel_button
    	{
        	position:relative;
        	cursor:pointer;
        	width:91%;
    	}
        
    	#cancel_button span
    	{
        	color:#FFF;
        	background-color:#808080;
        	border:2px solid #DDDDDD;
        	font-size:14px;
        	padding:2px 5px 2px 5px;
        	float: right;
    	}
        
	#r 
	{
		float: right;
		color: #666666;
		/*text-decoration: underline;*/
	}
	
	.link
	{
		color: blue;
	}
	.link:hover
	{
		color: blue;
		text-decoration: none;
	}
	
	.cant_a_pagar
	{
		color: maroon;
		font-size: 11px;
	}
	.fecha
	{
		color: maroon;
		font-size: 10px;
	}
	
	div#vinc 
	{
		margin: 0 auto;
        	margin-top: 20px;
		/*background-color:red;*/
		width: 25%;
		text-align: center ;
		padding-bottom: .7em;
	}
	
	div#vinc1
	{
		margin: 30px auto;
		/*background-color:red;*/
		width: 15%;
		text-align: right ;
		padding-bottom: .7em;
		float:right;
	}
	
	
	#scroll
	{
		overflow: auto;
		height: 295px;
		padding: 0 5px;
	}
	
	#comment
	{
		margin: 10px 30px;
	}
	.negro{color: black; }
	
	#cartel_sin_ventas_pendientes
	{
		background-color: #FFFCDD;
		text-align: center;
		padding: 5px;
		border: 1px #FFF4AA solid;
		font-size: 18px;
	}
 
</style>

<div style="border:solid 1px #A7A7A7;padding:10px">
    
<div id="updateMe">
    <?php
        $this->load->view('cafeteria/refresh_view'); //ECP V_1 imprimir pedidos y entregas muestra lista de pedidos
    ?>
</div>    
</div>

<br />

<div id="title_bar" style="width: 100%">
	<div id="title" style="width: 100%" class="float_left"><?php echo $this->lang->line('common_list_of').' '.$this->lang->line('cafeteria_list_orders_finished'); ?>
        
            <?php 
			echo anchor("cafeteria/view_report2",
			"<div class='small_button2' style='float:right;margin-top:5px;'><span>".$this->lang->line('cafeteria_imprimir')."</span></div>",
			array('class'=>'thickbox none','title'=>$this->lang->line('common_list_of') . ' ' .$this->lang->line('cafeteria_list_orders_finished')));
                        
                        
		
            ?> 
            
        </div>
</div>

<div id="updateMe2">
    <?php
        $this->load->view('cafeteria/refresh_view2'); //ECP V_1 imprimir pedidos y entregas muestra lista de pedidos terminados
    ?>
</div>    

<div class="clearfix" style="margin-bottom:<?php echo $margin.'px'; ?> ">&nbsp;</div>
<?php $this->load->view("partial/footer"); ?>
