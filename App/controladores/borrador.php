<?php
    require_once 'Connection.php';
    require_once 'Connection2.php';

    $con = Connection::getInstance()->getConnection();

    try{

        $consulta = "DELETE FROM Bitacora;";

        $finalConsulta = $con->query($consulta);

        $consulta1 = "DELETE FROM Productos;";

        $finalConsulta1 = $con->query($consulta1);

        $consulta2 = "DELETE FROM Facturas;";

        $finalConsulta2 = $con->query($consulta2);
        

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $con->close();

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ejemplo de PHP</title>
    </head>
    <body>
        <a href="/dashboard.php">Volver</a>
    </body>
</html>
