<?php

    require_once 'Connection.php';

    session_start();

    try {
        $con = Connection::getInstance()->getConnection();

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['idFactura'])) {

            $idFactura = $data['idFactura'];

            $idFactura = $con->real_escape_string($idFactura);

            $estado = false;
            
            $resultado = $con->query(
                "SELECT 
                    P.* 
                FROM 
                    Productos as P,
                    Facturas AS F
                WHERE 
                    F.vtaid = P.VtaId
                    AND AlisCant != VerCant
                    AND F.vtaid = $idFactura
            ;");

            if ($resultado->num_rows > 0) {

                $response = array(
                    "message" => "No verificado",
                    "status" => 1,
                    "estado" => $estado
                );

    
            } else {

                $estado = true;

                $response = array(
                    "message" => "Verificado",
                    "status" => 1,
                    "estado" => $estado
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
