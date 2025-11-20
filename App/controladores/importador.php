<?php
    require_once 'Connection.php';
    require_once 'Connection2.php';
    require_once 'filtroEmpresas.php';
    require_once 'importarDBF.php';
    require_once realpath(__DIR__ . '/../vendor/autoload.php');


    set_time_limit(1400); 
    use XBase\TableReader;

    function formatearFechaYMDaYSlash($fecha) {
        if (preg_match('/^\d{8}$/', $fecha)) {
            return substr($fecha, 0, 4) . '/' . substr($fecha, 4, 2) . '/' . substr($fecha, 6, 2);
        }
        return null;
    }

    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['fecha']) && !empty($data['fecha'])) {
        $fecha_minima = $data['fecha'];
    } else {
        $fecha_minima = '2024-09-30';
    }

            

    $con = Connection::getInstance()->getConnection();
    $quer = $con->query("SELECT COALESCE(MAX(vtaid_res), 0) AS max_vtaid FROM Facturas");

    if ($quer->num_rows > 0) {
        $row = $quer->fetch_assoc();

        $maxVtaid = $row['max_vtaid'];
    } else {
        $maxVtaid = 0;
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
            LEFT JOIN ciudad AS ci ON ci.ciuid = ter.CiuId
            LEFT JOIN Prefijo AS pr ON pr.PrfId = ve.PrfId
            WHERE 
                ve.vtaid > $maxVtaid
                AND ve.vtafec >= '".$fecha_minima."'  
            ;";

        $quer = $con2->query($consultaBusqueda1);

        $resultados = array();

        if ($quer->num_rows > 0) {
            while ($fila = $quer->fetch_assoc()) {
                $resultados[] = $fila;
            }

            
            // Construir cadena de vtaid de esta importación
            $vtaids = array_column($resultados, 'vtaid');
            $cadenaVtaids = implode(',', $vtaids);
            $cantidadFacturas = count($vtaids);

            // Registrar inicio de importación
            $stmtImp = $con->prepare(
                "INSERT INTO importaciones_dbf (fecha_inicio, vtaids_concatenados, cantidad_facturas)
                VALUES (NOW(), ?, ?)"
            );
            if (!$stmtImp) {
                throw new RuntimeException("Error al preparar insert de importación: " . $con->error);
            }

            $stmtImp->bind_param("si", $cadenaVtaids, $cantidadFacturas);

            if (!$stmtImp->execute()) {
                throw new RuntimeException("Error al ejecutar insert de importación: " . $stmtImp->error);
            }

            $importacionId = $stmtImp->insert_id;
            $stmtImp->close();

            sleep(10);

            $indexado = leerProfecvncIndexado($importacionId);
            

            foreach ($resultados as $resultado) {

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
                
                $indexFactura = $VtaNum . '|' . $PrfCod . '|';

                // Definir la consulta preparada
                $consulta = "INSERT INTO Facturas (vtaid_res, VtaNum, PrfId, vtafec, vtahor, TerId, TerNom, TerDir, TerTel, TerRaz, VenId, VenNom, CiuId, CiuNom, facObservaciones, facEstado, MomentoCarga, PrfCod) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, TIME(NOW()), ?)";

                // Preparar la consulta
                if ($stmt = $con->prepare($consulta)) {
                    // Vincular parámetros y tipos de datos
                    $stmt->bind_param("iissssssssssssss",$vtaid, $VtaNum, $PrfId, $vtafec, $vtahor, $TerId, $TerNom, $TerDir, $TerTel, $terrzn, $VenId, $UsuNom, $CiuId, $ciunom, $vtaobs, $PrfCod);

                    // Ejecutar la consulta preparada
                    if ($stmt->execute()) {

                        $ultimoId = $con->insert_id;

                        $consultaBusqueda = 
                        "SELECT 
                            COALESCE(TRIM(ved.VtaId), '') AS vtaid, 
                            COALESCE(TRIM(ved.VtaDetId), '') AS vtadetid, 
                            COALESCE(TRIM(ved.ProId), '') AS proid, 
                            COALESCE(TRIM(pro.ProCod), '') AS procod, 
                            --COALESCE(TRIM(pro.ProNom), 'DATO NO DISPONIBLE') AS pronom, 
                            CASE
                                WHEN TRIM(pro.ProNom) = '.' THEN COALESCE(ved.ProNom, 'DATO NO DISPONIBLE')
                                WHEN TRIM(pro.ProNom) = '..' THEN COALESCE(ved.ProNom, 'DATO NO DISPONIBLE')
                                WHEN TRIM(pro.ProNom) LIKE '.%.' THEN COALESCE(ved.ProNom, 'DATO NO DISPONIBLE')
                                ELSE COALESCE(TRIM(pro.ProNom), 'DATO NO DISPONIBLE')
                            END AS pronom,
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
            
                            $VtaId = $ultimoId;
                            $VtaDetId = mysqli_real_escape_string($con, $resultado['vtadetid']);
                            $ProId = mysqli_real_escape_string($con, $resultado['proid']);
                            $ProCod = mysqli_real_escape_string($con, $resultado['procod']);
                            $ProNom = mysqli_real_escape_string($con, $resultado['pronom']);
                            $ProUbica = mysqli_real_escape_string($con, $resultado['proubica']);
                            $ProPresentacion = mysqli_real_escape_string($con, $resultado['pround']);
                            $ProCodBar = mysqli_real_escape_string($con, $resultado['probarcode']);
                            $VtaCant = mysqli_real_escape_string($con, $resultado['vtacant']);


                            $claveBusqueda = $indexFactura . $ProCod;

                            if (isset($indexado[$claveBusqueda])) {
    
                                $registros = $indexado[$claveBusqueda];
                                foreach ($registros as $i => $registro) {

                                    if ($registro['vnccan'] != "0" && (int)$VtaCant > 0){

                                        $fechavenc = $registro['vncfec'];
                                        $lotevenc = $registro['vnclot'];
                                        $cantidad = $registro['vnccan'];

                                        if((int)$VtaCant <= (int)$cantidad){
                                            $consulta2 = "INSERT INTO Productos
                                            (VtaId, VtaDetId_res, ProId, ProCod, ProNom, ProUbica, ProPresentacion, ProCodBar, VtaCant, vncfec, vnclot) 
                                            VALUES 
                                            ('$VtaId', '$VtaDetId', '$ProId', '$ProCod', '$ProNom', '$ProUbica', '$ProPresentacion', '$ProCodBar', '$VtaCant', '$fechavenc', '$lotevenc');";
                                    
                                            $finalConsulta = $con->query($consulta2);

                                            $registro['vnccan'] = strval((int)$cantidad - (int)$VtaCant);
                                            $indexado[$claveBusqueda][$i]['vnccan'] = strval((int)$cantidad - (int)$VtaCant);

                                            $VtaCant = "0";

                                        }else{

                                            $consulta2 = "INSERT INTO Productos
                                            (VtaId, VtaDetId_res, ProId, ProCod, ProNom, ProUbica, ProPresentacion, ProCodBar, VtaCant, vncfec, vnclot) 
                                            VALUES 
                                            ('$VtaId', '$VtaDetId', '$ProId', '$ProCod', '$ProNom', '$ProUbica', '$ProPresentacion', '$ProCodBar', '$cantidad', '$fechavenc', '$lotevenc');";
                                    
                                            $finalConsulta = $con->query($consulta2);

                                            $registro['vnccan'] = "0";
                                            $indexado[$claveBusqueda][$i]['vnccan'] = "0";


                                            $VtaCant = strval((int)$VtaCant - (int)$cantidad);
                                        }

                                    }

                                }

                            } else {

                                $consulta2 = "INSERT INTO Productos
                                (VtaId, VtaDetId_res, ProId, ProCod, ProNom, ProUbica, ProPresentacion, ProCodBar, VtaCant) 
                                VALUES 
                                ('$VtaId', '$VtaDetId', '$ProId', '$ProCod', '$ProNom', '$ProUbica', '$ProPresentacion', '$ProCodBar', '$VtaCant');";
                        
                                $finalConsulta = $con->query($consulta2);

                            }

                        }
                        
                    } else {
                        $respuesta = array(
                            "mensaje" => "Error general: " . $stmt->error
                        );
                    }

                    $stmt->close();
                } else {
                    echo "Error al preparar la consulta: " . $con->error;
                }

            }

            if (isset($importacionId)) {
                $stmtFin = $con->prepare(
                    "UPDATE importaciones_dbf SET fecha_fin = NOW() WHERE id = ?"
                );
                if ($stmtFin) {
                    $stmtFin->bind_param("i", $importacionId);
                    $stmtFin->execute();
                    $stmtFin->close();
                }
            }

            $respuesta = array(
                "mensaje" => "Los datos fueron importados correctamente"
            );

            $con->close();


        } else {
            $respuesta = array(
                "mensaje" => "Ninguna factura encontrada"
            );

        }

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

