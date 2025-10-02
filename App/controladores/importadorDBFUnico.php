<?php
require_once 'Connection.php';
require_once 'Connection2.php';
require '../vendor/autoload.php';

set_time_limit(1400); 

use XBase\TableReader;

try {
    // Inicia medición total
    $inicioTotal = microtime(true);

    /*
    // Medir tiempo en abrir tabla
    $inicioApertura = microtime(true);

    //$tabla = new TableReader("C:\\Users\\csamu\\OneDrive\\Escritorio\\LotesyFechas\\PROFECVNC.DBF", [
    
    $tabla = new TableReader("D:\\AgilFE\\LabUnidos\\EMP001\\Datos\\PROFECVNC.DBF", [    
        'encoding' => 'CP1252'
    ]);
    $finApertura = microtime(true);

    echo "Tiempo apertura tabla: " . round($finApertura - $inicioApertura, 4) . " segundos.<br>";
    */
    // Obtener nombres de columnas
    /*
    $columnas = [];
    foreach ($tabla->getColumns() as $columna) {
        $columnas[] = $columna->getName();
    }

    echo "Columnas encontradas: " . implode(", ", $columnas) . "<br>";

    */

    $columnas = [
        "procod", "bodcod", "tmicod", "docnum", "vncfec", "vnclot", "vnccan",
        "vncsal", "prfcod", "vncsumres", "vnccns", "vncfecdoc", "empcod"
    ];    

    $columnas = [
        "procod", "docnum", "prfcod", "vncfec", "vnclot", "vnccan"
    ];    



    $indexado = [];

    //$columnas = [
     //   "procod", "docnum"
    //];    
    /*
    // Lectura registros
    echo "Inicia lectura de registros...<br>";
    $inicioLectura = microtime(true);

    //$registros = [];
    $cont = 0;
    while ($registro = $tabla->nextRecord()) {
        if ($cont > 100650){
        //if ($registro->get("vnccan") == '-1'){
            $fila = [];
            foreach ($columnas as $columna) {
                $fila[$columna] = $registro->get($columna);
            }

            $key = $fila['docnum'] . '|' . $fila['prfcod'] . '|' . $fila['procod'];
            if (!isset($indexado[$key])) {
                $indexado[$key] = [];
            }
            $indexado[$key][] = $fila;
            //$registros[] = $fila;
        }
        $cont++;
    }

    $finLectura = microtime(true);
    echo "Tiempo lectura registros: " . round($finLectura - $inicioLectura, 4) . " segundos.<br>";

    $tabla->close();

    // Fin medición total
    $finTotal = microtime(true);
    echo "Tiempo total ejecución: " . round($finTotal - $inicioTotal, 4) . " segundos.<br>";
    echo "Total de registros: " . $cont . ".<br>";

    echo "<pre>";
    print_r($indexado);
    echo "</pre>";
    */
    echo "Termina";

} catch (Exception $e) {
    var_dump("Error general: " . $e->getMessage());
}
