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
            
            $query = "
            SELECT 
                idUsuarios, 
                CONCAT(Nombres, ' ', Apellidos) AS NombreCompleto
            FROM 
                Usuarios";
            
            $resultado = $con->query($query);
            
            if ($resultado->num_rows > 0) {
                $usuarios = [];
                while ($row = $resultado->fetch_assoc()) {
                    $usuarios[] = [
                        "id" => $row["idUsuarios"],
                        "nombre" => $row["NombreCompleto"]
                    ];
                }
                echo json_encode(["status" => 1, "usuarios" => $usuarios]);
            } else {
                echo json_encode(["status" => 0, "error" => "No se encontraron usuarios"]);
            }
            
        } else {
            $response = [
                "error" => "Datos no proporcionados",
                "status" => 2
            ];
        }
    } catch (Exception $e) {
        $response = [
            "error" => "Error en la conexión: " . $e->getMessage(),
            "status" => 4
        ];
    }

    header("Content-Type: application/json");
    echo json_encode($response);

    function utf8_encode_array(&$array) {
        foreach ($array as &$value) {
            if (is_string($value) && $value !== "NULL" && !empty($value)) {
                $value = utf8_encode($value);
            }
        }
    }
    
?>
