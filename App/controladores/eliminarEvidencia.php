<?php

require_once 'Connection.php';
header('Content-Type: application/json');

try {
    $con = Connection::getInstance()->getConnection();

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id <= 0) {
        throw new Exception("ID inválido para eliminar.");
    }

    // Buscar el nombre del archivo antes de borrar
    $sqlSelect = "SELECT nombre_archivo FROM EvidenciaMultimedia WHERE id = $id";
    $resultadoSelect = $con->query($sqlSelect);

    if (!$resultadoSelect || $resultadoSelect->num_rows === 0) {
        throw new Exception("Archivo no encontrado en la base de datos.");
    }

    $fila = $resultadoSelect->fetch_assoc();
    $nombreArchivo = $fila['nombre_archivo'];
    $rutaArchivo = __DIR__ . "/../evidencia/" . $nombreArchivo;

    // Eliminar archivo si existe
    if (file_exists($rutaArchivo)) {
        if (!unlink($rutaArchivo)) {
            throw new Exception("No se pudo eliminar el archivo del servidor.");
        }
    }

    // Eliminar el registro de la base de datos
    $sqlDelete = "DELETE FROM EvidenciaMultimedia WHERE id = $id";
    $resultadoDelete = $con->query($sqlDelete);

    if (!$resultadoDelete) {
        throw new Exception("Error al eliminar registro: " . $con->error);
    }

    echo json_encode(["exito" => true, "mensaje" => "Archivo y registro eliminados correctamente."]);

} catch (Exception $e) {
    echo json_encode(["exito" => false, "mensaje" => $e->getMessage()]);
}
