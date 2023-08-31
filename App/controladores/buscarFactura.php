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
                    A.Nombres AS NombresAlistador,
                    A.Apellidos AS ApellidosAlistador,
                    TIMESTAMPDIFF(MINUTE, InicioAlistamiento, FinAlistamiento) AS duracionAlistamiento,
                    V.Nombres AS NombresVerificador,
                    V.Apellidos AS ApellidosVerificador,
                    TIMESTAMPDIFF(MINUTE, InicioVerificacion, FinVerificacion) AS duracionVerificacion,
                    E.Nombres AS NombresEntregador,
                    E.Apellidos AS ApellidosEntregador,
                    TIMESTAMPDIFF(MINUTE, InicioEntrega, FinEntrega) AS duracionEntrega,
                    LTRIM(F.Embalaje) AS embalaje,
                    Est.Descripcion AS estado
                FROM 
                    Facturas AS F
                LEFT JOIN
                    Usuarios AS A
                    ON
                        A.idUsuarios = F.idAlistador
                LEFT JOIN
                    Usuarios AS V
                    ON
                        V.idUsuarios = F.idVerificador
                LEFT JOIN
                    Usuarios AS E
                    ON
                        E.idUsuarios = F.idEntregador
                LEFT JOIN
                    Estados AS Est
                    ON
                        Est.idEstados = F.facEstado
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
