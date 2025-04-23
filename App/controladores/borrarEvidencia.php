<?php
    require_once 'Connection.php';
    header('Content-Type: application/json');

    try {
        $con = Connection::getInstance()->getConnection();

        $fechaInicio = $_POST['fechaInicio'] ?? null;
        $fechaFin = $_POST['fechaFin'] ?? null;

        if (!$fechaInicio || !$fechaFin) {
            throw new Exception("Ambas fechas son obligatorias.");
        }

        if ($fechaFin < $fechaInicio) {
            throw new Exception("La fecha fin no puede ser anterior a la fecha inicio.");
        }

        $sql = "SELECT id, nombre_archivo FROM EvidenciaMultimedia WHERE fecha_subida BETWEEN ? AND ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ss", $fechaInicio, $fechaFin);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $archivosBorrados = 0;

        while ($row = $resultado->fetch_assoc()) {
            $id = $row['id'];
            $archivo = __DIR__ . "/../evidencia/" . $row['nombre_archivo'];

            if (file_exists($archivo)) {
                unlink($archivo);
            }

            $con->query("DELETE FROM EvidenciaMultimedia WHERE id = $id");
            $archivosBorrados++;
        }

        echo json_encode([
            "exito" => true,
            "mensaje" => "Se borraron $archivosBorrados archivo(s) correctamente."
        ]);

    } catch (Exception $e) {
        echo json_encode([
            "exito" => false,
            "mensaje" => $e->getMessage()
        ]);
    }
?>
