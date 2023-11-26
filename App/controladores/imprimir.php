<?php
    require_once 'Connection.php';

    require '../vendor/autoload.php';

    include 'funciones.php';

    use PhpOffice\PhpWord\TemplateProcessor;
    use PhpOffice\PhpWord\PhpWord;

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

        $rutas = array();

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
                            $template->setValue('razon_social', $row['TerRaz']);
                            $template->setValue('cliente_telefono', $row['TerTel']);
                            $template->setValue('cliente_direccion', $row['TerDir']);
                            $template->setValue('prefijo', $row['PrfCod']);
                            $template->setValue('n_factura', $row['VtaNum']);
                            $template->setValue('ciudad', $row['CiuNom']);
                            $template->setValue('observaciones', $row['ObservacionesVer']);
                            $template->setValue('e', $clave);
                            $template->setValue('n', $j);
                            $template->setValue('t', $valor);
                    
                            $inputFile = '../documentos/documento_generado'.$i.'.docx';
                            $outputFile = '../documentos/documento_generado'.$i.'.pdf';

                            $rutas[] = $outputFile;
                    
                            $template->saveAs($inputFile);
                    
                            //$command = "libreoffice --convert-to pdf $inputFile  --outdir " . dirname($outputFile);

                            $command = '"C:\Program Files\libreOffice\program\soffice.bin" --convert-to pdf ' . $inputFile . '  --outdir ' . dirname($outputFile);
                            
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
                        return 'Error en el bucle while: ' . $e->getMessage();
                    }
                }
            }
        }

        try{

            $ruta_gs = "C:\Program Files\gs\gs10.02.0\bin\gswin64c.exe";

            $outputFinal = '../documentos/etiquetas'.$id.'.pdf';

            $comando_final = '"' . $ruta_gs . '" -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=' . $outputFinal;

            foreach ($rutas as $ruta) {

                $comando_final = $comando_final . " " . $ruta;

            }

            $output = [];
            $returnCode = 0;
    
            exec($comando_final, $output, $returnCode);

            foreach ($rutas as $ruta) {

                if (!(unlink($ruta))) {
                    echo "No se pudo borrar: " . $inputFile;
                }

            }

        } catch (Exception $e) {
            return 'Error en el bucle while: ' . $e->getMessage();
        }

        return "Termino";

    }

?>