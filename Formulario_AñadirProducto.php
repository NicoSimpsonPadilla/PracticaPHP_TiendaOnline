<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario_Añadir_Producto</title>
    <link rel="stylesheet" href="CSS/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/estilos.css">
    <?php require 'Utilidades/depurar.php'; ?>
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $temp_nombre = depurar($_POST["nombre"]);
        $temp_nombre = preg_replace("/[ ]{2,}/", ' ', $temp_nombre);
        $temp_precio = depurar($_POST["precio"]);
        $temp_descripcion = depurar($_POST["descripcion"]);
        $temp_descripcion = preg_replace("/[ ]{2,}/", ' ', $temp_descripcion);
        $temp_cantidad = depurar($_POST["cantidad"]);

        // Validacion del nombre
        if(!strlen($temp_nombre) > 0) {
            $err_nombre = "El producto debe de tener nombre.";
        } else {
            if (strlen($temp_nombre) > 40) {
                $err_nombre = "El nombre no puede ser tan largo";
            } else {
                // /^[a-zA-Z0-9]{4,8}$/
                $patron = "/^[a-zA-Z0-9 ]{1,40}$/";
                if(!preg_match($patron, $temp_nombre)) {

                } else {
                    $nombre = ucwords(strtolower($temp_nombre));
                }
            }
        }

        // Validacion del precio


        // Validacion de la descripcion


        // Validacion de la cantidad

    }
    ?>

    <div class="añadirProducto container">
        <div class="añadirProducto_content container">
            <h1>Formulario Añadir Producto</h1>
            <div class="col-9">
                <form action="" method="post">
                    <div class="mb-3">
                        <label class="form-label">Nombre del producto: </label>
                        <input class="form-control" type="text" name="nombre">
                        <?php if(isset($err_nombre)) echo $err_nombre ?>
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
                    <button class="btn btn-dark" type="submit">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>