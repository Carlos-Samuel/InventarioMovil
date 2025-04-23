<?php

    require_once 'Connection.php';
    header('Content-Type: application/json');

    try {
        $con = Connection::getInstance()->getConnection();

        $PrfCod = isset($_POST['PrfCod']) ? $con->real_escape_string($_POST['PrfCod']) : null;
        $VtaNum = isset($_POST['VtaNum']) ? intval($_POST['VtaNum']) : null;

        if (!$PrfCod || !$VtaNum) {
            throw new Exception("Faltan parámetros requeridos.");
        }

        $sql = "SELECT * FROM EvidenciaMultimedia WHERE PrfCod = '$PrfCod' AND VtaNum = $VtaNum";
        $resultado = $con->query($sql);

        if (!$resultado) {
            throw new Exception("Error en la consulta: " . $con->error);
        }

        $html = '<table class="table table-bordered"><thead><tr><th>Miniatura</th><th>Acciones</th></tr></thead><tbody>';

        while ($row = $resultado->fetch_assoc()) {
            $archivo = "evidencia/" . $row['nombre_archivo'];
            $esVideo = preg_match('/\.(mp4|webm|ogg)$/i', $archivo);
            $tipo = $esVideo ? 'video' : 'imagen';
            $preview = $esVideo ?
                "<video width='100'><source src='$archivo' type='video/mp4'></video>" :
                "<img src='$archivo' width='100'>";

            $html .= "<tr><td>$preview</td><td>
                        <button class='btn btn-sm btn-primary verArchivo' data-tipo='$tipo' data-src='$archivo'>Ver</button>
                        <button class='btn btn-sm btn-danger eliminarArchivo' data-id='{$row['id']}'>Borrar</button>
                    </td></tr>";
        }

        $html .= '</tbody></table>';

        echo json_encode(["exito" => true, "html" => $html]);

    } catch (Exception $e) {
        echo json_encode(["exito" => false, "mensaje" => $e->getMessage()]);
    }
