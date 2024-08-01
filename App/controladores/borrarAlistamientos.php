<?php

    require_once 'Connection.php';

    try {
        $con = Connection::getInstance()->getConnection();

        $data = json_decode(file_get_contents("php://input"), true);
        
        if (isset($data['fecIni']) && isset($data['fecFin'])) {
            $fecIni = $data['fecIni'];

            $fecFin = $data['fecFin'];

            $prefijoBorrar = "";

            if (isset($data['fecFin'])){
                $prefijoBorrar = $data['prefijoBorrar'];
            }

            $query = "DELETE FROM Facturas WHERE facEstado = 1 AND FinAlistamiento IS NULL AND vtafec >= '$fecIni' AND vtafec <= '$fecFin'";

            if ($prefijoBorrar != ""){
                $query = $query . " AND PrfCod = '" . $prefijoBorrar . "' ";
            }

            if ($con->query($query)) {
                $response = array(
                    "message" => "Registros borrados correctamente"
                );
            } else {
                $response = array(
                    "error" => "Error al borrar los registro: " . $con->error
                );
            }
                
        } else {
            $response = array(
                "error" => "Fechas no proporcionadas"
            );
        }
    } catch (Exception $e) {
        $response = array(
            "error" => "Error al borrar: " . $e->getMessage()
        );
    }    

    header("Content-Type: application/json");
    echo json_encode($response);
?>
