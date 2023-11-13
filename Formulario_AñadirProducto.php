<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario_Añadir_Producto</title>
    <link rel="stylesheet" href="CSS/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/estilos.css">
    <?php require 'Utilidades/depurar.php'; ?>
    <?php require 'Utilidades/base_de_datos.php'; ?>
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $temp_nombreProducto = depurar($_POST["nombre"]);
        $temp_nombreProducto = preg_replace("/[ ]{2,}/", ' ', $temp_nombreProducto);
        $temp_precio = depurar($_POST["precio"]);
        $temp_descripcion = depurar($_POST["descripcion"]);
        $temp_descripcion = preg_replace("/[ ]{2,}/", ' ', $temp_descripcion);
        $temp_cantidad = depurar($_POST["cantidad"]);

        //  $_FILES["nombreCampo"]["queQueremosCoger"] -> TYPE, NAME, SIZE, TMP_NAME
        $nombre_imagen = $_FILES["imagen"]["name"];
        $tipo_imagen = $_FILES["imagen"]["type"];
        $tamano_imagen = $_FILES["imagen"]["size"];
        $ruta_temporal = $_FILES["imagen"]["tmp_name"];
        $ruta_final = "imagenes/" . $nombre_imagen;
        move_uploaded_file($ruta_temporal, $ruta_final);

        // Validacion del nombre
        if(!strlen($temp_nombreProducto) > 0) {
            $err_nombreProducto = "El producto debe de tener nombre.";
        } else {
            if (strlen($temp_nombreProducto) > 40) {
                $err_nombreProducto = "El nombre no puede ser tan largo";
            } else {
                // /^[a-zA-Z0-9 ]{1,40}$/
                $patron = "/^[a-zA-Z0-9 ]{1,40}$/";
                if(!preg_match($patron, $temp_nombreProducto)) {

                } else {
                    $nombreProducto = ucwords(strtolower($temp_nombreProducto));
                }
            }
        }

        // Validacion del precio
        if(!strlen($temp_precio) > 0) {
            $err_precio = "El producto debe de tener precio.";
        } else {
            if ($temp_precio > 99999.99) {
                $err_precio = "El precio no puede ser tan alto";
            } else {
                if ($temp_precio <= 0) {
                    $err_precio = "No puede ser un numero negativo o 0";
                } else {
                    $precio = $temp_precio;
                }
            }
        }

        // Validacion de la descripcion
        if(!strlen($temp_descripcion) > 0) {
            $err_descripcion = "El producto debe de tener descripcion.";
        } else {
            if (strlen($temp_descripcion) > 255) {
                $err_descripcion = "La descripcion no puede ser tan larga";
            } else {
                $descripcion = $temp_descripcion;
            }
        }

        // Validacion de la cantidad
        if(!strlen($temp_cantidad) > 0) {
            $err_cantidad = "El producto debe de tener cantidad.";
        } else {
            if (strlen($temp_cantidad) > 5) {
                $err_cantidad = "La cantidad no puede ser tan alta";
            } else {
                if ($temp_cantidad <= 0) {
                    $err_cantidad = "No puede ser un numero negativo o 0";
                } else {
                    $cantidad = $temp_cantidad;
                }
            }
        }

        // Validacion de la imagen
        if($nombre_imagen == "") {
            $err_cantidad = "Debe de haber una imagen.";
        }

    }
    ?>

    <div class="añadirProducto container">
        <div class="añadirProducto_content container">
            <h1>Formulario Añadir Producto</h1>
            <div class="col-9">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Nombre del producto: </label>
                        <input class="form-control" type="text" name="nombre">
                        <?php if(isset($err_nombreProducto)) echo $err_nombreProducto ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Precio del producto: </label>
                        <input class="form-control" type="number" name="precio">
                        <?php if(isset($err_precio)) echo $err_precio ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripcion del producto: </label>
                        <input class="form-control" type="text" name="descripcion">
                        <?php if(isset($err_descripcion)) echo $err_descripcion ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cantidad del producto: </label>
                        <input class="form-control" type="number" name="cantidad">
                        <?php if(isset($err_cantidad)) echo $err_cantidad ?>
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Imagen del producto: </label>
                    <input class="form-control" type="file" name="imagen">
                        <?php if(isset($err_imagen)) echo $err_imagen ?>
                    </div>
                    <button class="btn btn-dark" type="submit">Enviar</button>
                </form>
            </div>
        </div>
    </div>

    <?php
    if(isset($nombreProducto) && isset($precio) && isset($descripcion) 
    && isset($cantidad) && isset($ruta_final)) {

        $sql = "INSERT INTO Productos (nombreProducto, precio, descripcion, cantidad, imagen)
            VALUES ('$nombreProducto', '$precio', '$descripcion', '$cantidad', '$ruta_final')";

        $conexion -> query($sql);
    }
    ?>
</body>
</html>