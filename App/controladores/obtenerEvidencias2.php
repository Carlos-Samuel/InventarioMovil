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

    $html = '<table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Evidencias</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>';

    while ($row = $resultado->fetch_assoc()) {
        $archivo = "evidencia/" . $row['nombre_archivo'];
        $esVideo = preg_match('/\.(mp4|webm|ogg)$/i', $archivo);
        $tipo = $esVideo ? 'video' : 'imagen';

        $preview = $esVideo
            ? "<video width='100%' style='max-width: 300px; cursor: pointer;' class='verArchivo' data-tipo='video' data-src='$archivo' muted>
                    <source src='$archivo' type='video/mp4'>
               </video>"
            : "<img src='$archivo' style='max-width: 300px; width: 100%; height: auto; cursor: pointer;' 
                     class='img-fluid verArchivo' data-tipo='imagen' data-src='$archivo'>";

        $botonDescarga = "<a href='$archivo' download class='btn btn-sm btn-success'>Descargar</a>";

        $html .= "<tr>
                    <td class='text-center'>$preview</td>
                    <td class='text-center align-middle'>$botonDescarga</td>
                  </tr>";
    }

    $html .= '</tbody></table>';

    echo json_encode(["exito" => true, "html" => $html]);

} catch (Exception $e) {
    echo json_encode(["exito" => false, "mensaje" => $e->getMessage()]);
}
