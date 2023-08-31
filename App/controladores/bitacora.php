<?php

    function bitacoraLog($accion, $idFactura){

        date_default_timezone_set('America/Bogota');
    
        $idUsuarios = $_SESSION["idUsuarios"];
    
        $horaLocal = date('Y-m-d H:i:s');
    
        $con = Connection::getInstance()->getConnection();

        $sql = "INSERT INTO Bitacora(FechaHora, idUsuario, Accion, idFactura) VALUES ('$horaLocal', $idUsuarios,'$accion',$idFactura);";

        $quer = $con->query($sql);
        
    }

?>