<?

include("classes/cliente.php");
include("classes/proveedor.php");

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
	$query2 = "INSERT INTO user_Cliente(login,observaciones,fechaRegistro) VALUES ('".$id."',' ',".$now.");";
		
	if($db->query($query))
    {
		return "Cliente añadido correctamente";
    }        
    else
    {
        return "Error añadiendo cliente";
    }
	
	if($db->query($query2))
    {
		return "Cliente añadido correctamente";
    }        
    else
    {
        return "Error añadiendo cliente";
    }
	
	$cliente->nuevoCliente($id,$apat,$amat,$rs,$giro,$correo,$fono,$pais,$now);
	
	echo "Registro exitoso!";
}

function addProv($id,$password,$correo,$nom,$tipo,$dir,$ciudad,$fono,$pais)
{
	$db = new mysql();
	$prov = new proveedor();
	
	$now = ahora();
	$query = "INSERT INTO user_Usuario(login,password,tipoUsuario,fechaRegistro,correo) VALUES('".$id."','".md5($password)."','prov',".$now.",'".$correo."');";
	$query2 = "INSERT INTO user_Proveedor(login,observaciones,fechaRegistro) VALUES ('".$id."',' ',".$now.");";
	
	if($db->query($query))
    {
		return "Proveedor añadido correctamente";
    }        
    else
    {
        return "Error añadiendo cliente";
    }
	
	if($db->query2($query))
    {
		return "Proveedor añadido correctamente";
    }        
    else
    {
        return "Error añadiendo cliente";
    }
	
	$cliente->nuevoProveedor($id,$nom,$tipo,$correo,$fono,$dir,$ciudad,$pais,$now);
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

function ahora()
{
	return time();
}

?>
