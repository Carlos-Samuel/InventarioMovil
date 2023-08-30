<?php
    require_once '../controladores/Connection.php';

    try {

        $con = Connection::getInstance()->getConnection();
        $quer = $con->query("select * from Embalajes");

        $response = array();

        while ($columna = $quer->fetch_assoc()) {
            $row['id'] = "<p>" . $columna['idEmbalajes'] . "</p>";
            $row['descripcion'] = "<p>" . $columna['Descripcion'] . "</p>";

            $boton = "<button data-id=" . $columna['idEmbalajes'] . " class='btn btn-danger btnEliminar'>Eliminar</button>";
            $row['borrar'] = $boton;

            $response[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode(array("data" => $response));

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $con->close();

?>
