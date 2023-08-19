<?php
    require_once 'Connection.php';
    try {
        $con = Connection::getInstance()->getConnection();

        $cedula = $_POST["cedula"];
        $nombres = $_POST["nombres"];
        $apellidos = $_POST["apellidos"];
        $correo = $_POST["correo"];
        $password = $_POST["password"]; 

        // Hash de la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO Usuarios (Cedula, Nombres, Apellidos, Correo, Password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssss", $cedula, $nombres, $apellidos, $correo, $hashedPassword);
        $stmt->execute();

        header("Location: ../lista_usuarios.php?mensaje=Usuario insertado correctamente");
    } catch (Exception $e) {
        header("Location: ../lista_usuarios.php?mensaje=" . $e->getMessage());
    }
?>
