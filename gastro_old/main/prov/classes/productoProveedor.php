<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of productoProveedor
 *
 * @author Watanuki
 */
class productoProveedor {

    protected $db;

    public function __construct()
    {
        $this->db = new mysql();
    }

    function nuevoProductoProveedor($idProducto,$idProveedor,$valorNeto)
    {
        $query = "INSERT INTO productoProveedor(idProducto,idProveedor,valorNeto) VALUES(".$idProducto.",".$idProveedor.",".$valorNeto.");";

        if($this->db->query($query))
        {
            return "Valor del producto añadido correctamente";
        }
        else
        {
            return "Error añadiendo valor del producto";
        }
    }

    function editarPrecio($idProducto,$idProveedor,$valorNeto)
    {
        $query = "UPDATE productoProveedor SET  valorNeto = ".$valorNeto." WHERE idProducto = ".$idProducto." AND idProveedor = ".$idProveedor.";";

        if($this->db->query($query))
        {
            return "Precio de producto modificado correctamente";
        }
        else
        {
            return "Error modificando precio de producto";
        }
    }

    function borrarProductoProveedor($idProducto,$idProveedor)
    {
        $query = "DELETE FROM productoProveedor WHERE idProducto = ".$idProducto." AND idProveedor ".$idProveedor.";";

        if($this->db->query($query))
        {
            return "Producto borrado correctamente";
        }
        else
        {
            return "Error borrando producto";
        }

    }
}
?>
