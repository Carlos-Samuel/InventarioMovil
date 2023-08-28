<?php

    require_once 'Connection.php';

    session_start();

    try {
        $con = Connection::getInstance()->getConnection();

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['prefijo']) && isset($data['documento'])) {

            $prefijo = $data['prefijo'];
            $documento = $data['documento'];

            $prefijo = $con->real_escape_string($prefijo);
            $documento = $con->real_escape_string($documento);
            
            $resultado = $con->query(
                "SELECT 
                    F.*,
                    U.Nombres,
                    U.Apellidos,
                    TIMESTAMPDIFF(MINUTE, InicioAlistamiento, FinAlistamiento) AS duracionAlistamiento
                FROM 
                    Facturas AS F,
                    Usuarios AS U
                WHERE 
                    PrfID = $prefijo 
                    AND VtaNum = $documento;");

            if ($resultado->num_rows > 0) {
                $row = $resultado->fetch_assoc();

                $response = array(
                    "message" => "Factura actualizada correctamente",
                    "status" => 1,
                    "datos" => $row
                );

            } else {
                $response = array(
                    "error" => "Factura no encontrada",
                    "status" => 3
                );
            }
        } else {
            $response = array(
                "error" => "Datos no proporcionados",
                "status" => 2
            );
        }
    } catch (Exception $e) {
        $response = array(
            "error" => "Error en la conexión: " . $e->getMessage(),
            "status" => 2
        );
    }

    header("Content-Type: application/json");
    echo json_encode($response);
?>
