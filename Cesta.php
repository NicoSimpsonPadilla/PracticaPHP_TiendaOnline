<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesta</title>
    <link rel="stylesheet" href="views/styles/bootstrap.min.css">
    <link rel="stylesheet" href="views/styles/estilos.css">
    <?php require "util/base_de_datos.php"; ?>
    <?php require "util/producto.php"; ?>
    <?php require "util/depurar.php"; ?>
</head>
<body>
    <?php
    // Usuario online
    session_start();
    if(isset($_SESSION["usuario"])) {
        $usuario = $_SESSION["usuario"];
    } else {
        $_SESSION["usuario"] = "invitado";
        $usuario = $_SESSION["usuario"];
    }
    
    // Invocacion de los productos
    $sql = "SELECT * FROM productos";
    $resultado = $conexion->query($sql);
    $productos = [];

    while ($fila = $resultado -> fetch_assoc()) {
        $nuevo_producto = new Producto(
            $fila["idProducto"], 
            $fila["nombreProducto"], 
            $fila["precio"],
            $fila["descripcion"], 
            $fila["cantidad"], 
            $fila["imagen"]
        );
        array_push($productos, $nuevo_producto);
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
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($productos as $producto) { ?>
                    <tr>
                        <td><?php echo $producto -> nombreProducto ?></td>
                        <td><?php echo "<img class='rounded' src=' " . 
                        $producto -> imagen . " ' width='50%' height='25%'/>" ?></td>
                        <td><?php echo $producto -> precio ?></td>
                        <?php 
                        $sql = "SELECT cantidad FROM ProductosCestas WHERE idProducto = '$producto -> idProducto'";
                        $cantidad = $conexion->query($sql);
                        ?>
                        <td><?php echo  $cantidad ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>