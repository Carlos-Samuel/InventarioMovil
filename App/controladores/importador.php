<?php
    require_once 'Connection.php';
    require_once 'Connection2.php';

    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['fecha']) && !empty($data['fecha'])) {
        $fecha_minima = $data['fecha'];
    } else {
        $fecha_minima = '2023-08-12';
    }

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
                COALESCE(TRIM(ven.vennom), 'DATO NO DISPONIBLE') AS UsuNom, 
                COALESCE(TRIM(ve.CiuId), 0) AS CiuId, 
                COALESCE(TRIM(ci.ciunom), 'DATO NO DISPONIBLE') AS ciunom, 
                COALESCE(TRIM(ve.vtaobs), 'SIN OBSERVACIONES') AS vtaobs
            FROM
                ventas AS ve
            LEFT JOIN terceros AS ter ON ter.terid = ve.TerId
            LEFT JOIN vendedor AS ven ON ven.venid = ve.VenId
            LEFT JOIN ciudad AS ci ON ci.ciuid = ve.CiuId
            WHERE 
                vtaid > $maxVtaid
                and vtafec >= '".$fecha_minima."'
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
                    COALESCE(TRIM(pro.ProCod), '') AS procod, 
                    COALESCE(TRIM(ved.ProNom), 'DATO NO DISPONIBLE') AS pronom, 
                    COALESCE(TRIM(NULLIF(pro.ProUbica, '')), 'DATO NO DISPONIBLE') AS proubica, 
                    COALESCE(TRIM(NULLIF(pro.ProUnd, '')), 'DATO NO DISPONIBLE') AS pround, 
                    TRIM(NULLIF(pro.ProCodBar, '')) AS probarcode, 
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
                        (VtaId, VtaDetId, ProId, ProCod, ProNom, ProUbica, ProPresentacion, ProCodBar, VtaCant) 
                    VALUES 
                        ('{$resultado['vtaid']}','{$resultado['vtadetid']}','{$resultado['proid']}','{$resultado['procod']}','{$resultado['pronom']}','{$resultado['proubica']}','{$resultado['pround']}','{$resultado['probarcode']}','{$resultado['vtacant']}')
                    ;";

                $finalConsulta = $con->query($consulta2);
            }

        }

        

    } catch (Exception $e) {
        $respuesta = array(
            "mensaje" => "Error al importar los datos ". $e->getMessage()
        );
    
        header('Content-Type: application/json');
        echo json_encode($respuesta);
    
        exit();
        
    }

    $con->close();

    $respuesta = array(
        "mensaje" => "Los datos fueron importados correctamente"
    );

    header('Content-Type: application/json');
    echo json_encode($respuesta);

    exit();
    

?>

