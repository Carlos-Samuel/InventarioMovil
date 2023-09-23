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
            $quer = $con->query(
                "SELECT 
                    F.*, 
                    A.Nombres AS NombresAlistador, 
                    A.Apellidos AS ApellidosAlistador, 
                    V.Nombres AS NombresVerificador, 
                    V.Apellidos AS ApellidosVerificador, 
                    CONCAT(
                        DATE_FORMAT(F.FinAlistamiento, '%Y-%m-%d'), 
                        ' ', 
                        TIME_FORMAT(F.FinAlistamiento, '%h:%i %p')
                    ) AS fecha_y_hora_alistado, 
                    CONCAT(
                        DATE_FORMAT(F.FinVerificacion, '%Y-%m-%d'), 
                        ' ', 
                        TIME_FORMAT(F.FinVerificacion, '%h:%i %p')
                    ) AS fecha_y_hora_verificado
                FROM 
                    Facturas AS F, Usuarios AS A, Usuarios AS V
                WHERE 
                    F.idAlistador = A.idUsuarios 
                    AND F.idVerificador = V.idUsuarios 
                    AND (F.facEstado = 5 OR F.facEstado = 6)
                    AND F.vtaid = " . $id_recibido . "
                    ;");
    
            if ($quer->num_rows > 0) {
                $row = $quer->fetch_assoc();
        
                $prefijo = $row['PrfId'];
                $numDoc = $row['VtaNum'];
                $fecha_hora_venta = $row['vtafec'] . " " . $row['vtahor'];
                $nombre = $row['TerNom'];
                $razon = $row['TerRaz'];
                $ciudad = $row['CiuNom'];
                $vendedor = $row['VenNom'];
                $alistador = $row['NombresAlistador'] . " " . $row['ApellidosAlistador'];
                $fecha_hora_alistado = $row['fecha_y_hora_alistado'];
                $verificador = $row['NombresVerificador'] . " " . $row['ApellidosVerificador'];
                $fecha_hora_verificado = $row['fecha_y_hora_verificado'];
                $observaciones = $row['facObservaciones'];
    
            } else {
                echo "No se encontro la factura.";
            }

            $quer2 = $con->query("SELECT * FROM Productos WHERE vtaid = " . $id_recibido . ";");

            $datosProductos = array();
    
            while ($columna = $quer2->fetch_assoc()) {
                $row['id'] = $columna['VtaDetId'];
                $row['item'] = $columna['ProId'];
                $row['descripcion'] = $columna['ProNom'];
                $row['ubicacion'] = $columna['ProUbica'];
                $row['presentacion'] = $columna['ProPresentacion'];
                $row['cantidad'] = $columna['VtaCant'];
                $row['alistado'] = $columna['AlisCant'];
                $row['verificado'] = $columna['VerCant'];
    
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
                width: 70%;
                max-width: 800px;
                margin: 0 auto;
            }
        </style>

    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "Entrega";
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
                                        <th>Fecha y Hora Venta</th>
                                        <th>Nombre Cliente</th>
                                        <th>Razón Social</th>
                                        <th>Ciudad</th>
                                        <th>Vendedor</th>
                                        <th>Alistador</th>
                                        <th>Fecha y Hora Alistado</th>
                                        <th>Verificador</th>
                                        <th>Fecha y Hora Verificado</th>
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
                                        <td data-label="FechaHoraAlistado"><?php echo $fecha_hora_alistado ?></td>
                                        <td data-label="Verificador"><?php echo $verificador ?></td>
                                        <td data-label="FechaHoraVerificado"><?php echo $fecha_hora_verificado ?></td>
                                    </tr>
                                    <!-- <tr>
                                        <td colspan="2"><strong>OBSERVACIONES</strong></td>
                                        <td colspan="7"><?php //echo $observaciones ?></td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <input id ="idFactura" type = "hidden" value = <?php echo $id_recibido?>>
                        <input id ="cedulaUsuario" type = "hidden" value = <?php echo $_SESSION["cedula"]?>>
                        <!-- <div class="d-grid gap-2">
                            <button id="botonPendiente" class="btn btn-warning primeButton" type="button">Pendiente</button>
                        </div> -->
                        <div class="d-grid gap-2">
                            <button id="botonCerrar" class="btn btn-success primeButton" type="button">Cerrar</button>
                        </div>
                        <br>
                    </div>
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
        <script src="scripts/entrega.js"></script>
        <!-- Incluye la biblioteca jQuery -->
        <script src="js/jquery-3.6.0.min.js"></script>
        <?php
            include('partes/foot.php')
        ?>  
    </body>
</html>