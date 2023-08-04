<!doctype html>
<html lang="en" data-bs-theme="auto">
    <head>
        <?php
            include('partes/head.php')
        ?>
        <style>
            body {
                font-family: arial;
            }

            table {
                border: 1px solid #ccc;
                width: 100%;
                margin:0;
                padding:0;
                border-collapse: collapse;
                border-spacing: 0;
            }

            table tr {
                border: 1px solid #ddd;
                padding: 5px;
            }

            table th, table td {
                padding: 10px;
                text-align: center;
            }

            table th {
                text-transform: uppercase;
                font-size: 14px;
                letter-spacing: 1px;
            }

            .input-container {
                text-align: right;
            }

            .input-container input{
                width: 50px;
                display: inline-block;
            }

            .buscador {
                text-align: center;
            }

            #busqueda {
                max-width: 200px;
                width: 100%; 
                margin: 0 auto;
            }

            .btn {
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                margin: 10px;
            }

            /* Estilos para el diálogo */
            .dialogo {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: white;
                padding: 20px;
                border-radius: 6px;
                box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.3);
            }

            #dialogoConfirmacionCerrar {
                display: none; /* Ocultar el diálogo inicialmente */
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: white;
                padding: 20px;
                border-radius: 6px;
                box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.3);
            }

            /* Estilos para los botones dentro del diálogo específico */
            #dialogoConfirmacionCerrar button {
                margin: 10px;
            }

            /* Estilos para la alineación de los botones en una fila */
            #dialogoConfirmacionCerrar .boton-container {
                display: flex;
                justify-content: center;
            }

            #dialogoConfirmacionForzado {
                display: none; /* Ocultar el diálogo inicialmente */
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: white;
                padding: 20px;
                border-radius: 6px;
                box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.3);
            }

            /* Estilos para los botones dentro del diálogo específico */
            #dialogoConfirmacionForzado button {
                margin: 10px;
            }

            /* Estilos para la alineación de los botones en una fila */
            #dialogoConfirmacionForzado .boton-container {
                display: flex;
                justify-content: center;
            }

            @media screen and (max-width: 600px) {

                table {
                    border: 0;
                }

                table thead {
                    display: none;
                }

                table tr {
                    margin-bottom: 10px;
                    display: block;
                    border-bottom: 2px solid #ddd;
                }

                table td {
                    display: block;
                    text-align: right;
                    font-size: 13px;
                    border-bottom: 1px dotted #ccc;
                }

                table td:last-child {
                    border-bottom: 0;
                }

                table td:before {
                    content: attr(data-label);
                    float: left;
                    text-transform: uppercase;
                    font-weight: bold;
                }

                .dialogo {
                    width: 80%;
                }
            }

        </style>
    </head>
    <body>
        <?php
            include('partes/header.php')
        ?>
            <div class = "container fullwidth mt-5"> 
                <br>
                <a href = "lista_alistamiento.php" ><button class="btn btn-primary primeButton" type="button">Volver</button></a>
                <br>
                <br>
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
                                    <td data-label="Prefijo">RMV</td>
                                    <td data-label="Doc">1234</td>
                                    <td data-label="Fecha">01/11/2021</td>
                                    <td data-label="NombreCliente">Sofia Pérez</td>
                                    <td data-label="RazonSocial">Tienda Sofia</td>
                                    <td data-label="Ciudad">Villavicencio</td>
                                    <td data-label="Vendedor">Carlos</td>
                                    <td data-label="HoraDoc">8:30 AM</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div class="d-grid gap-2">
                    <button class="btn btn-danger primeButton" type="button">Pendiente</button>
                </div>
                <div class="d-grid gap-2">
                    <button id="botonCerrar" class="btn btn-warning primeButton" type="button">Cerrar</button>
                </div>
                <div class="d-grid gap-2">
                    <button id="botonDevolver" class="btn btn-secondary primeButton" type="button">Devolver</button>
                </div>
                <div class="d-grid gap-2">
                    <button id="botonForzado" class="btn btn-info primeButton" type="button">Cierre forzado</button>
                </div>
                <br>
                <div class="buscador">
                    <input type="text" id="busqueda" placeholder="" style="max-width: 400px;">
                    <br>
                    <a class="btn btn-primary primeButton"  onclick="vaciarEspacioTexto()" role = "button">Vaciar</a>
                </div>
                <br>
                <br>
                <div class="col-sm-12">
                    <div class="table">
                        <table>
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
                                <tr>
                                    <td data-label="Item">010101</td>
                                    <td data-label="Descripcion">Galón pintura</td>
                                    <td data-label="Ubicacion">P1 E2 E3</td>
                                    <td data-label="Presentacion">Galón</td>
                                    <td data-label="Cantidad">2</td>
                                    <td data-label="Alistado" class="input-container"><input type="number" id="numero" name="numero" value="2"></td>
                                    <td data-label="Diferencia">0</td>
                                </tr>
                                <tr>
                                    <td data-label="Item">100109</td>
                                    <td data-label="Descripcion">Puntilla de pulgada</td>
                                    <td data-label="Ubicacion">P2 E4 E1</td>
                                    <td data-label="Presentacion">Caja</td>
                                    <td data-label="Cantidad">2</td>
                                    <td data-label="Alistado" class="input-container"><input type="number" id="numero" name="numero" value="2"></td>
                                    <td data-label="Diferencia">0</td>
                                </tr>
                                <tr>
                                    <td data-label="Item">200234</td>
                                    <td data-label="Descripcion">Varilla</td>
                                    <td data-label="Ubicacion">P5 E5 E6</td>
                                    <td data-label="Presentacion">Unidad</td>
                                    <td data-label="Cantidad">50</td>
                                    <td data-label="Alistado" class="input-container"><input type="number" id="numero" name="numero" value="48"></td>
                                    <td data-label="Diferencia">2</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="dialogoConfirmacionCerrar" class="dialogo">
                <p>¿Estás seguro de que desea cerrar el proceso?</p>
                <div class="boton-container">
                    <button id="confirmarCerrar" class="btn btn-success primeButton">Aceptar</button>
                    <button id="cancelarCerrar" class="btn btn-danger primeButton">Cancelar</button>
                </div>
            </div>
            <div id="dialogoConfirmacionForzado" class="dialogo">
                <p>¿Estás seguro de que desea cerrar el proceso?</p>
                <input type="password" placeholder="Ingrese la clave de usuario">
                <div class="boton-container">
                    <button id="confirmarForzado" class="btn btn-success primeButton">Aceptar</button>
                    <button id="cancelarForzado" class="btn btn-danger primeButton">Cancelar</button>
                </div>
            </div>
        <?php
            include('partes/foot.php')
        ?>
        <script>
            function vaciarEspacioTexto() {
                document.getElementById('busqueda').value = '';
            }
            const botonConfirmar = document.getElementById('botonConfirmar');
            const botonCerrar = document.getElementById('botonCerrar');
            const botonForzado = document.getElementById('botonForzado');
            const dialogoConfirmacionCerrar = document.getElementById('dialogoConfirmacionCerrar');
            const dialogoConfirmacionForzado = document.getElementById('dialogoConfirmacionForzado');
        
            const btnAceptarCerrar = document.getElementById('confirmarCerrar');
            const btnCancelarCerrar = document.getElementById('cancelarCerrar');

            const btnAceptarForzado = document.getElementById('confirmarForzado');
            const btnCancelarForzado = document.getElementById('cancelarForzado');

            botonCerrar.addEventListener('click', mostrarDialogoCerrar);
            botonForzado.addEventListener('click', mostrarDialogoForzado);

            btnAceptarCerrar.addEventListener('click', confirmarAccionCerrar);
            btnCancelarCerrar.addEventListener('click', ocultarDialogo);

            btnAceptarForzado.addEventListener('click', confirmarAccionForzado);
            btnCancelarForzado.addEventListener('click', ocultarDialogo);

            function mostrarDialogoCerrar() {
                dialogoConfirmacionCerrar.style.display = 'block';
            }

            function mostrarDialogoForzado() {
                dialogoConfirmacionForzado.style.display = 'block';
            }

            function ocultarDialogo() {
                dialogoConfirmacionCerrar.style.display = 'none';
                dialogoConfirmacionForzado.style.display = 'none';
            }

            function confirmarAccionCerrar() {
                ocultarDialogo();
                window.location.href = 'lista_alistamiento.php';
            }

            function confirmarAccionForzado() {
                ocultarDialogo();
                window.location.href = 'lista_alistamiento.php';
            }

        </script>   
    </body>
</html>