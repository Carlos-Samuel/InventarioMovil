<?php

    require '../vendor/autoload.php';

    $inputFile = '../documentos/documento_generado.docx'; // Ruta al archivo de Word
    $outputFile = '../documentos/documento_generado.pdf'; // Ruta al archivo PDF de salida


    $command = "libreoffice --convert-to pdf $inputFile 2>&1 --outdir " . dirname($outputFile);

    // var_dump($command);

    $output = [];
    $returnCode = 0;
    
    exec($command, $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "La conversión se realizó con éxito. Salida del comando:\n";
        foreach ($output as $line) {
            echo $line . "\n";
        }
    } else {
        echo "La conversión falló. Código de retorno: $returnCode\n";
        echo "Salida del comando:\n";
        foreach ($output as $line) {
            echo $line . "\n";
        }
        // Puedes agregar más manejo de errores según tus necesidades.
    }
    
    
    
    
    
    


?>