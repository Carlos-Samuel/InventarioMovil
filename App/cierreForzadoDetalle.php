<?php
    session_start(); 	
    date_default_timezone_set('America/Bogota');

    include 'controladores/funciones.php';

    if (!isset($_SESSION["cedula"]) || !isset($_SESSION["nombres"])) {
        header("Location: index.php");
        exit();
    }

    $permiso1 = "Admin";
    $permiso2 = "Alistamiento";

    $controladorAbsoluto = true;
    $controladorAlistador = false;

    if (!(strpos($_SESSION['permisos'], $permiso1) || strpos($_SESSION['permisos'], $permiso2))) {
        header("Location: dashboard.php");
        exit();
    }

    require_once 'controladores/Connection.php';
    require_once 'controladores/limpiadores.php';

    $idAlistador = $_SESSION["idUsuarios"];

    if(isset($_GET['id'])) {
        try{
            $id_recibido = urldecode($_GET['id']);

            $con = Connection::getInstance()->getConnection();
            $quer = $con->query("SELECT * FROM Facturas WHERE vtaid = " . $id_recibido . ";");
    
            if ($quer->num_rows > 0) {
                $row = $quer->fetch_assoc();

                utf8_encode_array($row);
        
                $prefijo = $row['PrfCod'];
                $numDoc = $row['VtaNum'];
                $fecha = $row['vtafec'];
                $nombre = $row['TerNom'];
                $razon = $row['TerRaz'];
                $ciudad = $row['CiuNom'];
                $vendedor = $row['VenNom'];
                $hora = $row['vtahor'];
                $justificacion = $row['ObservacionesFor'];
    
            } else {
                $controladorAbsoluto = false;
                echo "No se encontro la factura.";
            }

            $quer2 = $con->query(
                "SELECT * 
                FROM Productos 
                WHERE vtaid =  $id_recibido 
                AND VtaCant != AlisCant
                ORDER BY
                CASE
                  WHEN ProUbica = 'DATO NO DISPONIBLE' THEN 1
                  ELSE 0
                END,
                ProUbica ASC;");

            $datosProductos = array();
    
            while ($columna = $quer2->fetch_assoc()) {
                $row['id'] = $columna['VtaDetId'];
                $row['item'] = $columna['ProId'];
                $row['ProCod'] = $columna['ProCod'];
                $row['ProCodBar'] = $columna['ProCodBar'];
                $row['descripcion'] = $columna['ProNom'];
                $row['ubicacion'] = $columna['ProUbica'];
                $row['presentacion'] = $columna['ProPresentacion'];
                $row['cantidad'] = $columna['VtaCant'];
                $row['alistado'] = $columna['AlisCant'];
                $row['diferencia'] = $columna['VtaCant'] - $columna['AlisCant'];

                utf8_encode_array($row);
    
                $datosProductos[] = $row;
            }

            require_once 'controladores/ordenarProductos.php';

            usort($datosProductos, 'compararUbicaciones');

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        $controladorAbsoluto = false;
        echo "No se recibió ningún valor.";
    }
    if ($controladorAbsoluto){
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
                $activado = "CierresForzadosControl";
                include('partes/sidebar.php');
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <div class="col-sm-12">
                        <div class="table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Prefijo</th>
                                        <th># DOC</th>
                                        <th>Fecha</th>
                                        <th>Nombre Cliente</th>
                                        <th>Razón Social</th>
                                        <th>Ciudad</th>
                                        <th>Vendedor</th>
                                        <th>Hora DOC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-label="Prefijo"><?php echo $prefijo ?></td>
                                        <td data-label="Doc"><?php echo $numDoc ?></td>
                                        <td data-label="Fecha"><?php echo $fecha ?></td>
                                        <td data-label="NombreCliente"><?php echo $nombre ?></td>
                                        <td data-label="RazonSocial"><?php echo $razon ?></td>
                                        <td data-label="Ciudad"><?php echo $ciudad ?></td>
                                        <td data-label="Vendedor"><?php echo $vendedor ?></td>
                                        <td data-label="HoraDoc"><?php echo $hora ?></td>
                                    </tr>
                                    <tr>
                                        <td data-label="Justificacion" colspan="8"><b>JUSTIFICACIÓN:</b> <?php echo $justificacion ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <div class="table">
                            <table id = "tablaCierresForzados">
                                <thead>
                                    <tr>
                                        <th>Codigo de Producto</th>
                                        <th>Codigo de Barras</th>
                                        <th>Descripción</th>
                                        <th>Ubicación</th>
                                        <th>Presentación</th>
                                        <th>Cantidad</th>
                                        <th class="input-container">Alistado</th>
                                        <th>Diferencia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($datosProductos as $producto) {
                                    ?>
                                        <tr data-id="<?php echo $producto['id']; ?>">
                                            <td data-label="ProCod"><?php echo $producto['ProCod'] ?></td>
                                            <td data-label="ProCodBar"><?php echo $producto['ProCodBar'] ?></td>
                                            <td data-label="Descripcion"><?php echo ($producto['descripcion']) ?></td>
                                            <td data-label="Ubicacion"><?php echo ($producto['ubicacion']) ?></td>
                                            <td data-label="Presentacion"><?php echo ($producto['presentacion']) ?></td>
                                            <td data-label="Cantidad"><?php echo $producto['cantidad'] ?></td>
                                            <td data-label="Alistado"><?php echo $producto['alistado'] ?></td>
                                            <td data-label="Diferencia"><?php echo $producto['diferencia'] ?></td>             
                                       </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                            <br>
                        </div>
                    </div>
                    <input id ="idFactura" type = "hidden" value = <?php echo $id_recibido?>>
                    <div class="d-grid gap-2">
                        <button id="botonEnviarAlistamiento" class="btn btn-success primeButton" type="button">Enviar Alistamiento</button>
                    </div>
                    <div class="d-grid gap-2">
                        <button id="botonCerrar" class="btn btn-danger primeButton" type="button">Cerrar</button>
                    </div>
                    <br>
                </main>
            </div>
        </div>

        <div id="modalConfirmarEnviarAlistamiento" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>¿Estás seguro de que desea enviar esta factura con estos productos a alistamiento?</p>
                <div class="boton-container">
                    <button id="confirmarEnviarAlistamiento" class="btn btn-success primeButton">Aceptar</button>
                    <button id="cancelarEnviarAlistamiento" class="btn btn-danger primeButton">Cancelar</button>
                </div>
            </div>
        </div>
        <div id="modalConfirmarCerrar" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>¿Estás seguro de que desea cerrar esta factura?</p>
                <div class="boton-container">
                    <button id="confirmarCerrar" class="btn btn-success primeButton">Aceptar</button>
                    <button id="cancelarCerrar" class="btn btn-danger primeButton">Cancelar</button>
                </div>
            </div>
        </div>
        <script src="scripts/cierresForzados.js"></script>
        <!-- Incluye la biblioteca jQuery -->
        <script src="js/jquery-3.6.0.min.js"></script>
        <!-- <script src="path/to/honeywell-sdk.js"></script> -->

        <?php
            include('partes/foot.php')
        ?>  
    </body>
</html>
<?php 
    }else{
        header("Location: cierresForzadosControl.php");
        exit;
    }
?>