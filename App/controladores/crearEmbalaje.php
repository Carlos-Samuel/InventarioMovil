<?php

    require_once 'Connection.php';

    try {
        $con = Connection::getInstance()->getConnection();

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['embalaje'])) {
            $embalaje = $data['embalaje'];

            $embalaje = $con->real_escape_string($embalaje);

            $query = "INSERT INTO Embalajes(Descripcion) VALUES ($embalaje)";

            if ($con->query($query)) {
                $response = array(
                    "message" => "Registro insertado correctamente"
                );
            } else {
                $response = array(
                    "error" => "Error al inserter el registro: " . $con->error
                );
            }
        } else {
            $response = array(
                "error" => "ID no proporcionado"
            );
        }
    } catch (Exception $e) {
        $response = array(
            "error" => "Error al insertar: "
        );
    }

    header("Content-Type: application/json");
    echo json_encode($response);
?>
