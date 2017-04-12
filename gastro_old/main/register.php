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
	<script src="js/jquery.metadata.js" type="text/javascript"></script>
	<script src="js/jquery.validate.js" type="text/javascript"></script>
	
	<script type="text/javascript">
$.validator.setDefaults({
	submitHandler: function() { alert("submitted!"); }
});

$().ready(function() {
	// validate the comment form when it is submitted
	$("#commentForm").validate();
	
	// validate signup form on keyup and submit
	$("#signupForm").validate({
		rules: {
			firstname: "required",
			lastname: "required",
			username: {
				required: true,
				minlength: 2
			},
			password: {
				required: true,
				minlength: 8
			},
			confirm_password: {
				required: true,
				minlength: 8,
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
			agree: "required"
		},
		messages: {
			id: {
				required: "Por favor, rellene correctamente el ID (solo numeros, sin digito verificador)"
				number: "Por favor, rellene correctamente el ID (solo numeros, sin digito verificador)"
			},
			firstname: "Por favor complete el campo",
			lastname: "Por favor complete el campo",
			username: {
				required: "Por favor complete el campo",
				minlength: "Rellene correctamente el campo"
			},
			password: {
				required: "Por favor escriba la contraseña deseada",
				minlength: "La contraseña debe ser minimo de 8 caracteres"
			},
			confirm_password: {
				required: "Por favor escriba la contraseña deseada",
				minlength: "La contraseña debe ser minimo de 8 caracteres",
				equalTo: "Las contraseñas no coinciden"
			},
			email: "Por favor ingrese un correo valido",
			agree: "Please accept our policy"
		}
	});
	
	// propose username by combining first- and lastname
	$("#username").focus(function() {
		var firstname = $("#firstname").val();
		var lastname = $("#lastname").val();
		if(firstname && lastname && !this.value) {
			this.value = firstname + "." + lastname;
		}
	});
	
	//code to hide topic selection, disable for demo
	var newsletter = $("#newsletter");
	// newsletter topics are optional, hide at first
	var inital = newsletter.is(":checked");
	var topics = $("#newsletter_topics")[inital ? "removeClass" : "addClass"]("gray");
	var topicInputs = topics.find("input").attr("disabled", !inital);
	// show when newsletter is checked
	newsletter.click(function() {
		topics[this.checked ? "removeClass" : "addClass"]("gray");
		topicInputs.attr("disabled", !this.checked);
	});
});
</script>
<script type="text/javascript">
function idExistente()
{
	alert("Error! ID ya existe");
	window.location = "javascript:history.go(-1)";
}
</script>
	<style type="text/css">
.cmxform fieldset p.error label { color: red; }
div.container {
	background-color: #eee;
	border: 1px solid red;
	margin: 5px;
	padding: 5px;
}
div.container ol li {
	list-style-type: disc;
	margin-left: 20px;
}
div.container { display: none }
.container label.error {
	display: inline;
}
form.cmxform { width: 30em; }
form.cmxform label.error {
	display: block;
	margin-left: 1em;
	width: auto;
}
</style>
<?

include("db/mysql.php");
include("funciones.php");

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
			<?
			/*
			if($_GET['mode'] == 'user')
			{
				if($_GET['password'] != $_GET['confirm_password'])
				{
					echo "Error! Vuelva atrás e intente nuevamente";
				}
				addUser($_POST['id'],$_POST['password'],$_POST['email']);
			}
			if($_GET['mode'] == 'client')
			{

			*/
				if($_GET['password'] != $_GET['confirm_password'])
				{
					echo "Error! Vuelva atrás e intente nuevamente";
				}

				// Consultamos si ya existe el ID registrado
				$id = $_GET['idUser'];

				$cli = new cliente();
				$res = $cli->getDataCliente($id);

				$cant = sizeof($res);

				if($cant > 1)
				{
					?>
					<script type="text/javascript">
					idExistente();
					</script>
					<?
				}
				else if ($cant <= 1)
				{
					addClient($_GET['idUser'],$_GET['password'],$_GET['email'],$_GET['firstname'],$_GET['lastname'],$_GET['lastname2'],$_GET['username'],$_GET['giro'],$_GET['phone'],$_GET['pais']);
				}

/*	
			}
			if($_GET['mode'] == 'prov')
			{
				if($_POST['password'] != $_POST['confirm_password'])
				{
					echo "Error! Vuelva atrás e intente nuevamente";
				}
				addProv($_POST['id'],$_POST['password'],$_POST['email'],$_POST['firstname'],$_POST['lastname'],$_POST['lastname2'],$_POST['username'],$_POST['phone'],$_POST['pais']);
			}

*/
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
