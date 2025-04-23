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

            #prefijo, #documento {
                font-size: 18px; /* Aumenta el tamaño de la letra */
                margin: 20px; /* Espacio exterior */
                border: 2px solid black; /* Borde negro de 2px de grosor */
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
                            <h1>Prefijo</h1>
                            <input type="text" id="prefijo" name="prefijo">
                        </div>
                        <div style="width: 45%;">
                            <h1># Documento</h1>
                            <!--<label for="documento"><strong># Documento</strong></label>-->
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
                            <th id="hora-factura">Fecha y Hora Factura</th>
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
                            <th id="entregado">Empaquetado - hora FECHA empaquetado</th>
                        </tr>
                        <tr>
                            <td id="datos-verificador"></td>
                            <td id="datos-duracion-verificacion"></td>
                            <td id="datos-entregado"></td>
                        </tr>
                        <tr>
                            <th id="duracion-entrega">DURACION EMPAQUETADO</th>
                            <th id="embalaje">EMBALAJE</th>
                            <th id="total-embalajes">Total de embalajes</th>
                        </tr>
                        <tr>
                            <td id="datos-duracion-entrega"></td>
                            <td id="datos-embalaje"></td>
                            <td id="datos-total-embalajes"></td>
                        </tr>
                        <tr>
                            <th id="Nitems">NUMERO ITEMS</th>
                            <th id="tiempoAlistamientoItems">T. ALISTAMIENTO PROMEDIO POR ITEM</th>
                            <th id="tiempoVerificacionItems">T. VERIFICACION PROMEDIO POR ITEM</th>
                        </tr>
                        <tr>
                            <td id="datos-numero-items"></td>
                            <td id="datos-tiempo-alistamiento-items"></td>
                            <td id="datos-tiempo-verificacion-items"></td>
                        </tr>
                        <tr>
                            <th colspan="2" id="estado-documento">Estado documento</th>
                            <td id="datos-estado-documento"></td>
                        </tr>
                    </table>

                    <div id="tablaEvidencias" class="mt-4"></div>


                </main>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal" id="modalPreview" style="
            max-width: 900px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1055;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        ">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Vista previa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar" onclick="ocultarPreview()"></button>
                </div>

                <div class="modal-body text-center" id="contenidoModal">
                    <!-- Contenido dinámico aquí -->
                </div>

            </div>
        </div>



        <?php
            include('partes/foot.php')
        ?>  
        <!-- Incluye la biblioteca jQuery -->
        <script src="js/jquery-3.6.0.min.js"></script>
        <!-- Incluye la biblioteca DataTables -->
        <script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>
        <script src="bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js"></script>

        <script>

            modalPreview = document.getElementById("modalPreview"); 

            function ocultarPreview() {
                modalPreview.style.display = 'none';

                document.getElementById("modalPreview").innerHTML = '';
            }

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
                            $("#datos-hora").text(data.datos.vtafec + " " + data.datos.vtahor);
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
                            if(data.datos.items != null){
                                $("#datos-numero-items").text(data.datos.items);
                                if(data.datos.duracionAlistamiento != null){
                                    $("#datos-tiempo-alistamiento-items").text((data.datos.duracionAlistamiento / data.datos.items).toFixed(2) + " minutos");
                                }
                                if(data.datos.duracionVerificacion != null){
                                    $("#datos-tiempo-verificacion-items").text((data.datos.duracionVerificacion / data.datos.items).toFixed(2) + " minutos");
                                }
                            }
                            estado = data.datos.estado;

                            ObservacionesFor = data.datos.ObservacionesFor;

                            if (data.datos.Forzado == 1){
                                estado = estado + ", Forzado por: " + (ObservacionesFor);
                            }

                            $("#datos-estado-documento").text(estado);
                            
                            PrfCod = $('#prefijo').val();
                            VtaNum = $('#documento').val();

                            cargarTabla();


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
                        }else if(data.status == 4){
                            Swal.fire({
                                title: 'Factura de consulta',
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

            let PrfCod, VtaNum;


            function cargarTabla() {
                $.post('controladores/obtenerEvidencias2.php', { PrfCod, VtaNum }, function (response) {
                    if (response.exito) {
                        $('#tablaEvidencias').html(response.html);
                    } else {
                        alert("Error al cargar evidencias: " + response.mensaje);
                    }
                }, 'json')
                .fail(function () {
                    alert("Error en la comunicación con el servidor.");
                });
            }


            $(document).on('click', '.verArchivo', function () {
                const tipo = $(this).data('tipo');
                const src = $(this).data('src');
                
                const contenido = tipo === 'imagen'
                    ? `<img src="${src}" class="img-fluid rounded">`
                    : `<video controls autoplay class="w-100 rounded"><source src="${src}" type="video/mp4"></video>`;

                $('#contenidoModal').html(contenido);

                // Cierra cualquier instancia anterior
                const modalElement = document.getElementById('modalPreview');
                const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                modalPreview.style.display = "block";
            });



        </script>
    </body>
</html>