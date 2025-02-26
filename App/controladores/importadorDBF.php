<?php
    require_once 'Connection.php';
    require_once 'Connection2.php';

    set_time_limit(1400); 

    try {
        $db = dbase_open("C:\Users\csamu\OneDrive\Escritorio\LotesyFechas\VENTAS.DBF", 0);
        if ($db) {
            $num_registros = dbase_numrecords($db);
            for ($i = 1; $i <= $num_registros; $i++) {
                $fila = dbase_get_record_with_names($db, $i);
                if ($fila['deleted'] != 1) {  // omitir registros eliminados
                    echo $fila['CAMPO1'] . " - " . $fila['CAMPO2'] . "\n";
                }
            }
            dbase_close($db);
        }
        

    } catch (Exception $e) {
        var_dump("Error general: " . $e->getMessage());
    }    

?>

