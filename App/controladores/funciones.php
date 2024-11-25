<?php

    function bitacoraLog($accion, $idFactura){

        date_default_timezone_set('America/Bogota');
    
        $idUsuarios = $_SESSION["idUsuarios"];
    
        $horaLocal = date('Y-m-d H:i:s');
    
        $con = Connection::getInstance()->getConnection();

        $sql = "INSERT INTO Bitacora(FechaHora, idUsuario, Accion, idFactura) VALUES ('$horaLocal', $idUsuarios,'$accion',$idFactura);";

        $quer = $con->query($sql);
        
    }

    function utf8_encode_array(&$array) {
        try {
            foreach ($array as &$value) {
                if (is_string($value) && $value !== "NULL" && !empty($value)) {
                    $value = utf8_encode($value);
                }
            }
        } catch (Exception $e) {
            echo "Error al codificar a UTF-8: " . $e->getMessage();
        }
    }

?>