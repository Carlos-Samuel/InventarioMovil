<?php

    require_once 'Connection.php';

    try {
        $con = Connection::getInstance()->getConnection();

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['idEmbalaje'])) {
            $embalaje = $data['idEmbalaje'];

            $embalaje = $con->real_escape_string($embalaje);

            $query = "DELETE FROM Embalajes WHERE idEmbalajes = $embalaje";

            if ($con->query($query)) {
                $response = array(
                    "message" => "Registro borrado correctamente"
                );
            } else {
                $response = array(
                    "error" => "Error al borrar el registro: " . $con->error
                );
            }
        } else {
            $response = array(
                "error" => "ID no proporcionado"
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
