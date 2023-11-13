<?php
class Producto {
    public int $id_producto;
    public string $nombreProducto;
    public float $precio;
    public string $descripcion;
    public int $cantidad;
    public string $imagen;

    function __construct($id_producto, $nombreProducto, $precio, 
    $descripcion, $cantidad, $imagen) {

        $this -> idProducto = $id_producto;
        $this -> nombreProducto = $nombreProducto;
        $this -> precio = $precio;
        $this -> descripcion = $descripcion;
        $this -> cantidad = $cantidad;
        $this -> imagen = $imagen;
    }
}
?>