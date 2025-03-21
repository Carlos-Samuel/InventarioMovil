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
                $vtaid_res = $row['vtaid_res'];

                if ($idAlistador != $row['idAlistador']){
                    $controladorAlistador = true; 
                }
    
            } else {
                $controladorAbsoluto = false;
                echo "No se encontro la factura.";
            }

            if ($controladorAlistador){
                limpiarAlistamiento($con, $id_recibido);
                $horaLocal = date('Y-m-d H:i:s');
                $sql = "UPDATE Facturas SET MomentoCarga = CONCAT(vtafec, ' ', vtahor), InicioAlistamiento = '$horaLocal', idAlistador = $idAlistador WHERE vtaid = $id_recibido AND InicioAlistamiento IS NULL";
                $resultado = $con->query($sql);
            }

            $quer2 = $con->query(
                "SELECT * 
                FROM Productos 
                WHERE vtaid =  $id_recibido 
                ORDER BY
                CASE
                  WHEN ProUbica = 'DATO NO DISPONIBLE' THEN 1
                  ELSE 0
                END,
                ProUbica ASC;");

            $datosProductos = array();
    
            while ($columna = $quer2->fetch_assoc()) {

                $row2 = [];

                $row2['id'] = $columna['VtaDetId'];
                $row2['item'] = $columna['ProId'];
                $row2['ProCod'] = $columna['ProCod'];
                $row2['ProCodBar'] = $columna['ProCodBar'];
                $row2['descripcion'] = $columna['ProNom'];
                $row2['ubicacion'] = $columna['ProUbica'];
                $row2['presentacion'] = $columna['ProPresentacion'];
                $row2['cantidad'] = $columna['VtaCant'];
                $row2['alistado'] = $columna['AlisCant'];
                $row2['diferencia'] = $columna['VtaCant'] - $columna['AlisCant'];

                utf8_encode_array($row2);
    
                $datosProductos[] = $row2;
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
                $activado = "Alistamiento";
                include('partes/sidebar.php');
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <div id = "contenidoMovil" style="display: none;">
                        <br>
                        <!--
                            <a href = "dashboard.php" ><button class="btn btn-primary primeButton" type="button">Volver</button></a>
                        -->
                        <br>
                        <br>
                    </div>
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
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <div class="buscador">
                            <input type="text" id="busqueda" placeholder="" style="max-width: 400px;">
                            <br>
                            <a class="btn btn-primary primeButton"  onclick="vaciarEspacioTexto();" role = "button">Vaciar</a>
                            <a class="btn btn-success primeBUtton"  onclick="busqueda();" role = "button">Buscar</a>
                        </div>
                        <br>
                        <div class="table">
                            <table id = "tablaAlistamiento">
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
                                        <th>Comprobar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($datosProductos as $producto) {
                                            if ($producto['alistado'] == 0 && $producto['cantidad'] != 0){
                                                $filaClase = '';
                                            }else{
                                                $filaClase = ($producto['alistado'] == $producto['cantidad']) ? 'alistamiento-completo' : 'alistamiento-incompleto';
                                            }
                                    ?>
                                        <tr class="<?php echo $filaClase; ?>" data-id="<?php echo $producto['id']; ?>">
                                            <td data-label="ProCod"><?php echo $producto['ProCod'] ?></td>
                                            <td data-label="ProCodBar"><?php echo $producto['ProCodBar'] ?></td>
                                            <td data-label="Descripcion"><?php echo ($producto['descripcion']) ?></td>
                                            <td data-label="Ubicacion"><?php echo ($producto['ubicacion']) ?></td>
                                            <td data-label="Presentacion"><?php echo ($producto['presentacion']) ?></td>
                                            <td data-label="Cantidad"><?php echo $producto['cantidad'] ?></td>
                                            <td data-label="Alistado" class="input-container">
                                                <input type="number" min = 0 id="numero_<?php echo $producto['id'] ?>" name="numero_<?php echo $producto['id'] ?>" value="<?php echo $producto['alistado'] ?>">
                                            </td>
                                            <td data-label="Diferencia"><?php echo $producto['diferencia'] ?></td>
                                            <td data-label="Procesar">
                                                <button class="btn btn-primary primeButton procesar-btn" data-item="<?php echo $producto['item'] ?>" data-codBar="<?php echo $producto['ProCodBar'] ?>">Comprobar</button>
                                            </td>                     
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
                        <button id="botonPendiente" class="btn btn-warning primeButton" type="button">Pendiente</button>
                    </div>
                    <div class="d-grid gap-2">
                        <button id="botonCerrar" class="btn btn-success primeButton" type="button">Cerrar</button>
                    </div>
                    <?php
                    if (isset($vtaid_res)){
                    ?>
                    <div class="d-grid gap-2">
                        <button id="botonDevolver" class="btn btn-danger primeButton" type="button">Devolver</button>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="d-grid gap-2">
                        <button id="botonForzado" class="btn btn-info primeButton" type="button">Cierre forzado</button>
                    </div>
                    <br>
                </main>
            </div>
        </div>
        <div id="modalConfirmarPendiente" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>Ingrese la razón para dejar pendiente la factura</p>
                <input id ="razon" type="text" placeholder="Ingrese la razon">
                <div class="boton-container">
                    <button id="confirmarPendiente" class="btn btn-success primeButton">Aceptar</button>
                    <button id="cancelarPendiente" class="btn btn-danger primeButton">Cancelar</button>
                </div>
            </div>
        </div>
        <div id="modalConfirmarCerrar" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>¿Estás seguro de que desea cerrar el proceso?</p>
                <div class="boton-container">
                    <button id="confirmarCerrar" class="btn btn-success primeButton">Aceptar</button>
                    <button id="cancelarCerrar" class="btn btn-danger primeButton">Cancelar</button>
                </div>
            </div>
        </div>
        <div id="modalConfirmarDevolver" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>¿Estás seguro de que desea devolver la factura?</p>
                <div class="boton-container">
                    <button id="confirmarDevolver" class="btn btn-success primeButton">Aceptar</button>
                    <button id="cancelarDevolver" class="btn btn-danger primeButton">Cancelar</button>
                </div>
            </div>
        </div>
        <div id="modalConfirmarForzado" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>Ingrese las credenciales de un usuario con permisos de forzado</p>
                <input id ="cedulaUsuario" type="text" placeholder="Ingrese la cedula de usuario">
                <input id ="passwordUsuario" type="password" placeholder="Ingrese la clave de usuario">
                <p>E ingrese una justificación</p>
                <input id ="justificacion" type="text" placeholder="Justificacion">
                <div class="boton-container">
                    <button id="confirmarForzado" class="btn btn-success primeButton">Aceptar</button>
                    <button id="cancelarForzado" class="btn btn-danger primeButton">Cancelar</button>
                </div>
            </div>
        </div>
        <script src="scripts/alistamiento.js"></script>
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
        header("Location: lista_alistamiento.php");
        exit;
    }
?>