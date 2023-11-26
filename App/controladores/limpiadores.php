<?php
    function limpiarAlistamiento($con, $id_recibido) {
        try{
            $sql = "UPDATE Facturas SET MomentoCarga = NULL, InicioAlistamiento = NULL WHERE vtaid = $id_recibido";
            $con->query($sql);

            $sql = "UPDATE Productos SET AlisCant = 0 WHERE VtaId = $id_recibido";
            $con->query($sql);

        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
        return "Correcto";
    }

    function limpiarVerificacion($con, $id_recibido) {
        try{
            $sql = "UPDATE Facturas SET InicioVerificacion = NULL WHERE vtaid = $id_recibido";
            $con->query($sql);

            $sql = "UPDATE Productos SET VerCant = -1 WHERE VtaId = $id_recibido";
            $con->query($sql);

        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
        return "Correcto";
    }

?>