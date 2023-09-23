<?php
    require_once '../controladores/Connection.php';

    include 'funciones.php';

    try {

        $con = Connection::getInstance()->getConnection();
        $sql = 
            "SELECT 
                F.*,
                A.Nombres AS NombresAlistador,
                A.Apellidos AS ApellidosAlistador,
                TIMESTAMPDIFF(MINUTE, InicioAlistamiento, FinAlistamiento) AS duracionAlistamiento,
                V.Nombres AS NombresVerificador,
                V.Apellidos AS ApellidosVerificador,
                TIMESTAMPDIFF(MINUTE, InicioVerificacion, FinVerificacion) AS duracionVerificacion,
                E.Nombres AS NombresEntregador,
                E.Apellidos AS ApellidosEntregador,
                TIMESTAMPDIFF(MINUTE, InicioEntrega, FinEntrega) AS duracionEntrega,
                LTRIM(F.Embalaje) AS embalaje,
                Est.Descripcion AS estado,
                CONCAT(
                    DATE_FORMAT(F.FinAlistamiento, '%Y-%m-%d'), 
                    ' ', 
                    TIME_FORMAT(F.FinAlistamiento, '%h:%i %p')
                ) AS fecha_y_hora_alistado, 
                CONCAT(
                    DATE_FORMAT(F.FinVerificacion, '%Y-%m-%d'), 
                    ' ', 
                    TIME_FORMAT(F.FinVerificacion, '%h:%i %p')
                ) AS fecha_y_hora_verificado,
                CONCAT(
                    DATE_FORMAT(F.FinEntrega, '%Y-%m-%d'), 
                    ' ', 
                    TIME_FORMAT(F.FinEntrega, '%h:%i %p')
                ) AS fecha_y_hora_entregado
            FROM 
                Facturas AS F
            LEFT JOIN
                Usuarios AS A
                ON
                    A.idUsuarios = F.idAlistador
            LEFT JOIN
                Usuarios AS V
                ON
                    V.idUsuarios = F.idVerificador
            LEFT JOIN
                Usuarios AS E
                ON
                    E.idUsuarios = F.idEntregador
            LEFT JOIN
                Estados AS Est
                ON
                    Est.idEstados = F.facEstado
            ";
            
        

        if ($_GET['inicial'] != "" || $_GET['final'] != ""){
            $sql = $sql . " WHERE ";
            if ($_GET['inicial'] != ""){
                $sql = $sql . " F.vtafec >= '" . $_GET['inicial'] . "' ";
            }
            if ($_GET['inicial'] != "" && $_GET['final'] != ""){
                $sql = $sql . " AND " ;
            }
            if ($_GET['final'] != ""){
                $sql = $sql . " F.vtafec <= '" . $_GET['final'] . "' ";
            }
        }

        $quer = $con->query($sql);


        $response = array();

        while ($columna = $quer->fetch_assoc()) {
            $row['id'] = "<p>" . $columna['PrfId'] . " " .$columna['VtaNum'] . "</p>";
            $row['nombre'] = "<p>" . ($columna['TerNom']) . " " . ($columna['TerRaz']) . "</p>";
            $row['fecha'] = "<p>" . $columna['vtafec'] . " " . $columna['vtahor'] . "</p>";
            $row['vendedor'] = "<p>" . ($columna['VenNom']) . "</p>";
            $row['alistador'] = "<p>" . ($columna['NombresAlistador']) . " " .($columna['ApellidosAlistador']) . " " . $columna['fecha_y_hora_alistado'] ."</p>";
            $row['horaAlistado'] = "<p>" . $columna['duracionAlistamiento'] . "</p>";
            $row['verificador'] = "<p>" . ($columna['NombresVerificador']) . " " .($columna['ApellidosVerificador']) . " " . $columna['fecha_y_hora_verificado'] ."</p>";
            $row['horaVerificado'] = "<p>" . $columna['duracionVerificacion'] . "</p>";
            $row['entregador'] = "<p>" . ($columna['NombresEntregador']) . " " .($columna['ApellidosEntregador']) . " " . $columna['fecha_y_hora_entregado'] ."</p>";
            $row['horaEntrega'] = "<p>" . $columna['duracionEntrega'] . "</p>";
            $row['estado'] = "<p>" . $columna['estado'] . "</p>";

            // $rowClass = $columna['Forzado'] == '1' ? 'fila-amarilla' : '';
            // $row['DT_RowClass'] = $rowClass;

            utf8_encode_array($row);

            $response[] = $row;
        }


        header('Content-Type: application/json');
        echo json_encode(array("data" => $response));


    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $con->close();

?>
