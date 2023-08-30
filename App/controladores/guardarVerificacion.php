<?php

    require_once 'Connection.php';

    session_start();

    try {
        
        $con = Connection::getInstance()->getConnection();

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['idFactura']) && isset($data['idEstado'])) {

            $idFactura = $data['idFactura'];
            $idEstado = $data['idEstado'];

            $idFactura = $con->real_escape_string($idFactura);
            $idEstado = $con->real_escape_string($idEstado);

            if (isset($data['observacion'])){
                $observacion = $data['observacion'];
                $observacion = $con->real_escape_string($observacion);
            }

            if (isset($data['embalaje'])){
                $embalaje = $data['embalaje'];
                $embalaje = $con->real_escape_string($embalaje);
            }

            $idVerificador = $_SESSION["idUsuarios"];

            switch ($idEstado) {
                #Pendiente
                case 1:
                    
                    $sql = "UPDATE Facturas SET facEstado = 4 WHERE vtaid = $idFactura";

                    break;
                #Cerrar
                case 2:

                    date_default_timezone_set('America/Bogota');
                    $horaLocal = date('Y-m-d H:i:s');
                    
                    $sql = "UPDATE Facturas SET facEstado = 5, FinVerificacion = '$horaLocal', idVerificador = $idVerificador, Embalaje = '$embalaje', ObservacionesVer = '$observacion' WHERE vtaid = $idFactura";

                    break;
            }

            $resultado = $con->query($sql);

            if ($resultado) {
                $response = array(
                    "message" => "Factura actualizada correctamente",
                    "status" => 1
                );
            } else {
                $response = array(
                    "error" => "Error " . $sql . " al actualizar la facturacion: " . $con->error,
                    "status" => 2
                );
            }

        } else {
            $response = array(
                "error" => "ID no proporcionado",
                "status" => 2
            );

        }
    } catch (Exception $e) {
        $response = array(
            "error" => "Error " . $sql . " en la conexión: " . $e->getMessage(),
            "status" => 2
        );

    }

    header("Content-Type: application/json");
    echo json_encode($response);
?>
