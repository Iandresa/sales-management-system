<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of evento
 *
 * @author Watanuki
 */
class evento {

    protected $db;

    public function __construct()
    {
        $this->db = new mysql();
    }

    function nuevoEvento($idRecinto,$nombre,$descripcion,$fechaInicio,$fechaFin,$fechaCreacion)
    {
        $query = "INSERT INTO Evento(rut,nombre,descripcion,fechaInicio,fechaFin,fechaCreacion) VALUES('".$rut."','".$nombre."','".$descripcion."','".$fechaInicio."','".$fechaFin."',".$fechaCreacion.");";

        if($this->db->query($query))
        {
            return "Evento añadido correctamente";
        }
        else
        {
            return "Error añadiendo evento";
        }
    }

    function editarEvento($id,$idRecinto,$nombre,$descripcion,$fechaInicio,$fechaFin)
    {
        $query = "UPDATE Evento SET  nombre = '".$nombre."',descripcion = '".$descripcion."', idRecinto = '".$idRecinto."', fechaInicio = '".$fechaInicio."', fechaFin = '".$fechaFin."' WHERE id = '".$id."';";

        if($this->db->query($query))
        {
            return "Evento modificado correctamente";
        }
        else
        {
            return "Error modificando evento";
        }
    }

    function borrarEvento($id)
    {
        $query = "DELETE FROM Evento WHERE id = '".$id."';";

        if($this->db->query($query))
        {
            return "Evento borrado correctamente";
        }
        else
        {
            return "Error borrando evento";
        }

    }
}
?>
