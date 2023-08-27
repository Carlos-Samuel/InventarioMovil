<?php
    require_once '../controladores/Connection.php';

    try {

        $con = Connection::getInstance()->getConnection();
        $quer = $con->query("SELECT * FROM Facturas WHERE facEstado = 1 OR facEstado = 2");

        $response = array();

        while ($columna = $quer->fetch_assoc()) {
            $row['id'] = "<p>" . $columna['PrfId'] . " " .$columna['VtaNum'] . "</p>";
            $row['fecha'] = "<p>" . $columna['vtafec'] . "</p>";
            $row['nombre'] = "<p>" . $columna['TerNom'] . "</p>";
            $row['razon'] = "<p>" . $columna['TerRaz'] . "</p>";
            $row['ciudad'] = "<p>" . $columna['CiuNom'] . "</p>";
            $row['vendedor'] = "<p>" . $columna['VenNom'] . "</p>";
            $row['hora'] = "<p>" . $columna['vtahor'] . "</p>";
            $row['observacion'] = "<p>" . $columna['facObservaciones'] . "</p>";

            $boton = "<a href='alistamiento.php?id=" . $columna['vtaid'] . "' class='btn btn-primary'>Procesar</a>";
            $row['accion'] = $boton;

            $rowClass = $columna['facEstado'] == '2' ? 'fila-verde' : '';
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
