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

            $sql = "UPDATE Productos SET VerCant = $cantidadAlistada WHERE VtaDetId = $idProducto";

            $resultado = $con->query($sql);
            
            $resultado = $con->query("SELECT * FROM Productos WHERE VtaDetId = $idProducto;");

            if ($resultado->num_rows > 0) {

                $row = $resultado->fetch_assoc();
        
                $AlisCant = $row['AlisCant'];
        
                $ProId = $row['ProId'];

                if ($AlisCant == $cantidadAlistada) {
                    $estado = true;
                } else {
                    $estado = false;
                }

                $response = array(
                    "message" => "Producto encontrado",
                    "status" => 1,
                    "estado" => $estado,
                    "proId" => $ProId
                );
    
            } else {
                $response = array(
                    "error" => "Error al encontrar el producto: " . $con->error,
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
