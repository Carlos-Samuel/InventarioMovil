<?php
    require_once 'Connection.php';

    try {

        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['fecha']) && !empty($data['fecha'])) {
            $fecha_maxima = $data['fecha'];
        } else {
            $fecha_maxima = '2030-11-26';
        }

        $con = Connection::getInstance()->getConnection();

        $sql = "UPDATE Facturas SET facEstado = 9 WHERE vtafec < '$fecha_maxima' AND InicioAlistamiento IS NULL";
        $resultado = $con->query($sql);

        $respuesta = array(
            "mensaje" => "Los datos fueron borrados correctamente"
        );

    } catch (PDOException $sqlException) {
        $respuesta = array(
            "mensaje" => "Error SQL: " . $sqlException->getMessage()
        );
    } catch (Exception $e) {
        $respuesta = array(
            "mensaje" => "Error general: " . $e->getMessage(),
        );
    }


    header('Content-Type: application/json');
    echo json_encode($respuesta);

    exit();
    

?>

