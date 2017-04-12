<?
session_start();

include("db/mysql.php");
include("funciones.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Gastro</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<style type="text/css">
        body { font-family:Lucida Sans, Lucida Sans Unicode, Arial, Sans-Serif; font-size:13px; margin:0px auto;}
        #tabs { margin:0; padding:0; list-style:none; overflow:hidden; }
        #tabs li { float:left; display:block; padding:5px; background-color:#bbb; margin-right:5px;}
        #tabs li a { color:#fff; text-decoration:none; }
        #tabs li.current { background-color:#e1e1e1;}
        #tabs li.current a { color:#000; text-decoration:none; }
        #tabs li a.remove { color:#f00; margin-left:10px;}
        #content { background-color:#e1e1e1;}
        #content p { margin: 0; padding:20px 20px 100px 20px;}
        
        #main { width:900px; margin:0px auto; overflow:hidden;background-color:#F6F6F6; margin-top:20px;
             -moz-border-radius:10px;  -webkit-border-radius:10px; padding:30px;}
        #wrapper, #doclist { float:left; margin:0 20px 0 0;}
        #doclist { width:150px;}
        #doclist ul { margin:0; list-style:none;}
        #doclist li { margin:0; padding:0;}
        #documents { margin:0; padding:0;}
        
        #wrapper { width:700px; margin-top:20px;}
            
        #header{ background-color:#F6F6F6; width:900px; margin:0px auto; margin-top:20px;
             -moz-border-radius:10px;  -webkit-border-radius:10px; padding:30px; position:relative;}
        #header h2 {font-size:16px; font-weight:normal; margin:0px; padding:0px;}

    </style>
    
    <script type="text/javascript" src="js/lib/jquery.js" ></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#browser a").click(function() {
                addTab($(this));
            });

            $('#tabs a.tab').live('click', function() {
                // Get the tab name
                var contentname = $(this).attr("id") + "_content";

                // hide all other tabs
                $("#content p").hide();
                $("#tabs li").removeClass("current");

                // show current tab
                $("#" + contentname).show();
                $(this).parent().addClass("current");
            });

            $('#tabs a.remove').live('click', function() {
                // Get the tab name
                var tabid = $(this).parent().find(".tab").attr("id");

                // remove tab and related content
                var contentname = tabid + "_content";
                $("#" + contentname).remove();
                $(this).parent().remove();

                // if there is no current tab and if there are still tabs left, show the first one
                if ($("#tabs li.current").length == 0 && $("#tabs li").length > 0) {

                    // find the first tab    
                    var firsttab = $("#tabs li:first-child");
                    firsttab.addClass("current");

                    // get its link name and show related content
                    var firsttabid = $(firsttab).find("a.tab").attr("id");
                    $("#" + firsttabid + "_content").show();
                }
            });
        });
        function addTab(link) {
            // If tab already exist in the list, return
            if ($("#" + $(link).attr("rel")).length != 0)
                return;
            
            // hide other tabs
            $("#tabs li").removeClass("current");
            $("#content p").hide();
            
            // add new tab and related content
            $("#tabs").append("<li class='current'><a class='tab' id='" +
                $(link).attr("rel") + "' href='#'>" + $(link).html() + 
                "</a><a href='#' class='remove'>x</a></li>");

            $("#content").append("<p id='" + $(link).attr("rel") + "_content'>" + 
                "<iframe name='" + $(link).attr("rel") + "' src='" + $(link).attr("title") + "' frameborder=0 width='100%' height='400'></iframe></p>");

            
            // set the newly added tab as current
            $("#" + $(link).attr("rel") + "_content").show();
        }
    </script>
<script type="text/javascript" src="simpletreemenu.js">

