<?php

    require_once 'Connection.php';
    include 'funciones.php';

    session_start();

    try {
        
        $con = Connection::getInstance()->getConnection();

        //$data = json_decode(file_get_contents("php://input"), true);

        if (isset($_REQUEST['idFactura']) && isset($_REQUEST['justificacion'])) {

            $idFactura = $_REQUEST['idFactura'];

            $idFactura = $con->real_escape_string($idFactura);

            $justificacion = $_REQUEST['justificacion'];
            $justificacion = utf8_encode($con->real_escape_string($justificacion));


            date_default_timezone_set('America/Bogota');
            $horaLocal = date('Y-m-d H:i:s');

            $idAlistador = $_SESSION["idUsuarios"];
            
            $sql = "UPDATE Facturas SET facEstado = 8, FinAlistamiento = '$horaLocal', idAlistador = $idAlistador, justificacion = '$justificacion' WHERE vtaid = $idFactura";

            bitacoraLog('No Procesar Factura', $idFactura);        

            $resultado = $con->query($sql);

            if ($resultado) {
                header("Location: ../lista_alistamiento.php?mensaje=Actualizado correctamente");
            } else {
                header("Location: ../lista_alistamiento.php?mensaje=" . "Error " . $sql . " al actualizar la facturacion: " . $con->error);
            }


        } else {
            header("Location: ../lista_alistamiento.php?mensaje=ID no proporcionado");
       }
    } catch (Exception $e) {
        header("Location: ../lista_alistamiento.php?mensaje=" . "Error " . $sql . " al actualizar la facturacion: " .$e->getMessage());

    }
?>
