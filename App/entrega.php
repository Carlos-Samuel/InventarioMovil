<?php


    include_once 'controladores/funciones.php';

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

                utf8_encode_array($row);
        
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
                $observaciones = $row['ObservacionesVer'];
                $embalaje = $row['Embalaje'];
    
            } else {
                echo "No se encontro la factura.";
            }

            $horaLocal = date('Y-m-d H:i:s');
            $sql = "UPDATE Facturas SET InicioEntrega = '$horaLocal' WHERE vtaid = $id_recibido AND InicioEntrega IS NULL";
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
                                    <tr>
                                        <td colspan="2"><strong>OBSERVACIONES</strong></td>
                                        <td colspan="9"><?php echo $observaciones ?></td>
                                    </tr> 
                                    <tr>
                                        <td colspan="2"><strong>EMBALAJE</strong></td>
                                        <td colspan="9"><?php echo $embalaje ?></td>
                                    </tr> 
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <input id ="idFactura" type = "hidden" value = <?php echo $id_recibido?>>
                        <input id ="cedulaUsuario" type = "hidden" value = <?php echo $_SESSION["cedula"]?>>
                        <div class="d-grid gap-2">
                            <button id="botonCerrar" class="btn btn-success primeButton" type="button">Cerrar</button>
                        </div>
                        <br>
                    </div>
                </main>
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