<?
include("classes/all.php");

// Archivo de funciones básicas del sistema.
// Usado tambièn para acceso, creación de usuarios y login.
// Además de proveer funciones comunes y típicas.

// Funciones de acceso a BD

// Añadir nuevo usuario
function addUser($id,$password,$correo)
{
	$db = new mysql();
	
	$now = ahora();
	$query = "INSERT INTO user_Usuario(login,password,tipoUsuario,fechaRegistro,correo) VALUES('".$id."','".md5($password)."','user',".$now.",'".$correo."');";
	
	if($db->query($query))
    {
		return "Cliente añadido correctamente";
    }        
    else
    {
        return "Error añadiendo cliente";
    }
	
	echo "Registro exitoso!";
	?>
	<script type="text/javascript">
alert("Registro Exitoso!  Ingrese con sus datos en el formulario del centro");
window.location = "../index.html";
</script>
	<?
}

function addClient($id,$password,$correo,$nom,$apat,$amat,$rs,$giro,$fono,$pais)
{
	$db = new mysql();
	$cliente = new cliente();
	
	$now = ahora();
	$query = "INSERT INTO user_Usuario(login,password,tipoUsuario,fechaRegistro,correo) VALUES('".$id."','".md5($password)."','client',".$now.",'".$correo."');";
	$query2 = "INSERT INTO user_Cliente(login,observaciones,fechaRegistro) VALUES ('".$id."','Observacion',".$now.");";
		
	$db->query($query);
	$db->query($query2);
	$cliente->nuevoCliente($id,$nom,$apat,$amat,$rs,$giro,$correo,$fono,$pais,$now);
	echo "Registro exitoso!";

	$from = "From: Gastro <noresponder@iandresa.com>";
	$to = $correo;
	$asunto = "Registro en Gastro";
	$mensaje = "Estimado ".$nom." ".$apat."\n\nLe comunicamos que se ha registrado correctamente en Gastro. Por favor guarde cuidadosamente los siguientes datos, que le servirán para iniciar sesión en el sistema:\n\n----------\nID: ".$id."\nContraseña: ".$password."\n----------\n\nDisfrute el sistema.\n\nPD: No responda este correo.\n\n--\nGastro.";

	mail($to,$asunto,$mensaje,$from);
	?>
	<script type="text/javascript">
alert("Registro Exitoso!  Ingrese con sus datos en el formulario del centro");
window.location = "../index.html";
</script>
	<?

}

function addProv($id,$password,$correo,$nom,$tipo,$dir,$ciudad,$fono,$pais)
{
	$db = new mysql();
	$prov = new proveedor();
	
	$now = ahora();
	$query = "INSERT INTO user_Usuario(login,password,tipoUsuario,fechaRegistro,correo) VALUES('".$id."','".md5($password)."','prov',".$now.",'".$correo."');";
	$query2 = "INSERT INTO user_Proveedor(login,observaciones,fechaRegistro) VALUES ('".$id."','Observacion',".$now.");";
	
	$db->query($query);
	$db->query($query2);
	
	$prov->nuevoProveedor($id,$nom,$tipo,$correo,$fono,$dir,$ciudad,$pais,$now);
	echo "Registro exitoso!";
	?>
	<script type="text/javascript">
alert("Registro Exitoso! Ingrese con sus datos en el formulario del centro");
window.location = "../index.html";
</script>
	<?
	
}

function login($id,$pwd)
{
	// Primero consultamos si existe en la BD.

	$db = new mysql();

	$query = "SELECT * FROM user_Usuario WHERE login = '".$id."' AND password = '".md5($pwd)."';";

	$res = $db->queryArray($query);

	return $res;

}

function getOpciones($id)
{
	$db = new mysql();

	$query = "SELECT pais,impuesto,cantCifras FROM user_Opciones WHERE login = '".$id."';";

	$res = $db->queryArray($query);

	return $res;
}

function setOpciones($id,$pais,$iva,$ccif)
{
	$db = new mysql();
	$query = "UPDATE user_Opciones SET pais = '".$pais."', impuesto = ".$iva.", cantCifras = ".$ccif." WHERE login = '".$id."';";
	
	$db->query($query);

}

function ahora()
{
	return time();
}

function getNameRecinto($id)
{
	$db = new mysql();

	$query = "SELECT nombre FROM Recinto WHERE id = ".$id.";";

	$nom = $db->queryArray($query);

	return $nom[0];

}

