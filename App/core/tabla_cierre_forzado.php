<?php
    require_once '../controladores/Connection.php';

    try {

        $con = Connection::getInstance()->getConnection();
        $sql = 
            "SELECT 
                F.*,
                A.Nombres AS NombresAlistador,
                A.Apellidos AS ApellidosAlistador,
                TIMESTAMPDIFF(MINUTE, InicioAlistamiento, FinAlistamiento) AS duracionAlistamiento,
                Forz.Nombres AS NombresForzador,
                Forz.Apellidos AS ApellidosForzador,
                TIMESTAMPDIFF(MINUTE, InicioVerificacion, FinVerificacion) AS duracionVerificacion,
                TIMESTAMPDIFF(MINUTE, InicioEntrega, FinEntrega) AS duracionEntrega,
                LTRIM(F.Embalaje) AS embalaje,
                Est.Descripcion AS estado,
                CONCAT(
                    DATE_FORMAT(F.FinAlistamiento, '%Y-%m-%d'), 
                    ' ', 
                    TIME_FORMAT(F.FinAlistamiento, '%h:%i %p')
                ) AS fecha_y_hora_alistado
            FROM 
                Facturas AS F
            LEFT JOIN
                Usuarios AS A
                ON
                    A.idUsuarios = F.idAlistador
            LEFT JOIN
                Usuarios AS Forz
                ON
                    Forz.idUsuarios = F.Forzador
            LEFT JOIN
                Estados AS Est
                ON
                    Est.idEstados = F.facEstado
            WHERE
                F.Forzador = 1
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
            $row['fecha_alistado'] = "<p>" . $columna['fecha_y_hora_alistado'] . "</p>";
            $row['forzador'] = "<p>" . ($columna['NombresForzador']) . " " .($columna['ApellidosForzador']) ."</p>";
            $row['alistador'] = "<p>" . ($columna['NombresAlistador']) . " " .($columna['ApellidosAlistador']) ."</p>";
            $row['ObservacionesFor'] = "<p>" . $columna['ObservacionesFor'] . "</p>";

            utf8_encode_array($row);

            $response[] = $row;
        }


        header('Content-Type: application/json');
        echo json_encode(array("data" => $response));


    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $con->close();

    function utf8_encode_array(&$array) {
        foreach ($array as &$value) {
            if (is_string($value) && $value !== "NULL" && !empty($value)) {
                $value = utf8_encode($value);
            }
        }
    }

?>
