<?php
    require_once '../controladores/Connection.php';

    try {

        $con = Connection::getInstance()->getConnection();
        $quer = $con->query("
            SELECT 
                F.*, 
                U.Nombres, 
                U.Apellidos, 
                CONCAT(
                    DATE_FORMAT(F.FinAlistamiento, '%Y-%m-%d'), 
                    ' ', 
                    TIME_FORMAT(F.FinAlistamiento, '%h:%i %p')
                ) AS fecha_y_hora
            FROM 
                Facturas AS F, Usuarios AS U 
            WHERE 
                F.idAlistador = U.idUsuarios 
                AND (F.facEstado = 3 OR F.facEstado = 4 )
            ;
        ");

        $response = array();

        while ($columna = $quer->fetch_assoc()) {
            $row['id'] = "<p>" . $columna['PrfId'] . " " .$columna['VtaNum'] . "</p>";
            $row['fecha'] = "<p>" . $columna['vtafec'] . " " . $columna['vtahor'] . "</p>";
            $row['nombre'] = "<p>" . $columna['TerNom'] . "</p>";
            $row['razon'] = "<p>" . $columna['TerRaz'] . "</p>";
            $row['ciudad'] = "<p>" . $columna['CiuNom'] . "</p>";
            $row['vendedor'] = "<p>" . $columna['VenNom'] . "</p>";
            $row['alistador'] = "<p>" . $columna['Nombres'] . " " .$columna['Apellidos'] ."</p>";
            $row['horaAlistado'] = "<p>" . $columna['fecha_y_hora'] . "</p>";

            $boton = "<a href='verificacion.php?id=" . $columna['vtaid'] . "' class='btn btn-primary'>Procesar</a>";
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
