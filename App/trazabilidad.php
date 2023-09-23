<?php
    session_start(); 	

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
?>
<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
        <style>
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
        </style>
    </head>
    <body>
        <div class="layout has-sidebar fixed-sidebar fixed-header">
            <?php
                $activado = "Trazabilidad";
                include('partes/sidebar.php')
            ?>  
            <div id="overlay" class="overlay"></div>
            <div class="layout">
                <main class="content">
                    <br>
                    <h2>Consulta de trazabilidad</h2>
                    <br>
                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <div style="width: 45%;">
                            <label for="prefijo"><strong>Prefijo</strong></label>
                            <input type="text" id="prefijo" name="prefijo">
                        </div>
                        <div style="width: 45%;">
                            <label for="documento"><strong># Documento</strong></label>
                            <input type="documento" id="documento" name="documento">
                        </div>
                    </div>
                    <br>
                    <button type="button" class="btn btn-success" onclick="buscar();">Buscar</button>
                    <br>
                    <br>
                    <table class="invisible-table">
                        <tr>
                            <th id="factura-prefijo">Factura - Prefijo</th>
                            <th id="nombre-razon-social">Nombre - Razón Social</th>
                            <th id="hora-factura">Hora Factura</th>
                        </tr>
                        <tr>
                            <td id="datos-factura"></td>
                            <td id="datos-nombre"></td>
                            <td id="datos-hora"></td>
                        </tr>
                        <tr>
                            <th id="vendedor">Vendedor</th>
                            <th id="alistador">Alistador - Hora FECHA alistamiento</th>
                            <th id="duracion-alistamiento">Duración Alistamiento</th>
                        </tr>
                        <tr>
                            <td id="datos-vendedor"></td>
                            <td id="datos-alistador"></td>
                            <td id="datos-duracion-alistamiento"></td>
                        </tr>
                        <tr>
                            <th id="verificador">Verificador - Hora FECHA verificación</th>
                            <th id="duracion-verificacion">Duración verificación</th>
                            <th id="entregado">Entregado - hora FECHA entrega</th>
                        </tr>
                        <tr>
                            <td id="datos-verificador"></td>
                            <td id="datos-duracion-verificacion"></td>
                            <td id="datos-entregado"></td>
                        </tr>
                        <tr>
                            <th id="duracion-entrega">DURACION ENTREGA</th>
                            <th id="embalaje">EMBALAJE</th>
                            <th id="total-embalajes">Total de embalajes</th>
                        </tr>
                        <tr>
                            <td id="datos-duracion-entrega"></td>
                            <td id="datos-embalaje"></td>
                            <td id="datos-total-embalajes"></td>
                        </tr>
                        <tr>
                            <th colspan="2" id="estado-documento">Estado documento</th>
                            <td id="datos-estado-documento"></td>
                        </tr>
                    </table>
                </main>
            </div>
        </div>
        <?php
            include('partes/foot.php')
        ?>  
        <!-- Incluye la biblioteca jQuery -->
        <script src="js/jquery-3.6.0.min.js"></script>
        <!-- Incluye la biblioteca DataTables -->
        <script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>

        <script>
            function buscar(){

                var dataToSend = {
                    prefijo: $('#prefijo').val(),
                    documento: $('#documento').val()
                };

                // Configuración de la solicitud
                var requestOptions = {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(dataToSend)
                };

                fetch('controladores/buscarFactura.php', requestOptions)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta de la red');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if(data.status == 1){
                            $("#datos-factura").text(data.datos.PrfId + " " + data.datos.VtaNum);
                            $("#datos-nombre").text(data.datos.TerNom);
                            $("#datos-hora").text(data.datos.vtahor);
                            $("#datos-vendedor").text(data.datos.VenNom);
                            
                            if(data.datos.NombresAlistador != 'null'){
                                $("#datos-alistador").text(data.datos.NombresAlistador + " " + data.datos.ApellidosAlistador + " - " + data.datos.FinAlistamiento);
                            }
                            if(data.datos.duracionAlistamiento != null){
                                $("#datos-duracion-alistamiento").text(data.datos.duracionAlistamiento + " minutos");
                            }
                            if(data.datos.NombresVerificador != null){
                                $("#datos-verificador").text(data.datos.NombresVerificador + " " + data.datos.ApellidosVerificador + " - " + data.datos.FinVerificacion);
                            }
                            if(data.datos.duracionVerificacion != null){
                                $("#datos-duracion-verificacion").text(data.datos.duracionVerificacion + " minutos");
                            }
                            if(data.datos.NombresEntregador != null){
                                $("#datos-entregado").text(data.datos.NombresEntregador + " " + data.datos.ApellidosEntregador + " - " + data.datos.FinEntrega);
                            }
                            if(data.datos.duracionEntrega != null){
                                $("#datos-duracion-entrega").text(data.datos.duracionEntrega + " minutos");
                            }
                            if(data.datos.embalaje != null){
                                $("#datos-embalaje").text(data.datos.embalaje);
                            }
                            if(data.datos.embalaje != null){
                                $("#datos-total-embalajes").text(sumarNumerosEnLista(data.datos.embalaje));
                            }
                            estado = data.datos.estado;

                            ObservacionesFor = data.datos.ObservacionesFor;

                            if (data.datos.Forzado == 1){
                                estado = estado + ", Forzado por: " + (ObservacionesFor);
                            }

                            $("#datos-estado-documento").text(estado);
                            
                        }else if(data.status == 2){
                            Swal.fire({
                                title: 'Datos no proporcionados',
                                icon: 'error',
                                confirmButtonText: 'Entendido'
                            });
                        }else if(data.status == 3){
                            Swal.fire({
                                title: 'Factura no encontrada',
                                icon: 'error',
                                confirmButtonText: 'Entendido'
                            });
                        }
                    })
                    .catch(error => {
                        alert('Error con la conexión a la base de datos' + error);
                    });

            }
            function sumarNumerosEnLista(lista) {
                let sumaTotal = 0;
                const partes = lista.split('#');
                for (const parte of partes) {
                    if (parte.includes(':')) {
                        const [, numeroStr] = parte.split(':');
                        sumaTotal += parseInt(numeroStr.trim(), 10);
                    }
                }
                
                return sumaTotal;
            }
        </script>
    </body>
</html>