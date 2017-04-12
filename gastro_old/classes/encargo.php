<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of encargo
 *
 * @author Sebastián Arancibia
 */
class encargo {

    protected  $db;

    public function __construct()
    {
        $this->db = new mysql();
    }

    function nuevoEncargo($fecha,$idProveedor,$idProducto,$descuento,$impuesto,$estado)
    {
        $query = "INSERT INTO Encargo(fecha,idProveedor,idProducto,descuento,impuesto,estado) VALUES(".$fecha.",".$idProveedor.",".$idProducto.",".$descuento.",".$impuesto.",'".$estado."');";

        if($this->db->query($query))
        {
            return "Encargo añadido correctamente";
        }
        else
        {
            return "Error añadiendo encargo";
        }
    }



    function anularEncargo($id,$fecha)
    {
        $query = "UPDATE Encargo SET tipo = 'Anulado', fecha = ".$fecha." WHERE idEncargo = ".$id.";";

        if($this->db->query($query))
        {
            return "Encargo anulado correctamente";
        }
        else
        {
            return "Error anulando encargo";
        }
    }
}
?>
