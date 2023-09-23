<?php
    require_once 'Connection.php';

    require '../../vendor/autoload.php'; // Incluye el autoload de Composer

    $con = Connection::getInstance()->getConnection();

    // try{

    //     $consulta = "DELETE FROM Bitacora;";

    //     $finalConsulta = $con->query($consulta);

    //     $consulta1 = "DELETE FROM Productos;";

    //     $finalConsulta1 = $con->query($consulta1);

    //     $consulta2 = "DELETE FROM Facturas;";

    //     $finalConsulta2 = $con->query($consulta2);
        

    // } catch (Exception $e) {
    //     echo "Error: " . $e->getMessage();
    // }

    // $con->close();

?>