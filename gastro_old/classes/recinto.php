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

    function nuevoRecinto($rut,$direccion,$ciudad,$estado,$anioInicio,$correo,$telefono,$pais)
    {
        $query = "INSERT INTO Recinto(rut,direccion,ciudad,estado,anioInicio,correo,telefono,pais) VALUES('".$rut."','".$direccion."','".$ciudad."','".$estado."',".$anioInicio.",'".$correo."',".$telefono.",'".$pais."');";

        if($this->db->query($query))
        {
            return "Recinto añadido correctamente";
        }
        else
        {
            return "Error añadiendo recinto";
        }
    }

    function editarRecinto($id,$direccion,$ciudad,$correo,$telefono)
    {
        $query = "UPDATE Recinto SET  direccion = '".$direccion."',ciudad = '".$ciudad."', correo = '".$correo."', telefono = ".$telefono." WHERE id = '".$id."';";

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
}
?>
