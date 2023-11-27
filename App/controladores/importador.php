<?php
    require_once 'Connection.php';
    require_once 'Connection2.php';
    require_once 'filtroEmpresas.php';

    set_time_limit(900); 

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
                COALESCE(TRIM(ve.vtaobs), 'SIN OBSERVACIONES') AS vtaobs,
                pr.PrfCod AS PrfCod
            FROM
                ventas AS ve
            LEFT JOIN terceros AS ter ON ter.terid = ve.TerId
            LEFT JOIN vendedor AS ven ON ven.venid = ve.VenId
            LEFT JOIN ciudad AS ci ON ci.ciuid = ve.CiuId
            LEFT JOIN Prefijo AS pr ON pr.PrfId = ve.PrfId
            WHERE 
                vtaid > $maxVtaid
                AND vtafec >= '".$fecha_minima."'  
                ".$filtroEmpresa."
            ;";

        $quer = $con2->query($consultaBusqueda1);

        $resultados = array();

        if ($quer->num_rows > 0) {
            while ($fila = $quer->fetch_assoc()) {
                $resultados[] = $fila;
            }
        } else {
            $respuesta = array(
                "mensaje" => "Ninguna factura encontrada"
            );

            header('Content-Type: application/json');
            echo json_encode($respuesta);
        
            exit();
        }

        foreach ($resultados as $resultado) {

            //$consulta = 
           // "INSERT INTO Facturas(vtaid, VtaNum, PrfId, vtafec, vtahor, TerId, TerNom, TerDir, TerTel, TerRaz, VenId, VenNom, CiuId, CiuNom, facObservaciones, facEstado, MomentoCarga, PrfCod)
            //VALUES (
            //    '{$resultado['vtaid']}', '{$resultado['VtaNum']}', '{$resultado['PrfId']}', '{$resultado['vtafec']}', '{$resultado['vtahor']}', '{$resultado['TerId']}', '{$resultado['TerNom']}', '{$resultado['TerDir']}', '{$resultado['TerTel']}', '{$resultado['terrzn']}', '{$resultado['VenId']}', '{$resultado['UsuNom']}', {$resultado['CiuId']}, '{$resultado['ciunom']}', '{$resultado['vtaobs']}', 1, TIME(NOW()), '{$resultado['PrfCod']}'
            //);";

            //$finalConsulta = $con->query($consulta);

            $vtaid = (int)$resultado['vtaid'];
            $VtaNum = (int)$resultado['VtaNum'];
            $PrfId = (int)$resultado['PrfId'];
            $vtafec = $resultado['vtafec'];
            $vtahor = $resultado['vtahor'];
            $TerId = (int)$resultado['TerId'];
            $TerNom = $resultado['TerNom'];
            $TerDir = $resultado['TerDir'];
            $TerTel = $resultado['TerTel'];
            $terrzn = $resultado['terrzn'];
            $VenId = (int)$resultado['VenId'];
            $UsuNom = $resultado['UsuNom'];
            $CiuId = (int)$resultado['CiuId'];
            $ciunom = $resultado['ciunom'];
            $vtaobs = $resultado['vtaobs'];
            $PrfCod = $resultado['PrfCod'];

            // Crear una conexión a la base de datos (supongo que ya tienes esto configurado)

            // Definir la consulta preparada
            $consulta = "INSERT INTO Facturas (vtaid, VtaNum, PrfId, vtafec, vtahor, TerId, TerNom, TerDir, TerTel, TerRaz, VenId, VenNom, CiuId, CiuNom, facObservaciones, facEstado, MomentoCarga, PrfCod) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, TIME(NOW()), ?)";

            // Preparar la consulta
            if ($stmt = $con->prepare($consulta)) {
                // Vincular parámetros y tipos de datos
                $stmt->bind_param("iissssssssssssss", $vtaid, $VtaNum, $PrfId, $vtafec, $vtahor, $TerId, $TerNom, $TerDir, $TerTel, $terrzn, $VenId, $UsuNom, $CiuId, $ciunom, $vtaobs, $PrfCod);

                // Ejecutar la consulta preparada
                if ($stmt->execute()) {
                    $respuesta = array(
                        "mensaje" => "Todo bien"
                    );
                } else {
                    $respuesta = array(
                        "mensaje" => "Error general: " . $stmt->error
                    );
                }

                $stmt->close();
            } else {
                echo "Error al preparar la consulta: " . $con->error;
            }

            $consultaBusqueda = 
                "SELECT 
                    COALESCE(TRIM(ved.VtaId), '') AS vtaid, 
                    COALESCE(TRIM(ved.VtaDetId), '') AS vtadetid, 
                    COALESCE(TRIM(ved.ProId), '') AS proid, 
                    COALESCE(TRIM(pro.ProCod), '') AS procod, 
                    COALESCE(TRIM(pro.ProNom), 'DATO NO DISPONIBLE') AS pronom, 
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
            } 

            foreach ($resultados2 as $resultado) {

                $VtaId = mysqli_real_escape_string($con, $resultado['vtaid']);
                $VtaDetId = mysqli_real_escape_string($con, $resultado['vtadetid']);
                $ProId = mysqli_real_escape_string($con, $resultado['proid']);
                $ProCod = mysqli_real_escape_string($con, $resultado['procod']);
                $ProNom = mysqli_real_escape_string($con, $resultado['pronom']);
                $ProUbica = mysqli_real_escape_string($con, $resultado['proubica']);
                $ProPresentacion = mysqli_real_escape_string($con, $resultado['pround']);
                $ProCodBar = mysqli_real_escape_string($con, $resultado['probarcode']);
                $VtaCant = mysqli_real_escape_string($con, $resultado['vtacant']);

                $consulta2 = "INSERT INTO Productos
                    (VtaId, VtaDetId, ProId, ProCod, ProNom, ProUbica, ProPresentacion, ProCodBar, VtaCant) 
                    VALUES 
                    ('$VtaId', '$VtaDetId', '$ProId', '$ProCod', '$ProNom', '$ProUbica', '$ProPresentacion', '$ProCodBar', '$VtaCant');";
            
                $finalConsulta = $con->query($consulta2);
            }

        }

        $respuesta = array(
            "mensaje" => "Los datos fueron importados correctamente"
        );

        $con->close();

    } catch (PDOException $sqlException) {
        $respuesta = array(
            "mensaje" => "Error SQL: " . $sqlException->getMessage()
        );
    } catch (Exception $e) {
        $respuesta = array(
            "mensaje" => "Error general: " . $e->getMessage(),
            "extra1" => $consulta,
            "extra2" => $consulta2
        );
    }


    header('Content-Type: application/json');
    echo json_encode($respuesta);

    exit();
    

?>

