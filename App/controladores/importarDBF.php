<?php

    require_once 'Connection.php';
    require_once 'Connection2.php';
    require_once realpath(__DIR__ . '/../vendor/autoload.php');

    use XBase\TableReader;

    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();

function leerProfecvncIndexado($importacionId): array
{
    $habilitado = filter_var($_ENV['PROFECVNC_ENABLED'] ?? 'false', FILTER_VALIDATE_BOOLEAN);
    if (!$habilitado) {
        return [];
    }

    $ruta = $_ENV['PROFECVNC_PATH'] ?? null;

    if (!$ruta) {
        throw new RuntimeException('Falta la variable de entorno PROFECVNC_PATH.');
    }
    if (!is_file($ruta)) {
        throw new RuntimeException("No existe el archivo DBF en la ruta: {$ruta}");
    }

    $indexado = [];
    $todosRegistros = [];
    $tabla = null;

    try {
        $tabla = new TableReader($ruta, ['encoding' => 'CP1252']);

        $columnas = [
            "procod","bodcod","tmicod","docnum","vncfec","vnclot","vnccan",
            "vncsal","prfcod","vncsumres","vnccns","vncfecdoc","empcod"
        ];

        while ($registro = $tabla->nextRecord()) {
            $fila = [];
            foreach ($columnas as $columna) {
                $fila[$columna] = $registro->get($columna);
            }

            // Guardar para snapshot global
            $todosRegistros[] = $fila;

            $key = $fila['docnum'] . '|' . $fila['prfcod'] . '|' . $fila['procod'];
            $indexado[$key] ??= [];
            $indexado[$key][] = $fila;
        }
    } finally {
        if ($tabla) {
            try { $tabla->close(); } catch (\Throwable $t) {}
        }
    }

    if ($importacionId !== null && !empty($todosRegistros)) {
        $ultimos100 = array_slice($todosRegistros, -100); // últimos 100
        guardarSnapshotDbf($importacionId, $ultimos100);
    }

    return $indexado;
}

function guardarSnapshotDbf(int $importacionId, array $registros): void
{


    $habilitadoLog = filter_var($_ENV['PROFECVNC_LOG_ENABLED'] ?? 'false', FILTER_VALIDATE_BOOLEAN);

    if ($habilitadoLog){

        $con = Connection::getInstance()->getConnection();

        $sql = "INSERT INTO importaciones_dbf_detalle
                (importacion_id, secuencia, procod, bodcod, tmicod, docnum,
                vncfec, vnclot, vnccan, vncsal, prfcod, vncsumres, vnccns, vncfecdoc, empcod)
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if (!$stmt = $con->prepare($sql)) {
            throw new RuntimeException("Error al preparar insert de snapshot DBF: " . $con->error);
        }

        foreach ($registros as $i => $fila) {
            $secuencia = $i + 1; // 1..100

            $procod    = $fila['procod']    ?? null;
            $bodcod    = $fila['bodcod']    ?? null;
            $tmicod    = $fila['tmicod']    ?? null;
            $docnum    = $fila['docnum']    ?? null;
            $vncfec    = $fila['vncfec']    ?? null;
            $vnclot    = $fila['vnclot']    ?? null;
            $vnccan    = $fila['vnccan']    ?? null;
            $vncsal    = $fila['vncsal']    ?? null;
            $prfcod    = $fila['prfcod']    ?? null;
            $vncsumres = $fila['vncsumres'] ?? null;
            $vnccns    = $fila['vnccns']    ?? null;
            $vncfecdoc = $fila['vncfecdoc'] ?? null;
            $empcod    = $fila['empcod']    ?? null;

            $stmt->bind_param(
                "iisssssssssssss",
                $importacionId,
                $secuencia,
                $procod,
                $bodcod,
                $tmicod,
                $docnum,
                $vncfec,
                $vnclot,
                $vnccan,
                $vncsal,
                $prfcod,
                $vncsumres,
                $vnccns,
                $vncfecdoc,
                $empcod
            );

            $stmt->execute();
        }

        $stmt->close();
    }
}
