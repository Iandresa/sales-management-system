<? session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?

include("db/mysql.php");
include("funciones.php");

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Gastro</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="stylesheet" href="js/jquery.treeview.css" />
    <link rel="stylesheet" href="js/red-treeview.css" />
	<link rel="stylesheet" href="screen.css" />
	
<?
// Vemos si el usuario ha ingresado correctamente, y vemos el tipo de usuario. Luego, redirigimos a donde corresponda
/*
/	Lugar para el admin general: admin/index.php
/	Lugar para clientes: clientes/index.php
/	Lugar para proveedores: prov/index.php
/	Lugar para usuarios: user/index.php
/	Si no, pedimos autentificación.
*/
$id = $_POST['log'];
$pwd = $_POST['pwd'];

$res = login($id,$pwd);

$tipo = $res[2];
$_SESSION['id'] = $res[0];

	
	if($tipo == 'client')
	{
		?>
		<script type="text/javascript">
			window.location = "client/index.php";
		</script>
		<?
		
	}	
	if($tipo == 'user')
	{
		?>
		<script type="text/javascript">
			window.location = "user/index.php";
		</script>
		<?
	}
	if($tipo == 'prov')
	{
		?>
		<script type="text/javascript">
			window.location = "prov/index.php?id=";
		</script>
		<?
	}
	else if($tipo != 'client' && $tipo != 'prov')
	{
		?>
		<script type="text/javascript">
			alert("Error ingresando al sistema. Intente nuevamente.");
		</script>
		<?
		$aux = 1;
	}
	
	
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
		Ingresando...
	</div>
</div>
<!-- end header -->
<!-- start page -->
<div id="page">
	<!-- start content -->
	<div id="content">
	<?
	if($aux == 1)
	{ 
	?>
		<div class="post">
			<h1 class="title"><a href="#">Ingreso al Sistema</a></h1>
			<div class="entry"><p><form action="login.php" method="post">
				<div style="width:100%;float:left;">
					<div style="width:20%;float:left;display:inline">ID: </div>
					<div style="width:80%;float:left;display:inline"><input type="text" name="log"></div>
				</div>
				<div style="width:100%;float:left;">
					<div style="width:20%;float:left;display:inline">Contraseña: </div>
					<div style="width:80%;float:left;display:inline"><input type="password" name="pwd"></div>
				</div>
				<div style="width:100%;float:left;">
					<div style="width:20%;float:left;display:inline">&nbsp;</div>
					<div style="width:80%;float:left;display:inline"><input type="submit" value=" Ingresar "></div></form>
				</div>
			</p>
			</div>
		</div>
	<?
	}
	else
	{	
		?>
		
	
		<div class="post">
			<h1 class="title"><a href="#">Redirigiendo</a></h1>
			<div class="entry"><p>Redirigiendo...</p>
			</div>
		</div>
	<?
	}	
	?>
	</div>
	<!-- end content -->
	<!-- start sidebar -->
	<div id="sidebar">
		Redirigiendo...
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
