<?
session_start();

include("db/mysql.php");
include("classes/cliente.php");
include("classes/recinto.php");
include("classes/proveedor.php");
include("funciones.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Gastro</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="stylesheet" href="js/jquery.treeview.css" />
    <link rel="stylesheet" href="js/red-treeview.css" />
	<link rel="stylesheet" href="screen.css" />
	
	<script src="js/lib/jquery.js" type="text/javascript"></script>
	<script src="js/lib/jquery.cookie.js" type="text/javascript"></script>
	<script src="js/jquery.treeview.js" type="text/javascript"></script>
	
	<script type="text/javascript">
	$(document).ready(function(){
		$("#browser").treeview({
			toggle: function() {
				console.log("%s was toggled.", $(this).find(">span").text());
			}
		});
		
		$("#add").click(function() {
			var branches = $("<li><span class='folder'>New Sublist</span><ul>" + 
				"<li><span class='file'>Item1</span></li>" + 
				"<li><span class='file'>Item2</span></li></ul></li>").appendTo("#browser");
			$("#browser").treeview({
				add: branches
			});
		});
	});
	</script>
<?
$id = $_GET['id'];

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
	<div id="search">
		Bienvenido, <? echo $nombre; ?>. <br> <a href="messages.php">Mis Mensajes</a> | <a href="logout.php">Salir</a>.
	</div>
</div>
<!-- end header -->
<!-- start page -->
<div id="page">
	<!-- start content -->
	<div id="content">
		<div class="post">
			<h1 class="title"><a href="#">Pestañas</a></h1>
			<div class="entry"><p>Contenido de las Pestañas</p>
			</div>
		</div>
	</div>
	<!-- end content -->
	<!-- start sidebar -->
	<div id="sidebar">
		<ul id="browser" class="filetree treeview-famfamfam">
			<li><span class="folder"><? ?></span>
				<ul>
					<li><span class="folder"><? echo $rs; ?></span>
						<ul>
						<?
						// Listado de Recintos de la cadena
						echo "Recintos: ".$count;
						while($i < $count)
						{
						?>
							<li><span class="folder"><? echo $listaRec[$i][2]; ?></span>
								<ul>
									<li><span class="file">Realizar Pedido</span></li>
									<li><span class="file">Agregar Ingreso</span></li>
									<li><span class="file">Agregar Gasto</span></li>
									<li><span class="file">Ver Balance</span></li>
									<li><span class="file">Editar Datos</span></li>
								</ul>
							</li>
						<?
						}
						?>		
							<li><span class="file"><a href="index.php?module=addRecinto&id=<? echo $id; ?>">Agregar Recinto</a></span></li>
							<li><span class="file"><a href="index.php?module=opciones&id=<? echo $id; ?>">Opciones Generales</span></li>
						</ul>
					</li>
				</ul>
			</li>
		</ul>
	</div>
	<!-- end sidebar -->
	<div style="clear: both;">&nbsp;</div>
</div>
<!-- end page -->
<!-- start footer -->
<div id="footer">
	<p>&copy;2010 Todos los derechos reservados.</p>
</div>
<!-- end footer -->
</body>
</html>
