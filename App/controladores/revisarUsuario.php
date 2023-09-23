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

            $resultado = $con->query(
                "SELECT 
                    * 
                FROM 
                    Usuarios AS U,
                    Usuarios_tienen_permisos AS UTP,
                    Permisos AS P
                WHERE 
                    U.idUsuarios = UTP.idUsuarios
                    AND UTP.idPermisos = P.idPermisos
                    AND (P.idPermisos = 6 OR P.idPermisos = 5)
                    AND Cedula = $cedula ;"
                );

            if ($resultado->num_rows > 0) {

                $row = $resultado->fetch_assoc();

                $hashedPassword = $row["Password"];
                $idUsuario = $row["idUsuarios"];
                
                if (password_verify($password, $hashedPassword)) {
                    $response = array(
                        "message" => "Usuario analizado correctamente",
                        "status" => 1,
                        "estado" => true,
                        "idUsuario" => $idUsuario
                    );  
                }else{
                    $response = array(
                        "message" => "Credenciales incorrectas",
                        "status" => 1,
                        "estado" => false
                    );     
                }
    
            } else {
                $response = array(
                    "message" => "Credenciales incorrectas",
                    "status" => 1,
                    "estado" => false
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
