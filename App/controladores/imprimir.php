<?php
    require_once 'Connection.php';

    require '../vendor/autoload.php';

    include 'funciones.php';

    use PhpOffice\PhpWord\TemplateProcessor;

    function imprimir($id){

        try {
            $con = Connection::getInstance()->getConnection();
        } catch (Exception $e) {
            echo 'Error en la conexión: ' . $e->getMessage();
        }
        
        try {
            $quer = $con->query("SELECT * FROM Facturas WHERE vtaid = " . $id . ";");
        } catch (Exception $e) {
            echo 'Error en la consulta SQL: ' . $e->getMessage();
        }
        
        try {
            $row = $quer->fetch_assoc();
            utf8_encode_array($row);
        } catch (Exception $e) {
            echo 'Error al recuperar datos o al ejecutar utf8_encode_array: ' . $e->getMessage();
        }
        
        try {
            $elementos = explode("#", $row['Embalaje']);
        } catch (Exception $e) {
            echo 'Error al dividir la cadena: ' . $e->getMessage();
        }

        $i = 1;
    
        foreach ($elementos as $elemento) {

            if (trim($elemento)){
                $partes = explode(":", $elemento);
        
                $clave = trim($partes[0]);
                $valor = trim($partes[1]);
        
                if (is_numeric($valor)) {
                    $valor = intval($valor);        
                    
                    $j = 1;

                    try {
                        while ($j <= $valor) {
                            $template = new TemplateProcessor('../plantillas/etiqueta.docx');
                    
                            $template->setValue('cliente_nombre', $row['TerNom']);
                            $template->setValue('cliente_telefono', $row['TerTel']);
                            $template->setValue('cliente_direccion', $row['TerDir']);
                            $template->setValue('observaciones', $row['ObservacionesVer']);
                            $template->setValue('e', $clave);
                            $template->setValue('n', $j);
                            $template->setValue('t', $valor);
                    
                            $inputFile = '../documentos/documento_generado'.$i.'.docx';
                            $outputFile = '../documentos/documento_generado'.$i.'.pdf';
                    
                            $template->saveAs($inputFile);
                    
                            $command = "libreoffice --convert-to pdf $inputFile  --outdir " . dirname($outputFile);
                    
                            $output = [];
                            $returnCode = 0;
                    
                            exec($command, $output, $returnCode);
                    
                            if (!(unlink($inputFile))) {
                                echo "No se pudo borrar: " . $inputFile;
                            }
                    
                            $j++;
                            $i++;
                        }
                    } catch (Exception $e) {
                        echo 'Error en el bucle while: ' . $e->getMessage();
                    }
                }
            }
        }

    }

?>