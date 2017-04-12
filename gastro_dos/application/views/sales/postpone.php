<?php $this->load->view("partial/header"); ?>

<div id="title_bar">
    <div id="title" class="float_left"><?php echo $this->lang->line('sales_postponed'); ?></div>
</div>

<?php
    if(isset($success))
    { 
        echo "<div id='success_sale' class='success_message'>".$success."</div>";
    } 
?>

<script type='text/javascript'>  

function callMeOften()
{
      $.ajax({
            method: 'post',
            url : '<?php echo site_url();?>/sales/update_div',
            dataType : 'text',
            success: function (text) { 
                //alert(text);
                $('#updateMe').html(text); 
            }
      });
      //alert("UPDATE ME");
}
callMeOften();
var holdTheInterval = setInterval(callMeOften, 30000);

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
		background-color:#ffffff;
		font-family: sans-serif;
		font-size:11px;
		padding: .4em 1em;
		text-align:left;
	}
	
	#Cafeteria td:hover 
	{	
		background-color:#FFFFd0;
		cursor:default;
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
        .clienteVacio
	{
		color: #BBB1B1;
		font-size: 10px;
                margin: 0 10px;
                font-style:italic;
	}
        .cliente
	{
		color: #646464;
		font-size: 12px;
                margin: 0 10px;
	}
	
	div#vinc 
	{
		margin: 0 auto;
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
    <img   src="<?php echo base_url();?>/images/spinner_small.gif" border="0"/>        
</div>    
</div>

<div class="clearfix" style="margin-bottom:<?php echo $margin . 'px'; ?> ">&nbsp;</div>
<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
// haces referencia al elemento (en este caso div) e indicas el tiempo de espera en segundos
var strCmd1 = "document.getElementById('success_sale').style.display = 'none'";
var waitseconds1 = 5;

// Calculas el tiempo en milisegundos y ejecutas la acción
var timeOutPeriod1 = waitseconds1 * 1000;
var hideTimer1 = setTimeout(strCmd1, timeOutPeriod1);
</script>