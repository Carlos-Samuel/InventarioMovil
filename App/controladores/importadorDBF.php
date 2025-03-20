<?php
require_once 'Connection.php';
require_once 'Connection2.php';
require '../vendor/autoload.php';

set_time_limit(1400); 

use XBase\TableReader;

try {
    // Inicia medición total
    $inicioTotal = microtime(true);

    // Medir tiempo en abrir tabla
    $inicioApertura = microtime(true);
    $tabla = new TableReader("C:\\Users\\csamu\\OneDrive\\Escritorio\\LotesyFechas\\PROFECVNC.DBF", [
        'encoding' => 'CP1252'
    ]);
    $finApertura = microtime(true);

    echo "Tiempo apertura tabla: " . round($finApertura - $inicioApertura, 4) . " segundos.<br>";

    // Lectura registros
    echo "Inicia lectura de registros...<br>";
    $inicioLectura = microtime(true);

    $registros = [];
    $cont = 0;
    while ($registro = $tabla->nextRecord()) {
        $registros[] = $registro;
        $cont++;
    }

    $finLectura = microtime(true);
    echo "Tiempo lectura registros: " . round($finLectura - $inicioLectura, 4) . " segundos.<br>";

    $tabla->close();

    // Fin medición total
    $finTotal = microtime(true);
    echo "Tiempo total ejecución: " . round($finTotal - $inicioTotal, 4) . " segundos.<br>";
    echo "Total de registros: " . $cont . ".<br>";

    echo "Termina";

} catch (Exception $e) {
    var_dump("Error general: " . $e->getMessage());
}
