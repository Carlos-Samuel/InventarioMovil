<?php

require_once 'Connection.php';
include 'funciones.php';

session_start();
header('Content-Type: application/json');

date_default_timezone_set('America/Bogota');

try {
    $con = Connection::getInstance()->getConnection();

    if (!isset($_FILES['archivoMovil'])) {
        throw new Exception("No se recibió ningún archivo desde el móvil.");
    }

    $PrfCod = $_POST['PrfCod'] ?? null;
    $VtaNum = $_POST['VtaNum'] ?? null;

    if (!$PrfCod || !$VtaNum) {
        throw new Exception("Faltan parámetros requeridos (PrfCod o VtaNum).");
    }

    $archivo = $_FILES['archivoMovil'];
    $ext = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $nombre = hash('sha256', date('YmdHis') . rand()) . '.' . $ext;
    $rutaDestino = "../evidencia/" . $nombre;

    if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
        throw new Exception("No se pudo mover el archivo al servidor.");
    }

    $fechaSubida = date('Y-m-d H:i:s');

    $sql = "INSERT INTO EvidenciaMultimedia (PrfCod, VtaNum, nombre_archivo, fecha_subida)
            VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("siss", $PrfCod, $VtaNum, $nombre, $fechaSubida);

    if (!$stmt->execute()) {
        throw new Exception("Error al guardar en base de datos: " . $stmt->error);
    }

    echo json_encode(["exito" => true, "mensaje" => "Archivo subido correctamente desde móvil."]);

} catch (Exception $e) {
    echo json_encode(["exito" => false, "mensaje" => $e->getMessage()]);
} 
