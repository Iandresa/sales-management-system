<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cliente
 *
 * @author Sebastián Arancibia
 */
class cliente {
    
    protected $db;
    
    public function __construct()
    {
        $this->db = new mysql();
    }
    
    function nuevoCliente($rut,$nombre,$apellidoPaterno,$apellidoMaterno,$razonSocial,$giro,$correo,$telefono,$pais,$fechaRegistro)
    {
        $query = "INSERT INTO Cliente(rut,nombre,apellidoPaterno,apellidoMaterno, razonSocial, giro, correo, telefono, pais,fechaRegistro) VALUES('".$rut."','".$nombre."','".$apellidoPaterno."','".$apellidoMaterno."','".$razonSocial."','".$giro."','".$correo."','".$telefono."','".$pais."',".$fechaRegistro.");";
        
        if($this->db->query($query))
        {
            return "Cliente añadido correctamente";
        }        
        else
        {
            return "Error añadiendo cliente";
        }
    }
    
    function editarCliente($rut,$nombre,$apellidoPaterno,$apellidoMaterno,$razonSocial,$giro,$correo,$telefono,$pais)
    {
        $query = "UPDATE Cliente SET  nombre = '".$nombre."',apellidoMaterno = '".$apellidoMaterno."', apellidoPaterno = '".$apellidoPaterno."', razonSocial = '".$razonSocial."', giro = '".$giro."', correo = '".$correo."', telefono = '".$telefono."', pais = '".$pais."' WHERE rut = '".$rut."';";
        
        if($this->db->query($query))
        {
            return "Cliente modificado correctamente";
        }        
        else
        {
            return "Error modificando cliente";
        }
    }
    
    function borrarCliente($rut)
    {
        $query = "DELETE FROM Cliente WHERE rut = '".$rut."';";
        
        if($this->db->query($query))
        {
            return "Cliente borrado correctamente";
        }        
        else
        {
            return "Error borrando cliente";
        }
        
    }

	function getDataCliente($rut)
	{
		$query = "SELECT nombre,apellidoPaterno,apellidoMaterno,razonSocial,giro,capital FROM Cliente WHERE rut = '".$rut."';";

	$res = $this->db->queryArray($query);

	return $res;
	}

	function getAll()
	{
		$query = "SELECT rut,razonSocial FROM Cliente";

		$res = $this->db->queryTotal($query);

		return $res;
	}
    
    
}
?>