function getDataRecinto($id)
	{
		$db = new mysql();
		$query = "SELECT rut,nombre,direccion,ciudad,estado,correo,telefono,pais,capital FROM Recinto WHERE id = ".$id.";";
		$res = $db->queryArray($query);
		
		return $res;
	}

function getCapIn($id)
{
	$db = new mysql();
		$query = "SELECT capital FROM Recinto WHERE id = ".$id.";";

		$res = $db->queryArray($query);
		
		$cap = intval($res[0]);

		return $cap;
}

function getMensajes($id)
{
	$db = new mysql();

	$query = "SELECT idMensaje, remitente, destino, asunto, mensaje, estado FROM user_Mensaje WHERE destino = ".$id.";";

	$res = $db->queryTotal($query);

	return $res;
}

function getMensajesEnviados($id)
{
	$db = new mysql();

	$query = "SELECT idMensaje, remitente, destino, asunto, mensaje, estado FROM user_Mensaje WHERE remitente = ".$id.";";

	$res = $db->queryTotal($query);

	return $res;
}

function getMensaje($id)
{
	$db = new mysql();

	$query = "SELECT idMensaje, remitente, destino, asunto, mensaje, estado FROM user_Mensaje WHERE idMensaje = ".$id.";";

	$res = $db->queryArray($query);

	return $res;
}

function marcarLeido($id)
{
	$db = new mysql();

	$query = "SELECT estado FROM user_Mensaje WHERE idMensaje = ".$id.";";

	$res = $db->queryArray($query);

	// Si ya fue leido, no hacemos nada. Si no ha sido leido, cambiamos el valor
	if($res[0] == 0)
	{
		$query = "UPDATE user_Mensaje SET leido = 1 WHERE idMensaje = ".$id.";";

		$db->query($query);
	}
}

function searchName($id)
{
	// Debemos buscar primero si existe en los clientes, luego en los proveedores.
	$cli = new cliente();
	$prov = new proveedor();

	$nameCliente = $cli->getDataCliente($id);
	if($nameCliente[3] != '' && isset($nameCliente[3]))
	{
		return $nameCliente[3];
	}
	else if($nameCliente[3] == '' || !isset($nameCliente[3]))
	{
		$nameProv = $prov->getDataProv($id);
		if($nameProv[0] != '' && isset($nameProv[0]))
		{
			return $nameProv[0];
		}
		else if($nameProv[0] == '' || !isset($nameProv[0]))
		{
			return 0;
		}

	}
}

function searchMail($id)
{
	// Pendiente (hay que ver el mail a traves de la tabla de registro)
}

function enviarMensaje($remitente,$destino,$asunto,$mensaje)
{
	$db = new mysql();
	
	$query = "INSERT INTO user_Mensaje(remitente,destino,asunto,mensaje,estado) VALUES('".$remitente."','".$destino."','".$asunto."','".$mensaje."',0);";

	$db->query($query);

	?><script type="text/javascript">alert("Mensaje Enviado Correctamente");</script><?
	echo "Mensaje Enviado!";

	// Enviamos un correo de notificación al destinatario.
	$mail = searchMail($destino);
	$name = searchName($destino);
	$asunto = "Nuevo Mensaje en Gastro";
	$mensaje = "Estimado ".$name.":\n\nHa recibido un nuevo mensaje en Gastro! Para verlo, por favor ingrese al sistema: http://www.iandresa.com/Gastro/login.php\n\nGracias por su atención!\n\n--\nGastro.";

	mail($mail,$asunto,$mensaje,"<From: Gastro>");
}

function noLeidos($id)
{
	$db = new mysql();

	$query = "SELECT COUNT(estado) FROM user_Mensaje WHERE destino = '".$id."' AND estado = 0;";

	$res = $db->queryArray($query);

	return $res[0];
}

function camCapIn($id,$cap)
{
	$db = new mysql();

	$query = "UPDATE Recinto SET capital = ".$cap." WHERE id = ".$id.";";

	$db->query($query);
}

function getRedirect($id,$tipoUsuario)
{
	if($tipoUsuario == 'client')
	{
		$red = "client/index.php?id=".$id;
	}
	else if($tipoUsuario == 'prov')
	{
		$red = "prov/index.php?id=".$id;
	}
	else
	{
		$red = "login.php";
	}

	return $red;
}

?>
