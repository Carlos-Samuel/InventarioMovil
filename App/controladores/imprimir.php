<?php
    require_once 'Connection.php';

    require '../vendor/autoload.php';

    use PhpOffice\PhpWord\TemplateProcessor;

    $con = Connection::getInstance()->getConnection();


    $template = new TemplateProcessor('../plantillas/etiqueta.docx');

    $template->setValue('nombre', 'Juan Pérez');
    $template->setValue('correo', 'juan@example.com');

    $template->saveAs('../documentos/documento_generado.docx');

    $inputFile = '../documentos/documento_generado.docx'; // Ruta al archivo de Word
    $outputFile = '../documentos/documento_generado.pdf'; // Ruta al archivo PDF de salida


    $command = "libreoffice --convert-to pdf $inputFile  --outdir " . dirname($outputFile);

    $output = [];
    $returnCode = 0;
    
    exec($command, $output, $returnCode);

    $tempFilePath = 'documentos/documento_generado.docx';
    $printerName = 'HP_Ink_Tank_Wireless_410_series';
    $command = "lp -d $printerName $tempFilePath";
    $command = "lp -n 1 $tempFilePath";
    $comando = "lp $outputFile";
    var_dump($comando);
    //exec($comando);
    $output = [];
    $returnCode = 0;

    exec($comando, $output, $returnCode);
    
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



    // try{

    //     $consulta = "DELETE FROM Bitacora;";

    //     $finalConsulta = $con->query($consulta);

    //     $consulta1 = "DELETE FROM Productos;";

    //     $finalConsulta1 = $con->query($consulta1);

    //     $consulta2 = "DELETE FROM Facturas;";

    //     $finalConsulta2 = $con->query($consulta2);
        

    // } catch (Exception $e) {
    //     echo "Error: " . $e->getMessage();
    // }

    // $con->close();

?>