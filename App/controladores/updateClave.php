<?php
    require_once 'Connection.php';

    try {
        $con = Connection::getInstance()->getConnection();

        $idUsuario = $_POST["idUsuario"];
        $password = $_POST["clave"];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE Usuarios SET Password = '$hashedPassword' WHERE idUsuarios = $idUsuario";

        if ($con->query($sql)) {
            header("Location: ../lista_usuarios.php?mensaje=Contraseña actualizada correctamente");
        } else {
            header("Location: ../lista_usuarios.php?mensaje=" . $con->error);
        }

    } catch (Exception $e) {
        header("Location: ../lista_usuarios.php?mensaje=" . $e->getMessage());
    }
?>
