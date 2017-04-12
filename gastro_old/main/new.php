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
	// Recibimos los datos del registro
	
	$id = $_POST['signup'];
	$mail = $_POST['email'];
	?>

<link href="default.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<!-- start header -->
<div id="header">
	<div id="logo">
		<h1>Gastro</h1> <i>beta</i>
	</div>
	<div id="search">
		Bienvenido, [USUARIO]. &nbsp;&nbsp;&nbsp; Mis Mensajes | Salir.
	</div>
</div>
<!-- end header -->
<!-- start page -->
<div id="page">
	<!-- start content -->
	<div id="content">
		<div class="post">
			<h1 class="title"><a href="#">Nuevo Registro</a></h1>
			<div class="entry"><p>
			<b>Seleccione el tipo de registro de nuevo usuario</b><br><br>
			<form action="new_2.php" method="POST">
			<? /* <input type="radio" name="tipo" value="user"> */ ?> Consumidor, cliente de bares, pubs o restaurantes. (deshabilitado en esta beta)<br>
			<input type="radio" name="tipo" value="client"> Dueño, administrador de recinto o cadena de bar, pub, restaurant.<br>
			<? /* <input type="radio" name="tipo" value="prov"> */ ?>Proveedor de insumos para recintos. (deshabilitado en esta beta)<br><br>
			<input type="hidden" name="id" value="<? echo $id; ?>"> <input type="hidden" name="mail" value="<? echo $mail; ?>">
			<input type="submit" value=" Continuar ">
			</form>
			</p>
			</div>
		</div>
	</div>
	<!-- end content -->
	<!-- start sidebar -->
	<div id="sidebar">
		<h1>Gastro</h1>
<p>
Recuerde que puede registrarse como consumidor (persona que asiste a los recintos y/o cadenas gastronómicas), como dueño o administrador de su cadena, o bien como proveedor de insumos para estas cadenas. El registro no tiene costo.		
</p>	
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
