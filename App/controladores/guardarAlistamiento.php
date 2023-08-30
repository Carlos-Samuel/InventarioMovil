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

            if (isset($data['justificacion'])){
                $justificacion = $data['justificacion'];
                $justificacion = $con->real_escape_string($justificacion);
            }

            if (isset($data['usuario'])){
                $usuario = $data['usuario'];
                $usuario = $con->real_escape_string($usuario);
            }

            switch ($idEstado) {
                case 0:

                    date_default_timezone_set('America/Bogota');
                    $horaLocal = date('Y-m-d H:i:s');

                    $idAlistador = $_SESSION["idUsuarios"];
                    
                    $sql = "UPDATE Facturas SET facEstado = 0, FinAlistamiento = '$horaLocal', idAlistador = $idAlistador WHERE vtaid = $idFactura";

                    break;
                case 1:

                    date_default_timezone_set('America/Bogota');
                    $horaLocal = date('Y-m-d H:i:s');

                    $idAlistador = $_SESSION["idUsuarios"];
                    
                    $sql = "UPDATE Facturas SET facEstado = 3, FinAlistamiento = '$horaLocal', idAlistador = $idAlistador WHERE vtaid = $idFactura";

                    break;
                case 2:

                    $idAlistador = $_SESSION["idUsuarios"];
                    
                    $sql = "UPDATE Facturas SET facEstado = 2, idAlistador = $idAlistador, Justificacion = '$justificacion' WHERE vtaid = $idFactura";

                    break;
                    
                case 3:

                    date_default_timezone_set('America/Bogota');
                    $horaLocal = date('Y-m-d H:i:s');

                    $idAlistador = $_SESSION["idUsuarios"];
                    
                    $sql = "UPDATE Facturas SET facEstado = 3, FinAlistamiento = '$horaLocal', idAlistador = $idAlistador, Forzado = 1, Forzador = $usuario WHERE vtaid = $idFactura";

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

            header("Content-Type: application/json");
            echo json_encode($response);
        } else {
            $response = array(
                "error" => "ID no proporcionado",
                "status" => 2
            );

            header("Content-Type: application/json");
            echo json_encode($response);
        }
    } catch (Exception $e) {
        $response = array(
            "error" => "Error " . $sql . " en la conexión: " . $e->getMessage(),
            "status" => 2
        );

        header("Content-Type: application/json");
        echo json_encode($response);
    }
?>
