<?php

    require_once 'Connection.php';

    session_start();
    /*
    $response = array(
        "message" => "No verificado",
        "status" => 1,
        "estado" => false
    );

    header("Content-Type: application/json");
    echo json_encode($response);
    die();
    */
    try {
        $con = Connection::getInstance()->getConnection();

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['idFactura'])) {

            $idFactura = $data['idFactura'];

            $idFactura = $con->real_escape_string($idFactura);
            
            $resultado = $con->query(
                "SELECT 
                    P.* 
                FROM 
                    Productos as P,
                    Facturas AS F
                WHERE 
                    F.vtaid = P.VtaId
                    AND VtaCant != AlisCant
                    AND F.vtaid = $idFactura
            ;");

            if ($resultado->num_rows > 0) {

                $response = array(
                    "message" => "No verificado",
                    "status" => 1,
                    "estado" => false
                );

    
            } else {

                $response = array(
                    "message" => "Verificado",
                    "status" => 1,
                    "estado" => true
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
