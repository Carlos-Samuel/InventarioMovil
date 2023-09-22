<?php
    require_once 'Connection.php';
    require_once 'Connection2.php';

    $con = Connection::getInstance()->getConnection();
    $quer = $con->query("SELECT COALESCE(MAX(vtaid), 0) AS max_vtaid FROM Facturas");

    if ($quer->num_rows > 0) {
        $row = $quer->fetch_assoc();

        $maxVtaid = $row['max_vtaid'];
    }

    try {

        $con2 = Connection2::getInstance2()->getConnection2();

        $consultaBusqueda1 = 
            "SELECT 
                COALESCE(TRIM(ve.vtaid), '') AS vtaid, 
                COALESCE(TRIM(ve.VtaNum), '') AS VtaNum, 
                COALESCE(TRIM(ve.PrfId), 'DATO NO DISPONIBLE') AS PrfId, 
                COALESCE(TRIM(ve.vtafec), '') AS vtafec, 
                COALESCE(TRIM(ve.vtahor), '') AS vtahor, 
                COALESCE(TRIM(ve.TerId), '') AS TerId, 
                COALESCE(TRIM(ve.TerNom), 'DATO NO DISPONIBLE') AS TerNom, 
                COALESCE(TRIM(ve.TerDir), 'DATO NO DISPONIBLE') AS TerDir, 
                COALESCE(TRIM(ve.TerTel), 'DATO NO DISPONIBLE') AS TerTel, 
                COALESCE(TRIM(ter.terrzn), 'DATO NO DISPONIBLE') AS terrzn, 
                COALESCE(TRIM(ve.VenId), '') AS VenId, 
                COALESCE(TRIM(us.UsuNom), 'DATO NO DISPONIBLE') AS UsuNom, 
                COALESCE(TRIM(ve.CiuId), 0) AS CiuId, 
                COALESCE(TRIM(ci.ciunom), 'DATO NO DISPONIBLE') AS ciunom, 
                COALESCE(TRIM(ve.vtaobs), 'SIN OBSERVACIONES') AS vtaobs
            FROM
                ventas AS ve
            LEFT JOIN terceros AS ter ON ter.terid = ve.TerId
            LEFT JOIN usuarios AS us ON us.UsuID = ve.VenId
            LEFT JOIN ciudad AS ci ON ci.ciuid = ve.CiuId
            WHERE 
                vtaid > $maxVtaid
                AND vtaid > 230695
            ;";

        $quer = $con2->query($consultaBusqueda1);

        $resultados = array();

        if ($quer->num_rows > 0) {
            while ($fila = $quer->fetch_assoc()) {
                $resultados[] = $fila;
            }
        } else {
            echo "No se encontraron resultados.";
        }

        foreach ($resultados as $resultado) {

            $consulta = 
            "INSERT INTO Facturas(vtaid, VtaNum, PrfId, vtafec, vtahor, TerId, TerNom, TerDir, TerTel, TerRaz, VenId, VenNom, CiuId, CiuNom, facObservaciones, facEstado, MomentoCarga)
            VALUES (
                '{$resultado['vtaid']}', '{$resultado['VtaNum']}', '{$resultado['PrfId']}', '{$resultado['vtafec']}', '{$resultado['vtahor']}', '{$resultado['TerId']}', '{$resultado['TerNom']}', '{$resultado['TerDir']}', '{$resultado['TerTel']}', '{$resultado['terrzn']}', '{$resultado['VenId']}', '{$resultado['UsuNom']}', {$resultado['CiuId']}, '{$resultado['ciunom']}', '{$resultado['vtaobs']}', 1, TIME(NOW())
            );";

            $finalConsulta = $con->query($consulta);

            $consultaBusqueda = 
                "SELECT 
                    COALESCE(TRIM(ved.VtaId), '') AS vtaid, 
                    COALESCE(TRIM(ved.VtaDetId), '') AS vtadetid, 
                    COALESCE(TRIM(ved.ProId), '') AS proid, 
                    COALESCE(TRIM(ved.ProNom), 'DATO NO DISPONIBLE') AS pronom, 
                    COALESCE(TRIM(NULLIF(pro.ProUbica, '')), 'DATO NO DISPONIBLE') AS proubica, 
                    COALESCE(TRIM(NULLIF(pro.ProUnd, '')), 'DATO NO DISPONIBLE') AS pround, 
                    COALESCE(TRIM(ved.VtaCant), 0) AS vtacant 
                FROM
                    ventasdet AS ved
                LEFT JOIN productos AS pro ON pro.ProId = ved.ProId
                WHERE 
                    VtaId = {$resultado['vtaid']}";

            $quer = $con2->query($consultaBusqueda);

            $resultados2 = array();

            if ($quer->num_rows > 0) {
                while ($fila = $quer->fetch_assoc()) {
                    $resultados2[] = $fila;
                }
            } else {
                echo "No se encontraron resultados.";
            }

            foreach ($resultados2 as $resultado) {

                $consulta2 = 
                    "INSERT INTO Productos
                        (VtaId, VtaDetId, ProId, ProNom, ProUbica, ProPresentacion, VtaCant) 
                    VALUES 
                        ('{$resultado['vtaid']}','{$resultado['vtadetid']}','{$resultado['proid']}','{$resultado['pronom']}','{$resultado['proubica']}','{$resultado['pround']}','{$resultado['vtacant']}')
                    ;";

                $finalConsulta = $con->query($consulta2);
            }

        }

        

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $con->close();

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ejemplo de PHP</title>
    </head>
    <body>
        <h1>Recorriendo en <?php echo $maxVtaid ?></h1>
        <a href="/dashboard.php">Volver</a>
    </body>
</html>
