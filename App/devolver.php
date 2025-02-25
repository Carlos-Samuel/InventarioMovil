<?php
    session_start(); 	
    date_default_timezone_set('America/Bogota');
    require_once 'controladores/Connection.php';
    require_once 'controladores/Connection2.php';

    if (!isset($_SESSION["cedula"]) || !isset($_SESSION["nombres"])) {
        header("Location: index.php");
        exit();
    }

    $con = Connection::getInstance()->getConnection();
    $con2 = Connection2::getInstance2()->getConnection2();

    $permiso1 = "Admin";

    if (!(strpos($_SESSION['permisos'], $permiso1))) {
        header("Location: dashboard.php");
        exit();
    }

    $id_recibido = $_GET['id'];

    $mensaje = [];

    $valoresColumnasFacturas = [
        ['VtaNum', 'VtaNum'],
        ['PrfId', 'PrfId'],
        ['vtafec', 'vtafec'],
        ['vtahor', 'vtahor'],
        ['TerId', 'TerId'],
        ['TerNom', 'TerNom'],
        ['TerDir', 'TerDir'],
        ['TerTel', 'TerTel'],
        ['terrzn', 'TerRaz'],
        ['VenId', 'VenId'],
        ['UsuNom', 'VenNom'],
        ['CiuId', 'CiuId'],
        ['ciunom', 'CiuNom'],
        ['vtaobs', 'facObservaciones']
    ];

    $valoresColumnasProductos = [
        ['proid', 'ProId'],
        ['pronom', 'ProNom'],
        ['proubica', 'ProUbica'],
        ['pround', 'ProPresentacion'],
        ['probarcode', 'ProCodBar'],
        ['vtacant', 'VtaCant']
    ];

    $cambios = [];
    $cambiosProductos = [];

    $consultas_actualizacion = [];

    $sql_general = 
        "UPDATE 
            `XXXX` 
        SET 
            `facEstado`=1,`idAlistador`=NULL,
            `idVerificador`=NULL,
            `idEntregador`=NULL,`MomentoCarga`=NOW(),
            `InicioAlistamiento`=NULL,`FinAlistamiento`=NULL,
            `InicioVerificacion`=NULL,`FinVerificacion`=NULL,
            `InicioEntrega`=NULL,`FinEntrega`=NULL,`Forzado`=0,
            `Forzador`='0',`ObservacionesFor`='0',`Justificacion`=NULL,
            `Embalaje`='0',`ObservacionesVer`='0'
        WHERE 
            vtaid = $id_recibido";

    $consultas_actualizacion[] = $sql_general;

    $consultaEstadoFactura = 
        "SELECT 
            VtaNum AS numero,
            PrfCod AS prefijo,
            vtaid_res AS vtaid_res
        FROM
            facturas
        WHERE 
            VtaId = $id_recibido";

    $quer = $con->query($consultaEstadoFactura);

    $fila = $quer->fetch_assoc();
    $numero = $fila['numero'];
    $prefijo = $fila['prefijo'];
    $id_respaldo = $fila['vtaid_res'];

