<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesta</title>
    <link rel="stylesheet" href="views/styles/bootstrap.min.css">
    <link rel="stylesheet" href="views/styles/estilos.css">
    <?php require "util/base_de_datos.php"; ?>
    <?php require "util/productoCesta.php"; ?>
    <?php require "util/depurar.php"; ?>
</head>
<body>
    <?php
    // Usuario online
    session_start();
    if(isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
    } else {
        header('location: Principal.php');
    }
    $numProductos = 0;
    $sql = "SELECT * FROM cestas WHERE usuario = '$usuario'";
    $resultado = $conexion->query($sql);
    while ($row = $resultado->fetch_assoc()) {
        $idCesta = $row["idCesta"];
        $precioTotal = $row["precioTotal"];
    }

    // Obtengo los productos que hay en la cesta del usuario
    $sql = "SELECT p.idProducto as idProducto, p.nombreProducto as nombre, p.precio as precio, p.imagen as imagen, p.cantidad as cantidad FROM PRODUCTOS p JOIN productoscestas pc ON p.idProducto = pc.idProducto WHERE pc.idCesta = $idCesta";
    $resultado = $conexion->query($sql);
    $productos = [];
    while ($row = $resultado->fetch_assoc()) {
        $nuevoProducto = new productoCesta($row["idProducto"], $row["nombre"], $row["precio"], $row["imagen"], $row["cantidad"]);
        array_push($productos, $nuevoProducto);
        $numProductos++;
    }

    // Funcion Eliminar
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_producto = depurar($_POST["id_producto"]);
        $precio = depurar($_POST["precio"]);
        $cantidad = depurar($_POST["cantidad"]);

        $id_cesta = -1;
        // Defino la id_cesta
        $sql = "SELECT * FROM Cestas WHERE usuario = '$usuario'";
        $resultado = $conexion->query($sql);
        while ($fila = $resultado -> fetch_assoc()) {
            $id_cesta = $fila["idCesta"];
        }

        $sql = "DELETE FROM productoscestas 
        WHERE (idProducto = '$id_producto') and (idCesta = '$idCesta')";
        $conexion -> query($sql);

        $precioCantidad = $cantidad * $precio;
        $sqlPrecioTotal = "UPDATE Cestas SET precioTotal = precioTotal - $precioCantidad WHERE idCesta = $id_cesta";
        $conexion-> query($sqlPrecioTotal);
        
        header('location: Cesta.php');    
    }

    ?>

    <div class="alert alert-primary align-items-center" role="alert">
        <h2>Cesta de <?php echo $usuario ?></h2>
        <?php
        if ($usuario != "invitado") {
        ?>
            <a href="Principal.php" class="btn btn-outline-primary">Volver</a>
        <?php
        }
        ?>
   </div>
   
    <div class="container">
        <h1>Inicio</h1>
        <table class="table table-secondary table-hover text-center">
            <thead class="table table-dark">
                <tr>
                    <th class="col-md-1">Nombre</th>
                    <th class="col-md-6">Imagen</th>
                    <th class="col-md-1">Precio</th>
                    <th class="col-md-1">Cantidad</th>
                    <th class="col-md-1">Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($productos as $producto) { ?>
                    <?php 
                    $form_idProducto = $producto -> idProducto;
                    $sql = "SELECT * FROM ProductosCestas WHERE idProducto = '$form_idProducto' AND idCesta = '$idCesta'";
                    $resultado = $conexion->query($sql);
                    while ($fila = $resultado -> fetch_assoc()) {
                        $cantidad = $fila["cantidad"];
                    }
                    ?>
                    <tr>
                        <td><?php echo $producto -> nombreProducto ?></td>
                        <td><?php echo "<img class='rounded' src=' " . 
                        $producto -> imagen . " ' width='50%' height='25%'/>" ?></td>
                        <td><?php echo $producto -> precio ?></td>
                        <td><?php echo  $cantidad ?></td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="id_producto"
                                value="<?php echo $form_idProducto ?>">
                                <input type="hidden" name="precio"
                                value="<?php echo $producto -> precio ?>">
                                <input type="hidden" name="cantidad"
                                value="<?php echo $cantidad ?>">

                                <input class="btn btn-dark" type="submit" value="Eliminar">
                            </form>
                        </td>
                </tr>
                <?php
                }
                ?>
                <tfoot class="table table-dark">
                    <tr>
                        <td colspan="4">Total del carrito</td>
                        <td><?php echo $precioTotal; ?></td>
                    </tr>
                </tfoot>
            </tbody>
        </table>
    </div>
    <div class="container">
        <form method="post" action="util/pedido.php">
            <input type="hidden" name="precioTotal" value ="<?php echo $precioTotal ?>">
            <input type="hidden" name="idCesta" value="<?php echo $id_cesta ?>">
            <input type="hidden" name="numeroProductos" value="<?php echo $numeroProductos ?>">
            <input type="submit" name="pedir" value="Pedir" class="btn btn-dark">
        </form>
    </div>
</body>
</html>