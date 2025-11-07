<?php

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

function leerProfecvncIndexado(): array
{
    $habilitado = filter_var($_ENV['PROFECVNC_ENABLED'] ?? 'true', FILTER_VALIDATE_BOOLEAN);
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
    $tabla = null;

    try {
        $tabla = new \TableReader($ruta, ['encoding' => 'CP1252']);

        $columnas = [
            "procod","bodcod","tmicod","docnum","vncfec","vnclot","vnccan",
            "vncsal","prfcod","vncsumres","vnccns","vncfecdoc","empcod"
        ];

        while ($registro = $tabla->nextRecord()) {
            $fila = [];
            foreach ($columnas as $columna) {
                $fila[$columna] = $registro->get($columna);
            }

            $key = $fila['docnum'] . '|' . $fila['prfcod'] . '|' . $fila['procod'];
            $indexado[$key] ??= [];
            $indexado[$key][] = $fila;
        }
    } finally {
        if ($tabla) {
            try { $tabla->close(); } catch (\Throwable $t) {}
        }
    }

    return $indexado;
}

// Uso:
$indexado = leerProfecvncIndexado();
