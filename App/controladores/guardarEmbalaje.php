<?php

    require_once 'Connection.php';
    include_once 'funciones.php';
   

    session_start();

    try {
        
        $con = Connection::getInstance()->getConnection();

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['idFactura']) && isset($data['embalaje'])) {

            $idFactura = $data['idFactura'];
            $idFactura = $con->real_escape_string($idFactura);
            $observacion = "";

            if (isset($data['embalaje'])){
                $embalaje = $data['embalaje'];
                $embalaje = $con->real_escape_string($embalaje);
            }

            if (isset($data['observacion'])){
                $observacion = $data['observacion'];
                $observacion = $con->real_escape_string($observacion);
            }

            $sql = "UPDATE Facturas SET Embalaje = '$embalaje', ObservacionesVer = '$observacion' WHERE vtaid = $idFactura";

            $resultado = $con->query($sql);            

            if ($resultado) {
                $response = array(
                    "message" => "Embalaje guardado correctamente",
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
