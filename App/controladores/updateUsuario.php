<?php
    require_once 'Connection.php';

    try {
        $con = Connection::getInstance()->getConnection();

        $idUsuario = $_POST["idUsuario"];
        $cedula = $_POST["cedula"];
        $nombres = $_POST["nombres"];
        $apellidos = $_POST["apellidos"];
        $correo = $_POST["correo"];

        $sql = "UPDATE Usuarios SET Cedula = ?, Nombres = ?, Apellidos = ?, Correo = ? WHERE idUsuarios = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("isssi", $cedula, $nombres, $apellidos, $correo, $idUsuario);
        $stmt->execute();

        header("Location: ../lista_usuarios.php?mensaje=Usuario actualizado correctamente");
    } catch (Exception $e) {
        header("Location: ../lista_usuarios.php?mensaje=" . $e->getMessage());
    }
?>
