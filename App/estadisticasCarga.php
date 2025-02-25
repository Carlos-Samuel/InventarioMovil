<?php
    session_start(); 	
    date_default_timezone_set('America/Bogota');

    if (!isset($_SESSION["cedula"]) || !isset($_SESSION["nombres"])) {
        header("Location: index.php");
        exit();
    }

    $permiso1 = "Admin";
    $permiso2 = "Reportes";

    if (!(strpos($_SESSION['permisos'], $permiso1) || strpos($_SESSION['permisos'], $permiso2))) {
        header("Location: dashboard.php");
        exit();
    }

    require_once 'controladores/Connection.php';

    try{

        $con = Connection::getInstance()->getConnection();
        $quer = $con->query("SELECT facEstado, COUNT(*) AS num FROM Facturas GROUP BY facEstado;");

        $datosProductos = array();

        $datosProductos[1] = 0;
        $datosProductos[2] = 0;
        $datosProductos[3] = 0;
        $datosProductos[4] = 0;
        $datosProductos[5] = 0;
        $datosProductos[6] = 0;

        while ($columna = $quer->fetch_assoc()) {
            $datosProductos[$columna['facEstado']] = $columna['num'];

        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

?>
<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <link rel="stylesheet" href="css_individuales/alistamiento.css">
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
        <style>
            /* Estilos para la tabla */
            .tabla-container {
                display: flex;
                justify-content: center;
            }

            .invisible-table {
                border-collapse: collapse;
                width: 100%;
            }

            .invisible-table th, .invisible-table td {
                border: none;
                padding: 8px;
                text-align: left;
            }

            .invisible-table th:last-child,
            .invisible-table td:last-child {
                border-right: none;
            }

            /* Estilos para el botón con la clase "boton-estilizado" */
            .boton-estilizadoDetalles {
                background-color: green;
                color: white;
                font-size: 18px;
                padding: 10px 20px;
                border: none;
                cursor: pointer;
                border-radius: 5px;
            }

            .boton-estilizadoDetalles:hover {
                background-color: darkgreen;
            }

        </style>
    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "EstadisticasCarga";
                include('partes/sidebar.php');
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <br>
                    <br>
                    <table class="invisible-table">
                        <tr>
                            <td>Alistar</td>
                            <td><?php echo $datosProductos[1] + $datosProductos[2]; ?></td>
                            <td><button class="boton-estilizadoDetalles" type="button" id ="botonA">Detalles</button></td>
                        </tr>
                        <tr>
                            <td>Verificar</td>
                            <td><?php echo $datosProductos[3] + $datosProductos[4]; ?></td>
                            <td><button class="boton-estilizadoDetalles" type="button" id ="botonV">Detalles</button></td>
                        </tr>
                        <tr>
                            <td>Empaquetar</td>
                            <td><?php echo $datosProductos[5] + $datosProductos[6]; ?></td>
                            <td><button class="boton-estilizadoDetalles" type="button" id ="botonE" >Detalles</button></td>
                        </tr>
                    </table>
                    <br>
                    <br>
                    <br>
                    <div class="table" id = "contenidoEscritorio">
                        <table id="tablaEstadisticasCarga">
                            <thead>
                                <tr>
                                    <th>Prefijo - #Factura</th>
                                    <th>Fecha de la factura</th>
                                    <th>Hora de la factura</th>
                                    <th>Nombre cliente</th>
                                    <th>Razón social</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <br>
                </main>
            </div>
        </div>
        <!-- Incluye la biblioteca jQuery -->
        <script src="js/jquery-3.6.0.min.js"></script>
        <!-- Incluye la biblioteca DataTables -->
        <script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>
        <script>

            var botonA = document.getElementById('botonA');
            var botonV = document.getElementById('botonV');
            var botonE = document.getElementById('botonE');

            botonA.addEventListener('click', function() {
                cargarTabla(1);
            });

            botonV.addEventListener('click', function() {
                cargarTabla(2);
            });
        
            botonE.addEventListener('click', function() {
                cargarTabla(3);
            });

            function cargarTabla(numero){

                numeroActual = numero;

                console.log(numeroActual);

                tablaEstadisticas.ajax.reload();

            }

            //$(document).ready( function () {

                var controlador = 1;
                
                var numeroActual = 0;

                var tablaEstadisticas = $('#tablaEstadisticasCarga').DataTable({
                    destroy: true,
                    responsive: true,
                    processing: true,
                    pageLength: 10,
                    ajax: {
                        url: 'core/tabla_estadisticas_carga.php',
                        type: 'GET',
                        data: function(data) {
                            data.numero = numeroActual;
                            // data.numero = 1;
                        }
                    },
                    language: {
                        lengthMenu: '',
                        search: 'Buscar',
                        zeroRecords: 'Ningún Resultado',
                        emptyTable: "Ningún dato disponible en esta tabla",
                        info: 'De _START_ A _END_ De Un Total De _TOTAL_',
                        infoEmpty: 'Ningún Resultado',
                        infoFiltered: '(Filtrando _MAX_ En Total)',
                        loadingRecords: 'Cargando',
                        paginate: {
                            first: 'Primero',
                            last: 'Último',
                            next: 'Siguiente',
                            previous: 'Anterior'
                        },
                    },
                    columns: [
                        {data: 'id', name:'id', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'fecha', name:'fecha', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'hora', name:'hora', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'cliente', name:'cliente', orderable: true, searchable: true, className: 'dt-body-center'},
                        {data: 'razon', name:'razon', orderable: true, searchable: true, className: 'dt-body-center'}
                    ],

                });

            //});

        </script>
        <!-- Incluye la biblioteca jQuery -->
        <script src="js/jquery-3.6.0.min.js"></script>
        <?php
            include('partes/foot.php')
        ?>  
    </body>
</html>