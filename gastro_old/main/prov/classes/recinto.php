<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of recinto
 *
 * @author Watanuki
 */
class recinto {

    protected $db;

    public function __construct()
    {
        $this->db = new mysql();
    }

    function nuevoRecinto($rut,$nombre,$direccion,$ciudad,$estado,$anioInicio,$correo,$telefono,$pais,$fechaCreacion)
    {
        $query = "INSERT INTO Recinto(rut,nombre,direccion,ciudad,estado,anioInicio,correo,telefono,pais,fechaCreacion) VALUES('".$rut."','".$nombre."','".$direccion."','".$ciudad."','".$estado."',".$anioInicio.",'".$correo."',".$telefono.",'".$pais."',".$fechaCreacion.");";

        if($this->db->query($query))
        {
            return "Recinto añadido correctamente";
        }
        else
        {
            return "Error añadiendo recinto";
        }
    }

    function editarRecinto($id,$nombre,$direccion,$ciudad,$correo,$telefono)
    {
        $query = "UPDATE Recinto SET nombre = '".$nombre."', direccion = '".$direccion."',ciudad = '".$ciudad."', correo = '".$correo."', telefono = ".$telefono." WHERE id = '".$id."';";

        if($this->db->query($query))
        {
            return "Recinto modificado correctamente";
        }
        else
        {
            return "Error modificando recinto";
        }
    }

    function cierreRecinto($id)
    {
        $query = "UPDATE Recinto SET estado = 'Cerrado' WHERE id = '".$id."';";

        if($this->db->query($query))
        {
            return "Recinto cerrado correctamente";
        }
        else
        {
            return "Error cerrando recinto";
        }

    }


	function contarRecintos($rut)
	{
		$query = "SELECT COUNT(*) FROM Recinto WHERE rut = '".$rut."';";

		return $this->db->contar($query);
	}


	function getRecintos($rut)
	{
		$query = "SELECT * FROM Recinto WHERE rut = '".$rut."';";

		$res = $this->db->queryTotal($query);

		return $res;
	}



}
?>
