<?php
    require_once '../controladores/Connection.php';

    try {

        $con = Connection::getInstance()->getConnection();
        $sql = 
            "SELECT 
                B.*,
                F.PrfId,
                F.VtaNum,
                U.Nombres,
                U.Apellidos,
                DATE_FORMAT(B.FechaHora, '%Y-%m-%d') AS fechaBitacora, 
                TIME_FORMAT(B.FechaHora, '%h:%i %p') AS horaBitacora
            FROM 
                Bitacora AS B
            LEFT JOIN
                Facturas AS F
                ON
                    B.idFactura = F.vtaid
            LEFT JOIN
                Usuarios AS U
                ON
                    B.idUsuario = U.idUsuarios
            ";

        if ($_GET['inicial'] != "" || $_GET['final'] != "" || $_GET['usuario'] != 0){
            $sql = $sql . " WHERE ";
            if ($_GET['inicial'] != ""){
                $sql = $sql . " B.FechaHora >= '" . $_GET['inicial'] . "' ";
            }
            if ($_GET['inicial'] != "" && $_GET['final'] != ""){
                $sql = $sql . " AND " ;
            }
            if ($_GET['final'] != ""){
                $sql = $sql . " B.FechaHora <= '" . $_GET['final'] . "' ";
            }
            if ($_GET['final'] != "" && $_GET['usuario'] != 0){
                $sql = $sql . " AND " ;
            }
            if ( $_GET['usuario'] != 0){
                $sql = $sql . " B.idUsuario = '" . $_GET['usuario'] . "' ";
            }
        }

        $quer = $con->query($sql);

        $response = array();

        while ($columna = $quer->fetch_assoc()) {
            $row['fecha'] = "<p>" . $columna['fechaBitacora'] . "</p>";
            $row['hora'] = "<p>" . $columna['horaBitacora'] . "</p>";
            $row['usuario'] = "<p>" . utf8_encode($columna['Nombres']) . " " . utf8_encode($columna['Apellidos']) . "</p>";
            $row['accion'] = "<p>" . $columna['Accion'] . "</p>";
            $row['factura'] = "<p>" .  $columna['PrfId'] . " " .$columna['VtaNum'] ."</p>";

            // $rowClass = $columna['Forzado'] == '1' ? 'fila-amarilla' : '';
            // $row['DT_RowClass'] = $rowClass;


            $response[] = $row;
        }


        header('Content-Type: application/json');
        echo json_encode(array("data" => $response));


    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $con->close();

?>
