<?php
    
    echo "Antes 1";
    echo "<br>";

    $idFactura = 217108;

    //$comando_ejecutar = "php imprimir.php '$idFactura' > NUL 2>&1";
    $comando_ejecutar = "start /B php imprimir.php '$idFactura' > NUL 2>&1";


    echo $comando_ejecutar;
   
    //shell_exec($comando_ejecutar);
    popen($comando_ejecutar, "r");

    echo "<br>";
    echo "Despues";

?>