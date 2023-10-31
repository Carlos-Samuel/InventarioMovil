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

    $respuesta = array(
        "mensaje" => "Los datos fueron borrados correctamente"
    );

    header('Content-Type: application/json');
    echo json_encode($respuesta);

    exit();
    

?>
