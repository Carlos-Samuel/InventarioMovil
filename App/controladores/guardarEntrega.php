<?php

    require_once 'Connection.php';
    include_once 'funciones.php';
   

    session_start();

    try {

        $con = Connection::getInstance()->getConnection();

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['idFactura'])) {

            $idFactura = $data['idFactura'];

            $idFactura = $con->real_escape_string($idFactura);

            $idEntregador = $_SESSION["idUsuarios"];

            date_default_timezone_set('America/Bogota');
            $horaLocal = date('Y-m-d H:i:s');
            
            $sql = "UPDATE Facturas SET facEstado = 7, FinEntrega = '$horaLocal', idEntregador = $idEntregador WHERE vtaid = $idFactura";

            bitacoraLog('Entregado', $idFactura);

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
