<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Gastro</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="stylesheet" href="js/jquery.treeview.css" />
    <link rel="stylesheet" href="js/red-treeview.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
	
	<script src="js/lib/jquery.js" type="text/javascript"></script>
	<script src="js/lib/jquery.cookie.js" type="text/javascript"></script>
	<script src="js/jquery.treeview.js" type="text/javascript"></script>
	<script src="js/jquery.metadata.js" type="text/javascript"></script>
	<script src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.js" type="text/javascript"></script>
	
	<script type="text/javascript">

$().ready(function() {
	// validate the comment form when it is submitted
	$("#commentForm").validate();
	
	// validate signup form on keyup and submit
	$("#signupForm").validate({
		rules: {
			idUser:	{
				required: true,
				minlength: 8
			},
			firstname: "required",
			lastname: "required",
			lastname2: "required",
			username: {
				required: true,
				minlength: 2
			},
			giro: "required",
			password: {
				required: true,
				minlength: 6
			},
			confirm_password: {
				required: true,
				minlength: 6,
				equalTo: "#password"
			},
			email: {
				required: true,
				email: true
			},
			topic: {
				required: "#newsletter:checked",
				minlength: 2
			},
			phone: {
				required: true,
				digits: true
			},
			pais: "required"
		},
		messages: {
			idUser: {
				required: "Introduzca el ID de usuario (rut, etc, sin guión)",
				minlength: "El ID debe contener mas de 8 digitos o caracteres"
			},
			firstname: "Introduzca el primer nombre",
			lastname: "Introduzca el apellido paterno",
			lastname2: "Introduzca el apellido materno",
			username: "Introduzca la razón social",
			giro: "Introduzca el giro",
			password: {
				required: "Introduzca una contraseña",
				minlength: "La contraseña debe tener un mínimo de 6 caracteres"
			},
			confirm_password: {
				required: "Introduzca una contraseña",
				minlength: "La contraseña debe tener un mínimo de 6 caracteres",
				equalTo: "Las contraseñas deben coincidir"
			},
			email: "Please enter a valid email address",
			phone:
				{
				required: "Introduzca un número telefónico",
				digits: "Por favor, solo números (cod area + numero)"
				},
			pais: "Introduzca un país"
		}
	});
	
	
	
});
</script>
	<style type="text/css">
#formData
{
	display:inline;
	float:left;
	width:30%;
}

#formInput
{
	display:inline;
	float:left;
	width:70%;
}


#commentForm { width: 500px; }
#commentForm label { width: 250px; }
#commentForm label.error, #commentForm input.submit { margin-left: 253px; }
#signupForm { width: 500px; }
#signupForm label.error {
	margin-left: 10px;
	width: auto;
	display: inline;
}
#newsletter_topics label.error {
	display: none;
	margin-left: 103px;
}
</style>

<link href="default.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<!-- start header -->
<div id="header">
	<div id="logo">
		<h1>Gastro</h1> <i>beta</i>
	</div>
	<div id="search">
		Nuevo Cliente.
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
			<?
			if($_POST['tipo'] == 'user')
			{
				$action = 'register.php?mode=user';
			}
			if($_POST['tipo'] == 'client')
			{
				$action = 'register.php?mode=client';
			}
			if($_POST['tipo'] == 'prov')
			{
				$action = 'register.php?mode=prov';
			}
			?>
			<form id="signupForm" method="get" action="<? echo $action; ?>">
	<fieldset>
		<legend>Registro de nuevo usuario</legend>
		<p>
			<label for="idUser">ID</label>
			<input id="idUser" name="idUser" value="<? echo $_POST['id']; ?>" />
		</p>
		
		<p>
			<label for="password">Contraseña</label>
			<input id="password" name="password" type="password" />
		</p>

		<p>
			<label for="confirm_password">Confirmar Contraseña</label>
			<input id="confirm_password" name="confirm_password" type="password" />
		</p>
		<?
		// Aqui dividimos dependiendo del tipo de usuario.
		// Primero, veamos el caso de admin de recintos.
		if($_POST['tipo'] == 'client')
		{
		?>
		<form class="cmxform" id="signupForm" method="post" action="<? echo $action; ?>">
	<fieldset>
		
		<p>
			<label for="firstname">Nombre</label>
			<input id="firstname" name="firstname" />
		</p>
		<p>
			<label for="lastname">Apellido Paterno</label>
			<input id="lastname" name="lastname" />
		</p>
		<p>
			<label for="lastname2">Apellido Materno</label>
			<input id="lastname2" name="lastname2" />
		</p>

		<p>
			<label for="username">Razon Social</label>
			<input id="username" name="username" />
		</p>
		<p>
			<label for="giro">Giro</label>
			<input id="giro" name="giro" />
		</p>
		<p>
			<label for="phone">Fono</label>
			<input id="phone" name="phone" />
		</p>
		
		<p>
			<label for="email">Email</label>
			<input id="email" name="email" value="<? echo $_POST['mail']; ?>"/>
		</p>

		<p>
			<label for="pais">Pais</label>
			<input id="pais" name="pais" />
		</p>

		
		<p>
			<input class="submit" type="submit" value=" Nuevo Registro "/>
		</p>

	</fieldset>
</form>
		<?
		}
		?>
		<?
		// Caso para proveedores
		if($_POST['tipo'] == 'prov')
		{
		?>
		<p>
			<label for="firstname">Nombre Proveedor </label>
			<input id="firstname" name="firstname" />
		</p>
		<p>
			<label for="lastname">Tipo Proveedor</label>
			<input id="lastname" name="lastname" />
		</p>
		<p>
			<label for="lastname2">Dirección</label>
			<input id="lastname" name="lastname2" />
		</p>
		<p>
			<label for="username">Ciudad</label>
			<input id="username" name="username" />
		</p>
		<p>
			<label for="pais">País</label>
			<input id="lastname" name="pais" />
		</p>		
		<p>
			<label for="email">Correo Electrónico</label>
			<input id="email" name="email" value="<? echo $_POST['mail']; ?>" />
		</p>
		<p>
		<label for="phone">Fono</label>
			<input id="phone" name="phone" class="some styles {validate:{required:true,number:true, rangelength:[6,12]}}" value="<? echo $_POST['id']; ?>" />
		</p>
		<?
		}
		?>
		<?
		// Aqui dividimos dependiendo del tipo de usuario.
		// Primero, veamos el caso de admin de recintos.
		if($_POST['tipo'] == 'user')
		{
		?>		
		<p>
			<label for="email">Correo Electrónico</label>
			<input id="email" name="email" value="<? echo $_POST['mail']; ?>" />
		</p>

		<p>
			<input class="submit" type="submit" value=" Registrar "/></fieldset></form>
		</p>
		<?
		}
		?>
		
	

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