?>
<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <link rel="stylesheet" href="css_individuales/alistamiento.css">

    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "Devolver";
                include('partes/sidebar.php');
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <br>
                    <h3>Factura <?php echo $prefijo . " " . $numero; ?></h3>
                    <?php
                        $consultaFactura1 = 
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
                                vtaid = $id_respaldo
                            ;";

                        $quer = $con2->query($consultaFactura1);

                        $resultados1 = array();

                        if ($quer->num_rows > 0) {
                            while ($fila = $quer->fetch_assoc()) {
                                $resultados1[] = $fila;
                            }
                        } else {
                            $mensaje[] = "Factura borrada en Sistema original";
                        }

                        $consultaFactura2 = 
                            "SELECT 
                                *
                            FROM
                                Facturas
                            WHERE 
                                vtaid = $id_recibido
                            ;";

                        $quer = $con->query($consultaFactura2);

                        $resultados2 = array();

                        if ($quer->num_rows > 0) {
                            while ($fila = $quer->fetch_assoc()) {
                                $resultados2[] = $fila;
                            }
                        } else {
                            $mensaje[] = "Factura borrada en Sistema actual";
                        }

                        foreach($valoresColumnasFacturas as $vcf){
                            
                            if ($resultados1[0][$vcf[0]] != $resultados2[0][$vcf[1]]){
                                $cambio = "El valor: " . $vcf[1] . " cambio de: " . trim($resultados2[0][$vcf[1]]) . " a: " . trim($resultados1[0][$vcf[0]]);
                                $cambios[] = $cambio;
                                $sql = "UPDATE Facturas SET " . $vcf[1] . " = RTRIM('".$resultados1[0][$vcf[0]]."') WHERE vtaid = $id_recibido ;";
                                $consultas_actualizacion[] = $sql;
                            }

                        }
                        
                        echo "<br>";
                        echo "<h5>Cambios en la Factura</h5>";

                        foreach($cambios as $cambio){
                            echo $cambio;
                            echo "<br>";
                        }

                        $consultaElementos1 = 
                            "SELECT 
                                COALESCE(TRIM(ved.VtaId), '') AS vtaid, 
                                COALESCE(TRIM(ved.VtaDetId), '') AS vtadetid, 
                                COALESCE(TRIM(ved.ProId), '') AS proid, 
                                COALESCE(TRIM(ved.ProNom), 'DATO NO DISPONIBLE') AS pronom, 
                                COALESCE(TRIM(NULLIF(pro.ProUbica, '')), 'DATO NO DISPONIBLE') AS proubica, 
                                COALESCE(TRIM(NULLIF(pro.ProUnd, '')), 'DATO NO DISPONIBLE') AS pround, 
                                TRIM(NULLIF(pro.ProCodBar, '')) AS probarcode, 
                                COALESCE(TRIM(ved.VtaCant), 0) AS vtacant 
                            FROM
                                ventasdet AS ved
                            LEFT JOIN productos AS pro ON pro.ProId = ved.ProId
                            WHERE 
                                VtaId = $id_respaldo";

                        $quer = $con2->query($consultaElementos1);

                        $resultadosElementos1 = array();

                        if ($quer->num_rows > 0) {
                            while ($fila = $quer->fetch_assoc()) {
                                $resultadosElementos1[] = $fila;
                            }
                        } else {
                            $mensaje[] = "Elementos no encontrados 1";
                        }

                        $consultaElementos2 = 
                            "SELECT 
                                *
                            FROM
                                Productos AS pro
                            WHERE 
                                pro.VtaId = $id_recibido";

                        $quer = $con->query($consultaElementos2);

                        $resultadosElementos2 = array();

                        if ($quer->num_rows > 0) {
                            while ($fila = $quer->fetch_assoc()) {
                                $resultadosElementos2[] = $fila;
                            }
                        } else {
                            $mensaje[] = "Elementos no encontrados 2";
                        }

                        foreach($resultadosElementos1 as $rE1){

                            foreach ($resultadosElementos2 as $rE2) {
                                if ($rE1['vtadetid'] == $rE2['VtaDetId_res']){
                                    foreach($valoresColumnasProductos as $vcp){
                                        if ($rE1[$vcp[0]] != $rE2[$vcp[1]]){
                                            $cambio = "El valor: " . $vcp[1] . " cambio de: " . trim($rE2[$vcp[1]]) . " a: " . trim($rE1[$vcp[0]]) . " en el producto " . trim($rE1['pronom']) . " con vtadetid " . trim($rE1['vtadetid']);
                                            $cambiosProductos[] = $cambio;

                                            $sql = "UPDATE Productos SET " . $vcp[1] . " = RTRIM('".$rE1[$vcp[0]]."') WHERE VtaDetId = " . $rE2['VtaDetId'] . " ;";
                                            $consultas_actualizacion[] = $sql;
                                        }
                                    }
                                    // Eliminar elementos de los arreglos originales
                                    unset($resultadosElementos1[array_search($rE1, $resultadosElementos1)]);
                                    unset($resultadosElementos2[array_search($rE2, $resultadosElementos2)]);
                                    break;
                                }

                            }

                        }
                        
                        echo "<br>";
                        echo "<h5>Cambios en los Productos</h5>";

                        foreach($cambiosProductos as $cambio){
                            echo $cambio;
                            echo "<br>";
                        }

                        echo "<br>";
                        echo "<h5>Productos agregados</h5>";

                        foreach($resultadosElementos1 as $resEle1){
                            echo "Se agrego el elemento " . $resEle1['pronom'] ;
                            echo "<br>";
                            $elementosAgregar = 
                                "INSERT INTO Productos
                                    (VtaIdasdasd, VtaDetId, ProId, ProNom, ProUbica, ProPresentacion, ProCodBar, VtaCant) 
                                VALUES 
                                    ('{$id_recibido}','{$resEle1['vtadetid']}','{$resEle1['proid']}','{$resEle1['pronom']}','{$resEle1['proubica']}','{$resEle1['pround']}','{$resEle1['probarcode']}','{$resEle1['vtacant']}')
                                ;";
                                
                            echo $elementosAgregar;

                            $consultas_actualizacion[] = $elementosAgregar;
                        }

                        echo "<br>";
                        echo "<h5>Prouctos eliminados</h5>";

                        foreach($resultadosElementos2 as $resEle2){
                            echo "Se elimino el elemento " . $resEle2['ProNom'] ;
                            echo "<br>";
                            $elementosBorrar = "DELETE FROM Productos WHERE VtaDetId = " . $resEle2['VtaDetId'] . ";";
                            $consultas_actualizacion[] = $elementosBorrar;
                        }

                        if(isset($_GET['controlador'])){
                            
                            try{
            
                                foreach($consultas_actualizacion as $consulta_ejecutar){
                                    $con->query($consulta_ejecutar);
                                }
            
                                echo '<script>window.location.href="lista_devolver.php";</script>';
                                exit();
            
                            }catch (Exception $e) {
                                echo "<br>";
                                echo "<h1>Errores al actualizar</h1>";
                                echo 'Error en la consulta SQL: ' . $e->getMessage();
                            }
            
            
                        }
                    
                    ?>
                    <br>
                    <br>
                    <div class="d-grid gap-1">
                        <?php
                            echo '<a href= "devolver.php?id=' . $id_recibido . '&controlador=1" class="btn btn-success primeButton">Actualizar Factura</a>';
                        ?>
                    </div>
                </main>
            </div>
        </div>


        
        <script src="scripts/devolver.js"></script>
        <!-- Incluye la biblioteca jQuery -->
        <script src="js/jquery-3.6.0.min.js"></script>
        <!-- <script src="path/to/honeywell-sdk.js"></script> -->

        <?php
            include('partes/foot.php')
        ?>  
    </body>
</html>