/***********************************************
* Simple Tree Menu- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>

<link rel="stylesheet" type="text/css" href="simpletree.css" />
    

    
<?

if(!isset($_SESSION['id']))
{
	?>
	<script type="text/javascript">
		alert("Error Ingresando. Por favor ingrese nuevamente");
		window.location = "http://www.iandresa.com/Gastro/";
	</script>
	<?
}

$id = $_SESSION['id'];

$client = new cliente();
$rec = new recinto();

// Consultamos en la BD los datos del cliente.
$user = $client->getDataCliente($id);
$nombre = $user[0]." ".$user[1]." ".$user[2];
$rs = $user[3];

// Sacamos los recintos de la cadena
$count = $rec->contarRecintos($id);
$listaRec = $rec->getRecintos($id);


$i = 0;

?>
<link href="default.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<!-- start header -->
<div id="header">
	<div id="logo">
		<h1>Gastro</h1>
	</div>
<?
// Vemos si hay mensajes sin leer
$nl = noLeidos($id);
if($nl > 0)
	$mnl = "(".$nl.")";
else
	$mnl = '';
?>
	<div id="search">
		<img src="lateral.jpg">&nbsp;
		Bienvenido, <? echo $nombre; ?>. <a href="../logout.php">Salir</a>.
	</div>
</div>
<!-- end header -->
<!-- start page -->
<div id="page">
	<!-- start content -->
	<div id="content">
		<div class="post">
			<div class="entry">
			<p>
	 			<div id="wrapper">
			        <ul id="tabs">
        			    <!-- Tabs go here -->
       				 </ul>
       				 <div id="content">
       					<!-- Contenido -->    
				<p style="width:80%;">
				<b>Bienvenido a Gastro!</b><br><br>
				Te recordamos que el sistema está en versión beta. Por lo que puede que hayan algunos errores, te rogamos que los comuniques. Los módulos del sistema irán creciendo paulatinamente con opciones. Recuerda que puedes sugerirnos opciones en los módulos!<br><br>
Puedes empezar creando un recinto (en el menú de la izquierda, opción "Agregar Recinto"). Una vez crees tus recintos los podrás ver en el menú de la izquierda. (Actualiza el sitio presionando la tecla F5 si no los logras visualizar de inmediato). Una vez alli podras agregar ingresos, gastos, ver el balance financiero del local, entre otras opciones que iremos agregando con el tiempo.<br><br>Disfruta Gastro!.
				</p>
				
       				 </div>

    				</div>
			</p>
			</div>
		</div>
	</div>

	<!-- end content -->
	<!-- start sidebar -->
	<div id="sidebar">
		<div id="doclist">
	        <h2>Menu</h2>
	
	        <ul id="browser" class="treeview">
				<?
				while($i < $count)
				{
				?>
				<li><? echo $listaRec[$i][2]; ?>
					<ul>
						<li><a href="#" rel="Pedidos" title="modules.php?module=pedido&id=<? echo $listaRec[$i][0]; ?>">Pedidos</a></li>
						<li><a href="#" rel="Ingreso" title="modules.php?module=ingreso&id=<? echo $listaRec[$i][0]; ?>">Ingreso</a></li>
						<li><a href="#" rel="Gasto" title="modules.php?module=gasto&id=<? echo $listaRec[$i][0]; ?>">Gasto</a></li>
						<li><a href="#" rel="Balance" title="modules.php?module=balance&id=<? echo $listaRec[$i][0]; ?>">Balance</a></li>
						<li><a href="#" rel="Datos" title="modules.php?module=datos&id=<? echo $listaRec[$i][0]; ?>">Datos</a></li>
					</ul>
				</li>
				<?
				$i++;
				}
				?>	
				<li><a href="#" rel="Opciones" title="modules.php?module=opciones&id=<? echo $id; ?>">Opciones</a></li>
				<li><a href="#" rel="AgregarRecinto" title="modules.php?module=addRecinto&id=<? echo $id; ?>">Agregar Recinto</a></li>

				<li><a href="#" rel="Mensajes" title="modules.php?module=messages&id=<? echo $id; ?>">Mensajes <? echo $mnl; ?></a></li>
        	</ul>
		<script type="text/javascript">
			ddtreemenu.createTree("browser", true);
		</script>
    		</div>
	<img src="menu.jpg">
	</div>
	<!-- end sidebar -->
	<div style="clear: both;">&nbsp;</div>
</div>
<!-- end page -->
<!-- start footer -->
<div id="footer">
	<p>&copy;2010 Todos los derechos reservados.</p>
	<img src="lateral.jpg">
</div>


</body>
</html>
