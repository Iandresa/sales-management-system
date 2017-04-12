<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of proveedor
 *
 * @author Sebastián Arancibia
 */
class proveedor {

    protected $db;

    public function __construct()
    {
        $this->db = new mysql();
    }

    function nuevoProveedor($rut,$nombre,$tipo,$correo,$telefono,$direccion,$ciudad,$pais,$fechaRegistro)
    {
        $query = "INSERT INTO Proveedor(rut,nombre,tipo,correo,telefono,direccion,ciudad,pais,fechaRegistro) VALUES('".$rut."','".$nombre."','".$tipo."','".$correo."','".$telefono."','".$direccion."','".$ciudad."','".$pais."',".$fechaRegistro.");";

        if($this->db->query($query))
        {
            return "Proveedor añadido correctamente";
        }
        else
        {
            return "Error añadiendo proveedor";
        }
    }

    function editarProveedor($rut,$nombre,$tipo,$correo,$telefono,$direccion,$ciudad,$pais)
    {
        $query = "UPDATE Proveedor SET  nombre = '".$nombre."',tipo = '".$tipo."', correo = '".$correo."', telefono = '".$telefono."', direccion = '".$direccion."', ciudad = '".$ciudad."', pais = '".$pais."' WHERE rut = '".$rut."';";

        if($this->db->query($query))
        {
            return "Proveedor modificado correctamente";
        }
        else
        {
            return "Error modificando proveedor";
        }
    }

    function borrarProveedor($rut)
    {
        $query = "DELETE FROM Proveedor WHERE rut = '".$rut."';";

        if($this->db->query($query))
        {
            return "Proveedor borrado correctamente";
        }
        else
        {
            return "Error borrando proveedor";
        }
    }
	function getName($id)
	{
		$query = "SELECT nombre FROM Proveedor WHERE rut = '".$id."';";

		$res = $this->db->queryArray($query);

		return $res[0];
	}

	function getDataProv($rut)
	{
		$query = "SELECT nombre,tipo FROM Proveedor WHERE rut = '".$rut."';";

	$res = $this->db->queryArray($query);

	return $res;
	}
}
?>
