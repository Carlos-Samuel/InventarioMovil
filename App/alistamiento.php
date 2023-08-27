<?php
    session_start(); 	
    date_default_timezone_set('America/Bogota');

    if (!isset($_SESSION["cedula"]) || !isset($_SESSION["nombres"])) {
        header("Location: index.php");
        exit();
    }

    $permiso1 = "Admin";
    $permiso2 = "Alistamiento";

    if (!(strpos($_SESSION['permisos'], $permiso1) || strpos($_SESSION['permisos'], $permiso2))) {
        header("Location: dashboard.php");
        exit();
    }

    require_once 'controladores/Connection.php';

    if(isset($_GET['id'])) {
        try{
            $id_recibido = urldecode($_GET['id']);

            $con = Connection::getInstance()->getConnection();
            $quer = $con->query("select * from Facturas where vtaid = " . $id_recibido . ";");
    
            if ($quer->num_rows > 0) {
                $row = $quer->fetch_assoc();
        
                $prefijo = $row['PrfId'];
                $numDoc = $row['VtaNum'];
                $fecha = $row['vtafec'];
                $nombre = $row['TerNom'];
                $razon = $row['TerRaz'];
                $ciudad = $row['CiuNom'];
                $vendedor = $row['VenNom'];
                $hora = $row['vtahor'];
    
            } else {
                echo "No se encontro la factura.";
            }

            $quer2 = $con->query("select * from Productos where vtaid = " . $id_recibido . ";");

            $datosProductos = array();
    
            while ($columna = $quer2->fetch_assoc()) {
                $row['item'] = $columna['ProId'];
                $row['descripcion'] = $columna['ProNom'];
                $row['ubicacion'] = $columna['ProUbica'];
                $row['presentacion'] = $columna['ProPresentacion'];
                $row['cantidad'] = $columna['VtaCant'];
                $row['alistado'] = $columna['AlisCant'];
                $row['diferencia'] = $columna['VtaCant'] - $columna['AlisCant'];
    
                $datosProductos[] = $row;
            }

            $horaLocal = date('Y-m-d H:i:s');
            $sql = "UPDATE Facturas SET MomentoCarga = CONCAT(vtafec, ' ', vtahor), InicioAlistamiento = '$horaLocal' WHERE vtaid = $id_recibido AND InicioAlistamiento IS NULL";
            $resultado = $con->query($sql);

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "No se recibió ningún valor.";
    }

?>
<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <link rel="stylesheet" href="css_individuales/alistamiento.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css">
        <style>
            /* Ajusta el tamaño del contenedor de la notificación */
            .noty_body {
                font-size: 16px;
                padding: 15px; /* Ajusta el espacio alrededor del contenido de la notificación */
            }

            /* Ajusta el tamaño del texto de la notificación */
            .noty_body .noty_text {
                font-size: 18px;
            }
        </style>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>

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
                        <a href = "dashboard.php" ><button class="btn btn-primary primeButton" type="button">Volver</button></a>
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
                        <input id ="idFactura" type = "hidden" value = <?php echo $id_recibido?>>
                        <div class="d-grid gap-2">
                            <button id="botonPendiente" class="btn btn-warning primeButton" type="button">Pendiente</button>
                        </div>
                        <div class="d-grid gap-2">
                            <button id="botonCerrar" class="btn btn-success primeButton" type="button">Cerrar</button>
                        </div>
                        <div class="d-grid gap-2">
                            <button id="botonDevolver" class="btn btn-danger primeButton" type="button">Devolver</button>
                        </div>
                        <div class="d-grid gap-2">
                            <button id="botonForzado" class="btn btn-info primeButton" type="button">Cierre forzado</button>
                        </div>
                        <br>
                        <div class="buscador">
                            <input type="text" id="busqueda" placeholder="" style="max-width: 400px;">
                            <br>
                            <a class="btn btn-primary primeButton"  onclick="vaciarEspacioTexto(); busqueda();" role = "button">Vaciar</a>
                            <a class="btn btn-success primeBUtton"  onclick="RevisarBarCode();" role = "button">Revisar</a>
                        </div>
                        <br>
                        <div class="table">
                            <table id = "tablaAlistamiento">
                                <thead>
                                    <tr>
                                        <th>Ítem</th>
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
                                        <tr>
                                            <td data-label="Item"><?php echo $producto['item'] ?></td>
                                            <td data-label="Descripcion"><?php echo $producto['descripcion'] ?></td>
                                            <td data-label="Ubicacion"><?php echo $producto['ubicacion'] ?></td>
                                            <td data-label="Presentacion"><?php echo $producto['presentacion'] ?></td>
                                            <td data-label="Cantidad"><?php echo $producto['cantidad'] ?></td>
                                            <td data-label="Alistado" class="input-container"><input type="number" id="numero" name="numero" value="<?php echo $producto['alistado'] ?>"></td>
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
                </main>
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
                <p>¿Estás seguro de que desea forzar el proceso?</p>
                <input type="password" placeholder="Ingrese la clave de usuario">
                <div class="boton-container">
                    <button id="confirmarForzado" class="btn btn-success primeButton">Aceptar</button>
                    <button id="cancelarForzado" class="btn btn-danger primeButton">Cancelar</button>
                </div>
            </div>
        </div>
        <script src="scripts/alistamiento.js"></script>
        <!-- Incluye la biblioteca jQuery -->
        <script src="js/jquery-3.6.0.min.js"></script>
        <?php
            include('partes/foot.php')
        ?>  
    </body>
</html>