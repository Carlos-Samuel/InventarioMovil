<?php
// Incluir el archivo que contiene la definición de la clase Connection
require_once 'Connection.php';

try {
    $con = Connection::getInstance()->getConnection();

    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['idUsuario']) && isset($data['idPermiso'])) {
        $idUsuario = $data['idUsuario'];
        $idPermiso = $data['idPermiso'];

        $idUsuario = $con->real_escape_string($idUsuario);
        $idPermiso = $con->real_escape_string($idPermiso);

        $query = "DELETE FROM Usuarios_tienen_permisos WHERE idUsuarios = '$idUsuario' and idPermisos = '$idPermiso'";

        if ($con->query($query)) {
            $response = array(
                "message" => "Registro eliminado correctamente"
            );
        } else {
            $response = array(
                "error" => "Error al eliminar el registro: " . $con->error
            );
        }

        // Configurar las cabeceras para que la respuesta sea en formato JSON
        header("Content-Type: application/json");

        // Imprimir la respuesta en formato JSON
        echo json_encode($response);
    } else {
        $response = array(
            "error" => "ID no proporcionado"
        );

        // Configurar las cabeceras para que la respuesta sea en formato JSON
        header("Content-Type: application/json");

        // Imprimir la respuesta en formato JSON
        echo json_encode($response);
    }
} catch (Exception $e) {
    $response = array(
        "error" => "Error en la conexión: " . $e->getMessage()
    );

    // Configurar las cabeceras para que la respuesta sea en formato JSON
    header("Content-Type: application/json");

    // Imprimir la respuesta en formato JSON
    echo json_encode($response);
}
?>
