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
            $quer = $con->query("SELECT * FROM Facturas WHERE vtaid = " . $id_recibido . ";");
    
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

            $quer2 = $con->query(
                "SELECT COUNT(*) AS numero 
                    FROM Productos 
                    WHERE vtaid =  $id_recibido 
                ");

            if ($quer2) {
                $resultado = $quer2->fetch_assoc();
                
                if ($resultado) {
                    $mensajeNumero = "Esta factura tiene " . $resultado['numero'] . " registro(s).";
                } else {
                    $mensajeNumero = "No se encontraron registros para el ID recibido.";
                }
            } else {
                echo "Hubo un error en la consulta SQL.";
            }
    
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
                                        <td data-label="NombreCliente"><?php echo utf8_encode($nombre) ?></td>
                                        <td data-label="RazonSocial"><?php echo utf8_encode($razon) ?></td>
                                        <td data-label="Ciudad"><?php echo utf8_encode($ciudad) ?></td>
                                        <td data-label="Vendedor"><?php echo utf8_encode($vendedor) ?></td>
                                        <td data-label="HoraDoc"><?php echo $hora ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <h5><?php echo $mensajeNumero;?></h5>
                        <br>
                        <h5>¿Esta seguro de que no desea procesar esta factura?</h5>
                        <br>
                        <h5>De ser asi, ingrese una justificacion</h5>
                        <br>
                        <form id="formularioClave" action="controladores/guardarNoProcesar.php" method="post">
                            <input type ="hidden" id = "idFactura" name = "idFactura" value = "<?php echo $id_recibido; ?>" >
                    
                            <input type="text" id="justificacion" name="justificacion" value = "" required maxlength="120">

                            <button id="botonForzado" class="btn btn-info primeButton" type="submit">No Procesar</button>
                        </form>
                    </div>
                    <div class="d-grid gap-2">
                    </div>
                    <br>
                </main>
            </div>
        </div>
        <!-- Incluye la biblioteca jQuery -->
        <script src="js/jquery-3.6.0.min.js"></script>
        <!-- <script src="path/to/honeywell-sdk.js"></script> -->
        <?php
            include('partes/foot.php')
        ?>  
    </body>
</html>