<?php

    require_once 'Connection.php';

    session_start(); 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $cedula = $_POST["cedula"];
        $password = $_POST["password"];


        try {
            $con = Connection::getInstance()->getConnection();

            // Preparar y ejecutar la consulta
            $sql = "SELECT idUsuarios, Cedula, Nombres, Apellidos, Correo, Password FROM Usuarios WHERE Cedula = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $cedula);
            $stmt->execute();
            $result = $stmt->get_result();


            
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $hashedPassword = $row["Password"];
                
                // Verificar la contraseña
                if (password_verify($password, $hashedPassword)) {
                //if (false) {
                    $_SESSION["idUsuarios"] = $row["idUsuarios"];
                    $_SESSION["cedula"] = $row["Cedula"];
                    $_SESSION["nombres"] = $row["Nombres"];
                    $_SESSION["apellidos"] = $row["Apellidos"];
                    $_SESSION["correo"] = $row["Correo"];

                    $sql = "SELECT P.Nombre FROM Usuarios as U, Usuarios_tienen_permisos as UTP, Permisos AS P 
                    WHERE U.idUsuarios = UTP.idUsuarios AND P.idPermisos = UTP.idPermisos AND U.idUsuarios = ?;";
                    $stmt2 = $con->prepare($sql);
                    $stmt2->bind_param("i", $row["idUsuarios"]);
                    $stmt2->execute();
                    $result = $stmt2->get_result();

                    if ($result) {
                        $permisos = "Empezar#";
                    
                        while ($row = $result->fetch_assoc()) {
                            $permisos .= $row['Nombre'] . "#";
                        }
                    
                        if (!empty($permisos)) {
                            $permisos = rtrim($permisos, "#");
                        }
                    
                        $_SESSION["permisos"] = $permisos;

                    } else {
                        header("Location: ../index.php?mensaje=" . $stmt2->$error);
                    }

                    $stmt->close();
                    $stmt2->close();
                    $con->close();
                    
                    header("Location: ../dashboard.php");
                    exit();
                } else {

                    $error = "Cédula o contraseña incorrecta";
                    header("Location: ../index.php?mensaje=" . $error);

                }
            } else {
                $error = "Cédula o contraseña incorrecta";
                header("Location: ../index.php?mensaje=" . $error);

            }

            $stmt->close();
            $con->close();
        } catch (Exception $e) {
            header("Location: ../index.php?mensaje=" . $e->getMessage());
            $stmt->close();
            $con->close();
        }
    }
?>