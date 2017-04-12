<?


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
		$query = "SELECT rut,nombre,direccion,ciudad,estado,correo,telefono,pais FROM Recinto WHERE id = ".$id.";";
		$res = $db->queryArray($query);
		
		return $res;
	}

?>
