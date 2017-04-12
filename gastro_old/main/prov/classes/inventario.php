<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of inventario
 *
 * @author Watanuki
 */
class inventario {

    protected $db;

    public function __construct()
    {
        $this->db = new mysql();
    }

    function nuevoInventario($idRecinto,$idProducto,$cantidad)
    {
        $query = "INSERT INTO Inventario(idRecinto,idProducto,cantidad) VALUES(".$idRecinto.",".$idProducto.",".$cantidad.");";

        if($this->db->query($query))
        {
            return "Cantidad del producto añadido correctamente";
        }
        else
        {
            return "Error añadiendo cantidad del producto";
        }
    }

    function ingreso($idProducto,$idRecinto,$cantidad)
    {
        $query = "UPDATE Inventario SET  cantidad = cantidad + ".$cantidad." WHERE idProducto = ".$idProducto." AND idRecinto = ".$idRecinto.";";

        if($this->db->query($query))
        {
            return "Ingreso de producto modificado correctamente";
        }
        else
        {
            return "Error modificando ingreso de producto";
        }
    }

    function descuento($idProducto,$idRecinto,$cantidad)
    {
        $query = "UPDATE Inventario SET cantidad = cantidad - ".$cantidad." WHERE idProducto = ".$idProducto." AND idRecinto = ".$idRecinto.";";

        if($this->db->query($query))
        {
            return "Descuento de producto modificado correctamente";
        }
        else
        {
            return "Error modificando descuento de producto";
        }
    }

    function borrarDeInventario($idProducto,$idRecinto)
    {
        $query = "DELETE FROM productoProveedor WHERE idProducto = ".$idProducto." AND idRecinto ".$idrecinto.";";

        if($this->db->query($query))
        {
            return "Producto del recinto borrado correctamente";
        }
        else
        {
            return "Error borrando producto del recinto";
        }

    }
}
?>
