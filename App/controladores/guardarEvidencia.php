<?php

    require_once 'Connection.php';
    include 'funciones.php';

    session_start();
    header('Content-Type: application/json');

    try {
        $con = Connection::getInstance()->getConnection();

        if (!isset($_FILES['file'])) {
            throw new Exception("No se recibió ningún archivo.");
        }

        $PrfCod = $_REQUEST['PrfCod'];
        $VtaNum = $_REQUEST['VtaNum'];

        $archivo = $_FILES['file'];
        $ext = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombre = hash('sha256', date('YmdHis') . rand()) . '.' . $ext;
        $rutaDestino = "../evidencia/" . $nombre;

        if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            throw new Exception("No se pudo mover el archivo al servidor.");
        }

        date_default_timezone_set('America/Bogota');
        $fechaSubida = date('Y-m-d H:i:s');

        $sql = "INSERT INTO EvidenciaMultimedia (PrfCod, VtaNum, nombre_archivo, fecha_subida)
                VALUES ('$PrfCod', " . ($VtaNum !== null ? $VtaNum : "NULL") . ", '$nombre', '$fechaSubida')";

        if (!$con->query($sql)) {
            throw new Exception("Error al guardar en base de datos: " . $con->error);
        }

        echo json_encode(["exito" => true, "mensaje" => "Archivo subido y registrado correctamente"]);

    } catch (Exception $e) {
        echo json_encode(["exito" => false, "mensaje" => $e->getMessage()]);
    }
