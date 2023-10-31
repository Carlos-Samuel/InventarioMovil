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

    if (!(strpos($_SESSION['permisos'], $permiso1) || strpos($_SESSION['permisos'], $permiso2))) {
        header("Location: dashboard.php");
        exit();
    }

    require_once 'controladores/Connection.php';

    if(isset($_GET['id'])) {
        try{
            $id_recibido = urldecode($_GET['id']);

            $con = Connection::getInstance()->getConnection();
            $quer = $con->query("            
                SELECT 
                    F.*, 
                    U.Nombres, 
                    U.Apellidos, 
                    CONCAT(
                        DATE_FORMAT(F.FinAlistamiento, '%Y-%m-%d'), 
                        ' ', 
                        TIME_FORMAT(F.FinAlistamiento, '%h:%i %p')
                    ) AS fecha_y_hora
                FROM 
                    Facturas AS F, Usuarios AS U 
                WHERE 
                    F.idAlistador = U.idUsuarios 
                    AND (F.facEstado = 3 OR F.facEstado = 4)
                    AND F.vtaid = " . $id_recibido . ";");
    
            if ($quer->num_rows > 0) {
                $row = $quer->fetch_assoc();

                utf8_encode_array($row);
        
                $prefijo = $row['PrfId'];
                $numDoc = $row['VtaNum'];
                $fecha_hora_venta = $row['vtafec'] . " " . $row['vtahor'];
                $nombre = $row['TerNom'];
                $razon = $row['TerRaz'];
                $ciudad = $row['CiuNom'];
                $vendedor = $row['VenNom'];
                $alistador = $row['Nombres'] . " " . $row['Apellidos'];
                $fecha_hora_alitado = $row['fecha_y_hora'];
                $observaciones = $row['facObservaciones'];
    
            } else {
                echo "No se encontro la factura.";
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
                $row['id'] = $columna['VtaDetId'];
                $row['item'] = $columna['ProId'];
                $row['ProCod'] = $columna['ProCod'];
                $row['ProCodBar'] = $columna['ProCodBar'];
                $row['descripcion'] = $columna['ProNom'];
                $row['ubicacion'] = $columna['ProUbica'];
                $row['presentacion'] = $columna['ProPresentacion'];
                $row['cantidad'] = $columna['VtaCant'];
                $row['alistado'] = $columna['AlisCant'];
                $row['verificado'] = $columna['VerCant'];

                utf8_encode_array($row);
    
                $datosProductos[] = $row;
            }

            $quer3 = $con->query("SELECT * FROM Embalajes;");

            $datosEmbalajes = array();
    
            while ($columna = $quer3->fetch_assoc()) {
                $row['idEmbalajes'] = $columna['idEmbalajes'];
                $row['Descripcion'] = $columna['Descripcion'];
    
                $datosEmbalajes[] = $row;
            }

            $horaLocal = date('Y-m-d H:i:s');
            $sql = "UPDATE Facturas SET InicioVerificacion = '$horaLocal' WHERE vtaid = $id_recibido AND InicioVerificacion IS NULL";
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
        <style>
            #modalConfirmarCerrar .modal-content {
                width: 70%; /* Puedes ajustar el valor según tus necesidades */
                max-width: 800px; /* Opcional: Puedes establecer un ancho máximo */
                margin: 0 auto; /* Centrar horizontalmente el modal */
            }

            #loader {
                display: none;
                border: 16px solid #f3f3f3;
                border-top: 16px solid #3498db;
                border-radius: 50%;
                width: 120px;
                height: 120px;
                animation: spin 2s linear infinite;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>

    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "Verificacion";
                include('partes/sidebar.php');
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <div id="loader"></div>
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
                                        <th>Fecha y Hora Venta</th>
                                        <th>Nombre Cliente</th>
                                        <th>Razón Social</th>
                                        <th>Ciudad</th>
                                        <th>Vendedor</th>
                                        <th>Alistador</th>
                                        <th>Fecha y Hora Alistado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-label="Prefijo"><?php echo $prefijo ?></td>
                                        <td data-label="Doc"><?php echo $numDoc ?></td>
                                        <td data-label="FechaHoraVenta"><?php echo $fecha_hora_venta ?></td>
                                        <td data-label="NombreCliente"><?php echo $nombre ?></td>
                                        <td data-label="RazonSocial"><?php echo $razon ?></td>
                                        <td data-label="Ciudad"><?php echo $ciudad ?></td>
                                        <td data-label="Vendedor"><?php echo $vendedor ?></td>
                                        <td data-label="Alistador"><?php echo $alistador ?></td>
                                        <td data-label="FechaHoraAlistado"><?php echo $fecha_hora_alitado ?></td>
                                    </tr>
                                    <!-- <tr>
                                        <td colspan="2"><strong>OBSERVACIONES</strong></td>
                                        <td colspan="7"><?php //echo $observaciones ?></td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <div class="buscador">
                            <input type="text" id="busqueda" placeholder="" style="max-width: 400px;">
                            <br>
                            <a class="btn btn-warning primeButton"  onclick="vaciarEspacioTexto();" role = "button">Vaciar</a>
                            <a class="btn btn-success primeButton"  onclick="busqueda();" role = "button">Buscar</a>
                            <a class="btn btn-primary primeButton"  onclick="leerCodigo();" role = "button">Leer Codigo</a>
                        </div>
                        <br>
                        <div class="table">
                            <table id = "tablaVerificacion">
                                <thead>
                                    <tr>
                                        <th>Codigo de Producto</th>
                                        <th>Codigo de Barras</th>
                                        <th>Descripción</th>
                                        <th>Presentación</th>
                                        <th class="input-container">Cantidad</th>
                                        <!-- <th>Procesar</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($datosProductos as $producto) {
                                            // if ($producto['alistado'] == 0 && $producto['cantidad'] != 0){
                                            //     $filaClase = '';
                                            // }else{
                                                $filaClase = ($producto['alistado'] == $producto['verificado']) ? 'verificacion-completo' : '';
                                            // }
                                            //echo $producto['ProCodBar'];
                                    ?>
                                        <tr class="<?php echo $filaClase; ?>" data-id="<?php echo $producto['id']; ?>">
                                        <td data-label="ProCod"><?php echo $producto['ProCod'] ?></td>
                                            <td data-label="ProCodBar"><?php echo $producto['ProCodBar'] ?></td> 
                                            <td data-label="Descripcion"><?php echo $producto['descripcion'] ?></td>
                                            <td data-label="Presentacion"><?php echo $producto['presentacion'] ?></td>
                                            <td data-label="Cantidad" class="input-container">
                                                <input type="number" min = 0 id="numero_<?php echo $producto['id'] ?>" name="numero_<?php echo $producto['id'] ?>" value = "<?php echo ($producto['alistado'] == $producto['verificado']) ? $producto['verificado'] : ''; ?>">
                                            </td>
                                            <!-- <td data-label="Procesar">
                                                <button class="btn btn-primary primeButton procesar-btn" data-item="<?php echo $producto['item'] ?>">Procesar</button>
                                            </td>     -->
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
                    <input id ="cedulaUsuario" type = "hidden" value = <?php echo $_SESSION["cedula"]?>>
                    <div class="d-grid gap-2">
                        <button id="botonPendiente" class="btn btn-warning primeButton" type="button">Pendiente</button>
                    </div>
                    <div class="d-grid gap-2">
                        <button id="botonCerrar" class="btn btn-success primeButton" type="button">Cerrar</button>
                    </div>
                    <br>
                </main>
            </div>
        </div>
        <div id="modalConfirmarCerrar" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>¿Estás seguro de que desea cerrar el proceso?</p>
                <br>
                <table id = "tablaEmbalaje">
                    <thead>
                        <tr>
                            <th>Embalaje</th>
                            <th class="input-container">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($datosEmbalajes as $producto) {
                        ?>
                            <tr data-id="<?php echo $producto['id']; ?>">
                                <td data-label="Descripcion"><?php echo $producto['Descripcion'] ?></td>
                                <td data-label="Cantidad" class="input-container">
                                    <input type="number" id="numero_<?php echo $producto['idEmbalajes'] ?>" name="numero_<?php echo $producto['idEmbalajes'] ?>">
                                </td>
                            </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
                <br>
                <h6>Observaciones</h6>
                <br>
                <input tipe = "text" id = "observacionesVer" name = "observacionesVer" >
                <br>
                <div class="boton-container">
                    <button id="confirmarCerrar" class="btn btn-success primeButton">Aceptar</button>
                    <button id="cancelarCerrar" class="btn btn-danger primeButton">Cancelar</button>
                </div>
            </div>
        </div>
        <script src="scripts/verificacion.js"></script>
        <!-- Incluye la biblioteca jQuery -->
        <script src="js/jquery-3.6.0.min.js"></script>
        <?php
            include('partes/foot.php')
        ?>  
    </body>
</html>