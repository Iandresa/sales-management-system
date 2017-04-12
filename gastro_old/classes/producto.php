<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of producto
 *
 * @author Watanuki
 */
class producto {

    protected $db;

    public function __construct()
    {
        $this->db = new mysql();
    }

    function nuevoProducto($nombre,$idCategoria,$descripcion)
    {
        $query = "INSERT INTO Producto(nombre,idCategoria,descripcion) VALUES('".$nombre."',".$idCategoria.",'".$descripcion."');";

        if($this->db->query($query))
        {
            return "Producto añadido correctamente";
        }
        else
        {
            return "Error añadiendo producto";
        }
    }

    function editarProducto($id,$nombre,$idCategoria,$descripcion)
    {
        $query = "UPDATE Producto SET  nombre = '".$nombre."',idCategoria = ".$idCategoria.",descripcion = '".$descripcion."' WHERE id = ".$id.";";

        if($this->db->query($query))
        {
            return "Producto modificado correctamente";
        }
        else
        {
            return "Error modificando producto";
        }
    }


}
?>
