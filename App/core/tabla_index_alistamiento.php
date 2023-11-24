<?php
    require_once '../controladores/Connection.php';


    try {

        $con = Connection::getInstance()->getConnection();
        $quer = $con->query("SELECT * FROM Facturas WHERE facEstado = 1 OR facEstado = 2 ORDER BY vtafec ASC, vtahor ASC;");

        $response = array();



        while ($columna = $quer->fetch_assoc()) {


            $row['id'] = "<p>" . $columna['PrfId'] . " " .$columna['VtaNum'] . "</p>";
            $row['fecha'] = "<p>" . $columna['vtafec'] . "</p>";
            $row['nombre'] = "<p>" . utf8_encode($columna['TerNom']) . "</p>";
            $row['razon'] = "<p>" . utf8_encode($columna['TerRaz']) . "</p>";
            $row['ciudad'] = "<p>" . utf8_encode($columna['CiuNom']) . "</p>";
            $row['vendedor'] = "<p>" . utf8_encode($columna['VenNom']) . "</p>";
            $row['hora'] = "<p>" . $columna['vtahor'] . "</p>";
            $row['observacion'] = "<p>" . utf8_encode($columna['facObservaciones']) . "</p>";

            $boton = "<a href='alistamiento.php?id=" . $columna['vtaid'] . "' class='btn btn-primary'>Procesar</a>";
            $row['accion'] = $boton;

            $boton = "<a href='noProcesar.php?id=" . $columna['vtaid'] . "' class='btn btn-danger'>X</a>";
            $row['segundaAccion'] = $boton;

            $rowClass = $columna['facEstado'] == '2' ? 'fila-verde' : '';
            $row['DT_RowClass'] = $rowClass;

            $response[] = $row;
        }

        header('Content-Type: application/json; charset=utf-8');
        echo(json_encode(array("data" => $response))); 


    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $con->close();

?>
