<?php

    require_once 'Connection.php';

    try {
        $con = Connection::getInstance()->getConnection();

        $data = json_decode(file_get_contents("php://input"), true);
        
        if (isset($data['fecIni']) && isset($data['fecFin'])) {
            $fecIni = $data['fecIni'];

            $fecFin = $data['fecFin'];

            $query = "DELETE FROM Facturas WHERE facEstado = 1 AND InicioAlistamiento IS NULL AND vtafec >= '$fecIni' AND vtafec <= '$fecFin'";

            if ($con->query($query)) {
                $response = array(
                    "message" => "Registros borrados correctamente"
                );
            } else {
                $response = array(
                    "error" => "Error al borrar el registro: " . $con->error
                );
            }
                
        } else {
            $response = array(
                "error" => "Fechas no proporcionadas"
            );
        }
    } catch (Exception $e) {
        $response = array(
            "error" => "Error al borrar: "
        );
    }

    header("Content-Type: application/json");
    echo json_encode($response);
?>
