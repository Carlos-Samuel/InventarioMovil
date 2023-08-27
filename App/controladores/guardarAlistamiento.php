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

            switch ($idEstado) {
                case 2:

                    $idAlistador = $_SESSION["idUsuarios"];
                    
                    $sql = "UPDATE Facturas SET facEstado = $idEstado, idAlistador = $idAlistador WHERE vtaid = $idFactura";

                    break;
                case 3:

                    date_default_timezone_set('America/Bogota');
                    $horaLocal = date('Y-m-d H:i:s');

                    $idAlistador = $_SESSION["idUsuarios"];
                    
                    $sql = "UPDATE Facturas SET facEstado = $idEstado, FinAlistamiento = '$horaLocal', idAlistador = $idAlistador WHERE vtaid = $idFactura";

                    break;
                case 4:
                    echo "Opción 3 seleccionada";
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
