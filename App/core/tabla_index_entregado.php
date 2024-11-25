<?php
    require_once '../controladores/Connection.php';

    try {

        $con = Connection::getInstance()->getConnection();
        $quer = $con->query(
            "SELECT 
                F.*, 
                A.Nombres AS NombresAlistador, 
                A.Apellidos AS ApellidosAlistador, 
                V.Nombres AS NombresVerificador, 
                V.Apellidos AS ApellidosVerificador, 
                CONCAT(
                    DATE_FORMAT(F.FinAlistamiento, '%Y-%m-%d'), 
                    ' ', 
                    TIME_FORMAT(F.FinAlistamiento, '%h:%i %p')
                ) AS fecha_y_hora_alistado, 
                CONCAT(
                    DATE_FORMAT(F.FinVerificacion, '%Y-%m-%d'), 
                    ' ', 
                    TIME_FORMAT(F.FinVerificacion, '%h:%i %p')
                ) AS fecha_y_hora_verificado
            FROM 
                Facturas AS F, Usuarios AS A, Usuarios AS V
            WHERE 
                F.idAlistador = A.idUsuarios 
                AND F.idVerificador = V.idUsuarios 
                AND (F.facEstado = 5 OR F.facEstado = 6)
            ORDER BY vtafec ASC, vtahor ASC;
            ;
        ");

        $response = array();

        while ($columna = $quer->fetch_assoc()) {
            $row['id'] = "<p>" . $columna['PrfCod'] . " " .$columna['VtaNum'] . "</p>";
            $row['fecha'] = "<p>" . $columna['vtafec'] . " " . $columna['vtahor'] . "</p>";
            $row['nombre'] = "<p>" . utf8_encode($columna['TerNom']) . "</p>";
            $row['razon'] = "<p>" . utf8_encode($columna['TerRaz']) . "</p>";
            $row['ciudad'] = "<p>" . utf8_encode($columna['CiuNom']) . "</p>";
            $row['vendedor'] = "<p>" . utf8_encode($columna['VenNom']) . "</p>";
            $row['alistador'] = "<p>" . $columna['NombresAlistador'] . " " .$columna['ApellidosAlistador'] ."</p>";
            $row['horaAlistado'] = "<p>" . $columna['fecha_y_hora_alistado'] . "</p>";
            $row['verificador'] = "<p>" . $columna['NombresVerificador'] . " " .$columna['ApellidosVerificador'] ."</p>";
            $row['horaVerificado'] = "<p>" . $columna['fecha_y_hora_verificado'] . "</p>";
            $row['observacion'] = "<p>" . utf8_encode($columna['facObservaciones']) . "</p>";

            if ($columna['estadoImpresion']=='Impreso'){
                $documento = "<a href='documentos/etiquetas" . $columna['vtaid'] . ".pdf' target='_blank'><i class = 'fa fa-print'></i></a>";
            }else{
                $documento =  "<p>" . $columna['estadoImpresion'] . "</p>";
            }

            $row['documento'] = $documento;
            
            $boton = "<a href='entrega.php?id=" . $columna['vtaid'] . "' class='btn btn-primary'>Procesar</a>";
            $row['accion'] = $boton;

            $rowClass = $columna['Forzado'] == '1' ? 'fila-amarilla' : '';
            $row['DT_RowClass'] = $rowClass;


            $response[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode(array("data" => $response));

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $con->close();

?>
