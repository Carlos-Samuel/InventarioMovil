<?php

    require_once 'Connection.php';

    session_start();

    try {
        $con = Connection::getInstance()->getConnection();

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['cedula']) && isset($data['password'])) {

            $cedula = $data['cedula'];
            $password = $data['password'];

            $cedula = $con->real_escape_string($cedula);
            $password = $con->real_escape_string($password);

            $resultado = $con->query("SELECT * FROM Usuarios WHERE Cedula = $cedula ;");

            if ($resultado->num_rows > 0) {

                $row = $resultado->fetch_assoc();

                $hashedPassword = $row["Password"];
                
                if (password_verify($password, $hashedPassword)) {
                    $response = array(
                        "message" => "Usuario analizado correctamente",
                        "status" => 1,
                        "estado" => true
                    );  
                }else{
                    $response = array(
                        "message" => "Usuario analizado correctamente",
                        "status" => 1,
                        "estado" => false
                    );     
                }
    
            } else {
                $response = array(
                    "error" => "Error " . $sql . " al obtener usuario: " . $con->error,
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
