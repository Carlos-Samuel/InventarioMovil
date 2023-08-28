<?php

    require_once 'Connection.php';

    session_start();

    try {
        $con = Connection::getInstance()->getConnection();

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['idProducto']) && isset($data['cantidadAlistada'])) {

            $idProducto = $data['idProducto'];
            $cantidadAlistada = $data['cantidadAlistada'];

            $idProducto = $con->real_escape_string($idProducto);
            $cantidadAlistada = $con->real_escape_string($cantidadAlistada);

            $sql = "UPDATE Productos SET AlisCant = $cantidadAlistada WHERE VtaDetId = $idProducto";

            $resultado = $con->query($sql);
            
            $resultado2 = $con->query("SELECT ABS(VtaCant - AlisCant) AS diferencia FROM Productos WHERE VtaDetId = $idProducto ;");

            if ($resultado2->num_rows > 0) {
                $row = $resultado2->fetch_assoc();
        
                $diferencia = $row['diferencia'];



                if ($resultado) {
                    $response = array(
                        "message" => "Factura actualizada correctamente",
                        "status" => 1,
                        "diferencia" => $diferencia
                    );
                } else {
                    $response = array(
                        "error" => "Error " . $sql . " al actualizar el producto: " . $con->error,
                        "status" => 2
                    );
                }
    
            } else {
                $response = array(
                    "error" => "Error " . $sql . " al obtener el producto: " . $con->error,
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
