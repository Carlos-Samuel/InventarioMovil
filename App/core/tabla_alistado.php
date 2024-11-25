<?php
    require_once '../controladores/Connection.php';

    include '../controladores/funciones.php';

    function convertSecondsToTime($seconds) {
        $seconds = floatval($seconds);
    
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = $seconds % 60;
    
        $seconds = floatval($seconds);
    
        $timeString = '';
    
        if ($hours > 0) {
            $timeString .= sprintf("%02d:", $hours);
        }
    
        if ($minutes > 0 || $hours > 0) {
            $timeString .= sprintf("%02d:", $minutes);
        }
    
        $timeString .= sprintf("%02d", $seconds);
    
        return $timeString;
    }

    try {

        $con = Connection::getInstance()->getConnection();
        $sql = "
            SELECT 
                A.idUsuarios AS idAlistador,
                CONCAT(A.Nombres, ' ', A.Apellidos) AS Alistador,
                MIN(F.InicioAlistamiento) AS FechaInicioAlistamiento,
                MAX(F.FinAlistamiento) AS FechaFinAlistamiento,
                COUNT(DISTINCT F.vtaid) AS numFacturasAlistadas,
                COUNT(P.ProId) AS numItems,
                SUM(P.AlisCant) AS numProductosAlistados,
                SUM(TIMESTAMPDIFF(MINUTE, F.InicioAlistamiento, F.FinAlistamiento)) AS tiempoTotal,
                (SUM(TIMESTAMPDIFF(MINUTE, F.InicioAlistamiento, F.FinAlistamiento)) / COUNT(P.ProId)) AS tiempoPromedioCadaItem
            FROM 
                Usuarios AS A
            LEFT JOIN
                Facturas AS F
                ON
                    A.idUsuarios = F.idAlistador
            LEFT JOIN
                Productos AS P
                ON
                    F.vtaid = P.vtaid
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

        $sql = $sql . "GROUP BY A.idUsuarios, A.Nombres, A.Apellidos ";

        $quer = $con->query($sql);


        $response = array();

        while ($columna = $quer->fetch_assoc()) {
            $row['alistador'] = "<p>" . $columna['Alistador'] . "</p>";
            $row['rango'] = "<p>" . $_GET['inicial'] . " - " . $_GET['final'] . "</p>";
            $row['facturasAlistadas'] = "<p>" . $columna['numFacturasAlistadas'] . "</p>";
            $row['items'] = "<p>" . $columna['numItems'] . "</p>";
            $row['productosAlistados'] = "<p>" . $columna['numProductosAlistados'] . "</p>";
            $row['tiempoTotal'] = "<p>" . convertSecondsToTime($columna['tiempoTotal']) . "</p>";
            $row['tiempoPromedio'] = "<p>" . convertSecondsToTime($columna['tiempoPromedioCadaItem']) . "</p>";

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
