<?php
    require_once '../controladores/Connection.php';

    try {

        $numero = $_GET['numero'];

        $estadoA = ($numero - 1)*2 + 1;
        $estadoB = ($numero - 1)*2 + 2;

        $con = Connection::getInstance()->getConnection();
        $quer = $con->query(
            "SELECT 
                * 
            FROM 
                Facturas 
            WHERE 
                facEstado = $estadoA 
                OR facEstado = $estadoB;
        ");

        $response = array();

        if ($numero != 0){
  
            while ($columna = $quer->fetch_assoc()) {
                $row['id'] = "<p>" . $columna['PrfId'] . " " .$columna['VtaNum'] . "</p>";
                $row['fecha'] = "<p>" . $columna['vtafec'] . "</p>";
                $row['hora'] = "<p>" . utf8_encode($columna['vtahor']) . "</p>";
                $row['cliente'] = "<p>" . utf8_encode($columna['TerNom']) . "</p>";
                $row['razon'] = "<p>" . utf8_encode($columna['TerRaz']) . "</p>";

                $response[] = $row;
            }
        
        }

        header('Content-Type: application/json');
        echo json_encode(array("data" => $response));


    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $con->close();

?>
